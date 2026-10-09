<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-4" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Registro de Materia</h4>
        </div>
        <div class="card-body">
            <form action="../Proceso/Procesar_materias.php" method="POST">
                <input type="hidden" name="accion" value="insertar">

                <div class="mb-3">
                    <label class="form-label">Nombre de la Materia</label>
                    <input type="text" name="nombre_materia" class="form-control" placeholder="Ej. Base de Datos, Redes" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar Materia</button>
                    <a href="../Crude/crudemateria.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>