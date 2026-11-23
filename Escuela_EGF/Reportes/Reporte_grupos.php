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
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-collection-fill me-2"></i> Reporte General de Grupos</h4>
            <a href="../Registro/Grupos.php" class="btn btn-sm btn-primary fw-semibold"><i class="bi bi-plus-lg me-1"></i> Nuevo Grupo</a>
        </div>
        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Grupo</th>
                            <th>Nombre / Descripción del Grupo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM grupo"; 
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                $id = $row['id_grupo'] ?? $row['id'] ?? '';
                                $nombre = $row['nombre_grupo'] ?? $row['descripcion_grupo'] ?? $row['grupo'] ?? $row['nombre'] ?? '';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($id) . "</td>";
                                echo "<td>" . htmlspecialchars($nombre) . "</td>";
                                echo "<td>
                                        <a href='../modificaciones/Modif_grupos.php?idgrupo=" . htmlspecialchars($id) . "' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='../Baja/Baja_grupo.php?idgrupo=" . htmlspecialchars($id) . "' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center text-muted py-4'>No hay registros de grupos guardados en el sistema.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("../navegacion/footer.php"); ?>
