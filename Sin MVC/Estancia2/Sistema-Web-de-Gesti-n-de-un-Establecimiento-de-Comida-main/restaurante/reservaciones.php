<?php
session_start();
$currentPage = 'reservaciones';
include 'includes/header.php';
include 'Static/connect/db.php';

if(isset($_SESSION['usuario'])) {
    $user = $_SESSION['usuario'];
    ?>
    <div class="content">
        <h2>Gestión de Reservaciones</h2>
        
        <form class="user-form needs-validation" action="RReservacion.php" method="POST" novalidate>
            <div class="form-group">
                <input type="datetime-local" class="form-control" name="fecha" placeholder="Fecha y hora" required>
                <div class="invalid-feedback">Por favor ingrese la fecha y hora.</div>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="personas" placeholder="Número de personas" required>
                <div class="invalid-feedback">Por favor ingrese el número de personas.</div>
            </div>
            <div class="form-group">
                <select name="mesa_id" class="form-control" required>
                    <option value="">Seleccionar Mesa</option>
                    <?php
                    $mesas_query = "SELECT id, numero FROM mesas WHERE estado = 'disponible'";
                    $mesas_result = mysqli_query($conn, $mesas_query);
                    while($mesa = mysqli_fetch_assoc($mesas_result)) {
                        echo "<option value='{$mesa['id']}'>Mesa {$mesa['numero']}</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">Por favor seleccione una mesa.</div>
            </div>
            <button class="btn btn-primary" type="submit">Agregar Reservación</button>
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
                    <th>Fecha</th>
                    <th>Personas</th>
                    <th>Mesa</th>
                    <th>Estado</th>
                    <th>Actualizar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT r.id, r.fecha, r.personas, r.estado, m.numero as mesa_numero 
                      FROM reservaciones r 
                      JOIN reservaciones_mesas rm ON r.id = rm.reservacion_id 
                      JOIN mesas m ON rm.mesa_id = m.id";
            $result = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($result)){ ?>
                <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['fecha']?></td>
                    <td><?php echo $row['personas']?></td>
                    <td><?php echo $row['mesa_numero']?></td>
                    <td><?php echo $row['estado']?></td>
                    <td> 
                        <a href="actualizarReservacion.php?id=<?php echo $row['id']?>" class="btn btn-warning">ACTUALIZAR</a>
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
            ¿Está seguro de que desea eliminar esta reservación?
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
            confirmButton.attr('href', 'eliminarReservacion.php?id=' + id);
        });
    </script>

    <?php
    include 'includes/footer.php';
} else {
    header("Location: login.php");
}
?>