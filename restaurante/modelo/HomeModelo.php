<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class HomeModelo {
    // Método para agregar un nuevo usuario
    public function agregarUsuario($nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, contrasena, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo])) {
            $_SESSION['mensaje'] = "Usuario: ".$nombre." ".$apellidoP." ".$apellidoM.", Agregado exitosamente.";
        } else {
            $_SESSION['error'] = "Lo sentimos, ocurrió un error: ".$nombre;
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
}