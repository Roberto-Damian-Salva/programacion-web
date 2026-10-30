<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo_pagina; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navegación -->
    <header class="navbar navbar-expand-lg bg-black px-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold fs-4 text-white" href="index.php">MovieHub +</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav gap-3 align-items-center">
                    <li class="nav-item"><a class="nav-link active text-white" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="catalogo.php">Explorar</a></li>
                    <li class="nav-item"><a class="nav-link" href="estrenos.php">Estrenos</a></li>
                    <li class="nav-item"><a class="nav-link" href="ranking.php">Ranking</a></li>
                    <li class="nav-item"><a class="nav-link" href="reseñas.php">Reseñas</a></li>
                    <li class="nav-item"><a class="nav-link" href="comunidad.php">Comunidad</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm px-3" href="perfil.php">Mi cuenta</a></li>
                </ul>
            </div>
        </div>
    </header>