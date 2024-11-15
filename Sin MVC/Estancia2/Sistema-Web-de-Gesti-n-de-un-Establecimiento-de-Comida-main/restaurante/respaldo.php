<?php
session_start();
$currentPage = 'respaldo';

// Configuración de la base de datos
$host = 'localhost';
$usuario = 'root';
$password = '';
$base_datos = 'restaurante_db';

// Establecer conexión
try {
    $conn = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $_SESSION['error'] = "Error de conexión: " . $e->getMessage();
}

include 'includes/header.php';
?>

<div class="content">
    <h2>Gestión de Base de Datos</h2>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Realizar Respaldo</h3>
                    <form action="realizar_respaldo.php" method="POST" class="user-form needs-validation" novalidate>
                        <div class="form-group">
                            <label>Realizar una copia de seguridad de la base de datos</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Generar Respaldo</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3>Restaurar Base de Datos</h3>
                    <form action="realizar_restauracion.php" method="POST" enctype="multipart/form-data" class="user-form needs-validation" novalidate>
                        <div class="form-group">
                            <label for="backupFile">Seleccione archivo de respaldo o arrastre un archivo al campo (.sql)</label>
                            <input type="file" class="form-control" id="backupFile" name="backupFile" accept=".sql" required>
                            <div class="invalid-feedback">Por favor seleccione un archivo SQL válido.</div>
                        </div>
                        <button type="submit" class="btn btn-warning">Restaurar Base de Datos</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h3>Historial de Operaciones</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Tipo de Operación</th>
                                <th>Archivo</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM bitacora_db ORDER BY fecha_operacion DESC LIMIT 10");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";
                                echo "<td>" . ucfirst($row['tipo_operacion']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre_archivo']) . "</td>";
                                echo "<td>" . $row['fecha_operacion'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Bootstrap validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<?php include 'includes/footer.php'; ?>