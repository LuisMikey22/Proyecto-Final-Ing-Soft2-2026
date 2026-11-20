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

$pdo = getPDO();
$dashboardController = new DashboardController($pdo);
$chatbotController = new ChatbotController($pdo);
$userController = new UsersController($pdo);
$productController = new ProductsController($pdo);

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
if ($route === 'cart/add' && $method === 'POST') {
    requerirAutenticacion();
    
    $id_product = (int)($_POST['id_product'] ?? 0);
    
    if ($id_product > 0) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['cart_success'] = "Producto añadido al carrito exitosamente";
        header('Location: ' . BASE_PATH . '/products/' . $id_product);
        exit();
    }
    
    header('Location: ' . BASE_PATH . '/products');
    exit();
}

// ==========================================
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
// API del Dashboard: datos JSON para el filtro temporal asíncrono (AJAX/Fetch)
if ($route === 'api/dashboard/data' && $method === 'GET') {
    return $dashboardController->apiAnalyticsData();
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
            'role'        => $_POST['role'] ?? 'client',
            'description' => $_POST['description'] ?? ''
        ];
        (new User($pdo))->update((int)$matches[1], $data);
        header('Location: ' . BASE_PATH . '/admin/users');
        exit();
    }
}

if (preg_match('#^admin/users/delete/(\d+)$#', $route, $matches)) {
    requireAdmin();
    return $userController->deleteUser((int)$matches[1]);
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