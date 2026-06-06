<section style="max-width: 1000px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif;">
    <h2 class="text-3xl font-bold text-teal-950" style="margin-bottom: 2rem;">Gestión de Inventario 📋</h2>

    <?php if (isset($_SESSION['success_msg'])): ?>
        <div style="background-color: #d1fae5; color: #0f766e; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-weight: bold;">
            ✅ <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <div class="figure-shadow" style="background: white; border-radius: 1.5rem; overflow: hidden;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; background: #f8fafc; padding: 1rem 1.5rem; font-weight: bold; color: #475569; border-bottom: 2px solid #e2e8f0;">
            <div>Producto</div>
            <div style="text-align: center;">Precio</div>
            <div style="text-align: center;">Stock Actual</div>
            <div style="text-align: center;">Añadir Piezas</div>
        </div>

        <div style="display: flex; flex-direction: column;">
            <?php foreach ($products as $p): ?>
                <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr; padding: 1.5rem; border-bottom: 1px solid #f1f5f9; align-items: center;">
                    
                    <div>
                        <strong class="text-teal-950 text-lg" style="display: block;"><?= htmlspecialchars($p['name']) ?></strong>
                        <?php if ($p['stock'] <= 3): ?>
                            <span style="display: inline-block; margin-top: 0.5rem; background: #fee2e2; color: #991b1b; padding: 0.2rem 0.6rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: bold; animation: pulse 2s infinite;">
                                ⚠️ Stock Bajo (Quedan <?= $p['stock'] ?>)
                            </span>
                        <?php endif; ?>
                    </div>

                    <div style="text-align: center; font-weight: normal; color: var(--color-dark-blue);">
                        $<?= number_format($p['price'], 2) ?>
                    </div>

                    <div style="text-align: center; font-size: 1.2rem; font-weight: normal; color: <?= $p['stock'] <= 3 ? '#991b1b' : 'var(--color-dark-blue)' ?>;">
                        <?= $p['stock'] ?>
                    </div>

                    <div>
                        <form action="<?= BASE_PATH ?>/admin/inventory/add" method="POST" onsubmit="return confirmarStock(this);" style="display: flex; gap: 0.5rem; justify-content: center;">
                            <input type="hidden" name="id_product" value="<?= $p['id_product'] ?>">
                            <input type="hidden" name="product_name" value="<?= htmlspecialchars($p['name']) ?>">
                            
                            <input class="bordered-input" type="number" name="quantity" min="1" required placeholder="+0" style="width: 70px; padding: 0.5rem; border-radius: 0.5rem; text-align: center; color: var(--color-dark-blue);">
                            
                            <button type="submit" style="background: #0ea5e9; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; font-weight: bold; transition: 0.2s;">
                                Añadir
                            </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    function confirmarStock(formulario) {
        // Obtenemos los valores exactos que el usuario escribió
        const cantidad = formulario.quantity.value;
        const nombreProducto = formulario.product_name.value;
        
        // Lanzamos la alerta nativa del navegador
        const confirmacion = confirm(`¿Estás seguro que deseas añadir ${cantidad} piezas nuevas al stock de "${nombreProducto}"?`);
        
        // Si el usuario le da a "Cancelar", devolvemos false y el formulario se detiene.
        return confirmacion;
    }
</script>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
</style>
