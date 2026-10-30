<?php
$titulo_pagina = "MovieHub - Explorar Catálogo";
require_once 'includes/header.php';
?>

    <main class="container my-4 flex-grow-1">
        <!-- Cabecera de sección -->
        <div class="mb-4">
            <h1 class="h3 fw-bold">Siempre hay otra historia.</h1>
            <p class="text-secondary small">Explora nuestro catálogo completo para encontrar tu próxima película favorita.</p>
        </div>

        <div class="row">
            <!-- Barra lateral de Filtros -->
            <aside class="col-md-3 mb-4">
                <div class="p-3 rounded bg-dark border border-secondary">
                    <h6 class="fw-bold mb-3">Filtros</h6>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Género</label>
                        <select class="form-select form-select-sm bg-black text-light border-secondary">
                            <option>Todos</option>
                            <option>Acción</option>
                            <option>Ciencia Ficción</option>
                            <option>Drama</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Año</label>
                        <input type="text" class="form-control form-control-sm bg-black text-light border-secondary" placeholder="Ej. 2024">
                    </div>
                    <button class="btn btn-light btn-sm w-100 fw-bold">Aplicar filtros</button>
                </div>
            </aside>

            <!-- Grilla de Resultados -->
            <section class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-secondary small">Mostrando resultados de catálogo</span>
                    <select class="form-select form-select-sm w-auto bg-black text-light border-secondary">
                        <option>Más populares</option>
                        <option>Mejor valoradas</option>
                        <option>Más recientes</option>
                    </select>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <!-- Tarjeta de Película -->
                    <div class="col">
                        <div class="card h-100 p-2">
                            <div class="bg-secondary bg-opacity-25 rounded" style="height: 200px;"></div>
                            <div class="card-body px-0 pb-0">
                                <h6 class="card-title text-truncate m-0">Dune: Parte dos</h6>
                                <p class="text-muted small mb-2">2024 • Ciencia Ficción</p>
                                <a href="detalle.php" class="btn btn-outline-light btn-sm w-100">Ver Ficha</a>
                            </div>
                        </div>
                    </div>
                    <!-- Duplicar según necesidad -->
                </div>
            </section>
        </div>
    </main>

<?php require_once 'includes/footer.php'; ?>