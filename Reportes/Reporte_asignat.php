<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
$conn = new mysqli("localhost", "root", "", "sae_egf");
$conn->set_charset("utf8mb4");

// ---------- Funciones ----------
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
// -------------------------------

$mensaje = "";
$avisos = [];
$alumnos_ok    = alumnos_activos($conn, $avisos);
$materias_ok   = filas_alta($conn, 'materias', $avisos);
$profesores_ok = filas_alta($conn, 'profesor', $avisos);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_mat      = (int) $_POST["id_mat"];
    $id_alumno   = (int) $_POST["id_alumno"];
    $id_profesor = (int) $_POST["id_profesor"];

    if (!in_array($id_alumno, ids($alumnos_ok))
        || !in_array($id_mat, ids($materias_ok))
        || !in_array($id_profesor, ids($profesores_ok))) {
        $mensaje = '<div class="alert alert-danger">El alumno, la materia y el profesor deben estar en estatus ALTA.</div>';
    } else {
        $chk = $conn->prepare("SELECT Id_asigmat FROM asignat WHERE Id_mat = ? AND Id_alumno = ?");
        $chk->bind_param("ii", $id_mat, $id_alumno);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows > 0) {
            $mensaje = '<div class="alert alert-warning">Ese alumno ya tiene asignada esa materia.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO asignat (Id_mat, Id_alumno, Id_profesor) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $id_mat, $id_alumno, $id_profesor);
            $stmt->execute();
            $mensaje = '<div class="alert alert-success">Materia asignada correctamente.</div>';
        }
    }
}

// Datos para el panel del alumno
$info_alumnos = [];
foreach ($alumnos_ok as $a) { $info_alumnos[reset($a)] = $a; }

$etiq_mat = [];   // solo materias en ALTA
foreach ($materias_ok as $m) {
    $f = array_values($m);
    $etiq_mat[(int) $f[0]] = isset($f[1]) ? $f[1] : $f[0];
}

$materias_alumno = [];
$res = $conn->query("SELECT Id_alumno, Id_mat FROM asignat ORDER BY Id_asigmat");
while ($f = $res->fetch_assoc()) {
    $m = (int) $f['Id_mat'];
    if (isset($etiq_mat[$m])) {
        $materias_alumno[(int) $f['Id_alumno']][] = $etiq_mat[$m];
    }
}
?>

<div class="container my-4" style="max-width: 600px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Asignación de Materias</h4>
        </div>
        <div class="card-body">
            <?= html_avisos($avisos) ?>
            <?= $mensaje ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Alumno</label>
                    <select name="id_alumno" id="sel-alumno" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_filas($alumnos_ok, 3) ?>
                    </select>
                </div>

                <!-- Panel con los datos del alumno y sus materias -->
                <div id="panel-alumno" class="card border-primary mb-3 d-none">
                    <div class="card-header bg-light fw-bold">Datos del alumno</div>
                    <div class="card-body">
                        <div id="datos-alumno" class="row"></div>
                        <hr>
                        <div class="fw-bold mb-1">Materias en las que está inscrito:</div>
                        <ul id="materias-alumno" class="mb-0"></ul>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Materia</label>
                    <select name="id_mat" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_filas($materias_ok, 2) ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profesor</label>
                    <select name="id_profesor" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?= opciones_filas($profesores_ok, 3) ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Asignar materia</button>
            </form>
        </div>
    </div>
</div>

<script>
const ALUMNOS  = <?= json_encode($info_alumnos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
const MATERIAS = <?= json_encode($materias_alumno, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
const ETIQ = {
    matricula: 'Matrícula', nombre_alum: 'Nombre', A_paterno_alum: 'Apellido paterno',
    A_materno_alum: 'Apellido materno', domicilio: 'Domicilio', mail_alum: 'Correo', telefono: 'Teléfono'
};

function mostrarAlumno(id) {
    const panel = document.getElementById('panel-alumno');
    const datos = document.getElementById('datos-alumno');
    const lista = document.getElementById('materias-alumno');
    datos.innerHTML = '';
    lista.innerHTML = '';

    if (!id || !ALUMNOS[id]) { panel.classList.add('d-none'); return; }

    Object.entries(ALUMNOS[id]).forEach(([campo, valor]) => {
        const col = document.createElement('div');
        col.className = 'col-md-6 mb-2';
        const b = document.createElement('strong');
        b.textContent = (ETIQ[campo] || campo) + ': ';
        col.appendChild(b);
        col.appendChild(document.createTextNode(valor === null ? '' : valor));
        datos.appendChild(col);
    });

    const mats = MATERIAS[id] || [];
    if (mats.length === 0) {
        const li = document.createElement('li');
        li.className = 'text-muted';
        li.textContent = 'Sin materias asignadas todavía.';
        lista.appendChild(li);
    } else {
        mats.forEach(m => {
            const li = document.createElement('li');
            li.textContent = m;
            lista.appendChild(li);
        });
    }
    panel.classList.remove('d-none');
}

const selAlumno = document.getElementById('sel-alumno');
selAlumno.addEventListener('change', () => mostrarAlumno(selAlumno.value));
mostrarAlumno(selAlumno.value);
</script>

<?php include ("../navegacion/footer.php"); ?>