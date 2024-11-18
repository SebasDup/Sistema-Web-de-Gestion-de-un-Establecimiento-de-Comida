<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'promociones';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<h2 class="mt-4">Gestión de Promociones</h2>
<?php if($usuarioRol == 'administrador'): ?>
    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarPromocionModal">
        <i class="fas fa-plus me-2"></i>Nueva Promoción
    </button>
<?php endif; ?>

<?php if(isset($_SESSION['mensaje']) || isset($_SESSION['error'])): ?>
    <div class="alert alert-dismissible fade show <?php echo isset($_SESSION['mensaje']) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php 
        echo isset($_SESSION['mensaje']) ? htmlspecialchars($_SESSION['mensaje']) : htmlspecialchars($_SESSION['error']);
        unset($_SESSION['mensaje']); 
        unset($_SESSION['error']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($promociones as $promo): ?>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($promo['titulo']) ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= htmlspecialchars($promo['fecha_inicio']) ?> - <?= htmlspecialchars($promo['fecha_fin']) ?>
                </h6>
                <p class="card-text"><?= htmlspecialchars($promo['descripcion']) ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-success fs-6">
                        <i class="fas fa-tag me-1"></i>
                        $<?= number_format($promo['descuento'], 2) ?>
                    </span>
                    <?php if($usuarioRol == 'administrador'): ?>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editarPromoModal<?= $promo['id'] ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#eliminarPromoModal<?= $promo['id'] ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modales para agregar -->
<div class="modal fade" id="agregarPromocionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="nuevaPromocionForm" action="index.php?c=promocion&m=guardarPromocion" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Promoción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required maxlength="100">
                        <div class="invalid-feedback">
                            Por favor ingrese un título válido.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        <div class="invalid-feedback">
                            Por favor ingrese una descripción.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento ($)</label>
                        <input type="number" class="form-control" id="descuento" name="descuento" required step="0.01" min="0" max="999.99">
                        <div class="invalid-feedback">
                            Por favor ingrese un descuento válido entre 0 y 999.99.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required min="<?php echo date('Y-m-d'); ?>">
                        <div class="invalid-feedback">
                            Por favor seleccione una fecha de inicio.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required min="<?php echo date('Y-m-d'); ?>">
                        <div class="invalid-feedback">
                            Por favor seleccione una fecha de fin.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Promoción -->
<?php foreach ($promociones as $promo): ?>
<div class="modal fade" id="editarPromoModal<?= $promo['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editarPromoForm<?= $promo['id'] ?>" action="index.php?c=promocion&m=actualizarPromocion" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Promoción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $promo['id'] ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($promo['titulo']) ?>" required>
                        <div class="invalid-feedback">Por favor ingrese un título válido.</div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required><?= htmlspecialchars($promo['descripcion']) ?></textarea>
                        <div class="invalid-feedback">Por favor ingrese una descripción.</div>
                    </div>
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento ($)</label>
                        <input type="number" class="form-control" name="descuento" value="<?= $promo['descuento'] ?>" required step="0.01" min="0" max="999.99">
                        <div class="invalid-feedback">Por favor ingrese un descuento válido.</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" value="<?= $promo['fecha_inicio'] ?>" required>
                        <div class="invalid-feedback">Por favor seleccione una fecha de inicio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de fin</label>
                        <input type="date" class="form-control" name="fecha_fin" value="<?= $promo['fecha_fin'] ?>" required>
                        <div class="invalid-feedback">Por favor seleccione una fecha de fin.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Promoción -->
<div class="modal fade" id="eliminarPromoModal<?= $promo['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar la promoción <strong><?= htmlspecialchars($promo['titulo']) ?></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="index.php?c=promocion&m=eliminarPromocion&id=<?= $promo['id'] ?>" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>
<?php 
    } 
    require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
?>