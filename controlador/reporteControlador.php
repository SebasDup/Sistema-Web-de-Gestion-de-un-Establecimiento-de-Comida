<?php
require_once 'config.php';
require_once 'modelo/ReporteModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ReporteControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ReporteModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }

        $_SESSION['paginaActual'] = 'reportes';
        require_once 'vista/reportes.php';
    }
}