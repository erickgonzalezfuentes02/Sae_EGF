<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Nuevo Alumno</h4>
        </div>
        <div class="card-body">
            <form action="../Proceso/Procesar_alumnos.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Matrícula / No. Control</label>
                    <input type="text" class="form-control" name="matricula" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre_alum" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" name="A_paterno_alum" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" name="A_materno_alum" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio</label>
                    <input type="text" class="form-control" name="domicilio" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="mail_alum" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar Alumno</button>
                    <a href="../Crude/crudealumnos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
include ("../navegacion/footer.php"); 
?>