<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT c.Cal, a.matricula, a.nombre_alum, a.A_paterno_alum, a.A_materno_alum, m.descripcion_materia 
        FROM capcal c
        INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
        INNER JOIN materias m ON c.id_Unidad = m.id_materia
        ORDER BY a.matricula";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Boleta de Calificaciones</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Matrícula</th>
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Calificación Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['matricula']) ?></td>
                                <td><?= htmlspecialchars($row['A_paterno_alum'] . ' ' . $row['A_materno_alum'] . ' ' . $row['nombre_alum']) ?></td>
                                <td><?= htmlspecialchars($row['descripcion_materia']) ?></td>
                                <td><span class="badge bg-success fs-6"><?= htmlspecialchars($row['Cal']) ?></span></td>
                            </tr>
                        <?php } 
                    } else { ?>
                        <tr><td colspan="4" class="text-muted">No hay registros en boletas.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>