<?php
session_start();

if(isset($_SESSION['usuario'])) {
    $user = $_SESSION['usuario'];
    $usuarioRol = $_SESSION['rolUsuario'];

    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
        $currentPage = 'usuarios';
        include 'includes/header.php';
        include 'Static/connect/db.php';
        ?>

        <h2>Gestión de Usuarios</h2>
        <form class="user-form needs-validation" action="RUsuario.php" method="POST" novalidate>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre de usuario" required>
                <div class="invalid-feedback">
                    Por favor ingrese un nombre de usuario.
                </div>
            </div>
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                <div class="invalid-feedback">
                    Por favor ingrese un apellido.
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <div class="invalid-feedback">
                    Por favor ingrese un email válido.
                </div>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required>
                <div class="invalid-feedback">
                    Por favor ingrese una contraseña.
                </div>
            </div>
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensaje']);
                    unset($_SESSION['mensaje']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <button class="btn btn-primary" type="submit">Agregar Usuario</button>
        </form>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                'use strict'
                const forms = document.querySelectorAll('.needs-validation')
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            });

            document.addEventListener('DOMContentLoaded', function() {
                const deleteLinks = document.querySelectorAll('.delete-link');
                let deleteUrl = '';

                deleteLinks.forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        deleteUrl = this.href;
                        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                        confirmDeleteModal.show();
                    });
                });

                document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                    window.location.href = deleteUrl;
                });
            });
        </script>
        <?php if(isset($_SESSION['mensajeAU'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeAU']);
                    unset($_SESSION['mensajeAU']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['mensajeCE'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['mensajeCE']);
                    unset($_SESSION['mensajeCE']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <table class="user-table">
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
            $query = "SELECT * FROM usuarios WHERE tipo = 'cliente';";
            $resul = mysqli_query($conn, $query);
            while($row = mysqli_fetch_array($resul)){ ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']);?></td>
                    <td><?php echo htmlspecialchars($row['nombre']);?></td>
                    <td><?php echo htmlspecialchars($row['apellido']);?></td>
                    <td><?php echo htmlspecialchars($row['email']);?></td>
                    <td> 
                        <a href="actualizarC.php?id=<?php echo htmlspecialchars($row['id']);?>" class="btn btn-warning">ACTUALIZAR</a>
                    </td>
                    <td> 
                        <a href="eliminarC.php?id=<?php echo htmlspecialchars($row['id']);?>" class="btn btn-danger delete-link">ELIMINAR</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar este usuario?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include 'includes/footer.php';
    } else {
        header("Location: logout.php");
    }
} else {
    header("Location: logout.php");
}
?>