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
    exit('Solo el administrador puede modificar materias.');
}

require_once("../conexion.php");

$idMateria = 0;

if (isset($_GET['idmateria'])) {
    $idMateria = (int)$_GET['idmateria'];
} elseif (isset($_GET['id'])) {
    $idMateria = (int)$_GET['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idMateria = (int)($_POST['id_materia'] ?? 0);
    $descripcion = trim($_POST['descripcion_materia'] ?? '');

    if ($idMateria <= 0 || $descripcion === '') {
        $error = 'Falta el identificador o la descripción de la materia.';
    } else {
        $stmt = $conn->prepare(
            "UPDATE materias SET descripcion_materia = ? WHERE id_materia = ?"
        );

        if (!$stmt) {
            $error = 'No se pudo preparar la actualización: ' . $conn->error;
        } else {
            $stmt->bind_param("si", $descripcion, $idMateria);

            if ($stmt->execute()) {
                $stmt->close();
                header("Location: ../Crude/crudemateria.php?msg=modificado");
                exit();
            } else {
                $error = 'No se pudo modificar la materia: ' . $stmt->error;
                $stmt->close();
            }
        }
    }
}

$materia = null;

if ($idMateria > 0) {
    $stmt = $conn->prepare(
        "SELECT id_materia, descripcion_materia
         FROM materias
         WHERE id_materia = ?"
    );

    if ($stmt) {
        $stmt->bind_param("i", $idMateria);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $materia = $resultado->fetch_assoc();
        $stmt->close();
    }
}

require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");
?>

<div class="container my-5" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Modificar materia</h3>
        </div>

        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($materia): ?>
                <form action="Modif_materias.php?id=<?= (int)$materia['id_materia'] ?>"
                      method="POST">
                    <input type="hidden" name="id_materia"
                           value="<?= (int)$materia['id_materia'] ?>">

                    <div class="mb-3">
                        <label class="form-label">ID de materia</label>
                        <input type="text" class="form-control"
                               value="<?= (int)$materia['id_materia'] ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción de la materia</label>
                        <input type="text" class="form-control"
                               name="descripcion_materia" maxlength="150" required
                               value="<?= htmlspecialchars(
                                   $materia['descripcion_materia'] ?? '',
                                   ENT_QUOTES,
                                   'UTF-8'
                               ) ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a href="../Crude/crudemateria.php" class="btn btn-secondary">
                        Regresar
                    </a>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    No se encontró la materia solicitada.
                </div>
                <a href="../Crude/crudemateria.php" class="btn btn-secondary">
                    Regresar al catálogo
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once("../navegacion/footer.php"); ?>