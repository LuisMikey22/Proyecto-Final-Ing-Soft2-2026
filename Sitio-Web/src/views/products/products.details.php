<?php 
    $productDesc = explode(',', htmlspecialchars($product->description));
    $fiberContent = end($productDesc);
    
    $isLoggedIn = isset($_SESSION['id_user']);
?>

<section class="bg-base-100 rounded-3xl product">
    <div class="max-w-6xl mx-auto bg-base-100 flex flex-col lg:flex-row justify-between 
            gap-12 lg:gap-20 p-8 product-container">

        <figure class="flex items-center justify-center 
                lg:w-[50%] w-fit h-fit mx-auto lg:mx-0
                rounded-3xl bg-white figure-shadow">
            
            <img class="w-full h-full object-contain rounded-3xl" 
                src="<?=ASSETS_PATH?>/images/<?=$product->image?>" 
                alt="<?=$product->name?>">
        </figure>

        <div class="w-[320px] flex flex-col justify-center gap-8 lg:w-2/3 w-full">
            <h2 class="text-3xl font-bold text-teal-950"><?=$product->name?></h2>
            <p class="text-sm text-teal-950"><?=$fiberContent?></p>
            <p class="text-2xl font-bold text-teal-950">$<?=$product->price?> MXN</p>

            <?php if($isLoggedIn): ?>
                <form id="addToCartForm" action="<?=BASE_PATH?>/cart/add" method="POST" onsubmit="return showAddedMessage(event)">
                    <input type="hidden" name="product_id" value="<?=$product->id?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="add-button">Añadir al carrito</button>
                </form>
            <?php else: ?>
                <a href="<?=BASE_PATH?>/login" class="add-button" style="display: inline-block; text-align: center; text-decoration: none;">
                    Iniciar sesión para comprar
                </a>
            <?php endif; ?>

            <p class="text-teal-950 font-semibold mt-4"><?=$product->description?></p>
        </div>
    </div>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
        <a class="action-button" style="margin: auto;" href="<?=BASE_PATH?>/admin/products">
            Volver a lista de productos
        </a>
    <?php else: ?>
        <a class="action-button" style="margin: auto;" href="<?=BASE_PATH?>">
            Volver a lista de productos
        </a>
    <?php endif; ?>
</section>

<script>
function showAddedMessage(event) {
    event.preventDefault();
    
    alert('✓ Producto añadido al carrito exitosamente!');
    
    document.getElementById('addToCartForm').submit();
    
    return false;
}
</script>