<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Registro de Alumno</h4>
        </div>
        <div class="card-body">
            <form action="insert_alumno.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Matrícula</label>
                    <input type="text" name="matricula" class="form-control" placeholder="Ej. 0202020" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre_alum" class="form-control" placeholder="Nombre(s)" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" name="A_parterno_alum" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" name="A_materno_alum" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio</label>
                    <input type="text" name="domicilio" class="form-control" placeholder="Dirección completa" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="mail_alum" class="form-control" placeholder="correo@ejemplo.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" placeholder="5512345678" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar Alumno</button>
                    <a href="../Crude/crudalumnos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>