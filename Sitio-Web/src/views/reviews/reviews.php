<section class="rounded-3xl product" style="margin-top: 2rem; margin-bottom: 2rem;"  style="padding-top: 100px; min-height: 80vh; padding-bottom: 2rem;">
    <div class="w-full mx-auto bg-base-100 flez flex-row lg:flex-row justify-between gap-12 lg:gap-20 p-16 product-container" style="width: 28rem; gap: 1rem;display: flex;flex-direction: column;">
        
        <div class="flex justify-between items-center border-b-2 border-slate-200 pb-4 mb-8">
            <h2 class="text-3xl font-bold text-teal-950">Panel de Reseñas ⭐</h2>
        </div>

        <?php if (empty($reviews)): ?>
            <div class="text-center py-12 bg-white rounded-3xl figure-shadow">
                <span class="text-6xl block mb-4">📭</span>
                <p class="text-xl text-slate-500 font-medium">Aún no hay reseñas registradas en la tienda.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php foreach ($reviews as $r): ?>
                    <div class="product-action-container" style="flex-direction: column;">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <div class="text-teal-950  text-xl tracking-widest">
                                    <?php 
                                        // Imprime estrellas llenas y vacías matemáticamente
                                        echo str_repeat('★', $r['rating']) . str_repeat('☆', 5 - $r['rating']); 
                                    ?>
                                </div>
                                <span class="text-xs font-bold text-teal-950  bg-slate-100 px-2 py-1 rounded-md">
                                    <?= date('d M, Y', strtotime($r['created_at'])) ?>
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-teal-950 leading-tight mb-2">
                                📦 <?= htmlspecialchars($r['product_name']) ?>
                            </h3>

                            <p class="text-teal-950 italic mb-4 text-sm" style="quotes: '“' '”';">
                                "<?= empty($r['comment']) ? 'Sin comentario escrito.' : nl2br(htmlspecialchars($r['comment'])) ?>"
                            </p>
                        </div>

                        <div class="border-t border-slate-100 pt-3 mt-auto">
                            <p class="text-sm font-bold text-teal-950">👤 <?= htmlspecialchars($r['username']) ?></p>
                            <a href="mailto:<?= htmlspecialchars($r['email']) ?>" class="text-xs text-teal-950  hover:text-teal-600 transition-colors">
                                ✉️ Contactar al cliente
                            </a>
                        </div>
                        
                    </div>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>
</section>
