<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$resultado = mysqli_query($conn, "SELECT * FROM grupo");
?>

<div class="container my-4">
    <h2 class="text-center mb-4 text-secondary">Catálogo de grupos</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle text-center shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th scope="col"># ID</th>
                    <th scope="col">Descripción del Grupo</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultado && mysqli_num_rows($resultado) > 0) {
                    while ($row = mysqli_fetch_assoc($resultado)) { 
                        // Validación flexible de campos de clave primaria y descripción
                        $id = isset($row['idgrupo']) ? $row['idgrupo'] : (isset($row['id_grupo']) ? $row['id_grupo'] : reset($row));
                        $descripcion = isset($row['descripcion_grupo']) ? $row['descripcion_grupo'] : (isset($row['descripcion']) ? $row['descripcion'] : '');
                ?>
                    <tr>
                        <th scope="row"><?php echo $id; ?></th>
                        <td><?php echo $descripcion; ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="../Registro/Grupos.php" class="btn btn-dark btn-sm" title="Registrar grupo">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                <a href="../modificaciones/Modif_grupos.php?idgrupo=<?php echo $id; ?>" class="btn btn-primary btn-sm" title="Modificar grupo">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="../Baja/Baja_grupo.php?idgrupo=<?php echo $id; ?>" class="btn btn-dark btn-sm" title="Dar de baja grupo">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No hay grupos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include ("../navegacion/footer.php");
?>