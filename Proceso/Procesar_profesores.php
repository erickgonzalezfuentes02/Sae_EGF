<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_profesor'];
    $control = $_POST['N_control_profesor'];
    $nombre = $_POST['nombre_profesor'];
    $ap_pat = $_POST['A_paterno_profesor'];
    $ap_mat = $_POST['A_materno_profesor'];
    $domicilio = $_POST['Domicilio_profesor'];
    $mail = $_POST['mail_profesor'];
    $telefono = $_POST['telefono_profesor'];
    $estatus = $_POST['estatus_profesor'];

    $stmt = $conexion->prepare("UPDATE profesor SET N_control_profesor=?, nombre_profesor=?, A_paterno_profesor=?, A_materno_profesor=?, Domicilio_profesor=?, mail_profesor=?, telefono_profesor=?, estatus_profesor=? WHERE id_profesor=?");
    $stmt->bind_param("ssssssssi", $control, $nombre, $ap_pat, $ap_mat, $domicilio, $mail, $telefono, $estatus, $id);
    $stmt->execute();
}

header("Location: ../Crude/Crudeprofesores.php");
exit();
?>