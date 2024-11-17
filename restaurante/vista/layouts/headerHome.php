<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Portal Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles2.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="nav-brand">
            Restaurante 
        </div>
        <div class="nav-links">
            <?php
            if(isset($_SESSION['rolUsuario']) && $_SESSION['rolUsuario'] == 'administrador'){
                echo '<a href="admin.php"><i class="fas fa-cog"></i> Administrar</a>';
            }
            if(!isset($_SESSION['usuario'])){
                echo '<a href="login.php">Iniciar Sesion</a>';
                echo '<a href="register.php">Registrarse</a>';
            }else{
                if(($_SESSION['rolUsuario'])== 'cliente'){
                    echo 'Bienvenido/a '.$_SESSION['nombre'];
                }
            }
            ?>
            <a href="#promociones">Promociones</a>
            <a href="#horario">Horario</a>
            <a href="#reservas">Reservar</a>
            <?php
            if(isset($_SESSION['usuario'])){
                echo '<a href="#mis-reservas">Ver mis reservaciones</a>';
            }
            if(isset($_SESSION['usuario'])){
                echo '<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>';
            }
            ?>
        </div>
    </nav>