<section style="max-width: 900px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif;">
    <h2 class="text-3xl font-bold text-teal-950" style="margin-bottom: 2rem;">Mis Compras 📦</h2>

    <?php if (empty($orders)): ?>
        <p style="text-align: center; color: #64748b; font-size: 1.2rem;">Aún no has realizado ninguna compra.</p>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($orders as $o): ?>
                <div class="figure-shadow" style="background: white; padding: 1.5rem; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Pedido #000<?= $o['id_order'] ?> • <?= date('d M, Y', strtotime($o['created_at'])) ?></p>
                        <strong class="text-teal-950 text-xl">Total: $<?= number_format($o['total_amount'], 2) ?></strong>
                        
                        <?php if($o['status'] === 'return_requested'): ?>
                            <span style="background: #fee2e2; color: #991b1b; padding: 0.2rem 0.5rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: bold; margin-left: 1rem;">En proceso de devolución</span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= BASE_PATH ?>/tracking/<?= $o['id_order'] ?>" class="action-button" style="text-decoration: none; border-radius: 0.5rem; padding: 0.75rem 1.5rem;">
                        Ver Seguimiento 🚚
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
