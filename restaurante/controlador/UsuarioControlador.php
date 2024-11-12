<?php
require_once 'config.php';
require_once 'modelo/UsuarioModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class UsuarioControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $usuarios = $this->modelo->obtenerUsuarios();
        $_SESSION['paginaActual'] = 'usuarios';
        require_once 'vista/usuarios.php';
    }

    // Método para agregar un nuevo usuario
    public function guardarUsuario() {
        if (isset($_POST['nombre']) && isset($_POST['apellidoP']) && isset($_POST['apellidoM']) && isset($_POST['email']) && isset($_POST['contrasena'])) {
            if ($this->modelo->emailExiste($_POST['email'])) {
                $_SESSION['error'] = '¡PELUCAS! Error al registrar usuario: '. $_POST['nombre'] . ' '. $_POST['apellidoP'] .' ' . $_POST['apellidoM'] . ', el email: '. $_POST['email'] .' ya está registrado, por favor ingrese otro';
            } else {
                $tipo = 'cliente';
                $this->modelo->agregarUsuario($_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'], $_POST['contrasena'], $tipo);
            }
        }
        header("Location: " . urlsite . "index.php?c=usuario");
    }

    // Método para actualizar un usuario
    public function actualizarUsuario() {
        if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['apellidoP']) && isset($_POST['apellidoM']) && isset($_POST['email']) && isset($_POST['contrasena'])) {
            if($this->modelo->emailEsPropio($_POST['id'],$_POST['email'])){
                $this->modelo->actualizarUsuario($_POST['id'], $_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'],$_POST['contrasena']);
            }else{
                if ($this->modelo->emailExiste($_POST['email'])) {
                    $_SESSION['error'] = 'Error al actualizar usuario: '. $_POST['nombre'] . ' '. $_POST['apellidoP'] . ' '. $_POST['apellidoM'] . ', el email: '. $_POST['email'] .' ya está registrado, por favor ingrese otro';
                }else{
                    $this->modelo->actualizarUsuario($_POST['id'], $_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'],$_POST['contrasena']);
                }
            }
        }
        header("Location: " . urlsite . "index.php?c=usuario");
    }

    // Método para eliminar un usuario
    public function eliminarUsuario() {
        if (isset($_GET['id']) && isset($_GET['nombre']) && isset($_GET['apellidoP']) && isset($_GET['apellidoM'])) {
            $this->modelo->eliminarUsuario($_GET['id'],$_GET['nombre'], $_GET['apellidoP'], $_GET['apellidoM']);
        }else{
            $_SESSION['error'] = "Error al eliminar";
        }
        header("Location: " . urlsite . "index.php?c=usuario");
    }
}
