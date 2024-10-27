<?php 
$currentPage = 'usuarios';
include 'Static/connect/db.php';
include 'includes/header.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['contrasena'];

    $checkEmailQuery = "SELECT * FROM usuarios WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($result) > 0) {
        echo "Error: El correo electrónico ya está registrado.";
    } else {
        $sql = "INSERT INTO usuarios (usuario, email, contrasena, tipo) VALUES ('$nombre', '$email', '$password', 'cliente')";
        $execute = mysqli_query($conn, $sql);

        if ($execute) {
            $_SESSION['message'] = "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 10px;'>
                                        Registro exitoso. Se ha enviado un correo de confirmación.
                                    </div>";
            header("Location: usuarios.php");
            exit();
        } else {
            echo "Error en el registro: " . mysqli_error($conn);
        }
    }
}
?>
