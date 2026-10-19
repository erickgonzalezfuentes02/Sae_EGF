<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
$conn = new mysqli("localhost", "root", "", "sae_egf");
$conn->set_charset("utf8mb4");

// ---------- Funciones ----------
function opciones($res, $n = 3) {
    $out = "";
    while ($f = $res->fetch_row()) {
        $out .= '<option value="' . htmlspecialchars($f[0]) . '">'
              . htmlspecialchars(implode(" - ", array_slice($f, 0, $n))) . '</option>';
    }
    return $out;
}

function mapa_estatus($conn) {
    $mapa = [];
    $res = $conn->query("SELECT Id_alumno, Estatus_ins FROM insgrup ORDER BY Id_in ASC");
    while ($f = $res->fetch_assoc()) {
        $mapa[(int) $f['Id_alumno']] = strtoupper(trim($f['Estatus_ins']));
    }
    return $mapa;
}

function esta_de_baja($mapa, $id_alumno) {
    return isset($mapa[(int) $id_alumno]) && $mapa[(int) $id_alumno] === 'BAJA';
}

function opciones_alumnos($filas, $mapa, $n = 3) {
    $out = "";
    foreach ($filas as $f) {
        $baja  = esta_de_baja($mapa, $f[0]);
        $texto = implode(" - ", array_slice($f, 0, $n)) . ($baja ? " (BAJA)" : "");
        $out  .= '<option value="' . htmlspecialchars($f[0]) . '"' . ($baja ? ' disabled' : '') . '>'
               . htmlspecialchars($texto) . '</option>';
    }
    return $out;
}
// -------------------------------

$mensaje = "";
$mapa = mapa_estatus($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_grupo  = (int) $_POST["id_grupo"];
    $id_alumno = (int) $_POST["id_alumno"];
    $estatus   = "ALTA";

    if (esta_de_baja($mapa, $id_alumno)) {
        $mensaje = '<div class="alert alert-danger">El alumno está en estatus BAJA y no puede inscribirse con estatus ALTA.</div>';
    } else {
        $chk = $conn->prepare("SELECT Id_in FROM insgrup WHERE Id_grupo = ? AND Id_alumno = ?");
        $chk->bind_param("ii", $id_grupo, $id_alumno);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows > 0) {
            $mensaje = '<div class="alert alert-warning">El alumno ya está inscrito en ese grupo.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO insgrup (Id_grupo, Id_alumno, Estatus_ins) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_grupo, $id_alumno, $estatus);
            $stmt->execute();
            $mensaje = '<div class="alert alert-success">Inscripción registrada correctamente.</div>';
        }
    }
}

$grupos  = $conn->query("SELECT * FROM grupo");
$alumnos = $conn->query("SELECT * FROM alumnos")->fetch_all(MYSQLI_NUM);
?>

<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Inscripción a Grupos</h4>
        </div>
        <div class="card-body">
            <?= $mensaje ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Grupo</label>
                    <select name="id_grupo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones($grupos, 2) ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alumno</label>
                    <select name="id_alumno" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_alumnos($alumnos, $mapa, 3) ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Inscribir</button>
            </form>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>