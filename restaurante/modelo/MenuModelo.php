<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class MenuModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerMenu() {
        $stmt = $this->pdo->query("SELECT * FROM menu");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar un nuevo usuario
    public function agregarUsuario($nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, contrasena, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo])) {
            $_SESSION['mensaje'] = "Usuario: ".$nombre." ".$apellidoP." ".$apellidoM.", Agregado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al agregar el usuario: ".$nombre." ".$apellidoP." ".$apellidoM;
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

    // Método para actualizar un usuario existente
    public function actualizarUsuario($id, $nombre, $apellidoP, $apellidoM, $email, $contrasena) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, apellidoP = ?, apellidoM = ?, email = ?, contrasena = ? WHERE id = ?");
        if ($stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $id])) {
            $_SESSION['mensaje'] = "Usuario Actualizado exitosamente a: ".$nombre." ".$apellidoP." ".$apellidoM ." con email: ".$email;
        } else {
            $_SESSION['error'] = "Error al actualizar el usuario: ".$nombre." ".$apellidoP." ".$apellidoM . " con id: ".$id;
        }
    }

    // Método para eliminar un usuario
    public function eliminarUsuario($id, $nombre, $apellidoP, $apellidoM) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmt->execute([$id])) {
            $_SESSION['mensaje'] = "Usuario Id: ". $id ." ". $nombre ." ". $apellidoP ." ". $apellidoM .", Eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario: ". $id ." ". $nombre ." ". $apellidoP ." ". $apellidoM;
        }
    }
}
