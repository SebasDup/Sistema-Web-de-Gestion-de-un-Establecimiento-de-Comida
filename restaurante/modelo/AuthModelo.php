<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class AuthModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerUsuario($usuario, $contrasena) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE nombre = :nombre AND contrasena = :contrasena");
        $stmt->bindParam(':nombre', $usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}