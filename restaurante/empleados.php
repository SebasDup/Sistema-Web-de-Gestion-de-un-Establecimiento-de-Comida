<?php
session_start();
  
$user = $_SESSION['usuario'];
if(isset($_SESSION['usuario'])){
$currentPage = 'empleados';
include 'includes/header.php';
include 'Static/connect/db.php';
?>
<div class="content">
    <h2>Gestión de empleados                                               </h2>
    
    <form class="user-form" action="REmpleadoU.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre de usuario" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button class="boton-agregar" type="submit">Agregar empleado</button>
    </form>
    <script src="Static/js/appvacliente.js"></script>

    <h2>Usuarios empleados                                              </h2>

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
        $query = "SELECT * FROM usuarios WHERE tipo = 'empleado';";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['usuario']?></td>
            <td><?php echo $row['email']?></td>
            <td> 
                <a href="actualizarEmpleado.php?id=<?php echo $row['id']?>">ACTUALIZAR</a>
            </td>
            <td> 
                <a href="eliminarEmpleado.php?id=<?php echo $row['id']?>">ELIMINAR</a>
            </td>
            </tr>
    <?php   }?>
        </tbody>
    </table>

    <h2>Registro de empleados                                               </h2>

    <form class="user-form" action="REmpleado.php" method="POST">
        <input type="text" name="idEmpleado" placeholder="Id de empleado" required>
        <input type="text" name="puesto" placeholder="Puesto del empleado" required>
        <label for="contratacion">Fecha de contratación: </label>
        <input type="date" name="contratacion" placeholder="Fecha de contratación " required>
        <input type="text" name="salario" placeholder="Salario" required>
        <input type="text" name="serviciosRealizados" placeholder="Servicios realizados" required>
        <input type="text" name="zona" placeholder="Zona asignada" required>

        <button class="boton-agregar" type="submit">Registrar</button>
    </form>
    <script src="Static/js/appvacliente.js"></script>  
    
    <h2>Empleados                                               </h2>

    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario_ID</th>
                <th>Puesto</th>
                <th>Fecha de contratacion</th>
                <th>Salario</th>
                <th>Servicios Hechos</th>       
                <th>Zona</th>         
                <th>Actualizar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT * FROM empleados";
        $resul = mysqli_query($conn, $query);
        while($row = mysqli_fetch_array($resul)){ ?>
            <tr>
            <td><?php echo $row['id'];?></td>
            <td><?php echo $row['usuario_id']?></td>
            <td><?php echo $row['puesto']?></td>
            <td><?php echo $row['fecha_contratacion'];?></td>
            <td><?php echo $row['salario']?></td>
            <td><?php echo $row['servicios_realizados']?></td>
            <td><?php echo $row['zona_asignada']?></td>

            <td> 
                <a href="actualizarEmpleadoU.php?id=<?php echo $row['id']?>">ACTUALIZAR</a>
            </td>
            <td> 
                <a href="eliminarEmpleadoU.php?id=<?php echo $row['id']?>">ELIMINAR</a>
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