<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT c.Cal, m.descripcion_materia, a.matricula, a.nombre_alum, a.A_paterno_alum 
        FROM capcal c
        INNER JOIN materias m ON c.Id_Unidad = m.id_materia
        INNER JOIN alumnos a ON c.id_alumno = a.id_alumno
        ORDER BY a.A_paterno_alum";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Calificaciones por Materia y Grupo</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Matrícula</th>
                        <th>Alumno</th>
                        <th>Materia</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['matricula']) ?></td>
                                <td><?= htmlspecialchars($row['A_paterno_alum'] . ' ' . $row['nombre_alum']) ?></td>
                                <td><?= htmlspecialchars($row['descripcion_materia']) ?></td>
                                <td><strong class="text-primary"><?= htmlspecialchars($row['Cal']) ?></strong></td>
                            </tr>
                        <?php } 
                    } else { ?>
                        <tr><td colspan="4" class="text-muted">No hay calificaciones capturadas todavía.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>