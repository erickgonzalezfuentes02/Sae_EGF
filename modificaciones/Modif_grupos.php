<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idgrupo = isset($_GET['idgrupo']) ? intval($_GET['idgrupo']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_grupo = intval($_POST['id_grupo']);
    $descripcion_grupo = $_POST['descripcion_grupo'];

    $query = "UPDATE grupo SET 
              descripcion_grupo='$descripcion_grupo' 
              WHERE id_grupo=$id_grupo";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudegrupos.php?msg=modificado");
        exit();
    } else {
        echo "Error al modificar: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM grupo WHERE id_grupo = $idgrupo");
$grupo = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Grupo</h3>

    <?php if ($grupo): ?>
    <form action="Modif_grupos.php?idgrupo=<?php echo $idgrupo; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_grupo" value="<?php echo $grupo['id_grupo']; ?>">

        <div class="mb-3">
            <label class="form-label">ID Grupo</label>
            <input type="text" class="form-control" value="<?php echo $grupo['id_grupo']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción del Grupo</label>
            <input type="text" class="form-control" name="descripcion_grupo" value="<?php echo $grupo['descripcion_grupo']; ?>" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudegrupos.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-center text-danger">Grupo no encontrado.</p>
    <?php endif; ?>
</div>

<?php
include ("../navegacion/footer.php");
?>