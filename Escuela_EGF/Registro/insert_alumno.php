<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede acceder a esta sección.'); }



// 1. Validar que la persona haya iniciado sesión
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

// 2. Restricción estricta: Si NO es Administrador, bloquear la pantalla con un mensaje limpio
if ($_SESSION['puesto'] != 'Administrador') {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow p-4 text-center'><strong>Acceso denegado:</strong> Esta sección es exclusiva para el Administrador.</div></div>";
    exit();
}
?>

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
