<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión del Restaurante</title>
    <link rel="stylesheet" type="text/css" href="Static/css/styles.css">
</head>
<body>
    <div class="container">
        <nav class="sidebar">
            <h1>Gestión Restaurante</h1>
            <p>Bienvenido, Admin</p>
            <ul>
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
            </ul>
            <a class="logout" href="logout.php">Cerrar Sesión</a>
        </nav>
        <main class="content">