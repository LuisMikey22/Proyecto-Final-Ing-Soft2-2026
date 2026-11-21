<?php
    // Valores previos y error de validación enviados por el controlador (si los hay)
    $error = $error ?? null;
    $old = $old ?? ['username' => '', 'email' => '', 'description' => ''];
?>

<section class="create-account-section">
    <h3 class="product-title">🧶Por favor complete todos los campos:</h3>

    <?php if (!empty($error)): ?>
        <div class="home-desc" style="color: var(--color-danger); padding: 0.5em 0;"><?= e($error) ?></div>
    <?php endif; ?>

    <form class="create-account-form" action="<?= BASE_PATH ?>/account/register" method="post">
        <fieldset class="create-account-fieldset">
            <div class="text-input-container">
                <label class="input-label" for="user">Nombre de usuario</label>
                <input class="bordered-input" id="user" type="text" name="username" placeholder="Usuario" maxlength="100" value="<?= e($old['username']) ?>" required>
            </div>
            
            <div class="text-input-container">
                <label class="input-label" for="email">Email</label>
                <input class="bordered-input" id="email" type="email" name="email" placeholder="Ejemplo@gmail.com" value="<?= e($old['email']) ?>" required>
            </div>
            
            <div class="text-input-container">
                <label class="input-label" for="password">Contraseña (mínimo 8 caracteres)</label>
                <input class="bordered-input" id="password" type="password" name="password" placeholder="Contraseña" minlength="8" required>
            </div>

            <div class="text-input-container">
                <label class="input-label" for="rol">Rol</label>
                <?php if (esAdmin()): ?>
                    <!-- Solo el administrador ve el selector de roles (crear cuentas admin desde el panel) -->
                    <select class="bordered-input" id="rol" name="role" required>
                        <optgroup label="Roles">
                            <option value="client">cliente</option>
                            <option value="admin">Administrador</option>
                        </optgroup>
                    </select>
                <?php else: ?>
                    <input class="bordered-input" id="rol" type="text" value="Cliente" readonly style="background-color: #f0f0f0; cursor: not-allowed; opacity: 0.7;">
                <?php endif; ?>
            </div>
            
            <div class="desc-container">
                <label class="input-label" for="description">Descripción de perfil</label>
                <textarea class="bordered-input" name="description" placeholder="Descripción"><?= e($old['description']) ?></textarea>
            </div>

            <div class="action-container">
                <a class="small-link" href="<?=BASE_PATH?>/login">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                <button class="action-button" type="submit">Registrarme</button>
            </div>
        </fieldset>
    </form>
</section>
