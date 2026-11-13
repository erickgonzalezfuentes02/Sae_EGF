<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idgrupo = 0;
if (isset($_GET['idgrupo'])) {
    $idgrupo = intval($_GET['idgrupo']);
} elseif (isset($_GET['id'])) {
    $idgrupo = intval($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $descripcion_grupo = $_POST['descripcion_grupo'];

    $query = "UPDATE grupo SET 
              descripcion_grupo='$descripcion_grupo',
              Nombre_grupo='$descripcion_grupo' 
              WHERE Id_grupo=$id_grupo OR id_grupo=$id_grupo";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudegrupos.php?msg=modificado");
        exit();
    } else {
        echo "Error al modificar: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM grupo WHERE Id_grupo = $idgrupo OR id_grupo = $idgrupo");
$grupo = $res ? mysqli_fetch_assoc($res) : null;
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Grupo</h3>
    <?php if ($grupo): 
        $pk = isset($grupo['Id_grupo']) ? $grupo['Id_grupo'] : $grupo['id_grupo'];
        $desc = isset($grupo['descripcion_grupo']) ? $grupo['descripcion_grupo'] : ($grupo['Nombre_grupo'] ?? '');
    ?>
    <form action="Modif_grupos.php?idgrupo=<?php echo $idgrupo; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_grupo" value="<?php echo $pk; ?>">
        <div class="mb-3">
            <label class="form-label">ID Grupo</label>
            <input type="text" class="form-control" value="<?php echo $pk; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción / Nombre del Grupo</label>
            <input type="text" class="form-control" name="descripcion_grupo" value="<?php echo htmlspecialchars($desc); ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudegrupos.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-danger text-center">Grupo no encontrado.</div>
        <div class="text-center"><a href="../Crude/crudegrupos.php" class="btn btn-secondary">Regresar al catálogo</a></div>
    <?php endif; ?>
</div>

<?php include ("../navegacion/footer.php"); ?>