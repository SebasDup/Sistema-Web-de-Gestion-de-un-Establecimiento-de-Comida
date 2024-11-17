<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'reservaciones';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<h2 class="mt-4">Gestión de Reservaciones</h2>
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

<?php if($usuarioRol == 'administrador') { ?>
    <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarReservacionModal">Agregar Reservacion</button>
<?php } ?>

<?php if($_SESSION['rolUsuario'] == 'administrador') { ?>

    <div class="modal fade" id="agregarReservacionModal" tabindex="-1" aria-labelledby="agregarReservacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="nuevaReservacionForm" action="index.php?c=reservacion&m=guardarReservacion" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarReservacionLabel">Agregar Reservacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label>Cliente</label>
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                        <ul id="listaClientes" class="list-group mt-2"></ul>
                        <input type="hidden" name="cliente_id" id="cliente_id">
                        <div class="invalid-feedback">Por favor, seleccione un cliente válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="Personas">Personas</label>
                        <input type="number" name="personas" class="form-control" required min="1" oninput="validity.valid||(value='');">
                        <div class="invalid-feedback">Por favor, ingrese la cantidad de personas.</div>
                    </div>

                    <div class="mb-3">
                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="hora">Hora:</label>
                        <input type="time" id="hora" name="hora" required>
                    </div>

                    <div class="mb-3">
                        <label>Mesa</label>
                        <select name="id_mesa" class="form-control" required onchange="actualizarMesaId(this)">
                            <option value="">Seleccionar Mesa</option>
                            <?php foreach ($mesas as $mesa): ?>
                                <option value="<?= $mesa['id']; ?>">Mesa <?= $mesa['numero']; ?> (capacidad: <?= $mesa['capacidad']; ?> personas)</option>
                            <?php endforeach; ?>
                        </select>
                            <input type="hidden" name="mesa_id" id="mesa_id">
                            <div class="invalid-feedback">Por favor, seleccione una mesa.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="comentarios">Comentarios Adicionales:</label>
                        <textarea id="comentarios" name="comentarios" rows="4" class="form-control" placeholder="Ingrese cualquier comentario adicional aquí..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-Agregar btn btn-primary" onclick="validarFormulario('nuevaReservacionForm')">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('buscarCliente').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const clientes = <?php echo json_encode($usuarios); ?>;
        const listaClientes = document.getElementById('listaClientes');
        listaClientes.innerHTML = '';

        clientes.forEach(cliente => {
            const nombreCompleto = `${cliente.nombre} ${cliente.apellidoP} ${cliente.apellidoM}`.toLowerCase();
            if (nombreCompleto.includes(query)) {
                const li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = nombreCompleto;
                li.dataset.id = cliente.id;
                li.addEventListener('click', function() {
                    document.getElementById('buscarCliente').value = nombreCompleto;
                    document.getElementById('cliente_id').value = cliente.id;
                    listaClientes.innerHTML = '';
                });
                listaClientes.appendChild(li);
            }
        });
    });

    document.getElementById('agregarReservacionModal').addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('nuevaReservacionForm');
        form.reset();
        const invalidFeedbacks = form.querySelectorAll('.invalid-feedback');
        invalidFeedbacks.forEach(feedback => feedback.style.display = 'none');
    });

    let currentWeekStart = new Date();
    currentWeekStart.setDate(currentWeekStart.getDate() - (currentWeekStart.getDay() === 0 ? 6 : currentWeekStart.getDay() - 1));

    function changeWeek(offset) {
        currentWeekStart.setDate(currentWeekStart.getDate() + (offset * 7));
        updateReservations();
    }

    function updateReservations() {
        const weekStart = new Date(currentWeekStart);
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);

        document.getElementById('weekRange').textContent = `${formatDate(weekStart)} - ${formatDate(weekEnd)}`;

        const xhr = new XMLHttpRequest();
        xhr.open('GET', `index.php?c=reservacion&m=obtenerReservaciones&start=${weekStart.toISOString().split('T')[0]}&end=${weekEnd.toISOString().split('T')[0]}`, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const reservaciones = JSON.parse(xhr.responseText);
                const tbody = document.querySelector('table tbody');
                tbody.innerHTML = '';

                for (let hour = 6; hour < 24; hour++) {
                    const tr = document.createElement('tr');
                    const tdHour = document.createElement('td');
                    tdHour.textContent = `${String(hour).padStart(2, '0')}:00`;
                    tr.appendChild(tdHour);

                    for (let day = 0; day < 7; day++) {
                        const currentDate = new Date(weekStart);
                        currentDate.setDate(weekStart.getDate() + day);
                        const td = document.createElement('td');
                        const reservacionesDiaHora = reservaciones.filter(reservacion => {
                            const reservacionFecha = new Date(reservacion.fecha);
                            return reservacionFecha.getFullYear() === currentDate.getFullYear() &&
                                reservacionFecha.getMonth() === currentDate.getMonth() &&
                                reservacionFecha.getDate() === currentDate.getDate() &&
                                reservacionFecha.getHours() === hour;
                        });

                        if (reservacionesDiaHora.length > 0) {
                            const div = document.createElement('div');
                            div.className = 'reservacion-count';
                            div.textContent = reservacionesDiaHora.length;
                            div.dataset.day = currentDate.toISOString().split('T')[0];
                            div.dataset.hour = hour;
                            div.addEventListener('click', function() {
                                showReservationsModal(this.dataset.day, this.dataset.hour, reservacionesDiaHora);
                            });
                            td.appendChild(div);
                        }
                        tr.appendChild(td);
                    }
                    tbody.appendChild(tr);
                }
            }
        };
        xhr.send();
    }

    function formatDate(date) {
        const months = ["ENE", "FEB", "MAR", "ABR", "MAY", "JUN", "JUL", "AGO", "SEP", "OCT", "NOV", "DIC"];
        return `${date.getDate()}/${months[date.getMonth()]}/${date.getFullYear()}`;
    }

    function showReservationsModal(day, hour, reservaciones) {
        const modal = new bootstrap.Modal(document.getElementById('reservacionesModal'));
        const modalBody = document.getElementById('reservacionesModalBody');
        modalBody.innerHTML = '';
    
        const clientes = <?php echo json_encode($usuarios); ?>;
        const mesas = <?php echo json_encode($mesas); ?>;
        const reservacionesMesas = <?php echo json_encode($reservaciones_mesas); ?>;
    
        reservaciones.forEach(reservacion => {
            const cliente = clientes.find(c => c.id == reservacion.cliente_id);
            const nombreCompleto = cliente ? `${cliente.nombre} ${cliente.apellidoP} ${cliente.apellidoM}` : 'Cliente no encontrado';
    
            const mesasReservacion = reservacionesMesas.filter(rm => rm.reservacion_id == reservacion.id).map(rm => {
                const mesa = mesas.find(m => m.id == rm.mesa_id);
                return mesa ? `Mesa ${mesa.numero}` : 'Mesa no encontrada';
            }).join(', ');
    
            const div = document.createElement('div');
            div.className = 'reservacion';
            div.innerHTML = `
                Cliente: <strong>${nombreCompleto}</strong><br>
                Mesas: ${mesasReservacion}<br>
                Personas: ${reservacion.personas}
            `;
            modalBody.appendChild(div);
        });
    
        modal.show();
    }

    function goToToday() {
        currentWeekStart = new Date();
        currentWeekStart.setDate(currentWeekStart.getDate() - (currentWeekStart.getDay() === 0 ? 6 : currentWeekStart.getDay() - 1));
        updateReservations();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateReservations();
    });
