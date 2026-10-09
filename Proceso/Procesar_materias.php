<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_materia'];
    $descripcion = $_POST['descripcion_materia'];
    $estatus = $_POST['estatus_materia'];

    $stmt = $conexion->prepare("UPDATE materias SET descripcion_materia=?, estatus_materia=? WHERE id_materia=?");
    $stmt->bind_param("ssi", $descripcion, $estatus, $id);
    $stmt->execute();
}

header("Location: ../Crude/Crudemateria.php");
exit();
?>