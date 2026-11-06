<?php
$titulo_pagina = "MovieHub - Inicio";
require_once 'includes/header.php';
?>

<!-- Aquí va el contenido principal de tu página de inicio (lo que tenías en el <main>) -->
<main class="container my-5">
    <section class="text-center py-5">
        <h1 class="display-4 fw-bold text-white">Bienvenido a MovieHub +</h1>
        <p class="lead text-secondary">Explora nuestra cartelera, descubre los estrenos y comparte tu pasión por el cine.</p>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>

<div class="container my-4">
    <h3>Búsqueda de Productos en Tiempo Real</h3>
    <!-- Barra de búsqueda -->
    <div class="mb-3">
        <input type="text" id="inputBusqueda" class="form-control" placeholder="Escribe para buscar un producto...">
    </div>

    <!-- Contenedor dinámico donde se cargarán los resultados -->
    <div id="resultados">
        <!-- Los datos aparecerán aquí asincrónicamente -->
    </div>
</div>

<!-- Enlazar el script de JavaScript al final -->
<script src="js/app.js"></script>