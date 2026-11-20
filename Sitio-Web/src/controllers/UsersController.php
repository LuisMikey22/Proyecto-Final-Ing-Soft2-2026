<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

/**
 * Controlador encargado de la gestión de usuarios, perfiles y autenticación.
 */
class UsersController {
    private User $userModel;

    /**
     * Constructor del controlador de usuarios.
     * @param PDO $pdo Conexión activa a la base de datos.
     */
    public function __construct(PDO $pdo) {
        $this->userModel = new User($pdo);
    }

    /**
     * Muestra el formulario de registro de cuenta.
     * @return void
     */
    public function showRegisterForm() {
        return view('account/account.register');
    }

    /**
     * Muestra el formulario de inicio de sesión.
     * @return void
     */
    public function showLoginForm() {
        return view('account/account.login');
    }

    /**
     * Procesa la solicitud de registro de un nuevo usuario.
     * Aplica las validaciones del Plan de Calidad: contraseña >= 8 caracteres,
     * correo válido y rol forzado a 'client' (nunca se acepta el rol del formulario,
     * para evitar la escalada de privilegios SEC-01).
     * @return void
     */
    public function registerUser() {
        $data = [
            'username'    => trim($_POST['username'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'password'    => $_POST['password'] ?? '',
            'role'        => 'client', // Rol forzado: el registro público siempre crea clientes
            'description' => $_POST['description'] ?? ''
        ];

        // Validaciones de entrada con mensajes amigables
        $error = null;
        if (!isValidUsername($data['username'])) {
            $error = 'El nombre de usuario es obligatorio (máximo 100 caracteres).';
        } elseif (!isValidEmail($data['email'])) {
            $error = 'El correo electrónico no tiene un formato válido.';
        } elseif (!isValidPassword($data['password'])) {
            $error = 'La contraseña debe tener al menos 8 caracteres.';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $error = 'Ese correo electrónico ya está registrado. Intenta iniciar sesión.';
        }

        if ($error !== null) {
            return view('account/account.register', ['error' => $error, 'old' => $data]);
        }

        $success = $this->userModel->add($data);

        if (!$success) {
            return view("errors/500", ["msg" => "Error al registrar usuario"]);
        }

        $user = $this->userModel->findByEmail($data['email']);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true); // Previene fijación de sesión tras autenticarse
        $_SESSION['id_user'] = $user->id_user; // Adaptado al nuevo ID
        $_SESSION['role'] = $user->role;

        header("Location: " . BASE_PATH . "/account/profile/" . $user->id_user);
        exit();
    }

    /**
     * Procesa las credenciales para iniciar sesión.
     * Incluye límite de intentos fallidos (control ISO/IEC 27001 A.9 del Plan de Calidad):
     * tras 5 fallos consecutivos se bloquea el intento por 60 segundos.
     * @return void
     */
    public function loginUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Bloqueo temporal por intentos fallidos (fuerza bruta)
            $lockedUntil = $_SESSION['login_locked_until'] ?? 0;
            if (time() < $lockedUntil) {
                $_SESSION['login_error'] = 'Demasiados intentos fallidos. Espera un minuto e intenta de nuevo.';
                header("Location: " . BASE_PATH . "/login");
                exit();
            }

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user->password_hash)) {
                session_regenerate_id(true); // Previene fijación de sesión

                $_SESSION['id_user'] = $user->id_user;
                $_SESSION['role']    = $user->role;
                unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);

                header("Location: " . BASE_PATH . "/account/profile/" . $user->id_user);
                exit();
            }

            // Fallo: incrementar contador y aplicar bloqueo al llegar a 5
            $attempts = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['login_attempts'] = $attempts;
            if ($attempts >= 5) {
                $_SESSION['login_locked_until'] = time() + 60;
                $_SESSION['login_attempts'] = 0;
            }

            $_SESSION['login_error'] = "Credenciales incorrectas.";
            header("Location: " . BASE_PATH . "/login");
            exit();
        }
    }

    /**
     * Cierra la sesión activa del usuario y limpia las cookies.
     * @return void
     */
    public function logoutUser() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
        
        header('Location: ' . BASE_PATH);
        exit();
    }

    /**
     * Muestra el perfil público o privado de un usuario.
     * @param int $id Identificador del usuario.
     * @return void
     */
    public function showProfile($id) {
        $user = $this->userModel->findById($id);
        if (!$user) return view('errors/404');

        return view('account/account.profile', ['user' => $user]);
    }

    /**
     * Lista todos los usuarios en el panel de administración.
     * @return void
     */
    public function showUserList() {
        $allUsers = $this->userModel->getAll();
        return view('admin/user.index', ['users' => $allUsers]);
    }

    /**
     * Elimina a un usuario desde el panel de administrador.
     * @param int $id Identificador del usuario.
     * @return void
     */
    public function deleteUser($id) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Evita que el admin se elimine a sí mismo
        if (isset($_SESSION['id_user']) && $_SESSION['id_user'] == $id) {
            return view("errors/500", ["msg" => "No puedes eliminarte a ti mismo"]);
        }

        $success = $this->userModel->delete($id);

        if (!$success) {
            return view("errors/500", ["msg" => "Error al eliminar usuario"]);
        }

        header('Location: ' . BASE_PATH . '/admin/users');
        exit();
    }
}