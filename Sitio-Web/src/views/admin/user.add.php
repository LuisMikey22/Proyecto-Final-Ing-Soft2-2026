<section class="create-account-section">
    <h3 class="product-title">🧶Añadir Nuevo Usuario</h3>

    <form class="create-account-form" action="<?= BASE_PATH ?>/admin/users/create" method="post">
        <fieldset class="create-account-fieldset">
            <div class="text-input-container">
                <label class="input-label" for="user">Nombre de usuario</label>
                <input class="bordered-input" id="username" type="text" name="username" placeholder="Usuario" required>
            </div>
            
            <div class="text-input-container">
                <label class="input-label" for="email">Correo electrónico</label>
                <input class="bordered-input" id="email" type="email" name="email" placeholder="Ejemplo@gmail.com" required>
            </div>
            
            <div class="text-input-container">
                <label class="input-label" for="password">Contraseña</label>
                <input class="bordered-input" id="password" type="password" name="password" placeholder="Contraseña" required>
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

            <div class="desc-container">
                <label class="input-label" for="description">Descripción de perfil</label>
                <textarea class="bordered-input" name="description" placeholder="Descripción opcional"></textarea>
            </div>
            
            <div class="action-container">
                <button class="action-button" style="margin: 0;" type="submit">Crear Usuario</button>
                <a class="delete-button" style="width: fit-content; margin: 0;" href="<?=BASE_PATH?>/admin/users">
                    Cancelar
                </a>
            </div>
        </fieldset>
    </form>
</section>