<?php
session_start();
if (!isset($_SESSION['id_user'])) { header('Location: ../login.php'); exit(); }
$rol = $_SESSION['puesto'] ?? '';
if (!in_array($rol, ['Administrador', 'Docente', 'Alumno'], true)) { http_response_code(403); exit('Acceso denegado.'); }
require_once('../conexion.php');
require_once('../navegacion/header.php');
require_once('../navegacion/navegacion.php');
$opciones = [];
if ($rol === 'Administrador') {
    $opciones = ['alumnos'=>'Reporte de alumnos','profesores'=>'Reporte de profesores','materias'=>'Reporte de materias','grupos'=>'Reporte de grupos','insgrup'=>'Alumnos por grupo','asignat'=>'Materias asignadas','capcal'=>'Calificaciones'];
} elseif ($rol === 'Docente') {
    $opciones = ['insgrup'=>'Alumnos de mis grupos','asignat'=>'Mis materias y alumnos','capcal'=>'Calificaciones de mis grupos'];
} else {
    $opciones = ['mi_alumno'=>'Mis datos, grupos y calificaciones'];
}
?>
<main class="container my-4"><div class="card shadow-sm"><div class="card-header bg-primary text-white"><h3 class="mb-0">Exportar reportes a Excel</h3></div><div class="card-body">
<p>Selecciona el reporte. Se descargará como archivo <strong>.xls</strong>, compatible con Microsoft Excel.</p><div class="list-group">
<?php foreach ($opciones as $tipo=>$titulo): ?><a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="Descargar_excel.php?reporte=<?= rawurlencode($tipo) ?>"><span><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></span><span class="btn btn-success btn-sm"><i class="bi bi-file-earmark-spreadsheet me-1"></i>Descargar Excel</span></a><?php endforeach; ?>
</div></div></div></main>
<?php require_once('../navegacion/footer.php'); ?>
