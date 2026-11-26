<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$rol_actual = $_SESSION['puesto'] ?? '';
$base = defined('BASE_URL') ? BASE_URL : '/Escuela_EGF/';
$inicio = $rol_actual === 'Alumno' ? 'Reportes/Reporte_mi_alumno.php' : ($rol_actual === 'Docente' ? 'Proceso/Proceso_capcal.php' : 'Reportes/Reporte_alumnos.php');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm"><div class="container-fluid">
<a class="navbar-brand fw-bold" href="<?= htmlspecialchars($base.$inicio) ?>"><i class="bi bi-mortarboard-fill text-primary me-2"></i>Escuela_EGF</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Abrir menú"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navbarNav"><ul class="navbar-nav me-auto">
<?php if ($rol_actual === 'Administrador'): ?>
<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Catálogos</a><ul class="dropdown-menu shadow">
<li><a class="dropdown-item" href="<?= $base ?>Registro/Alumno.php">Alumnos</a></li><li><a class="dropdown-item" href="<?= $base ?>Registro/Profesores.php">Profesores</a></li><li><a class="dropdown-item" href="<?= $base ?>Registro/Materia.php">Materias</a></li><li><a class="dropdown-item" href="<?= $base ?>Registro/Grupos.php">Grupos</a></li></ul></li>
<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Procesos</a><ul class="dropdown-menu shadow"><li><a class="dropdown-item" href="<?= $base ?>Proceso/Proceso_capcal.php">Capturar calificaciones</a></li><li><a class="dropdown-item" href="<?= $base ?>Proceso/Proceso_insgrup.php">Inscribir a grupos</a></li><li><a class="dropdown-item" href="<?= $base ?>Proceso/Proceso_asignat.php">Asignar materias y docentes</a></li></ul></li>
<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Reportes</a><ul class="dropdown-menu shadow"><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_alumnos.php">Reporte alumnos</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_profesores.php">Reporte profesores</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_materias.php">Reporte materias</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_grupos.php">Reporte grupos</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_insgrup.php">Alumnos por grupo</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_asignat.php">Materias asignadas</a></li><li><a class="dropdown-item" href="<?= $base ?>Reportes/Reporte_capcal.php">Calificaciones</a></li><li><hr class="dropdown-divider"></li><li><a class="dropdown-item fw-semibold" href="<?= $base ?>Reportes/Exportar_excel.php">Exportar a Excel</a></li></ul></li>
<?php elseif ($rol_actual === 'Docente'): ?>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Proceso/Proceso_capcal.php">Capturar calificaciones</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Reportes/Reporte_asignat.php">Mis materias y alumnos</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Reportes/Reporte_capcal.php">Calificaciones capturadas</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Reportes/Exportar_excel.php">Exportar a Excel</a></li>
<?php elseif ($rol_actual === 'Alumno'): ?>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Reportes/Reporte_mi_alumno.php">Mis consultas</a></li>
<li class="nav-item"><a class="nav-link" href="<?= $base ?>Reportes/Exportar_excel.php">Exportar a Excel</a></li>
<?php endif; ?>
</ul><ul class="navbar-nav ms-auto align-items-center">
<?php if ($rol_actual === 'Administrador'): ?><li class="nav-item dropdown me-2"><a class="nav-link dropdown-toggle text-warning fw-semibold" href="#" data-bs-toggle="dropdown"><i class="bi bi-shield-lock-fill"></i> Panel Admin</a><ul class="dropdown-menu dropdown-menu-end shadow"><li><a class="dropdown-item" href="<?= $base ?>Registro/Usuario.php">Registrar cuenta y rol</a></li><li><a class="dropdown-item" href="<?= $base ?>Crude/crudeusuarios.php">Gestionar usuarios</a></li></ul></li><?php endif; ?>
<li class="nav-item text-white-50 small me-3"><i class="bi bi-person-circle text-light me-1"></i><?= htmlspecialchars($_SESSION['nombre_user'] ?? 'Usuario') ?> <span class="badge bg-secondary ms-1"><?= htmlspecialchars($rol_actual) ?></span></li>
<li class="nav-item"><a class="btn btn-outline-danger btn-sm px-3" href="<?= $base ?>logout.php"><i class="bi bi-box-arrow-right me-1"></i>Salir</a></li>
</ul></div></div></nav>
