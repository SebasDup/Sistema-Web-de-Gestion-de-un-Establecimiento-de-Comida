<?php 
$currentPage = 'usuarios';
include 'Static/connect/db.php';
include 'includes/header.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $precio = mysqli_real_escape_string($conn, $_POST['precio']);
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);

    // Consulta de inserción
    $sql = "INSERT INTO menu (nombre, descripcion, precio, categoria) VALUES ('$nombre', '$descripcion', '$precio', '$categoria')";
    $execute = mysqli_query($conn, $sql);

    // Mensaje de confirmación
    if ($execute) {
        $_SESSION['message'] = "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 10px;'>
                                    Registro exitoso. Se ha enviado un correo de confirmación.
                                </div>";
        header("Location: menu.php");
        exit();
    } else {
        echo "Error en el registro: " . mysqli_error($conn);
    }
}
?>
