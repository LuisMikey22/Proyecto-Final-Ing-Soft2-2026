<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Enrutador Centralizado del Sistema (Front Controller)
 * Redirige las peticiones HTTP a los métodos correspondientes de los controladores.
 */

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/helpers/functions.php';
require_once __DIR__ . '/../src/helpers/auth.php';
require_once __DIR__ . '/../src/controllers/UsersController.php';
require_once __DIR__ . '/../src/controllers/ProductsController.php';
require_once __DIR__ . '/../src/controllers/ChatbotController.php';
require_once __DIR__ . '/../src/controllers/DashboardController.php';
require_once __DIR__ . '/../src/controllers/CartController.php';
require_once __DIR__ . '/../src/controllers/CheckoutController.php';
require_once __DIR__ . '/../src/controllers/TrackingController.php';
require_once __DIR__ . '/../src/controllers/InventoryController.php';
require_once __DIR__ . '/../src/controllers/ReviewController.php';

$pdo = getPDO();
$dashboardController = new DashboardController($pdo);
$chatbotController = new ChatbotController($pdo);
$userController = new UsersController($pdo);
$productController = new ProductsController($pdo);
$cartController = new CartController($pdo);
$checkoutController = new CheckoutController($pdo);
$trackingController = new TrackingController($pdo);
$inventoryController = new InventoryController($pdo);
$reviewController = new ReviewController($pdo);

$route = trim($_GET['route'] ?? 'home', '/');
$method = $_SERVER['REQUEST_METHOD'];

// ==========================================
// 1. RUTAS PÚBLICAS (Visitantes y Clientes)
// ==========================================

// Inicio (Catálogo de productos)
if ($route === '' || $route === 'home') {
    if ($method === 'GET') {
        // Obtenemos productos directamente para la vista home usando el modelo
        $productModel = new Product($pdo);
        $products = $productModel->getAll();
        return view('home/index', ['products' => $products]);
    }
}

// Sobre Nosotros
if ($route === 'about') {
    if ($method === 'GET') return view('about/index');
}

// Búsqueda de Productos
if (preg_match('#^search/(.+)$#', $route, $matches) || (isset($_GET['q']) && $route === 'search')) {
    if ($method === 'GET') {
        $query = $_GET['q'] ?? $matches[1] ?? '';
        
        $productModel = new Product($pdo);
        $searchResult = $productModel->search($query);
        
        return view('search/products.search', ['searchResult' => $searchResult]);
    }
}

// Detalles de un Producto
if (preg_match('#^products/(\d+)$#', $route, $matches)) {
    if ($method === 'GET') return $productController->showDetails((int)$matches[1]);
}


// ==========================================
// 2. RUTAS DE AUTENTICACIÓN Y PERFIL
// ==========================================

// Registro de Usuarios
if ($route === 'account/register') {
    if ($method === 'GET') return $userController->showRegisterForm();
    if ($method === 'POST') return $userController->registerUser();
}

// Iniciar Sesión
if ($route === 'login') {
    if ($method === 'GET') return $userController->showLoginForm();
    if ($method === 'POST') return $userController->loginUser();
}

// Cerrar Sesión
if ($route === 'logout') {
    if ($method === 'GET') return $userController->logoutUser();
}

// Perfil de Usuario
if (preg_match('#^account/profile/(\d+)$#', $route, $matches)) {
    requerirAutenticacion();
    if ($method === 'GET') return $userController->showProfile((int)$matches[1]);
}


// ==========================================
// 3. RUTAS DEL CARRITO DE COMPRAS
// ==========================================

// Añadir al Carrito

// Ver el carrito
if ($route === 'cart' && $method === 'GET') {
    return $cartController->showCart();
}

// Añadir al Carrito
if ($route === 'cart/add' && $method === 'POST') {
    return $cartController->addToCart();
}

// Eliminar del carrito
if (preg_match('#^cart/remove/(\d+)$#', $route, $matches) && $method === 'POST') {
    return $cartController->removeFromCart((int)$matches[1]);
}


// ==========================================
// RUTAS DE RESEÑAS / REVIEWS
// ==========================================
if (preg_match('#^review/(\d+)$#', $route, $matches) && $method === 'GET') {
    return $reviewController->showForm((int)$matches[1]);
}
if ($route === 'review/submit' && $method === 'POST') {
    return $reviewController->submitReview();
}
if ($route === 'reviews/all' && $method === 'GET') {
    return $reviewController->showAllReviews();
} 


// ==========================================
// RUTAS DE CHECKOUT Y PAGOS
// ==========================================
if ($route === 'checkout' && $method === 'GET') {
    return $checkoutController->showCheckout();
}
if ($route === 'checkout/process' && $method === 'POST') {
    return $checkoutController->processPayment();
}
if ($route === 'checkout/success' && $method === 'GET') {
    return $checkoutController->success();
}


