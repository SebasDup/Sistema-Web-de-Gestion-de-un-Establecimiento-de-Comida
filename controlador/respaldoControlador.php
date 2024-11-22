<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modelo/RespaldoModelo.php';

class RespaldoControlador {
    private $modelo;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
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

    public function realizar_respaldo() {
        try {
            $this->modelo->realizarRespaldo();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al crear el respaldo: " . $e->getMessage();
            header('Location: ' . urlsite . 'vista/respaldos.php');
            exit;
        }
    }

    public function realizar_restauracion() {
        if ($_FILES['backupFile']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['backupFile']['tmp_name'];
            try {
                $this->modelo->realizarRestauracion($tmpName);
                header('Location: ' . urlsite . 'vista/admin.php'); // Changed redirect to admin.php
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al restaurar la base de datos: " . $e->getMessage();
                header('Location: ' . urlsite . 'vista/respaldos.php');
            }
        } else {
            $_SESSION['error'] = "Error al subir el archivo. Código: " . $_FILES['backupFile']['error'];
            header('Location: ' . urlsite . 'vista/respaldos.php');
        }
        exit;
    }
}

$action = $_GET['action'] ?? 'index';
$controller = new RespaldoControlador();
$controller->$action();
?>