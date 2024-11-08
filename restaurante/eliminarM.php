<?php  
session_start();
$currentPage = 'usuarios';
include 'Static/connect/db.php';  
include 'includes/header.php';  

$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){ ?>
    <a href="menu.php"><img src="Static/img/back.png"></a>
    <br></br>
    <?php 
    if(isset($_GET['id'])) {
        $ID = $_GET['id'];
        $delete = "DELETE FROM menu WHERE id = $ID;";
        if(mysqli_query($conn, $delete)) {
            $_SESSION['mensajeEM'] = "Platillo eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el usuario";
        }
        mysqli_query($conn, $delete);
        sleep(1);
        header("Location: menu.php");
    }
} else {
    header("Location: login.php");
}
?>
