<?php
require_once '../config/conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        // Buscar el usuario por email
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        // Validar contraseña con password_verify
        if ($usuario && password_verify($password, $usuario['password'])) {
            // Requisitos estrictos de seguridad de sesión
            session_start();
            session_regenerate_id(true); // Evita fijación de sesión

            // Almacenar datos clave en la sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['rol'] = $usuario['rol'];

            // Redirigir al panel protegido
            header("Location: admin_panel.php");
            exit;
        } else {
            $mensaje = "Credenciales incorrectas.";
        }
    } else {
        $mensaje = "Por favor completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 card p-4 bg-dark text-light">
            <h2 class="mb-4">Iniciar Sesión</h2>
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Ingresar</button>
            </form>
            <div class="mt-3 text-center">
                <a href="registro.php" class="text-info">¿No tienes una cuenta? Regístrate</a>
            </div>
        </div>
    </div>
</body>
</html>