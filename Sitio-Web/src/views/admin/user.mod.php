<section class="product-section">
    <h3 class="product-title">Modificar Usuario</h3>

    <form action="<?= BASE_PATH ?>/admin/users/update/<?= $user->id_user ?>" method="POST" class="create-product-form">
        <fieldset class="product-fieldset">

            <input class="bordered-input" type="hidden" name="id" value="<?= $user->id_user ?>">

            <div class="text-input-container">
                <label class="input-label" for="user">Nombre</label>
                <input class="bordered-input" id="user" type="text" name="user" value="<?= htmlspecialchars($user->username) ?>" required>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="email">Email</label>
                <input class="bordered-input" id="email" type="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="rol">Rol</label>
                <select class="bordered-input" id="role" type="text" name="role" required>
                    <optgroup label="Roles">
                        <option value="client">cliente</option>
                        <option value="admin">Administrador</option>
                    </optgroup> 
                </select>
            </div>

            <div class="action-container">
                <button class="action-button" type="submit">Guardar</button>
                <a class="delete-button" style="width: fit-content; margin: 0;" href="<?=BASE_PATH?>/admin/users">
                    Cancelar
                </a>
            </div>
        </fieldset>
    </form>
</section>