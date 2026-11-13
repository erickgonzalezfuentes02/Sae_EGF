<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$sql = "SELECT * FROM profesor ORDER BY id_profesor";
$resultado = $conn->query($sql);
?>

<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Reporte General de Profesores</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>No. Control</th>
                        <th>Nombre</th>
                        <th>Ap. Paterno</th>
                        <th>Ap. Materno</th>
                        <th>Domicilio</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['id_profesor'] ?></td>
                            <td><?= htmlspecialchars($row['N_control_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['nombre_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['A_paterno_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['A_materno_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['Domicilio_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['mail_profesor']) ?></td>
                            <td><?= htmlspecialchars($row['telefono_profesor']) ?></td>
                            <td>
                                <span class="badge <?= $row['estatus_profesor'] === 'ALTA' ? 'bg-success' : 'bg-danger' ?>">
                                    <?= htmlspecialchars($row['estatus_profesor']) ?>
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