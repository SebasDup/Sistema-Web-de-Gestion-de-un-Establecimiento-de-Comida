<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'usuarios';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <h2 class="mt-4">Gestión de Usuarios</h2>
    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">Nuevo Usuario</button>
    <!-- Mostrar mensajes de error o éxito por medio de las variables de sesión -->
    <?php if(isset($_SESSION['mensaje']) || isset($_SESSION['error'])): ?>
        <div class="alert alert-dismissible fade show <?php echo isset($_SESSION['mensaje']) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php 
            if(isset($_SESSION['mensaje'])) {
                echo htmlspecialchars($_SESSION['mensaje']);
                unset($_SESSION['mensaje']); 
            } else {
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); 
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar usuario...">
        <button class="btn btn-outline-secondary d-flex align-items-center" style="height: 46px;" type="button" onclick="buscar()">
            <i class="bi bi-search me-1"></i>
        </button>
        <button class="btn btn-outline-secondary" style="height: 46px;" type="button" onclick="cerrar()">
            <i class="bi bi-x-circle"></i>
        </button>
        <div class="invalid-feedback">No se encontraron resultados.</div>
    </div>
    <!-- Tabla de Usuarios -->
    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nombre'] ?></td>
                <td><?= $usuario['apellidoP'] ?></td>
                <td><?= $usuario['apellidoM'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal<?= $usuario['id'] ?>">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal<?= $usuario['id'] ?>">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        
            <!-- Modal Editar Usuario -->
            <div class="modal fade" id="editarUsuarioModal<?= $usuario['id'] ?>" tabindex="-1" aria-labelledby="editarUsuarioLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editarUsuarioForm<?= $usuario['id'] ?>" action="index.php?c=usuario&m=actualizarUsuario" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarUsuarioLabel">Editar Usuario</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Apellido Paterno</label>
                                    <input type="text" name="apellidoP" class="form-control" value="<?= $usuario['apellidoP'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Apellido Materno</label>
                                    <input type="text" name="apellidoM" class="form-control" value="<?= $usuario['apellidoM'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese los apellidos.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= $usuario['email'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese un email válido.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Contraseña</label>
                                    <input type="password" name="contrasena" class="form-control" value="<?= $usuario['contrasena'] ?>">
                                    <div class="invalid-feedback">Por favor, ingrese una contraseña.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-Agregar btn btn-success" onclick="validarFormulario('editarUsuarioForm<?= $usuario['id'] ?>')">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <!-- Modal Confirmar Eliminar Usuario -->
            <div class="modal fade" id="confirmarEliminarModal<?= $usuario['id'] ?>" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de que desea eliminar al usuario <strong><?= $usuario['nombre'] ?> <?= $usuario['apellidoP'] ?> <?= $usuario['apellidoM'] ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="index.php?c=usuario&m=eliminarUsuario&id=<?= $usuario['id'] ?>&nombre=<?= $usuario['nombre'] ?>&apellidoP=<?= $usuario['apellidoP'] ?>&apellidoM=<?= $usuario['apellidoM'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-labelledby="nuevoUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="nuevoUsuarioForm" action="index.php?c=usuario&m=guardarUsuario" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoUsuarioLabel">Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                        </div>
                        <div class="mb-3">
                            <label>Apellido Paterno</label>
                            <input type="text" name="apellidoP" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese el apellido paterno.</div>
                        </div>
                        <div class="mb-3">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellidoM" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese el apellido materno.</div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese un email válido.</div>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese una contraseña.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevoUsuarioForm')">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
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