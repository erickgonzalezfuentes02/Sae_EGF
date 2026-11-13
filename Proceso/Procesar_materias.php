<?php
include ("../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : 'insertar';
    
    $descripcion_materia = isset($_POST['descripcion_materia']) ? $_POST['descripcion_materia'] : 
                         (isset($_POST['nombre_materia']) ? $_POST['nombre_materia'] : 
                         (isset($_POST['materia']) ? $_POST['materia'] : ''));
                         
    $descripcion_materia = mysqli_real_escape_string($conn, $descripcion_materia);

    if ($accion == 'insertar') {
        $query = "INSERT INTO materias (descripcion_materia, estatus_materia) VALUES ('$descripcion_materia', 'ALTA')";
        if (mysqli_query($conn, $query)) {
            header("Location: ../Crude/crudemateria.php?msg=agregado");
            exit();
        } else {
            echo "Error al registrar la materia: " . mysqli_error($conn);
        }
    } elseif ($accion == 'modificar') {
        $id_materia = intval($_POST['id_materia']);
        $query = "UPDATE materias SET descripcion_materia='$descripcion_materia' WHERE id_materia=$id_materia OR Id_mat=$id_materia";
        if (mysqli_query($conn, $query)) {
            header("Location: ../Crude/crudemateria.php?msg=modificado");
            exit();
        } else {
            echo "Error al modificar la materia: " . mysqli_error($conn);
        }
    }
}
?>