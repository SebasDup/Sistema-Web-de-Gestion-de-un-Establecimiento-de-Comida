<?php
include 'Static/connect/db.php';
include 'includes/header.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = mysqli_real_escape_string($conn, $_POST['usuario']);
    $password = $_POST['contrasena'];
    
    $sql = "SELECT * FROM usuarios WHERE usuario = '$user';";
    $execute = mysqli_query($conn, $sql);
    
    if ($execute) {
        $row = mysqli_fetch_assoc($execute);
        
        if ($row) {
            if ($password == $row['contrasena']) {
                $_SESSION['usuario'] = $user;
                if ($row['tipo'] == 'administrador') {
                    header("Location: admin.php");
                } else if ($row['tipo'] == 'empleado') {
                    header("Location: empleados.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Usuario no encontrado.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error en la consulta a la base de datos.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Método de solicitud no válido.";
    header("Location: login.php");
    exit();
}
?>