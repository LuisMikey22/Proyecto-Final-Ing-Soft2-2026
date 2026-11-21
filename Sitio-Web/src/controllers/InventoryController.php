<?php
require_once __DIR__ . '/../Models/Inventory.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

class InventoryController {
    private Inventory $inventoryModel;

    public function __construct(PDO $pdo) {
        $this->inventoryModel = new Inventory($pdo);
    }

    // ESTA ES LA FUNCIÓN QUE PHP ESTÁ BUSCANDO:
    public function showInventory() {
        requerirAutenticacion(); 
        
        $products = $this->inventoryModel->getAllProductsWithStock();
        return view('admin/inventory', ['products' => $products]);
    }

    // Procesa el formulario para sumar stock
    public function updateStock() {
        requerirAutenticacion();
        
        $id_product = (int)($_POST['id_product'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);

        if ($id_product > 0 && $quantity > 0) {
            $this->inventoryModel->addStock($id_product, $quantity);
            $_SESSION['success_msg'] = "Se añadieron $quantity piezas al inventario exitosamente.";
        } else {
            $_SESSION['error_msg'] = "Cantidad no válida.";
        }
        
        header('Location: ' . BASE_PATH . '/admin/inventory');
        exit();
    }
}
