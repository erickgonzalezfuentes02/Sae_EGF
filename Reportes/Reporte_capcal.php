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
$cal_guardada = "";
$mapa = mapa_estatus($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST["modo"] === "individual") {
        $id_alumno = (int) $_POST["id_alumno"];
        $id_unidad = (int) $_POST["id_unidad"];
        $cal       = (float) $_POST["cal"];

        if (esta_de_baja($mapa, $id_alumno)) {
            $mensaje = '<div class="alert alert-danger">El alumno está en estatus BAJA y no se le pueden capturar calificaciones.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO capcal (Id_alumno, Cal, Id_Unidad) VALUES (?, ?, ?)");
            $stmt->bind_param("idi", $id_alumno, $cal, $id_unidad);
            $stmt->execute();

            // Lee lo guardado para imprimirlo en la segunda caja
            $id  = $conn->insert_id;
            $res = $conn->query("SELECT Cal FROM capcal WHERE Id_capcalf = $id");
            $cal_guardada = $res->fetch_assoc()["Cal"];
            $mensaje = '<div class="alert alert-success">Calificación guardada.</div>';
        }
    }

    if ($_POST["modo"] === "masiva") {
        $id_unidad = (int) $_POST["id_unidad"];
        $stmt = $conn->prepare("INSERT INTO capcal (Id_alumno, Cal, Id_Unidad) VALUES (?, ?, ?)");
        $n = 0;
        $omitidos = 0;

        foreach ($_POST["cal"] as $id_alumno => $valor) {
            if ($valor === "") continue;                 // sin calificación: se omite
            $id_a = (int) $id_alumno;
            if (esta_de_baja($mapa, $id_a)) { $omitidos++; continue; }
            $c = (float) $valor;
            $stmt->bind_param("idi", $id_a, $c, $id_unidad);
            $stmt->execute();
            $n++;
        }
        $mensaje = '<div class="alert alert-success">Se guardaron ' . $n . ' calificaciones.</div>';
        if ($omitidos > 0) {
            $mensaje .= '<div class="alert alert-warning">Se omitieron ' . $omitidos . ' alumnos en estatus BAJA.</div>';
        }
    }
}

$lista_alumnos = $conn->query("SELECT * FROM alumnos")->fetch_all(MYSQLI_NUM);
$unidades_html = opciones($conn->query("SELECT * FROM unidad"), 2);
?>

<div class="container my-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Captura de Calificaciones</h4>
        </div>
        <div class="card-body">
            <?= $mensaje ?>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#individual" type="button">Individual</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#masiva" type="button">Masiva</button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- INDIVIDUAL -->
                <div class="tab-pane fade show active" id="individual">
                    <form method="POST">
                        <input type="hidden" name="modo" value="individual">
                        <div class="mb-3">
                            <label class="form-label">Alumno</label>
                            <select name="id_alumno" class="form-select" required>
                                <?= opciones_alumnos($lista_alumnos, $mapa, 3) ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Unidad</label>
                            <select name="id_unidad" class="form-select" required>
                                <?= $unidades_html ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ingresar calificación</label>
                                <input type="number" name="cal" class="form-control" min="0" max="100" step="0.01" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Calificación registrada</label>
                                <input type="text" class="form-control" value="<?= $cal_guardada ?>" readonly>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar calificación</button>
                    </form>
                </div>

                <!-- MASIVA -->
                <div class="tab-pane fade" id="masiva">
                    <form method="POST">
                        <input type="hidden" name="modo" value="masiva">
                        <div class="mb-3">
                            <label class="form-label">Unidad</label>
                            <select name="id_unidad" class="form-select" required>
                                <?= $unidades_html ?>
                            </select>
                        </div>
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr><th>Alumno</th><th style="width:150px;">Calificación</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lista_alumnos as $a) {
                                    $baja = esta_de_baja($mapa, $a[0]); ?>
                                    <tr class="<?= $baja ? 'table-secondary' : '' ?>">
                                        <td>
                                            <?= htmlspecialchars(implode(" - ", array_slice($a, 0, 3))) ?>
                                            <?= $baja ? '<span class="badge bg-danger">BAJA</span>' : '' ?>
                                        </td>
                                        <td>
                                            <input type="number" name="cal[<?= (int) $a[0] ?>]" class="form-control"
                                                   min="0" max="100" step="0.01" <?= $baja ? 'disabled' : '' ?>>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary w-100">Guardar todas</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>