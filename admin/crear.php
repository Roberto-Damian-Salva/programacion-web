<?php
// 1. Requerir la conexión segura PDO
require_once '../config/conexion.php';

$mensaje = "";

// 2. Verificar si el formulario fue enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;

    // Validación básica en servidor
    if (!empty($titulo)) {
        try {
            // 3. Consulta Preparada obligatoria (Anti SQL Injection)
            $sql = "INSERT INTO productos (titulo, descripcion, precio) VALUES (:titulo, :descripcion, :precio)";
            $stmt = $pdo->prepare($sql);
            
            // 4. Ejecución pasando los parámetros de forma segura
            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':precio' => $precio
            ]);

            // Redireccionar al listado principal con éxito
            header('Location: index.php?status=created');
            exit;

        } catch (\PDOException $e) {
            $mensaje = "Error al guardar el registro: " . $e->getMessage();
        }
    } else {
        $mensaje = "El título es obligatorio.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Nuevo Registro - MovieHub</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2 class="mb-4">Agregar Nuevo Elemento</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-danger"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Formulario de Inserción (Create) -->
    <form action="crear.php" method="POST" class="card p-4 bg-dark text-light">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio / Valor</label>
            <input type="number" step="0.01" name="precio" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar Registro</button>
        <a href="index.php" class="btn btn-secondary mt-2">Volver al Listado</a>
    </form>
</body>
</html>