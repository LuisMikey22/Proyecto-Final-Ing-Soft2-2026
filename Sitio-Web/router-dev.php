<?php
/**
 * Router de desarrollo para el servidor embebido de PHP.
 * Imita las reglas de reescritura del .htaccess de Apache (solo para uso local).
 * Uso: php -S localhost:8000 -t public router-dev.php
 */
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . '/public' . $path;

// Si el archivo estático existe, dejar que el servidor embebido lo sirva
if ($path !== '/' && is_file($file)) {
    return false;
}

// Cualquier otra ruta pasa al Front Controller
$_GET['route'] = ltrim($path, '/');
require __DIR__ . '/public/index.php';
