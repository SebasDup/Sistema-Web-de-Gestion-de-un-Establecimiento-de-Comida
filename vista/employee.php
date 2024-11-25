<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
if(isset($_SESSION['usuario'])){
    if($usuarioRol == 'empleado'){
        $_SESSION['paginaActual'] = 'inicio';
include 'layouts/header.php';
$backgroundImage = 'http://localhost/restaurante/vista/Static/img/fondoM.jpg';
?>
<link type_ rel="stylesheet" href="css/styles.css">
<div class="welcome-container" style="background-image: url('<?php echo $backgroundImage; ?>');">
    <div class="welcome-text">
        <h2>Bienvenido al Sistema de Gestión del Restaurante</h2>
        <p>Seleccione una opción del menú para comenzar.</p>
    </div>
</div>
<?php
include 'layouts/footer.php';
} else {
    header("Location: logout.php");
}
} else {
header("Location: logout.php");
}
?>