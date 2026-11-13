<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT a.matricula, a.nombre_alum, a.A_paterno_alum, a.A_materno_alum, m.descripcion_materia, p.nombre_profesor 
        FROM asignat asig
        INNER JOIN alumnos a ON asig.id_alumno = a.id_alumno
        INNER JOIN materias m ON asig.id_mat = m.id_materia
        LEFT JOIN profesor p ON asig.id_profesor = p.id_profesor
        ORDER BY a.A_paterno_alum, m.descripcion_materia";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Lista de Materias por Alumno</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Matrícula</th>
                        <th>Alumno</th>
                        <th>Materia Asignada</th>
                        <th>Profesor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['matricula']) ?></td>
                                <td><?= htmlspecialchars($row['A_paterno_alum'] . ' ' . $row['A_materno_alum'] . ' ' . $row['nombre_alum']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['descripcion_materia']) ?></span></td>
                                <td><?= htmlspecialchars($row['nombre_profesor'] ?? 'Sin asignar') ?></td>
                            </tr>
                        <?php } 
                    } else { ?>
                        <tr><td colspan="4" class="text-muted">No hay materias asignadas a alumnos.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>