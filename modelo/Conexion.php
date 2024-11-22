
<?php
$servidor = "localhost";
$usuario = "root"; 
$password = "";
$db = "restaurante_db";

$conn = mysqli_connect($servidor, $usuario, $password, $db);

if (!$conn) {
    die("La conexión falló: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");
?>