<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$resultado = mysqli_query($conn, "SELECT * FROM alumnos");
?>

<div class="container my-4">
    <h2 class="text-center mb-4 text-secondary">Catálogo de alumnos</h2>

    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] == 'agregado'): ?>
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                Se agregó alumno exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'modificado'): ?>
            <div class="alert alert-primary alert-dismissible fade show text-center" role="alert">
                Se modificó alumno exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] == 'eliminado'): ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                Se eliminó alumno exitosamente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Matrícula</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido paterno</th>
                    <th scope="col">Apellido materno</th>
                    <th scope="col">Domicilio</th>
                    <th scope="col">Estatus</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($salumno = mysqli_fetch_assoc($resultado)) { 
                ?>
                    <tr>
                        <th scope="row"><?php echo $salumno["id_alumno"]; ?></th>
                        <td><?php echo $salumno["matricula"]; ?></td>
                        <td><?php echo $salumno["nombre_alum"]; ?></td>
                        <td><?php echo $salumno["A_parterno_alum"]; ?></td>
                        <td><?php echo $salumno["A_materno_alum"]; ?></td>
                        <td><?php echo $salumno["domicilio"]; ?></td>
                        <td><span class="badge bg-secondary"><?php echo isset($salumno["estatus"]) ? $salumno["estatus"] : 'ALTA'; ?></span></td>
                        
                        <td>
                            <div class="btn-group" role="group">
                                <a href="../Registro/Alumno.php" class="btn btn-dark btn-sm" title="Registrar alumno">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <a href="../modificaciones/Modif_alumnos.php?idalumn=<?php echo $salumno['id_alumno']; ?>" class="btn btn-primary btn-sm" title="Modificar alumno">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="../Baja/Baja_alumno.php?idalumn=<?php echo $salumno['id_alumno']; ?>" class="btn btn-dark btn-sm" title="Dar de baja alumno">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No hay alumnos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>