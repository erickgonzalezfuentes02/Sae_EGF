<?php
include ("../navegacion/header.php");
include ("../navegacion/navegacion.php");
include ("../conexion.php");

$grupo_seleccionado = $_POST['id_grupo'] ?? null;
$mensaje = "";

// 1. Guardar o Actualizar Captura Individual (Evita duplicados)
if (isset($_POST['guardar_individual'])) {
    $id_alumno = $_POST['id_alumno'];
    $id_unidad = $_POST['id_unidad'];
    $calificacion = $_POST['calificacion'];

    // Verificamos si ya existe un registro previo para este alumno y materia
    $sql_check = "SELECT * FROM capcal WHERE id_alumno = ? AND Id_Unidad = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $id_alumno, $id_unidad);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        // Si ya existe, actualizamos la calificación existente en lugar de duplicar
        $stmt_update = $conn->prepare("UPDATE capcal SET Cal = ? WHERE id_alumno = ? AND Id_Unidad = ?");
        $stmt_update->bind_param("dii", $calificacion, $id_alumno, $id_unidad);
        if ($stmt_update->execute()) {
            $mensaje = "<div class='alert alert-warning'>¡El alumno ya tenía una calificación registrada! Se ha actualizado correctamente sin duplicarse.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
        }
    } else {
        // Si no existe, insertamos el nuevo registro limpio
        $stmt_insert = $conn->prepare("INSERT INTO capcal (id_alumno, Id_Unidad, Cal) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iid", $id_alumno, $id_unidad, $calificacion);
        if ($stmt_insert->execute()) {
            $mensaje = "<div class='alert alert-success'>¡Calificación individual guardada con éxito!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al guardar: " . $conn->error . "</div>";
        }
    }
}

// 2. Guardar o Actualizar Captura Masiva (Evita duplicados por cada alumno del grupo)
if (isset($_POST['guardar_masiva'])) {
    $id_grupo = $_POST['id_grupo_hidden'];
    $id_unidad = $_POST['id_unidad_masiva'];
    $calificaciones = $_POST['calificaciones'] ?? [];

    $exito = true;
    foreach ($calificaciones as $id_alumno => $calif) {
        if ($calif !== "") {
            // Verificamos si ya existe registro para cada alumno en esta unidad
            $sql_check = "SELECT * FROM capcal WHERE id_alumno = ? AND Id_Unidad = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ii", $id_alumno, $id_unidad);
            $stmt_check->execute();
            $res_check = $stmt_check->get_result();

            if ($res_check->num_rows > 0) {
                // Actualiza si ya existe
                $stmt_up = $conn->prepare("UPDATE capcal SET Cal = ? WHERE id_alumno = ? AND Id_Unidad = ?");
                $stmt_up->bind_param("dii", $calif, $id_alumno, $id_unidad);
                if (!$stmt_up->execute()) { $exito = false; }
            } else {
                // Inserta si es nuevo
                $stmt_in = $conn->prepare("INSERT INTO capcal (id_alumno, Id_Unidad, Cal) VALUES (?, ?, ?)");
                $stmt_in->bind_param("iid", $id_alumno, $id_unidad, $calif);
                if (!$stmt_in->execute()) { $exito = false; }
            }
        }
    }

    if ($exito) {
        $mensaje = "<div class='alert alert-success'>¡Calificaciones masivas registradas/actualizadas sin duplicados con éxito!</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Hubo un error al registrar algunas calificaciones.</div>";
    }
}
?>

