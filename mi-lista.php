<?php
$titulo_pagina = "MovieHub - Mi Lista";
require_once 'includes/header.php';
?>

    <main class="container my-4 flex-grow-1">
        <div class="mb-4">
            <h1 class="h3 fw-bold">Todas las que quieres ver.</h1>
            <p class="text-secondary small">Tu espacio personal de películas. Guarda todo lo que tengas pensado ver después.</p>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card bg-dark border border-secondary p-3 text-center">
                    <span class="fs-3 fw-bold text-white">42</span>
                    <span class="text-muted small">Películas guardadas</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark border border-secondary p-3 text-center">
                    <span class="fs-3 fw-bold text-white">24</span>
                    <span class="text-muted small">Vistas este año</span>
                </div>
            </div>
        </div>

        <!-- Tabla de la lista -->
        <div class="card bg-dark border border-secondary p-3">
            <h6 class="fw-bold mb-3">Pendientes de ver</h6>
            <ul class="list-group list-group-flush bg-transparent">
                <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center border-secondary py-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-secondary rounded" style="width: 40px; height: 55px;"></div>
                        <div>
                            <h6 class="mb-0">Dune: Parte dos</h6>
                            <small class="text-muted">2024 • Ciencia Ficción</small>
                        </div>
                    </div>
                    <a href="detalle.php" class="btn btn-outline-light btn-sm">Ver detalle</a>
                </li>
            </ul>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>