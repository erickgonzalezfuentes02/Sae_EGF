<?php
session_start();
if (!isset($_SESSION['id_user']) || ($_SESSION['puesto'] ?? '') !== 'Administrador') { header("Location: ../login.php"); exit(); }
require_once("../conexion.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header("Location: ../Registro/Usuario.php"); exit(); }
$nombre=trim($_POST['nombre_user'] ?? ''); $password=$_POST['pw_user'] ?? '';
$id_puesto=filter_input(INPUT_POST,'id_puesto',FILTER_VALIDATE_INT);
$id_profesor=filter_input(INPUT_POST,'id_profesor',FILTER_VALIDATE_INT) ?: null;
$id_alumno=filter_input(INPUT_POST,'id_alumno',FILTER_VALIDATE_INT) ?: null;
if ($nombre==='' || $password==='' || !$id_puesto) { header("Location: ../Registro/Usuario.php?msg=datos_invalidos"); exit(); }
$role=$conn->prepare("SELECT descripcion_puesto FROM puesto WHERE id_puesto=? AND status_puesto='Alta'");
$role->bind_param("i",$id_puesto); $role->execute(); $row=$role->get_result()->fetch_assoc();
if (!$row) { header("Location: ../Registro/Usuario.php?msg=puesto_invalido"); exit(); }
$role_name=mb_strtolower(trim($row['descripcion_puesto']));
if (str_contains($role_name,'docente') || str_contains($role_name,'profesor')) {
    if (!$id_profesor || $id_alumno) { header("Location: ../Registro/Usuario.php?msg=perfil_invalido"); exit(); }
    $profile=$conn->prepare("SELECT id_profesor FROM profesor WHERE id_profesor=? AND estatus_profesor='ALTA'");
    $profile->bind_param("i",$id_profesor); $profile->execute(); if (!$profile->get_result()->fetch_assoc()) { header("Location: ../Registro/Usuario.php?msg=perfil_invalido"); exit(); }
    $id_alumno=null;
} elseif (str_contains($role_name,'alumno')) {
    if (!$id_alumno || $id_profesor) { header("Location: ../Registro/Usuario.php?msg=perfil_invalido"); exit(); }
    $profile=$conn->prepare("SELECT id_alumno FROM alumnos WHERE id_alumno=? AND estatus='ALTA'");
    $profile->bind_param("i",$id_alumno); $profile->execute(); if (!$profile->get_result()->fetch_assoc()) { header("Location: ../Registro/Usuario.php?msg=perfil_invalido"); exit(); }
    $id_profesor=null;
} else { $id_profesor=null; $id_alumno=null; }
$check=$conn->prepare("SELECT id_user FROM usuarios WHERE nombre_user=? LIMIT 1"); $check->bind_param("s",$nombre); $check->execute();
if ($check->get_result()->fetch_assoc()) { header("Location: ../Registro/Usuario.php?msg=usuario_repetido"); exit(); }
$hash=sha1($password); $status='Alta';
$insert=$conn->prepare("INSERT INTO usuarios (nombre_user,pw_user,id_puesto,id_alumno,id_profesor,status_user) VALUES (?,?,?,?,?,?)");
$insert->bind_param("ssiiis",$nombre,$hash,$id_puesto,$id_alumno,$id_profesor,$status);
if ($insert->execute()) { header("Location: ../Crude/crudeusuarios.php?msg=agregado"); exit(); }
header("Location: ../Registro/Usuario.php?msg=error"); exit();
?>
