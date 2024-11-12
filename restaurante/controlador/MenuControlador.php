<?php
require_once 'config.php';
require_once 'modelo/MenuModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class MenuControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new MenuModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        $menu = $this->modelo->obtenerMenu();
        $_SESSION['paginaActual'] = 'menu';
        require_once 'vista/menu.php';
    }
}

?>