<?php

class AdminControlador {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }

        require_once 'vista/admin.php';
    }
}
