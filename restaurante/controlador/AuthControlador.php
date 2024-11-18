<?php
require_once 'modelo/AuthModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class AuthControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new AuthModelo();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $contrasena = $_POST['contrasena'];
            
            $user = $this->modelo->obtenerUsuario($email, $contrasena);
            $idU = $user['id'];
            $empleado = $this->modelo->obtenerEmpleado($idU);
            
            if ($user['email'] == $email) {
                if ($user['contrasena'] == $contrasena) {
                    $_SESSION['usuario'] = $user['nombre'];
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['rolUsuario'] = $user['tipo'];
                    $zonaAsignada = $empleado['zona_asignada'];
                    $idZona = $this->modelo->obtenerZonaID($zonaAsignada);
                    $_SESSION['IDZonaEmp'] = $idZona['id'];
                    
                    if ($user['tipo'] == 'administrador') {
                        $_SESSION['nombre'] = $user['nombre'];
                        header("Location: vista/admin.php");
                    } else if ($user['tipo'] == 'empleado') {
                        $_SESSION['nombre'] = $user['nombre'];
                        header("Location: vista/employee.php");
                    } else if ($user['tipo'] == 'cliente') {
                        $_SESSION['nombre'] = $user['nombre'];
                        header("Location: vista/home.php");
                    }
                    exit();
                } else {
                    $_SESSION['error'] = "Contraseña incorrecta.";
                    header("Location: http://localhost/restaurante/vista/login.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Usuario no encontrado.";
                header("Location: http://localhost/restaurante/vista/login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Método de solicitud no válido.";
            header("Location: http://localhost/restaurante/vista/login.php");
            exit();
        }
    }

    public function guardarCliente() {
        if (isset($_POST['nombre']) && isset($_POST['apellidoP']) && isset($_POST['apellidoM']) && isset($_POST['email']) && isset($_POST['contrasena'])) {
            if ($this->modelo->emailExiste($_POST['email'])) {
                $_SESSION['errorRegister'] = '¡PELUCAS! el email: '. $_POST['email'] .' ya está registrado, por favor ingrese otro';
                header("Location: " . urlsite . "register.php");
            } else {
                $tipo = 'cliente';
                $this->modelo->agregarUsuario($_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'], $_POST['contrasena'], $tipo);
                header("Location: " . urlsite . "index.php?c=home");
            }
        }else{
            $_SESSION['errorRegister'] = '¡PELUCAS! Todos los campos son obligatorios, por favor contestalos';
            header("Location: " . urlsite . "register.php");
        }
    }

    public function logout() {
        // Código para manejar el cierre de sesión
        session_start();
        session_unset();
        session_destroy();
        header("Location: http://localhost/restaurante/vista/logout.php");
        exit();
    }
}
?>
