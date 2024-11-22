<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporte = $_POST['reporte'];
    
    switch($reporte) {
        case 'empleados_antiguedad':
            header('Location: ../vista/reportes/EmpleadosAntiguedad.php');
            break;
        case 'empleados_actividad':
            header('Location: ../vista/reportes/EmpleadosActividad.php');
            break;
        case 'clientes_reservaciones':
            $fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
            $fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
            
            if ($fecha_inicio && $fecha_fin) {
                header("Location: ../vista/reportes/ClientesReservaciones.php?fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin");
            } else {
                header('Location: ../vista/reportes/ClientesReservaciones.php');
            }
            break;
        case 'mesas_disponibles':
            header('Location: ../vista/reportes/MesasDisponibles.php');
            break;
        case 'mesas_asignadas':
            header('Location: ../vista/reportes/MesasAsignadas.php');
            break;
        case 'dia_mas_clientes':
            header('Location: ../vista/reportes/DiaMasClientes.php');
            break;
        case 'platillos_vendidos':
            header('Location: ../vista/reportes/PlatillosVendidos.php');
            break;
        case 'ingresos_totales':
            $tipo_periodo = isset($_POST['tipo_periodo']) ? $_POST['tipo_periodo'] : 'dia';
            header("Location: ../vista/reportes/IngresosTotales.php?periodo=$tipo_periodo");
            break;
        case 'descuentos_usados':
            header("Location: ../vista/reportes/DescuentosUsados.php");
            break;
        case 'ingresos_categoria':
            $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : '';
            header("Location: ../vista/reportes/IngresosPorCategoria.php?categoria=" . urlencode($categoria));
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