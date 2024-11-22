<?php
require_once 'config.php';
require_once 'modelo/PromocionModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class PromocionControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new PromocionModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $promociones = $this->modelo->obtenerPromociones();
        $_SESSION['paginaActual'] = 'promociones';
        require_once 'vista/promociones.php';
    }

    public function guardarPromocion(){
        if (isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['descuento']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
            if($this->modelo->TituloRepetido($_POST['titulo'])){
                $_SESSION['error'] = '¡PELUCAS! El titulo de la promoción ya existe, por favor ingrese otro';
                header("Location: " . urlsite . "index.php?c=promocion");
                exit();
            }else{
                if($_POST['fecha_inicio'] > $_POST['fecha_fin'] && $_POST['fecha_fin'] < $_POST['fecha_inicio']){
                    $_SESSION['error'] = '¡PELUCAS! El rango de fechas no es valido, por favor verifique';
                    header("Location: " . urlsite . "index.php?c=promocion");
                    exit();
                }else{
                    $this->modelo->agregarPromocion($_POST['titulo'], $_POST['descripcion'], $_POST['descuento'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
                }
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al registrar promoción, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=promocion");
    }

    public function actualizarPromocion(){
        if (isset($_POST['id']) && isset($_POST['titulo']) && isset($_POST['descripcion']) && isset($_POST['descuento']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
            if($this->modelo->TituloEsPropio($_POST['titulo'], $_POST['id'])){
                if($_POST['fecha_inicio'] > $_POST['fecha_fin'] && $_POST['fecha_fin'] < $_POST['fecha_inicio']){
                    $_SESSION['error'] = '¡PELUCAS! El rango de fechas no es valido, por favor verifique';
                    header("Location: " . urlsite . "index.php?c=promocion");
                    exit();
                }else{
                    $this->modelo->actualizarPromocion($_POST['id'], $_POST['titulo'], $_POST['descripcion'], $_POST['descuento'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
                }
            }else{
                if($this->modelo->TituloRepetido($_POST['titulo'])){
                    $_SESSION['error'] = '¡PELUCAS! El titulo de la promoción ya existe, por favor ingrese otro';
                    header("Location: " . urlsite . "index.php?c=promocion");
                    exit();
                }else{
                    if($_POST['fecha_inicio'] > $_POST['fecha_fin'] && $_POST['fecha_fin'] < $_POST['fecha_inicio']){
                        $_SESSION['error'] = '¡PELUCAS! El rango de fechas no es valido, por favor verifique';
                        header("Location: " . urlsite . "index.php?c=promocion");
                        exit();
                    }else{
                        $this->modelo->actualizarPromocion($_POST['id'], $_POST['titulo'], $_POST['descripcion'], $_POST['descuento'], $_POST['fecha_inicio'], $_POST['fecha_fin']);
                    }
                }
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al actualizar promoción, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=promocion");
    }

    public function eliminarPromocion(){
        if (isset($_GET['id'])) {
            $this->modelo->eliminarPromocion($_GET['id']);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al eliminar promoción, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=promocion");
    }
}