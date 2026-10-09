<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$resultado = mysqli_query($conn, "SELECT * FROM materias");
?>

<div class="container my-4">
    <h2 class="text-center mb-4 text-secondary">Catálogo de materias</h2>

    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'agregado'): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                Se agregó materia exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'modificado'): ?>
            <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                Se modificó materia exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'eliminado'): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                Se eliminó materia exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col"># ID</th>
                    <th scope="col">Nombre de la Materia</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) { 
                ?>
                    <tr>
                        <th scope="row"><?php echo $row["id_materia"]; ?></th>
                        <td><?php echo $row["descripcion_materia"]; ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="../Registro/Materia.php" class="btn btn-dark btn-sm" title="Registrar materia">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <a href="../modificaciones/Modif_materias.php?idmateria=<?php echo $row['id_materia']; ?>" class="btn btn-primary btn-sm" title="Modificar materia">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="../Baja/Baja_materia.php?idmateria=<?php echo $row['id_materia']; ?>" class="btn btn-dark btn-sm" title="Dar de baja materia">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No hay materias registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>