<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'mesas';
include 'includes/header.php';
include 'Static/connect/db.php';
?>

<div class="content">
    <h2>Gestión de Mesas</h2>
    
    <form class="user-form needs-validation" action="RMesa.php" method="POST" novalidate>
        <div class="form-group">
            <input type="number" class="form-control" name="numero" placeholder="Número de mesa" required>
            <div class="invalid-feedback">Por favor ingrese el número de mesa.</div>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="capacidad" placeholder="Capacidad" required>
            <div class="invalid-feedback">Por favor ingrese la capacidad.</div>
        </div>
        <div class="form-group">
            <select name="zona" class="form-control" required>
                <option value="">Seleccionar Zona</option>
                <option value="1">Zona A</option>
                <option value="2">Zona B</option>
                <option value="3">Zona C</option>
            </select>
            <div class="invalid-feedback">Por favor seleccione una zona.</div>
        </div>
        <button class="btn btn-primary" type="submit">Agregar Mesa</button>
    </form>

    <?php if(isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['mensaje']);
            unset($_SESSION['mensaje']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Capacidad</th>
                <th>Estado</th>
                <th>Zona</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT m.*, z.nombre as zona_nombre FROM mesas m 
                  LEFT JOIN zonas z ON m.zona_id = z.id 
                  ORDER BY m.numero";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['numero']; ?></td>
                <td><?php echo $row['capacidad']; ?></td>
                <td><?php echo $row['estado']; ?></td>
                <td><?php echo $row['zona_nombre']; ?></td>
                <td>
                    <a href="actualizarMesa.php?id=<?php echo $row['id']?>" class="btn btn-warning">ACTUALIZAR</a>
                </td>
                <td>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['id']?>">ELIMINAR</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar esta mesa?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Eliminar</a>
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

    // Modal confirmation
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var confirmButton = $('#confirmDeleteButton');
        confirmButton.attr('href', 'eliminarMesa.php?id=' + id);
    });
</script>

<?php
include 'includes/footer.php';
} else {
    header("Location: login.php");
}
?>
