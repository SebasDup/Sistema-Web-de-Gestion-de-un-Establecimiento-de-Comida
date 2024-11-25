<?php
require_once 'config.php';
require_once 'modelo/ReservacionModelo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class ReservacionControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new ReservacionModelo();
    }

    public function index() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?c=auth&m=login");
            exit();
        }
        
        $reservaciones = $this->modelo->obtenerReservaciones();
        $reservaciones_mesas = $this->modelo->obtenerReservacionesMesas();
        $usuarios = $this->modelo->obtenerUsuarios();
        $mesas = $this->modelo->obtenerMesas();
        $_SESSION['paginaActual'] = 'reservaciones';
        require_once 'vista/reservaciones.php';
    }

    public function obtenerReservaciones(){
        $reservaciones = $this->modelo->obtenerReservaciones();
        echo json_encode($reservaciones);
    }

    public function guardarReservacion() {
        if (!isset($_POST['cliente_id']) || empty($_POST['cliente_id'])) {
            $_SESSION['error'] = "Error: Debe seleccionar un cliente para la reservación.";
            header("Location: " . urlsite . "index.php?c=reservacion");
            exit();
        }

        if (isset($_POST['cliente_id'], $_POST['personas'], $_POST['fecha'], $_POST['hora'], $_POST['id_mesa'])) {
            $cliente_id = $_POST['cliente_id'];
            $personas = $_POST['personas'];
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $fechaHora = $fecha . ' ' . $hora;
            $mesa_id = $_POST['id_mesa'];
            $estado = 'pendiente';
            $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';
            
            // ...rest of the validation code...

            if (!$this->validarHorario($fecha, $hora)) {
                $_SESSION['error'] = 'El restaurante se encuentra cerrado en el horario seleccionado.';
                header("Location: " . urlsite . "index.php?c=reservacion");
                exit();
            }

            $capacidad = $this->modelo->obtenerCapacidadMesa($mesa_id);
            $nombreMesa = $this->modelo->obtenerNombreMesa($mesa_id);

            if ($capacidad < $personas) {
                $_SESSION['error'] = 'La Mesa '. $nombreMesa .', solamente tiene la capacidad de: '. $capacidad .'. No hay cupo para: '. $personas.' personas.';
            } else {
                if ($this->modelo->verificarReservacion($fechaHora, $mesa_id)) {
                    $_SESSION['error'] = 'La mesa ya esta reservada para esa fecha y hora: '. $fechaHora;
                } else {
                    $this->modelo->agregarReservacion($cliente_id, $fechaHora, $personas, $estado, $comentarios, $mesa_id);
                }
            }
        } else {
            $_SESSION['error'] = 'Error: Faltan datos requeridos para la reservación.';
        }
        header("Location: " . urlsite . "index.php?c=reservacion");
    }

    public function editarReservacion(){
        if(isset($_POST['reservacion_id']) && isset($_POST['personas']) && isset($_POST['fecha']) && isset($_POST['mesa_id'])){
            $reservacion_id = $_POST['reservacion_id'];
            $personas = $_POST['personas'];
            $fechaHora = $_POST['fecha'];
            $fecha = date('Y-m-d', strtotime($fechaHora));
            $hora = date('H:i:s', strtotime($fechaHora));
            $mesa_id = $_POST['mesa_id'];
            $capacidad = $this->modelo->obtenerCapacidadMesa($mesa_id);
            $nombreMesa = $this->modelo->obtenerNombreMesa($mesa_id);
    
            if (!$this->validarHorario($fecha, $hora)) {
                $_SESSION['error'] = 'El restaurante se encuentra cerrado en el horario seleccionado.';
                header("Location: " . urlsite . "index.php?c=reservacion");
                exit();
            }
    
            if($capacidad < $personas){
                $_SESSION['error'] = 'La Mesa '. $nombreMesa .', solamente tiene la capacidad de: '. $capacidad .'. No hay cupo para: '. $personas.' personas.';
                header("Location: " . urlsite . "index.php?c=reservacion");
                exit();
            }else{
                if($this->modelo->EsReservacionPropia($reservacion_id, $fechaHora, $mesa_id)){
                    $this->modelo->editarReservacion($reservacion_id, $personas, $fechaHora, $mesa_id);
                }else{
                    if($this->modelo->verificarReservacion($fechaHora, $mesa_id)){
                        $_SESSION['error'] = 'La mesa ya esta reservada para esa fecha y hora: '. $fechaHora;
                        header("Location: " . urlsite . "index.php?c=reservacion");
                        exit();
                    }else{
                        $this->modelo->editarReservacion($reservacion_id, $personas, $fecha, $mesa_id);
                    }
                }
            }
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al editar reservación, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=reservacion");
    }

    private function validarHorario($fecha, $hora) {
        $diaSemana = date('N', strtotime($fecha));
        $dias = [
            1 => 'lunes',
            2 => 'martes',
            3 => 'miércoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sábado',
            7 => 'domingo'
        ];
        $diaSemanaNombre = $dias[$diaSemana];
        $horario = $this->modelo->obtenerHorario($diaSemanaNombre);
        if ($horario['estado'] == 'abierto') {
            $horaApertura = $horario['hora_apertura'];
            $horaCierre = $horario['hora_cierre'];
            $horaReservacion = $hora;
            if ($horaReservacion >= $horaApertura && $horaReservacion <= $horaCierre) {
                return true;
            }
        }
        return false;
    }

    public function eliminarReservacion(){
        if(isset($_POST['reservacion_id'])){
            $reservacion_id = $_POST['reservacion_id'];
            $this->modelo->eliminarReservacion($reservacion_id);
        }else{
            $_SESSION['error'] = '¡PELUCAS! Error al eliminar reservación, Comuniquese con el administrador';
        }
        header("Location: " . urlsite . "index.php?c=reservacion");
    }
}
