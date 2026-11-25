<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SESSION['puesto'] != 'Administrador') {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow p-4 text-center'><strong>Acceso denegado:</strong> Sección exclusiva para Administrador.</div></div>";
    exit();
}

include("../conexion.php");
include("../navegacion/header.php");
include("../navegacion/navegacion.php");

// Detectar columnas reales de la tabla materias
$cols = [];
$resCols = mysqli_query($conn, "SHOW COLUMNS FROM materias");
if ($resCols) {
    while ($c = mysqli_fetch_assoc($resCols)) {
        $cols[] = $c['Field'];
    }
}
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-book-fill me-2"></i> Reporte General de Materias</h4>
            <a href="../Registro/Materia.php" class="btn btn-sm btn-primary fw-semibold"><i class="bi bi-plus-lg me-1"></i> Nueva Materia</a>
        </div>
        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Materia</th>
                            <th>Nombre de la Materia</th>
                            <th>Créditos / Horas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM materias";
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                $id = $row['id_materia'] ?? $row['id'] ?? reset($row);
                                $nombre = '';
                                $creditos = '';

                                foreach ($row as $colName => $val) {
                                    $low = strtolower($colName);
                                    if ($nombre === '' && (strpos($low, 'mat') !== false || strpos($low, 'nom') !== false || strpos($low, 'desc') !== false)) {
                                        // Evitar confundir id_materia con el nombre
                                        if (strpos($low, 'id_') === false) {
                                            $nombre = $val;
                                        }
                                    } elseif ($creditos === '' && (strpos($low, 'cred') !== false || strpos($low, 'hor') !== false)) {
                                        $creditos = $val;
                                    }
                                }

                                // Si no encontró por palabra clave y hay al menos 2 columnas, toma la segunda columna de texto/datos
                                if ($nombre === '' && count($row) >= 2) {
                                    $values = array_values($row);
                                    $nombre = $values[1] ?? '';
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars((string)$id) . "</td>";
                                echo "<td>" . htmlspecialchars((string)$nombre) . "</td>";
                                echo "<td>" . htmlspecialchars((string)($creditos !== '' ? $creditos : 'N/D')) . "</td>";
                                echo "<td>
                                        <a href='../modificaciones/Modif_materias.php?idmateria=" . htmlspecialchars((string)$id) . "' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='../Baja/Baja_materia.php?idmateria=" . htmlspecialchars((string)$id) . "' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No hay registros de materias guardadas en el sistema.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($cols)): ?>
                <div class="text-muted small mt-2">Columnas en <code>materias</code>: <?php echo htmlspecialchars(implode(', ', $cols)); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("../navegacion/footer.php"); ?>
