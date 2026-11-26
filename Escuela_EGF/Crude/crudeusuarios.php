<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede acceder a esta sección.'); }



// Candado estricto para Administrador
if (!isset($_SESSION['id_user']) || $_SESSION['puesto'] != 'Administrador') {
    header("Location: ../login.php");
    exit();
}

include("../conexion.php");
include("../navegacion/header.php");
include("../navegacion/navegacion.php");
?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Gestión de Usuarios del Sistema</h4>
            <a href="../Registro/Usuario.php" class="btn btn-sm btn-primary fw-semibold"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Usuario</a>
        </div>
        <div class="card-body p-4 bg-white">
            <?php if (($_GET['msg'] ?? '') === 'agregado'): ?><div class="alert alert-success">Usuario y rol guardados correctamente.</div><?php endif; ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Usuario</th>
                            <th>Nombre de Usuario</th>
                            <th>Puesto / Rol</th>
                            <th>Status</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT u.id_user, u.nombre_user, u.status_user, p.descripcion_puesto 
                                FROM usuarios u 
                                INNER JOIN puesto p ON u.id_puesto = p.id_puesto";
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id_user']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre_user']) . "</td>";
                                echo "<td><span class='badge bg-secondary'>" . htmlspecialchars($row['descripcion_puesto']) . "</span></td>";
                                echo "<td><span class='badge bg-success'>" . htmlspecialchars($row['status_user']) . "</span></td>";
                                echo "<td class='text-center'>
                                        <a href='#' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='#' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted py-4'>No hay usuarios registrados en el sistema.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
include("../navegacion/footer.php"); 
?>

