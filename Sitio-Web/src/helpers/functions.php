<?php 
    require __DIR__.'/../config/database.php';
    
    $config = require __DIR__.'/../config/config.php';
    define('BASE_PATH', $config['base_url']);
    define('ASSETS_PATH', $config['assets_url']);

    function view($template, $data = []) {
        // Convierte cada clave del array en una variable
        extract($data);

        // Rutas absolutas
        $viewsPath = __DIR__.'/../views/';
        $partialPath = $viewsPath.'partials/';

        // Header
        require $partialPath.'nav.php';

        // Vista solicitada
        require $viewsPath.$template.'.php';
        
        // Footer
        require $partialPath.'footer.php';
    }

    /**
     * Escapa una salida de texto para prevenir ataques XSS (OWASP Top 10).
     * Debe usarse siempre que se imprima información capturada por usuarios.
     * @param string|null $value Texto original (puede venir nulo de la BD).
     * @return string Texto seguro para imprimir en HTML.
     */
    function e(?string $value): string {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
?>