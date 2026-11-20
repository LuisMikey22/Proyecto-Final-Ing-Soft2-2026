<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas del helper de vistas: verifica que view() renderice la plantilla
 * completa (nav + contenido + footer) sin errores.
 */
final class FunctionsTest extends TestCase {

    public function testViewRenderizaLaPlantillaConNavYFooter(): void {
        ob_start();
        view('errors/404');
        $html = ob_get_clean();

        $this->assertStringContainsString('EstaHambreQueAta', $html); // nav
        $this->assertStringContainsString('404', $html);               // contenido
        $this->assertStringContainsString('</html>', $html);           // cierre del documento
    }

    public function testViewRenderizaLaVistaDeError500(): void {
        ob_start();
        view('errors/500', ['msg' => 'Mensaje de prueba PHPUnit']);
        $html = ob_get_clean();

        $this->assertStringContainsString('Error en el servidor', $html);
    }
}
