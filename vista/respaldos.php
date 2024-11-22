<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
require_once(__DIR__ . "/../config.php"); // Cambiar la ruta a la correcta
require_once(__DIR__ . "/../modelo/RespaldoModelo.php"); // Agregar la conexión a la base de datos
try {
    $conn = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', ''); // Crear conexión PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error de conexión: " . $e->getMessage();
    header('Location: logout.php');
    exit();
}
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'reportes';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <h2 class="mt-4">Respaldo y Restauración de la Base de Datos</h2>
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
                        <form action="<?php echo urlsite; ?>index.php?c=respaldo&action=realizar_respaldo" method="POST" class="user-form needs-validation" novalidate>
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
                        <form action="<?php echo urlsite; ?>index.php?c=respaldo&action=realizar_restauracion" method="POST" enctype="multipart/form-data" class="user-form needs-validation" novalidate>
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
<?php
require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
} else {
header("Location: logout.php");
}
?>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>