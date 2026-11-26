<?php
session_start();
if (!isset($_SESSION['id_user'])) { http_response_code(401); exit('Inicia sesión para descargar reportes.'); }
$rol = $_SESSION['puesto'] ?? '';
if (!in_array($rol, ['Administrador', 'Docente', 'Alumno'], true)) { http_response_code(403); exit('Acceso denegado.'); }
require_once('../conexion.php');
$tipo = $_GET['reporte'] ?? '';
$idProfesor = (int)($_SESSION['id_profesor'] ?? 0);
$idAlumno = (int)($_SESSION['id_alumno'] ?? 0);

function obtenerFilasExportacion($conn, $sql, $tipos = '', $parametros = []) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) { http_response_code(500); exit('No fue posible preparar el reporte.'); }
    if ($tipos !== '') { $stmt->bind_param($tipos, ...$parametros); }
    if (!$stmt->execute()) { http_response_code(500); exit('No fue posible generar el reporte.'); }
    $res = $stmt->get_result();
    $filas = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    $stmt->close();
    return $filas;
}

$secciones = [];
$nombreArchivo = '';
if ($rol === 'Administrador') {
    $consultas = [
        'alumnos' => ['Reporte de alumnos', 'alumnos', 'SELECT id_alumno AS `ID alumno`, nombre_alum AS `Nombre`, A_paterno_alum AS `Apellido paterno`, A_materno_alum AS `Apellido materno`, matricula AS `Matrícula` FROM alumnos ORDER BY A_paterno_alum,A_materno_alum,nombre_alum'],
        'profesores' => ['Reporte de profesores', 'profesores', 'SELECT id_profesor AS `ID profesor`, N_control_profesor AS `No. control`, nombre_profesor AS `Nombre`, A_paterno_profesor AS `Apellido paterno`, A_materno_profesor AS `Apellido materno`, Domicilio_profesor AS `Domicilio`, mail_profesor AS `Correo`, telefono_profesor AS `Teléfono` FROM profesor ORDER BY A_paterno_profesor,A_materno_profesor,nombre_profesor'],
        'materias' => ['Reporte de materias', 'materias', 'SELECT id_materia AS `ID materia`, descripcion_materia AS `Materia` FROM materias ORDER BY descripcion_materia'],
        'grupos' => ['Reporte de grupos', 'grupos', 'SELECT id_grupo AS `ID grupo`, descripcion_grupo AS `Grupo` FROM grupo ORDER BY descripcion_grupo'],
        'insgrup' => ['Alumnos por grupo', 'alumnos_por_grupo', 'SELECT g.descripcion_grupo AS `Grupo`, a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre` FROM insgrup ig INNER JOIN grupo g ON g.id_grupo=ig.Id_grupo INNER JOIN alumnos a ON a.id_alumno=ig.Id_alumno ORDER BY g.descripcion_grupo,a.A_paterno_alum,a.A_materno_alum,a.nombre_alum'],
        'asignat' => ['Materias asignadas', 'materias_asignadas', 'SELECT a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre`, m.descripcion_materia AS `Materia`, p.N_control_profesor AS `No. control docente`, p.A_paterno_profesor AS `Apellido paterno docente`, p.A_materno_profesor AS `Apellido materno docente`, p.nombre_profesor AS `Nombre docente` FROM asignat x INNER JOIN alumnos a ON a.id_alumno=x.Id_alumno INNER JOIN materias m ON m.id_materia=x.Id_mat LEFT JOIN profesor p ON p.id_profesor=x.Id_profesor ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia'],
        'capcal' => ['Calificaciones', 'calificaciones', 'SELECT a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre`, m.descripcion_materia AS `Materia`, c.Cal AS `Calificación` FROM capcal c INNER JOIN materias m ON m.id_materia=c.Id_Unidad INNER JOIN alumnos a ON a.id_alumno=c.Id_alumno ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia']
    ];
    if (!isset($consultas[$tipo])) { http_response_code(400); exit('Reporte no válido.'); }
    [$titulo, $archivo, $sql] = $consultas[$tipo];
    $nombreArchivo = $archivo;
    $secciones[] = ['titulo'=>$titulo, 'filas'=>obtenerFilasExportacion($conn, $sql)];
} elseif ($rol === 'Docente') {
    if (!$idProfesor || !in_array($tipo, ['insgrup','asignat','capcal'], true)) { http_response_code(403); exit('Este reporte no está disponible para esta cuenta docente.'); }
    if ($tipo === 'insgrup') {
        $sql = 'SELECT g.descripcion_grupo AS `Grupo`, a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre` FROM insgrup ig INNER JOIN grupo g ON g.id_grupo=ig.Id_grupo INNER JOIN alumnos a ON a.id_alumno=ig.Id_alumno WHERE EXISTS (SELECT 1 FROM asignat x WHERE x.Id_alumno=a.id_alumno AND x.Id_profesor=?) ORDER BY g.descripcion_grupo,a.A_paterno_alum,a.A_materno_alum,a.nombre_alum';
        $titulo = 'Alumnos por grupo';
    } elseif ($tipo === 'asignat') {
        $sql = 'SELECT a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre`, m.descripcion_materia AS `Materia` FROM asignat x INNER JOIN alumnos a ON a.id_alumno=x.Id_alumno INNER JOIN materias m ON m.id_materia=x.Id_mat WHERE x.Id_profesor=? ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia';
        $titulo = 'Mis materias y alumnos';
    } else {
        $sql = 'SELECT a.matricula AS `Matrícula`, a.A_paterno_alum AS `Apellido paterno`, a.A_materno_alum AS `Apellido materno`, a.nombre_alum AS `Nombre`, m.descripcion_materia AS `Materia`, c.Cal AS `Calificación` FROM capcal c INNER JOIN materias m ON m.id_materia=c.Id_Unidad INNER JOIN alumnos a ON a.id_alumno=c.Id_alumno WHERE EXISTS (SELECT 1 FROM asignat x WHERE x.Id_alumno=c.Id_alumno AND x.Id_mat=c.Id_Unidad AND x.Id_profesor=?) ORDER BY a.A_paterno_alum,a.A_materno_alum,m.descripcion_materia';
        $titulo = 'Calificaciones de mis grupos';
    }
    $nombreArchivo = ['insgrup'=>'mis_alumnos_por_grupo','asignat'=>'mis_materias','capcal'=>'mis_calificaciones'][$tipo];
    $secciones[] = ['titulo'=>$titulo, 'filas'=>obtenerFilasExportacion($conn, $sql, 'i', [$idProfesor])];
} else {
    if ($tipo !== 'mi_alumno' || !$idAlumno) { http_response_code(403); exit('Esta cuenta no está vinculada a una ficha de alumno.'); }
    $nombreArchivo = 'mis_consultas_escolares';
    $secciones[] = ['titulo'=>'Mis datos y estatus', 'filas'=>obtenerFilasExportacion($conn, 'SELECT matricula AS `Matrícula`, A_paterno_alum AS `Apellido paterno`, A_materno_alum AS `Apellido materno`, nombre_alum AS `Nombre`, estatus AS `Estatus` FROM alumnos WHERE id_alumno=?', 'i', [$idAlumno])];
    $secciones[] = ['titulo'=>'Mis grupos', 'filas'=>obtenerFilasExportacion($conn, 'SELECT g.descripcion_grupo AS `Grupo`, ig.Estatus_ins AS `Estatus de inscripción` FROM insgrup ig INNER JOIN grupo g ON g.id_grupo=ig.Id_grupo WHERE ig.Id_alumno=? ORDER BY g.descripcion_grupo', 'i', [$idAlumno])];
    $secciones[] = ['titulo'=>'Mis calificaciones', 'filas'=>obtenerFilasExportacion($conn, 'SELECT m.descripcion_materia AS `Materia`, c.Cal AS `Calificación` FROM capcal c INNER JOIN materias m ON m.id_materia=c.Id_Unidad WHERE c.Id_alumno=? ORDER BY m.descripcion_materia', 'i', [$idAlumno])];
}

$nombreArchivo .= '_' . date('Y-m-d') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');
echo "\xEF\xBB\xBF";
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><style>table{border-collapse:collapse;margin-bottom:24px}th,td{border:1px solid #777;padding:5px}th{background:#d9e2f3;font-weight:bold}.titulo{font-size:16px;font-weight:bold;background:#1f4e78;color:#fff}</style></head><body>
<?php foreach ($secciones as $seccion): ?>
<table><thead><tr><th class="titulo" colspan="<?= max(1, count($seccion['filas'][0] ?? ['Reporte'])) ?>"><?= htmlspecialchars($seccion['titulo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></th></tr>
<?php if (!empty($seccion['filas'])): ?><tr><?php foreach (array_keys($seccion['filas'][0]) as $encabezado): ?><th><?= htmlspecialchars((string)$encabezado, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></th><?php endforeach; ?></tr><?php endif; ?></thead><tbody>
<?php if (!$seccion['filas']): ?><tr><td>Sin registros para mostrar.</td></tr><?php else: foreach ($seccion['filas'] as $fila): ?><tr><?php foreach ($fila as $valor): ?><td><?= htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td><?php endforeach; ?></tr><?php endforeach; endif; ?>
</tbody></table>
<?php endforeach; ?></body></html>
