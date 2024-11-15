<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$password = '';
$base_datos = 'restaurante_db';

try {
    $conn = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Generar nombre del archivo
    $nombre_archivo = 'backup_' . date("Y-m-d_H-i-s") . '.sql';

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
    $stmt = $conn->prepare("INSERT INTO bitacora_db (usuario_id, nombre_usuario, tipo_operacion, nombre_archivo) VALUES (?, ?, 'respaldo', ?)");
    $stmt->execute([$usuario_id, $nombre_usuario, $nombre_archivo]);

    // Iniciar el contenido del respaldo
    $respaldo = "-- Respaldo de la base de datos $base_datos\n";
    $respaldo .= "-- Fecha: " . date("Y-m-d H:i:s") . "\n\n";
    $respaldo .= "DROP DATABASE IF EXISTS `$base_datos`;\n";
    $respaldo .= "CREATE DATABASE `$base_datos`;\n";
    $respaldo .= "USE `$base_datos`;\n\n";
    $respaldo .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    // Obtener todas las tablas
    $tablas = array();
    $resultado = $conn->query("SHOW TABLES");
    while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
        $tablas[] = $fila[0];
    }

    foreach ($tablas as $tabla) {
        // Obtener estructura
        $resultado = $conn->query("SHOW CREATE TABLE $tabla");
        $fila = $resultado->fetch(PDO::FETCH_NUM);
        $respaldo .= "\n\n" . $fila[1] . ";\n\n";

        // Obtener información de las columnas
        $columnas = array();
        $tipos = array();
        $stmt = $conn->query("DESCRIBE $tabla");
        while ($col = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columnas[] = $col['Field'];
            $tipos[] = strtolower($col['Type']);
        }

        // Obtener datos
        $resultado = $conn->query("SELECT * FROM $tabla");
        while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
            $respaldo .= "INSERT INTO `$tabla` VALUES(";
            for ($i = 0; $i < count($fila); $i++) {
                if ($fila[$i] === null) {
                    $respaldo .= "NULL";
                } else {
                    // Verificar si es un tipo numérico
                    if (strpos($tipos[$i], 'int') !== false || 
                        strpos($tipos[$i], 'decimal') !== false || 
                        strpos($tipos[$i], 'float') !== false || 
                        strpos($tipos[$i], 'double') !== false) {
                        $respaldo .= $fila[$i];
                    } else {
                        $valor = addslashes($fila[$i]);
                        $valor = str_replace("\n", "\\n", $valor);
                        $respaldo .= "'" . $valor . "'";
                    }
                }
                if ($i < (count($fila) - 1)) {
                    $respaldo .= ',';
                }
            }
            $respaldo .= ");\n";
        }
    }

    $respaldo .= "\n\nSET FOREIGN_KEY_CHECKS=1;";

    // Generar el archivo
    
    // Configurar headers para la descarga
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $nombre_archivo);
    header('Content-Length: ' . strlen($respaldo));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    
    // Enviar el contenido
    echo $respaldo;
    exit;

} catch(PDOException $e) {
    $_SESSION['error'] = "Error al crear el respaldo: " . $e->getMessage();
    header('Location: respaldo.php');
    exit;
}