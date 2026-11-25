<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede acceder a esta sección.'); }


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
            <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i> Reporte General de Alumnos</h4>
            <a href="../Registro/Alumno.php" class="btn btn-sm btn-primary fw-semibold"><i class="bi bi-plus-lg me-1"></i> Nuevo Alumno</a>
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
                        <?php
                        $sql = "SELECT id_alumno, matricula, nombre_alum, A_paterno_alum, A_materno_alum, domicilio, mail_alum, telefono, estatus FROM alumnos";
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                $id = $row['id_alumno'];
                                $estatus = $row['estatus'] ?? 'ALTA';
                                $badge = ($estatus === 'BAJA') ? 'bg-danger' : 'bg-success';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($id) . "</td>";
                                echo "<td>" . htmlspecialchars($row['matricula'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['nombre_alum'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['A_paterno_alum'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['A_materno_alum'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['domicilio'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['mail_alum'] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row['telefono'] ?? '') . "</td>";
                                echo "<td><span class='badge " . $badge . "'>" . htmlspecialchars($estatus) . "</span></td>";
                                echo "<td>
                                        <a href='../modificaciones/Modif_alumnos.php?id=" . htmlspecialchars($id) . "' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='../Baja/Baja_alumno.php?id=" . htmlspecialchars($id) . "' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10' class='text-center text-muted py-4'>No hay registros de alumnos guardados en el sistema.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("../navegacion/footer.php"); ?>
