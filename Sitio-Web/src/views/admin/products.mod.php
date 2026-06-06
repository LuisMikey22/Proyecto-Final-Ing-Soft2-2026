<section class="product-section">
    <h3 class="product-title">Modificar producto</h3>

    <form action="<?= BASE_PATH ?>/admin/products/update/<?= $product->id_product ?>" method="POST" enctype="multipart/form-data" class="create-product-form">
        <fieldset class="product-fieldset">
            <div class="text-input-container">
                <label class="input-label" for="name">Nombre</label>
                <input class="bordered-input" type="text" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="id_category">Categoría</label>
                <select class="bordered-input" id="id_category" name="id_category" required>
                    <optgroup label="Selecciona una categoría">
                        <option value="1" <?= $product->id_category == 1 ? 'selected' : '' ?>>Artículo nuevo</option>
                        <option value="2" <?= $product->id_category == 2 ? 'selected' : '' ?>>Artículo mejor vendido</option>
                        <option value="3" <?= $product->id_category == 3 ? 'selected' : '' ?>>Artículo de temporada</option>
                        <option value="4" <?= $product->id_category == 4 ? 'selected' : '' ?>>Estambres</option>
                        <option value="5" <?= $product->id_category == 5 ? 'selected' : '' ?>>Patrones</option>
                        <option value="6" <?= $product->id_category == 6 ? 'selected' : '' ?>>Ganchos</option>
                    </optgroup>
                </select>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="price">Precio</label>
                <input class="bordered-input" type="number" step="0.01" min="0" name="price" value="<?= htmlspecialchars($product->price) ?>" required>
            </div>

            <div class="desc-container">
                <label class="input-label" for="description">Descripción</label>
                <textarea class="bordered-input" name="description" required><?= htmlspecialchars($product->description) ?></textarea>
            </div>

            <div class="desc-container">
                <label class="input-label" for="image">Imagen actual</label>
                <figure class="art-item-image-container" style="box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
                    <img src="<?= ASSETS_PATH ?>/images/<?= htmlspecialchars($product->image) ?>" >  
                </figure>
            </div>

             <div class="file-input-container">
                <label class="input-label" for="image">Cambiar imagen (opcional)</label>
                <input class="bordered-input" type="file" name="image">
            </div>

            <div class="action-container">
                <button class="action-button" type="submit">Guardar cambios</button>
                <a class="delete-button" style="width: fit-content; margin: 0; text-decoration: none; text-align: center;" href="<?= BASE_PATH ?>/admin/products">
                    Cancelar
                </a>
            </div>
        </fieldset>
    </form>
</section>