<?php
session_start();
$currentPage = 'consultas';
include 'includes/header.php';
include 'Static/connect/db.php';
?>

<div class="content">
    <h2>Consultas</h2>
    <form class="user-form needs-validation" action="consultas.php" method="POST" novalidate>
        <div class="form-group">
            <label for="consulta">Seleccione una consulta:</label>
            <select class="form-control" name="consulta" required>
                <option value="">Seleccione una opción</option>
                <option value="reservaciones">Consultar reservaciones</option>
                <option value="MesasDisponibles">Consultar mesas disponibles</option>
            </select>
            <div class="invalid-feedback">Por favor seleccione una opción.</div>
        </div>
        <button class="btn btn-primary" type="submit">Consultar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $consulta = $_POST['consulta'];
        $query = '';

        switch ($consulta) {
            case 'reservaciones':
                // Ejemplo de consulta para reservaciones
                $query = "SELECT * FROM reservaciones";
                break;
            case 'MesasDisponibles':
                // Ejemplo de consulta para mesas disponibles con estado específico
                $query = "SELECT * FROM mesas WHERE id NOT IN (SELECT mesa_id FROM reservaciones_mesas) AND estado = 'disponible'";
                break;
            default:
                echo "Opción no válida.";
                exit;
        }

        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<div class='content'>";
            echo "<h2>Resultados de la consulta</h2>";
            echo "<table class='table table-striped user-table'>";
            echo "<thead><tr>";

            $fields = mysqli_fetch_fields($result);
            foreach ($fields as $field) {
                echo "<th>{$field->name}</th>";
            }

            echo "</tr></thead><tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>{$value}</td>";
                }
                echo "</tr>";
            }

            echo "</tbody></table>"; // Ensure the table is properly closed
            echo '<div>';
            
            // Dynamic report generation link based on consultation type
            $reportPath = ($consulta == 'reservaciones') ? 'fpdf/ConsultarReservaciones.php' : 'fpdf/mesasDisponibles.php';
            echo '<a href="' . $reportPath . '" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Generar reporte</a>';
            
            echo '</div>'; // Moved the button below the table
            echo "</div>";
        } else {
            echo "Error en la consulta: " . mysqli_error($conn);
        }
    }
    ?>
</div>

<?php
include 'includes/footer.php';
?>