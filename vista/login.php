<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="login-page">
    <div class="background-overlay"><img class="imagen-login" src="Static/img/fondoL.jpg" alt=""></div>
    <div class="login-container">
        <form method="POST" name="frm1" id="frm1" action="http://localhost/restaurante/index.php?c=auth&m=login" class="login-form needs-validation" novalidate>
            <h2>Inicio de Sesión</h2>
            <?php if(isset($_SESSION['error']) || isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']); 
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>
            <div class="input-group mb-3" style="min-width: 200px;">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width: 42px; display: flex; align-items: center;"><i class="fas fa-user"></i></span>
            </div>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            <div class="invalid-feedback">Por favor ingrese su correo electrónico.</div>
            </div>
            <div class="input-group mb-3" style="min-width: 200px;">
            <div class="input-group-prepend">
                <span class="input-group-text" style="width: 42px; display: flex; align-items: center;"><i class="fas fa-lock"></i></span>
            </div>
            <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
            <div class="invalid-feedback">Por favor ingrese su contraseña.</div>
            </div>
            <div class="mt-3 ">
            ¿No tienes una cuenta? <a href="register.php">Regístrate</a>
            </div>
            <button type="submit" class="btn btn-primary boton-login">Enviar</button>
            <div id="error-message" class="alert alert-danger mt-3 d-none" role="alert">
            Credenciales incorrectas. Por favor, inténtelo de nuevo.
            </div>
        </form>
        <a href="http://localhost/restaurante/vista/home.php" class="btn btn-primary boton-login mt-3">
            <i class="fas fa-home"></i> Regresar al inicio
        </a>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>