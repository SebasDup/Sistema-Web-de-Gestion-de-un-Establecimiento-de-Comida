<?php
session_start();
include 'Static/connect/db.php';
include 'layouts/headerHome.php';
        $query_promos = "SELECT *, 
                         CASE 
                             WHEN CURDATE() BETWEEN fecha_inicio AND fecha_fin THEN 1 
                             ELSE 2 
                         END AS activo 
                         FROM promociones";
        $result_promos = mysqli_query($conn, $query_promos);

        $query_horario = "SELECT * FROM horarios";
        $result_horario = mysqli_query($conn, $query_horario);
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo $_SESSION['message'];
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';
            unset($_SESSION['message']);
        }
        echo '<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>';
?>
        <section id="promociones" class="section">
            <h2>Promociones Actuales</h2>
            <div class="promo-container">
                <?php while($promo = mysqli_fetch_assoc($result_promos)) { 
                    if ($promo['activo'] == 1) { ?>
                    <div class="promo-card">
                        <img src="Static/img/promo2.jpg" alt="Imagen de la promoción">
                        <h3><?php echo $promo['titulo']; ?></h3>
                        <p><?php echo $promo['descripcion']; ?></p>
                        <span class="promo-precio">$<?php echo $promo['descuento']; ?></span>
                    </div>
                <?php } } ?>
            </div>
        </section>
                
        <section id="horario" class="section">
            <h2>Horario de Atención</h2>
            <div class="horario-container">
                <table>
                    <tr>
                        <th>Día</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                    </tr>
                    <?php while($horario = mysqli_fetch_assoc($result_horario)) { ?>
                        <tr>
                            <td><?php echo $horario['dia_semana']; ?></td>
                            <td><?php echo $horario['hora_apertura']; ?></td>
                            <td><?php echo $horario['hora_cierre']; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </section>
                        
        <?php
            if(isset($_SESSION['usuario'])){
                echo '<section id="reservas" class="section">';
                echo '<h2>Realizar Reservación</h2>';
                echo '<div class="reserva-container">';
                echo '<form action="procesar_reserva.php" method="POST">';
                echo '<div class="form-group">';
                echo '<label for="fecha">Fecha:</label>';
                echo '<input type="date" id="fecha" name="fecha" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="hora">Hora:</label>';
                echo '<input type="time" id="hora" name="hora" required>';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="personas">Número de Personas:</label>';
                echo '<input type="number" id="personas" name="personas" min="1" max="20" required>';
                echo '</div>';        
                echo '<div class="form-group">';
                echo '<label for="mesas">Número de Mesas:</label>';
                echo '<input type="number" id="mesas" name="mesas" min="1" max="5" required>';
                echo '</div>';  
                echo '<div class="form-group">';
                echo '<label for="comentarios">Comentarios Adicionales:</label>';
                echo '<textarea id="comentarios" name="comentarios" rows="4"></textarea>';
                echo '</div>';  
                echo '<button type="submit" class="btn-reservar">Realizar Reservación</button>';
                echo '</form>';
                echo '</div>';
                echo '</section>';
            }
        ?>

        <section id="registro" class="section-registro">
            <div class="oval-container">
            <h2 class="text-center mb-4">Registro de Cliente</h2>
            <form class="needs-validation" action="" method="POST" novalidate>
                <input type="hidden" name="tipo" value="cliente">
                
                <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
                <div class="invalid-feedback">
                    Por favor ingrese un nombre.
                </div>
                </div>

                <div class="mb-3">
                <label for="apellidoP" class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" id="apellidoP" name="apellidoP" required>
                <div class="invalid-feedback">
                    Por favor ingrese un apellido.
                </div>
                </div>

                <div class="mb-3">
                <label for="apellidoM" class="form-label">Apellido materno</label>
                <input type="text" class="form-control" id="apellidoM" name="apellidoM" required>
                <div class="invalid-feedback">
                    Por favor ingrese un apellido.
                </div>
                </div>

                <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">
                    Por favor ingrese un email válido.
                </div>
                </div>

                <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                <div class="invalid-feedback">
                    Por favor ingrese una contraseña.
                </div>
                </div>

                <?php if(isset($_SESSION['errorH'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                    echo htmlspecialchars($_SESSION['errorH']);
                    unset($_SESSION['errorH']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['mensajeH'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                    echo $_SESSION['mensajeH'];
                    unset($_SESSION['mensajeH']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Registrarse</button>
                </div>
            </form>
            </div>
        </section>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre'], $_POST['apellidoP'], $_POST['apellidoM'], $_POST['email'], $_POST['contrasena'])) {
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
            $apellidoP = mysqli_real_escape_string($conn, $_POST['apellidoP']);
            $apellidoM = mysqli_real_escape_string($conn, $_POST['apellidoM']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $contrasena = $_POST['contrasena'];
            $tipo = 'cliente';

            $query = "INSERT INTO usuarios (nombre, apellidoP, apellidoM, email, contrasena, tipo) VALUES ('$nombre', '$apellidoP', '$apellidoM', '$email', '$contrasena', '$tipo')";
            
            if (mysqli_query($conn, $query)) {
            $_SESSION['mensajeH'] = "Registro exitoso. Ahora puede iniciar sesión.";
            } else {
            $_SESSION['errorH'] = "Error al registrar el usuario: " . mysqli_error($conn);
            }
        }
        ?>

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
        <?php
            if(!isset($_SESSION['usuario'])){
                echo '<section id="reservas" class="section">';
                echo '<div class="reserva-container">';
                echo '<div class="aviso-texto"><a href="login.php">Para realizar una reservación es necesario iniciar sesión</a></div>';
                echo '</div>';
                echo '</section>';
            }
        ?>

<?php
include 'layouts/footerHome.php';
?>