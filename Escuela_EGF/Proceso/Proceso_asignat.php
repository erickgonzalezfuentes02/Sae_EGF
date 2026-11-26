<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
require_once("../conexion.php");
require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_materia = filter_input(INPUT_POST, 'id_mat', FILTER_VALIDATE_INT);
    $id_alumno = filter_input(INPUT_POST, 'id_alumno', FILTER_VALIDATE_INT);
    $id_profesor = filter_input(INPUT_POST, 'id_profesor', FILTER_VALIDATE_INT);
    if (!$id_materia || !$id_alumno || !$id_profesor) {
        $mensaje = "<div class='alert alert-danger'>Selecciona una materia, un alumno y un profesor válidos.</div>";
    } else {
        $check = $conn->prepare("SELECT Id_asigmat FROM asignat WHERE Id_mat = ? AND Id_alumno = ?");
        $check->bind_param("ii", $id_materia, $id_alumno); $check->execute();
        $existe = $check->get_result()->fetch_assoc();
        if ($existe) {
            $stmt = $conn->prepare("UPDATE asignat SET Id_profesor = ? WHERE Id_asigmat = ?");
            $stmt->bind_param("ii", $id_profesor, $existe['Id_asigmat']);
            $ok = $stmt->execute();
            $mensaje = $ok ? "<div class='alert alert-warning'>La asignación ya existía; se actualizó el profesor.</div>" : "<div class='alert alert-danger'>No se pudo actualizar la asignación.</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO asignat (Id_mat, Id_alumno, Id_profesor) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $id_materia, $id_alumno, $id_profesor); $ok = $stmt->execute();
            $mensaje = $ok ? "<div class='alert alert-success'>Materia asignada correctamente.</div>" : "<div class='alert alert-danger'>No se pudo guardar la asignación: " . htmlspecialchars($stmt->error) . "</div>";
        }
    }
}
?>
<div class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0">Asignación de Materias</h3></div><div class="card-body"><?= $mensaje ?>
<form method="POST">
<div class="mb-3"><label class="form-label">Materia</label><select name="id_mat" class="form-select" required><option value="">Selecciona una materia...</option><?php $res=$conn->query("SELECT id_materia, descripcion_materia FROM materias WHERE estatus_materia='ALTA' ORDER BY descripcion_materia"); while($r=$res->fetch_assoc()): ?><option value="<?= (int)$r['id_materia'] ?>"><?= htmlspecialchars($r['descripcion_materia']) ?></option><?php endwhile; ?></select></div>
<div class="mb-3"><label class="form-label">Alumno</label><select name="id_alumno" class="form-select" required><option value="">Selecciona un alumno...</option><?php $res=$conn->query("SELECT id_alumno, matricula, nombre_alum, A_paterno_alum, A_materno_alum FROM alumnos WHERE estatus='ALTA' ORDER BY A_paterno_alum,A_materno_alum,nombre_alum"); while($r=$res->fetch_assoc()): ?><option value="<?= (int)$r['id_alumno'] ?>"><?= htmlspecialchars($r['matricula'].' - '.$r['A_paterno_alum'].' '.$r['A_materno_alum'].' '.$r['nombre_alum']) ?></option><?php endwhile; ?></select></div>
<div class="mb-3"><label class="form-label">Profesor</label><select name="id_profesor" class="form-select" required><option value="">Selecciona un profesor...</option><?php $res=$conn->query("SELECT id_profesor, nombre_profesor, A_paterno_profesor, A_materno_profesor FROM profesor WHERE estatus_profesor='ALTA' ORDER BY A_paterno_profesor,nombre_profesor"); while($r=$res->fetch_assoc()): ?><option value="<?= (int)$r['id_profesor'] ?>"><?= htmlspecialchars($r['A_paterno_profesor'].' '.$r['A_materno_profesor'].' '.$r['nombre_profesor']) ?></option><?php endwhile; ?></select></div>
<button type="submit" class="btn btn-primary">Asignar materia</button></form></div></div></div>
<?php include("../navegacion/footer.php"); ?>
