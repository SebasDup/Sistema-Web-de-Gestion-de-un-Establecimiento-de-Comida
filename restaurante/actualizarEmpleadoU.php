<?php
session_start();

$currentPage = 'empleados';
include 'Static/connect/db.php'; ?>
<?php include 'includes/header.php'; ?>

<a href="empleados.php"><img src="Static/img/back.png"></a>

<?php 
if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $query = "SELECT * FROM empleados WHERE id = $ID;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $idUsuario = $row['usuario_id'];
        $puesto = $row['puesto'];
        $fechaContratacion = $row['fecha_contratacion'];
        $salario = $row['salario'];
        $serviciosRealizados = $row['servicios_realizados'];
        $zonaAsignada = $row['zona_asignada'];
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $idUsuario = $_POST['usuario_id'];
    $fechaContratacion = $_POST['fecha_contratacion'];
    $salario = $_POST['salario']; 
    $serviciosRealizados = $_POST['servicios_realizados'];
    $zonaAsignada = $_POST['zona_asignada'];

    $update = "UPDATE empleados SET usuario_id = '$idUsuario', fecha_contratacion = '$fechaContratacion', salario = '$salario', servicios_realizados = '$serviciosRealizados', zona_asignada = '$zonaAsignada' WHERE id = $id;";
    if (mysqli_query($conn, $update)) {
        $_SESSION['mensajeAE'] = 'Empleado actualizado exitosamente';
    } else {
        $_SESSION['error'] = 'Error al actualizar el empleado: ' . mysqli_error($conn);
    }
    mysqli_query($conn, $update);
    header("Location: empleados.php");
}
?>

<div class="content"><h2>Actualizar asignación de empleado</h2>
<form class="user-form" method="POST" action="actualizarEmpleadoU.php?id=<?php echo $_GET['id']; ?>">
    <div class="form_container">
        <label for="usuario_id" class="formulario_label">Id del usuario:</label>
        <input type="text" name="usuario_id" id="usuario_id" class="formulario_input" value="<?php echo $idUsuario; ?>">
    </div>

    <div class="form_container">
        <label for="fecha_contratacion" class="formulario_label">Fecha de contratación:</label>
        <input type="date" name="fecha_contratacion" id="fecha_contratacion" class="formulario_input" value="<?php echo $fechaContratacion; ?>">
    </div> 

    <div class="form_container">
        <label for="salario">Salario:</label>
        <input type="number" name="salario" id="salario" class="formulario_input" value="<?php echo $salario; ?>">
    </div>

    <div class="form_container">
        <label for="servicios_realizados">Servicios realizados:</label>
        <input type="text" name="servicios_realizados" id="servicios_realizados" class="formulario_input" value="<?php echo $serviciosRealizados; ?>">
    </div>

    <div class="form_container">
        <label for="zona_asignada">Zona asignada:</label>
        <input type="text" name="zona_asignada" id="zona_asignada" class="formulario_input" value="<?php echo $zonaAsignada;?>">
    </div>

    <br>
    <div class="form_container">                    
        <button class="formulario_btn" name="update">ACTUALIZAR</button> 
    </div>
</form>