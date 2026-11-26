<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

if (($_SESSION['puesto'] ?? '') !== 'Administrador') {
    http_response_code(403);
    exit('Solo el administrador puede acceder a esta sección.');
}

require_once("../conexion.php");
require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");

if (isset($_GET['nuevo']) && $_GET['nuevo'] === '1') {
    ?>
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Registrar alumno</h4>
                <a href="Alumno.php" class="btn btn-sm btn-light">Volver al catálogo</a>
            </div>

            <div class="card-body p-4">
                <form action="insert_alumno.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Matrícula</label>
                            <input type="text" name="matricula" class="form-control" maxlength="15" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre_alum" class="form-control" maxlength="15" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido paterno</label>
                            <input type="text" name="A_paterno_alum" class="form-control" maxlength="15" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellido materno</label>
                            <input type="text" name="A_materno_alum" class="form-control" maxlength="15" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Domicilio</label>
                            <input type="text" name="domicilio" class="form-control" maxlength="80" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="mail_alum" class="form-control" maxlength="50" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" maxlength="35" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar alumno</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    require_once("../navegacion/footer.php");
    exit();
}

$sql = "SELECT id_alumno, matricula, nombre_alum, A_paterno_alum,
               A_materno_alum, domicilio, mail_alum, telefono, estatus
        FROM alumnos";
$resultado = mysqli_query($conn, $sql);
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-people-fill me-2"></i>Reporte General de Alumnos
            </h4>

            <a href="?nuevo=1" class="btn btn-sm btn-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>Nuevo Alumno
            </a>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Domicilio</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                                <?php
                                $id = (int)$row['id_alumno'];
                                $estatus = $row['estatus'] ?? 'ALTA';
                                $badge = ($estatus === 'BAJA') ? 'bg-danger' : 'bg-success';
                                ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= htmlspecialchars($row['matricula'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['nombre_alum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['A_paterno_alum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['A_materno_alum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['domicilio'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['mail_alum'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($row['telefono'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="badge <?= $badge ?>">
                                            <?= htmlspecialchars($estatus, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../modificaciones/Modif_alumnos.php?id=<?= $id ?>"
                                           class="btn btn-sm btn-warning text-white me-1" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="../Baja/Baja_alumno.php?id=<?= $id ?>"
                                           class="btn btn-sm btn-danger" title="Dar de baja">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No hay registros de alumnos guardados en el sistema.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once("../navegacion/footer.php"); ?>