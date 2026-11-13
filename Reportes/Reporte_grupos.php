<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT * FROM grupo ORDER BY id_grupo";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Reporte General de Grupos</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Descripción del Grupo</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id_grupo'] ?></td>
                            <td><?= htmlspecialchars($row['descripcion_grupo']) ?></td>
                            <td>
                                <span class="badge <?= $row['estatus_grupo'] === 'ALTA' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($row['estatus_grupo']) ?>
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