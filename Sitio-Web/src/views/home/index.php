<section class="new-art">
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
                        <a class="image-link" href="<?=BASE_PATH?>/products/<?=$product->id?>">
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

    <div role="tablist" class="carousel-indicator"></div>
</section>

<section class="best-selling-art">
    <h3 class="best-selling-art-title">Artículos mejor vendidos</h3>

    <section class="best-selling-art-carousel">
        <span class="previous-element-button-container">
            <button aria-label="previous" class="previous-element-button" alt="seeMore"></button>
        </span>

        <!-- tarjetas -->
        <div class="best-selling-art-item-container item-container">
            <?php foreach($products as $product) : ?>
                <?php if($product->category === 'bestsellingart'): ?>
                    <div class="card bg-base-100 shadow-sm art-item item">
                        <a class="image-link" href="<?=BASE_PATH?>/products/<?=$product->id?>">
                            <figure class="art-item-image-container">
                                <img src="<?=ASSETS_PATH?>/images/<?=$product->image?>" alt="<?=$product->name?>">  
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

    <div role="tablist" class="carousel-indicator"></div>
</section>

<section class="seasonal-art">
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
                        <a class="image-link" href="<?=BASE_PATH?>/products/<?=$product->id?>">
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

    <div role="tablist" class="carousel-indicator"></div>
</section>