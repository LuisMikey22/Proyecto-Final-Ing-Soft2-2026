<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias del flujo de compra: carrito persistente, congelamiento
 * de precio, checkout transaccional y aislamiento entre usuarios.
 */
final class CartOrderTest extends TestCase {

    private Cart $cart;
    private Order $order;
    private int $idUser;
    private int $idOtroUser;

    protected function setUp(): void {
        $pdo = testPDO();
        $this->cart = new Cart($pdo);
        $this->order = new Order($pdo);

        // Dos usuarios de prueba para verificar el aislamiento entre cuentas
        $userModel = new User($pdo);
        $userModel->add(['username' => 'Comprador PHPUnit', 'email' => 'phpunit-cart-' . uniqid() . '@test.com', 'password' => 'passwordseguro123', 'role' => 'client', 'description' => '']);
        $this->idUser = (int)$pdo->lastInsertId();
        $userModel->add(['username' => 'Otro Comprador', 'email' => 'phpunit-cart-otro-' . uniqid() . '@test.com', 'password' => 'passwordseguro123', 'role' => 'client', 'description' => '']);
        $this->idOtroUser = (int)$pdo->lastInsertId();
    }

    protected function tearDown(): void {
        $pdo = testPDO();
        // orders no tiene borrado en cascada desde users: limpiar primero los pedidos
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id_user IN (?, ?)");
        $stmt->execute([$this->idUser, $this->idOtroUser]);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id_user IN (?, ?)");
        $stmt->execute([$this->idUser, $this->idOtroUser]);
    }

    public function testAddItemCreaCarritoActivoYPrecioCongelado(): void {
        $this->assertTrue($this->cart->addItem($this->idUser, 1, 2));

        $items = $this->cart->getUserCart($this->idUser);
        $this->assertCount(1, $items);
        $this->assertSame(2, (int)$items[0]['quantity']);
        $this->assertSame(260.00, (float)$items[0]['price']); // precio del producto 1
    }

    public function testAddItemRepetidoSumaCantidad(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $this->cart->addItem($this->idUser, 1, 3);

        $items = $this->cart->getUserCart($this->idUser);
        $this->assertCount(1, $items); // un solo renglón
        $this->assertSame(4, (int)$items[0]['quantity']);
    }

    public function testRemoveItemNoAfectaCarritosDeOtrosUsuarios(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $items = $this->cart->getUserCart($this->idUser);
        $idItem = (int)$items[0]['id_cart_item'];

        // El otro usuario intenta borrar un renglón que no es suyo
        $this->cart->removeItem($idItem, $this->idOtroUser);

        $sigue = $this->cart->getUserCart($this->idUser);
        $this->assertCount(1, $sigue); // el artículo sigue ahí

        // El dueño sí puede borrarlo
        $this->cart->removeItem($idItem, $this->idUser);
        $this->assertCount(0, $this->cart->getUserCart($this->idUser));
    }

    /**
     * Prepara el carrito del usuario de pruebas (2 items) y ejecuta el checkout.
     * @return int ID del pedido (0 si processCheckout falló).
     */
    private function checkoutSetup(): int {
        $this->cart->addItem($this->idUser, 1, 2); // 2 x 260 = 520
        $this->cart->addItem($this->idUser, 3, 1); // 1 x 80 = 80
        return (int)$this->order->processCheckout($this->idUser, 'tarjeta');
    }



    public function testProbeSumaTotalCarritoEnCI(): void {
        $this->cart->addItem($this->idUser, 1, 2);
        $this->cart->addItem($this->idUser, 3, 1);
        $pdo = testPDO();
        $r = $pdo->query("SELECT c.id_cart, SUM(ci.quantity * ci.price_at_addition) as total FROM carts c JOIN cart_items ci ON c.id_cart = ci.id_cart WHERE c.id_user = " . (int)$this->idUser . " AND c.status = 'active' GROUP BY c.id_cart");
        $row = $r->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($row, 'La query del carrito no devolvio fila');
        $this->assertNotNull($row['total'], 'El total debe no ser NULL');
        $this->assertNotSame(0, (int)$row['total'], 'El total debe ser distinto de cero');
    }

