<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias de las reglas de validación del Plan de Calidad
 * (contraseña >= 8 caracteres, correo válido, username saneado) y del
 * escape de salidas para prevenir XSS.
 */
final class ValidationTest extends TestCase {

    public function testPasswordCortaEsRechazada(): void {
        $this->assertFalse(isValidPassword('123'));
        $this->assertFalse(isValidPassword('1234567')); // 7 caracteres: justo por debajo del mínimo
        $this->assertFalse(isValidPassword(''));
    }

    public function testPasswordDeOchoCaracteresEsAceptada(): void {
        $this->assertTrue(isValidPassword('12345678')); // límite exacto
        $this->assertTrue(isValidPassword('contraseña-segura-2026'));
    }

    public function testEmailValidoEsAceptado(): void {
        $this->assertTrue(isValidEmail('cliente@tienda.com'));
        $this->assertTrue(isValidEmail('axtc@gmail.com'));
    }

    public function testEmailInvalidoEsRechazado(): void {
        $this->assertFalse(isValidEmail('no-es-correo'));
        $this->assertFalse(isValidEmail('cliente@'));
        $this->assertFalse(isValidEmail(''));
    }

    public function testUsernameVacioEsRechazado(): void {
        $this->assertFalse(isValidUsername(''));
        $this->assertFalse(isValidUsername('   '));
    }

    public function testUsernameValidoEsAceptado(): void {
        $this->assertTrue(isValidUsername('Axdiael Trinidad'));
    }

    public function testUsernameDemasiadoLargoEsRechazado(): void {
        $this->assertFalse(isValidUsername(str_repeat('a', 101)));
        $this->assertTrue(isValidUsername(str_repeat('a', 100))); // límite de la columna
    }

    public function testEscapeNeutralizaScriptXss(): void {
        $this->assertSame(
            '&lt;script&gt;alert(1)&lt;/script&gt;',
            e('<script>alert(1)</script>')
        );
    }

    public function testEscapeConvierteComillas(): void {
        $this->assertSame('&quot; onmouseover=&quot;alert(1)', e('" onmouseover="alert(1)'));
    }

    public function testEscapeDeNuloDevuelveCadenaVacia(): void {
        $this->assertSame('', e(null));
    }
}
