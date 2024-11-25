<?php
session_start();
include 'Static/connect/db.php';
include 'layouts/headerHome.php';

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $usuarioRol = $_SESSION['rolUsuario'];
    $usuarioId = $_SESSION['user_id'];
}

$pdo = new PDO('mysql:host=localhost;dbname=restaurante_db', 'root', '');

$query_promos = "SELECT *, 
                 CASE 
                     WHEN CURDATE() BETWEEN fecha_inicio AND fecha_fin THEN 1 
                     ELSE 2 
                 END AS activo 
                 FROM promociones";
$result_promos = mysqli_query($conn, $query_promos);

$query_horario = "SELECT * FROM horarios";
$result_horario = mysqli_query($conn, $query_horario);

$query_mesas = "SELECT * FROM mesas";
$result_mesas = mysqli_query($conn, $query_mesas);
$mesas = [];
while ($mesa = mysqli_fetch_assoc($result_mesas)) {
    $mesas[] = $mesa;
}

function isRestaurantOpen($pdo, $fecha, $hora) {
    $diaSemana = date('N', strtotime($fecha));
    $stmt = $pdo->prepare("SELECT hora_apertura, hora_cierre, estado FROM horarios WHERE id = ?");
    $stmt->execute([$diaSemana]);
    $horario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$horario) {
        return false;
    }
    
    $horaReserva = strtotime($hora);
    $horaApertura = strtotime($horario['hora_apertura']);
    $horaCierre = strtotime($horario['hora_cierre']);
    $estado = $horario['estado'];
    
    return ($horaReserva >= $horaApertura && $horaReserva <= $horaCierre) && $estado === 'abierto';
}

