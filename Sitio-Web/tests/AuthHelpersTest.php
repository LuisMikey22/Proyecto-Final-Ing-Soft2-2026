<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias de los helpers de autenticación y RBAC (control de
 * acceso por roles del Plan de Calidad, ISO/IEC 27001 A.9).
 */
final class AuthHelpersTest extends TestCase {

    protected function setUp(): void {
        $_SESSION = [];
    }

    protected function tearDown(): void {
        $_SESSION = [];
    }

    public function testSinSesionNoEstaLogueado(): void {
        $this->assertFalse(estaLogueado());
    }

    public function testConSesionEstaLogueado(): void {
        $_SESSION['id_user'] = 23;
        $this->assertTrue(estaLogueado());
    }

    public function testClienteNoEsAdmin(): void {
        $_SESSION['id_user'] = 28;
        $_SESSION['role'] = 'client';
        $this->assertFalse(esAdmin());
    }

    public function testAdminEsAdmin(): void {
        $_SESSION['id_user'] = 23;
        $_SESSION['role'] = 'admin';
        $this->assertTrue(esAdmin());
    }

    public function testRequerirAutenticacionDejaPasarAlUsuarioLogueado(): void {
        $_SESSION['id_user'] = 23;
        // Si no estuviera logueado, esta función haría header+exit y mataría la prueba
        requerirAutenticacion();
        $this->assertTrue(estaLogueado());
    }

    public function testRequireAdminDejaPasarAlAdministrador(): void {
        $_SESSION['id_user'] = 23;
        $_SESSION['role'] = 'admin';
        requireAdmin();
        $this->assertTrue(esAdmin());
    }
}
