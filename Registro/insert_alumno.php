<?php
include ("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = mysqli_real_escape_string($conn, $_POST['matricula'] ?? '');
    $nombre_alum = mysqli_real_escape_string($conn, $_POST['nombre_alum'] ?? '');
    
    $A_paterno_alum = mysqli_real_escape_string($conn, $_POST['A_paterno_alum'] ?? ($_POST['ap_paterno'] ?? ($_POST['apellido_paterno'] ?? '')));
    $A_materno_alum = mysqli_real_escape_string($conn, $_POST['A_materno_alum'] ?? ($_POST['ap_materno'] ?? ''));
    $domicilio = mysqli_real_escape_string($conn, $_POST['domicilio'] ?? '');
    $mail_alum = mysqli_real_escape_string($conn, $_POST['mail_alum'] ?? '');
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono'] ?? '');

    $query = "INSERT INTO alumnos (matricula, nombre_alum, A_paterno_alum, A_materno_alum, domicilio, mail_alum, telefono, estatus) 
              VALUES ('$matricula', '$nombre_alum', '$A_paterno_alum', '$A_materno_alum', '$domicilio', '$mail_alum', '$telefono', 'ALTA')";
              
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudealumnos.php?msg=agregado");
        exit();
    } else {
        echo "Error al registrar el alumno: " . mysqli_error($conn);
    }
}
?>