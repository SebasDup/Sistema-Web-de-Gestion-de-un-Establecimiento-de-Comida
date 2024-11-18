<?php
include 'vista/Static/connect/db.php';

class homeControlador {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            
            header("Location: http://localhost/restaurante/vista/home.php");
        }else{
            if($_SESSION['rolUsuario'] == 'administrador'){
                header("Location: http://localhost/restaurante/vista/admin.php");
            }else{
                if($_SESSION['rolUsuario'] == 'empleado'){
                    header("Location: http://localhost/restaurante/vista/employee.php");
                }
            }
        }
    }
}
?>
