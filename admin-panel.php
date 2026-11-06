<?php
// Incluir el guardián de rutas (protege el acceso)
require_once 'auth_guard.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container py-5">
    <div class="card p-4 bg-light text-dark">
        <h1>Bienvenido al Panel, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h1>
        <p>Tu rol actual es: <strong><?= htmlspecialchars($_SESSION['rol']) ?></strong></p>
        <hr>
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>
</body>
</html>