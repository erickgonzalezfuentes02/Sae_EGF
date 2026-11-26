<?php
session_start();

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

$mostrarFormulario = isset($_GET['nuevo']) && $_GET['nuevo'] === '1';

function escaparProfesor($valor)
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<?php if ($mostrarFormulario): ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-person-badge-fill me-2"></i>
                            Registro de profesores
                        </h4>
                        <a href="Profesores.php" class="btn btn-sm btn-light">
                            Volver al catálogo
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="../Proceso/Procesar_profesores.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Número de control</label>
                            <input type="text" name="N_control_profesor"
                                   class="form-control" required maxlength="15">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre_profesor"
                                   class="form-control" required maxlength="15">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido paterno</label>
                            <input type="text" name="A_paterno_profesor"
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Apellido materno</label>
                            <input type="text" name="A_materno_profesor"
                                   class="form-control" required maxlength="15">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="mail_profesor"
                                   class="form-control" required maxlength="50">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Domicilio</label>
                            <input type="text" name="Domicilio_profesor"
                                   class="form-control" maxlength="80">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono_profesor"
                                   class="form-control" maxlength="35">
                        </div>

                        <input type="hidden" name="accion" value="insertar">

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                Guardar profesor
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php else: ?>

<div class="container mt-4 mb-5">
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-person-badge-fill me-2"></i>
                Catálogo de Profesores
            </h4>

            <a href="Profesores.php?nuevo=1"
               class="btn btn-sm btn-primary fw-semibold">
                <i class="bi bi-plus-lg me-1"></i>
                Nuevo Profesor
            </a>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>No. Control</th>
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
                        $sql = "SELECT
                                    id_profesor,
                                    N_control_profesor,
                                    nombre_profesor,
                                    A_paterno_profesor,
                                    A_materno_profesor,
                                    Domicilio_profesor,
                                    mail_profesor,
                                    telefono_profesor,
                                    estatus_profesor
                                FROM profesor
                                ORDER BY A_paterno_profesor,
                                         A_materno_profesor,
                                         nombre_profesor";

                        $resultado = mysqli_query($conn, $sql);

                        if ($resultado && mysqli_num_rows($resultado) > 0):
                            while ($row = mysqli_fetch_assoc($resultado)):
                                $id = (int)$row['id_profesor'];
                                $estatus = strtoupper(trim($row['estatus_profesor'] ?? 'ALTA'));
                                $badge = ($estatus === 'BAJA') ? 'bg-danger' : 'bg-success';
                        ?>
                            <tr>
                                <td><?= $id ?></td>
                                <td><?= escaparProfesor($row['N_control_profesor']) ?></td>
                                <td><?= escaparProfesor($row['nombre_profesor']) ?></td>
                                <td><?= escaparProfesor($row['A_paterno_profesor']) ?></td>
                                <td><?= escaparProfesor($row['A_materno_profesor']) ?></td>
                                <td><?= escaparProfesor($row['Domicilio_profesor']) ?></td>
                                <td><?= escaparProfesor($row['mail_profesor']) ?></td>
                                <td><?= escaparProfesor($row['telefono_profesor']) ?></td>

                                <td>
                                    <span class="badge <?= $badge ?>">
                                        <?= escaparProfesor($estatus) ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="../modificaciones/Modif_profesores.php?idprofesor=<?= $id ?>"
                                       class="btn btn-sm btn-warning text-white me-1"
                                       title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <a href="../Baja/Baja_profesores.php?idprofesor=<?= $id ?>"
                                       class="btn btn-sm btn-danger"
                                       title="Dar de baja">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    No hay profesores registrados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php require_once("../navegacion/footer.php"); ?>