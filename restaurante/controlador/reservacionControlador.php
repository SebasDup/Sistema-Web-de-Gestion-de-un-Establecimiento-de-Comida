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
        
        $reservaciones = $this->modelo->obtenerReservaciones();
        $reservaciones_mesas = $this->modelo->obtenerReservacionesMesas();
        $usuarios = $this->modelo->obtenerUsuarios();
        $mesas = $this->modelo->obtenerMesas();
        $_SESSION['paginaActual'] = 'reservaciones';
        require_once 'vista/reservaciones.php';
    }

    public function guardarReservacion(){
        if (isset($_POST['cliente_id']) && isset($_POST['personas']) && isset($_POST['fecha']) && isset($_POST['hora']) && isset($_POST['id_mesa'])) {
            $cliente_id = $_POST['cliente_id'];
            $personas = $_POST['personas'];
            $fecha = $_POST['fecha'] . ' ' . $_POST['hora'];
            $mesa_id = $_POST['id_mesa'];
            $estado = 'pendiente';
            $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
            $this->modelo->agregarReservacion($cliente_id, $fecha, $personas, $estado, $comentarios, $mesa_id);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al registrar reservación, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=reservacion");
    }

    public function obtenerReservaciones(){
        $reservaciones = $this->modelo->obtenerReservaciones();
        echo json_encode($reservaciones);
    }
}
