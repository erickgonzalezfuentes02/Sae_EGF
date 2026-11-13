<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");
?>
<div class="container my-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Asignación de Materias</h3>
        </div>
        <div class="card-body">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Materia</label>
                    <select name="id_materia" class="form-control" required>
                        <option value="">Selecciona una materia...</option>
                        <?php 
                        $res_m = $conn->query("SELECT * FROM materias");
                        while($m = $res_m->fetch_assoc()) {
                            echo "<option value='".$m['id_materia']."'>".$m['descripcion_materia']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alumno</label>
                    <select name="id_alumno" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php 
                        $res_a = $conn->query("SELECT * FROM alumnos");
                        while($a = $res_a->fetch_assoc()) {
                            echo "<option value='".$a['id_alumno']."'>".$a['matricula']." - ".$a['nombre_alum']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="asignar" class="btn btn-primary">Asignar materia</button>
            </form>
        </div>
    </div>
</div>
<?php include ("../navegacion/footer.php"); ?>