// ==========================================
// RUTAS DE SEGUIMIENTO Y DEVOLUCIONES
// ==========================================
if ($route === 'mis-compras' && $method === 'GET') {
    return $trackingController->myOrders();
}
if (preg_match('#^tracking/(\d+)$#', $route, $matches) && $method === 'GET') {
    return $trackingController->trackOrder((int)$matches[1]);
}
if (preg_match('#^returns/(\d+)$#', $route, $matches) && $method === 'POST') {
    return $trackingController->processReturn((int)$matches[1]);
}

// =
// =========================================
// 4. RUTAS ADMINISTRATIVAS (Protegidas)
// ==========================================

// --- MÓDULO DE PRODUCTOS ---
if ($route === 'admin/products') {
    requireAdmin();
    if ($method === 'GET') {
        // Reutilizamos showList pero indicando que es para el admin si fuera necesario
        return $productController->showList(); 
    }
}
if (preg_match('#^admin/products/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'GET') return $productController->showDetails((int)$matches[1]);
}

if ($route === 'admin/products/add') {
    requireAdmin();
    if ($method === 'GET') return $productController->showAddForm();
}

if ($route === 'admin/products/create' && $method === 'POST') {
    requireAdmin();
    return $productController->createProduct();
}

if (preg_match('#^admin/products/mod/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'GET') return $productController->showEditForm((int)$matches[1]);
}

if (preg_match('#^admin/products/update/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'POST') return $productController->updateProduct((int)$matches[1]);
}

if (preg_match('#^admin/products/delete/(\d+)$#', $route, $matches)) {
    requireAdmin();
    return $productController->deleteProduct((int)$matches[1]);
}
if ($route === 'admin/dashboard' && $method === 'GET') {
    return $dashboardController->showAnalyticsSuite();
}


// --- MÓDULO DE USUARIOS ---
if ($route === 'admin/users') {
    requireAdmin();
    if ($method === 'GET') return $userController->showUserList();
}

if ($route === 'admin/users/add') {
    requireAdmin();
    if ($method === 'GET') return view('admin/user.add');
}

if ($route === 'admin/users/create' && $method === 'POST') {
    requireAdmin();
    // Reutilizamos el registro normal
    return $userController->registerUser(); 
}

if (preg_match('#^admin/users/details/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'GET') {
        $userModel = new User($pdo);
        $user = $userModel->findById((int)$matches[1]);
        if (!$user) return view('errors/404');
        
        return view('admin/user.details', ['user' => $user]);
    }
}

if (preg_match('#^admin/users/mod/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'GET') {
        $user = (new User($pdo))->findById((int)$matches[1]);
        if (!$user) return view('errors/404');
        return view('admin/user.mod', ['user' => $user]);
    }
}

if (preg_match('#^admin/users/update/(\d+)$#', $route, $matches)) {
    requireAdmin();
    if ($method === 'POST') {
        // Enrutamiento directo al modelo para actualización administrativa rápida
        $data = [
            'username'    => $_POST['user'] ?? '',
            'email'       => $_POST['email'] ?? '',
            'password'    => '', 
            'role'        => $_POST['role'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];
        (new User($pdo))->update((int)$matches[1], $data);

        if ($_POST['role'] === 'admin') : 
            header('Location: ' . BASE_PATH . '/admin/users');
        else:
            header('Location: ' . BASE_PATH);
        endif;

        exit();
    }
}

if (preg_match('#^admin/users/delete/(\d+)$#', $route, $matches)) {
    requireAdmin();
    return $userController->deleteUser((int)$matches[1]);
}


// RUTAS DE INVENTARIO (ADMIN)
// ==========================================
if ($route === 'admin/inventory' && $method === 'GET') {
    return $inventoryController->showInventory();
}
if ($route === 'admin/inventory/add' && $method === 'POST') {
    return $inventoryController->updateStock();
}


// ==========================================
// MÓDULO DE CHATBOT (Rutas Web y API)
// ==========================================
if ($route === 'chatbot' && $method === 'GET') {
    return $chatbotController->showChatInterface();
}
if ($route === 'api/chatbot/main' && $method === 'GET') {
    return $chatbotController->apiGetMainOptions();
}
if (preg_match('#^api/chatbot/sub/(\d+)$#', $route, $matches) && $method === 'GET') {
    return $chatbotController->apiGetSubOptions((int)$matches[1]);
}
if ($route === 'api/chatbot/log' && $method === 'POST') {
    return $chatbotController->apiLogInteraction();
}


// ==========================================
// 5. MANEJO DE ERRORES (Cierre del Router)
// ==========================================
http_response_code(404);
return view('errors/404');