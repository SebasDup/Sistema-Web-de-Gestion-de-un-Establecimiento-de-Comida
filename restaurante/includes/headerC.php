<?php
$usuarioRol = $_SESSION['rolUsuario'];
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Portal Cliente</title>
    <link rel="stylesheet" type="text/css" href="Static/css/styles2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="nav-brand">
            Restaurante 
        </div>
        <div class="nav-links">
            <a href="#promociones">Promociones</a>
            <a href="#horario">Horario</a>
            <a href="#reservas">Reservar</a>
            <a href="#mis-reservas">Ver mis reservaciones</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </nav>
    