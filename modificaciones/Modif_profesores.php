<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idprofesor = 0;
if (isset($_GET['idprofesor'])) {
    $idprofesor = intval($_GET['idprofesor']);
} elseif (isset($_GET['id'])) {
    $idprofesor = intval($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_profesor = intval($_POST['id_profesor']);
    $nombre_profesor = $_POST['nombre_profesor'];
    $A_paterno_profesor = $_POST['A_paterno_profesor'];
    $A_materno_profesor = $_POST['A_materno_profesor'];

    $query = "UPDATE profesor SET 
              nombre_profesor='$nombre_profesor', 
              A_paterno_profesor='$A_paterno_profesor', 
              A_materno_profesor='$A_materno_profesor' 
              WHERE Id_profesor=$id_profesor OR id_profesor=$id_profesor";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudeprofesores.php?msg=modificado");
        exit();
    } else {
        echo "Error al modificar: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM profesor WHERE Id_profesor = $idprofesor OR id_profesor = $idprofesor");
$profesor = $res ? mysqli_fetch_assoc($res) : null;
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Profesor</h3>
    <?php if ($profesor): 
        $pk = isset($profesor['Id_profesor']) ? $profesor['Id_profesor'] : $profesor['id_profesor'];
    ?>
    <form action="Modif_profesores.php?idprofesor=<?php echo $idprofesor; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_profesor" value="<?php echo $pk; ?>">
        <div class="mb-3">
            <label class="form-label">ID Profesor</label>
            <input type="text" class="form-control" value="<?php echo $pk; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre_profesor" value="<?php echo htmlspecialchars($profesor['nombre_profesor'] ?? $profesor['Nombre_profesor'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" name="A_paterno_profesor" value="<?php echo htmlspecialchars($profesor['A_paterno_profesor'] ?? $profesor['a_paterno_profesor'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" name="A_materno_profesor" value="<?php echo htmlspecialchars($profesor['A_materno_profesor'] ?? $profesor['a_materno_profesor'] ?? ''); ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-danger text-center">Profesor no encontrado.</div>
        <div class="text-center"><a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Regresar al catálogo</a></div>
    <?php endif; ?>
</div>

<?php include ("../navegacion/footer.php"); ?>