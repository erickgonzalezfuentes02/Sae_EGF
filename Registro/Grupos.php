<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-4" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Registro de Grupo</h4>
        </div>
        <div class="card-body">
            <form action="../Proceso/Procesar_grupos.php" method="POST">
                <input type="hidden" name="accion" value="insertar">
                
                <div class="mb-3">
                    <label class="form-label">Descripción del Grupo</label>
                    <input type="text" name="descripcion_grupo" class="form-control" placeholder="Ej. 3T1, 4T1" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Guardar Grupo</button>
                    <a href="../Crude/crudegrupos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>