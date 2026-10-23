<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
$conn = new mysqli("localhost", "root", "", "sae_egf");
$conn->set_charset("utf8mb4");

function opciones_filas($filas, $n = 3) {
    $out = "";
    foreach ($filas as $f) {
        $f = array_values($f);
        $out .= '<option value="' . htmlspecialchars($f[0]) . '">'
              . htmlspecialchars(implode(" - ", array_slice($f, 0, $n))) . '</option>';
    }
    return $out;
}

function ids($filas) {
    return array_map(function ($f) { return (int) reset($f); }, $filas);
}

function col_estatus($conn, $tabla) {
    $res = $conn->query("SHOW COLUMNS FROM `$tabla`");
    while ($c = $res->fetch_assoc()) {
        if (preg_match('/estatus|status/i', $c['Field'])) return $c['Field'];
    }
    return null;
}

function filas_alta($conn, $tabla, &$avisos) {
    $col = col_estatus($conn, $tabla);
    if ($col === null) {
        $avisos[$tabla] = "La tabla $tabla no tiene columna de estatus, por eso se muestran todos sus registros.";
        return $conn->query("SELECT * FROM `$tabla`")->fetch_all(MYSQLI_ASSOC);
    }
    return $conn->query("SELECT * FROM `$tabla` WHERE UPPER(TRIM(`$col`)) = 'ALTA'")->fetch_all(MYSQLI_ASSOC);
}

function mapa_estatus($conn) {
    $mapa = [];
    $res = $conn->query("SELECT Id_alumno, MAX(UPPER(TRIM(Estatus_ins)) = 'BAJA') AS baja
                         FROM insgrup GROUP BY Id_alumno");
    while ($f = $res->fetch_assoc()) {
        $mapa[(int) $f['Id_alumno']] = $f['baja'] ? 'BAJA' : 'ALTA';
    }
    return $mapa;
}

function esta_de_baja($mapa, $id_alumno) {
    return isset($mapa[(int) $id_alumno]) && $mapa[(int) $id_alumno] === 'BAJA';
}

function alumnos_activos($conn, &$avisos) {
    $mapa = mapa_estatus($conn);
    return array_values(array_filter(filas_alta($conn, 'alumnos', $avisos), function ($a) use ($mapa) {
        return !esta_de_baja($mapa, reset($a));
    }));
}

function html_avisos($avisos) {
    $out = "";
    foreach ($avisos as $a) {
        $out .= '<div class="alert alert-warning">' . htmlspecialchars($a) . '</div>';
    }
    return $out;
}

$mensaje = "";
$avisos = [];
$alumnos_ok = alumnos_activos($conn, $avisos);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_grupo  = (int) $_POST["id_grupo"];
    $id_alumno = (int) $_POST["id_alumno"];
    $estatus   = "ALTA";

    if (!in_array($id_alumno, ids($alumnos_ok))) {
        $mensaje = '<div class="alert alert-danger">El alumno no está en estatus ALTA y no se puede inscribir.</div>';
    } else {
        $chk = $conn->prepare("SELECT Id_in, Estatus_ins FROM insgrup WHERE Id_grupo = ? AND Id_alumno = ?");
        $chk->bind_param("ii", $id_grupo, $id_alumno);
        $chk->execute();
        $existe = $chk->get_result()->fetch_assoc();

        if ($existe) {
            if (strtoupper(trim($existe["Estatus_ins"])) === $estatus) {
                $mensaje = '<div class="alert alert-warning">El alumno ya tiene estatus ' . $estatus . ' en ese grupo.</div>';
            } else {
                $upd = $conn->prepare("UPDATE insgrup SET Estatus_ins = ? WHERE Id_in = ?");
                $upd->bind_param("si", $estatus, $existe["Id_in"]);
                $upd->execute();
                $mensaje = '<div class="alert alert-success">Estatus actualizado a ' . $estatus . '.</div>';
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO insgrup (Id_grupo, Id_alumno, Estatus_ins) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_grupo, $id_alumno, $estatus);
            $stmt->execute();
            $mensaje = '<div class="alert alert-success">Inscripción registrada con estatus ' . $estatus . '.</div>';
        }
        $alumnos_ok = alumnos_activos($conn, $avisos);   
    }
}

$grupos = $conn->query("SELECT * FROM grupo")->fetch_all(MYSQLI_ASSOC);
?>

<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Inscripción a Grupos</h4>
        </div>
        <div class="card-body">
            <?= html_avisos($avisos) ?>
            <?= $mensaje ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Grupo</label>
                    <select name="id_grupo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_filas($grupos, 2) ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alumno</label>
                    <select name="id_alumno" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_filas($alumnos_ok, 3) ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Estatus</label>
                    <input type="text" name="estatus" class="form-control" value="ALTA" readonly>
                </div>
                <button type="submit" class="btn btn-primary w-100">Guardar</button>
            </form>
        </div>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>