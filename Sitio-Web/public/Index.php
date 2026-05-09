<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require __DIR__ . '/../src/helpers/functions.php';
    include __DIR__.'/../src/controllers/UsersController.php';
    include __DIR__.'/../src/controllers/ProductsController.php';
    require __DIR__ . '/../src/helpers/auth.php';

    error_log("=== NUEVA PETICIÓN ===");
    error_log("Ruta solicitada: " . ($_GET['route'] ?? 'home'));
    error_log("Método: " . $_SERVER['REQUEST_METHOD']);
    error_log("SESSION ID: " . session_id());
    error_log("User ID en sesión: " . ($_SESSION['user_id'] ?? 'NO DEFINIDO'));

    // Obtener ruta limpia desde $_GET['route']
    $route = trim($_GET['route'] ?? '', '/');
    $method = $_SERVER['REQUEST_METHOD'];

    if($route === '' || $route === 'home') {
        if($method === 'GET') {
            $productModel = new Product(getPDO());
            $products = $productModel->all(); 
            return view('home/index', ['products' => $products]);
        }
    }

    if($route === 'about') {
        return view('about/index');
    }

    if($route === 'admin/products') {
        requireAdmin();

        if($method === 'GET') {
            $productModel = new Product(getPDO());
            $products = $productModel->all(); 
            return view('admin/products.index', compact('products'));
        }
    }

    if(preg_match('#^search/(.+)$#', $route, $matches)) {
        $searchQuery = filter_var($matches[1], FILTER_SANITIZE_SPECIAL_CHARS);

        if($method === 'GET') {
            $productModel = new Product(getPDO());
            $searchResult = $productModel->search($searchQuery);
            return view('search/products.search', compact('searchResult'));
        }
    }

    if(preg_match('#^products/(\d+)$#', $route, $matches)) {
        $productId = filter_var($matches[1], FILTER_SANITIZE_NUMBER_INT);

        if($method === 'GET') {
            $productModel = new Product(getPDO());
            $product = $productModel->find($productId);
            return view('products/products.details', ['product' => $product]);
        }
    }

    if(preg_match('#^admin/products/(\d+)$#', $route, $matches)) {
        $productId = filter_var($matches[1], FILTER_SANITIZE_NUMBER_INT);

        if($method === 'GET') {
            $productModel = new Product(getPDO());
            $product = $productModel->find($productId);
            return view('admin/products.details', ['product' => $product]);
        }
    }

    if (preg_match('#^admin/products/mod/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $id = (int)$matches[1];

        if ($method === 'GET') {
            $productModel = new Product(getPDO());
            $product = $productModel->find($id);
            return view('admin/products.mod', ['product' => $product]);
        }
    }

    if (preg_match('#^admin/products/update/(\d+)$#', $route, $matches)) {
        requireAdmin();
    
        $id = (int)$matches[1];

        if ($method === 'POST') {

            $pdo = getPDO();
            $productModel = new Product($pdo);

            $product = $productModel->find($id);
            if (!$product) {
                die("Producto no encontrado");
            }

            $name        = $_POST['name'] ?? '';
            $category    = $_POST['category'] ?? '';
            $price       = $_POST['price'] ?? 0;
            $description = $_POST['description'] ?? '';
            $image       = $product->image;

            if (!empty($_FILES['image']['name'])) {
                $tmp       = $_FILES['image']['tmp_name'];
                $fileName  = time() . "_" . basename($_FILES['image']['name']);
                $destino   = __DIR__ . "/assets/images/" . $fileName;

                if (move_uploaded_file($tmp, $destino)) {
                    $image = $fileName;
                }
            }

            $ok = $productModel->updateProduct($id, [
                'name'        => $name,
                'category'    => $category,
                'price'       => $price,
                'description' => $description,
                'image'       => $image
            ]);

            if (!$ok) {
                die("Error al actualizar el producto.");
            }

            $updatedProduct = $productModel->find($id);

            return view('admin/products.update', [
                'product' => $updatedProduct
            ]);
        }
    }

    if (preg_match('#^admin/products/delete/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $id = (int)$matches[1];

        if ($method === 'GET') {

            $pdo = getPDO();
            $productModel = new Product($pdo);

            $product = $productModel->find($id);

            if (!$product) {
                return view('errors/404');
            }

            $ok = $productModel->deleteProduct($id);

            if (!$ok) {
                die("Error al eliminar producto.");
            }

            return view("admin/products.delete", [
                "name" => $product->name,
                "id"   => $id
            ]);
        }
    }

    if ($route === 'admin/products/add' && $method === 'GET') {
        requireAdmin();
        return view('admin/products.add');
    }

    if ($route === 'admin/products/create' && $method === 'POST') {
        requireAdmin();

        $productModel = new Product(getPDO());

        $name        = $_POST['name'] ?? '';
        $category    = $_POST['category'] ?? '';
        $price       = $_POST['price'] ?? '';
        $description = $_POST['description'] ?? '';

        $imageName = null;

        if (!empty($_FILES['image']['name'])) {
            
            $uploadsDir = __DIR__ . "/assets/images/";

            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }

            $imageName = time() . "_" . basename($_FILES['image']['name']);

            $destino = $uploadsDir . $imageName;

            move_uploaded_file($_FILES['image']['tmp_name'], $destino);
        }

        $productModel->addProduct([
            'name'        => $name,
            'category'    => $category,
            'price'       => $price,
            'description' => $description,
            'image'       => $imageName
        ]);

        header("Location: " . BASE_PATH . "/products");
        exit;
    }

    if($route === 'account') {
        return view('account/account.register');
    }

    if ($route === 'account/register' && $method === 'GET') {
        $controller = new UsersController(getPDO());
        return $controller->showRegisterForm();
    }

    if ($route === 'account/register' && $method === 'POST') {
        $controller = new UsersController(getPDO());
        return $controller->register();
    }

    if (preg_match('#^account/profile/(\d+)$#', $route, $matches)) {
        requireLogin();
        
        $id = (int)$matches[1];

        $pdo = getPDO();
        $controller = new UsersController($pdo);

        return $controller->profile($id);
    }

    if ($route === 'login' && $method === 'GET') {
        $controller = new UsersController(getPDO());
        return $controller->showLoginForm();
    }

    if ($route === 'login' && $method === 'POST') {
        $controller = new UsersController(getPDO());
        return $controller->logIn();
    }

    if ($route === 'logout' && $method === 'GET') {
        $controller = new UsersController(getPDO());
        return $controller->logout();
    }

    if($route === 'admin/users') {
        requireAdmin();

        if($method === 'GET') {
            $controller = new UsersController(getPDO());
            return $controller->listUsers();
        }
    }

    if(preg_match('#^admin/users/details/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $userId = (int)$matches[1];

        if($method === 'GET') {
            $controller = new UsersController(getPDO());
            return $controller->showUserDetails($userId);
        }
    }

    if($route === 'admin/users/add') {
        requireAdmin();

        if($method === 'GET') {
            $controller = new UsersController(getPDO());
            return $controller->showAddUserForm();
        }
    }

    if($route === 'admin/users/create' && $method === 'POST') {
        requireAdmin();
        
        $controller = new UsersController(getPDO());
        return $controller->createUser();
    }

    if(preg_match('#^admin/users/mod/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $userId = (int)$matches[1];

        if($method === 'GET') {
            $controller = new UsersController(getPDO());
            return $controller->showModUserForm($userId);
        }
    }

    if(preg_match('#^admin/users/update/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $userId = (int)$matches[1];

        if($method === 'POST') {
            $controller = new UsersController(getPDO());
            return $controller->updateUser($userId);
        }
    }

    if(preg_match('#^admin/users/delete/(\d+)$#', $route, $matches)) {
        requireAdmin();
        
        $userId = (int)$matches[1];

        if($method === 'GET') {
            $controller = new UsersController(getPDO());
            return $controller->deleteUser($userId);
        }
    }

    if ($route === 'cart/add' && $method === 'POST') {
        error_log("=== RUTA CART/ADD INVOCADA ===");
        error_log("POST data recibida: " . print_r($_POST, true));
        error_log("SESSION data: " . print_r($_SESSION, true));
        
        // NO necesitas session_start() aquí porque ya se inició arriba
        // REMUEVE esta línea:
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }
        
        if (!isset($_SESSION['user_id'])) {
            error_log("USUARIO NO LOGUEADO - Redirigiendo a login");
            header('Location: ' . BASE_PATH . '/login');
            exit();
        }
        
        $productId = (int)($_POST['product_id'] ?? 0);
        error_log("Product ID recibido: " . $productId);
        
        // Verifica que el ID sea válido
        if ($productId <= 0) {
            error_log("ERROR: Product ID inválido");
            $_SESSION['cart_error'] = "ID de producto inválido";
            header('Location: ' . BASE_PATH . '/products');
            exit();
        }
        
        $_SESSION['cart_success'] = "Producto añadido al carrito exitosamente";
        error_log("Mensaje de éxito establecido en sesión");
        
        header('Location: ' . BASE_PATH . '/products/' . $productId);
        exit();
    }
    http_response_code(404);
    return view('errors/404');
