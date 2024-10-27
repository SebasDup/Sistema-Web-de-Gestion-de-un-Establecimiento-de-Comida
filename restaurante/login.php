<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link rel="stylesheet" type="text/css" href="Static/css/styles.css">
    <link rel="stylesheet" href="login-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="login-page">
    <div class="background-overlay"><img class="imagen-login" src="Static/img/fondoL.jpg" alt=""></div>
    <div class="login-container">
        <form method="POST" name="frm1" id="frm1" action="validacion.php" class="login-form">
            <h2>Inicio de Sesión</h2>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="usuario" id="usuario" placeholder="Usuario" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required>
            </div>
            <button type="button" class="boton-login" onclick="validacion();">Enviar</button>
        </form>
        <script src="Static/js/validaciones.js"></script>
    </div>
</body>
</html>