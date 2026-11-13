<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");
?>
<div class="container my-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Inscripción de Alumnos a Grupos</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Selecciona el Grupo</label>
                    <select name="id_grupo" class="form-control" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php 
                        $res_g = $conn->query("SELECT * FROM grupo");
                        while($g = $res_g->fetch_assoc()) {
                            echo "<option value='".$g['id_grupo']."'>".$g['descripcion_grupo']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Selecciona el Alumno</label>
                    <select name="id_alumno" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php 
                        $res_a = $conn->query("SELECT * FROM alumnos");
                        while($a = $res_a->fetch_assoc()) {
                            echo "<option value='".$a['id_alumno']."'>".$a['matricula']." - ".$a['nombre_alum']." ".$a['A_paterno_alum']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="inscribir" class="btn btn-primary">Inscribir alumno</button>
            </form>
        </div>
    </div>
</div>
<?php include ("../navegacion/footer.php"); ?>