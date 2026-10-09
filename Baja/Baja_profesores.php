<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idprofesor = isset($_GET['idprofesor']) ? intval($_GET['idprofesor']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM profesor WHERE id_profesor = $idprofesor";
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudeprofesores.php?msg=eliminado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM profesor WHERE id_profesor = $idprofesor");
$profesor = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h4 class="mb-0">Confirmar Baja de Profesor</h4>
        </div>
        <div class="card-body">
            <?php if ($profesor): ?>
                <p class="text-center text-muted fw-bold">¿Estás seguro de que deseas dar de baja al siguiente profesor?</p>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>ID Profesor:</strong> <?php echo htmlspecialchars($profesor['id_profesor']); ?></li>
                    <li class="list-group-item"><strong>Nombre:</strong> <?php echo htmlspecialchars($profesor['nombre_profesor'] . ' ' . $profesor['A_paterno_profesor'] . ' ' . $profesor['A_materno_profesor']); ?></li>
                </ul>

                <form action="" method="POST" class="d-flex justify-content-between">
                    <a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Confirmar Baja</button>
                </form>
            <?php else: ?>
                <p class="text-center text-danger">Profesor no encontrado.</p>
                <div class="text-center">
                    <a href="../Crude/crudeprofesores.php" class="btn btn-secondary">Regresar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>