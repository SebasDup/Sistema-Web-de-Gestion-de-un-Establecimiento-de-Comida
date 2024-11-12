<?php
class EmpleadoModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerEmpleados() {
        $stmt = $this->pdo->query("
            SELECT 
                usuarios.id, 
                usuarios.nombre, 
                usuarios.apellidoP,
                usuarios.apellidoM, 
                usuarios.email, 
                usuarios.contrasena,
                empleados.puesto, 
                empleados.fecha_contratacion, 
                empleados.salario, 
                empleados.servicios_realizados, 
                empleados.zona_asignada 
            FROM 
                usuarios 
            INNER JOIN 
                empleados 
            ON 
                usuarios.id = empleados.usuario_id 
            WHERE 
                usuarios.tipo = 'empleado'
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo empleado
    public function agregarEmpleado($nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo, $puesto, $fecha_contratacion, $salario, $zona_asignada) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, contrasena, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo])) {
            $usuario_id = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("INSERT INTO empleados (usuario_id, puesto, fecha_contratacion, salario, zona_asignada) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$usuario_id, $puesto, $fecha_contratacion, $salario, $zona_asignada])) {
                $_SESSION['mensaje'] = "Empleado: ".$nombre." ".$apellidoP." ".$apellidoM.", Agregado exitosamente.";
            } else {
                $_SESSION['error'] = "Error al agregar el empleado para el usuario: ".$nombre." ".$apellidoP . " ".$apellidoM;
            }
        } else {
            $_SESSION['error'] = "Error al agregar el empleado: ".$nombre." ".$apellidoP . " ".$apellidoM;
        }
    }

    // Método para verificar si un email ya existe
    public function emailEsPropio($id,$email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id = ?");
        $stmt->execute([$email, $id]);
        return $stmt->fetchColumn() > 0;
    }

    // Método para verificar si un email ya existe
    public function emailExiste($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    // Método para actualizar un empleado existente
    public function actualizarEmpleado($id, $nombre, $apellidoP, $apellidoM, $email, $contrasena, $puesto, $fecha_contratacion, $salario, $zona_asignada) {
        try {
            $this->pdo->beginTransaction();
            // Actualizar datos en la tabla usuarios
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, apellidoP = ?, apellidoM = ?, email = ?, contrasena = ? WHERE id = ?");
            if (!$stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $id])) {
                throw new Exception("Error al actualizar el usuario: ".$nombre." ".$apellidoP . " ". $apellidoM . " con id: ".$id);        
            }
            $stmt = $this->pdo->prepare("UPDATE empleados SET puesto = ?, fecha_contratacion = ?, salario = ?, zona_asignada = ? WHERE usuario_id = ?");
            // Actualizar datos en la tabla empleados
            if (!$stmt->execute([$puesto, $fecha_contratacion, $salario, $zona_asignada, $id])) {
                throw new Exception("Error al actualizar el empleado: ".$nombre." ".$apellidoP . " ". $apellidoM . " con id: ".$id);
            }
            $this->pdo->commit();
            $_SESSION['mensaje'] = "Empleado Actualizado exitosamente a: ".$nombre." ".$apellidoP ." ". $apellidoM ." con email: ".$email;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->pdo->rollBack();
        }
     }

    // Método para eliminar un empleado
    public function eliminarEmpleado($id, $nombre, $apellidoP, $apellidoM) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Empleado Id: ". $id ." ". $nombre ." ". $apellidoP ." ". $apellidoM .", Eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el empleado: ". $id ." ". $nombre ." ". $apellidoP ." ". $apellidoM;
        }
    }
}
?>
