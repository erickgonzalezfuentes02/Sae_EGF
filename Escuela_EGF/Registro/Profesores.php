<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede registrar profesores.'); }
require_once("../conexion.php");
require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");
?>
<div class="container mt-5 mb-5"><div class="row justify-content-center"><div class="col-md-7"><div class="card shadow-lg border-0 rounded-4 overflow-hidden">
<div class="card-header bg-primary text-white py-3"><h4 class="mb-0 fw-bold"><i class="bi bi-person-badge-fill me-2"></i>Registro de profesores</h4></div>
<div class="card-body p-4"><form action="../Proceso/Procesar_profesores.php" method="POST">
<div class="mb-3"><label class="form-label">Número de control</label><input type="text" name="N_control_profesor" class="form-control" required maxlength="15"></div>
<div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="nombre_profesor" class="form-control" required maxlength="15"></div>
<div class="mb-3"><label class="form-label">Apellido paterno</label><input type="text" name="A_paterno_profesor" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Apellido materno</label><input type="text" name="A_materno_profesor" class="form-control" required maxlength="15"></div>
<div class="mb-3"><label class="form-label">Correo electrónico</label><input type="email" name="mail_profesor" class="form-control" required maxlength="50"></div>
<div class="mb-3"><label class="form-label">Domicilio</label><input type="text" name="Domicilio_profesor" class="form-control" maxlength="80"></div>
<div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="telefono_profesor" class="form-control" maxlength="35"></div>
<input type="hidden" name="accion" value="insertar"><div class="d-grid"><button type="submit" class="btn btn-primary py-2 fw-semibold">Guardar profesor</button></div>
</form></div></div></div></div></div>
<?php include("../navegacion/footer.php"); ?>

