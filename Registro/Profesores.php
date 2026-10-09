<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-4" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Registro de Profesor</h4>
        </div>
        <div class="card-body">
            <form action="../Proceso/Procesar_profesores.php" method="POST">
                <input type="hidden" name="accion" value="insertar">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre_profesor" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" name="A_paterno_prof" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" name="A_materno_prof" class="form-control" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar Profesor</button>
                    <a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>