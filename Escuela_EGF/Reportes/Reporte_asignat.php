<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
$rol=$_SESSION['puesto'] ?? '';
$idProfesorSession=(int)($_SESSION['id_profesor'] ?? 0);
if ($rol==='Alumno') { header("Location: Reporte_mi_alumno.php"); exit(); }
if (!in_array($rol,['Administrador','Docente'],true)) { http_response_code(403); exit('Acceso denegado.'); }
require_once("../conexion.php"); require_once("../navegacion/header.php"); require_once("../navegacion/navegacion.php");
$sql="SELECT a.matricula,a.nombre_alum,a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia,p.nombre_profesor,p.A_paterno_profesor,p.A_materno_profesor FROM asignat x JOIN alumnos a ON a.id_alumno=x.Id_alumno JOIN materias m ON m.id_materia=x.Id_mat LEFT JOIN profesor p ON p.id_profesor=x.Id_profesor";
if($rol==='Docente'){$sql.=" WHERE x.Id_profesor=?";}
$sql.=" ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia";
$stmt=$conn->prepare($sql); if($rol==='Docente'){$stmt->bind_param("i",$idProfesorSession);} $stmt->execute(); $resultado=$stmt->get_result();
?>
<div class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0"><?= $rol==='Docente'?'Mis materias y alumnos':'Materias asignadas' ?></h3></div><div class="card-body table-responsive"><table class="table table-striped text-center"><thead class="table-dark"><tr><th>Matrícula</th><th>Alumno</th><th>Materia</th><th>Docente</th></tr></thead><tbody>
<?php if($resultado->num_rows):while($r=$resultado->fetch_assoc()): ?><tr><td><?= htmlspecialchars($r['matricula']) ?></td><td><?= htmlspecialchars($r['A_paterno_alum'].' '.$r['A_materno_alum'].' '.$r['nombre_alum']) ?></td><td><?= htmlspecialchars($r['descripcion_materia']) ?></td><td><?= htmlspecialchars(trim($r['A_paterno_profesor'].' '.$r['A_materno_profesor'].' '.$r['nombre_profesor'])) ?></td></tr><?php endwhile;else: ?><tr><td colspan="4">No hay materias asignadas.</td></tr><?php endif; ?>
</tbody></table></div></div></div><?php include("../navegacion/footer.php"); ?>


