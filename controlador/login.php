<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../modelo/Conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE email = '$email' AND contrasena = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Then set session variables
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['nombre'];
        $_SESSION['rolUsuario'] = $user['tipo'];

        header("Location: ../vista/index.php");
        exit();
    } else {
        $_SESSION['error'] = "Credenciales incorrectas";
        header("Location: ../vista/login.php");
    }
} else {
    header("Location: ../vista/login.php");
}
?>