<?php 
include 'Static/connect/db.php';
session_start();

$usuarioId = $_SESSION['user_id'];
if(isset($_SESSION['usuario'])){

$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$fecha = date('Y-m-d H:i:s', strtotime($fecha . ' ' . $hora));
$personas = $_POST['personas'];
$estado = 'pendiente';

$stmt = $conn->prepare("INSERT INTO reservaciones (cliente_id, fecha, personas, estado) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $usuarioId, $fecha, $personas, $estado);

if ($stmt->execute()) {
    $reservacion_id = $conn->insert_id;
    $mesa_id = 1;
    $stmt = $conn->prepare("INSERT INTO reservaciones_mesas (reservacion_id, mesa_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $reservacion_id, $mesa_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = '<div class="alert alert-success" data-bs-dismiss="alert" role="alert">Nueva reservación creada exitosamente</div>';
    } else {
        $_SESSION['message'] = '<div class="alert alert-danger" data-bs-dismiss="alert" role="alert">Error al registrar la mesa: ' . $stmt->error . '</div>';
    }
} else {
    $_SESSION['message'] = '<div class="alert alert-danger" data-bs-dismiss="alert" role="alert">Error: ' . $stmt->error . '</div>';
}

$stmt->close();
$conn->close();
header("Location: Vclientes.php");
exit();
} else {
    header("Location: login.php");
    exit();
}
?>