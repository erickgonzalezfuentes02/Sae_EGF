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

// Función robusta: Lee de la base de datos o muestra unidades por defecto si la tabla está vacía
function opciones_unidades($conn) {
    $out = '<option value="">Seleccione una unidad...</option>';
    $res = $conn->query("SELECT * FROM unidad");
    
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $keys = array_keys($row);
            $id = $row[$keys[0]];
            $descripcion = "";
            foreach ($row as $colName => $val) {
                if (stripos($colName, 'desc') !== false) {
                    $descripcion = $val;
                    break;
                }
            }
            if ($descripcion === "" && count($keys) > 1) {
                $descripcion = $row[$keys[1]];
            }
            $out .= '<option value="' . htmlspecialchars($id) . '">' . htmlspecialchars($descripcion) . '</option>';
        }
    } else {
        // Fallback automático si la tabla 'unidad' está vacía en MySQL
        for ($i = 1; $i <= 5; $i++) {
            $out .= '<option value="' . $i . '">Unidad ' . $i . '</option>';
        }
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
$cal_guardada = "";
$avisos = [];
$alumnos_ok  = alumnos_activos($conn, $avisos);
$materias_ok = filas_alta($conn, 'materias', $avisos);
$ids_ok      = ids($alumnos_ok);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $modo = $_POST["modo"] ?? "";

    if ($modo === "individual") {
        $id_alumno = isset($_POST["id_alumno"]) ? (int) $_POST["id_alumno"] : 0;
        $id_unidad = isset($_POST["id_unidad"]) ? (int) $_POST["id_unidad"] : 0;
        $cal       = isset($_POST["cal"]) ? (float) $_POST["cal"] : 0.0;

        if (!in_array($id_alumno, $ids_ok)) {
            $mensaje = '<div class="alert alert-danger">El alumno no está en estatus ALTA y no se le pueden capturar calificaciones.</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO capcal (Id_alumno, Cal, Id_Unidad) VALUES (?, ?, ?)");
            $stmt->bind_param("idi", $id_alumno, $cal, $id_unidad);
            $stmt->execute();

            $id  = $conn->insert_id;
            $res = $conn->query("SELECT Cal FROM capcal WHERE Id_capcalf = $id");
            if ($res && $row = $res->fetch_assoc()) {
                $cal_guardada = $row["Cal"];
            }
            $mensaje = '<div class="alert alert-success">Calificación guardada correctamente.</div>';
        }
    }

    if ($modo === "masiva") {
        $id_unidad = isset($_POST["id_unidad"]) ? (int) $_POST["id_unidad"] : 0;
        $stmt = $conn->prepare("INSERT INTO capcal (Id_alumno, Cal, Id_Unidad) VALUES (?, ?, ?)");
        $n = 0;
        $omitidos = 0;

        if (isset($_POST["cal"]) && is_array($_POST["cal"])) {
            foreach ($_POST["cal"] as $id_alumno => $valor) {
                if ($valor === "") continue;
                $id_a = (int) $id_alumno;
                if (!in_array($id_a, $ids_ok)) { $omitidos++; continue; }
                $c = (float) $valor;
                $stmt->bind_param("idi", $id_a, $c, $id_unidad);
                $stmt->execute();
                $n++;
            }
        }
        $mensaje = '<div class="alert alert-success">Se guardaron ' . $n . ' calificaciones masivas.</div>';
        if ($omitidos > 0) {
            $mensaje .= '<div class="alert alert-warning">Se omitieron ' . $omitidos . ' alumnos que no están en estatus ALTA.</div>';
        }
    }
}

$info_alumnos = [];
foreach ($alumnos_ok as $a) { $info_alumnos[reset($a)] = $a; }

$etiq_mat = [];
foreach ($materias_ok as $m) {
    $f = array_values($m);
    $etiq_mat[(int) $f[0]] = isset($f[1]) ? $f[1] : $f[0];
}

$materias_alumno = [];
$res = $conn->query("SELECT Id_alumno, Id_mat FROM asignat ORDER BY Id_asigmat");
if ($res) {
    while ($f = $res->fetch_assoc()) {
        $m = (int) $f['Id_mat'];
        if (isset($etiq_mat[$m])) {
            $materias_alumno[(int) $f['Id_alumno']][] = $etiq_mat[$m];
        }
    }
}

$unidades_html = opciones_unidades($conn);
?>

<div class="container my-4" style="max-width: 900px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Captura de Calificaciones</h4>
        </div>
        <div class="card-body">
            <?= html_avisos($avisos) ?>
            <?= $mensaje ?>

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#individual" type="button">Individual</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#masiva" type="button">Masiva por Unidad</button>
                </li>
            </ul>

            <div class="tab-content">

                <!-- INDIVIDUAL -->
                <div class="tab-pane fade show active" id="individual">
                    <form method="POST">
                        <input type="hidden" name="modo" value="individual">
                        <div class="mb-3">
                            <label class="form-label">Alumno</label>
                            <select name="id_alumno" id="sel-alumno" class="form-select" required>
                                <option value="">Seleccione un alumno...</option>
                                <?= opciones_filas($alumnos_ok, 3) ?>
                            </select>
                        </div>

                        <div id="panel-alumno" class="card border-primary mb-3 d-none bg-light">
                            <div class="card-body">
                                <div id="datos-alumno" class="row"></div>
                                <hr>
                                <div class="fw-bold mb-1 text-secondary">Materia y Profesor Asignado:</div>
                                <ul id="materias-alumno" class="mb-0 ps-3"></ul>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Unidad a evaluar</label>
                            <select name="id_unidad" class="form-select" required>
                                <?= $unidades_html ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ingresar calificación (Escala de 1 a 100)</label>
                                <input type="number" name="cal" class="form-control" min="1" max="100" step="0.01" required placeholder="Ej. 85">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Calificación registrada</label>
                                <input type="text" class="form-control bg-white" value="<?= $cal_guardada ?>" readonly placeholder="Se mostrará al guardar">
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
                            <label class="form-label fw-bold">Unidad a evaluar para todos</label>
                            <select name="id_unidad" class="form-select" required>
                                <?= $unidades_html ?>
                            </select>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alumno</th>
                                        <th style="width:200px;">Calificación (1 - 100)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos_ok as $a) { $a = array_values($a); ?>
                                        <tr>
                                            <td><?= htmlspecialchars(implode(" - ", array_slice($a, 0, 3))) ?></td>
                                            <td>
                                                <input type="number" name="cal[<?= (int) $a[0] ?>]" class="form-control"
                                                       min="1" max="100" step="0.01" placeholder="0 - 100">
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar todas las calificaciones</button>
                    </form>
                </div>

            </div>
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
if(selAlumno.value) mostrarAlumno(selAlumno.value);
</script>

<?php include ("../navegacion/footer.php"); ?>