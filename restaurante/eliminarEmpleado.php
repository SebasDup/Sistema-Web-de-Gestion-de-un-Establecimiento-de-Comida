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
        ?>
        <script type="text/javascript">
            var confirmDelete = confirm("¿Estás seguro de que deseas eliminar este registro?");
            if(confirmDelete) {
                window.location.href = "eliminarEmpleado.php?confirm=true&id=<?php echo $ID; ?>";
            } else {
                window.location.href = "empleados.php";
            }
        </script>
        <?php
    }

    if(isset($_GET['confirm']) && $_GET['confirm'] == 'true' && isset($_GET['id'])) {
        $ID = $_GET['id'];
        $delete = "DELETE FROM usuarios WHERE id = $ID;";
        mysqli_query($conn, $delete);
        sleep(1);
        header("Location: empleados.php");
    }
} else {
    header("Location: login.php");
}
?>