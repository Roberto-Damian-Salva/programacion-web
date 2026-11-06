<?php
require_once __DIR__ . '/../app/Config/conexion.php';
require_once __DIR__ . '/../app/Controllers/ProductoController.php';

// Instanciar el controlador pasándole la conexión PDO
$controller = new ProductoController($pdo);
$controller->index();
?>