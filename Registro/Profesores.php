<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
?>

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Registrar Nuevo Profesor</h4>
        </div>
        <div class="card-body">
            <form action="../Proceso/Procesar_profesores.php" method="POST">
                <input type="hidden" name="accion" value="insertar">
                <div class="mb-3">
                    <label class="form-label">Número de Control</label>
                    <input type="text" class="form-control" name="N_control_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" name="A_paterno_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" name="A_materno_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio</label>
                    <input type="text" class="form-control" name="Domicilio_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="mail_profesor" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono_profesor" required>
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