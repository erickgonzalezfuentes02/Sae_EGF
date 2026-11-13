<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idmateria = 0;
if (isset($_GET['idmateria'])) {
    $idmateria = intval($_GET['idmateria']);
} elseif (isset($_GET['id'])) {
    $idmateria = intval($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = intval($_POST['id_materia']);
    $descripcion_materia = $_POST['descripcion_materia'];

    $query = "UPDATE materias SET 
              descripcion_materia='$descripcion_materia',
              Nombre_materia='$descripcion_materia'
              WHERE Id_mat=$id_materia OR id_materia=$id_materia";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudemateria.php?msg=modificado");
        exit();
    } else {
        echo "Error al modificar: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM materias WHERE Id_mat = $idmateria OR id_materia = $idmateria");
$materia = $res ? mysqli_fetch_assoc($res) : null;
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Materia</h3>
    <?php if ($materia): 
        $pk = isset($materia['Id_mat']) ? $materia['Id_mat'] : $materia['id_materia'];
        $desc = isset($materia['descripcion_materia']) ? $materia['descripcion_materia'] : ($materia['Nombre_materia'] ?? '');
    ?>
    <form action="Modif_materias.php?idmateria=<?php echo $idmateria; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_materia" value="<?php echo $pk; ?>">
        <div class="mb-3">
            <label class="form-label">ID Materia</label>
            <input type="text" class="form-control" value="<?php echo $pk; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre / Descripción de la Materia</label>
            <input type="text" class="form-control" name="descripcion_materia" value="<?php echo htmlspecialchars($desc); ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudemateria.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-danger text-center">Materia no encontrada.</div>
        <div class="text-center"><a href="../Crude/crudemateria.php" class="btn btn-secondary">Regresar al catálogo</a></div>
    <?php endif; ?>
</div>

<?php include ("../navegacion/footer.php"); ?>