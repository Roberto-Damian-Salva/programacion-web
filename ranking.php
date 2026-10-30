<?php
$titulo_pagina = "MovieHub - Ranking";
require_once 'includes/header.php';
?>

    <main class="container my-4 flex-grow-1">
        <div class="mb-4">
            <h1 class="h3 fw-bold">Las historias que se quedan.</h1>
            <p class="text-secondary small">Un ranking hecho entre todos los usuarios de la comunidad de MovieHub.</p>
        </div>

        <div class="card bg-dark border border-secondary p-3">
            <ul class="list-group list-group-flush bg-transparent">
                <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center border-secondary py-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-bold fs-5 text-secondary">01</span>
                        <div>
                            <h6 class="mb-0">El viaje de Chihiro</h6>
                            <small class="text-muted">2001 • Animación</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-warning">★ 8,9</span>
                        <div class="text-muted small">120K votos</div>
                    </div>
                </li>
                <!-- Repetir elementos del ranking -->
            </ul>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>