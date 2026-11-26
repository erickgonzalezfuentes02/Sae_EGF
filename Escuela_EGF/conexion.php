<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sae_egf";

$conn = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Aseguramos el juego de caracteres UTF-8 para acentos
mysqli_set_charset($conn, "utf8mb4");

// Ruta base universal para el proyecto
if (!defined('BASE_URL')) {
    define('BASE_URL', '/Escuela_EGF/');
}
?>