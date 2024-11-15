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

    public function obtenerEmpleado($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM empleados WHERE usuario_id = ?");
        $stmt->execute([$id]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $empleado ? $empleado : -1;
    }

    public function obtenerZonaID($zona) {
        $stmt = $this->pdo->prepare("SELECT * FROM zonas WHERE nombre = ?");
        $stmt->execute([$zona]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}