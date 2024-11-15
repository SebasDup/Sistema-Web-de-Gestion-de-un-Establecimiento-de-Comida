<?php
session_start();
$currentPage = 'reportes';
include 'includes/header.php';
include 'Static/connect/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reporte = $_POST['reporte'];
    $query = '';

    switch ($reporte) {
        case 'clientes_reservaciones':
            // Ejemplo de consulta para clientes con reservaciones dentro de un rango de fechas
            $query = "SELECT * FROM reservaciones WHERE fecha BETWEEN '2023-01-01' AND '2023-12-31'";
            break;
        case 'empleados_antiguedad':
            $query = "SELECT e.*, u.nombre, u.apellido,
                     TIMESTAMPDIFF(YEAR, e.fecha_contratacion, CURDATE()) as años,
                     TIMESTAMPDIFF(MONTH, e.fecha_contratacion, CURDATE()) % 12 as meses,
                     TIMESTAMPDIFF(DAY, DATE_ADD(e.fecha_contratacion, 
                         INTERVAL TIMESTAMPDIFF(MONTH, e.fecha_contratacion, CURDATE()) MONTH), 
                         CURDATE()) as dias
                     FROM empleados e 
                     INNER JOIN usuarios u ON e.usuario_id = u.id 
                     ORDER BY e.fecha_contratacion ASC";
            break;
        case 'mesas_disponibles':
            // Ejemplo de consulta para mesas sin reservación en una fecha determinada
            $query = "SELECT * FROM mesas WHERE id NOT IN (SELECT mesa_id FROM reservaciones WHERE fecha = '2023-10-10')";
            break;
        case 'mesas_asignadas':
            $query = "SELECT m.id, m.numero, m.estado, z.nombre as zona_nombre, 
                     CONCAT(u.nombre, ' ', u.apellido) as nombre_empleado 
                     FROM mesas m 
                     INNER JOIN zonas z ON m.zona_id = z.id 
                     LEFT JOIN empleados e ON e.zona_asignada = z.nombre 
                     LEFT JOIN usuarios u ON e.usuario_id = u.id 
                     WHERE e.id IS NOT NULL 
                     ORDER BY m.zona_id, m.numero";
            break;
        case 'dia_mas_clientes':
            // Ejemplo de consulta para el día con más clientes dentro de un rango de fechas
            $query = "SELECT fecha, COUNT(*) as total_clientes FROM reservaciones WHERE fecha BETWEEN '2023-01-01' AND '2023-12-31' GROUP BY fecha ORDER BY total_clientes DESC LIMIT 1";
            break;
        default:
            echo "Opción no válida.";
            exit;
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='content'>";
        echo "<h2>Resultados del reporte</h2>";
        echo "<table class='table table-striped user-table'>";
        echo "<thead><tr>";

        // Obtener los nombres de las columnas
        $fields = mysqli_fetch_fields($result);
        foreach ($fields as $field) {
            echo "<th>{$field->name}</th>";
        }

        echo "</tr></thead><tbody>";

        // Obtener los datos de las filas
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>{$value}</td>";
            }
            echo "</tr>";
        }

        echo "</tbody></table>";
        
        // Map report types to their corresponding FPDF files
        $reportFiles = [
            'clientes_reservaciones' => 'fpdf/ClientesReservaciones.php',
            'empleados_antiguedad' => 'fpdf/EmpleadosAntiguedad.php',
            'mesas_disponibles' => 'fpdf/MesasDisponibles.php',
            'mesas_asignadas' => 'fpdf/MesasAsignadas.php',
            'dia_mas_clientes' => 'fpdf/DiaMasClientes.php'
        ];

        if (isset($reportFiles[$reporte])) {
            echo '<div>';
            echo '<a href="' . $reportFiles[$reporte] . '" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Generar reporte</a>';
            echo '</div>';
        }
        
        echo "</div>";
    } else {
        echo "Error en la consulta: " . mysqli_error($conn);
    }
}

include 'includes/footer.php';
?>

<script>
function generatePDF() {
    window.print();
}
</script>