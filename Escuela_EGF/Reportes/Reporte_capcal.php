<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
$rol=$_SESSION['puesto'] ?? '';
$idProfesorSession=(int)($_SESSION['id_profesor'] ?? 0);
if ($rol==='Alumno') { header("Location: Reporte_mi_alumno.php"); exit(); }
if (!in_array($rol,['Administrador','Docente'],true)) { http_response_code(403); exit('Acceso denegado.'); }
require_once("../conexion.php"); require_once("../navegacion/header.php"); require_once("../navegacion/navegacion.php");
$sql="SELECT c.Cal,m.descripcion_materia,a.matricula,a.nombre_alum,a.A_paterno_alum,a.A_materno_alum FROM capcal c JOIN materias m ON m.id_materia=c.Id_Unidad JOIN alumnos a ON a.id_alumno=c.Id_alumno";
if($rol==='Docente'){$sql.=" WHERE EXISTS (SELECT 1 FROM asignat x WHERE x.Id_alumno=c.Id_alumno AND x.Id_mat=c.Id_Unidad AND x.Id_profesor=?)";}
$sql.=" ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia";
$stmt=$conn->prepare($sql); if($rol==='Docente'){$stmt->bind_param("i",$idProfesorSession);} $stmt->execute(); $resultado=$stmt->get_result();
?>
<div class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0">Calificaciones <?= $rol==='Docente'?'de mis grupos':'' ?></h3></div><div class="card-body table-responsive"><table class="table table-striped text-center"><thead class="table-dark"><tr><th>Matrícula</th><th>Alumno</th><th>Materia</th><th>Calificación</th></tr></thead><tbody>
<?php if($resultado->num_rows):while($r=$resultado->fetch_assoc()): ?><tr><td><?= htmlspecialchars($r['matricula']) ?></td><td><?= htmlspecialchars($r['A_paterno_alum'].' '.$r['A_materno_alum'].' '.$r['nombre_alum']) ?></td><td><?= htmlspecialchars($r['descripcion_materia']) ?></td><td><?= htmlspecialchars($r['Cal']) ?></td></tr><?php endwhile;else: ?><tr><td colspan="4">No hay calificaciones.</td></tr><?php endif; ?>
</tbody></table></div></div></div><?php include("../navegacion/footer.php"); ?>


