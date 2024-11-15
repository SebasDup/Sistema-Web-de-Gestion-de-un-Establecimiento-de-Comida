<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'usuarios';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador') {
?>

    <h2 class="mt-4">Gestión de Empleados</h2>
    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#nuevoEmpleadoModal">Nuevo Empleado</button>
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
        <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar empleado...">
        <button class="btn btn-outline-secondary d-flex align-items-center" style="height: 46px;" type="button" onclick="buscar()">
            <i class="bi bi-search me-1"></i>
        </button>
        <button class="btn btn-outline-secondary" style="height: 46px;" type="button" onclick="cerrar()">
            <i class="bi bi-x-circle"></i>
        </button>
        <div class="invalid-feedback">No se encontraron resultados.</div>
    </div>
    <!-- Tabla de Empleados -->
    <table class="table-responsive table table-striped user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Correo electrónico</th>
                <th>Puesto</th>
                <th>Fecha de contratacion</th>
                <th>Salario</th>
                <th>Servicios hechos</th>
                <th>Zona asignada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $empleado): ?>
            <tr>
                <td><?= $empleado['id'] ?></td>
                <td><?= $empleado['nombre'] ?></td>
                <td><?= $empleado['apellidoP'] ?></td>
                <td><?= $empleado['apellidoM'] ?></td>
                <td><?= $empleado['email'] ?></td>
                <td><?= $empleado['puesto'] ?></td>
                <td><?= $empleado['fecha_contratacion'] ?></td>
                <td><?= $empleado['salario'] ?></td>
                <td><?= $empleado['servicios_realizados'] ?></td>
                <td><?= $empleado['zona_asignada'] ?></td>
                <td>
                    <button class="btn-Editar btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editarEmpleadoModal<?= $empleado['id'] ?>">Modificar</button>
                    <button class="btn-Eliminar btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal<?= $empleado['id'] ?>">Eliminar</button>
                </td>
            </tr>
        
            <!-- Modal Editar Empleado -->
            <div class="modal fade" id="editarEmpleadoModal<?= $empleado['id'] ?>" tabindex="-1" aria-labelledby="editarEmpleadoLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editarEmpleadoForm<?= $empleado['id'] ?>" action="index.php?c=empleado&m=actualizarEmpleado" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarEmpleadoLabel">Editar Empleado</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= $empleado['nombre'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese un nombre.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Apellido Paterno</label>
                                    <input type="text" name="apellidoP" class="form-control" value="<?= $empleado['apellidoP'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese apellido paterno.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Apellido Materno</label>
                                    <input type="text" name="apellidoM" class="form-control" value="<?= $empleado['apellidoM'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese apellido materno.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= $empleado['email'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese un email válido.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Puesto</label>
                                    <input type="text" name="puesto" class="form-control" value="<?= $empleado['puesto'] ?>" required>
                                    <div class="invalid-feedback">Por favor, ingrese el puesto.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Fecha de Contratación</label>
                                    <input type="date" name="fecha_contratacion" class="form-control" value="<?= $empleado['fecha_contratacion'] ?>" min="2020-01-01" required>
                                    <div class="invalid-feedback">Por favor, ingrese la fecha de contratación (2020 o posterior).</div>
                                </div>
                                <div class="mb-3">
                                    <label>Salario</label>
                                    <input type="number" name="salario" class="form-control" value="<?= $empleado['salario'] ?>" min="0" required>
                                    <div class="invalid-feedback">Por favor, ingrese el salario (no negativo).</div>
                                </div>
                                <div class="mb-3">
                                    <label>Zona</label>
                                    <select name="zona_asignada" class="form-control" required onchange="actualizarZonaId(this, 'zona_nombre_<?= $empleado['id'] ?>')">
                                        <?php foreach ($zonas as $zona): ?>
                                            <option value="<?= $zona['nombre'] ?>" <?= ($empleado['zona_asignada'] == $zona['nombre']) ? 'selected' : '' ?>>
                                                <?= strtoupper($zona['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="zona_id" id="zona_nombre_<?= $empleado['id'] ?>" value="<?= $empleado['zona_asignada'] ?>">
                                    <div class="invalid-feedback">Por favor, seleccione una zona.</div>
                                </div>
                                <div class="mb-3">
                                    <label>Contraseña</label>
                                    <input type="password" name="contrasena" class="form-control" value="<?= $empleado['contrasena'] ?>">
                                    <div class="invalid-feedback">Por favor, ingrese una contraseña.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-Agregar btn btn-success" onclick="validarFormulario('editarEmpleadoForm<?= $empleado['id'] ?>')">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <!-- Modal Confirmar Eliminar Empleado -->
            <div class="modal fade" id="confirmarEliminarModal<?= $empleado['id'] ?>" tabindex="-1" aria-labelledby="confirmarEliminarLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmarEliminarLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de que desea eliminar al empleado <strong><?= $empleado['nombre'] ?> <?= $empleado['apellidoP'] ?> <?= $empleado['apellidoM'] ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="index.php?c=empleado&m=eliminarEmpleado&id=<?= $empleado['id'] ?>&nombre=<?= $empleado['nombre'] ?>&apellidoP=<?= $empleado['apellidoP'] ?>&apellidoM=<?= $empleado['apellidoM'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- Modal Nuevo Empleado -->
    <div class="modal fade" id="nuevoEmpleadoModal" tabindex="-1" aria-labelledby="nuevoEmpleadoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="nuevoEmpleadoForm" action="index.php?c=empleado&m=guardarEmpleado" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoEmpleadoLabel">Nuevo Empleado</h5>
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
                            <div class="invalid-feedback">Por favor, ingrese apellido paterno.</div>
                        </div>
                        <div class="mb-3">
                            <label>Apellido Materno</label>
                            <input type="text" name="apellidoM" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese apellido materno.</div>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese un email válido.</div>
                        </div>
                        <div class="mb-3">
                            <label>Puesto</label>
                            <input type="text" name="puesto" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese el puesto.</div>
                        </div>
                        <div class="mb-3">
                            <label>Fecha de Contratación</label>
                            <input type="date" name="fecha_contratacion" class="form-control" min="2020-01-01" required>
                            <div class="invalid-feedback">Por favor, ingrese la fecha de contratación (2020 o posterior).</div>
                        </div>
                        <div class="mb-3">
                            <label>Salario</label>
                            <input type="number" name="salario" class="form-control" min="0" required>
                            <div class="invalid-feedback">Por favor, ingrese el salario (no negativo).</div>
                        </div>
                        <div class="mb-3">
                                <label>Zona</label>
                                <select name="id_zona" class="form-control" required>
                                    <option value="">Seleccionar Zona</option>
                                    <?php foreach ($zonas as $zona): ?>
                                        <option value="<?= $zona['id']; ?>"><?= $zona['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="zona_id" id="zona_id">
                                <div class="invalid-feedback">Por favor, seleccione una zona.</div>
                            </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" required>
                            <div class="invalid-feedback">Por favor, ingrese una contraseña.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevoEmpleadoForm')">Guardar</button>
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
<script>
function actualizarZonaId(selectElement, hiddenInputId) {
    document.getElementById(hiddenInputId).value = selectElement.value;
}
</script>
