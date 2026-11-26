<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
$rol=$_SESSION['puesto'] ?? '';
$idProfesorSession=(int)($_SESSION['id_profesor'] ?? 0);
$esAdmin=($rol==='Administrador'); $esDocente=($rol==='Docente');
if (!$esAdmin && !$esDocente) { header("Location: ../Reportes/Reporte_mi_alumno.php"); exit(); }
if ($esDocente && empty($idProfesorSession)) { http_response_code(403); exit('La cuenta docente no está vinculada a una ficha de profesor.'); }
require_once("../conexion.php");
$mensaje='';
$grupoSeleccionado=(int)($_POST['id_grupo'] ?? $_POST['id_grupo_hidden'] ?? 0);
$materiaSeleccionada=(int)($_POST['id_unidad_masiva'] ?? $_POST['id_unidad_hidden'] ?? 0);
$mostrarMasiva=isset($_POST['cargar_masiva']) || isset($_POST['guardar_masiva']);

if (isset($_POST['guardar_individual'])) {
    $idAlumno=filter_input(INPUT_POST,'id_alumno',FILTER_VALIDATE_INT);
    $idMateria=filter_input(INPUT_POST,'id_unidad',FILTER_VALIDATE_INT);
    $cal=filter_input(INPUT_POST,'calificacion',FILTER_VALIDATE_FLOAT);
    $permitido=(bool)$idAlumno && (bool)$idMateria;
    if ($permitido && $esDocente) {
        $access=$conn->prepare("SELECT Id_asigmat FROM asignat WHERE Id_profesor=? AND Id_alumno=? AND Id_mat=? LIMIT 1");
        $access->bind_param("iii",$idProfesorSession,$idAlumno,$idMateria); $access->execute();
        $permitido=(bool)$access->get_result()->fetch_assoc();
    }
    if (!$permitido || $cal===false || $cal<0 || $cal>100) {
        $mensaje="<div class='alert alert-danger'>Elige un alumno y una materia autorizados, e ingresa una calificación de 0 a 100.</div>";
    } else {
        $find=$conn->prepare("SELECT Id_capcalf FROM capcal WHERE Id_alumno=? AND Id_Unidad=? LIMIT 1");
        $find->bind_param("ii",$idAlumno,$idMateria); $find->execute(); $existing=$find->get_result()->fetch_assoc();
        if ($existing) {
            $save=$conn->prepare("UPDATE capcal SET Cal=? WHERE Id_capcalf=?"); $save->bind_param("di",$cal,$existing['Id_capcalf']);
        } else {
            $save=$conn->prepare("INSERT INTO capcal (Id_alumno,Id_Unidad,Cal) VALUES (?,?,?)"); $save->bind_param("iid",$idAlumno,$idMateria,$cal);
        }
        $mensaje=$save->execute() ? "<div class='alert alert-success'>Calificación guardada.</div>" : "<div class='alert alert-danger'>No se pudo guardar: ".htmlspecialchars($save->error)."</div>";
    }
}

