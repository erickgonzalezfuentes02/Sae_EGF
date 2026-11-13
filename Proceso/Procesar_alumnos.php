<?php
include ("../conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = isset($_POST['matricula']) ? $_POST['matricula'] : '';
    $nombre_alum = isset($_POST['nombre_alum']) ? $_POST['nombre_alum'] : '';
    
    $A_paterno_alum = '';
    if (!empty($_POST['A_paterno_alum'])) {
        $A_paterno_alum = $_POST['A_paterno_alum'];
    } elseif (!empty($_POST['ap_paterno'])) {
        $A_paterno_alum = $_POST['ap_paterno'];
    } elseif (!empty($_POST['apellido_paterno'])) {
        $A_paterno_alum = $_POST['apellido_paterno'];
    }
    
    $A_materno_alum = isset($_POST['A_materno_alum']) ? $_POST['A_materno_alum'] : (isset($_POST['ap_materno']) ? $_POST['ap_materno'] : '');
    $domicilio = isset($_POST['domicilio']) ? $_POST['domicilio'] : '';
    $mail_alum = isset($_POST['mail_alum']) ? $_POST['mail_alum'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';

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