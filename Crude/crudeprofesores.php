<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
$conn = new mysqli("localhost", "root", "", "sae_egf");
$conn->set_charset("utf8mb4");

$TABLA        = "profesor";
$TITULO       = "Catálogo de profesores";
$TXT_NUEVO    = "+ Nuevo Profesor";
$URL_NUEVO    = "../Registro/Profesores.php";
$URL_EDITAR   = "../modificaciones/Modif_profesores.php";
$PARAM_EDITAR = "id";
$OCULTAR      = [];
$ETIQUETAS    = [];

function etiqueta($f, $pk, $est, $mapa) {
    if ($f === $pk)  return '#';
    if ($f === $est) return 'Estatus';
    $k = strtolower($f);
    return isset($mapa[$k]) ? $mapa[$k] : ucfirst(str_replace('_', ' ', $f));
}

$col_pk = null;
$col_est = null;
$cols = $conn->query("SHOW COLUMNS FROM `$TABLA`")->fetch_all(MYSQLI_ASSOC);
foreach ($cols as $c) {
    if ($c['Key'] === 'PRI' && $col_pk === null) $col_pk = $c['Field'];
    if ($col_est === null && preg_match('/estatus|status/i', $c['Field'])) $col_est = $c['Field'];
}

$mensaje = "";
if ($col_pk === null || $col_est === null) {
    $mensaje = '<div class="alert alert-danger">La tabla ' . htmlspecialchars($TABLA)
             . ' necesita una clave primaria y una columna de estatus para poder dar de baja.</div>';
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["baja"]) && $col_pk && $col_est) {
    $id = (int) $_POST["baja"];
    $stmt = $conn->prepare("UPDATE `$TABLA` SET `$col_est` = 'BAJA' WHERE `$col_pk` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $mensaje = '<div class="alert alert-success">El registro se dio de baja correctamente.</div>';
}

$ocultar_min = array_map('strtolower', $OCULTAR);
$mostrar = [];
foreach ($cols as $c) {
    if (!in_array(strtolower($c['Field']), $ocultar_min)) $mostrar[] = $c['Field'];
}

$sql = "SELECT * FROM `$TABLA`" . ($col_pk ? " ORDER BY `$col_pk`" : "");
$filas = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><?= $TITULO ?></h2>
        <a href="<?= $URL_NUEVO ?>" class="btn btn-primary"><?= $TXT_NUEVO ?></a>
    </div>
    <?= $mensaje ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <?php foreach ($mostrar as $f) { ?>
                        <th><?= htmlspecialchars(etiqueta($f, $col_pk, $col_est, $ETIQUETAS)) ?></th>
                    <?php } ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($filas)) { ?>
                    <tr><td colspan="<?= count($mostrar) + 1 ?>" class="text-muted">No hay registros.</td></tr>
                <?php } ?>
                <?php foreach ($filas as $fila) {
                    $id   = $col_pk ? $fila[$col_pk] : '';
                    $baja = $col_est && strtoupper(trim($fila[$col_est])) === 'BAJA'; ?>
                    <tr>
                        <?php foreach ($mostrar as $f) { ?>
                            <td>
                                <?php if ($f === $col_est) {
                                    $v = strtoupper(trim($fila[$f])); ?>
                                    <span class="badge <?= $v === 'ALTA' ? 'bg-success' : 'bg-danger' ?>"><?= htmlspecialchars($v) ?></span>
                                <?php } else { ?>
                                    <?= htmlspecialchars((string) $fila[$f]) ?>
                                <?php } ?>
                            </td>
                        <?php } ?>
                        <td class="text-nowrap">
                            <a href="<?= $URL_EDITAR . '?' . $PARAM_EDITAR . '=' . urlencode($id) ?>"
                               class="btn btn-sm btn-outline-primary">Editar</a>
                            <?php if ($baja) { ?>
                                <button class="btn btn-sm btn-outline-secondary" disabled>Dar de baja</button>
                            <?php } else { ?>
                                <form method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Dar de baja este registro?');">
                                    <input type="hidden" name="baja" value="<?= htmlspecialchars($id) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Dar de baja</button>
                                </form>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ("../navegacion/footer.php"); ?>