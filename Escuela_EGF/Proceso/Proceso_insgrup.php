<?php
session_start();
if (!isset($_SESSION['id_user'])) { header("Location: ../login.php"); exit(); }
if (($_SESSION['puesto'] ?? '') !== 'Administrador') { http_response_code(403); exit('Solo el administrador puede inscribir alumnos.'); }
require_once("../conexion.php");
require_once("../navegacion/header.php");
require_once("../navegacion/navegacion.php");
$mensaje = "";

if (isset($_POST['inscribir'])) {
    $id_grupo = (int)$_POST['id_grupo'];
    $id_alumno = (int)$_POST['id_alumno'];

    $check = $conn->prepare("SELECT * FROM insgrup WHERE id_grupo = ? AND id_alumno = ?");
    $check->bind_param("ii", $id_grupo, $id_alumno);
    $check->execute();
    $res_check = $check->get_result();

    if ($res_check->num_rows > 0) {
        $mensaje = "<div class='alert alert-warning'>El alumno ya está inscrito en este grupo.</div>";
    } else {
        $ins = $conn->prepare("INSERT INTO insgrup (id_grupo, id_alumno) VALUES (?, ?)");
        $ins->bind_param("ii", $id_grupo, $id_alumno);
        if ($ins->execute()) {
            $mensaje = "<div class='alert alert-success'>¡Alumno inscrito al grupo correctamente!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al inscribir: " . htmlspecialchars($conn->error) . "</div>";
        }
    }
}
?>
<div class="container my-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Inscripción de Alumnos a Grupos</h3>
        </div>
        <div class="card-body">
            <?= $mensaje ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Selecciona el Grupo</label>
                    <select name="id_grupo" class="form-control" required>
                        <option value="">Selecciona un grupo...</option>
                        <?php
                        $res_g = $conn->query("SELECT id_grupo, descripcion_grupo FROM grupo");
                        while ($g = $res_g->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($g['id_grupo']) . "'>"
                                . htmlspecialchars($g['descripcion_grupo']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Selecciona el Alumno</label>
                    <select name="id_alumno" class="form-control" required>
                        <option value="">Selecciona un alumno...</option>
                        <?php
                        $res_a = $conn->query("SELECT id_alumno, matricula, nombre_alum, A_paterno_alum, A_materno_alum FROM alumnos");
                        while ($a = $res_a->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($a['id_alumno']) . "'>"
                                . htmlspecialchars($a['matricula']) . " - "
                                . htmlspecialchars($a['nombre_alum']) . " "
                                . htmlspecialchars($a['A_paterno_alum']) . " "
                                . htmlspecialchars($a['A_materno_alum']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="inscribir" class="btn btn-primary">Inscribir alumno</button>
            </form>
        </div>
    </div>
</div>
<?php include("../navegacion/footer.php"); ?>
