<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'mesas';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <h2 class="mt-4">Gestión de Mesas</h2>
    <?php if(isset($_SESSION['errorMesa'])) { ?>
        <div class="alert alert-dismissible fade show <?php echo isset($_SESSION['mensaje']) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
            <?php
                echo '<div style="color: red; font-size: 20px; font-weight: bold; text-align: center;">No hay mesas asignadas en tu zona.</div>';
                unset($_SESSION['errorMesa']); 
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>
    <?php if($usuarioRol == 'administrador') { ?>
        <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarMesaModal">Agregar Mesa</button>
        <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarZonaModal">Agregar Zona</button>
        <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#verZonasModal">Ver Zonas</button>

        <div class="modal fade" id="verZonasModal" tabindex="-1" aria-labelledby="verZonasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verZonasLabel">Lista de Zonas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <?php foreach ($zonas as $zona): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= $zona['nombre']; ?>
                                    <div>
                                        <button type="button" class="btn-icono editar" data-bs-toggle="modal" data-bs-target="#editarZonaModal<?= $zona['id']; ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn-icono eliminar" data-bs-toggle="modal" data-bs-target="#confirmarZonaModal<?= $zona['id']; ?>">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach ($zonas as $zona): ?>
            <div class="modal fade" id="editarZonaModal<?= $zona['id'] ?>" tabindex="-1" aria-labelledby="editarZonaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editarZonaForm<?= $zona['id'] ?>" action="index.php?c=mesa&m=actualizarZona" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarZonaLabel">Modificar Zona</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="zona_id" value="<?= $zona['id'] ?>">
                                <div class="mb-3">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" class="form-control" value="<?= strtoupper($zona['nombre']) ?>" required pattern="[A-Za-z]" title="Por favor, ingrese una sola letra del abecedario." style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                    <div class="invalid-feedback">Por favor, ingrese unicamente una letra para la zona A,B,C...</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-Agregar btn btn-success" onclick="validarFormulario('editarZonaForm<?= $zona['id'] ?>')">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmarZonaModal<?= $zona['id'] ?>" tabindex="-1" aria-labelledby="confirmarZonaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmarZonaLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de que desea eliminar la zona <strong><?= $zona['nombre'] ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="index.php?c=mesa&m=eliminarZona&id=<?= $zona['id'] ?>&nombre=<?= $zona['nombre'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php } ?>
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
    <div class="row">
        <?php foreach ($mesas as $mesa): ?>
            <div class="col-md-3 mb-4">
                <div class="mesa-card <?= $mesa['estado']; ?>">
                    <div class="botones-Mesa d-flex justify-content-between align-items-center">
                        <h3 class="text-start">Mesa <?= $mesa['numero']; ?></h3>
                        <?php if($usuarioRol == 'administrador'): ?>
                            <div>
                                <button type="button" class="btn-icono editar" data-bs-toggle="modal" data-bs-target="#editarMesaModal<?= $mesa['id']; ?>">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn-icono eliminar" data-bs-toggle="modal" data-bs-target="#confirmarMesaModal<?= $mesa['id']; ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <p>Capacidad: <?= $mesa['capacidad']; ?> personas</p>
                    <p>Estado: <?= $mesa['estado']; ?></p>
                    <p>Zona: 
                        <?php foreach ($zonas as $zona): ?>
                            <?php if ($zona['id'] == $mesa['zona_id']): ?>
                                <?= $zona['nombre']; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </p>
                    <p>
                        <div class="estado-buttons">
                            <form method="POST" action="index.php?c=mesa&m=actualizarEstado" class="d-inline">
                                <input type="hidden" name="mesa_id" value="<?= $mesa['id']; ?>">
                                <input type="hidden" name="numero" value="<?= $mesa['numero']; ?>">
                                <button type="submit" name="estado" value="disponible" 
                                        class="btn btn-sm <?= $mesa['estado'] == 'disponible' ? 'btn-success' : 'btn-outline-success'; ?>">
                                    Disponible
                                </button>
                                <button type="submit" name="estado" value="ocupada" 
                                        class="btn btn-sm <?= $mesa['estado'] == 'ocupada' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                                    Ocupada
                                </button>
                                <button type="submit" name="estado" value="reservada" 
                                        class="btn btn-sm <?= $mesa['estado'] == 'reservada' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                                    Reservada
                                </button>
                            </form>
                        </div>
                    </p>
                </div>
            </div>

            <div class="modal fade" id="editarMesaModal<?= $mesa['id'] ?>" tabindex="-1" aria-labelledby="editarMesaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editarMesaForm<?= $mesa['id'] ?>" action="index.php?c=mesa&m=actualizarMesa" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editarMesaLabel">Modificar Mesa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="mesa_id" value="<?= $mesa['id'] ?>">
                                <div class="mb-3">
                                    <label>Número</label>
                                    <input type="number" name="numero" class="form-control" value="<?= $mesa['numero'] ?>" required min="1">
                                    <div class="invalid-feedback">Por favor, ingrese un número de mesa válido.</div>
                                </div>
                                <div class="mb-3">
                            <label>Capacidad</label>
                                <input type="number" name="capacidad" class="form-control" value="<?= $mesa['capacidad'] ?>" required min="1">
                                <div class="invalid-feedback">Por favor, ingrese una capacidad válida.</div>
                            </div>
                            <div class="mb-3">
                                <label>Zona</label>
                                <select name="id_zona" class="form-control" required onchange="actualizarZonaId(this, 'zona_id')">
                                    <option value="">Seleccionar Zona</option>
                                    <?php foreach ($zonas as $zona): ?>
                                        <option value="<?= $zona['id']; ?>" <?= $zona['id'] == $mesa['zona_id'] ? 'selected' : ''; ?>><?= $zona['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="zona_id" id="zona_id">
                                <div class="invalid-feedback">Por favor, seleccione una zona.</div>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-Agregar btn btn-success" onclick="validarFormulario('editarMesaForm<?= $mesa['id'] ?>')">Guardar Cambios</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmarMesaModal<?= $mesa['id'] ?>" tabindex="-1" aria-labelledby="confirmarMesaLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmarMesaLabel">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Está seguro de que desea eliminar la mesa <strong><?= $mesa['numero'] ?></strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <a href="index.php?c=mesa&m=eliminarMesa&id=<?= $mesa['id'] ?>&numero=<?= $mesa['numero'] ?>" class="btn btn-danger">Eliminar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php if($_SESSION['rolUsuario'] == 'administrador') { ?>
    <div class="modal fade" id="agregarMesaModal" tabindex="-1" aria-labelledby="agregarMesaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="nuevaMesaForm" action="index.php?c=mesa&m=guardarMesa" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarMesaLabel">Agregar Mesa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Número</label>
                            <input type="number" name="numero" class="form-control" required min="1">
                            <div class="invalid-feedback">Por favor, ingrese un número de mesa válido.</div>
                        </div>
                        <div class="mb-3">
                            <label>Capacidad</label>
                            <input type="number" name="capacidad" class="form-control" required min="1">
                            <div class="invalid-feedback">Por favor, ingrese una capacidad válida.</div>
                        </div>
                        <div class="mb-3">
                            <label>Zona</label>
                            <select name="id_zona" class="form-control" required onchange="actualizarZonaId(this)">
                                <option value="">Seleccionar Zona</option>
                                <?php foreach ($zonas as $zona): ?>
                                    <option value="<?= $zona['id']; ?>"><?= $zona['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="zona_id" id="zona_id">
                            <div class="invalid-feedback">Por favor, seleccione una zona.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevaMesaForm')">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="agregarZonaModal" tabindex="-1" aria-labelledby="agregarZonaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="nuevaZonaForm" action="index.php?c=mesa&m=guardarZona" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarZonaLabel">Agregar Zona</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Zona</label>
                            <input type="text" name="zona" class="form-control" required pattern="[A-Za-z]" title="Por favor, ingrese una sola letra del abecedario." style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                            <div class="invalid-feedback">Por favor, ingrese unicamente una letra para la zona A,B,C...</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevaZonaForm')">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<?php
require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
?>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>
<?php
}
?>
