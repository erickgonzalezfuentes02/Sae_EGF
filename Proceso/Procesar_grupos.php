<?php
include ("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : 'insertar';
    $descripcion_grupo = isset($_POST['descripcion_grupo']) ? $_POST['descripcion_grupo'] : '';

    if ($accion == 'insertar') {
        $query = "INSERT INTO grupo (descripcion_grupo) VALUES ('$descripcion_grupo')";
        if (mysqli_query($conn, $query)) {
            header("Location: ../Crude/crudegrupos.php?msg=agregado");
            exit();
        } else {
            echo "Error al registrar el grupo: " . mysqli_error($conn);
        }
    } elseif ($accion == 'modificar') {
        $id_grupo = intval($_POST['id_grupo']);
        $query = "UPDATE grupo SET descripcion_grupo='$descripcion_grupo' WHERE id_grupo=$id_grupo";
        if (mysqli_query($conn, $query)) {
            header("Location: ../Crude/crudegrupos.php?msg=modificado");
            exit();
        } else {
            echo "Error al modificar el grupo: " . mysqli_error($conn);
        }
    }
}
?>