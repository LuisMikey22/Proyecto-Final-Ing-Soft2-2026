<?php

class Review {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Guarda la reseña con estrellas y comentario en la base de datos
     */
    public function addReview($id_user, $id_product, $rating, $comment) {
        try {
            // Guardar el ID del usuario, el producto, calificación (1-5), el texto y la fecha
            $stmt = $this->pdo->prepare("INSERT INTO reviews (id_user, id_product, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
            return $stmt->execute([$id_user, $id_product, $rating, $comment]);
        } catch(PDOException $e) {
            // Detector de errores: Si la tabla no existe o se llama diferente, nos avisará.
            die("ERROR AL GUARDAR RESEÑA: " . $e->getMessage() . "<br><br>👉 Pista: Revisa si tu tabla se llama 'reviews' o 'resenas' y si tiene las columnas correctas.");
        }
    }

    /**
     * Obtiene todas las reseñas de la tienda con datos del usuario y producto
     */
    public function getAllReviews() {
        try {
            $stmt = $this->pdo->query("
                SELECT r.rating, r.comment, r.created_at, 
                       u.username, u.email, p.name as product_name
                FROM reviews r
                JOIN users u ON r.id_user = u.id_user
                JOIN products p ON r.id_product = p.id_product
                ORDER BY r.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error al obtener reseñas: " . $e->getMessage());
            return [];
        }
    }

}
