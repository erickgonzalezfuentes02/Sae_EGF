<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$resultado = mysqli_query($conn, "SELECT * FROM profesor");
?>

<div class="container my-4">
    <h2 class="text-center mb-4 text-secondary">Catálogo de profesores</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col"># ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido Paterno</th>
                    <th scope="col">Apellido Materno</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) { 
                ?>
                    <tr>
                        <th scope="row"><?php echo $row["id_profesor"]; ?></th>
                        <td><?php echo $row["nombre_profesor"]; ?></td>
                        <td><?php echo $row["A_paterno_profesor"]; ?></td>
                        <td><?php echo $row["A_materno_profesor"]; ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="../Registro/Profesores.php" class="btn btn-dark btn-sm" title="Registrar profesor">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <a href="../modificaciones/Modif_profesores.php?idprofesor=<?php echo $row['id_profesor']; ?>" class="btn btn-primary btn-sm" title="Modificar profesor">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="../Baja/Baja_profesores.php?idprofesor=<?php echo $row['id_profesor']; ?>" class="btn btn-dark btn-sm" title="Dar de baja profesor">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No hay profesores registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>