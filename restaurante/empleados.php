<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'empleados';
include 'includes/header.php';
?>

<?php
include 'includes/footer.php';
}else{
    header("Location: login.php");
}
?>