<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "sae_egf";

$conn = mysqli_connect($servidor, $usuario, $password, $base_datos);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Ruta base universal para el proyecto
define('BASE_URL', '/Escuela_EGF/');
?>