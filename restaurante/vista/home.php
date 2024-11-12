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
            }else{
                echo '<section id="reservas">';
                echo '<div class="container-reserva">';
                echo '<div class="aviso-texto"><a href="login.php">Para realizar una reservación es necesario iniciar sesión</a></div>';
                echo '</div>';
                echo '</section>';
            }
            ?>
        <script src="path/to/bootstrap.bundle.min.js"></script>
<?php
include 'layouts/footerHome.php';
?>