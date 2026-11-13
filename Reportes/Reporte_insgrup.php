<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT g.descripcion_grupo, a.matricula, a.nombre_alum, a.A_paterno_alum, a.A_materno_alum 
        FROM insgrup ig
        INNER JOIN grupo g ON ig.id_grupo = g.id_grupo
        INNER JOIN alumnos a ON ig.id_alumno = a.id_alumno
        ORDER BY g.descripcion_grupo, a.A_paterno_alum";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Lista de Alumnos por Grupo</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Grupo</th>
                        <th>Matrícula</th>
                        <th>Nombre del Alumno</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($row['descripcion_grupo']) ?></span></td>
                                <td><?= htmlspecialchars($row['matricula']) ?></td>
                                <td><?= htmlspecialchars($row['A_paterno_alum'] . ' ' . $row['A_materno_alum'] . ' ' . $row['nombre_alum']) ?></td>
                            </tr>
                        <?php } 
                    } else { ?>
                        <tr><td colspan="3" class="text-muted">No hay alumnos inscritos en grupos todavía.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>