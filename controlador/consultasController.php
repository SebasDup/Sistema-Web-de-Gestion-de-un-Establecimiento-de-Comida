<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consulta = $_POST['consulta'];
    
    switch($consulta) {
        case 'mesas_estado':
            $estado = isset($_POST['estado']) ? $_POST['estado'] : 'disponible';
            header("Location: ../vista/consultas/ConsultaMesasEstado.php?estado=$estado");
            break;
        case 'reservaciones_estado':
            $estado = isset($_POST['estado_reservacion']) ? $_POST['estado_reservacion'] : 'pendiente';
            header("Location: ../vista/consultas/ConsultaReservacionesEstado.php?estado=$estado");
            break;
        case 'reservaciones':
            header('Location: ../vista/consultas/Reservaciones.php');
            break;
        case 'MesasDisponibles':
            header('Location: ../vista/consultas/MesasDisponibles.php');
            break;
        case 'historial_servicio_mesas':
            header("Location: ../vista/consultas/ConsultaHistorialServicioMesas.php");
            break;
        case 'empleados_puesto':
            $puesto = isset($_POST['puesto']) ? $_POST['puesto'] : '';
            header("Location: ../vista/consultas/ConsultaEmpleadosPuesto.php?puesto=$puesto");
            break;
        default:
            header('Location: ../vista/reportes.php');
            break;
    }
    exit();
} else {
    header('Location: ../vista/reportes.php');
    exit();
}
?>