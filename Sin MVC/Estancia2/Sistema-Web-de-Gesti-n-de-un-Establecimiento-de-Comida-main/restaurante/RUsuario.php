<?php 
$currentPage = 'usuarios';
include 'Static/connect/db.php';
include 'includes/header.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['contrasena'];
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'cliente';

    $checkEmailQuery = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Error: El correo electrónico ya está registrado.";
        $redirect = ($tipo == 'cliente') ? 'clienteRegistro.php' : 'usuarios.php';
        header("Location: $redirect");
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, apellido, contrasena, tipo) 
                VALUES ('$nombre', '$email', '$apellido', '$password', '$tipo')";
        $execute = mysqli_query($conn, $sql);

        if ($execute) {
            $_SESSION['mensaje'] = "Registro exitoso!";
            $redirect = ($tipo == 'cliente') ? 'clienteRegistro.php' : 'usuarios.php';
            header("Location: $redirect");
            exit();
        } else {
            $_SESSION['error'] = "Error en el registro: " . mysqli_error($conn);
            $redirect = ($tipo == 'cliente') ? 'clienteRegistro.php' : 'usuarios.php';
            header("Location: $redirect");
        }
    }
}
?>
