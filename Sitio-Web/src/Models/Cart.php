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
     * Añade un producto al carrito activo del usuario.
     * Si el producto ya estaba, suma la cantidad; si no, crea el renglón
     * congelando el precio al momento de agregarlo (price_at_addition).
     * @param int $id_user Usuario dueño del carrito.
     * @param int $id_product Producto a agregar.
     * @param int $quantity Cantidad solicitada.
     * @return bool Verdadero si se guardó correctamente.
     */
    public function addItem($id_user, $id_product, $quantity = 1) {
        try {
            // Obtenemos el precio del producto
            $stmtProd = $this->pdo->prepare("SELECT price FROM products WHERE id_product = ?");
            $stmtProd->execute([$id_product]);
            $prod = $stmtProd->fetch(PDO::FETCH_ASSOC);
            $price_at_add = $prod ? $prod['price'] : 0; 

            // Obtenemos o creamos el carrito del usuario
            $id_cart = $this->getActiveCartId($id_user);

            // Verificamos si el producto ya existe en el carrito
            $check = $this->pdo->prepare("SELECT id_cart_item, quantity FROM cart_items WHERE id_cart = ? AND id_product = ?");
            $check->execute([$id_cart, $id_product]);
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Si existe, actualizamos la cantidad
                $newQty = $existing['quantity'] + $quantity;
                $update = $this->pdo->prepare("UPDATE cart_items SET quantity = ? WHERE id_cart_item = ?");
                return $update->execute([$newQty, $existing['id_cart_item']]);
            }

            // Si no existe, insertamos el renglón con el precio congelado
            $insert = $this->pdo->prepare("INSERT INTO cart_items (id_cart, id_product, quantity, price_at_addition, created_at) VALUES (?, ?, ?, ?, NOW())");
            return $insert->execute([$id_cart, $id_product, $quantity, $price_at_add]);

        } catch(PDOException $e) {
            // El detalle técnico va al log del servidor, nunca al navegador del cliente
            error_log("Error en Cart::addItem(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la cantidad de un renglón del carrito.
     * Verifica que el item pertenezca al carrito activo del usuario que lo pide.
     * @param int $id_cart_item ID del renglón.
     * @param int $id_user Usuario que actualiza.
     * @param int $quantity Nueva cantidad (>= 1).
     * @return bool Verdadero si se actualizó.
     */
    public function updateItem($id_cart_item, $id_user, $quantity) {
        try {
            $sql = "UPDATE cart_items ci
                    SET ci.quantity = ?
                    WHERE ci.id_cart_item = ?
                      AND ci.quantity <> ?
                      AND EXISTS (SELECT 1 FROM carts c WHERE c.id_cart = ci.id_cart AND c.id_user = ? AND c.status = 'active')";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([(int)$quantity, (int)$id_cart_item, (int)$quantity, (int)$id_user]);
        } catch(PDOException $e) {
            error_log("Error en Cart::updateItem(): " . $e->getMessage());
            return false;
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
