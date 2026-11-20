<?php
/**
 * Helper de validación de entradas de usuario.
 * Centraliza las reglas de seguridad del Plan de Calidad (ISO/IEC 27001 A.9 / OWASP):
 * contraseñas de al menos 8 caracteres, correos válidos y nombres de usuario saneados.
 * Son funciones puras para poder cubrirlas con pruebas unitarias.
 */

/**
 * Valida que la contraseña tenga al menos 8 caracteres (meta del Plan de Calidad).
 * @param string $password Contraseña en texto plano recibida del formulario.
 * @return bool Verdadero si cumple la longitud mínima.
 */
function isValidPassword(string $password): bool {
    return strlen($password) >= 8;
}

/**
 * Valida que el correo electrónico tenga un formato correcto.
 * @param string $email Correo capturado en el formulario.
 * @return bool Verdadero si el formato es válido.
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida el nombre de usuario: no vacío y máximo 100 caracteres (columna username).
 * @param string $username Nombre capturado en el formulario.
 * @return bool Verdadero si cumple las reglas.
 */
function isValidUsername(string $username): bool {
    $trimmed = trim($username);
    return $trimmed !== '' && strlen($trimmed) <= 100;
}
