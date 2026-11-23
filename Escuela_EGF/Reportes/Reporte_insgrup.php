<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
$rol=$_SESSION['puesto'] ?? '';
$idProfesorSession=(int)($_SESSION['id_profesor'] ?? 0);
if ($rol==='Alumno') { header("Location: Reporte_mi_alumno.php"); exit(); }
if (!in_array($rol,['Administrador','Docente'],true)) { http_response_code(403); exit('Acceso denegado.'); }
require_once("../conexion.php"); require_once("../navegacion/header.php"); require_once("../navegacion/navegacion.php");
$sql="SELECT g.descripcion_grupo,a.matricula,a.nombre_alum,a.A_paterno_alum,a.A_materno_alum FROM insgrup ig JOIN grupo g ON g.id_grupo=ig.Id_grupo JOIN alumnos a ON a.id_alumno=ig.Id_alumno";
if($rol==='Docente'){$sql.=" WHERE EXISTS (SELECT 1 FROM asignat x WHERE x.Id_alumno=a.id_alumno AND x.Id_profesor=?)";}
$sql.=" ORDER BY g.descripcion_grupo,a.A_paterno_alum,a.A_materno_alum,a.nombre_alum";
$stmt=$conn->prepare($sql); if($rol==='Docente'){$stmt->bind_param("i",$idProfesorSession);} $stmt->execute(); $resultado=$stmt->get_result();
?>
<div class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0">Alumnos por grupo</h3></div><div class="card-body table-responsive"><table class="table table-striped table-hover text-center"><thead class="table-dark"><tr><th>Grupo</th><th>Matrícula</th><th>Alumno</th></tr></thead><tbody>
<?php if($resultado->num_rows): while($r=$resultado->fetch_assoc()): ?><tr><td><?= htmlspecialchars($r['descripcion_grupo']) ?></td><td><?= htmlspecialchars($r['matricula']) ?></td><td><?= htmlspecialchars($r['A_paterno_alum'].' '.$r['A_materno_alum'].' '.$r['nombre_alum']) ?></td></tr><?php endwhile; else: ?><tr><td colspan="3">No hay alumnos asignados.</td></tr><?php endif; ?>
</tbody></table></div></div></div><?php include("../navegacion/footer.php"); ?>