function isTableAvailable($pdo, $fecha, $hora, $mesa_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservaciones WHERE fecha = ? AND id IN (SELECT reservacion_id FROM reservaciones_mesas WHERE mesa_id = ?)");
    $stmt->execute([$fecha . ' ' . $hora, $mesa_id]);
    return $stmt->fetchColumn() == 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fecha'], $_POST['hora'], $_POST['personas'], $_POST['id_mesa'])) {
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['message'] = 'Debe iniciar sesión para realizar una reservación.';
        $_SESSION['message_type'] = 'danger';
        header('Location: login.php');
        exit();
    }

    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = (int)$_POST['personas'];
    $mesa_id = (int)$_POST['id_mesa'];

    // Verificar si el restaurante está abierto
    if (!isRestaurantOpen($pdo, $fecha, $hora)) {
        $_SESSION['message'] = 'Lo sentimos, el restaurante está cerrado en el horario seleccionado. Por favor, elija otro horario dentro de nuestro horario de atención.';
        $_SESSION['message_type'] = 'warning';
        header('Location: home.php');
        exit();
    }

    // Verificar si la mesa tiene capacidad suficiente
    $stmtMesaCapacidad = $pdo->prepare("SELECT capacidad FROM mesas WHERE id = ?");
    $stmtMesaCapacidad->execute([$mesa_id]);
    $mesa = $stmtMesaCapacidad->fetch(PDO::FETCH_ASSOC);
    if ($mesa['capacidad'] < $personas) {
        $_SESSION['message'] = 'La mesa seleccionada no tiene capacidad suficiente para el número de personas ingresadas.';
        $_SESSION['message_type'] = 'warning';
        header('Location: home.php');
        exit();
    }

    // Verificar si la mesa está disponible en la fecha y hora seleccionadas
    if (!isTableAvailable($pdo, $fecha, $hora, $mesa_id)) {
        $_SESSION['message'] = 'La mesa seleccionada ya está reservada en la fecha y hora seleccionadas. Por favor, elija otra mesa o cambie la fecha/hora.';
        $_SESSION['message_type'] = 'warning';
        header('Location: home.php');
        exit();
    }

    $cliente_id = $_SESSION['usuario_id'];
    $fechaHora = $fecha . ' ' . $hora;
    $estado = 'pendiente';
    $comentarios = isset($_POST['comentarios']) ? $_POST['comentarios'] : '';

    $stmt = $pdo->prepare("INSERT INTO reservaciones (cliente_id, fecha, personas, estado, comentarios) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$cliente_id, $fechaHora, $personas, $estado, $comentarios])) {
        $reservacion_id = $pdo->lastInsertId();
        $stmtMesa = $pdo->prepare("INSERT INTO reservaciones_mesas (reservacion_id, mesa_id) VALUES (?, ?)");
        if ($stmtMesa->execute([$reservacion_id, $mesa_id])) {
            $_SESSION['message'] = 'Reservación registrada exitosamente.';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = 'Error al registrar la mesa de la reservación.';
            $_SESSION['message_type'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Error al registrar la reservación.';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: home.php');
    exit();
}

if (isset($_SESSION['message'])) {
    $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
    
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: "' . ($type === "success" ? "¡Éxito!" : "Aviso") . '",
                text: "' . $_SESSION['message'] . '",
                icon: "' . $type . '",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                customClass: {
                    popup: "swal2-popup-custom",
                    title: "swal2-title-custom",
                    content: "swal2-content-custom",
                    confirmButton: "swal2-confirm-button-custom"
                }
            });
        });
    </script>';
    
    echo '<style>
        .swal2-popup-custom {
            background: #f0f0f0;
            border-radius: 10px;
        }
        .swal2-title-custom {
            color: #333;
            font-weight: bold;
        }
        .swal2-content-custom {
            color: #555;
        }
        .swal2-confirm-button-custom {
            background-color: #3085d6;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>';
    
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>
<section id="promociones" class="section">
    <h2>Promociones Actuales</h2>
    <div class="row">
        <?php 
        while($promo = mysqli_fetch_assoc($result_promos)) { 
            if ($promo['activo'] == 1) { ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <img src="Static/img/promo2.jpg" class="card-img-top" alt="Imagen de la promoción">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $promo['titulo']; ?></h5>
                        <p class="card-text"><?php echo $promo['descripcion']; ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">$<?php echo $promo['descuento']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php } ?>
    </div>
    <?php 
    mysqli_free_result($result_promos);
    ?>
</section>
<section id="horarios" class="section">
    <h2>Nuestros Horarios de Atención</h2>
    <style>
        .horarios-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .table {
            box-shadow: 0 4px 6px rgba(139, 69, 19, 0.2);
            border-radius: 15px;
            overflow: hidden;
            margin: 20px 0;
        }
        .table thead th {
            background: #8A5B15;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            padding: 15px;
            border: none;
        }
        .table tbody td {
            padding: 12px 15px;
            border: none;
            color: #8B4513;
            font-weight: 500;
        }
        .table tbody tr {
            background-color: #FEDCB5;
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #FFE4B5;
            transform: scale(1.01);
        }
        .estado-abierto {
            color: #7DDA58;
            font-weight: bold;
            text-transform: capitalize;
        }
        .estado-cerrado {
            color: #FF3C3E;
            font-weight: bold;
            text-transform: capitalize;
        }
        .horario-tiempo {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #A0522D;
        }
    </style>
    <div class="horarios-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Día de la Semana</th>
                    <th>Horario de Apertura</th>
                    <th>Horario de Cierre</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while($horario = mysqli_fetch_assoc($result_horario)) { ?>
                    <tr>
                        <td><i class="fas fa-calendar-day"></i> <?php echo $horario['dia_semana']; ?></td>
                        <td class="horario-tiempo"><i class="far fa-clock"></i> <?php echo date('h:i A', strtotime($horario['hora_apertura'])); ?></td>
                        <td class="horario-tiempo"><i class="far fa-clock"></i> <?php echo date('h:i A', strtotime($horario['hora_cierre'])); ?></td>
                        <td class="estado-<?php echo strtolower($horario['estado']); ?>">
                            <i class="fas fa-<?php echo $horario['estado'] == 'abierto' ? 'check-circle' : 'times-circle'; ?>"></i>
                            <?php echo $horario['estado']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php mysqli_free_result($result_horario); ?>
</section>
<?php
mysqli_free_result($result_mesas);

if(isset($_SESSION['usuario'])){
    echo '<section id="reservas" class="section">';
    echo '<h2>Realizar Reservación</h2>';
    echo '<div class="reserva-container">';
    echo '<form id="reservaForm" method="POST" action="home.php" class="needs-validation" novalidate>';
    echo '<div class="form-group">';
    echo '<label for="fecha">Fecha:</label>';
    echo '<input type="date" id="fecha" name="fecha" class="form-control" required min="' . date('Y-m-d', strtotime('+1 day')) . '">';
    echo '<div class="invalid-feedback">Por favor, seleccione una fecha válida.</div>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="hora">Hora:</label>';
    echo '<input type="time" id="hora" name="hora" class="form-control" required>';
    echo '<div class="invalid-feedback">Por favor, seleccione una hora válida.</div>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="personas">Número de Personas:</label>';
    echo '<input type="number" id="personas" name="personas" class="form-control" min="1" max="20" required>';
    echo '<div class="invalid-feedback">Por favor, ingrese un número de personas válido (1-20).</div>';
    echo '</div>';        
    echo '<div class="mb-3">';
    echo '<label>Mesa</label>';
    echo '<select name="id_mesa" class="form-control" required>';
    echo '<option value="">Seleccionar Mesa</option>';
    foreach ($mesas as $mesa) {
        echo '<option value="' . $mesa['id'] . '">Mesa ' . $mesa['numero'] . ' (capacidad: ' . $mesa['capacidad'] . ' personas)</option>';
    }
    echo '</select>';
    echo '<div class="invalid-feedback">Por favor, seleccione una mesa.</div>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="comentarios">Comentarios Adicionales:</label>';
    echo '<textarea id="comentarios" name="comentarios" class="form-control" rows="4"></textarea>';
    echo '</div>';  
    echo '<button type="submit" class="btn btn-primary">Realizar Reservación</button>';
    echo '</form>';
    echo '</div>';
    echo '</section>';
    echo '<script>
        (function() {
            "use strict";
            window.addEventListener("load", function() {
                var forms = document.getElementsByClassName("needs-validation");
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener("submit", function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    }, false);
                });
            }, false);
        })();
    </script>';

    echo '<section id="mis-reservas" class="section">';
    echo '<h2 class="text-center mb-4" style="color: #8B4513;">Mis Reservaciones</h2>';
    echo '<div class="mis-reservas-container">';
    echo '<button id="verReservasBtn" class="btn btn-primary mb-4" style="background: #FEE5C5; border-color: #8B4513; color: #8B4513; padding: 10px 20px; border-radius: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <i class="fas fa-calendar-check mr-2"></i> Ver mis reservaciones</button>';
    echo '<div id="reservasList" style="display: none;">';

    $query_reservas = "SELECT * FROM reservaciones WHERE cliente_id = '$usuarioId' ORDER BY fecha DESC";
    $result_reservas = mysqli_query($conn, $query_reservas);
    if (mysqli_num_rows($result_reservas) > 0) {
        echo '<div class="row justify-content-center">';
        while ($reserva = mysqli_fetch_assoc($result_reservas)) {
            echo "<div class='col-md-4 mb-4'>";
            echo "<div class='card h-100' style='background: #FFF5EE; border-radius: 15px; box-shadow: 0 4px 8px rgba(139, 69, 19, 0.1);'>";
            echo "<div class='card-body' style='padding: 20px; background-color: white;'>";
            $datetime = new DateTime($reserva['fecha']);
            echo "<h5 class='card-title mb-3' style='color: #8B4513; border-bottom: 2px solid #DEB887; padding-bottom: 10px;'>
                    <i class='fas fa-calendar-alt mr-2'></i> Reservación</h5>";
            echo "<p class='card-text' style='color: #A0522D;'><i class='far fa-calendar mr-2'></i> <strong>Fecha:</strong> " . $datetime->format('d/m/Y') . "</p>";
            echo "<p class='card-text' style='color: #A0522D;'><i class='far fa-clock mr-2'></i> <strong>Hora:</strong> " . $datetime->format('H:i') . " hrs</p>";
            $estadoClass = $reserva['estado'] == 'confirmada' ? 'text-success' : ($reserva['estado'] == 'pendiente' ? 'text-warning fw-bold' : 'text-danger');
            $estadoIcon = $reserva['estado'] == 'confirmada' ? 'check-circle' : ($reserva['estado'] == 'pendiente' ? 'clock' : 'times-circle');
            
            echo "<p class='card-text'><i class='fas fa-{$estadoIcon} mr-2'></i> <strong>Estado:</strong> 
                    <span class='{$estadoClass}'>" . ucfirst($reserva['estado']) . "</span></p>";
            echo "</div></div></div>";
        }
        echo '</div>';
    } else {
        echo "<p>No tienes reservaciones.</p>";
    }

    echo '</div>';
    echo '</div>';
    echo '</section>';
}
?>

<script>
document.getElementById('verReservasBtn').addEventListener('click', function() {
    const reservasList = document.getElementById('reservasList');
    if (reservasList.style.display === 'none') {
        reservasList.style.display = 'block';
    } else {
        reservasList.style.display = 'none';
    }
});
</script>

<?php
if(!isset($_SESSION['usuario'])){
    echo '<section id="reservas" class="section">';
    echo '<div class="reserva-container">';
    echo '<div class="aviso-texto"><a href="login.php">Para realizar una reservación es necesario iniciar sesión</a></div>';
    echo '</div>';
    echo '</section>';
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include 'layouts/footerHome.php';
?>
