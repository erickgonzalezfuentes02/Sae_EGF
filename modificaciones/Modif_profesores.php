<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idprofesor = isset($_GET['idprofesor']) ? intval($_GET['idprofesor']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_profesor = intval($_POST['id_profesor']);
    $nombre_profesor = $_POST['nombre_profesor'];
    $A_paterno_profesor = $_POST['A_paterno_profesor'];
    $A_materno_profesor = $_POST['A_materno_profesor'];

    $query = "UPDATE profesor SET 
              nombre_profesor='$nombre_profesor', 
              A_paterno_profesor='$A_paterno_profesor', 
              A_materno_profesor='$A_materno_profesor' 
              WHERE id_profesor=$id_profesor";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudeprofesores.php?msg=modificado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM profesor WHERE id_profesor = $idprofesor");
$profesor = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Profesor</h3>

    <?php if ($profesor): ?>
    <form action="Modif_profesores.php?idprofesor=<?php echo $idprofesor; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_profesor" value="<?php echo $profesor['id_profesor']; ?>">

        <div class="mb-3">
            <label class="form-label">ID Profesor</label>
            <input type="text" class="form-control" value="<?php echo $profesor['id_profesor']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre_profesor" value="<?php echo $profesor['nombre_profesor']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" name="A_paterno_profesor" value="<?php echo $profesor['A_paterno_profesor']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" name="A_materno_profesor" value="<?php echo $profesor['A_materno_profesor']; ?>" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-center text-danger">Profesor no encontrado.</p>
    <?php endif; ?>
</div>

<?php
include ("../navegacion/footer.php");
?>