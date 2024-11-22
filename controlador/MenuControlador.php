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

    public function guardarMenu(){
        if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['precio']) && isset($_POST['categoria'])) {
            if ($this->modelo->platilloExiste($_POST['nombre'])) {
                $_SESSION['error'] = '¡PELUCAS! Error al registrar platillo: '. $_POST['nombre'] .' ya está registrado, por favor ingrese otro platillo';
            }else{
                $this->modelo->agregarMenu($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria']);
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al registrar platillo, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=menu");
    }

    public function actualizarMenu(){
        if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['precio']) && isset($_POST['categoria'])) {
            if($this->modelo->platilloEsPropio($_POST['id'],$_POST['nombre'])){
                $this->modelo->actualizarMenu($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria']);
            }else{
                if ($this->modelo->platilloExiste($_POST['nombre'])) {
                    $_SESSION['error'] = '¡PELUCAS! Error al actualizar platillo: '. $_POST['nombre'] .' ya está registrado, por favor ingrese otro platillo';
                }else{
                    $this->modelo->actualizarMenu($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['categoria']);
                }
            }
        }
        header("Location: " . urlsite . "index.php?c=menu");
    }

    public function eliminarMenu() {
        if (isset($_GET['id']) && isset($_GET['nombre'])) {
            $this->modelo->eliminarMenu($_GET['id'],$_GET['nombre']);
        }else{
            $_SESSION['error'] = "¡PELUCAS! Error al eliminar";
        }
        header("Location: " . urlsite . "index.php?c=menu");
    }

}

?>