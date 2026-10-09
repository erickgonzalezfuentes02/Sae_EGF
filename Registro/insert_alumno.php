<?php
$conn = include "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $nombre_alum = $_POST['nombre_alum'];
    $A_parterno_alum = $_POST['A_parterno_alum'];
    $A_materno_alum = $_POST['A_materno_alum'];
    $domicilio = $_POST['domicilio'];
    $mail_alum = $_POST['mail_alum'];
    $telefono = $_POST['telefono'];

    $query = "INSERT INTO alumnos (matricula, nombre_alum, A_parterno_alum, A_materno_alum, domicilio, mail_alum, telefono, estatus) 
              VALUES ('$matricula', '$nombre_alum', '$A_parterno_alum', '$A_materno_alum', '$domicilio', '$mail_alum', '$telefono', 'ALTA')";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudalumnos.php?msg=agregado");
        exit();
    } else {
        echo "Error al registrar el alumno: " . mysqli_error($conn);
    }
}
?>