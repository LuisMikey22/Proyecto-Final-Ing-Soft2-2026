<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias de reseñas e inventario (módulos agregados en la rama main).
 */
final class ReviewInventoryTest extends TestCase {

    private Review $review;
    private Inventory $inventory;
    private int $idUser;

    protected function setUp(): void {
        $pdo = testPDO();
        $this->review = new Review($pdo);
        $this->inventory = new Inventory($pdo);

        $userModel = new User($pdo);
        $userModel->add(['username' => 'Reseñador PHPUnit', 'email' => 'phpunit-review-' . uniqid() . '@test.com', 'password' => 'passwordseguro123', 'role' => 'client', 'description' => '']);
        $this->idUser = (int)$pdo->lastInsertId();
    }

    protected function tearDown(): void {
        $stmt = testPDO()->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->execute([$this->idUser]); // reviews se borra en cascada
    }

    public function testAddReviewGuardaYGetAllReviewsLaDevuelve(): void {
        $this->assertTrue($this->review->addReview($this->idUser, 1, 5, 'Excelente bufanda PHPUnit'));

        $reviews = $this->review->getAllReviews();
        $mia = array_values(array_filter($reviews, fn($r) => $r['comment'] === 'Excelente bufanda PHPUnit'));
        $this->assertCount(1, $mia);
        $this->assertSame(5, (int)$mia[0]['rating']);
        $this->assertSame('Bufanda de ojos', $mia[0]['product_name']);
    }

    public function testInventarioOrdenaPorStockAscendente(): void {
        $lista = $this->inventory->getAllProductsWithStock();
        $this->assertNotEmpty($lista);
        $stocks = array_column($lista, 'stock');
        $ordenados = $stocks;
        sort($ordenados);
        $this->assertSame($ordenados, $stocks);
    }

    public function testAddStockSumaLaCantidad(): void {
        $pdo = testPDO();
        $antes = (int)$pdo->query("SELECT stock FROM products WHERE id_product = 1")->fetchColumn();

        $this->assertTrue($this->inventory->addStock(1, 5));
        $despues = (int)$pdo->query("SELECT stock FROM products WHERE id_product = 1")->fetchColumn();
        $this->assertSame($antes + 5, $despues);

        // Revertir para no alterar los datos de prueba
        $this->inventory->addStock(1, -5);
    }
}
