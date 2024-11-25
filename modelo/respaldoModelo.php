<?php
class RespaldoModelo {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            
            // Asegurar que la tabla bitacora_db existe
            $this->crearTablaBitacora();
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    private function crearTablaBitacora() {
        $sql = "CREATE TABLE IF NOT EXISTS bitacora_db (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_usuario VARCHAR(255) NOT NULL,
            tipo_operacion VARCHAR(50) NOT NULL,
            nombre_archivo VARCHAR(255) NOT NULL,
            fecha_operacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $this->pdo->exec($sql);
        
        // Establecer zona horaria correcta
        $this->pdo->exec("SET time_zone = '+00:00'");
    }

    private function registrarBitacora($tipo_operacion, $nombre_archivo) {
        try {
            if (!$this->pdo->inTransaction()) {
                $this->pdo->beginTransaction();
            }
            
            $stmt = $this->pdo->prepare("INSERT INTO bitacora_db (nombre_usuario, tipo_operacion, nombre_archivo) VALUES (?, ?, ?)");
            $resultado = $stmt->execute([$_SESSION['usuario'], $tipo_operacion, $nombre_archivo]);
            
            if (!$resultado) {
                throw new Exception("Error al registrar en bitácora");
            }
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Error al registrar en bitácora: " . $e->getMessage());
        }
    }

    public function realizarRespaldo() {
        try {
            $nombre_archivo = 'backup_' . date("Y-m-d_H-i-s") . '.sql';
            $respaldo = "-- Respaldo de la base de datos restaurante_db\n";
            $respaldo .= "-- Fecha: " . date("Y-m-d H:i:s") . "\n\n";
            $respaldo .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $respaldo .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $respaldo .= "SET time_zone = \"+00:00\";\n\n";
            
            $respaldo .= "DROP DATABASE IF EXISTS `restaurante_db`;\n";
            $respaldo .= "CREATE DATABASE `restaurante_db`;\n";
            $respaldo .= "USE `restaurante_db`;\n\n";

            // Obtener y respaldar estructura de tablas y datos
            $tablas = array();
            $resultado = $this->pdo->query("SHOW TABLES");
            while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
                $tablas[] = $fila[0];
            }

            // Respaldar estructura de tablas
            foreach ($tablas as $tabla) {
                $resultado = $this->pdo->query("SHOW CREATE TABLE $tabla");
                $fila = $resultado->fetch(PDO::FETCH_NUM);
                $respaldo .= "\n\n" . $fila[1] . ";\n\n";
            }

            // Respaldar datos de las tablas
            foreach ($tablas as $tabla) {
                $resultado = $this->pdo->query("SELECT * FROM $tabla");
                while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
                    $respaldo .= "INSERT INTO `$tabla` VALUES(";
                    foreach ($fila as $i => $valor) {
                        if ($valor === null) {
                            $respaldo .= "NULL";
                        } else {
                            $valor = addslashes($valor);
                            $valor = str_replace("\n", "\\n", $valor);
                            $respaldo .= "'$valor'";
                        }
                        if ($i < (count($fila) - 1)) {
                            $respaldo .= ",";
                        }
                    }
                    $respaldo .= ");\n";
                }
                $respaldo .= "\n";
            }