</script>

<h3 class="text-center">Calendario de Reservaciones</h3>
<div class="container mt-5 text-center d-flex justify-content-center align-items-center">
    <i class="fas fa-arrow-left btn btn-link fa-2x" onclick="changeWeek(-1)" style="cursor: pointer; text-decoration: none;"></i>
    
    <span id="weekRange" class="mx-3"></span>
    <i class="fas fa-arrow-right btn btn-link fa-2x" onclick="changeWeek(1)" style="cursor: pointer; text-decoration: none;"></i>
</div>
<button class="btn btn-link fa-2x mx-3" onclick="goToToday()" style="cursor: pointer; text-decoration: none;">Ir a semana actual</button>
<div class="container mt-3 text-center">
    <div class="table-responsive d-inline-block" style="max-width: 100%;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                    <th>Sábado</th>
                    <th>Domingo</th>
                </tr>
            </thead>
            <tbody>
                <?php $mesa_numeros = [];
                foreach ($mesas as $mesa) {
                    $mesa_numeros[$mesa['id']] = $mesa['numero'];
                }
                ?>
                <?php for ($hour = 6; $hour < 24; $hour++): ?>
                    <tr>
                        <td><?php echo str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00'; ?></td>
                        <?php for ($day = 1; $day <= 7; $day++): ?>
                            <td>
                                <?php 
                                $reservacionesDiaHora = array_filter($reservaciones, function($reservacion) use ($day, $hour) {
                                    $reservacionFecha = new DateTime($reservacion['fecha']);
                                    return $reservacionFecha->format('N') == $day && $reservacionFecha->format('H') == $hour;
                                });
                                if (count($reservacionesDiaHora) > 0): ?>
                                    <div class="reservacion-count" data-day="<?= $day ?>" data-hour="<?= $hour ?>" onclick="showReservationsModal('<?= $day ?>', '<?= $hour ?>', <?= htmlspecialchars(json_encode($reservacionesDiaHora)) ?>)">
                                        <?= count($reservacionesDiaHora) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="reservacionesModal" tabindex="-1" aria-labelledby="reservacionesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservacionesModalLabel">Reservaciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reservacionesModalBody">
                <!-- Reservaciones se cargarán aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .reservacion {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
    }
    .reservacion-count {
        background-color: #007bff;
        color: white;
        padding: 5px;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<?php } ?>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>
<?php 
    } 
    require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
?>