if (isset($_POST['guardar_masiva'])) {
    $group=filter_input(INPUT_POST,'id_grupo_hidden',FILTER_VALIDATE_INT);
    $subject=filter_input(INPUT_POST,'id_unidad_hidden',FILTER_VALIDATE_INT);
    $grades=$_POST['calificaciones'] ?? [];
    $ok=(bool)$group && (bool)$subject && is_array($grades);
    if ($ok) {
        $conn->begin_transaction();
        foreach ($grades as $student=>$grade) {
            if ($grade==='') continue;
            $student=filter_var($student,FILTER_VALIDATE_INT); $grade=filter_var($grade,FILTER_VALIDATE_FLOAT);
            if (!$student || $grade===false || $grade<0 || $grade>100) { $ok=false; break; }
            $enroll=$conn->prepare("SELECT Id_in FROM insgrup WHERE Id_grupo=? AND Id_alumno=? AND Estatus_ins='ALTA' LIMIT 1");
            $enroll->bind_param("ii",$group,$student); $enroll->execute();
            if (!$enroll->get_result()->fetch_assoc()) { $ok=false; break; }
            if ($esDocente) {
                $access=$conn->prepare("SELECT Id_asigmat FROM asignat WHERE Id_profesor=? AND Id_alumno=? AND Id_mat=? LIMIT 1");
                $access->bind_param("iii",$idProfesorSession,$student,$subject); $access->execute();
                if (!$access->get_result()->fetch_assoc()) { $ok=false; break; }
            }
            $find=$conn->prepare("SELECT Id_capcalf FROM capcal WHERE Id_alumno=? AND Id_Unidad=? LIMIT 1");
            $find->bind_param("ii",$student,$subject); $find->execute(); $existing=$find->get_result()->fetch_assoc();
            if ($existing) { $save=$conn->prepare("UPDATE capcal SET Cal=? WHERE Id_capcalf=?"); $save->bind_param("di",$grade,$existing['Id_capcalf']); }
            else { $save=$conn->prepare("INSERT INTO capcal (Id_alumno,Id_Unidad,Cal) VALUES (?,?,?)"); $save->bind_param("iid",$student,$subject,$grade); }
            if (!$save->execute()) { $ok=false; break; }
        }
        if ($ok) { $conn->commit(); $mensaje="<div class='alert alert-success'>Calificaciones del grupo guardadas.</div>"; }
        else { $conn->rollback(); $mensaje="<div class='alert alert-danger'>No se guardaron los cambios. Verifica grupo, asignación docente y calificaciones de 0 a 100.</div>"; }
    } else { $mensaje="<div class='alert alert-danger'>Selecciona un grupo y una materia válidos.</div>"; }
}

