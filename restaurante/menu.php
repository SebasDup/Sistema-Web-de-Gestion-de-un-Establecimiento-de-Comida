<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'menu';
include 'includes/header.php';
include 'Static/connect/db.php';
?>
<div class="content">
    <h2>Gestión de menú</h2>
    
    <form class="user-form" action="RMenu.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre del platillo" required>
        <input type="text" name="descripcion" placeholder="Descripción" required> <!-- Corrección aquí -->
        <input type="number" name="precio" placeholder="Precio" required>
        <input type="text" name="categoria" placeholder="Categoría" required> <!-- Corrección aquí -->
        <button class="boton-agregar" type="submit">Agregar platillo al menu</button>
    </form>
    <script src="Static/js/appvacliente.js"></script>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM menu;";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['nombre']?></td>
            <td><?php echo $row['descripcion']?></td>
            <td><?php echo $row['precio']?></td>
            <td><?php echo $row['categoria']?></td>         
            <td> 
                <a href="actualizarM.php?id=<?php echo $row['id']?>">ACTUALIZAR</a>
            </td>
            <td> 
                <a href="eliminarM.php?id=<?php echo $row['id']?>">ELIMINAR</a>
            </td>
            </tr>
    <?php   }?>
        </tbody>
    </table>
</div>
<?php
include 'includes/footer.php';
}else{
    header("Location: login.php");
  }
?>

<?php

include 'includes/footer.php';
?>