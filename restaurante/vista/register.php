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
            <div class="oval-container">
            <h2 class="text-center mb-4">Registro</h2>
            <form class="needs-validation" action="http://localhost/restaurante/index.php?c=auth&m=guardarCliente" method="POST" novalidate>
                <input type="hidden" name="tipo" value="cliente">
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" required>
                    <div class="invalid-feedback">Por favor ingrese su usuario.</div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <input type="text" name="apellidoP" id="apellidoP" class="form-control" placeholder="Apellido Paterno" required>
                    <div class="invalid-feedback">Por favor ingrese un apellido paterno.</div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    </div>
                    <input type="text" name="apellidoM" id="apellidoM" class="form-control" placeholder="Apellido Materno" required>
                    <div class="invalid-feedback">Por favor ingrese un apellido materno.</div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" required>
                    <div class="invalid-feedback">Por favor ingrese un email válido.</div>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="contrasena" id="contrasena" class="form-control" placeholder="Contraseña" required>
                    <div class="invalid-feedback">Por favor ingrese un email válido.</div>
                </div>

                <?php if(isset($_SESSION['errorRegister'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['errorRegister']);
                    unset($_SESSION['errorRegister']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                <button class="btn btn-primary boton-login" type="submit">Registrarse</button>
                </div>
                <div class="mt-3 ">
                ¿Ya tienes una cuenta?<a href="login.php"> Iniciar Sesión</a>
                </div>
            </form>
            </div>
        <a href="http://localhost/restaurante/vista/home.php" class="btn btn-primary boton-login mt-3">
            <i class="fas fa-home"></i> Regresar al inicio
        </a>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
                }, false)
            })
            })
    </script>
</body>
</html>