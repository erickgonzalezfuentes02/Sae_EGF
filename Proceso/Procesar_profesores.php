<?php
include("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Registro/Profesores.php");
    exit();
}

$n_control = trim($_POST['N_control_profesor'] ?? '');
$nombre    = trim($_POST['nombre_profesor'] ?? '');
$paterno   = trim($_POST['A_paterno_profesor'] ?? '');
$materno   = trim($_POST['A_materno_profesor'] ?? '');
$domicilio = trim($_POST['Domicilio_profesor'] ?? '');
$mail      = trim($_POST['mail_profesor'] ?? '');
$telefono  = trim($_POST['telefono_profesor'] ?? '');

if ($n_control === '' || $nombre === '' || $paterno === '' || $mail === '') {
    http_response_code(400);
    exit('Faltan campos obligatorios. <a href="../Registro/Profesores.php">Regresar</a>');
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('El correo electrónico no tiene un formato válido. <a href="../Registro/Profesores.php">Regresar</a>');
}

$accion = $_POST['accion'] ?? 'insertar';

if ($accion !== 'insertar') {
    http_response_code(400);
    exit('Acción no válida.');
}

$sql = "INSERT INTO profesor (
            N_control_profesor,
            nombre_profesor,
            A_paterno_profesor,
            A_materno_profesor,
            Domicilio_profesor,
            mail_profesor,
            telefono_profesor,
            estatus_profesor
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'ALTA')";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    exit("No se pudo preparar el registro: " . htmlspecialchars(mysqli_error($conn)));
}

mysqli_stmt_bind_param(
    $stmt,
    "sssssss",
    $n_control,
    $nombre,
    $paterno,
    $materno,
    $domicilio,
    $mail,
    $telefono
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: ../Crude/crudeprofesores.php?msg=agregado");
    exit();
}

$error = mysqli_stmt_error($stmt);
mysqli_stmt_close($stmt);

http_response_code(500);
echo "No se pudo registrar el profesor: " . htmlspecialchars($error);
?>