<?php
require_once __DIR__ . '/../Models/Cart.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

class CartController {
    private Cart $cartModel;

    public function __construct(PDO $pdo) {
        $this->cartModel = new Cart($pdo);
    }

    /**
     * Muestra la vista del carrito con los productos del usuario.
     */
    public function showCart() {
        requerirAutenticacion(); // Solo usuarios logueados tienen carrito
        $id_user = $_SESSION['id_user'];
        
        $items = $this->cartModel->getUserCart($id_user);
        
        // Calcular el total a pagar
        $total = 0;
        foreach($items as $item) {
            $total += ($item['price'] * $item['quantity']);
        }

        return view('cart/index', [
            'items' => $items,
            'total' => $total
        ]);
    }

    /**
     * Procesa la acción de agregar al carrito.
     */
    public function addToCart() {
        requerirAutenticacion();
        $id_user = $_SESSION['id_user'];
        $id_product = (int)($_POST['id_product'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($id_product > 0 && $quantity > 0) {
            $this->cartModel->addItem($id_user, $id_product, $quantity);
            $_SESSION['success_msg'] = "Producto añadido al carrito.";
        }
        
        header('Location: ' . BASE_PATH . '/cart');
        exit();
    }

    /**
     * Actualiza la cantidad de un renglón del carrito desde el formulario.
     * @return void
     */
    public function updateItemQuantity($id_cart_item) {
        requerirAutenticacion();
        $id_user = $_SESSION['id_user'];
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($quantity < 1) { $quantity = 1; }

        $this->cartModel->updateItem($id_cart_item, $id_user, $quantity);
        $_SESSION['success_msg'] = "Cantidad actualizada en el carrito.";

        header('Location: ' . BASE_PATH . '/cart');
        exit();
    }

    /**
     * Procesa la acción de eliminar un producto del carrito.
     */
    public function removeFromCart($id_cart_item) {
        requerirAutenticacion();
        $id_user = $_SESSION['id_user'];
        
        $this->cartModel->removeItem($id_cart_item, $id_user);
        $_SESSION['success_msg'] = "Producto eliminado del carrito.";
        
        header('Location: ' . BASE_PATH . '/cart');
        exit();
    }
}
