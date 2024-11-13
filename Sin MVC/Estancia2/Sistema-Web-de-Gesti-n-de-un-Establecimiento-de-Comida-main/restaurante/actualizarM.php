<?php
session_start();
$currentPage = 'menu';
include 'Static/connect/db.php'; ?>
<?php include 'includes/header.php'; ?>

<a href="menu.php"><img src="Static/img/back.png"></a>
<br><br>

<?php 
if (isset($_GET['id'])) {
    $ID = $_GET['id'];
    $query = "SELECT * FROM menu WHERE id = $ID;";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $nombre = $row['nombre'];
        $descripcion = $row['descripcion'];
        $precio = $row['precio'];
        $categoria = $row['categoria'];
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio']; 
    $categoria = $_POST['categoria'];

    $update = "UPDATE menu SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', categoria = '$categoria' WHERE id = $id;";
    if (mysqli_query($conn, $update)) {
        $_SESSION['mensajeAM'] = 'Platillo actualizado exitosamente';
    } else {
        $_SESSION['error'] = 'Error al actualizar el empleado: ' . mysqli_error($conn);
    }
    mysqli_query($conn, query: $update);
    header("Location: menu.php");
}
?>

<div class="content"><h2>Actualizar Platillo</h2>
<form class="user-form" method="POST" action="actualizarM.php?id=<?php echo $_GET['id']; ?>">
    
    <div class="form_container">
        <label for="nombre" class="formulario_label">Nombre del cliente:</label>
        <input type="text" name="nombre" id="nombre" class="formulario_input" value="<?php echo $nombre; ?>">
    </div> 

    <div class="form_container">
        <label for="descripcion" class="formulario_label">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" class="formulario_input" value="<?php echo $descripcion; ?>">
    </div>

    <div class="form_container">
        <label for="precio">Precio:</label>
        <input type="number" name="precio" id="precio" class="formulario_input" value="<?php echo $precio; ?>">
    </div>

    <div class="form_container">
        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" id="categoria" class="formulario_input" value="<?php echo $categoria; ?>">
        </div>

    <br>
    <div class="form_container">                    
        <button class="formulario_btn" name="update">ACTUALIZAR</button> 
    </div>
</form>
