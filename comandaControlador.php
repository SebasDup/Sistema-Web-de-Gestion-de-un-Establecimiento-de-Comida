<?php
require_once 'config.php';
require_once 'modelo/ComandaModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ComandaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ComandaModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $comandas = $this->modelo->obtenerComandas();
        $mesas = $this->modelo->obtenerMesas();
        $usuarios = $this->modelo->obtenerClientes();
        $platillos = $this->modelo->obtenerPlatillos();
        $platillos_comanda = $this->modelo->obtenerPlatillosComanda();
        $_SESSION['paginaActual'] = 'comandas';
        require_once 'vista/comandas.php';
    }

    public function guardarComanda(){
        if (isset($_POST['cliente_id']) && !empty($_POST['platillos']) && isset($_POST['total']) && isset($_POST['id_mesa']) && isset($_POST['cantidades']) && isset($_POST['precios'])) {
            $cliente_id = $_POST['cliente_id'];
            $platillos_id = $_POST['platillos'];
            $totalComanda = $_POST['total'];
            $cantidades = $_POST['cantidades'];
            $precios = $_POST['precios'];
            $mesa_id = $_POST['id_mesa'];
            $comentarios = $_POST['comentarios'];
            $estado = 'abierta';
            $this->modelo->agregarComanda($cliente_id, $platillos_id, $totalComanda, $mesa_id, $comentarios, $estado, $cantidades, $precios);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al registrar comanda, Comuniquese con el administrador cantidades';
        }
        header("Location: " . urlsite . "index.php?c=comanda");
    }

    public function editarComanda(){
        if(isset($_POST['comanda_id']) && isset($_POST['platillos']) && isset($_POST['total']) && isset($_POST['id_mesa']) && isset($_POST['cantidades']) && isset($_POST['precios'])){
            $comanda_id = $_POST['comanda_id'];
            $platillos_id = $_POST['platillos'];
            $totalComanda = $_POST['total'];
            $cantidades = $_POST['cantidades'];
            $precios = $_POST['precios'];
            $mesa_id = $_POST['id_mesa'];
            $comentarios = $_POST['comentarios'];
            $estado = $_POST['estado'];
            $this->modelo->editarComanda($comanda_id, $platillos_id, $totalComanda, $mesa_id, $comentarios, $estado, $cantidades, $precios);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al editar comanda, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=comanda");
    }

    public function eliminarComanda(){
        if(isset($_POST['comanda_id'])){
            $comanda_id = $_POST['comanda_id'];
            $this->modelo->eliminarComanda($comanda_id);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al eliminar comanda, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=comanda");
    }
}