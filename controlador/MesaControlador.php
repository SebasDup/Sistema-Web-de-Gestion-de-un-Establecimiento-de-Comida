<?php
require_once 'config.php';
require_once 'modelo/MesaModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class MesaControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new MesaModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        if($_SESSION['rolUsuario'] == 'empleado'){
            $id = $_SESSION['usuario_id'];
            $zonaAsignada = $_SESSION['IDZonaEmp'];
            $mesas = $this->modelo->obtenerMesasEmpelado($zonaAsignada);
            if (empty($mesas)) {
                $_SESSION['errorMesa'] = 'errorMesa';
            }
            $zonas = $this->modelo->obtenerZonas();
            $_SESSION['paginaActual'] = 'mesas';
            require_once 'vista/mesas.php';
        }else{
            if($_SESSION['rolUsuario'] == 'administrador'){
                $mesas = $this->modelo->obtenerMesas();
                $zonas = $this->modelo->obtenerZonas();
                $_SESSION['paginaActual'] = 'mesas';
                require_once 'vista/mesas.php';       
        }
        }
    }

    public function guardarMesa() {
        if (isset($_POST['numero']) && isset($_POST['capacidad']) && isset($_POST['id_zona'])) {
            if ($this->modelo->mesaExiste($_POST['numero'])) {
                $_SESSION['error'] = 'Error al registrar mesa: El número de mesa ' . $_POST['numero'] . ' ya está registrado, por favor ingrese otro número';
            } else {
                $estado = 'disponible';
                $this->modelo->agregarMesa($_POST['numero'], $_POST['capacidad'], $estado, $_POST['id_zona']);
            }
        } else {
            $_SESSION['error'] = '¡PELUCAS! Error al registrar mesa, comuníquese con el administrador numero: '. $_POST['numero'] .' ' . $_POST['capacidad']. ' '. $_POST['id_zona'];
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function actualizarMesa(){
        if (isset($_POST['mesa_id']) && isset($_POST['numero']) && isset($_POST['capacidad']) && isset($_POST['id_zona'])) {
            if ($this->modelo->mesaEsPropia($_POST['mesa_id'], $_POST['numero'])) {
                $this->modelo->actualizarMesa($_POST['mesa_id'], $_POST['numero'], $_POST['capacidad'], $_POST['id_zona']);
            } else {
                if ($this->modelo->mesaExiste($_POST['numero'])) {
                    $_SESSION['error'] = 'Error al actualizar mesa: El número de mesa ' . $_POST['numero'] . ' ya está registrado, por favor ingrese otro número';
                } else {
                    $this->modelo->actualizarMesa($_POST['mesa_id'], $_POST['numero'], $_POST['capacidad'], $_POST['id_zona']);
                }
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al actualizar mesa, comuníquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function eliminarMesa() {
        if (isset($_GET['id']) && isset($_GET['numero'])) {
            if ($this->modelo->mesaDisponible($_GET['id'], $_GET['numero'])) {
                $this->modelo->eliminarMesa($_GET['id'], $_GET['numero']);
            }else{
                $_SESSION['error'] = "¡PELUCAS! Error al eliminar la Mesa ". $_GET['numero'] .", la mesa debe estar disponible";
            }
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al eliminar mesa";
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function actualizarEstado(){
        if (isset($_POST['estado']) && isset($_POST['mesa_id']) && isset($_POST['numero'])) {
            $this->modelo->actualizarEstado($_POST['estado'], $_POST['mesa_id'],$_POST['numero']);
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al actualizar estado de la mesa número: ". $_POST['mesa_id'];
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function guardarZona() {
        if (isset($_POST['zona']) && isset($_POST['descripcion'])) {
            $zona = $_POST['zona'];
            $descripcion = $_POST['descripcion'];
            if ($this->modelo->zonaExiste($zona)) {
                $_SESSION['error'] = 'Error al registrar la Zona ' . $_POST['zona'] . ' ya está registrada, por favor ingrese otra';
            } else {
                $this->modelo->agregarZona($zona, $descripcion);
            }
        } else {
            $_SESSION['error'] = '¡PELUCAS! Error al registrar zona, comuníquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function eliminarZona() {
        if (isset($_GET['id']) && isset($_GET['nombre'])) {
            $zona = $_GET['nombre'];
            if(!$this->modelo->zonaLibre($_GET['id'])){
                $this->modelo->eliminarZona($_GET['id'], $zona);
            }else{
                $_SESSION['error'] = "¡PELUCAS! La Zona ".$_GET['nombre'].', no debe tener mesas asignadas';
            }
        } else {
            $_SESSION['error'] = "¡PELUCAS! Error al eliminar Zona ".$_GET['id'].' '.$_GET['zona'];
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }

    public function actualizarZona(){
        if (isset($_POST['zona_id']) && isset($_POST['nombre']) && isset($_POST['descripcion'])) {
            $zona = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            if ($this->modelo->zonaEsPropia($_POST['zona_id'], $zona)) {
                $this->modelo->actualizarZona($_POST['zona_id'], $zona, $descripcion);
            } else {
                if ($this->modelo->zonaExiste($zona)) {
                    $_SESSION['error'] = 'Error al actualizar zona: La zona ' . $zona . ' ya está registrada, por favor ingrese otra';
                } else {
                    $this->modelo->actualizarZona($_POST['zona_id'], $zona, $descripcion);
                }
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al actualizar zona, comuníquese con el administrador ';
        }
        header("Location: " . urlsite . "index.php?c=mesa");
    }
}
?>