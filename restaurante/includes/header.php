<?php
$usuarioRol = $_SESSION['rolUsuario'];
$nombreUsuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión del Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="Static/css/styles.css">
</head>
<body>
    <div class="container-fluid p-0">
        <div class="d-flex">
        <nav class="sidebar">
            <h1>Gestión Restaurante</h1>
            <?php if ($usuarioRol == 'administrador'){
                $nombreUsuario = 'Administrador';
            }?>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <p>Bienvenido, <?php echo ucfirst($nombreUsuario); ?></p>
            <ul>
                <?php if ($usuarioRol == 'administrador'): ?>
                    <li><a href="admin.php" <?php echo ($currentPage == 'inicio') ? 'class="active"' : ''; ?>>Inicio</a></li>
                    <li><a href="usuarios.php" <?php echo ($currentPage == 'usuarios') ? 'class="active"' : ''; ?>>Usuarios</a></li>
                    <li><a href="empleados.php" <?php echo ($currentPage == 'empleados') ? 'class="active"' : ''; ?>>Empleados</a></li>
                    <li><a href="menu.php" <?php echo ($currentPage == 'menu') ? 'class="active"' : ''; ?>>Menú</a></li>
                    <li><a href="mesas.php" <?php echo ($currentPage == 'mesas') ? 'class="active"' : ''; ?>>Mesas</a></li>
                    <li><a href="reservaciones.php" <?php echo ($currentPage == 'reservaciones') ? 'class="active"' : ''; ?>>Reservaciones</a></li>
                    <li><a href="promociones.php" <?php echo ($currentPage == 'promociones') ? 'class="active"' : ''; ?>>Promociones</a></li>
                    <li><a href="comandas.php" <?php echo ($currentPage == 'comandas') ? 'class="active"' : ''; ?>>Comandas</a></li>
                    <li><a href="reportes.php" <?php echo ($currentPage == 'reportes') ? 'class="active"' : ''; ?>>Reportes</a></li>
                    <li><a href="respaldo.php" <?php echo ($currentPage == 'respaldo') ? 'class="active"' : ''; ?>>Respaldo y Restauración</a></li>
                <?php elseif ($usuarioRol == 'empleado'): ?>
                    <li><a href="empleado.php" <?php echo ($currentPage == 'empleados') ? 'class="active"' : ''; ?>>Inicio</a></li>
                    <li><a href="usuarios.php" <?php echo ($currentPage == 'usuarios') ? 'class="active"' : ''; ?>>Usuarios</a></li>
                    <li><a href="menu.php" <?php echo ($currentPage == 'menu') ? 'class="active"' : ''; ?>>Menú</a></li>
                    <li><a href="mesas.php" <?php echo ($currentPage == 'mesas') ? 'class="active"' : ''; ?>>Mesas</a></li>
                    <li><a href="reservaciones.php" <?php echo ($currentPage == 'reservaciones') ? 'class="active"' : ''; ?>>Reservaciones</a></li>
                    <li><a href="promociones.php" <?php echo ($currentPage == 'promociones') ? 'class="active"' : ''; ?>>Promociones</a></li>
                    <li><a href="comandas.php" <?php echo ($currentPage == 'comandas') ? 'class="active"' : ''; ?>>Comandas</a></li>
                    <li><a href="reportes.php" <?php echo ($currentPage == 'reportes') ? 'class="active"' : ''; ?>>Reportes</a></li>
                    <li><a href="respaldo.php" <?php echo ($currentPage == 'respaldo') ? 'class="active"' : ''; ?>>Respaldo y Restauración</a></li>
                <?php endif; ?>
            </ul>
            <a class="logout" href="logout.php">Cerrar Sesión</a>
        </nav>
        <main class="content">