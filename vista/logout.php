<?php include 'layouts/header.php';?>
<?php
    session_start();
    session_destroy();
    header("Location: login.php");
?>