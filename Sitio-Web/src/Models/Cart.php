<?php

class Cart {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * FUNCIÓN AUXILIAR: Obtiene el ID del carrito activo del usuario. 
     * Si no tiene uno, se lo crea automáticamente.
     */
    private function getActiveCartId($id_user) {
        // 1. Buscamos si ya tiene un carrito activo
        $stmt = $this->pdo->prepare("SELECT id_cart FROM carts WHERE id_user = ? AND status = 'active'");
        $stmt->execute([$id_user]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            return $cart['id_cart']; // Ya tiene uno, regresamos su ID
        } else {
            // 2. No tiene, le creamos uno nuevo
            $insert = $this->pdo->prepare("INSERT INTO carts (id_user, status) VALUES (?, 'active')");
            $insert->execute([$id_user]);
            return $this->pdo->lastInsertId(); // Regresamos el ID recién creado
        }
    }

    /**
     * Obtiene todos los productos en el carrito ACTIVO de un usuario.
     */
    public function getUserCart($id_user) {
        try {
            // Unimos carts -> cart_items -> products
            $sql = "SELECT ci.id_cart_item, ci.quantity, ci.id_product, 
                           p.name, p.price, p.stock 
                    FROM carts c 
                    JOIN cart_items ci ON c.id_cart = ci.id_cart 
                    JOIN products p ON ci.id_product = p.id_product 
                    WHERE c.id_user = ? AND c.status = 'active'";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_user]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error en Cart::removeItem(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Añade un producto al carrito (Versión de Diagnóstico Extremo)
     */
    public function addItem($id_user, $id_product, $quantity = 1) {
        // 1. FORZAMOS A PHP A MOSTRAR LOS ERRORES DE SQL SÍ O SÍ
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Obtenemos el precio del producto
            $stmtProd = $this->pdo->prepare("SELECT price FROM products WHERE id_product = ?");
            $stmtProd->execute([$id_product]);
            $prod = $stmtProd->fetch(PDO::FETCH_ASSOC);
            $price_at_add = $prod ? $prod['price'] : 0; 

            // Obtenemos o creamos el carrito del usuario
            $id_cart = $this->getActiveCartId($id_user);

            // Verificamos si ya existe el estambre en el carrito
            $check = $this->pdo->prepare("SELECT id_cart_item, quantity FROM cart_items WHERE id_cart = ? AND id_product = ?");
            $check->execute([$id_cart, $id_product]);
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Si existe, actualizamos
                $newQty = $existing['quantity'] + $quantity;
                $update = $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id_cart_item = ?");
                $success = $update->execute([$newQty, $existing['id_cart_item']]);
            } else {
                // Si no existe, insertamos (AQUÍ ESTÁ EL SOSPECHOSO)
                $insert = $this->pdo->prepare("INSERT INTO cart_items (id_cart, id_product, quantity, price_at_addition, created_at) VALUES (?, ?, ?, ?, NOW())");
                $success = $insert->execute([$id_cart, $id_product, $quantity, $price_at_add]);
            }

            // Si la ejecución falla pero PDO no lanza excepción, lo forzamos a detenerse
            if (!$success) {
                die("🚨 ERROR SILENCIOSO DESCUBIERTO: " . print_r($this->pdo->errorInfo(), true));
            }

            return true;

        } catch(PDOException $e) {
            // Si PDO lanza la excepción, la atrapamos e imprimimos
            die("🚨 ERROR EXPLÍCITO AL GUARDAR: " . $e->getMessage());
        }
    }

    /**
     * Elimina un producto específico del carrito.
     */
    public function removeItem($id_cart_item, $id_user) {
        try {
            // Borramos el item asegurándonos de que pertenezca al usuario correcto
            $sql = "DELETE ci FROM cart_items ci 
                    JOIN carts c ON ci.id_cart = c.id_cart 
                    WHERE ci.id_cart_item = ? AND c.id_user = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_cart_item, $id_user]);
        } catch(PDOException $e) {
            error_log("Error en Cart::removeItem(): " . $e->getMessage());
            return false;
        }
    }
}
