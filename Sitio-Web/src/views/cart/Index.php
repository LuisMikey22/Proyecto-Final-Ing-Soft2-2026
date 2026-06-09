<section style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif;">
    <h2 class="text-3xl font-bold text-teal-950" style="margin-bottom: 2rem;">Tu Carrito de Compras 🛒</h2>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <div style="background-color: #d1fae5; color: #0f766e; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-weight: bold;">
            <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($items)): ?>
        <div class="figure-shadow" style="background: white; padding: 3rem; border-radius: 1.5rem; text-align: center;">
            <p class="text-teal-700 text-lg">Tu carrito está vacío en este momento.</p>
            <a href="<?= BASE_PATH ?>/" class="action-button" style="display: inline-block; margin-top: 1.5rem; text-decoration: none;">Explorar Productos</a>
        </div>
    <?php else: ?>
        <div style="display: flex; gap: 2rem; align-items: center;">
            
            <div style="display: flex; flex-direction: column; width: min-content; padding: 1rem; gap: 1.5rem; max-height: 25vw; overflow-y: scroll;">
                <?php foreach ($items as $item): ?>
                    <div class="figure-shadow" style="background: white; width: fit-content; padding: 1.5rem; border-radius: 1.5rem; display: flex; gap: 1.5rem; align-items: center;">
                        <div style="width: 100px; height: 100px; background-color: #f1f5f9; border-radius: 1rem; overflow: hidden; flex-shrink: 0;">
                            <?php if(!empty($item['image_url'] ?? null)): ?>
                                <img src="<?=ASSETS_PATH?>/images/uploads/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #94a3b8;">🧶</div>
                            <?php endif; ?>
                        </div>
                        
                        <div style="flex-grow: 1;">
                            <h3 class="text-xl font-bold text-teal-950" style="margin: 0 0 0.5rem 0;"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-teal-700" style="margin: 0; font-weight: bold;">$<?= number_format($item['price'], 2) ?> MXN</p>

                            <form action="<?= BASE_PATH ?>/cart" method="POST">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <button type="submit" id="dec" style="color: var(--color-dark-blue);">−</button>
                                    <input id="quantity" class="bordered-input" type="number" name="quantity" min="1" step="1" value="1" readonly
                                            style="width: 70px; padding: 0.5rem; border-radius: 0.5rem; text-align: center; color: var(--color-dark-blue);" readonly>
                                    <button type="submit" id="inc" style="color: var(--color-dark-blue);">+</button>
                                </div>
                            </form>
                        </div>
                        
                        <div>
                            <form action="<?= BASE_PATH ?>/cart/remove/<?= $item['id_cart_item'] ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas quitar este producto?');">
                                <button type="submit" style="background: #fee2e2; color: #991b1b; border: none; padding: 0.75rem 1rem; border-radius: 0.75rem; cursor: pointer; font-weight: bold; transition: 0.2s;">
                                    Quitar 🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="figure-shadow" style="background: white; padding: 2rem; border-radius: 1.5rem; position: sticky; top: 2rem; width: max-content; display: flex; flex-direction: column;">
                <h3 class="text-xl font-bold text-teal-950" style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">Resumen de Pedido</h3>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #64748b;">
                    <span>Subtotal</span>
                    <span>$<?= number_format($total, 2) ?> MXN</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; color: #64748b;">
                    <span>Envío</span>
                    <span>Calculado en checkout</span>
                </div>
                
                <div style="display: flex; justify-content: space-between; font-size: 1.25rem; gap: 0.5rem; margin-bottom: 2rem; border-top: 2px solid #f1f5f9; padding-top: 1rem;">
                    <strong class="text-teal-950">Total a pagar</strong>
                    <strong style="color: var(--color-dark-blue);">$<?= number_format($total, 2) ?> MXN</strong>
                </div>

                <a href="<?= BASE_PATH ?>/checkout" class="action-button" style="display: block; width: 100%; text-align: center; font-size: 1.1rem; padding: 1rem; border-radius: 1rem; text-decoration: none; box-sizing: border-box;">
                    Proceder al Pago 💳
                </a>
            </div>
        </div>
    <?php endif; ?>
</section>

<script>
    const quantity = document.getElementById('quantity');
    const inc = document.getElementById('inc');
    const dec = document.getElementById('dec');

    inc.addEventListener('click', () => {
        quantity.value = parseInt(quantity.value) + 1;
    });

    dec.addEventListener('click', () => {
        if (parseInt(quantity.value) > 1) {
            quantity.value = parseInt(quantity.value) - 1;
        }
    });
</script>