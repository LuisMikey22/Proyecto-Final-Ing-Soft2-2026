<?php

class Inventory {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Obtiene todos los productos, ordenando primero los que tienen poco stock.
     */
    public function getAllProductsWithStock() {
        try {
            // Traemos id, nombre, precio y stock. Los de menor stock saldrán primero.
            $stmt = $this->pdo->query("SELECT id_product, name, price, stock FROM products ORDER BY stock ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al leer inventario: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Suma la cantidad indicada al stock actual del producto.
     */
    public function addStock($id_product, $quantity) {
        try {
            $stmt = $this->pdo->prepare("UPDATE products SET stock = stock + ? WHERE id_product = ?");
            return $stmt->execute([$quantity, $id_product]);
        } catch(PDOException $e) {
            // El detalle técnico va al log del servidor, nunca al navegador del cliente
            error_log("Error en Inventory::addStock(): " . $e->getMessage());
            return false;
        }
    }
}
