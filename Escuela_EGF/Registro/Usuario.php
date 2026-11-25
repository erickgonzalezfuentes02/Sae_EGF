<?php
session_start();
if (!isset($_SESSION['id_user']) || ($_SESSION['puesto'] ?? '') !== 'Administrador') { header("Location: ../login.php"); exit(); }
require_once("../conexion.php"); require_once("../navegacion/header.php"); require_once("../navegacion/navegacion.php");
$mensajes=['datos_invalidos'=>'Completa los datos y vincula la cuenta con una ficha.','puesto_invalido'=>'El rol seleccionado no está activo.','perfil_invalido'=>'Selecciona un docente o alumno activo.','usuario_repetido'=>'Ese nombre de usuario ya existe.','error'=>'No se pudo guardar. Ejecuta primero BD/vincular_usuarios_personas.sql.'];
$mensaje=$mensajes[$_GET['msg'] ?? ''] ?? '';
$roles=$conn->query("SELECT id_puesto,descripcion_puesto FROM puesto WHERE status_puesto='Alta' ORDER BY id_puesto");
$docentes=$conn->query("SELECT id_profesor,N_control_profesor,nombre_profesor,A_paterno_profesor,A_materno_profesor FROM profesor WHERE estatus_profesor='ALTA' ORDER BY A_paterno_profesor,nombre_profesor");
$alumnos=$conn->query("SELECT id_alumno,matricula,nombre_alum,A_paterno_alum,A_materno_alum FROM alumnos WHERE estatus='ALTA' ORDER BY A_paterno_alum,nombre_alum");
?>
<div class="container mt-5 mb-5">
<?php if($mensaje!==''): ?><div class="alert alert-warning"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
<div class="row justify-content-center"><div class="col-md-7"><div class="card shadow-lg border-0 rounded-4 overflow-hidden"><div class="card-header bg-primary text-white py-3"><h4 class="mb-0 fw-bold">Registro de cuenta y permisos</h4><p class="text-white-50 small mb-0">Cada cuenta se vincula con una ficha de docente o alumno.</p></div><div class="card-body p-4">
<form action="../Proceso/Procesar_usuario.php" method="POST">
<div class="mb-3"><label class="form-label">Nombre de usuario</label><input type="text" name="nombre_user" class="form-control" required autocomplete="off"></div>
<div class="mb-3"><label class="form-label">Contraseña</label><input type="password" name="pw_user" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Rol</label><select id="id_puesto" name="id_puesto" class="form-select" required><option value="">Selecciona rol...</option>
<?php if($roles): while($r=$roles->fetch_assoc()): ?><option value="<?= (int)$r['id_puesto'] ?>" data-role="<?= htmlspecialchars(mb_strtolower($r['descripcion_puesto'])) ?>"><?= htmlspecialchars($r['descripcion_puesto']) ?></option><?php endwhile; endif; ?></select></div>
<div class="mb-3" id="docente-wrap" hidden><label class="form-label">Ficha del docente</label><select name="id_profesor" id="id_profesor" class="form-select"><option value="">Selecciona docente...</option><?php if($docentes): while($d=$docentes->fetch_assoc()): ?><option value="<?= (int)$d['id_profesor'] ?>"><?= htmlspecialchars($d['N_control_profesor'].' - '.$d['A_paterno_profesor'].' '.$d['A_materno_profesor'].' '.$d['nombre_profesor']) ?></option><?php endwhile; endif; ?></select></div>
<div class="mb-3" id="alumno-wrap" hidden><label class="form-label">Ficha del alumno</label><select name="id_alumno" id="id_alumno" class="form-select"><option value="">Selecciona alumno...</option><?php if($alumnos): while($a=$alumnos->fetch_assoc()): ?><option value="<?= (int)$a['id_alumno'] ?>"><?= htmlspecialchars($a['matricula'].' - '.$a['A_paterno_alum'].' '.$a['A_materno_alum'].' '.$a['nombre_alum']) ?></option><?php endwhile; endif; ?></select></div>
<div class="d-grid"><button type="submit" class="btn btn-primary py-2">Crear cuenta</button></div></form>
</div></div></div></div></div>
<script>
(()=>{const role=document.getElementById('id_puesto'),dw=document.getElementById('docente-wrap'),aw=document.getElementById('alumno-wrap'),ds=document.getElementById('id_profesor'),as=document.getElementById('id_alumno');function sync(){const t=(role.selectedOptions[0]?.dataset.role||'').toLowerCase(),d=t.includes('docente')||t.includes('profesor'),a=t.includes('alumno');dw.hidden=!d;aw.hidden=!a;ds.required=d;as.required=a;if(!d)ds.value='';if(!a)as.value='';}role.addEventListener('change',sync);sync();})();
</script>
<?php include("../navegacion/footer.php"); ?>
