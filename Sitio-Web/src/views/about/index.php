<?php
    $isLoggedIn = isset($_SESSION['id_user']);
?>

<section class="home-info">
    <h1 class="home-title">Estambres de Calidad</h1>
    
    <h4 class="home-desc">
        Encuentra los mejores estambres, colores vibrantes y texturas perfectas para tus proyectos de tejido.
    </h4>

    <h4 class="home-desc">
        Una gran variedad de colores con miles de tonos para tus creaciones.
    </h4>

    <h4 class="home-desc">
        Materiales premium con texturas suaves y duraderas.
    </h4>

    <a href="<?=BASE_PATH?>">
        <button class="action-button">Ver Productos</button>
    </a>
    <h2 class="home-subtitle">¿Por qué elegirnos?</h2>
</section>

<section class="shipping-info-banner">
    <div class="info-banner-statement">
        <span class="star-review-icon">
            <img src="<?=ASSETS_PATH?>/images/starIcon.svg" alt="star-review-icon">
        </span>

        <h4 class="banner-text">
            <a href="<?= BASE_PATH ?>/reviews/all">
                4,000+ reseñas positivas
            </a>
        </h4>
    </div>

    <div class="info-banner-statement">
        <span class="shipping-icon">
            <img src="<?=ASSETS_PATH?>/images/truckIcon.svg" alt="shipping-icon">
        </span>

        <h4 class="banner-text"> 
            <?php if ($isLoggedIn) : ?>
                <a href="<?= BASE_PATH ?>/mis-compras">
                    Envío gratis en México en compras mayores a $500
                </a>
            <?php else: ?>
                <a href="<?=BASE_PATH?>/login">
                    Envío gratis en México en compras mayores a $500
                </a>
            <?php endif; ?>
        </h4> 
    </div>

    <div class="info-banner-statement">
        <span class="return-icon">
            <img src="<?=ASSETS_PATH?>/images/boxIcon.svg" alt="return-icon">
        </span>

        <h4 class="banner-text">
            <?php if ($isLoggedIn) : ?>
                <a href="<?= BASE_PATH ?>/mis-compras">
                    Cambios o devoluciones 
                </a>
            <?php else: ?>
                <a href="<?=BASE_PATH?>/login">
                    Cambios o devoluciones 
                </a>
            <?php endif; ?>
        </h4> 
    </div>
</section>