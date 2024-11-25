<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'configuracion';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
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
<!-- Modal para editar horario -->
<?php foreach ($horarios as $horario): ?>
<div class="modal fade" id="editarHorarioModal<?= $horario['id'] ?>" tabindex="-1" aria-labelledby="editarHorarioLabel<?= $horario['id'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-gradient-warning text-dark">
                <h5 class="modal-title" id="editarHorarioLabel<?= $horario['id'] ?>">
                    <i class="fas fa-clock me-2"></i>Configurar Horario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editarHorarioForm<?= $horario['id'] ?>" action="index.php?c=configuracion&m=actualizarHorario" method="POST" class="needs-validation" novalidate>
                <div class="modal-body px-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Día de la semana</label>
                        <input type="text" class="form-control" name="dia" value="<?= $horario['dia_semana'] ?>" readonly>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label fw-bold">Hora de apertura</label>
                            <input type="time" class="form-control" name="apertura" value="<?= $horario['hora_apertura'] ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Hora de cierre</label>
                            <input type="time" class="form-control" name="cierre" value="<?= $horario['hora_cierre'] ?>" required>
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input type="hidden" name="estado" value="Cerrado">
                        <input class="form-check-input" type="checkbox" name="estado" id="estadoHorario<?= $horario['id'] ?>" value="Abierto" <?= $horario['estado'] == 'Abierto' ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="estadoHorario<?= $horario['id'] ?>" id="labelEstado<?= $horario['id'] ?>" style="color: <?= $horario['estado'] == 'Abierto' ? '#198754' : '#dc3545' ?>">
                            <?= $horario['estado'] ?>
                        </label>
                    </div>
                    <input type="hidden" name="id" value="<?= $horario['id'] ?>">
                    <script>
                        document.getElementById('estadoHorario<?= $horario['id'] ?>').addEventListener('change', function() {
                            const label = document.getElementById('labelEstado<?= $horario['id'] ?>');
                            if (this.checked) {
                                label.style.color = '#198754';
                                label.textContent = 'Abierto';
                            } else {
                                label.style.color = '#dc3545';
                                label.textContent = 'Cerrado';
                            }
                        });
                    </script>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<h2 class="mt-4">Configuración del horario</h2>
<div class="container mt-5 animate__animated animate__fadeIn">
    <div class="card shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">Horario del Restaurante</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="horarioTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center">Día</th>
                            <th class="text-center">Apertura</th>
                            <th class="text-center">Cierre</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($horarios as $horario): 
                        ?>
                        <tr class="align-middle">
                            <td class="text-center fw-bold"><?= $horario['dia_semana'] ?></td>
                            <td class="text-center"><?= $horario['hora_apertura'] ?></td>
                            <td class="text-center"><?= $horario['hora_cierre'] ?></td>
                            <td class="text-center">
                            <span class="badge <?= trim($horario['estado']) === 'abierto' ? 'bg-success' : 'bg-danger' ?>"><?= $horario['estado'] ?></span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-2 hover-shadow" data-bs-toggle="modal" data-bs-target="#editarHorarioModal<?= $horario['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card {
    border-radius: 15px;
    overflow: hidden;
    margin: 0 auto; /* Center align the card */
    max-width: 90%; /* Make it responsive */
    background-color: #f8f1e4; /* Pastel brown */
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    background-color: #f4d3a1; /* Pastel orange */
}

.badge {
    padding: 8px 12px;
    border-radius: 20px;
    background-color: #f4d3a1; /* Pastel orange */
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate__fadeIn {
    animation: fadeIn 0.6s ease-out;
}

@media (min-width: 768px) {
    .card {
        max-width: 70%; /* Adjust the width for larger screens */
    }
}

@media (min-width: 992px) {
    .card {
        max-width: 50%; /* Adjust the width for even larger screens */
    }
}
</style>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>
<?php 
    } 
} else {
    header("Location: logout.php");
}
?>