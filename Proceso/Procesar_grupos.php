<?php
include ("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descripcion_grupo = isset($_POST['descripcion_grupo']) ? $_POST['descripcion_grupo'] : (isset($_POST['nombre_grupo']) ? $_POST['nombre_grupo'] : '');
    $descripcion_grupo = mysqli_real_escape_string($conn, $descripcion_grupo);

    $query = "INSERT INTO grupo (descripcion_grupo, estatus_grupo) VALUES ('$descripcion_grupo', 'ALTA')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudegrupos.php?msg=agregado");
        exit();
    } else {
        echo "Error al registrar el grupo: " . mysqli_error($conn);
    }
}
?>