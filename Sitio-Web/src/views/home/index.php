<section id="new-art" class="new-art">
    <h3 class="new-art-title">Nuevos artículos</h3>

    <section class="new-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="new-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'newart'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>

<section id="best-selling-art" class="best-selling-art">
    <h3 class="best-selling-art-title">Artículos mejor vendidos</h3>

    <section class="best-selling-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div  class="best-selling-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'bestsellingart'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>

<section id="seasonal-art" class="seasonal-art">
    <h3 class="seasonal-art-title">Artículos de temporada</h3>

    <section class="seasonal-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="seasonal-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'seasonalart'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>

<section class="new-art">
    <h3 class="new-art-title">Estambres</h3>

    <section id="yarn-art" class="new-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="new-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'yarn'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>

<section class="best-selling-art">
    <h3 class="best-selling-art-title">Patrones</h3>

    <section id="pattern-art" class="best-selling-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="best-selling-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'pattern'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>

<section class="seasonal-art">
    <h3 class="seasonal-art-title">Ganchos</h3>

    <section id="hook-art" class="seasonal-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="seasonal-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'hook'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?= BASE_PATH ?>/products/<?= $product->id_product ?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>">  
                            </figure>

                            <div class="card-body art-desc-container">
                                <p><h4 class="card-title art-desc"><?=$product->name?></h4></p>
                                <p><h4 class="card-actions art-price">$<?=$product->price?> MXN</h4></p> 
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="next-element-button-container">
            <button aria-label="next" class="next-element-button" alt="seeMore"></button>
        </span>
    </section>
</section>