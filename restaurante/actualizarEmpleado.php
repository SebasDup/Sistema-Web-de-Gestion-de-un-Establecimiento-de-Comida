<?php
$currentPage = 'empleados';
include 'Static/connect/db.php'; ?>
<?php include 'includes/header.php'; ?>

<a href="empleados.php"><img src="Static/img/back.png"></a>
<br><br>

<?php 
if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $query = "SELECT * FROM usuarios WHERE id = $ID;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $nombre = $row['usuario'];
        $email = $row['email'];
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $update = "UPDATE usuarios SET usuario = '$nombre', email = '$email'";
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update .= ", contrasena = '$hashed_password'";
    }
    $update .= " WHERE id = $id;";

    mysqli_query($conn, query: $update);
    header("Location: empleados.php");
}
?>

<div class="content"><h2>Actualizar empleado</h2>
<form class="user-form" method="POST" action="actualizarEmpleado.php?id=<?php echo $_GET['id']; ?>">
    <div class="form_container">
        <label for="nombre" class="formulario_label">Nombre del cliente:</label>
        <input type="text" name="nombre" id="nombre" class="formulario_input" value="<?php echo $nombre; ?>">
    </div> 
    <div class="form_container">
        <label for="email" class="formulario_label">Email del cliente:</label>
        <input type="email" name="email" id="email" class="formulario_input" value="<?php echo $email; ?>">
    </div> 
    <div class="form_container">
        <label for="password" class="formulario_label">Contraseña del cliente (dejar en blanco para no cambiar):</label>
        <input type="password" name="password" id="password" class="formulario_input">
    </div> 
    <br>
    <div class="form_container">                    
        <button class="formulario_btn" name="update">ACTUALIZAR</button> 
    </div>
</form>
