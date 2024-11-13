<?php
session_start();
$currentPage = 'reportes';
include 'includes/header.php';
include 'Static/connect/db.php';
?>


<div class="content">
    <h2>Consultas</h2>
    <form class="user-form needs-validation" action="consultas.php" method="POST" novalidate>
        <div class="form-group">
            <label for="consulta">Seleccione una consulta:</label>
            <select class="form-control" name="consulta" required>
                <option value="">Seleccione una opción</option>
                <option value="reservaciones">Consultar reservaciones</option>
                <option value="MesasDisponibles">Consultar mesas disponibles</option>
            </select>
            <div class="invalid-feedback">Por favor seleccione una opción.</div>
        </div>
        <button class="btn btn-primary" type="submit">Consultar</button>
    </form>

    <h2>Reportes</h2>
    <form class="user-form needs-validation" action="generarReporte.php" method="POST" novalidate>
        <div class="form-group">
            <label for="reporte">Seleccione un reporte:</label>
            <select class="form-control" name="reporte" required>
                <option value="">Seleccione una opción</option>
                <option value="clientes_reservaciones">Clientes con reservaciones dentro de un rango de fechas</option>
                <option value="empleados_antiguedad">Empleados con  mayor antigüedad</option>
                <option value="mesas_disponibles">Mesas sin reservación en una fecha determinada</option>
                <option value="mesas_asignadas">Mesas asignadas a empleados</option>
                <option value="dia_mas_clientes">Día con más clientes dentro de un rango de fechas</option>
            </select>
            <div class="invalid-feedback">Por favor seleccione una opción.</div>
        </div>
        <button class="btn btn-primary" type="submit">Generar Reporte</button>
    </form>
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
</script>

<?php
include 'includes/footer.php';
?>