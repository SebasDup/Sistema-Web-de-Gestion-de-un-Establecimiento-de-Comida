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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="vista/css/styles.css">
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
                    <li><a href="http://localhost/restaurante/index.php?c=admin&m=index" <?php echo ($_SESSION['paginaActual'] == 'inicio') ? 'class="active"' : ''; ?>><i class="bi bi-house"></i> Inicio</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=usuario&m=index" <?php echo ($_SESSION['paginaActual'] == 'usuarios') ? 'class="active"' : ''; ?>><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=empleado&m=index" <?php echo ($_SESSION['paginaActual'] == 'empleados') ? 'class="active"' : ''; ?>><i class="bi bi-person-badge"></i> Empleados</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=menu&m=index" <?php echo ($_SESSION['paginaActual'] == 'menu') ? 'class="active"' : ''; ?>><i class="bi bi-card-list"></i> Menú</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=mesa&m=index" <?php echo ($_SESSION['paginaActual'] == 'mesas') ? 'class="active"' : ''; ?>><i class="bi bi-table"></i> Mesas Y Zonas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reservacion&m=index" <?php echo ($_SESSION['paginaActual'] == 'reservaciones') ? 'class="active"' : ''; ?>><i class="bi bi-calendar-check"></i> Reservaciones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=promocion&m=index" <?php echo ($_SESSION['paginaActual'] == 'promociones') ? 'class="active"' : ''; ?>><i class="bi bi-tags"></i> Promociones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=comanda&m=index" <?php echo ($_SESSION['paginaActual'] == 'comanda') ? 'class="active"' : ''; ?>><i class="bi bi-receipt"></i> Comandas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reporte&m=index" <?php echo ($_SESSION['paginaActual'] == 'reporte') ? 'class="active"' : ''; ?>><i class="bi bi-bar-chart"></i> Reportes</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=respaldo&m=index" <?php echo ($_SESSION['paginaActual'] == 'respaldo') ? 'class="active"' : ''; ?>><i class="bi bi-cloud-arrow-up"></i> Respaldo y Restauración</a></li>
                <?php elseif ($usuarioRol == 'empleado'): ?>
                    <li><a href="http://localhost/restaurante/index.php?c=admin&m=index" <?php echo ($_SESSION['paginaActual'] == 'inicio') ? 'class="active"' : ''; ?>><i class="bi bi-house"></i> Inicio</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=usuario&m=index" <?php echo ($_SESSION['paginaActual'] == 'usuarios') ? 'class="active"' : ''; ?>><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=menu&m=index" <?php echo ($_SESSION['paginaActual'] == 'menu') ? 'class="active"' : ''; ?>><i class="bi bi-card-list"></i> Menú</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=mesa&m=index" <?php echo ($_SESSION['paginaActual'] == 'mesas') ? 'class="active"' : ''; ?>><i class="bi bi-table"></i> Mesas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reservacion&m=index" <?php echo ($_SESSION['paginaActual'] == 'reservaciones') ? 'class="active"' : ''; ?>><i class="bi bi-calendar-check"></i> Reservaciones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=promocion&m=index" <?php echo ($_SESSION['paginaActual'] == 'promociones') ? 'class="active"' : ''; ?>><i class="bi bi-tags"></i> Promociones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=comanda&m=index" <?php echo ($_SESSION['paginaActual'] == 'comandasa') ? 'class="active"' : ''; ?>><i class="bi bi-receipt"></i> Comandas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reporte&m=index" <?php echo ($_SESSION['paginaActual'] == 'reportes') ? 'class="active"' : ''; ?>><i class="bi bi-bar-chart"></i> Reportes</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=respaldo&m=index" <?php echo ($_SESSION['paginaActual'] == 'respaldo') ? 'class="active"' : ''; ?>><i class="bi bi-cloud-arrow-up"></i> Respaldo y Restauración</a></li>
                <?php endif; ?>
            </ul>
            <a class="logout" href="http://localhost/restaurante/vista/logout.php">Cerrar Sesión</a>
        </nav>
        <main class="content">