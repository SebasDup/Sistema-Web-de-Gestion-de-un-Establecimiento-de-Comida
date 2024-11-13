<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'inicio';
$backgroundImage = 'Static/img/fondoM.jpg';
include 'includes/header.php';
?>
<link type_ rel="stylesheet" href="style.css">
<div class="welcome-container" style="background-image: url('<?php echo $backgroundImage; ?>');">
    <div class="welcome-text">
        <h2>Bienvenido al Sistema de Gestión del Restaurante</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </div>
</div>
<?php
include 'includes/footer.php';
}else{
    header("Location: login.php");
  }
?>