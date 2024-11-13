<?php
session_start();
include 'includes/headerC.php';
include 'Static/connect/db.php';

$query_promos = "SELECT *, 
                 CASE 
                     WHEN CURDATE() BETWEEN fecha_inicio AND fecha_fin THEN 1 
                     ELSE 2 
                 END AS activo 
                 FROM promociones";
$result_promos = mysqli_query($conn, $query_promos);

$query_horario = "SELECT * FROM horarios";
$result_horario = mysqli_query($conn, $query_horario);

// Show messages if they exist
if (isset($_SESSION['message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo $_SESSION['message'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    unset($_SESSION['message']);
}

// Load required scripts
echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>';
?>
<section id="promociones" class="section">
    <h2>Promociones Actuales</h2>
    <div class="promo-container">
        <?php while($promo = mysqli_fetch_assoc($result_promos)) { 
            if ($promo['activo'] == 1) { ?>
            <div class="promo-card">
                <img src="Static/img/promo2.jpg" alt="Imagen de la promoción">
                <h3><?php echo $promo['titulo']; ?></h3>
                <p><?php echo $promo['descripcion']; ?></p>
                <span class="promo-precio">$<?php echo $promo['descuento']; ?></span>
            </div>
        <?php } } ?>
    </div>
</section>
        
<section id="horario" class="section">
    <h2>Horario de Atención</h2>
    <div class="horario-container">
        <table>
            <tr>
                <th>Día</th>
                <th>Apertura</th>
                <th>Cierre</th>
            </tr>
            <?php while($horario = mysqli_fetch_assoc($result_horario)) { ?>
                <tr>
                    <td><?php echo $horario['dia_semana']; ?></td>
                    <td><?php echo $horario['hora_apertura']; ?></td>
                    <td><?php echo $horario['hora_cierre']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>

<?php
// Only show reservations section if user is logged in
if(isset($_SESSION['usuario']) && $_SESSION['rolUsuario'] == 'cliente') {
?>
<section id="reservas" class="section">
    <h2>Realizar Reservación</h2>
    <div class="reserva-container">
        <form action="procesar_reserva.php" method="POST">
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" required>
            </div>
            
            <div class="form-group">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora" required>
            </div>
            
            <div class="form-group">
                <label for="personas">Número de Personas:</label>
                <input type="number" id="personas" name="personas" min="1" max="20" required>
            </div>
            
            <div class="form-group">
                <label for="mesas">Número de Mesas:</label>
                <input type="number" id="mesas" name="mesas" min="1" max="5" required>
            </div>
            
            <div class="form-group">
                <label for="comentarios">Comentarios Adicionales:</label>
                <textarea id="comentarios" name="comentarios" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn-reservar">Realizar Reservación</button>
        </form>
    </div>
</section>
<section id="mis-reservas" class="section">
<h2>Mis Reservaciones</h2>
<div class="mis-reservas-container">
    <button id="verReservasBtn" class="btn btn-ver-reservas">Ver mis reservaciones</button>
    <div id="reservasList" style="display: none;">
        <?php
        $query_reservas = "SELECT * FROM reservaciones WHERE cliente_id = '$usuarioId'";
        $result_reservas = mysqli_query($conn, $query_reservas);
        if (mysqli_num_rows($result_reservas) > 0) {
            while ($reserva = mysqli_fetch_assoc($result_reservas)) {
                echo "<div class='reserva-card'>";
                $datetime = new DateTime($reserva['fecha']);
                echo "<p><strong>Fecha:</strong> " . $datetime->format('Y-m-d') . "</p>";
                echo "<p><strong>Hora:</strong> " . $datetime->format('H:i') . "</p>";
                echo "<p><strong>Número de Personas:</strong> " . $reserva['personas'] . "</p>";
                echo "<p><strong>Estado:</strong> " . $reserva['estado'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No tienes reservaciones.</p>";
        }
        ?>
    </div>
</div>
</section>
<?php
} else {
    echo '<div class="alert alert-info text-center" role="alert">';
    echo 'Para realizar reservaciones, por favor <a href="clienteRegistro.php">regístrese</a> o <a href="login.php">inicie sesión</a>.';
    echo '</div>';
}
?>
<script>
    document.getElementById('fecha').min = new Date().toISOString().split('T')[0];

    const horarios = {
        0: { apertura: '10:00', cierre: '20:00' }, 
        1: { apertura: '12:00', cierre: '20:00' }, 
        2: { apertura: '10:00', cierre: '20:00' }, 
        3: { apertura: '11:00', cierre: '20:00' }, 
        4: { apertura: '10:00', cierre: '20:00' }, 
        5: { apertura: '11:00', cierre: '20:00' }, 
        6: { apertura: '10:00', cierre: '19:00' } 
    };

    document.querySelector('form').addEventListener('submit', function(e) {
        const fecha = new Date(document.getElementById('fecha').value);
        const hora = document.getElementById('hora').value;
        const personas = document.getElementById('personas').value;
        const mesas = document.getElementById('mesas').value;

        const diaSemana = fecha.getDay();
        const apertura = horarios[diaSemana].apertura;
        const cierre = horarios[diaSemana].cierre;

        if (fecha < new Date()) {
            e.preventDefault();
            alert('Por favor seleccione una fecha futura');
        }

        if (hora < apertura || hora > cierre) {
            e.preventDefault();
            alert(`Por favor seleccione una hora entre ${apertura} y ${cierre}`);
        }

        if (personas > (mesas * 4)) {
            e.preventDefault();
            alert('El número de personas excede la capacidad de las mesas seleccionadas');
        }
    });

    document.getElementById('verReservasBtn').addEventListener('click', function() {
    const reservasList = document.getElementById('reservasList');
    if (reservasList.style.display === 'none') {
        reservasList.style.display = 'block';
    } else {
        reservasList.style.display = 'none';
    }
    });
</script>
<script src="path/to/bootstrap.bundle.min.js"></script>
<?php
include 'includes/footerC.php';
?>