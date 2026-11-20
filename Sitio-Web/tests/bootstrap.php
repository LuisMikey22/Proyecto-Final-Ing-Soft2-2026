<?php
/**
 * Bootstrap de PHPUnit: prepara el entorno de pruebas.
 * Usa la base de datos anuiesne_ehqa_test (copia del esquema oficial) para
 * nunca tocar los datos reales de desarrollo ni de producción.
 */

// Forzar la BD de pruebas ANTES de cargar .env (Dotenv immutable no sobreescribe
// valores que ya existen en $_ENV)
$_ENV['DB_DATABASE'] = 'anuiesne_ehqa_test';
$_ENV['APP_URL'] = 'http://localhost';

require __DIR__ . '/../src/helpers/functions.php';
require __DIR__ . '/../src/helpers/auth.php';
require __DIR__ . '/../src/helpers/validation.php';
require __DIR__ . '/../src/Models/User.php';
require __DIR__ . '/../src/Models/Product.php';
require __DIR__ . '/../src/Models/Dashboard.php';

// Sesión disponible para las pruebas de los helpers de autenticación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Conexión PDO compartida por las pruebas (patrón Singleton del proyecto).
 * @return PDO
 */
function testPDO(): PDO {
    return getPDO();
}
