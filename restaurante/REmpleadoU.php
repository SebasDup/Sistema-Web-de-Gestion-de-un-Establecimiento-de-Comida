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

    $checkEmailQuery = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "Error: El correo electrónico ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (nombre, apellido,email, contrasena, tipo) VALUES ('$nombre', '$apellido','$email', '$password', 'empleado')";
        $execute = mysqli_query($conn, $sql);

        if ($execute) {
            $_SESSION['mensajeREU'] = "Registro exitoso.";
            header("Location: empleados.php");
            exit();
        } else {
            echo "Error en el registro: " . mysqli_error($conn);
        }
    }
}
?>
