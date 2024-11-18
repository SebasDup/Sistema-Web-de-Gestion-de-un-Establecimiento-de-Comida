<?php
require_once 'config.php';
require_once 'modelo/RespaldoModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class RespaldoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new RespaldoModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $_SESSION['paginaActual'] = 'respaldo';
        require_once 'vista/respaldos.php';
    }
}