<div class="container my-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Captura de Calificaciones</h3>
        </div>
        <div class="card-body">
            
            <?= $mensaje ?>

            <ul class="nav nav-tabs mb-4" id="capCalTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual" type="button" role="tab">Captura Individual</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="masiva-tab" data-bs-toggle="tab" data-bs-target="#masiva" type="button" role="tab">Captura Masiva (Por Grupo)</button>
                </li>
            </ul>

            <div class="tab-content" id="capCalTabsContent">
                
                <!-- SECCIÓN 1: INDIVIDUAL -->
                <div class="tab-pane fade" id="individual" role="tabpanel">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Selecciona el Alumno</label>
                            <select name="id_alumno" class="form-control" required>
                                <option value="">Selecciona un alumno...</option>
                                <?php 
                                $res_a = $conn->query("SELECT * FROM alumnos");
                                while($a = $res_a->fetch_assoc()) {
                                    echo "<option value='".$a['id_alumno']."'>".$a['matricula']." - ".$a['nombre_alum']." ".$a['A_paterno_alum']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Materia / Unidad</label>
                            <select name="id_unidad" class="form-control" required>
                                <option value="">Selecciona una materia...</option>
                                <?php 
                                $res_m = $conn->query("SELECT * FROM materias");
                                while($m = $res_m->fetch_assoc()) {
                                    $id_mat_val = $m['id_materia'] ?? $m['id_mat'];
                                    echo "<option value='".$id_mat_val."'>".$m['descripcion_materia']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Calificación</label>
                            <input type="number" step="0.01" name="calificacion" class="form-control" placeholder="Ej. 90 o 10.0" required>
                        </div>
                        <button type="submit" name="guardar_individual" class="btn btn-primary">Guardar Calificación Individual</button>
                    </form>
                </div>

                <!-- SECCIÓN 2: MASIVA -->
                <div class="tab-pane fade" id="masiva" role="tabpanel">
                    <form action="" method="POST">
                        <input type="hidden" name="active_tab" value="masiva">
                        <div class="row align-items-end mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Selecciona el Grupo</label>
                                <select name="id_grupo" class="form-control" required onchange="this.form.submit()">
                                    <option value="">Selecciona un grupo para cargar alumnos...</option>
                                    <?php 
                                    $res_g = $conn->query("SELECT * FROM grupo");
                                    while($g = $res_g->fetch_assoc()) {
                                        $selected = ($grupo_seleccionado == $g['id_grupo']) ? 'selected' : '';
                                        echo "<option value='".$g['id_grupo']."' $selected>".$g['descripcion_grupo']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </form>

                    <?php if ($grupo_seleccionado): ?>
                        <hr>
                        <form action="" method="POST">
                            <input type="hidden" name="id_grupo_hidden" value="<?= $grupo_seleccionado ?>">
                            <input type="hidden" name="active_tab" value="masiva">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Materia / Unidad para este grupo:</label>
                                <select name="id_unidad_masiva" class="form-control" required>
                                    <option value="">Selecciona la materia que se calificará...</option>
                                    <?php 
                                    $res_m2 = $conn->query("SELECT * FROM materias");
                                    while($m2 = $res_m2->fetch_assoc()) {
                                        $id_mat_val2 = $m2['id_materia'] ?? $m2['id_mat'];
                                        echo "<option value='".$id_mat_val2."'>".$m2['descripcion_materia']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <h5 class="text-secondary mb-3">Alumnos pertenecientes al grupo:</h5>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-center align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Matrícula</th>
                                            <th>Alumno</th>
                                            <th>Calificación a Registrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql_alumnos = "SELECT a.id_alumno, a.matricula, a.nombre_alum, a.A_paterno_alum 
                                                        FROM insgrup ig 
                                                        INNER JOIN alumnos a ON ig.id_alumno = a.id_alumno 
                                                        WHERE ig.id_grupo = ?";
                                        $stmt_alumnos = $conn->prepare($sql_alumnos);
                                        $stmt_alumnos->bind_param("i", $grupo_seleccionado);
                                        $stmt_alumnos->execute();
                                        $resultado_alumnos = $stmt_alumnos->get_result();

                                        if ($resultado_alumnos && $resultado_alumnos->num_rows > 0) {
                                            while($alum = $resultado_alumnos->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($alum['matricula']) ?></td>
                                                    <td><?= htmlspecialchars($alum['A_paterno_alum'] . ' ' . $alum['nombre_alum']) ?></td>
                                                    <td>
                                                        <input type="number" step="0.01" name="calificaciones[<?= $alum['id_alumno'] ?>]" class="form-control text-center calif-input" placeholder="0.00" oninput="calcularSuma()">
                                                    </td>
                                                </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='3' class='text-muted'>No hay alumnos inscritos en este grupo.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($resultado_alumnos && $resultado_alumnos->num_rows > 0): ?>
                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Suma total de calificaciones ingresadas:</span>
                                    <span id="sumaTotal" class="fs-4 fw-bold text-primary">0.00</span>
                                </div>
                                <button type="submit" name="guardar_masiva" class="btn btn-success btn-lg w-100">Registrar Calificaciones Masivas</button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function calcularSuma() {
    let inputs = document.querySelectorAll('.calif-input');
    let suma = 0;
    inputs.forEach(input => {
        let valor = parseFloat(input.value);
        if (!isNaN(valor)) {
            suma += valor;
        }
    });
    document.getElementById('sumaTotal').innerText = suma.toFixed(2);
}

// Control automático de pestañas
document.addEventListener("DOMContentLoaded", function() {
    <?php if ($grupo_seleccionado): ?>
        var masivaTab = new bootstrap.Tab(document.querySelector('#masiva-tab'));
        masivaTab.show();
    <?php else: ?>
        var individualTab = new bootstrap.Tab(document.querySelector('#individual-tab'));
        individualTab.show();
    <?php endif; ?>
});
</script>

<?php include ("../navegacion/footer.php"); ?>