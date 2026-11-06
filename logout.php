<?php
session_start();

// Limpiar variables de sesión
$_SESSION = array();

// Eliminar cookies de sesión del navegador si están configuradas
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al login
header("Location: login.php?status=logout");
exit;
?>