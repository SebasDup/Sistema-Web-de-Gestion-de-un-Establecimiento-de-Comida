<?php
$usuarioRol = $_SESSION['rolUsuario'];
$nombreUsuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            <div class="sidebar-header">
                <h1>Gestión Restaurante</h1>
            </div>
            <?php if ($usuarioRol == 'administrador'){
                $nombreUsuario = 'Administrador';
            }?>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <p>Bienvenido, <?php echo ucfirst($nombreUsuario); ?></p>
            <?php if ($usuarioRol == 'administrador'): ?>
            <a href="http://localhost/restaurante/index.php?c=configuracion&m=index" class="settings-link <?php echo ($_SESSION['paginaActual'] == 'configuracion') ? 'active' : ''; ?>">
                <div class="settings-button">
                    <i class="bi bi-gear-fill"></i>
                </div>
            </a>
            <?php endif; ?>
            <style>
                .settings-link {
                    text-decoration: none;
                    display: block;
                    margin: 15px auto;
                    width: fit-content;
                }
                .settings-button {
                    height: 45px;
                    width: 45px;
                    background: linear-gradient(145deg, #a06800, #784e00);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.3s ease;
                    box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.2),
                               -4px -4px 8px rgba(255, 255, 255, 0.1);
                }
                .settings-link:hover .settings-button {
                    transform: rotate(45deg);
                    background: linear-gradient(145deg, #b37300, #8b5800);
                    box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.25),
                               -6px -6px 12px rgba(255, 255, 255, 0.1);
                }
                .settings-link.active .settings-button {
                    background: linear-gradient(145deg, #e60000, #cc0000);
                    transform: scale(1.1);
                }
                .settings-button i {
                    font-size: 22px;
                    color: white;
                    transition: all 0.3s ease;
                }
                .settings-link:hover .settings-button i {
                    color: #ffffff;
                }
                .logout {
                    margin-top: 1rem;
                    display: block;
                }
            </style>
            <ul>
                <?php if ($usuarioRol == 'administrador'): ?>
                    <li><a href="http://localhost/restaurante/index.php?c=admin&m=index" <?php echo ($_SESSION['paginaActual'] == 'inicio') ? 'class="active"' : ''; ?>><i class="bi bi-house"></i> Inicio</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=usuario&m=index" <?php echo ($_SESSION['paginaActual'] == 'usuarios') ? 'class="active"' : ''; ?>><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=empleado&m=index" <?php echo ($_SESSION['paginaActual'] == 'empleados') ? 'class="active"' : ''; ?>><i class="bi bi-person-badge"></i> Empleados</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=menu&m=index" <?php echo ($_SESSION['paginaActual'] == 'menu') ? 'class="active"' : ''; ?>><i class="bi bi-card-list"></i> Menú</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=mesa&m=index" <?php echo ($_SESSION['paginaActual'] == 'mesas') ? 'class="active"' : ''; ?>><i class="bi bi-table"></i> Mesas Y Zonas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reservacion&m=index" <?php echo ($_SESSION['paginaActual'] == 'reservaciones') ? 'class="active"' : ''; ?>><i class="bi bi-calendar-check"></i> Reservaciones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=promocion&m=index" <?php echo ($_SESSION['paginaActual'] == 'promociones') ? 'class="active"' : ''; ?>><i class="bi bi-tags"></i> Promociones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=comanda&m=index" <?php echo ($_SESSION['paginaActual'] == 'comandas') ? 'class="active"' : ''; ?>><i class="bi bi-receipt"></i> Comandas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reporte&m=index" <?php echo ($_SESSION['paginaActual'] == 'reportes') ? 'class="active"' : ''; ?>><i class="bi bi-bar-chart"></i> Reportes</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=respaldo&m=index" <?php echo ($_SESSION['paginaActual'] == 'respaldo') ? 'class="active"' : ''; ?>><i class="bi bi-cloud-arrow-up"></i> Respaldo y Restauración</a></li>
                <?php elseif ($usuarioRol == 'empleado'): ?>
                    <li><a href="http://localhost/restaurante/index.php?c=admin&m=index" <?php echo ($_SESSION['paginaActual'] == 'inicio') ? 'class="active"' : ''; ?>><i class="bi bi-house"></i> Inicio</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=usuario&m=index" <?php echo ($_SESSION['paginaActual'] == 'usuarios') ? 'class="active"' : ''; ?>><i class="bi bi-people"></i> Usuarios</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=menu&m=index" <?php echo ($_SESSION['paginaActual'] == 'menu') ? 'class="active"' : ''; ?>><i class="bi bi-card-list"></i> Menú</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=mesa&m=index" <?php echo ($_SESSION['paginaActual'] == 'mesas') ? 'class="active"' : ''; ?>><i class="bi bi-table"></i> Mesas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reservacion&m=index" <?php echo ($_SESSION['paginaActual'] == 'reservaciones') ? 'class="active"' : ''; ?>><i class="bi bi-calendar-check"></i> Reservaciones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=promocion&m=index" <?php echo ($_SESSION['paginaActual'] == 'promociones') ? 'class="active"' : ''; ?>><i class="bi bi-tags"></i> Promociones</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=comanda&m=index" <?php echo ($_SESSION['paginaActual'] == 'comandas') ? 'class="active"' : ''; ?>><i class="bi bi-receipt"></i> Comandas</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=reporte&m=index" <?php echo ($_SESSION['paginaActual'] == 'reportes') ? 'class="active"' : ''; ?>><i class="bi bi-bar-chart"></i> Reportes</a></li>
                    <li><a href="http://localhost/restaurante/index.php?c=respaldo&m=index" <?php echo ($_SESSION['paginaActual'] == 'respaldo') ? 'class="active"' : ''; ?>><i class="bi bi-cloud-arrow-up"></i> Respaldo y Restauración</a></li>
                <?php endif; ?>
            </ul>
            <a class="logout" href="http://localhost/restaurante/index.php?c=auth&m=logout">Cerrar Sesión</a>
        </nav>
        <main class="content">