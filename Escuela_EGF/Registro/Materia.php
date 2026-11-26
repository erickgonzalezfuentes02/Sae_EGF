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
    <div class="container mt-4 mb-5" style="max-width: 650px">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Registrar materia</h4>
                <a href="Materia.php" class="btn btn-sm btn-light">Volver al catálogo</a>
            </div>

            <div class="card-body p-4">
                <form action="../Proceso/Procesar_materias.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Descripción de la materia</label>
                        <input type="text" name="descripcion_materia"
                               class="form-control" maxlength="150" required>
                    </div>

                    <input type="hidden" name="accion" value="insertar">

                    <button type="submit" class="btn btn-primary">Guardar materia</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    require_once("../navegacion/footer.php");
    exit();
}

$sql = "SELECT id_materia, descripcion_materia, estatus_materia FROM materias";
$resultado = mysqli_query($conn, $sql);
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-book-fill me-2"></i>Reporte General de Materias
            </h4>

            <a href="?nuevo=1" class="btn btn-sm btn-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>Nueva Materia
            </a>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Materia</th>
                            <th>Descripción de la Materia</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                                <?php
                                $id = (int)$row['id_materia'];
                                $estatus = $row['estatus_materia'] ?? 'ALTA';
                                $badge = ($estatus === 'BAJA') ? 'bg-danger' : 'bg-success';
                                ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td><?= htmlspecialchars($row['descripcion_materia'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="badge <?= $badge ?>">
                                            <?= htmlspecialchars($estatus, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../modificaciones/Modif_materias.php?id=<?= $id ?>"
                                           class="btn btn-sm btn-warning text-white me-1" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="../Baja/Baja_materia.php?id=<?= $id ?>"
                                           class="btn btn-sm btn-danger" title="Dar de baja">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No hay materias registradas.
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