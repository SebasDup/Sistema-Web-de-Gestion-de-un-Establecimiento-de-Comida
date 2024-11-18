<?php
require_once 'config.php';

$controlador = $_GET['c'] ?? 'home';
$metodo = $_GET['m'] ?? 'index';

// Generar el nombre del archivo y la clase del controlador
$archivoControlador = "controlador/{$controlador}Controlador.php";
$nombreControlador = ucfirst($controlador) . 'Controlador';

// Verificar si el archivo del controlador existe
if (file_exists($archivoControlador)) {
    require_once $archivoControlador;
    $controlador = new $nombreControlador();

    // Verificar si el método existe en el controlador
    if (method_exists($controlador, $metodo)) {
        $controlador->$metodo();
    } else {
        echo "Error: El método '{$metodo}' no existe en el controlador '{$nombreControlador}'.";
    }
} else {
    echo "Error: El archivo controlador '{$archivoControlador}' no existe.";
}
?>
