<?php
require_once 'config.php';
require_once 'modelo/ReservacionModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ReservacionControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ReservacionModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $usuarios = $this->modelo->obtenerReservaciones();
        $_SESSION['paginaActual'] = 'reservaciones';
        require_once 'vista/reservaciones.php';
    }
}
