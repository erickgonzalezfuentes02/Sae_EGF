<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "sae_egf";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Error de conexion: " . mysqli_connect_error());
}
?>