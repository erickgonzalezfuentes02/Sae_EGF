<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$idgrupo = isset($_GET['idgrupo']) ? intval($_GET['idgrupo']) : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = "DELETE FROM grupo WHERE id_grupo = $idgrupo";
    if (mysqli_query($conn, $query)) {
        header("Location: ../Crude/crudegrupos.php?msg=eliminado");
        exit();
    }
}

$res = mysqli_query($conn, "SELECT * FROM grupo WHERE id_grupo = $idgrupo");
$grupo = mysqli_fetch_assoc($res);
?>

<div class="container my-5" style="max-width: 500px;">
    <div class="card border-danger shadow-sm">
        <div class="card-header bg-danger text-white text-center">
            <h4 class="mb-0">Confirmar Baja de Grupo</h4>
        </div>
        <div class="card-body">
            <?php if ($grupo): ?>
                <p class="text-center text-muted fw-bold">¿Estás seguro de que deseas dar de baja el siguiente grupo?</p>

                <ul class="list-group list-group-flush mb-4">
                    <li class="list-group-item"><strong>ID Grupo:</strong> <?php echo htmlspecialchars($grupo['id_grupo']); ?></li>
                    <li class="list-group-item"><strong>Descripción:</strong> <?php echo htmlspecialchars($grupo['descripcion_grupo']); ?></li>
                </ul>

                <form action="" method="POST" class="d-flex justify-content-between">
                    <a href="../Crude/crudegrupos.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-danger">Confirmar Baja</button>
                </form>
            <?php else: ?>
                <p class="text-center text-danger">Grupo no encontrado.</p>
                <div class="text-center">
                    <a href="../Crude/crudegrupos.php" class="btn btn-secondary">Regresar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>