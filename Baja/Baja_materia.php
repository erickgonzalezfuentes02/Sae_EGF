<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idmateria = isset($_GET['idmateria']) ? intval($_GET['idmateria']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM materias WHERE id_materia = $idmateria";
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudemateria.php?msg=eliminado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM materias WHERE id_materia = $idmateria");
$materia = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h4 class="mb-0">Confirmar Baja de Materia</h4>
        </div>
        <div class="card-body">
            <?php if ($materia): ?>
                <p class="text-center text-muted fw-bold">¿Estás seguro de que deseas dar de baja la siguiente materia?</p>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>ID Materia:</strong> <?php echo htmlspecialchars($materia['id_materia']); ?></li>
                    <li class="list-group-item"><strong>Nombre Materia:</strong> <?php echo htmlspecialchars($materia['descripcion_materia']); ?></li>
                </ul>

                <form action="" method="POST" class="d-flex justify-content-between">
                    <a href="../Crude/crudemateria.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Confirmar Baja</button>
                </form>
            <?php else: ?>
                <p class="text-center text-danger">Materia no encontrada.</p>
                <div class="text-center">
                    <a href="../Crude/crudemateria.php" class="btn btn-secondary">Regresar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>