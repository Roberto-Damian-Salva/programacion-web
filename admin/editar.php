<?php
require_once '../config/conexion.php';

$mensaje = "";
$registro = null;

// 1. Obtener el registro a editar mediante el ID recibido por la URL (GET)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Consulta preparada para buscar el registro de forma segura
    $sqlSelect = "SELECT * FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sqlSelect);
    $stmt->execute([':id' => $id]);
    $registro = $stmt->fetch();

    // Si el registro no existe, redirigir al listado principal
    if (!$registro) {
        header('Location: index.php?status=not_found');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}

// 2. Procesar la actualización cuando se envía el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;

    if (!empty($titulo) && !empty($id)) {
        try {
            // Consulta Preparada para UPDATE (Anti SQL Injection)
            $sqlUpdate = "UPDATE productos SET titulo = :titulo, descripcion = :descripcion, precio = :precio WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            
            $stmtUpdate->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':id' => $id
            ]);

            // Redireccionar al listado principal tras actualizar con éxito
            header('Location: index.php?status=updated');
            exit;

        } catch (\PDOException $e) {
            $mensaje = "Error al actualizar el registro: " . $e->getMessage();
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
    <title>Editar Registro - MovieHub</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2 class="mb-4">Editar Elemento</h2>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <!-- Formulario de Actualización (Update) -->
    <form action="editar.php?id=<?= $registro['id'] ?>" method="POST" class="card p-4 bg-dark text-light">
        <!-- Campo oculto para asegurar que el ID viaje correctamente al hacer POST -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id']) ?>">

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($registro['titulo']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($registro['descripcion']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio / Valor</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="<?= htmlspecialchars($registro['precio']) ?>">
        </div>
        <button type="submit" class="btn btn-warning">Actualizar Registro</button>
        <a href="index.php" class="btn btn-secondary mt-2">Volver al Listado</a>
    </form>
</body>
</html>