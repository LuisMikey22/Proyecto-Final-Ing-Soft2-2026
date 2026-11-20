<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del modelo Product: catálogo, búsqueda saneada
 * (la caja blanca del Avance 1 validaba que las búsquedas vacías no
 * lleguen a la BD) y validación de identificadores.
 */
final class ProductModelTest extends TestCase {

    private Product $model;

    protected function setUp(): void {
        $this->model = new Product(testPDO());
    }

    public function testGetAllDevuelveElCatalogoConCategoriaEImagen(): void {
        $products = $this->model->getAll();
        $this->assertNotEmpty($products);
        foreach ($products as $p) {
            $this->assertNotEmpty($p->category);
            $this->assertNotEmpty($p->image);
        }
    }

    public function testBusquedaVaciaNoConsultaLaBaseDeDatos(): void {
        [$term, $qty, $items] = $this->model->search('');
        $this->assertSame(0, $qty);
        $this->assertSame([], $items);

        [$term2, $qty2, $items2] = $this->model->search('   ');
        $this->assertSame(0, $qty2);
        $this->assertSame([], $items2);
    }

    public function testBusquedaEncuentraPorNombreSinDistinguirMayusculas(): void {
        [$term, $qty, $items] = $this->model->search('BUFANDA');
        $this->assertGreaterThanOrEqual(1, $qty);
        $this->assertStringContainsStringIgnoringCase('bufanda', $items[0]->name);
    }

    public function testBusquedaSinResultadosDevuelveCero(): void {
        [$term, $qty, $items] = $this->model->search('xyz-no-existe-' . uniqid());
        $this->assertSame(0, $qty);
        $this->assertSame([], $items);
    }

    public function testFindByIdDevuelveElProducto(): void {
        $p = $this->model->findById(1);
        $this->assertNotNull($p);
        $this->assertSame('Bufanda de ojos', $p->name);
    }

    public function testFindByIdInvalidoDevuelveNulo(): void {
        $this->assertNull($this->model->findById(0));
        $this->assertNull($this->model->findById(-3));
        $this->assertNull($this->model->findById(999999));
    }

    public function testAddCreaProductoConImagenPrincipal(): void {
        $nombre = 'Producto PHPUnit ' . uniqid();
        $ok = $this->model->add([
            'id_category' => 1,
            'name' => $nombre,
            'description' => 'Creado por prueba automatizada',
            'price' => 99.50,
            'stock' => 7,
            'image_url' => 'phpunit-test.webp'
        ]);
        $this->assertTrue($ok);

        // Buscar por nombre: lastInsertId() apuntaría a la imagen (segundo INSERT)
        $stmt = testPDO()->prepare("SELECT id_product FROM products WHERE name = ?");
        $stmt->execute([$nombre]);
        $id = (int)$stmt->fetchColumn();

        $p = $this->model->findById($id);
        $this->assertSame(99.50, (float)$p->price);
        $this->assertSame('phpunit-test.webp', $p->image);

        // Limpieza: borrar el producto elimina su imagen por la FK en cascada
        $this->model->delete($id);
    }

    public function testUpdateCambiaNombreYPrecio(): void {
        $this->model->add([
            'id_category' => 2,
            'name' => 'Original PHPUnit',
            'description' => 'para editar',
            'price' => 10.00,
            'stock' => 3
        ]);
        $id = (int)testPDO()->lastInsertId();

        $ok = $this->model->update($id, [
            'name' => 'Editado PHPUnit',
            'description' => 'ya editado',
            'price' => 25.00,
            'id_category' => 3
        ]);
        $this->assertTrue($ok);

        $p = $this->model->findById($id);
        $this->assertSame('Editado PHPUnit', $p->name);
        $this->assertSame(25.00, (float)$p->price);

        $this->model->delete($id);
    }

    public function testDeleteEliminaElProducto(): void {
        $this->model->add([
            'id_category' => 1,
            'name' => 'Borrable PHPUnit',
            'description' => 'para borrar',
            'price' => 1.00,
            'stock' => 1
        ]);
        $id = (int)testPDO()->lastInsertId();

        $this->assertTrue($this->model->delete($id));
        $this->assertNull($this->model->findById($id));
    }

    public function testUpdateConIdInvalidoDevuelveFalso(): void {
        $this->assertFalse($this->model->update(-1, ['name' => 'x', 'description' => 'x', 'price' => 1]));
        $this->assertFalse($this->model->delete(0));
    }
}
