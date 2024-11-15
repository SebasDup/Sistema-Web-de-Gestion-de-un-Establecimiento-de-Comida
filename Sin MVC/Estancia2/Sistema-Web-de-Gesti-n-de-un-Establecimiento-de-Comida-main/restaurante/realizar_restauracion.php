<?php
session_start();

if ($_FILES['backupFile']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['backupFile']['tmp_name'];
    $fileName = $_FILES['backupFile']['name'];
    
    // Verificar el archivo
    if (pathinfo($fileName, PATHINFO_EXTENSION) !== 'sql') {
        $_SESSION['error'] = "El archivo debe ser de tipo SQL.";
        header('Location: respaldo.php');
        exit;
    }

    try {
        // Configuración de la base de datos
        $host = 'localhost';
        $usuario = 'root';
        $password = '';
        $base_datos = 'restaurante_db';

        // Crear conexión PDO
        $conn = new PDO("mysql:host=$host", $usuario, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear directorio para historial de respaldos
        $backup_dir = 'backups/historial';
        if (!file_exists($backup_dir)) {
            mkdir($backup_dir, 0777, true);
        }

        // Guardar una copia del archivo en el historial
        $historial_path = $backup_dir . '/restore_' . date('Y-m-d_H-i-s') . '_' . $fileName;
        if (copy($tmpName, $historial_path)) {
            // Leer el archivo SQL
            $sql = file_get_contents($tmpName);
            
            // Dividir el archivo en consultas individuales
            $queries = array_filter(array_map('trim', explode(';', $sql)));
            
            // Contador de consultas ejecutadas
            $executed_queries = 0;
            
            // Ejecutar cada consulta
            foreach($queries as $query) {
                if (!empty($query)) {
                    $conn->exec($query);
                    $executed_queries++;
                }
            }

            // Verificar que la base de datos existe y tiene datos
            $conn = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $password);
            $stmt = $conn->query("SHOW TABLES");
            $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (count($tablas) > 0) {
                $_SESSION['success'] = "Base de datos restaurada correctamente.\n" .
                                     "- Se ejecutaron $executed_queries consultas\n" .
                                     "- Se restauraron " . count($tablas) . " tablas\n" .
                                     "- Backup guardado en historial como: " . basename($historial_path);
                
                // Obtener datos del usuario desde la tabla usuarios
                if (isset($_SESSION['email'])) {
                    $stmt = $conn->prepare("SELECT id, CONCAT(nombre, ' ', apellido) as nombre_completo FROM usuarios WHERE email = ?");
                    $stmt->execute([$_SESSION['email']]);
                    $usuario_data = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $usuario_id = $usuario_data['id'];
                    $nombre_usuario = $usuario_data['nombre_completo'];
                } else {
                    $usuario_id = 1; // ID del administrador por defecto
                    $nombre_usuario = 'Administrador';
                }
                
                // Registrar en bitácora
                $stmt = $conn->prepare("INSERT INTO bitacora_db (usuario_id, nombre_usuario, tipo_operacion, nombre_archivo) VALUES (?, ?, 'restauracion', ?)");
                $stmt->execute([$usuario_id, $nombre_usuario, $fileName]);
            } else {
                throw new Exception("La restauración no creó ninguna tabla.");
            }

        } else {
            throw new Exception("Error al guardar el archivo en el historial.");
        }

    } catch(PDOException $e) {
        $_SESSION['error'] = "Error en la base de datos: " . $e->getMessage();
    } catch(Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

} else {
    $_SESSION['error'] = "Error al subir el archivo. Código: " . $_FILES['backupFile']['error'];
}

header('Location: respaldo.php');
exit;