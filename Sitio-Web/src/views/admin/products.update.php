<section class="home-info">
    <div class="home-info" style="width: auto; margin: auto;">
        <h2 class="home-desc">✔ El producto se actualizó correctamente</h2>

        <h3 class="new-art-title">Detalles del producto actualizado</h2>

        <!-- Imagen -->
        <figure class="art-item-image-container" name="image" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
            <img src="<?= ASSETS_PATH ?>/images/<?= e($product->image) ?>">  
        </figure>

        <!-- Datos -->
        <div class="text-input-container">
            <label class="input-label"><b>Nombre:</b></label> 
            <label class="input-label"><?= htmlspecialchars($product->name) ?></label>
        </div>

        <div class="text-input-container">
            <label class="input-label"><b>Categoría:</b></label> 
            <label class="input-label"><?= htmlspecialchars($product->category) ?></label>
        </div>

        <div class="text-input-container">
            <label class="input-label"><b>Precio:</b></label>
            <label class="input-label">$<?= number_format($product->price, 2) ?></label>
        </div>

        <div class="text-input-container">
            <label class="input-label"><b>Descripción:</b></label> 
            <label class="input-label"><?= nl2br(htmlspecialchars($product->description)) ?></label>
        </div>
    </div>

    <a href="<?= BASE_PATH ?>/products/<?= $product->id ?>">
        <button class="action-button" type="submit">Ir a vista de detalles</button>
    </a>
</section>
