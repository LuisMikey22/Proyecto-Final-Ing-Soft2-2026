<section style="max-width: 600px; margin: 4rem auto; padding: 2rem; text-align: center; font-family: sans-serif; background: white; border-radius: 2rem;" class="figure-shadow">
    <div style="font-size: 4rem; margin-bottom: 1rem;">✅</div>
    <h2 class="text-3xl font-bold text-teal-950" style="margin-bottom: 1rem;">¡Compra Exitosa!</h2>
    <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 2rem;">
        Tu pago ha sido procesado correctamente (Simulado). <br>
        Número de Pedido: <strong>#000<?= htmlspecialchars($id_order) ?></strong>
    </p>

    <a href="<?= BASE_PATH ?>/tracking/<?= htmlspecialchars($id_order) ?>" style="display: inline-block; background-color: #0ea5e9; color: white; padding: 1rem 2rem; border-radius: 1rem; text-decoration: none; font-weight: bold; font-size: 1.1rem; margin-bottom: 1rem; transition: 0.2s;">
        📦 Seguir Envío
    </a>
    
    <br>
    
    <a href="<?= BASE_PATH ?>/" style="color: #0f766e; text-decoration: underline; font-weight: bold; margin-top: 1rem; display: inline-block;">
        Volver a la tienda
    </a>
</section>
