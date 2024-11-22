<?php
require_once 'config.php';
require_once 'modelo/ConfiguracionModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ConfiguracionControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ConfiguracionModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $horarios = $this->modelo->obtenerHorario();
        $_SESSION['paginaActual'] = 'configuracion';
        require_once 'vista/configuracion.php';
    }

    public function actualizarHorario() {
        if(isset($_POST['dia']) && isset($_POST['apertura']) && isset($_POST['cierre']) && isset($_POST['estado'])) {
            $hora_apertura = date('H:i', strtotime($_POST['apertura']));
            $hora_cierre = date('H:i', strtotime($_POST['cierre']));
            $this->modelo->actualizarHorario($_POST['dia'], $hora_apertura, $hora_cierre, $_POST['estado']);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al actualizar horario, comuníquese con el administrador';
        }

        header("Location: index.php?c=configuracion");
    }
}
