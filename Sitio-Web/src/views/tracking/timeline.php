<style>
    /* CSS Mágico para la línea de tiempo vertical */
    .timeline { border-left: 3px solid #e2e8f0; margin-left: 1rem; padding-left: 2rem; position: relative; margin-top: 2rem; }
    .tl-step { margin-bottom: 2.5rem; position: relative; }
    .tl-step::before { content: ''; position: absolute; left: -2.6rem; top: 0; width: 1rem; height: 1rem; border-radius: 50%; background: #0f766e; border: 3px solid white; box-shadow: 0 0 0 2px #0f766e; }
    .tl-step.pending::before { background: #e2e8f0; box-shadow: 0 0 0 2px #e2e8f0; }
    .tl-step.pending h4, .tl-step.pending p { color: #94a3b8; }
</style>

<section style="max-width: 700px; margin: 2rem auto; padding: 0 1rem; font-family: sans-serif;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <h2 class="text-3xl font-bold text-teal-950" style="margin: 0;">Seguimiento #000<?= $order['id_order'] ?></h2>
        <a href="<?= BASE_PATH ?>/mis-compras" style="color: #0f766e; text-decoration: none; font-weight: bold;">← Volver</a>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] === 'return_success'): ?>
        <div style="background-color: #fef2f2; color: #991b1b; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-weight: bold; border: 1px solid #fecaca;">
            ✅ Hemos recibido tu solicitud de devolución. Te enviaremos una guía de paquetería a tu correo pronto.
        </div>
    <?php endif; ?>

    <div class="figure-shadow" style="background: white; padding: 3rem; border-radius: 1.5rem; position: relative;">
        
        <?php if($order['status'] === 'return_requested' || $order['status'] === 'returned'): ?>
            <div style="text-align: center; padding: 2rem 0;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🔙</div>
                <h3 class="text-2xl font-bold text-teal-950">Devolución en Curso</h3>
                <p style="color: #64748b;">Tu dinero ($<?= number_format($order['total_amount'], 2) ?>) será reembolsado una vez que recibamos el paquete en nuestro almacén.</p>
            </div>
        <?php else: ?>
            <h3 class="text-xl font-bold text-teal-950">Estado del Envío</h3>
            <div class="timeline">
                <div class="tl-step">
                    <h4 style="margin: 0; color: #0f766e; font-size: 1.1rem;">Pago Aprobado 💳</h4>
                    <p style="margin: 0.2rem 0 0 0; color: #475569; font-size: 0.9rem;"><?= $fechas['aprobado'] ?></p>
                </div>
                
                <div class="tl-step">
                    <h4 style="margin: 0; color: #0f766e; font-size: 1.1rem;">En preparación 📦</h4>
                    <p style="margin: 0.2rem 0 0 0; color: #475569; font-size: 0.9rem;"><?= $fechas['preparacion'] ?> - Vendedor preparando paquete</p>
                </div>

                <div class="tl-step pending">
                    <h4 style="margin: 0; font-size: 1.1rem;">En camino 🚚</h4>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.9rem;">Llegará entre el <?= $fechas['camino'] ?> y el <?= $fechas['entrega'] ?></p>
                </div>

                <div class="tl-step pending" style="margin-bottom: 0;">
                    <h4 style="margin: 0; font-size: 1.1rem;">Entregado 🏠</h4>
                    <p style="margin: 0.2rem 0 0 0; font-size: 0.9rem;">Esperando confirmación del repartidor</p>
                </div>
            </div>

            <hr style="border: 0; border-top: 2px dashed #f1f5f9; margin: 3rem 0 2rem 0;">
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <div>
                    <strong class="text-teal-950">¿Tuviste un problema?</strong>
                    <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Tienes 30 días desde tu compra para devolverlo gratis.</p>
                </div>
                <form action="<?= BASE_PATH ?>/returns/<?= $order['id_order'] ?>" method="POST" onsubmit="return confirm('¿Seguro que deseas iniciar una devolución para este pedido?');">
                    <button type="submit" style="background: white; color: #0ea5e9; border: 2px solid #0ea5e9; padding: 0.75rem 1.5rem; border-radius: 0.75rem; cursor: pointer; font-weight: bold; transition: 0.2s;">
                        Devolver gratis
                    </button>
                </form>
            </div>
        <?php endif; ?>

    </div>
</section>
