<?php
require_once __DIR__ . '/../Models/Order.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

class TrackingController {
    private Order $orderModel;

    public function __construct(PDO $pdo) {
        $this->orderModel = new Order($pdo);
    }

    // 1. Muestra la lista de "Mis Compras"
    public function myOrders() {
        requerirAutenticacion();
        $orders = $this->orderModel->getUserOrders($_SESSION['id_user']);
        return view('tracking/my_orders', ['orders' => $orders]);
    }

    // 2. Muestra el timeline estilo Mercado Libre
    public function trackOrder($id_order) {
        requerirAutenticacion();
        $order = $this->orderModel->getOrderById($id_order, $_SESSION['id_user']);
        
        if (!$order) {
            die("🚨 Pedido no encontrado o no tienes permiso para verlo.");
        }

        // Lógica de fechas simuladas para el tracking
        $fechaCompra = new DateTime($order['created_at']);
        $fechas = [
            'aprobado' => $fechaCompra->format('d M, Y - H:i'),
            'preparacion' => (clone $fechaCompra)->modify('+1 day')->format('d M, Y'),
            'camino' => (clone $fechaCompra)->modify('+2 days')->format('d M, Y'),
            'entrega' => (clone $fechaCompra)->modify('+4 days')->format('d M, Y')
        ];

        return view('tracking/timeline', [
            'order' => $order,
            'fechas' => $fechas
        ]);
    }

    // 3. Procesa la solicitud de devolución
    public function processReturn($id_order) {
        requerirAutenticacion();
        $this->orderModel->requestReturn($id_order, $_SESSION['id_user']);
        header("Location: " . BASE_PATH . "/tracking/" . $id_order . "?msg=return_success");
        exit();
    }
}
