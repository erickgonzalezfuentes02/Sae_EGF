<?php
session_start();

// 1. Validar sesión activa
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

// 2. Restricción estricta de Administrador
if ($_SESSION['puesto'] != 'Administrador') {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow p-4 text-center'><strong>Acceso denegado:</strong> Esta sección es exclusiva para el Administrador.</div></div>";
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
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Alumno</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Matrícula</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta directa a tu tabla de alumnos
                        $sql = "SELECT * FROM alumnos"; 
                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0) {
                            while ($row = mysqli_fetch_assoc($resultado)) {
                                // Mapeo exacto basado en la estructura que tienes en phpMyAdmin
                                $id = $row['id_alumno'] ?? '';
                                $nombre = $row['nombre_alum'] ?? '';
                                $ap_paterno = $row['A_paterno_alum'] ?? '';
                                $ap_materno = $row['A_materno_alum'] ?? '';
                                $matricula = $row['matricula'] ?? '';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($id) . "</td>";
                                echo "<td>" . htmlspecialchars($nombre) . "</td>";
                                echo "<td>" . htmlspecialchars($ap_paterno) . "</td>";
                                echo "<td>" . htmlspecialchars($ap_materno) . "</td>";
                                echo "<td>" . htmlspecialchars($matricula) . "</td>";
                                echo "<td class='text-center'>
                                        <a href='../modificaciones/Modif_alumnos.php?idalumn=" . htmlspecialchars($id) . "' class='btn btn-sm btn-warning text-white me-1' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                        <a href='../Baja/Baja_alumno.php?idalumn=" . htmlspecialchars($id) . "' class='btn btn-sm btn-danger' title='Eliminar'><i class='bi bi-trash'></i></a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted py-4'>No hay registros de alumnos guardados en el sistema.</td></tr>";
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
