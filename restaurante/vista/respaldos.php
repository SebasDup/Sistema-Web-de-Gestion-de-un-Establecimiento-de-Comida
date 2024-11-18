<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once("layouts/header.php"); 
$user = $_SESSION['usuario'];
$usuarioRol = $_SESSION['rolUsuario'];
$_SESSION['paginaActual'] = 'reportes';
if(isset($_SESSION['usuario'])) {
    if($usuarioRol == 'administrador' || $usuarioRol == 'empleado') {
?>
    <h2 class="mt-4">Respaldo y Restauración de la Base de Datos</h2>
    <?php if($usuarioRol == 'administrador'): ?>
        <button class="btn btn-primary my-3 btn-Agregar" data-bs-toggle="modal" data-bs-target="#agregarMenuModal">Generar respaldo</button>
    <?php endif; ?>
<?php
require_once("layouts/footer.php"); 
} else {
    header("Location: logout.php");
}
} else {
header("Location: logout.php");
}
?>
<script src="vista/Static/js/validacionFormularios.js"></script>
<script src="vista/Static/js/buscarDatos.js"></script>