<?php
require_once 'config.php';
require_once 'modelo/EmpleadoModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class EmpleadoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new EmpleadoModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        // Obtiene la lista de empleados y la envía a la vista
        $empleados = $this->modelo->obtenerEmpleados();
        $zonas = $this->modelo->obtenerZonas();
        $_SESSION['paginaActual'] = 'empleados';
        require_once 'vista/empleados.php';
    }

    // Método para agregar un empleado
    public function guardarEmpleado() {
        if (isset($_POST['nombre']) && isset($_POST['apellidoP']) && isset($_POST['apellidoM']) && isset($_POST['email']) && isset($_POST['puesto']) && isset($_POST['fecha_contratacion']) && isset($_POST['salario']) && isset($_POST['zona_asignada']) && isset($_POST['contrasena'])) {
            if ($this->modelo->emailExiste($_POST['email'])) {
                $_SESSION['error'] = '¡PELUCAS! Error al registrar empleado: '. $_POST['nombre'] . ' '. $_POST['apellidoP'] . ' '. $_POST['apellidoM'] . ', el email: '. $_POST['email'] .' ya está registrado, por favor ingrese otro';
            } else {
                $tipo = 'empleado';
                $this->modelo->agregarEmpleado($_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'], $_POST['contrasena'], $tipo, $_POST['puesto'], $_POST['fecha_contratacion'], $_POST['salario'], $_POST['zona_asignada']);
            }
        }
        header("Location: " . urlsite . "index.php?c=empleado");
    }
    
    // Método para actualizar un empleado
    public function actualizarEmpleado() {
        if (isset($_POST['id']) && isset($_POST['nombre']) && isset($_POST['apellidoP']) && isset($_POST['apellidoM']) && isset($_POST['email']) && isset($_POST['contrasena']) && isset($_POST['puesto']) && isset($_POST['fecha_contratacion']) && isset($_POST['salario']) && isset($_POST['zona_asignada'])) {
            if($this->modelo->emailEsPropio($_POST['id'],$_POST['email'])){
                $this->modelo->actualizarEmpleado($_POST['id'], $_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'],$_POST['contrasena'], $_POST['puesto'], $_POST['fecha_contratacion'], $_POST['salario'], $_POST['zona_asignada']);
                
            }else{
                if ($this->modelo->emailExiste($_POST['email'])) {
                    $_SESSION['error'] = '¡PELUCAS! Error al actualizar empleado: '. $_POST['nombre'] . ' '. $_POST['apellidoP'] . ' '. $_POST['apellidoM'] . ', el email: '. $_POST['email'] .' ya está registrado, por favor ingrese otro';
                }else{
                    $this->modelo->actualizarEmpleado($_POST['id'], $_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'],$_POST['contrasena'], $_POST['puesto'], $_POST['fecha_contratacion'], $_POST['salario'], $_POST['zona_asignada']);
                }
            }
        }else{
            $_SESSION['error'] = "¡PELUCAS! Error al actualizar el empleado";
        }
        header("Location: " . urlsite . "index.php?c=empleado");
    }

    // Método para eliminar un empleado
    public function eliminarEmpleado() {
        if (isset($_GET['id']) && isset($_GET['nombre']) && isset($_GET['apellidoP']) && isset($_GET['apellidoM'])) {
            $this->modelo->eliminarEmpleado($_GET['id'],$_GET['nombre'], $_GET['apellidoP'], $_GET['apellidoM']);
        }else{
            $_SESSION['error'] = "¡PELUCAS! Error al eliminar el empleado";
        }
        header("Location: " . urlsite . "index.php?c=empleado");
    }
}
?>
