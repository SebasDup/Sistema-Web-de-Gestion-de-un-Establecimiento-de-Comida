<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'menu';
include 'includes/header.php';
include 'Static/connect/db.php';
?>
<div class="content">
    <h2>Gestión de menú</h2>
    
    <form class="user-form needs-validation" action="RMenu.php" method="POST" novalidate>
        <div class="form-group">
            <input type="text" class="form-control" name="nombre" placeholder="Nombre del platillo" required>
            <div class="invalid-feedback">Por favor ingrese el nombre del platillo.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="descripcion" placeholder="Descripción" required>
            <div class="invalid-feedback">Por favor ingrese una descripción.</div>
        </div>
        <div class="form-group">
            <input type="number" class="form-control" name="precio" placeholder="Precio" required>
            <div class="invalid-feedback">Por favor ingrese el precio.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="categoria" placeholder="Categoría" required>
            <div class="invalid-feedback">Por favor ingrese la categoría.</div>
        </div>
        <button class="btn btn-primary" type="submit">Agregar platillo al menu</button>
    </form>
    <script src="Static/js/appvacliente.js"></script>
    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM menu;";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['nombre']?></td>
            <td><?php echo $row['descripcion']?></td>
            <td><?php echo $row['precio']?></td>
            <td><?php echo $row['categoria']?></td>         
            <td> 
                <a href="actualizarM.php?id=<?php echo $row['id']?>" class="btn btn-warning">ACTUALIZAR</a>
            </td>
            <td> 
                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['id']?>">ELIMINAR</button>
            </td>
            </tr>
    <?php   }?>
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
        ¿Está seguro de que desea eliminar este platillo?
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
        confirmButton.attr('href', 'eliminarM.php?id=' + id);
    });
</script>

<?php
include 'includes/footer.php';
}else{
    header("Location: login.php");
}
?>