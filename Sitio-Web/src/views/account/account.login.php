<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION['login_error'])) {
        echo '<div class="home-desc" style="padding: 1em 0 0 0;">'.e($_SESSION['login_error']).'</div>';
        unset($_SESSION['login_error']);
    }
?>

<section class="login-account-section">
    <h3 class="product-title">Iniciar sesión</h3>

    <form class="login-account-form" action="<?=BASE_PATH?>/login" method="post">
        <fieldset class="login-account-fieldset">
            <div class="text-input-container">
                <label class="input-label" for="email">Email</label>
                <input class="bordered-input" id="email" type="email" name="email" placeholder="Ejemplo@gmail.com" required>
            </div>
            
            <div class="text-input-container">
                <label class="input-label" for="password">Contraseña</label>
                <input class="bordered-input" id="password" type="password" name="password" placeholder="Contraseña" required>
            </div>

            <div class="action-container">
                <a class="small-link" href="<?=BASE_PATH?>/account/register">¿Aún no te has registrado? Crea una cuenta</a>
                <button class="action-button" type="submit">Iniciar sesión</button>
            </div>
        </fieldset>
    </form>
</section>