<?php 
$currentPage = 'usuarios';
include 'Static/connect/db.php';
include 'includes/header.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Cambié el nombre a los campos que coinciden con el formulario
    $idEmpleado = mysqli_real_escape_string($conn, $_POST['idEmpleado']);
    $puesto = mysqli_real_escape_string($conn, $_POST['puesto']);
    $fechaContratacion = mysqli_real_escape_string($conn, $_POST['contratacion']);
    $salario = mysqli_real_escape_string($conn, $_POST['salario']);
    $serviciosRealizados = mysqli_real_escape_string($conn, $_POST['serviciosRealizados']);
    $zona = mysqli_real_escape_string($conn, $_POST['zona']);
    
    // Verificar si el ID de usuario existe en la tabla 'usuarios' para cumplir con la clave externa
    $VerificarIDEmpleado = "SELECT * FROM usuarios WHERE id='$idEmpleado'";
    $result = mysqli_query($conn, $VerificarIDEmpleado);

    if (mysqli_num_rows($result) == 0) {
        echo "Error: El ID del empleado no existe en la tabla 'usuarios'.";
    } else {
        // Insertar en la tabla empleados
        $sql = "INSERT INTO empleados (usuario_id, puesto, fecha_contratacion, salario, servicios_realizados, zona_asignada) 
                VALUES ('$idEmpleado', '$puesto', '$fechaContratacion', '$salario', '$serviciosRealizados', '$zona')";
        $execute = mysqli_query($conn, $sql);

        if ($execute) {
            $_SESSION['message'] = "<div style='background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 5px; margin-top: 10px;'>
                                        Registro exitoso.
                                    </div>";
            header("Location: empleados.php");
            exit();
        } else {
            echo "Error en el registro: " . mysqli_error($conn);
        }
    }
}
?>
