<?php 
    $urlDestino = isset($_SESSION['id_user']) ? BASE_PATH . "/account/profile/" . $_SESSION['id_user'] : BASE_PATH . "/account/register"; 
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="EstaHambreQueAta">
        <meta name="description" content="EstaHambreQueAta desata la creatividad">

        <link rel="icon" href="<?=ASSETS_PATH?>/images/EHQALogoCropped2.svg" type="image/x-icon">
        
        <link href="<?=BASE_PATH?>/output.css" rel="stylesheet">
        <link href="<?=BASE_PATH?>/css/style.css" rel="stylesheet"> 

        <title>EstaHambreQueAta</title>
    </head>

    <script> 
        const BASE_PATH = "<?=BASE_PATH?>";
        const ASSETS_PATH = "<?=ASSETS_PATH?>";
    </script> 
    
    <script src="<?=BASE_PATH?>/scripts/script.js" defer></script>

    <body>
        <header>
            <input type="checkbox" id="menu-toggle" class="menu-toggle">
                        
            <nav>
                <div id="search-bar-container" class="search-bar-container-hidden">
                    <form id="search-bar-form" class="search-bar-form" action="<?=BASE_PATH?>/search" method="get">
                        <button id="search-bar-button" class="search-bar-button" type="submit" title="Buscar">
                            <img src="<?=ASSETS_PATH?>/images/searchIcon.svg" alt="search">
                        </button>

                        <input id="search-bar-input" class="search-bar-input" name="q" type="text" placeholder="Buscar" required>

                        <button id="close-button" class="close-button" type="button" name="close" title="Cerrar">
                            <img src="<?=ASSETS_PATH?>/images/closeIconDark.svg" alt="close">
                        </button>
                    </form>
                </div>

                <label for="menu-toggle" class="menu-button"><img id="menu-button-image" src="<?=ASSETS_PATH?>/images/menuIcon.svg" alt="menu"></label>
                
                <a class="logo-link" href="<?=BASE_PATH?>/about">
                    <span class="svg-logo">
                        <img src="<?=ASSETS_PATH?>/images/EHQALogoIcon.svg" alt="EstaHambreQueAta-logo">
                    </span>
                </a>

                <ul class="nav-link-list">
                    <li><b>Estambres</b>
                        <ul class="nav-link-sub-list">
                            <a><li>Algodón</li></a>
                            <a><li>Lana</li></a>
                            <a><li>Lana merino</li></a>
                            <a><li>Lino</li></a>
                        </ul>
                    </li>

                    <li><b>Patrones</b>
                        <ul class="nav-link-sub-list">
                            <a><li>Principiantes</li></a>
                            <a><li>Intermedios</li></a>
                            <a><li>Avanzados</li></a>
                        </ul>
                    </li>

                    <li><b>Accesorios</b>
                        <ul class="nav-link-sub-list">
                            <a href="<?=BASE_PATH?>#"><li>Recién agregados</li></a>
                            <a href="<?=BASE_PATH?>#new-art-item-container"><li>Mejor vendidos</li></a>
                            <a href="<?=BASE_PATH?>#best-art-item-container"><li>De temporada</li></a>
                        </ul>
                    </li>

                    <li><b>Ganchos</b>
                        <ul class="nav-link-sub-list">
                            <a><li>Metal</li></a>
                            <a><li>Bambú</li></a>
                            <a><li>Plástico</li></a>
                            <a><li>Vidrio</li></a>
                        </ul>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li class="admin-link-visible"><b>Admin</b>
                            <ul class="nav-link-sub-list">
                                <a href="<?=BASE_PATH?>/admin/products"><li>Productos</li></a>
                                <a href="<?=BASE_PATH?>/admin/users"><li>Usuarios</li></a>
                                <a href="<?= BASE_PATH ?>/admin/dashboard"><li>Estadísticas</li></a>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <div class="nav-button-bar">
                    <button id="search-button" class="search-button" title="Buscar"><img src="<?=ASSETS_PATH?>/images/searchIcon.svg" alt="search"></button>
                    <button class="account-button" title="Perfil"><a href="<?= $urlDestino ?>"><img src="<?=ASSETS_PATH?>/images/userIcon.svg" alt="account"></a></button>
                    <button class="cart-button" title="Bolsa de compras"><a href="<?=BASE_PATH?>"><img src="<?=ASSETS_PATH?>/images/cartIcon.svg" alt="cart"></a></button>
                </div>
            </nav>

            <div class="nav-decoration-line"></div>

            <div class="breadcrumbs max-w-xs text-sm">
        </header>