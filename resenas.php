<?php
$titulo_pagina = "MovieHub - Reseñas";
require_once 'includes/header.php';
?>

    <main class="container my-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold">Una película. Mil miradas.</h1>
                <p class="text-secondary small">Lo que opinan nuestros usuarios sobre las últimas proyecciones.</p>
            </div>
            <a href="nueva-resena.php" class="btn btn-light fw-bold">Escribir reseña</a>
        </div>

        <!-- Lista de reseñas -->
        <div class="card bg-dark border border-secondary p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-secondary rounded-circle" style="width: 35px; height: 35px;"></div>
                    <div>
                        <h6 class="mb-0 small fw-bold">LucasV12</h6>
                        <small class="text-muted" style="font-size: 0.75rem;">Sobre: Dune: Parte dos</small>
                    </div>
                </div>
                <span class="text-warning fw-bold">★ 9</span>
            </div>
            <p class="text-light small mb-2">"Un espectáculo que también sabe detenerse. Visualmente monumental y con personajes que te atrapan de principio a fin."</p>
            <div class="text-muted" style="font-size: 0.75rem;">Hace 2 horas • 14 Me gusta</div>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>