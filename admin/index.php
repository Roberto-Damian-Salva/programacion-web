<?php
// 1. Requerir la conexión segura PDO (subiendo un nivel de carpeta)
require_once '../config/conexion.php';

// 2. Consulta segura para obtener los registros de la base de datos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
$registros = $stmt->fetchAll();

// 3. Incluir el encabezado modular del sitio
include_once '../includes/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Administración del Sitio Web</h2>
        <a href="crear.php" class="btn btn-primary">Agregar Nuevo Registro</a>
    </div>

    <!-- Tabla de listado de registros -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($registros)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay registros cargados actualmente.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($registros as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id']) ?></td>
                        <td><?= htmlspecialchars($item['titulo']) ?></td>
                        <td><?= htmlspecialchars($item['descripcion']) ?></td>
                        <td>$<?= htmlspecialchars($item['precio']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="eliminar.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Confirma eliminar este registro?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// 4. Incluir el pie de página modular del sitio
include_once '../includes/footer.php';
?>