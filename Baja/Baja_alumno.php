<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idalumn = isset($_GET['idalumn']) ? intval($_GET['idalumn']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "UPDATE alumnos SET estatus = 'BAJA' WHERE id_alumno = $idalumn";
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudealumnos.php?msg=eliminado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM alumnos WHERE id_alumno = $idalumn");
$alumno = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card border-warning shadow-sm">
        <div class="card-header bg-warning text-dark text-center">
            <h4 class="mb-0">Dar de Baja Alumno</h4>
        </div>
        <div class="card-body">
            <?php if ($alumno): ?>
                <p class="text-center text-muted fw-bold">¿Estás seguro de que deseas cambiar el estatus a BAJA para el siguiente alumno?</p>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?></li>
                    <li class="list-group-item"><strong>Nombre:</strong> <?php echo htmlspecialchars($alumno['nombre_alum'] . ' ' . $alumno['A_paterno_alum'] . ' ' . $alumno['A_materno_alum']); ?></li>
                </ul>

                <form action="" method="POST" class="d-flex justify-content-between">
                    <a href="../Crude/crudealumnos.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Confirmar Baja</button>
                </form>
            <?php else: ?>
                <p class="text-center text-danger">Alumno no encontrado.</p>
                <div class="text-center">
                    <a href="../Crude/crudealumnos.php" class="btn btn-secondary">Regresar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>