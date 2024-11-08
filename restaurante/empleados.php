<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'empleados';
include 'includes/header.php';
include 'Static/connect/db.php';
?>
<?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
    <h2>Gestión de empleados</h2>
    
    <form class="user-form needs-validation" action="REmpleadoU.php" method="POST" novalidate>
        <div class="form-group">
            <input type="text" class="form-control" name="nombre" placeholder="Nombre del usuario" required>
            <div class="invalid-feedback">Por favor ingrese un nombre de usuario.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="apellido" placeholder="Apellido del usuario" required>
            <div class="invalid-feedback">Por favor ingrese un apellido de usuario.</div>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            <div class="invalid-feedback">Por favor ingrese un email válido.</div>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="contrasena" placeholder="Contraseña" required>
            <div class="invalid-feedback">Por favor ingrese una contraseña.</div>
        </div>
            <?php if(isset($_SESSION['mensajeREU'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeREU']);
                    unset($_SESSION['mensajeREU']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <button class="btn btn-primary" type="submit">Agregar empleado</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="Static/js/appvacliente.js"></script>
    <?php if(isset($_SESSION['mensajeAEU'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeAEU']);
                    unset($_SESSION['mensajeAEU']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensajeEUE'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeEUE']);
                    unset($_SESSION['mensajeEUE']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
    <h2>Usuarios empleados</h2>

    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM usuarios WHERE tipo = 'empleado';";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['nombre']?></td>
            <td><?php echo $row['apellido']?></td>
            <td><?php echo $row['email']?></td>
            <td> 
                <a href="actualizarEmpleado.php?id=<?php echo $row['id']?>" class="btn btn-warning">ACTUALIZAR</a>
            </td>
            <td> 
                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['id']?>">ELIMINAR</button>
            </td>
            </tr>
    <?php   }?>
        </tbody>
    </table>

    <h2>Registro de empleados</h2>

    <form class="user-form needs-validation" action="REmpleado.php" method="POST" novalidate>
        <div class="form-group">
            <input type="text" class="form-control" name="idEmpleado" placeholder="Id de empleado" required>
            <div class="invalid-feedback">Por favor ingrese el ID del empleado.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="puesto" placeholder="Puesto del empleado" required>
            <div class="invalid-feedback">Por favor ingrese el puesto del empleado.</div>
        </div>
        <div class="form-group">
            <label for="contratacion">Fecha de contratación: </label>
            <input type="date" class="form-control" name="contratacion" placeholder="Fecha de contratación" required>
            <div class="invalid-feedback">Por favor ingrese la fecha de contratación.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="salario" placeholder="Salario" required>
            <div class="invalid-feedback">Por favor ingrese el salario.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="serviciosRealizados" placeholder="Servicios realizados" required>
            <div class="invalid-feedback">Por favor ingrese los servicios realizados.</div>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="zona" placeholder="Zona asignada" required>
            <div class="invalid-feedback">Por favor ingrese la zona asignada.</div>
        </div>
        <?php if(isset($_SESSION['mensajeRE'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeRE']);
                    unset($_SESSION['mensajeRE']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <button class="btn btn-primary" type="submit">Registrar</button>
    </form>

    <?php if(isset($_SESSION['mensajeAE'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeAE']);
                    unset($_SESSION['mensajeAE']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensajeEE'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeEE']);
                    unset($_SESSION['mensajeEE']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
    <h2>Empleados</h2>

    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario_ID</th>
                <th>Puesto</th>
                <th>Fecha de contratacion</th>
                <th>Salario</th>
                <th>Servicios Hechos</th>       
                <th>Zona</th>         
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM empleados";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['usuario_id']?></td>
            <td><?php echo $row['puesto']?></td>
            <td><?php echo $row['fecha_contratacion'];?></td>
            <td><?php echo $row['salario']?></td>
            <td><?php echo $row['servicios_realizados']?></td>
            <td><?php echo $row['zona_asignada']?></td>

            <td> 
                <a href="actualizarEmpleadoU.php?id=<?php echo $row['id']?>" class="btn btn-warning">ACTUALIZAR</a>
            </td>
            <td> 
                <button class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['id']?>">ELIMINAR</button>
            </td>
            </tr>
    <?php   }?>
        </tbody>
    </table>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
      </div>
      <div class="modal-body">
        ¿Está seguro de que desea eliminar este empleado?
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
        confirmButton.attr('href', 'eliminarEmpleado.php?id=' + id);
    });
</script>

<?php
include 'includes/footer.php';
}else{
    header("Location: login.php");
}
?>