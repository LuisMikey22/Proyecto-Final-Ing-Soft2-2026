<?php
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

class CheckoutController {
    private Cart $cartModel;
    private Order $orderModel;

    public function __construct(PDO $pdo) {
        $this->cartModel = new Cart($pdo);
        $this->orderModel = new Order($pdo);
    }

    // Muestra el formulario simulado de pago
    public function showCheckout() {
        requerirAutenticacion();
        $id_user = $_SESSION['id_user'];
        $items = $this->cartModel->getUserCart($id_user);
        
        if (empty($items)) {
            header('Location: ' . BASE_PATH . '/cart');
            exit();
        }

        $total = 0;
        foreach($items as $item) { $total += ($item['price'] * $item['quantity']); }

        return view('checkout/index', ['items' => $items, 'total' => $total]);
    }

    // Procesa el pago simulado
    public function processPayment() {
        requerirAutenticacion();
        $id_user = $_SESSION['id_user'];
        $metodo_pago = $_POST['payment_method'] ?? 'tarjeta'; // Simulado

        $id_order = $this->orderModel->processCheckout($id_user, $metodo_pago);

        if ($id_order) {
            header('Location: ' . BASE_PATH . '/checkout/success?order=' . $id_order);
            exit();
        } else {
            die("🚨 Ocurrió un problema, tu carrito está vacío o la sesión expiró.");
        }
    }

    // Muestra la pantalla de éxito con el botón de envío
    public function success() {
        requerirAutenticacion();
        $id_order = $_GET['order'] ?? 0;
        return view('checkout/success', ['id_order' => $id_order]);
    }
}
