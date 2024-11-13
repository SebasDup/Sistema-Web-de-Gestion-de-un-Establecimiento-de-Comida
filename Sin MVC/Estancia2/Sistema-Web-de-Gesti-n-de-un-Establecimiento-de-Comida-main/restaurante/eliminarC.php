<?php  
$currentPage = 'usuarios';
include 'Static/connect/db.php';  
include 'includes/header.php';  
session_start();

$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){ ?>
    <a href="usuarios.php"><img src="Static/img/back.png"></a>
    <br></br>
    <?php 
    if(isset($_GET['id'])) {
        $ID = $_GET['id'];
        $delete = "DELETE FROM usuarios WHERE id = $ID;";
        if(mysqli_query($conn, $delete)) {
            $_SESSION['mensajeCE'] = "Usuario eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        header("Location: usuarios.php");
        exit();
    }
} else {
    header("Location: login.php");
}
?>
