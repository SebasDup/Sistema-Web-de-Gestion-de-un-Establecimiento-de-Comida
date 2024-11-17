<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class AuthModelo {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');
    }

    public function obtenerUsuario($email, $contrasena) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND contrasena = :contrasena");
        $stmt->bindParam(':email', $email);
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

    // Método para verificar si un email ya existe
    public function emailExiste($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function agregarUsuario($nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, contrasena, tipo) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$nombre, $apellidoP, $apellidoM, $email, $contrasena, $tipo])) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['usuario'] = $nombre;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['usuario_id'] = $this->pdo->lastInsertId();
            $_SESSION['rolUsuario'] = $tipo;
        } else {
            $_SESSION['error'] = "Lo sentimos ".$nombre." ocurrió un error, por favor inténtelo de nuevo más tarde." ;
        }
    }
}