<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idalumn = isset($_GET['idalumn']) ? intval($_GET['idalumn']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_alumno = intval($_POST['id_alumno']);
    $matricula = $_POST['matricula'];
    $nombre_alum = $_POST['nombre_alum'];
    $A_parterno_alum = $_POST['A_parterno_alum'];
    $A_materno_alum = $_POST['A_materno_alum'];
    $domicilio = $_POST['domicilio'];
    $mail_alum = $_POST['mail_alum'];
    $telefono = $_POST['telefono'];

    $query = "UPDATE alumnos SET 
              matricula='$matricula', 
              nombre_alum='$nombre_alum', 
              A_parterno_alum='$A_parterno_alum', 
              A_materno_alum='$A_materno_alum', 
              domicilio='$domicilio', 
              mail_alum='$mail_alum', 
              telefono='$telefono' 
              WHERE id_alumno=$id_alumno";

    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudealumnos.php?msg=modificado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM alumnos WHERE id_alumno = $idalumn");
$alumno = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 600px;">
    <h3 class="text-center mb-4 text-secondary">Modificar Alumno</h3>

    <?php if ($alumno): ?>
    <form action="Modif_alumnos.php?idalumn=<?php echo $idalumn; ?>" method="POST" class="card card-body shadow-sm">
        <input type="hidden" name="id_alumno" value="<?php echo $alumno['id_alumno']; ?>">

        <div class="mb-3">
            <label class="form-label">No.</label>
            <input type="text" class="form-control" value="<?php echo $alumno['id_alumno']; ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" class="form-control" name="matricula" value="<?php echo $alumno['matricula']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="nombre_alum" value="<?php echo $alumno['nombre_alum']; ?>" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" name="A_parterno_alum" value="<?php echo $alumno['A_parterno_alum']; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Apellido materno</label>
                <input type="text" class="form-control" name="A_materno_alum" value="<?php echo $alumno['A_materno_alum']; ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Domicilio</label>
            <input type="text" class="form-control" name="domicilio" value="<?php echo $alumno['domicilio']; ?>" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" name="mail_alum" value="<?php echo $alumno['mail_alum']; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" value="<?php echo $alumno['telefono']; ?>" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
            <a href="../Crude/crudealumnos.php" class="btn btn-secondary">Regresar</a>
        </div>
    </form>
    <?php else: ?>
        <p class="text-center text-danger">Alumno no encontrado.</p>
    <?php endif; ?>
</div>

<?php
include ("../navegacion/footer.php");
?>