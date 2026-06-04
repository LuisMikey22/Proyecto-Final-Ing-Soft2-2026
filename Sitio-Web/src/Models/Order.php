<?php

class Order {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Convierte el carrito activo en un pedido pagado para el Dashboard
     */
    public function processCheckout($id_user, $metodo_pago) {
        try {
            // Iniciamos una transacción (Si algo falla, no se guarda nada a medias)
            $this->pdo->beginTransaction();

            // 1. Obtener el carrito activo y calcular el total de dinero
            $stmt = $this->pdo->prepare("
                SELECT c.id_cart, SUM(ci.quantity * ci.price_at_addition) as total
                FROM carts c
                JOIN cart_items ci ON c.id_cart = ci.id_cart
                WHERE c.id_user = ? AND c.status = 'active'
                GROUP BY c.id_cart
            ");
            $stmt->execute([$id_user]);
            $cartInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cartInfo || empty($cartInfo['total'])) {
                $this->pdo->rollBack();
                return false; // Carrito vacío o inexistente
            }

            $total_amount = $cartInfo['total'];
            $id_cart = $cartInfo['id_cart'];

            // 2. CREAR LA VENTA (Esto alimenta tu Dashboard directamente)
            $stmtOrder = $this->pdo->prepare("INSERT INTO orders (id_user, total_amount, status, created_at) VALUES (?, ?, 'paid', NOW())");
            $stmtOrder->execute([$id_user, $total_amount]);
            $id_order = $this->pdo->lastInsertId();

            // 3. Cerrar el carrito (Lo pasamos a 'completed' para que el usuario inicie uno nuevo vacío)
            $stmtCloseCart = $this->pdo->prepare("UPDATE carts SET status = 'completed' WHERE id_cart = ?");
            $stmtCloseCart->execute([$id_cart]);

            $this->pdo->commit();
            return $id_order; // Devolvemos el número de pedido
            
        } catch(PDOException $e) {
            $this->pdo->rollBack();
            // Detector de errores encendido por seguridad
            die("🚨 ERROR AL PROCESAR EL PAGO: " . $e->getMessage());
        }
    }

    /**
     * Obtiene todo el historial de compras de un usuario
     */
    public function getUserOrders($id_user) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_order, total_amount, status, created_at FROM orders WHERE id_user = ? ORDER BY created_at DESC");
            $stmt->execute([$id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al leer compras: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los detalles de un pedido específico para el seguimiento
     */
    public function getOrderById($id_order, $id_user) {
        try {
            $stmt = $this->pdo->prepare("SELECT id_order, total_amount, status, created_at FROM orders WHERE id_order = ? AND id_user = ?");
            $stmt->execute([$id_order, $id_user]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return false;
        }
    }

    /**
     * Cambia el estado del pedido a "Devolución Solicitada"
     */
    public function requestReturn($id_order, $id_user) {
        try {
            $stmt = $this->pdo->prepare("UPDATE orders SET status = 'return_requested' WHERE id_order = ? AND id_user = ?");
            return $stmt->execute([$id_order, $id_user]);
        } catch(PDOException $e) {
            return false;
        }
    }

}
