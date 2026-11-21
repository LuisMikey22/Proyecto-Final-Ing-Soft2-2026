<section style="max-width: 800px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif;">
    <h2 class="text-3xl font-bold text-teal-950" style="margin-bottom: 2rem;">Finalizar Compra 🔒</h2>

    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem;">
        <div class="figure-shadow" style="background: white; padding: 2rem; border-radius: 1.5rem;">
            <h3 class="text-xl font-bold text-teal-950" style="margin-top: 0;">Método de Pago</h3>
            <p style="color: #64748b; margin-bottom: 1.5rem; font-size: 0.9rem;">(Módulo de prueba simulado)</p>
            
            <form action="<?= BASE_PATH ?>/checkout/process" method="POST">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-weight: bold; margin-bottom: 0.5rem; color: #0f766e;">Opciones:</label>
                    <select name="payment_method" style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; color: var(--color-darker-blue);">
                        <option value="tarjeta">💳 Tarjeta de Crédito / Débito</option>
                        <option value="paypal">🅿️ PayPal (Simulado)</option>
                        <option value="transferencia">🏦 Transferencia SPEI</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-weight: bold; margin-bottom: 0.5rem; color: #0f766e;">Número de Tarjeta de Prueba:</label>
                    <input type="text" value="4000 1234 5678 9010" readonly style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; background: #f8fafc; color: var(--color-darker-blue);">
                </div>

                <button type="submit" class="action-button" style="width: 100%; font-size: 1.1rem; padding: 1rem; border-radius: 1rem; margin-top: 1rem;">
                    Confirmar y Pagar $<?= number_format($total, 2) ?>
                </button>
            </form>
        </div>

        <div class="figure-shadow" style="background: #f8fafc; padding: 2rem; border-radius: 1.5rem; height: fit-content;">
            <h3 class="text-xl font-bold text-teal-950" style="margin-top: 0; margin-bottom: 1rem;">Resumen</h3>
            <?php foreach ($items as $item): ?>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #475569; font-size: 0.9rem;">
                    <span><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></span>
                    <span>$<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                </div>
            <?php endforeach; ?>
            <hr style="border: 0; border-top: 1px solid #cbd5e1; margin: 1rem 0;">
            <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; color: #0f766e;">
                <span>Total:</span>
                <span>$<?= number_format($total, 2) ?> MXN</span>
            </div>
        </div>
    </div>
</section>
