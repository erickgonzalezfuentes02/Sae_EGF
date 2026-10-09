<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idmateria = isset($_GET['idmateria']) ? intval($_GET['idmateria']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_materia = intval($_POST['id_materia']);
    $descripcion_materia = $_POST['descripcion_materia'];

    $query = "UPDATE materias SET 
              descripcion_materia='$descripcion_materia' 
              WHERE id_materia=$id_materia";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudemateria.php?msg=modificado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM materias WHERE id_materia = $idmateria");
$materia = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Materia</h3>

    <?php if ($materia): ?>
    <form action="Modif_materias.php?idmateria=<?php echo $idmateria; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_materia" value="<?php echo $materia['id_materia']; ?>">

        <div class="mb-3">
            <label class="form-label">ID Materia</label>
            <input type="text" class="form-control" value="<?php echo $materia['id_materia']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre / Descripción de la Materia</label>
            <input type="text" class="form-control" name="descripcion_materia" value="<?php echo $materia['descripcion_materia']; ?>" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudemateria.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-center text-danger">Materia no encontrada.</p>
    <?php endif; ?>
</div>

<?php
include ("../navegacion/footer.php");
?>