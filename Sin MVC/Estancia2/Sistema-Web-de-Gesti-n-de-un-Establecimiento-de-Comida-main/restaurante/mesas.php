<?php
session_start();
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'mesas';
include 'includes/header.php';
$conn = new mysqli("localhost", "root", "", "restaurante_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['agregar'])) {
    $numero = $_POST['numero'];
    $capacidad = $_POST['capacidad'];
    $zona_id = $_POST['zona'];
    $estado = 'disponible';

    // Check if table number already exists
    $sql = "SELECT * FROM mesas WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['mensaje'] = "Número de mesa ya existe";
        $_SESSION['mensaje_tipo'] = "error";
    } else {
        $sql = "INSERT INTO mesas (numero, capacidad, estado, zona_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $numero, $capacidad, $estado, $zona_id);

        if($stmt->execute()) {
            $_SESSION['mensaje'] = "Mesa agregada correctamente";
            $_SESSION['mensaje_tipo'] = "success";
        }
    }
}

// Update table status
if(isset($_POST['estado'])) {
    $id = $_POST['mesa_id'];
    $nuevo_estado = $_POST['estado'];

    $sql = "UPDATE mesas SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id);

    if($stmt->execute()) {
        $_SESSION['mensaje'] = "Estado de la mesa actualizado";
        $_SESSION['mensaje_tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el estado de la mesa";
        $_SESSION['mensaje_tipo'] = "error";
    }
}

// Delete table
if(isset($_POST['eliminar'])) {
    $id = $_POST['mesa_id'];
    $sql = "DELETE FROM mesas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        $_SESSION['mensaje'] = "Mesa eliminada correctamente";
        $_SESSION['mensaje_tipo'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la mesa";
        $_SESSION['mensaje_tipo'] = "error";
    }
}

// Update table number
if(isset($_POST['actualizar_numero'])) {
    $id = $_POST['mesa_id'];
    $nuevo_numero = $_POST['nuevo_numero'];

    // Check if new table number already exists
    $sql = "SELECT * FROM mesas WHERE numero = ? AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $nuevo_numero, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['mensaje'] = "Número de mesa ya existe";
        $_SESSION['mensaje_tipo'] = "error";
    } else {
        $sql = "UPDATE mesas SET numero = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nuevo_numero, $id);

        if($stmt->execute()) {
            $_SESSION['mensaje'] = "Número de mesa actualizado";
            $_SESSION['mensaje_tipo'] = "success";
        }
    }
}

// Other code remains the same...

// Get all tables
$sql = "SELECT m.*, z.nombre as zona_nombre FROM mesas m 
        LEFT JOIN zonas z ON m.zona_id = z.id 
        ORDER BY m.numero";
$result = $conn->query($sql);
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="Static/css/mesas.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <!-- Main content -->
            <div class="col-md-10 main-content">
                <h2>Gestión de Mesas</h2>
                
                <!-- Add table form -->
                <form method="POST" class="mb-4 needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-3">
                            <input type="number" name="numero" class="form-control" placeholder="Número de mesa" required min="1">
                            <div class="invalid-feedback">
                                Por favor ingrese un número de mesa válido.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="capacidad" class="form-control" placeholder="Capacidad" required min="1">
                            <div class="invalid-feedback">
                                Por favor ingrese una capacidad válida.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="zona" class="form-control" required>
                                <option value="">Seleccionar Zona</option>
                                <option value="1">Zona A</option>
                                <option value="2">Zona B</option>
                                <option value="3">Zona C</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione una zona.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" name="agregar" class="btn btn-primary">Agregar Mesa</button>
                        </div>
                    </div>
                </form>
                <?php if(isset($_SESSION['mensaje'])): ?>
                <script>
                    Swal.fire({
                        icon: '<?php echo $_SESSION['mensaje_tipo']; ?>',
                        title: '<?php echo $_SESSION['mensaje']; ?>',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>
                <?php unset($_SESSION['mensaje']); unset($_SESSION['mensaje_tipo']); endif; ?>
                <!-- Tables grid -->
                <div class="row">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-md-3 mb-4">
                            <div class="mesa-card <?php echo $row['estado']; ?>">
                                <h3>Mesa <?php echo $row['numero']; ?>
                                <p><button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">
                                        Editar
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger mt-2" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row['id']; ?>">
                                        Eliminar
                                    </button></p>
                                </h3>
                                <p>Capacidad: <?php echo $row['capacidad']; ?> personas</p>
                                <p>Estado: <?php echo $row['estado']; ?></p>
                                <p>Zona: <?php echo $row['zona_nombre']; ?></p>
                                
                                <div class="estado-buttons">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="mesa_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="actualizar_estado" value="1">
                                        <button type="submit" name="estado" value="disponible" 
                                                class="btn btn-sm <?php echo $row['estado'] == 'disponible' ? 'btn-success' : 'btn-outline-success'; ?>">
                                            Disponible
                                        </button>
                                        <button type="submit" name="estado" value="ocupada" 
                                                class="btn btn-sm <?php echo $row['estado'] == 'ocupada' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                            Ocupada
                                        </button>
                                        <button type="submit" name="estado" value="reservada" 
                                                class="btn btn-sm <?php echo $row['estado'] == 'reservada' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                                            Reservada
                                        </button>
                                        <!-- Modal de confirmación -->
                                        <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Está seguro de que desea eliminar la mesa <?php echo $row['numero']; ?>?
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <input type="hidden" name="confirmar_eliminar" value="1">
                                                            <button type="submit" name="eliminar" class="btn btn-danger" value="1">
                                                                Eliminar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Editar Número de Mesa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" class="needs-validation" novalidate>
                                        <div class="modal-body">
                                            <input type="hidden" name="mesa_id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="nuevo_numero" class="form-label">Nuevo Número de Mesa</label>
                                                <input type="number" class="form-control" id="nuevo_numero" name="nuevo_numero" value="<?php echo $row['numero']; ?>" required min="1">
                                                <div class="invalid-feedback">
                                                    Por favor ingrese un número de mesa válido.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" name="actualizar_numero" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })();
    </script>
<?php
include 'includes/footer.php';
} else {
    header("Location: login.php");
}
?>
