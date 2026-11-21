<?php
    $isLoggedIn = isset($_SESSION['id_user']);
?>
   
   <footer>
        <div class="footer-links-bar">
            <div class="footer-brand-links-bar">
                <a class="logo-link" href="<?=BASE_PATH?>/#"><span class="svg-logo">
                    <img src="<?=ASSETS_PATH?>/images/EHQALogoText.svg" alt="EstaHambreQueAta-logo">
                </span></a>

                <div class="social-media-links">
                    <button class="instagram-button" title="https://www.instagram.com/estahambrequeata/"><a target="_blank" href="https://www.instagram.com/estahambrequeata/"><img src="<?=ASSETS_PATH?>/images/instagramIcon.svg" alt="Instagram"></a></button>
                    <!--<button class="youtube-button" title="Youtube"><img src="<?=ASSETS_PATH?>/images/youtubeIcon.svg" alt="Youtube"></button>-->
                </div>
            </div>

            <div class="about-links-container">
                <h4>Acerca de</h4>
                <ul class="about-list">
                    <li><a href="<?=BASE_PATH?>/about">Nosotros</a></li>
                    <!--<li><a>Política de privacidad</a></li>
                    <li><a>Términos y condiciones</a></li>
                    <li><a>Blog</a></li>
                </ul>--> 
            </div>

            <div class="help-links-container">
                <h4>Ayuda</h4>
                <ul class="help-list">
                    <!--<li><a href="<?= BASE_PATH ?>/chatbot" class="menu-link">Ayuda y contacto</a></li>
                    <li><a>Tutoriales</a></li>-->
                    <li><a href="<?= BASE_PATH ?>/chatbot" class="menu-link">Preguntas Frecuentes</a></li>
                    <!--<li><a>Accesibilidad</a></li>-->
                </ul> 
            </div>

            <div class="store-links-container">
                <h4>Tienda</h4>
                <ul class="store-list">
                    <!--<li><a>Envíos nacionales</a></li>
                    <li><a>Envios internacionales</a></li>-->
                    <?php if ($isLoggedIn) : ?>
                        <li><a href="<?= BASE_PATH ?>/mis-compras">Cambios y devoluciones</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_PATH ?>/mis-compras">Cambios y devoluciones</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer-copyright-content">
            <small class="copyright-content">
                <p>© 2026, EstaHambreQueAta</p>
                <p>
                    Boulevard Forjadores S/N entre Calle Av. Universidad y Calle Félix Agramont Cota Col. Universitario. La Paz, Baja California Sur, México
                </p>
            </small>
        </div>
    </footer>

    </body>
</html>