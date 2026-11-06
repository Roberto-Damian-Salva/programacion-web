<?php
require_once '../config/conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = 'usuario'; // Rol por defecto

    if (!empty($nombre) && !empty($email) && !empty($password)) {
        // 1. Verificar si el correo ya existe en la BD
        $stmtCheck = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmtCheck->execute([':email' => $email]);
        
        if ($stmtCheck->rowCount() > 0) {
            $mensaje = "El correo electrónico ya está registrado.";
        } else {
            // 2. Encriptación segura con password_hash y BCRYPT
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // 3. Inserción con consultas preparadas
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre' => htmlspecialchars($nombre),
                ':email' => htmlspecialchars($email),
                ':password' => $passwordHash,
                ':rol' => $rol
            ]);

            header("Location: login.php?status=registered");
            exit;
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 card p-4 bg-dark text-light">
            <h2 class="mb-4">Registro de Usuario</h2>
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
            <form action="registro.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrarse</button>
            </form>
            <div class="mt-3 text-center">
                <a href="login.php" class="text-info">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </div>
    </div>
</body>
</html>