require_once("../navegacion/header.php"); require_once("../navegacion/navegacion.php");
?>
<div class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0">Captura de calificaciones</h3></div><div class="card-body">
<?= $mensaje ?>
<ul class="nav nav-tabs mb-4" role="tablist"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#individual" type="button">Individual</button></li><?php if($esAdmin): ?><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#masiva" type="button">Masiva por grupo</button></li><?php endif; ?></ul>
<div class="tab-content"><div class="tab-pane fade show active" id="individual"><form method="POST">
<div class="mb-3"><label class="form-label">Alumno asignado</label><select name="id_alumno" class="form-select" required><option value="">Selecciona...</option>
<?php
if ($esDocente) { $students=$conn->prepare("SELECT DISTINCT a.id_alumno,a.matricula,a.nombre_alum,a.A_paterno_alum,a.A_materno_alum FROM asignat x JOIN alumnos a ON a.id_alumno=x.Id_alumno WHERE x.Id_profesor=? AND a.estatus='ALTA' ORDER BY a.A_paterno_alum,a.nombre_alum"); $students->bind_param("i",$idProfesorSession); $students->execute(); $resStudents=$students->get_result(); }
else { $resStudents=$conn->query("SELECT id_alumno,matricula,nombre_alum,A_paterno_alum,A_materno_alum FROM alumnos WHERE estatus='ALTA' ORDER BY A_paterno_alum,nombre_alum"); }
while($a=$resStudents->fetch_assoc()): ?><option value="<?= (int)$a['id_alumno'] ?>"><?= htmlspecialchars($a['matricula'].' - '.$a['A_paterno_alum'].' '.$a['A_materno_alum'].' '.$a['nombre_alum']) ?></option><?php endwhile; ?>
</select></div>
<div class="mb-3"><label class="form-label">Materia</label><select name="id_unidad" class="form-select" required><option value="">Selecciona...</option>
<?php if($esDocente){$subjects=$conn->prepare("SELECT DISTINCT m.id_materia,m.descripcion_materia FROM asignat x JOIN materias m ON m.id_materia=x.Id_mat WHERE x.Id_profesor=? AND m.estatus_materia='ALTA' ORDER BY m.descripcion_materia");$subjects->bind_param("i",$idProfesorSession);$subjects->execute();$resSubjects=$subjects->get_result();}else{$resSubjects=$conn->query("SELECT id_materia,descripcion_materia FROM materias WHERE estatus_materia='ALTA' ORDER BY descripcion_materia");} while($m=$resSubjects->fetch_assoc()): ?><option value="<?= (int)$m['id_materia'] ?>"><?= htmlspecialchars($m['descripcion_materia']) ?></option><?php endwhile; ?>
</select></div><div class="mb-3"><label class="form-label">Calificación (0–100)</label><input type="number" name="calificacion" min="0" max="100" step="0.01" class="form-control" required></div><button name="guardar_individual" class="btn btn-primary">Guardar calificación</button></form></div>
<?php if($esAdmin): ?><div class="tab-pane fade" id="masiva"><form method="POST" class="row g-3 align-items-end mb-4"><input type="hidden" name="active_tab" value="masiva"><div class="col-md-5"><label class="form-label">Grupo</label><select name="id_grupo" class="form-select" required><option value="">Selecciona...</option><?php $groups=$conn->query("SELECT id_grupo,descripcion_grupo FROM grupo WHERE estatus_grupo='ALTA' ORDER BY descripcion_grupo");while($g=$groups->fetch_assoc()): ?><option value="<?= (int)$g['id_grupo'] ?>" <?= $grupoSeleccionado===(int)$g['id_grupo']?'selected':'' ?>><?= htmlspecialchars($g['descripcion_grupo']) ?></option><?php endwhile; ?></select></div><div class="col-md-5"><label class="form-label">Materia</label><select name="id_unidad_masiva" class="form-select" required><option value="">Selecciona...</option><?php $subjects=$conn->query("SELECT id_materia,descripcion_materia FROM materias WHERE estatus_materia='ALTA' ORDER BY descripcion_materia");while($m=$subjects->fetch_assoc()): ?><option value="<?= (int)$m['id_materia'] ?>" <?= $materiaSeleccionada===(int)$m['id_materia']?'selected':'' ?>><?= htmlspecialchars($m['descripcion_materia']) ?></option><?php endwhile; ?></select></div><div class="col-md-2"><button name="cargar_masiva" class="btn btn-secondary w-100">Cargar alumnos</button></div></form>
<?php if($mostrarMasiva && $grupoSeleccionado && $materiaSeleccionada):
$studentSql="SELECT DISTINCT a.id_alumno,a.matricula,a.nombre_alum,a.A_paterno_alum,a.A_materno_alum FROM insgrup ig JOIN alumnos a ON a.id_alumno=ig.Id_alumno WHERE ig.Id_grupo=? AND ig.Estatus_ins='ALTA' AND a.estatus='ALTA' ORDER BY a.A_paterno_alum,a.A_materno_alum,a.nombre_alum";
$stmt=$conn->prepare($studentSql);$stmt->bind_param("i",$grupoSeleccionado);$stmt->execute();$studentRows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC); ?>
<form method="POST"><input type="hidden" name="id_grupo_hidden" value="<?= $grupoSeleccionado ?>"><input type="hidden" name="id_unidad_hidden" value="<?= $materiaSeleccionada ?>"><div class="table-responsive"><table class="table table-striped table-bordered"><thead><tr><th>Matrícula</th><th>Alumno</th><th>Calificación</th></tr></thead><tbody>
<?php foreach($studentRows as $a): $q=$conn->prepare("SELECT Cal FROM capcal WHERE Id_alumno=? AND Id_Unidad=? LIMIT 1");$q->bind_param("ii",$a['id_alumno'],$materiaSeleccionada);$q->execute();$existing=$q->get_result()->fetch_assoc(); ?><tr><td><?= htmlspecialchars($a['matricula']) ?></td><td><?= htmlspecialchars($a['A_paterno_alum'].' '.$a['A_materno_alum'].' '.$a['nombre_alum']) ?></td><td><input type="number" name="calificaciones[<?= (int)$a['id_alumno'] ?>]" min="0" max="100" step="0.01" value="<?= htmlspecialchars($existing['Cal'] ?? '') ?>" class="form-control" placeholder="0–100"></td></tr><?php endforeach; ?>
<?php if(!$studentRows): ?><tr><td colspan="3" class="text-center">No hay alumnos inscritos activos en este grupo.</td></tr><?php endif; ?></tbody></table></div><?php if($studentRows): ?><button name="guardar_masiva" class="btn btn-success w-100">Guardar calificaciones del grupo</button><?php endif; ?></form><?php endif; ?></div><?php endif; ?></div>
</div></div></div>
<?php include("../navegacion/footer.php"); ?>


