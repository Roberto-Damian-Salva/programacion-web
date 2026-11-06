<?php
require_once __DIR__ . '/../Models/ProductoModel.php';

class ProductoController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ProductoModel($pdo);
    }

    public function index() {
        // Obtener datos del modelo
        $productos = $this->model->obtenerTodos();
        
        // Cargar la vista pasándole los datos
        require_once __DIR__ . '/../Views/productos/index.php';
    }
}
?>