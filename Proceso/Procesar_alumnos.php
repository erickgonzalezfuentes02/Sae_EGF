<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_alumno'];
    $matricula = $_POST['matricula'];
    $nombre = $_POST['nombre_alum'];
    $ap_pat = $_POST['A_parterno_alum'];
    $ap_mat = $_POST['A_materno_alum'];
    $domicilio = $_POST['domicilio'];
    $mail = $_POST['mail_alum'];
    $telefono = $_POST['telefono'];
    $estatus = $_POST['estatus'];

    $stmt = $conexion->prepare("UPDATE alumnos SET matricula=?, nombre_alum=?, A_parterno_alum=?, A_materno_alum=?, domicilio=?, mail_alum=?, telefono=?, estatus=? WHERE id_alumno=?");
    $stmt->bind_param("ssssssssi", $matricula, $nombre, $ap_pat, $ap_mat, $domicilio, $mail, $telefono, $estatus, $id);
    $stmt->execute();
}

header("Location: ../Crude/Crudalumnos.php");
exit();
?>