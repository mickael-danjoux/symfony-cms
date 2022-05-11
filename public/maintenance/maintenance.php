<?php
// si maintenance mode activé
if (file_exists('../.maintenance')) {

    $canAccess = false;

    // Si preview demandé via URL, on set un cookie. Sinon on regarde si un cookie n’existe pas déjà
    if (isset($_GET['preview'])) {
        $canAccess = boolval($_GET['preview']);
        setcookie('preview', boolval($_GET['preview']), time() + 900);
    } elseif (isset($_COOKIE['preview'])) {
        $canAccess = boolval($_COOKIE['preview']);
    }

    // sinon on affiche la page de maintenance
    if (!$canAccess) {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        die(file_get_contents('./maintenance/maintenance.html'));
    }
}