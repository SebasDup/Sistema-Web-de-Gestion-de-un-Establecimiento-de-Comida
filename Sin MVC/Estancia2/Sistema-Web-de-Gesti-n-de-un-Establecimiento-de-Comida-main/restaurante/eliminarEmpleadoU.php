<?php  
$currentPage = 'usuarios';
include 'Static/connect/db.php';  
include 'includes/header.php';  
session_start();

$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){ ?>
    <a href="empleados.php"><img src="Static/img/back.png"></a>
    <br></br>
    <?php 
    if(isset($_GET['id'])) {
        $ID = $_GET['id'];
        $delete = "DELETE FROM empleados WHERE id = $ID;";
        if(mysqli_query($conn, $delete)) {
            $_SESSION['mensajeEE'] = "Empleado eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        mysqli_query($conn, $delete);
        sleep(1);
        header("Location: empleados.php");
    }
} else {
    header("Location: login.php");
}
?>
