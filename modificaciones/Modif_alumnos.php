<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idalumn = 0;
if (isset($_GET['idalumn'])) {
    $idalumn = intval($_GET['idalumn']);
} elseif (isset($_GET['id'])) {
    $idalumn = intval($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_alumno = intval($_POST['id_alumno']);
    $matricula = $_POST['matricula'];
    $nombre_alum = $_POST['nombre_alum'];
    $A_paterno_alum = $_POST['A_paterno_alum'];
    $A_materno_alum = $_POST['A_materno_alum'];
    $domicilio = $_POST['domicilio'];
    $mail_alum = $_POST['mail_alum'];
    $telefono = $_POST['telefono'];

    $query = "UPDATE alumnos SET 
              matricula='$matricula', 
              nombre_alum='$nombre_alum', 
              A_paterno_alum='$A_paterno_alum', 
              A_materno_alum='$A_materno_alum', 
              domicilio='$domicilio', 
              mail_alum='$mail_alum', 
              telefono='$telefono' 
              WHERE Id_alumno=$id_alumno OR id_alumno=$id_alumno";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudealumnos.php?msg=modificado");
        exit();
    } else {
        echo "Error al modificar: " . mysqli_error($conn);
    }
}

$res = mysqli_query($conn, "SELECT * FROM alumnos WHERE Id_alumno = $idalumn OR id_alumno = $idalumn");
$alumno = $res ? mysqli_fetch_assoc($res) : null;
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Alumno</h3>
    <?php if ($alumno): 
        $pk = isset($alumno['Id_alumno']) ? $alumno['Id_alumno'] : $alumno['id_alumno'];
    ?>
    <form action="Modif_alumnos.php?idalumn=<?php echo $idalumn; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_alumno" value="<?php echo $pk; ?>">
        <div class="mb-3">
            <label class="form-label"># ID</label>
            <input type="text" class="form-control" value="<?php echo $pk; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" class="form-control" name="matricula" value="<?php echo htmlspecialchars($alumno['matricula']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre_alum" value="<?php echo htmlspecialchars($alumno['nombre_alum']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Paterno</label>
            <input type="text" class="form-control" name="A_paterno_alum" value="<?php echo htmlspecialchars($alumno['A_paterno_alum'] ?? $alumno['a_paterno_alum'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellido Materno</label>
            <input type="text" class="form-control" name="A_materno_alum" value="<?php echo htmlspecialchars($alumno['A_materno_alum'] ?? $alumno['a_materno_alum'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Domicilio</label>
            <input type="text" class="form-control" name="domicilio" value="<?php echo htmlspecialchars($alumno['domicilio']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" name="mail_alum" value="<?php echo htmlspecialchars($alumno['mail_alum']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($alumno['telefono']); ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudealumnos.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-danger text-center">Alumno no encontrado.</div>
        <div class="text-center"><a href="../Crude/crudealumnos.php" class="btn btn-secondary">Regresar al catálogo</a></div>
    <?php endif; ?>
</div>

<?php include ("../navegacion/footer.php"); ?>