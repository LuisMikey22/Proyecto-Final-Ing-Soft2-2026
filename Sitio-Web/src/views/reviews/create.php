<section style="max-width: 600px; margin: 3rem auto; padding: 2rem; font-family: sans-serif; background: white; border-radius: 1.5rem;" class="figure-shadow">
    <h2 class="text-3xl font-bold text-teal-950" style="text-align: center; margin-top: 0;">¿Qué te pareció el producto?</h2>
    <p style="text-align: center; color: #64748b; margin-bottom: 2rem;">Tu opinión ayuda a otros a elegir mejor.</p>

    <form action="<?= BASE_PATH ?>/review/submit" method="POST">
        <input type="hidden" name="id_product" value="<?= $id_product ?>">
        <input type="hidden" name="rating" id="rating-input" value="0">

        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 2rem; flex-direction: row-reverse;" id="star-container">
            <button type="button" class="star-btn" data-value="5" required>★</button>
            <button type="button" class="star-btn" data-value="4" required>★</button>
            <button type="button" class="star-btn" data-value="3" required>★</button>
            <button type="button" class="star-btn" data-value="2" required>★</button>
            <button type="button" class="star-btn" data-value="1" required>★</button>
        </div>

        <div class="desc-container">
            <label class="input-label" for="description">Escribe tu reseña</label>
            <textarea class="bordered-input" name="comment" placeholder="Ej. El estambre es súper suave y el color es idéntico a la foto..." required></textarea>
        </div>

        <button type="submit" class="action-button" style="width: 100%; font-size: 1.1rem; padding: 1rem; border-radius: 1rem;">
            Enviar Reseña 🚀
        </button>
    </form>
</section>

<style>
    /* Usar flex-direction: row-reverse arriba para que el hover ilumine hacia atrás */
    .star-btn { font-size: 4rem; color: #e2e8f0; background: none; border: none; cursor: pointer; transition: 0.2s; padding: 0; outline: none; }
    
    /*iluminar estrellas anteriores al pasar el mouse */
    #star-container button:hover,
    #star-container button:hover ~ button { color: #fcd34d; }
    
    /* Clase para cuando le dan clic */
    .star-btn.active { color: #fbbf24; }
</style>

<script>
    const stars = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('rating-input');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const ratingValue = this.getAttribute('data-value');
            ratingInput.value = ratingValue; // Guardar el valor en el input oculto
            
            // Limpiar todas las estrellas y pintar solo las seleccionadas
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= ratingValue) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
</script>
