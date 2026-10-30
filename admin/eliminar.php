<?php
require_once '../config/conexion.php';

// Verificar si se recibió el ID por la URL mediante GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Consulta Preparada obligatoria para DELETE (Anti SQL Injection)
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Ejecutar pasando el parámetro de forma segura
        $stmt->execute([':id' => $id]);

        // Redireccionar al listado principal con un estado de éxito
        header('Location: index.php?status=deleted');
        exit;

    } catch (\PDOException $e) {
        die("Error crítico al eliminar el registro: " . $e->getMessage());
    }
} else {
    // Si no viene el ID, redirigir al listado principal
    header('Location: index.php');
    exit;
}
?>