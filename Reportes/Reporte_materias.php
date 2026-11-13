<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT * FROM materias ORDER BY id_materia";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Reporte General de Materias</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Descripción de la Materia</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id_materia'] ?></td>
                            <td><?= htmlspecialchars($row['descripcion_materia']) ?></td>
                            <td>
                                <span class="badge <?= $row['estatus_materia'] === 'ALTA' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($row['estatus_materia']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>