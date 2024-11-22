<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'reportes';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <div class="content">
        <h2>Consultas</h2>
        <form class="user-form needs-validation" action="controlador/consultasController.php" method="POST" novalidate>
            <div class="form-group">
                <label for="consulta">Seleccione una consulta:</label>
                <select class="form-control" name="consulta" required>
                    <option value="">Seleccione una opción</option>
                    <option value="mesas_estado">Consultar mesas por estado</option>
                    <option value="reservaciones_estado">Consultar reservaciones por estado</option>
                    <option value="historial_servicio_mesas">Historial de servicio por mesa</option>
                    <option value="empleados_puesto">Consultar empleados por puesto</option>
                </select>
                <div class="invalid-feedback">Por favor seleccione una opción.</div>
            </div>
            <div id="estadoMesa" style="display: none;">
                <div class="form-group">
                    <label for="estado">Estado de la mesa:</label>
                    <select class="form-control" name="estado" id="estado">
                        <option value="disponible">Disponible</option>
                        <option value="reservada">Reservada</option>
                        <option value="ocupada">Ocupada</option>
                    </select>
                </div>
            </div>
            <div id="estadoReservacion" style="display: none;">
                <div class="form-group">
                    <label for="estado_reservacion">Estado de la reservación:</label>
                    <select class="form-control" name="estado_reservacion" id="estado_reservacion">
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>
            </div>
            <div id="empleadosPuesto" style="display: none;">
                <div class="form-group">
                    <label for="puesto">Puesto:</label>
                    <select class="form-control" name="puesto" id="puesto">
                        <option value="">Todos los puestos</option>
                        <option value="Mesero">Mesero</option>
                        <option value="Cocinero">Cocinero</option>
                        <option value="Cajero">Cajero</option>
                    </select>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Generar consulta en PDF</button>
        </form>

        <h2>Reportes</h2>
        <form class="user-form needs-validation" action="controlador/reportesController.php" method="POST" novalidate>
            <div class="form-group">
                <label for="reporte">Seleccione un reporte:</label>
                <select class="form-control" id="reporteSelect" name="reporte" required>
                    <option value="">Seleccione una opción</option>
                    <option value="clientes_reservaciones">Clientes con reservaciones dentro de un rango de fechas</option>
                    <option value="empleados_antiguedad">Empleados con mayor antigüedad</option>
                    <option value="empleados_actividad">Empleados con mayor actividad</option>
                    <option value="platillos_vendidos">Platillos más vendidos</option>
                    <option value="ingresos_totales">Ingresos totales por período</option>
                    <option value="ingresos_categoria">Ingresos por categoría</option>
                </select>
                <div class="invalid-feedback">Por favor seleccione una opción.</div>
            </div>

            <div id="categoriaSelect" style="display: none;">
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <select class="form-control" name="categoria" id="categoria">
                        <?php
                        require_once("modelo/Conexion.php");
                        $query = "SELECT DISTINCT categoria FROM menu ORDER BY categoria";
                        $result = mysqli_query($conn, $query);
                        echo "<option value=''>Todas las categorías</option>";
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['categoria']) . "'>" . htmlspecialchars($row['categoria']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div id="fechasRango" style="display: none;">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
                </div>
            </div>
            
            <div id="periodosIngresos" style="display: none;">
                <div class="form-group">
                    <label for="tipo_periodo">Tipo de período:</label>
                    <select class="form-control" name="tipo_periodo" id="tipo_periodo">
                        <option value="dia">Por día</option>
                        <option value="mes">Por mes</option>
                        <option value="anio">Por año</option>
                    </select>
                </div>
            </div>
            
            <button class="btn btn-primary" type="submit">Generar Reporte En PDF</button>
        </form>
    </div>

    <script>
        // Función para mostrar/ocultar elementos basados en la selección
        function actualizarFormulario(valor) {
            var fechasRango = document.getElementById('fechasRango');
            var periodosIngresos = document.getElementById('periodosIngresos');
            var categoriaSelect = document.getElementById('categoriaSelect');
            
            fechasRango.style.display = 'none';
            periodosIngresos.style.display = 'none';
            categoriaSelect.style.display = 'none';
            
            if (valor === 'clientes_reservaciones') {
                fechasRango.style.display = 'block';
            } else if (valor === 'ingresos_totales') {
                periodosIngresos.style.display = 'block';
            } else if (valor === 'ingresos_categoria') {
                categoriaSelect.style.display = 'block';
            }
            
            // Guardar la selección actual
            sessionStorage.setItem('ultimoReporte', valor);
        }

        // Guardar selección de consulta en sessionStorage
        document.querySelector('select[name="consulta"]').addEventListener('change', function() {
            var estadoMesa = document.getElementById('estadoMesa');
            var estadoReservacion = document.getElementById('estadoReservacion');
            var empleadosPuesto = document.getElementById('empleadosPuesto');
            
            estadoMesa.style.display = 'none';
            estadoReservacion.style.display = 'none';
            empleadosPuesto.style.display = 'none';
            
            sessionStorage.setItem('ultimaConsulta', this.value);
            
            if (this.value === 'mesas_estado') {
                estadoMesa.style.display = 'block';
                sessionStorage.setItem('ultimoEstadoMesa', document.getElementById('estado').value);
            } else if (this.value === 'reservaciones_estado') {
                estadoReservacion.style.display = 'block';
                sessionStorage.setItem('ultimoEstadoReservacion', document.getElementById('estado_reservacion').value);
            } else if (this.value === 'empleados_puesto') {
                empleadosPuesto.style.display = 'block';
                sessionStorage.setItem('ultimoPuestoEmpleado', document.getElementById('puesto').value);
            }
        });

        // Guardar selecciones de submenús
        document.getElementById('estado').addEventListener('change', function() {
            sessionStorage.setItem('ultimoEstadoMesa', this.value);
        });

        document.getElementById('estado_reservacion').addEventListener('change', function() {
            sessionStorage.setItem('ultimoEstadoReservacion', this.value);
        });

        document.getElementById('puesto').addEventListener('change', function() {
            sessionStorage.setItem('ultimoPuestoEmpleado', this.value);
        });

        // Recuperar selecciones al cargar la página
        window.addEventListener('load', function() {
            var ultimaConsulta = sessionStorage.getItem('ultimaConsulta');
            if (ultimaConsulta) {
                var consultaSelect = document.querySelector('select[name="consulta"]');
                consultaSelect.value = ultimaConsulta;
                
                // Restaurar submenús según la última consulta
                if (ultimaConsulta === 'mesas_estado') {
                    document.getElementById('estadoMesa').style.display = 'block';
                    var ultimoEstado = sessionStorage.getItem('ultimoEstadoMesa');
                    if (ultimoEstado) {
                        document.getElementById('estado').value = ultimoEstado;
                    }
                } else if (ultimaConsulta === 'reservaciones_estado') {
                    document.getElementById('estadoReservacion').style.display = 'block';
                    var ultimoEstadoRes = sessionStorage.getItem('ultimoEstadoReservacion');
                    if (ultimoEstadoRes) {
                        document.getElementById('estado_reservacion').value = ultimoEstadoRes;
                    }
                } else if (ultimaConsulta === 'empleados_puesto') {
                    document.getElementById('empleadosPuesto').style.display = 'block';
                    var ultimoPuesto = sessionStorage.getItem('ultimoPuestoEmpleado');
                    if (ultimoPuesto) {
                        document.getElementById('puesto').value = ultimoPuesto;
                    }
                }
            }

            // Resto del código de window.load existente...
            var ultimoReporte = sessionStorage.getItem('ultimoReporte');
            if (ultimoReporte) {
                var reporteSelect = document.getElementById('reporteSelect');
                reporteSelect.value = ultimoReporte;
                actualizarFormulario(ultimoReporte);
            }
        });

        // Manejo del select de consultas
        document.querySelector('select[name="consulta"]').addEventListener('change', function() {
            var estadoMesa = document.getElementById('estadoMesa');
            var estadoReservacion = document.getElementById('estadoReservacion');
            var empleadosPuesto = document.getElementById('empleadosPuesto');
            
            estadoMesa.style.display = 'none';
            estadoReservacion.style.display = 'none';
            empleadosPuesto.style.display = 'none';
            
            if (this.value === 'mesas_estado') {
                estadoMesa.style.display = 'block';
            } else if (this.value === 'reservaciones_estado') {
                estadoReservacion.style.display = 'block';
            } else if (this.value === 'empleados_puesto') {
                empleadosPuesto.style.display = 'block';
            }
        });

        // Manejo del select de reportes
        document.getElementById('reporteSelect').addEventListener('change', function() {
            actualizarFormulario(this.value);
        });

        // Recuperar y aplicar la última selección al cargar la página
        window.addEventListener('load', function() {
            var ultimoReporte = sessionStorage.getItem('ultimoReporte');
            if (ultimoReporte) {
                var reporteSelect = document.getElementById('reporteSelect');
                reporteSelect.value = ultimoReporte;
                actualizarFormulario(ultimoReporte);
            }
        });

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
    }
    require_once("layouts/footer.php");
} else {
    header("Location: logout.php");
}
?>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>