<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'menu';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <h2 class="mt-4">Gestión de Menú</h2>
    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarMenuModal">Agregar platillo al menú</button>
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar platillo...">
        <button class="btn btn-outline-secondary d-flex align-items-center" style="height: 46px;" type="button" onclick="buscarUsuario()">
            <i class="bi bi-search me-1"></i>
        </button>
        <button class="btn btn-outline-secondary" style="height: 46px;" type="button" onclick="mostrarTodosUsuarios()">
            <i class="bi bi-x-circle"></i>
        </button>
        <div class="invalid-feedback">No se encontraron resultados.</div>
    </div>
    <!-- Mostrar mensajes de error o éxito por medio de las variables de sesión -->
    <?php if(isset($_SESSION['mensaje']) || isset($_SESSION['error'])): ?>
        <div class="alert alert-dismissible fade show <?php echo isset($_SESSION['mensaje']) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php 
            if(isset($_SESSION['mensaje'])) {
                echo htmlspecialchars($_SESSION['mensaje']);
                unset($_SESSION['mensaje']); 
            }
            if(isset($_SESSION['error'])) {
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); 
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Tabla de Menú -->
    <table class="table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menu as $menu): ?>
            <tr>
                <td><?= $menu['id'] ?></td>
                <td><?= $menu['nombre'] ?></td>
                <td><?= $menu['descripcion'] ?></td>
                <td><?= $menu['precio'] ?></td>
                <td><?= $menu['categoria'] ?></td>
                <td>
                    <button class="btn-Editar btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editarMenuModal<?= $menu['id'] ?>">Modificar</button>
                    <button class="btn-Eliminar btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmarMenuModal<?= $menu['id'] ?>">Eliminar</button>
                </td>
            </tr>
        
            <!-- Modal Editar Platillo -->
            <div class="modal fade" id="editarMenuModal<?= $menu['id'] ?>" tabindex="-1" aria-labelledby="editarMenuLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editarMenuForm<?= $menu['id'] ?>" action="UMenu.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarMenuLabel">Modificar Platillo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $menu['id'] ?>">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= $menu['nombre'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Descripción</label>
                                    <input type="text" name="descripcion" class="form-control" value="<?= $menu['descripcion'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese una descripción.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Precio</label>
                                    <input type="number" name="precio" class="form-control" value="<?= $menu['precio'] ?>" min="0" required>
                                    <div class="invalid-feedback">Por favor, ingrese un precio.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Categoría</label>
                                    <input type="text" name="categoria" class="form-control" value="<?= $menu['categoria'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese una categoría.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-Agregar btn btn-success" onclick="validarFormulario('editarMenuForm<?= $menu['id'] ?>')">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <!-- Modal Confirmar Eliminar Platillo -->
            <div class="modal fade" id="confirmarMenuModal<?= $menu['id'] ?>" tabindex="-1" aria-labelledby="confirmarMenuLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmarMenuLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de que desea eliminar el platillo <strong><?= $menu['nombre'] ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="DMenu.php?id=<?= $menu['id'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Modal Nuevo Platillo -->
    <div class="modal fade" id="agregarMenuModal" tabindex="-1" aria-labelledby="agregarMenuLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="nuevoMenuForm" action="RMenu.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarMenuLabel">Agregar Platillo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                        </div>
                        <div class="mb-3">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese una descripción.</div>
                        </div>
                        <div class="mb-3">
                            <label>Precio</label>
                            <input type="number" name="precio" class="form-control" value="<?= $menu['precio'] ?>" min="0" required>
                            <div class="invalid-feedback">Por favor, ingrese un precio.</div>
                        </div>
                        <div class="mb-3">
                            <label>Categoría</label>
                            <input type="text" name="categoria" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese una categoría.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevoMenuForm')">Guardar</button>
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