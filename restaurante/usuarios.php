<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'usuarios';
include 'includes/header.php';
include 'Static/connect/db.php';
?>
<div class="content">
    <h2>Gestión de Usuarios</h2>
    
    <form class="user-form" action="RUsuario.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre de usuario" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button class="boton-agregar" type="submit">Agregar Usuario</button>
    </form>
    <script src="Static/js/appvacliente.js"></script>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM usuarios WHERE tipo = 'cliente';";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['usuario']?></td>
            <td><?php echo $row['email']?></td>
            <td> 
                <a href="actualizarC.php?id=<?php echo $row['id']?>">ACTUALIZAR</a>
            </td>
            <td> 
                <a href="eliminarC.php?id=<?php echo $row['id']?>">ELIMINAR</a>
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