    public function testProbeInsertOrderDirecto(): void {
        $pdo = testPDO();
        $pdo->exec("INSERT INTO orders (id_user, total_amount, status, shipping_address, created_at) VALUES (" . (int)$this->idUser . ", 99.50, 'paid', '', NOW())");
        $id = (int)$pdo->lastInsertId();
        $this->assertGreaterThan(0, $id, 'lastInsertId debe ser mayor que cero');
        $row = $pdo->query("SELECT id_order, status, total_amount FROM orders WHERE id_order = $id")->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($row, 'El pedido insertado debe existir');
    }

    public function testCheckoutSetupCartTieneDosItems(): void {
        $this->cart->addItem($this->idUser, 1, 2); // 2 x 260 = 520
        $this->cart->addItem($this->idUser, 3, 1); // 1 x 80 = 80
        $items = $this->cart->getUserCart($this->idUser);
        $this->assertCount(2, $items);
        $this->assertEquals([1, 3], array_column($items, 'id_product'));
    }

    public function testCheckoutProcessCheckoutDevuelveIdValido(): void {
        $this->cart->addItem($this->idUser, 1, 2); // 2 x 260 = 520
        $this->cart->addItem($this->idUser, 3, 1); // 1 x 80 = 80
        $idOrder = $this->order->processCheckout($this->idUser, 'tarjeta');
        $this->assertGreaterThan(0, $idOrder);
    }

    public function testCheckoutProcessCheckoutFalsoSiCarroVacio(): void {
        // Sin items en el carrito, processCheckout debe devolver falso
        $this->assertFalse($this->order->processCheckout($this->idUser, 'tarjeta'));
    }

    public function testCheckoutPedidoEstatusEsPaid(): void {
        $idOrder = $this->checkoutSetup();
        $pedido = $this->order->getOrderById($idOrder, $this->idUser);
        $this->assertNotNull($pedido, 'El pedido debe existir para el usuario');
        $this->assertSame('paid', (string)$pedido['status']);
    }

    public function testCheckoutPedidoTotalEs600(): void {
        $idOrder = $this->checkoutSetup();
        $pedido = $this->order->getOrderById($idOrder, $this->idUser);
        $this->assertNotNull($pedido);
        $this->assertEquals(600.00, (float)$pedido['total_amount'], 'Total: ' . var_export($pedido['total_amount'], true));
    }

    public function testCheckoutCarritoCerrado(): void {
        $this->checkoutSetup();
        // El carrito activo quedó vacío (se cerró el anterior)
        $this->assertCount(0, $this->cart->getUserCart($this->idUser));
    }

    public function testCheckoutConCarritoVacioDevuelveFalso(): void {
        $this->assertFalse($this->order->processCheckout($this->idUser, 'tarjeta'));
    }

    public function testGetOrderByIdDeOtroUsuarioDevuelveFalso(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $idOrder = (int)$this->order->processCheckout($this->idUser, 'tarjeta');

        $this->assertFalse($this->order->getOrderById($idOrder, $this->idOtroUser));
    }

    public function testRequestReturnCambiaElEstado(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $idOrder = (int)$this->order->processCheckout($this->idUser, 'tarjeta');

        $this->assertTrue($this->order->requestReturn($idOrder, $this->idUser));
        $pedido = $this->order->getOrderById($idOrder, $this->idUser);
        $this->assertSame('return_requested', $pedido['status']);
    }

    public function testUpdateItemActualizaLaCantidad(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $items = $this->cart->getUserCart($this->idUser);
        $idItem = (int)$items[0]['id_cart_item'];

        // El otro usuario no puede modificar
        $this->cart->updateItem($idItem, $this->idOtroUser, 5);
        $sigue = $this->cart->getUserCart($this->idUser);
        $this->assertSame(1, (int)$sigue[0]['quantity']);

        // El dueño sí puede
        $this->cart->updateItem($idItem, $this->idUser, 5);
        $actualizado = $this->cart->getUserCart($this->idUser);
        $this->assertSame(5, (int)$actualizado[0]['quantity']);
    }

    public function testGetUserOrdersDevuelveElHistorial(): void {
        $this->cart->addItem($this->idUser, 1, 1);
        $this->order->processCheckout($this->idUser, 'tarjeta');

        $historial = $this->order->getUserOrders($this->idUser);
        $this->assertCount(1, $historial);
        $this->assertSame('paid', $historial[0]['status']);
    }
}
