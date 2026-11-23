<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
require_once("../conexion.php");
require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");
if (($_SESSION['puesto'] ?? '') !== 'Alumno') {
    http_response_code(403);
    echo '<main class="container my-5"><div class="alert alert-danger">Esta consulta es exclusiva para alumnos.</div></main>';
    include("../navegacion/footer.php"); exit();
}
$id_alumno=(int)($_SESSION['id_alumno'] ?? 0);
if (!$id_alumno) {
    echo '<main class="container my-5"><div class="alert alert-warning">Esta cuenta aún no está vinculada con una ficha de alumno. Pide al administrador que la vincule desde Registro de cuenta y permisos.</div></main>';
    include("../navegacion/footer.php"); exit();
}
$stmt=$conn->prepare("SELECT matricula,nombre_alum,A_paterno_alum,A_materno_alum,estatus FROM alumnos WHERE id_alumno=? LIMIT 1");
$stmt->bind_param("i",$id_alumno); $stmt->execute(); $alumno=$stmt->get_result()->fetch_assoc();
$grupos=$conn->prepare("SELECT g.descripcion_grupo,ig.Estatus_ins FROM insgrup ig INNER JOIN grupo g ON g.id_grupo=ig.Id_grupo WHERE ig.Id_alumno=? ORDER BY g.descripcion_grupo");
$grupos->bind_param("i",$id_alumno); $grupos->execute(); $res_grupos=$grupos->get_result();
$calif=$conn->prepare("SELECT m.descripcion_materia,c.Cal FROM capcal c INNER JOIN materias m ON m.id_materia=c.Id_Unidad WHERE c.Id_alumno=? ORDER BY m.descripcion_materia");
$calif->bind_param("i",$id_alumno); $calif->execute(); $res_calif=$calif->get_result();
?>
<main class="container my-4">
<?php if (!$alumno): ?><div class="alert alert-warning">No se encontró tu ficha de alumno vinculada. Contacta al administrador.</div>
<?php else: ?>
<div class="card shadow-sm mb-4"><div class="card-header bg-primary text-white"><h3 class="mb-0">Mis consultas escolares</h3></div><div class="card-body">
<h5>Mi estatus</h5><p><strong>Matrícula:</strong> <?= htmlspecialchars($alumno['matricula']) ?><br><strong>Nombre:</strong> <?= htmlspecialchars($alumno['A_paterno_alum'].' '.$alumno['A_materno_alum'].' '.$alumno['nombre_alum']) ?><br><strong>Estatus:</strong> <?= htmlspecialchars($alumno['estatus']) ?></p>
</div></div>
<div class="card shadow-sm mb-4"><div class="card-header"><h5 class="mb-0">Mis grupos</h5></div><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Grupo</th><th>Estatus de inscripción</th></tr></thead><tbody>
<?php if ($res_grupos->num_rows): while($g=$res_grupos->fetch_assoc()): ?><tr><td><?= htmlspecialchars($g['descripcion_grupo']) ?></td><td><?= htmlspecialchars($g['Estatus_ins']) ?></td></tr><?php endwhile; else: ?><tr><td colspan="2">Sin grupos asignados.</td></tr><?php endif; ?>
</tbody></table></div></div>
<div class="card shadow-sm"><div class="card-header"><h5 class="mb-0">Mis calificaciones</h5></div><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>Materia</th><th>Calificación</th></tr></thead><tbody>
<?php if ($res_calif->num_rows): while($c=$res_calif->fetch_assoc()): ?><tr><td><?= htmlspecialchars($c['descripcion_materia']) ?></td><td><?= htmlspecialchars($c['Cal']) ?></td></tr><?php endwhile; else: ?><tr><td colspan="2">Aún no hay calificaciones registradas.</td></tr><?php endif; ?>
</tbody></table></div></div>
<?php endif; ?></main>
<?php include("../navegacion/footer.php"); ?>