            // Respaldar triggers
            $resultado = $this->pdo->query("SHOW TRIGGERS");
            $respaldo .= "\nDELIMITER //\n\n";
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $respaldo .= "CREATE TRIGGER `" . $fila['Trigger'] . "` " . 
                            $fila['Timing'] . " " . $fila['Event'] . 
                            " ON `" . $fila['Table'] . "`\n" .
                            "FOR EACH ROW\n" . $fila['Statement'] . "//\n\n";
            }
            $respaldo .= "DELIMITER ;\n\n";

            // Respaldar procedimientos almacenados
            $resultado = $this->pdo->query("SHOW PROCEDURE STATUS WHERE Db = 'restaurante_db'");
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $proc_name = $fila['Name'];
                $proc = $this->pdo->query("SHOW CREATE PROCEDURE $proc_name")->fetch(PDO::FETCH_ASSOC);
                $respaldo .= "\nDELIMITER //\n" . $proc['Create Procedure'] . "//\nDELIMITER ;\n\n";
            }

            // Respaldar funciones
            $resultado = $this->pdo->query("SHOW FUNCTION STATUS WHERE Db = 'restaurante_db'");
            while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $func_name = $fila['Name'];
                $func = $this->pdo->query("SHOW CREATE FUNCTION $func_name")->fetch(PDO::FETCH_ASSOC);
                $respaldo .= "\nDELIMITER //\n" . $func['Create Function'] . "//\nDELIMITER ;\n\n";
            }

            // Respaldar vistas
            $resultado = $this->pdo->query("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
            while ($fila = $resultado->fetch(PDO::FETCH_NUM)) {
                $view_name = $fila[0];
                $view = $this->pdo->query("SHOW CREATE VIEW $view_name")->fetch(PDO::FETCH_ASSOC);
                $respaldo .= "\n" . $view['Create View'] . ";\n\n";
            }

            $respaldo .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

            // Registrar en bitácora antes de enviar el archivo
            $this->registrarBitacora('respaldo', $nombre_archivo);

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $nombre_archivo);
            header('Content-Length: ' . strlen($respaldo));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            
            echo $respaldo;
            exit;
        } catch (Exception $e) {
            throw new Exception("Error en el respaldo: " . $e->getMessage());
        }
    }

    public function realizarRestauracion($filePath) {
        try {
            $nombre_archivo = basename($filePath);
            
            // 1. Hacer backup de la tabla bitacora_db y convertir fechas a UTC
            $bitacora_backup = [];
            $stmt = $this->pdo->query("SELECT id, nombre_usuario, tipo_operacion, nombre_archivo, 
                CONVERT_TZ(fecha_operacion, @@session.time_zone, '+00:00') as fecha_operacion 
                FROM bitacora_db");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bitacora_backup[] = $row;
            }

            // 2. Asegurarnos de que no hay transacción activa
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            // 3. Iniciar nueva transacción
            $this->pdo->beginTransaction();
            
            // 4. Desactivar verificación de claves foráneas
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=0');
            
            // 5. Leer y ejecutar el archivo SQL por partes
            $sql_content = file_get_contents($filePath);
            if ($sql_content === false) {
                throw new Exception("No se pudo leer el archivo de respaldo");
            }

            // 6. Dividir el SQL en comandos individuales
            $commands = [];
            $current_command = '';
            $delimiter = ';';
            $in_delimiter_change = false;

            foreach (explode("\n", $sql_content) as $line) {
                $trimmed = trim($line);
                
                // Saltar líneas vacías y comentarios
                if (empty($trimmed) || strpos($trimmed, '--') === 0) {
                    continue;
                }

                // Manejar cambios de delimitador
                if (preg_match('/^DELIMITER\s+(.+)$/', $trimmed, $matches)) {
                    if (!empty($current_command)) {
                        $commands[] = $current_command;
                    }
                    $delimiter = $matches[1];
                    $current_command = '';
                    continue;
                }

                $current_command .= $line . "\n";

                if (substr(rtrim($line), -strlen($delimiter)) === $delimiter) {
                    $commands[] = $current_command;
                    $current_command = '';
                }
            }

            // 7. Ejecutar cada comando
            foreach ($commands as $command) {
                if (!empty(trim($command))) {
                    try {
                        $this->pdo->exec($command);
                    } catch (PDOException $e) {
                        error_log("Error en comando SQL: " . $e->getMessage());
                        // Continuar con el siguiente comando
                    }
                }
            }

            // 8. Limpiar la tabla bitacora_db antes de restaurar
            $this->pdo->exec("TRUNCATE TABLE bitacora_db");

            // 9. Restaurar los registros de bitacora_db preservando IDs y fechas originales
            foreach ($bitacora_backup as $registro) {
                $stmt = $this->pdo->prepare("INSERT INTO bitacora_db (id, nombre_usuario, tipo_operacion, nombre_archivo, fecha_operacion) 
                                           VALUES (?, ?, ?, ?, CONVERT_TZ(?, '+00:00', @@session.time_zone))");
                $stmt->execute([
                    $registro['id'],
                    $registro['nombre_usuario'],
                    $registro['tipo_operacion'],
                    $registro['nombre_archivo'],
                    $registro['fecha_operacion']
                ]);
            }

            // 10. Registrar la operación actual en bitacora
            $stmt = $this->pdo->prepare("INSERT INTO bitacora_db (nombre_usuario, tipo_operacion, nombre_archivo) 
                                       VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['usuario'], 'restauracion', $nombre_archivo]);

            // 11. Reactivar verificación de claves foráneas
            $this->pdo->exec('SET FOREIGN_KEY_CHECKS=1');

            // 12. Confirmar la transacción
            if (!$this->pdo->commit()) {
                throw new Exception("Error al confirmar la transacción");
            }

            $_SESSION['success'] = "Base de datos restaurada correctamente. Se mantuvieron los registros de bitácora.";
            return true;

        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception($e->getMessage());
        }
    }
}
?>
