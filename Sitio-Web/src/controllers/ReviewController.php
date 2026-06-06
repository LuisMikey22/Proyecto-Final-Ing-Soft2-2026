<?php
require_once __DIR__ . '/../Models/Review.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/auth.php';

class ReviewController {
    private Review $reviewModel;

    public function __construct(PDO $pdo) {
        $this->reviewModel = new Review($pdo);
    }

    // Muestra el formulario con las estrellas
    public function showForm($id_product) {
        requerirAutenticacion();
        return view('reviews/create', ['id_product' => $id_product]);
    }

    // Atrapa los datos del formulario y los guarda
    public function submitReview() {
        requerirAutenticacion();
        
        $id_product = (int)($_POST['id_product'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = htmlspecialchars($_POST['comment'] ?? '');
        $id_user = $_SESSION['id_user'];

        if ($rating >= 1 && $rating <= 5 && $id_product > 0) {
            $this->reviewModel->addReview($id_user, $id_product, $rating, $comment);
            // Si todo sale bien, lo regresamos a sus compras con un mensaje
            header('Location: ' . BASE_PATH . '/mis-compras?msg=review_success');
            exit();
        } else {
            header('Location: ' . BASE_PATH . '/review/'. htmlspecialchars($id_product));
        }
    }
}
