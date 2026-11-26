<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede acceder a esta sección.'); }


if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

include("../conexion.php");
include("../navegacion/header.php");
include("../navegacion/navegacion.php");

$msg = $_GET['msg'] ?? '';
?>

<div class="container mt-4 mb-5">
    <?php if ($msg === 'agregado'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Grupo registrado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($msg === 'editado'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Grupo actualizado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($msg === 'eliminado'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Grupo dado de baja correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-collection-fill me-2"></i> Catálogo / Gestión de Grupos</h4>
            <a href="../Registro/Grupos.php" class="btn btn-sm btn-primary fw-semibold"><i class="bi bi-plus-lg me-1"></i> Nuevo Grupo</a>
        </div>
        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Grupo</th>
                            <th>Descripción del Grupo</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id_grupo, descripcion_grupo, estatus_grupo FROM grupo";
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                $id = $row['id_grupo'];
                                $desc = $row['descripcion_grupo'];
                                $estatus = $row['estatus_grupo'] ?? 'ALTA';
                                $badge = ($estatus === 'BAJA') ? 'bg-danger' : 'bg-success';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($id) . "</td>";
                                echo "<td>" . htmlspecialchars($desc) . "</td>";
                                echo "<td><span class='badge " . $badge . "'>" . htmlspecialchars($estatus) . "</span></td>";
                                echo "<td>
                                        <a href='../modificaciones/Modif_grupos.php?idgrupo=" . htmlspecialchars($id) . "' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='../Baja/Baja_grupo.php?idgrupo=" . htmlspecialchars($id) . "' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center text-muted py-4'>No hay grupos registrados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("../navegacion/footer.php"); ?>

