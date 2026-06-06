<section class="product-section">
    <h3 class="product-title">Agregar producto</h3>

    <form action="<?= BASE_PATH ?>/admin/products/create"method="POST" enctype="multipart/form-data" class="create-product-form">
        <fieldset class="product-fieldset">
            <div class="text-input-container">
                <label class="input-label" for="name">Nombre</label>
                <input class="bordered-input" type="text" name="name" required>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="id_category">Categoría</label>
                <select class="bordered-input" id="id_category" name="id_category" required>
                    <optgroup label="Selecciona una categoría">
                        <option value="1">Artículo nuevo</option>
                        <option value="2">Artículo mejor vendido</option>
                        <option value="3">Artículo de temporada</option>
                        <option value="4">Estambres</option>
                        <option value="5">Patrones</option>
                        <option value="6">Ganchos</option>
                    </optgroup>
                </select>
            </div>

            <div class="text-input-container"> 
                <label class="input-label" for="price">Precio</label>
                <input class="bordered-input" type="number" step="0.01" min="0" name="price" class="input input-bordered w-full" required>
            </div>

            <div class="desc-container">
                <label class="input-label" for="description">Descripción</label>
                <textarea class="bordered-input" name="description" class="textarea textarea-bordered w-full h-28" required></textarea>
            </div>

            <div class="file-input-container">
                <label class="input-label" for="image">Imagen del producto</label>
                <input class="bordered-input" type="file" name="image" required>
            </div>

            <div class="action-container">
                <button class="action-button" type="submit">Agregar producto</button>
                <a class="delete-button" style="width: fit-content; margin: 0;" href="<?=BASE_PATH?>/admin/products">
                    Cancelar
                </a>
            </div>
        </fieldset>
    </form>
</section>
