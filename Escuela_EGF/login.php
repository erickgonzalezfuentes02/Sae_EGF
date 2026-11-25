<?php
session_start();
require_once("conexion.php");
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre_user'] ?? '');
    $password = sha1($_POST['pw_user'] ?? '');
    $sql = "SELECT u.id_user, u.nombre_user, u.id_puesto, u.id_alumno, u.id_profesor, p.descripcion_puesto
            FROM usuarios u INNER JOIN puesto p ON u.id_puesto = p.id_puesto
            WHERE u.nombre_user = ? AND u.pw_user = ? AND u.status_user = 'Alta'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nombre, $password);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        session_regenerate_id(true);
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['nombre_user'] = $row['nombre_user'];
        $_SESSION['id_puesto'] = $row['id_puesto'];
        $_SESSION['puesto'] = $row['descripcion_puesto'];
        $_SESSION['id_alumno'] = $row['id_alumno'];
        $_SESSION['id_profesor'] = $row['id_profesor'];
        if ($row['descripcion_puesto'] === 'Alumno') {
            header("Location: " . BASE_URL . "Reportes/Reporte_mi_alumno.php");
        } elseif ($row['descripcion_puesto'] === 'Docente') {
            header("Location: " . BASE_URL . "Proceso/Proceso_capcal.php");
        } else {
            header("Location: " . BASE_URL . "Reportes/Reporte_alumnos.php");
        }
        exit();
    }
    $error = "Usuario o contraseña incorrectos.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Escuela EGF</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<!-- Fondo oscuro negro/azul marino institucional -->
<body class="bg-dark d-flex justify-content-center align-items-center vh-100 m-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white">
                    
                    <!-- Cabecera oscura estilizada -->
                    <div class="card-header bg-black text-white text-center py-4 border-0">
                        <div class="mb-2">
                            <i class="bi bi-shield-lock-fill text-primary fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Sistema Escolar</h4>
                        <p class="text-white-50 small mb-0">Escuela_EGF</p>
                    </div>

                    <div class="card-body p-4 p-sm-4">
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label text-muted fw-semibold small">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="nombre_user" class="form-control bg-light border-start-0 ps-0" placeholder="Ingresa tu usuario" required autocomplete="off">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fw-semibold small">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                    <input type="password" name="pw_user" class="form-control bg-light border-start-0 ps-0" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold shadow-sm">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Entrar al Sistema
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer bg-light text-center py-3 border-0 text-muted small">
                        <span>&copy; 2026 Escuela EGF</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


