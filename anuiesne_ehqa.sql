-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-06-2026 a las 05:19:44
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `anuiesne_ehqa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id_cart` bigint(20) NOT NULL COMMENT 'ID del carrito',
  `id_user` bigint(20) NOT NULL COMMENT 'Dueño del carrito',
  `status` varchar(50) NOT NULL DEFAULT 'active' COMMENT 'Estados: active, ordered, abandoned',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Sesiones de compra persistentes';

--
-- Volcado de datos para la tabla `carts`
--

INSERT INTO `carts` (`id_cart`, `id_user`, `status`, `created_at`, `updated_at`) VALUES
(1, 23, 'completed', '2026-06-04 01:03:46', '2026-06-04 01:43:10'),
(2, 23, 'completed', '2026-06-04 01:43:58', '2026-06-04 01:55:03'),
(3, 23, 'completed', '2026-06-04 01:55:07', '2026-06-04 02:00:12'),
(4, 23, 'completed', '2026-06-04 02:32:33', '2026-06-04 02:32:58'),
(5, 23, 'completed', '2026-06-04 02:37:51', '2026-06-04 12:04:38'),
(6, 23, 'completed', '2026-06-04 20:07:08', '2026-06-05 01:19:38'),
(7, 23, 'active', '2026-06-05 01:34:24', '2026-06-05 01:34:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `id_cart_item` bigint(20) NOT NULL COMMENT 'ID del renglón en el carrito',
  `id_cart` bigint(20) NOT NULL COMMENT 'Carrito asociado',
  `id_product` bigint(20) NOT NULL COMMENT 'Producto agregado',
  `quantity` int(11) NOT NULL DEFAULT 1 COMMENT 'Cantidad solicitada',
  `price_at_addition` decimal(10,2) NOT NULL COMMENT 'Precio congelado al momento de agregar',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Detalle de los artículos dentro de un carrito';

--
-- Volcado de datos para la tabla `cart_items`
--

INSERT INTO `cart_items` (`id_cart_item`, `id_cart`, `id_product`, `quantity`, `price_at_addition`, `created_at`) VALUES
(2, 1, 10, 1, 95.00, '2026-06-04 01:41:08'),
(3, 1, 11, 1, 125.00, '2026-06-04 01:41:33'),
(4, 2, 3, 1, 80.00, '2026-06-04 01:43:58'),
(5, 3, 3, 1, 80.00, '2026-06-04 01:55:07'),
(6, 4, 10, 2, 95.00, '2026-06-04 02:32:33'),
(7, 5, 10, 2, 95.00, '2026-06-04 02:37:51'),
(8, 5, 11, 1, 125.00, '2026-06-04 12:02:49'),
(9, 6, 3, 4, 80.00, '2026-06-04 20:07:08'),
(10, 6, 5, 1, 90.00, '2026-06-04 23:39:58'),
(11, 7, 3, 2, 80.00, '2026-06-05 01:34:24'),
(12, 7, 4, 1, 250.00, '2026-06-05 01:45:13'),
(13, 7, 40, 1, 35.00, '2026-06-05 19:33:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id_category` bigint(20) NOT NULL COMMENT 'Identificador de la categoría',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la categoría (ej. Lana, Algodón)',
  `description` text DEFAULT NULL COMMENT 'Descripción de la categoría'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Clasificación de productos (1FN aplicada)';

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `description`) VALUES
(1, 'newart', 'Nuevos artículos y lanzamientos recientes'),
(2, 'bestsellingart', 'Los artículos más vendidos y populares'),
(3, 'seasonalart', 'Artículos de temporada y edición limitada'),
(4, 'yarn', 'Estambre de distintos grosores, colores y fibras.'),
(5, 'pattern', 'Patrones a crochet para tejer prendas, muñecos y accesorios.'),
(6, 'hook', 'Ganchos de tejer de diferentes tamaños y materiales.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chatbot_extra_questions`
--

CREATE TABLE `chatbot_extra_questions` (
  `id_chatbot_extra_question` bigint(20) NOT NULL COMMENT 'ID de la sub-pregunta',
  `id_chatbot_option` bigint(20) NOT NULL COMMENT 'Opción principal que la desencadena',
  `bot_prompt` text NOT NULL COMMENT 'Texto que el bot dice antes de mostrar estas sub-opciones',
  `button_label` varchar(150) NOT NULL COMMENT 'Texto del sub-botón (Ej. Devolución parcial)',
  `final_response` text NOT NULL COMMENT 'Respuesta final tras pulsar este sub-botón'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Árbol de decisiones y sub-preguntas (1:N) para el Chatbot';

--
-- Volcado de datos para la tabla `chatbot_extra_questions`
--

INSERT INTO `chatbot_extra_questions` (`id_chatbot_extra_question`, `id_chatbot_option`, `bot_prompt`, `button_label`, `final_response`) VALUES
(1, 1, 'Elige una opción sobre envíos:', 'Tiempos y costos', 'El envío estándar cuesta $99 MXN y tarda de 3 a 5 días hábiles. El envío exprés cuesta $150 MXN y llega en 1 a 2 días hábiles.'),
(2, 1, 'Elige una opción sobre envíos:', '¿Dónde está mi pedido?', 'Puedes rastrear tu paquete entrando a tu perfil en la sección \"Mis Pedidos\", ahí podrás realizar el seguimiento de tu paquete.'),
(3, 2, 'Elige una opción de diseño:', '¿Cómo cotizar?', 'Para cotizar un diseño personalizado, envíanos un correo a contacto@tienda.com con imágenes de referencia. Requerimos un 50% de anticipo para iniciar.'),
(4, 2, 'Elige una opción de diseño:', 'Tiempos de elaboración', 'Al ser productos hechos a mano, los pedidos personalizados toman entre 1 y 2 semanas en fabricarse, más el tiempo de envío.'),
(5, 3, 'Selecciona el tipo de producto:', 'Lavar crochet', 'Lávalos a mano con agua fría y jabón suave. No los exprimas retorciendo, presiona con una toalla seca y deja secar a la sombra sin colgar.'),
(6, 3, 'Selecciona el tipo de producto:', 'Lavar prendas y bolsas', 'Lavar en ciclo delicado (preferiblemente en malla de lavado). No usar secadora. Secar extendido horizontalmente para que no se deforme el tejido.'),
(7, 4, 'Opciones financieras:', 'Métodos de pago', 'Aceptamos tarjetas de crédito/débito, transferencias bancarias SPEI y pagos en efectivo en tiendas de conveniencia.'),
(8, 4, 'Opciones financieras:', 'Solicitar devolución', 'Tienes 7 días tras recibir tu paquete para solicitar una devolución si el artículo llegó dañado. Escríbenos en la sección \"Mis Pedidos\" con fotos de evidencia.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chatbot_interactions`
--

CREATE TABLE `chatbot_interactions` (
  `id_chatbot_interaction` bigint(20) NOT NULL COMMENT 'ID del registro métrico',
  `id_chatbot_option` bigint(20) NOT NULL COMMENT 'Opción principal pulsada',
  `id_chatbot_extra_question` bigint(20) DEFAULT NULL COMMENT 'Sub-opción pulsada (Opcional, puede ser NULL)',
  `id_user` bigint(20) DEFAULT NULL COMMENT 'Cliente que interactúa (NULL si es invitado)',
  `interaction_date` datetime DEFAULT current_timestamp() COMMENT 'Momento exacto del clic',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla de métricas de clics del Chatbot para el Dashboard';

--
-- Volcado de datos para la tabla `chatbot_interactions`
--

INSERT INTO `chatbot_interactions` (`id_chatbot_interaction`, `id_chatbot_option`, `id_chatbot_extra_question`, `id_user`, `interaction_date`, `created_at`) VALUES
(1, 1, NULL, 23, '2026-06-02 14:30:27', '2026-06-02 15:02:16'),
(2, 1, 1, 23, '2026-06-02 14:30:32', '2026-06-02 15:02:16'),
(3, 1, NULL, 23, '2026-06-02 14:30:38', '2026-06-02 15:02:16'),
(4, 1, 2, 23, '2026-06-02 14:30:40', '2026-06-02 15:02:16'),
(5, 1, 1, NULL, '2026-06-02 15:10:33', '2026-06-02 15:10:33'),
(6, 2, 3, NULL, '2026-06-02 15:10:33', '2026-06-02 15:10:33'),
(7, 1, 2, NULL, '2026-06-02 15:10:33', '2026-06-02 15:10:33'),
(8, 3, 5, NULL, '2026-06-02 15:10:33', '2026-05-30 15:10:33'),
(9, 4, 7, NULL, '2026-06-02 15:10:33', '2026-05-30 15:10:33'),
(10, 1, 1, NULL, '2026-06-02 15:10:33', '2026-05-18 15:10:33'),
(11, 2, 4, NULL, '2026-06-02 15:10:33', '2026-05-13 15:10:33'),
(12, 3, 5, NULL, '2026-06-02 15:10:33', '2026-04-02 15:10:33'),
(13, 4, 8, NULL, '2026-06-02 15:10:33', '2026-03-02 15:10:33'),
(14, 1, 1, NULL, '2026-06-02 15:18:50', '2026-06-02 15:18:50'),
(15, 2, 3, NULL, '2026-06-02 15:18:50', '2026-06-02 15:18:50'),
(16, 1, 2, NULL, '2026-06-02 15:18:50', '2026-06-02 15:18:50'),
(17, 3, 5, NULL, '2026-06-02 15:18:50', '2026-05-30 15:18:50'),
(18, 4, 7, NULL, '2026-06-02 15:18:50', '2026-05-30 15:18:50'),
(19, 1, 1, NULL, '2026-06-02 15:18:50', '2026-05-18 15:18:50'),
(20, 2, 4, NULL, '2026-06-02 15:18:50', '2026-05-13 15:18:50'),
(21, 3, 5, NULL, '2026-06-02 15:18:50', '2026-04-02 15:18:50'),
(22, 4, 8, NULL, '2026-06-02 15:18:50', '2026-03-02 15:18:50'),
(23, 1, NULL, 23, '2026-06-02 15:30:32', '2026-06-02 15:30:32'),
(24, 1, NULL, 23, '2026-06-04 01:20:41', '2026-06-04 01:20:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chatbot_options`
--

CREATE TABLE `chatbot_options` (
  `id_chatbot_option` bigint(20) NOT NULL COMMENT 'ID de la opción principal',
  `button_text` varchar(150) NOT NULL COMMENT 'Texto del botón principal',
  `bot_response` text DEFAULT NULL COMMENT 'Respuesta directa o instrucción del bot',
  `display_order` int(11) NOT NULL DEFAULT 0 COMMENT 'Orden de aparición en el menú',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Define si el botón está visible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Opciones principales del menú del Chatbot';

--
-- Volcado de datos para la tabla `chatbot_options`
--

INSERT INTO `chatbot_options` (`id_chatbot_option`, `button_text`, `bot_response`, `display_order`, `is_active`) VALUES
(1, '📦 Sobre envíos y rastreo', 'Hacemos envíos a todo el país. Una vez que tu pago es procesado, preparamos tu pedido con mucho cuidado. ¿Qué necesitas saber exactamente?', 1, 1),
(2, '✨ Pedidos personalizados', '¡Nos encanta crear piezas únicas! Hacemos crochet y prendas sobre diseño. ¿Qué información buscas?', 2, 1),
(3, '🧼 Cuidados y lavado', 'Nuestros artículos son 100% algodón y hechos a mano. Selecciona qué artículo quieres lavar:', 3, 1),
(4, '💳 Pagos y devoluciones', 'Tu dinero está seguro con nosotros. ¿En qué te puedo asesorar?', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupons`
--

CREATE TABLE `coupons` (
  `id_coupon` bigint(20) NOT NULL COMMENT 'ID del cupón',
  `code` varchar(50) NOT NULL COMMENT 'Código promocional ingresado por el usuario',
  `discount_percentage` decimal(5,2) NOT NULL COMMENT 'Porcentaje de descuento aplicado',
  `valid_until` datetime NOT NULL COMMENT 'Fecha de expiración',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Indica si el cupón puede ser usado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Cupones de descuento (RF23)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id_loyalty_point` bigint(20) NOT NULL COMMENT 'ID del registro de puntos',
  `id_user` bigint(20) NOT NULL COMMENT 'Usuario dueño de los puntos',
  `points_balance` int(11) NOT NULL DEFAULT 0 COMMENT 'Saldo actual de puntos disponibles',
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Última modificación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Sistema de fidelización (RF15)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id_order` bigint(20) NOT NULL COMMENT 'Número de orden / Pedido',
  `id_user` bigint(20) NOT NULL COMMENT 'Cliente comprador',
  `id_coupon` bigint(20) DEFAULT NULL COMMENT 'Cupón aplicado (opcional)',
  `total_amount` decimal(10,2) NOT NULL COMMENT 'Costo final con envíos e impuestos',
  `status` varchar(50) NOT NULL DEFAULT 'pending' COMMENT 'Estados: pending, paid, shipped, delivered, cancelled',
  `shipping_address` text NOT NULL COMMENT 'Dirección de envío estructurada',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Transacciones y compras finalizadas';

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id_order`, `id_user`, `id_coupon`, `total_amount`, `status`, `shipping_address`, `created_at`) VALUES
(9, 23, NULL, 450.00, 'paid', '', '2026-06-02 15:18:50'),
(10, 23, NULL, 120.00, 'pending', '', '2026-06-02 15:18:50'),
(11, 23, NULL, 899.50, 'paid', '', '2026-05-31 15:18:50'),
(12, 23, NULL, 250.00, 'paid', '', '2026-05-28 15:18:50'),
(13, 23, NULL, 1500.00, 'paid', '', '2026-05-21 15:18:50'),
(14, 23, NULL, 340.00, 'pending', '', '2026-05-15 15:18:50'),
(15, 23, NULL, 2200.00, 'paid', '', '2026-02-02 15:18:50'),
(16, 23, NULL, 560.00, 'return_requested', '', '2025-12-02 15:18:50'),
(17, 23, NULL, 220.00, 'paid', '', '2026-06-04 01:43:10'),
(18, 23, NULL, 80.00, 'paid', '', '2026-06-04 01:55:03'),
(19, 23, NULL, 80.00, 'paid', '', '2026-06-04 02:00:12'),
(20, 23, NULL, 190.00, 'paid', '', '2026-06-04 02:32:58'),
(21, 23, NULL, 315.00, 'paid', '', '2026-06-04 12:04:38'),
(22, 23, NULL, 410.00, 'paid', '', '2026-06-05 01:19:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_details`
--

CREATE TABLE `order_details` (
  `id_order_detail` bigint(20) NOT NULL COMMENT 'Renglón de la compra',
  `id_order` bigint(20) NOT NULL COMMENT 'Orden asociada',
  `id_product` bigint(20) NOT NULL COMMENT 'Producto adquirido',
  `quantity` int(11) NOT NULL COMMENT 'Cantidad',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Precio unitario histórico e inmutable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro histórico inmutable de ventas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id_payment` bigint(20) NOT NULL COMMENT 'ID del pago',
  `id_order` bigint(20) NOT NULL COMMENT 'Orden liquidada',
  `payment_method` varchar(100) NOT NULL COMMENT 'Método utilizado',
  `payment_status` varchar(50) NOT NULL DEFAULT 'completed' COMMENT 'Estado financiero',
  `transaction_id` varchar(255) DEFAULT NULL COMMENT 'Folio pasarela',
  `paid_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Registro formal de ingresos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id_product` bigint(20) NOT NULL COMMENT 'Identificador único del producto',
  `id_category` bigint(20) NOT NULL COMMENT 'Categoría a la que pertenece',
  `id_provider` bigint(20) DEFAULT NULL COMMENT 'Proveedor que surte el material',
  `sku` varchar(50) DEFAULT NULL COMMENT 'Código de referencia (SKU)',
  `name` varchar(150) NOT NULL COMMENT 'Nombre del artículo',
  `description` text NOT NULL COMMENT 'Descripción para el cliente',
  `price` decimal(10,2) NOT NULL COMMENT 'Precio de venta al público en MXN',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad disponible',
  `minimum_stock_alert` int(11) NOT NULL DEFAULT 5 COMMENT 'Umbral para alertas',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Regla 8: Booleano de estado',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Catálogo maestro de artículos';

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id_product`, `id_category`, `id_provider`, `sku`, `name`, `description`, `price`, `stock`, `minimum_stock_alert`, `is_active`, `created_at`) VALUES
(1, 1, NULL, NULL, 'Bufanda de ojos', 'Bufanda delgada en forma de ojos humanos, 130cm, 100% Algodón.', 260.00, 22, 5, 1, '2026-06-01 23:55:54'),
(2, 1, NULL, NULL, 'Caja organizadora', 'Caja organizadora de cables en forma de pastel de Minecraft con asa pequeña de cierre, 10cmx8cm, 100% Algodón.', 85.00, 20, 5, 1, '2026-06-01 23:55:54'),
(3, 1, NULL, NULL, 'Moño de cabello', 'Moño pequeño para cabello color azul, 15cm, 100% Algodón.', 80.00, 20, 5, 1, '2026-06-01 23:55:54'),
(4, 1, NULL, NULL, 'Sombrero de playa', 'Sombrero de playa tipo gorra con diseño simple, 60cm diámetro, 100% Algodón.', 250.00, 20, 5, 1, '2026-06-01 23:55:54'),
(5, 1, NULL, NULL, 'Rosa individual.', 'Rosa individual color amarillo con tallo, 30cm, 100% Algodón.', 90.00, 20, 5, 1, '2026-06-01 23:55:54'),
(8, 2, NULL, NULL, 'Guantes para horno', 'Set de guantes para horno color lila, 10cmx20cm, 100% Algodón.', 110.00, 20, 5, 1, '2026-06-01 23:55:54'),
(9, 2, NULL, NULL, 'Amigurumi de perro Coraje', 'Amigurumi de perro Coraje, 12cmx12cm, 100% Algodón.', 130.00, 20, 5, 1, '2026-06-01 23:55:54'),
(10, 2, NULL, NULL, 'Organizador de manzana', 'Organizador de cables en forma de manzana mordidad, 10cmx10cm, 100% Algodón.', 95.00, 20, 5, 1, '2026-06-01 23:55:54'),
(11, 2, NULL, NULL, 'Amigurumi de Mike Wazowski', 'Amigurumi de Mike Wazowski de Monsters INC, 11cmx10cm, 100% Algodón.', 125.00, 20, 5, 1, '2026-06-01 23:55:54'),
(12, 2, NULL, NULL, 'Portabotellas con asa', 'Portabotellas tejido de red con asa, 15cmx30cm, 100% Algodón.', 170.00, 20, 5, 1, '2026-06-01 23:55:54'),
(13, 3, NULL, NULL, 'Ramo de tulipanes y flores', 'Ramo de tulipanes naranjas y flores pequeñas blancas con tallo, 25cm, 100% Algodón.', 255.00, 20, 5, 1, '2026-06-01 23:55:54'),
(14, 3, NULL, NULL, 'Bolsa de estrella', 'Bolsa en forma de estrella con rayas blancas y lilas, lazo 100cm, tamaño 40cm, 100% Algodón', 315.00, 20, 5, 1, '2026-06-01 23:55:54'),
(16, 3, NULL, NULL, 'Funda de laptop', 'Funda de laptop color amarillo y crema con asa, 45cmx30cm, 100% Algodón.', 285.00, 20, 5, 1, '2026-06-01 23:55:54'),
(17, 3, NULL, NULL, 'Bolsa tejida a rayas', 'Bolsa tejida de agujas a rayas color café, verde y blanca, lazo 100cm, tamaño 25cm, 100% Algodón.', 454.00, 20, 5, 1, '2026-06-01 23:55:54'),
(18, 1, NULL, NULL, 'Amigurumi de Mariposa', 'Amigurumi de mariposa monarca pequeño, 12cmx10cm, 100% Algodón.', 82.00, 20, 5, 1, '2026-06-01 23:55:54'),
(19, 1, NULL, NULL, 'Bolsa de red con cordón', 'Bolsa de tejido tipo red con cordón ajustable color naranja claro y verde, cordón 50cm, bolsa 30cm, 100% Algodón.', 129.00, 20, 5, 1, '2026-06-01 23:55:54'),
(20, 1, NULL, NULL, 'Banda delgada para cabello', 'Banda delgada de cabello con cordones ajustables color verdes oscuro, 50cm, 100% Algodón.', 99.00, 20, 5, 1, '2026-06-01 23:55:54'),
(21, 1, NULL, NULL, 'Sombrero de mujer a rayas', 'Sombrero a rayas de mujer color rojo, morado y negro con cordones ajustables, 60cm diámetro, 100% Algodón.', 340.00, 20, 5, 1, '2026-06-01 23:55:54'),
(22, 1, NULL, NULL, 'Sombrero de playa a rayas', 'Sombrero mediano de playa con rayas color rojo, verde y azul con cordones ajustables, 60cm diámetro, 100% Algodón.', 355.00, 20, 5, 1, '2026-06-01 23:55:54'),
(23, 3, NULL, NULL, 'Bolsa grande con diseño', 'Bolsa grande tipo maleta con diseño en ambas caras de galaxia y \"I Love Geology\" con dos bolsillos internos, lazo 100cm, 100% Algodón.', 1500.00, 20, 5, 1, '2026-06-01 23:55:54'),
(24, 3, NULL, NULL, 'Amigurumi de Paleta Payaso', 'Amigurumi de Paleta Payaso pequeño, 10cmx15cm, 100% Algodón.', 122.00, 20, 5, 1, '2026-06-01 23:55:54'),
(25, 3, NULL, NULL, 'Gorro de cerebro', 'Gorro en forma de cerebro humano color rosa y detalles color negro, 60cm de diámetro, 100% Algodón.', 522.00, 20, 5, 1, '2026-06-01 23:55:54'),
(26, 3, NULL, NULL, 'Bolsa de cuadros', 'Bolsa pequeña hecha de cuadros tipo \"Granny\", bolsa 40cmx40cm, lazo 60cm, 100% Algodón.', 640.00, 20, 5, 1, '2026-06-01 23:55:54'),
(27, 2, NULL, NULL, 'Amigurumi de Trazo Bob Esponja', 'Amigurumi de Trazo de Bob Esponja color blanco y negro, 25cmx25cm, 100% Algodón.', 332.00, 20, 5, 1, '2026-06-01 23:55:54'),
(28, 2, NULL, NULL, 'Amigurumi de Calamar gato Coraline', 'Amigurumi de Calamar gato de Coraline color azul, 15cmx35cm, 100% Algodón.', 680.00, 20, 5, 1, '2026-06-01 23:55:54'),
(29, 2, NULL, NULL, 'Bolsa roja mediana de red', 'Bolsa de tejido de red color rojo, 50cmx50cm, 100% Algodón.', 468.00, 20, 5, 1, '2026-06-01 23:55:54'),
(30, 2, NULL, NULL, 'Suéter de red telaraña', 'Suéter mediano de tejido de red en forma de telaraña, 100% Algodón.', 1360.00, 20, 5, 1, '2026-06-01 23:55:54'),
(31, 2, NULL, NULL, 'Gargantilla con cuentas', 'Gargantilla mediana color negro con cuentas decorativas color rosa, cordones ajustables, 50cm diámetro, 100% Algodón.', 465.00, 35, 5, 1, '2026-06-01 23:55:54'),
(32, 4, NULL, NULL, 'Estambre La Abuelita Rosa', 'Hecha 100% con algodón mercerizado, famosa por su calidad y rendimiento. Se recomienda para tejer carpetas, sobrecamas, prendas de vestir, ropa de bebé y artesanías. Puede emplearse para aquellas labores que se deseen hacer con mayor velocidad.', 39.00, 10, 5, 1, '2026-06-05 17:47:45'),
(33, 4, NULL, NULL, 'Estambre La Abuelita Verde cobre', 'Hecha 100% con algodón mercerizado, famosa por su calidad y rendimiento. Se recomienda para tejer carpetas, sobrecamas, prendas de vestir, ropa de bebé y artesanías. Puede emplearse para aquellas labores que se deseen hacer con mayor velocidad.', 39.00, 10, 5, 1, '2026-06-05 18:06:42'),
(34, 4, NULL, NULL, 'Estambre La Abuelita Azul marino', 'Hecha 100% con algodón mercerizado, famosa por su calidad y rendimiento. Se recomienda para tejer carpetas, sobrecamas, prendas de vestir, ropa de bebé y artesanías. Puede emplearse para aquellas labores que se deseen hacer con mayor velocidad.', 39.00, 10, 5, 1, '2026-06-05 18:08:33'),
(35, 4, NULL, NULL, 'Estambre La Abuelita Crema', 'Hecha 100% con algodón mercerizado, famosa por su calidad y rendimiento. Se recomienda para tejer carpetas, sobrecamas, prendas de vestir, ropa de bebé y artesanías. Puede emplearse para aquellas labores que se deseen hacer con mayor velocidad.', 39.00, 10, 5, 1, '2026-06-05 18:09:11'),
(36, 4, NULL, NULL, 'Estambre La Abuelita Azul oscuro', 'Hecha 100% con algodón mercerizado, famosa por su calidad y rendimiento. Se recomienda para tejer carpetas, sobrecamas, prendas de vestir, ropa de bebé y artesanías. Puede emplearse para aquellas labores que se deseen hacer con mayor velocidad.', 39.00, 10, 5, 1, '2026-06-05 18:09:42'),
(37, 5, NULL, NULL, 'Patrón Almohada Granny', 'Patrón de almohada hecha de cuadros tejidos.', 30.00, 10, 5, 1, '2026-06-05 19:25:56'),
(38, 5, NULL, NULL, 'Patrón Bufanda/Gorro Granny', 'Patrón de bufanda y gorro para dormir tejido en punto granny.', 65.00, 10, 5, 1, '2026-06-05 19:27:42'),
(39, 5, NULL, NULL, 'Patrón Funda/Carpeta libro', 'Patrón de carpeta para guardar libros.', 45.00, 10, 5, 1, '2026-06-05 19:28:38'),
(40, 5, NULL, NULL, 'Patrón Monedero Pequeño Flor', 'Patrón de pequeño monedero decorado con una flor y cierre a presión metálico.', 35.00, 10, 5, 1, '2026-06-05 19:29:41'),
(41, 6, NULL, NULL, 'Gancho ergonómico 4mm aluminio', 'Gancho ergonómico de 4mm hecho de aluminio con mango de suave de plástico.', 40.00, 10, 5, 1, '2026-06-05 20:09:55'),
(42, 6, NULL, NULL, 'Gancho ergonómico 10mm madera', 'Gancho ergonómico artesanal de 10mm hecho de madera.', 80.00, 10, 5, 1, '2026-06-05 20:10:58'),
(43, 6, NULL, NULL, 'Gancho ergonómico 4.5mm vidrio decorado', 'Gancho ergonómico de 5mm hecho de vidrio decorado decorado.', 125.00, 10, 5, 1, '2026-06-05 20:12:00'),
(44, 6, NULL, NULL, 'Gancho ergonómico 4.5mm resina multicolor', 'Gancho ergonómico de 4.5mm hecho de resina multicolor.', 35.00, 10, 5, 1, '2026-06-05 20:14:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id_product_image` bigint(20) NOT NULL COMMENT 'ID de la imagen',
  `id_product` bigint(20) NOT NULL COMMENT 'Producto al que pertenece',
  `image_url` varchar(255) NOT NULL COMMENT 'Ruta del archivo de imagen',
  `is_primary` tinyint(1) DEFAULT 0 COMMENT 'Indica si es la imagen principal de portada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Galería de imágenes normalizada (1:N)';

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`id_product_image`, `id_product`, `image_url`, `is_primary`) VALUES
(1, 1, 'newArtItem (1).png', 1),
(2, 2, 'newArtItem (2).png', 1),
(3, 3, 'newArtItem (3).png', 1),
(4, 4, 'newArtItem (4).png', 1),
(5, 5, 'newArtItem (5).png', 1),
(6, 8, 'bestSellingArt (1).png', 1),
(7, 9, 'bestSellingArt (2).png', 1),
(8, 10, 'bestSellingArt (3).png', 1),
(9, 11, 'bestSellingArt (4).png', 1),
(10, 12, 'bestSellingArt (5).png', 1),
(11, 13, 'seasonArtItem (1).png', 1),
(12, 14, 'seasonArtItem (2).png', 1),
(14, 16, 'seasonArtItem (4).png', 1),
(15, 17, 'seasonArtItem (5).png', 1),
(16, 18, 'newArtItem (6).png', 1),
(17, 19, 'newArtItem (7).png', 1),
(18, 20, 'newArtItem (8).png', 1),
(19, 21, 'newArtItem (9).png', 1),
(20, 22, 'newArtItem (10).png', 1),
(21, 23, 'seasonArtItem (6).png', 1),
(22, 24, 'seasonArtItem (7).png', 1),
(23, 25, 'seasonArtItem (8).png', 1),
(24, 26, 'seasonArtItem (9).png', 1),
(25, 27, 'bestSellingArt (6).png', 1),
(26, 28, 'bestSellingArt (7).png', 1),
(27, 29, 'bestSellingArt (8).png', 1),
(28, 30, 'bestSellingArt (9).png', 1),
(29, 31, 'bestSellingArt (10).png', 1),
(30, 32, '1780706865_estambre-rosa-abuelita.png', 1),
(31, 33, '1780708002_estambre-verde-cobre-abuelita.png', 1),
(32, 34, '1780708113_estambre-azul-marino-abuelita.png', 1),
(33, 35, '1780708151_estambre-crema-abuelita.png', 1),
(34, 36, '1780708182_estambre-azul-oscuro-abuelita.png', 1),
(35, 37, '1780712756_Patrón Almohada Granny Square.png', 1),
(36, 38, '1780712862_Patrón Bufanda y gorro Granny.png', 1),
(37, 39, '1780712918_Patrón Funda de libro.png', 1),
(38, 40, '1780712981_Patrón Monedero pequeño.png', 1),
(39, 41, '1780715395_Gancho ergonómico 4mm aluminio.png', 1),
(40, 42, '1780715458_Gancho ergonómico 10mm madera.png', 1),
(41, 43, '1780715520_Gancho ergonómico 5mm vidrio decorado.png', 1),
(42, 44, '1780715662_Gancho ergonómico 4.5mm resina.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_views`
--

CREATE TABLE `product_views` (
  `id_view` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `product_views`
--

INSERT INTO `product_views` (`id_view`, `id_product`, `created_at`) VALUES
(1, 1, '2026-06-02 15:10:33'),
(2, 2, '2026-06-02 15:10:33'),
(3, 1, '2026-06-02 15:10:33'),
(4, 1, '2026-05-29 15:10:33'),
(5, 2, '2026-05-27 15:10:33'),
(6, 1, '2026-05-19 15:10:33'),
(7, 2, '2026-05-08 15:10:33'),
(8, 2, '2026-01-02 15:10:33'),
(9, 1, '2026-06-02 15:18:50'),
(10, 2, '2026-06-02 15:18:50'),
(11, 1, '2026-06-02 15:18:50'),
(12, 1, '2026-05-29 15:18:50'),
(13, 2, '2026-05-27 15:18:50'),
(14, 1, '2026-05-19 15:18:50'),
(15, 2, '2026-05-08 15:18:50'),
(16, 2, '2026-01-02 15:18:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_visits`
--

CREATE TABLE `product_visits` (
  `id_product_visit` bigint(20) NOT NULL COMMENT 'ID de interacción',
  `id_user` bigint(20) DEFAULT NULL COMMENT 'Usuario visitante',
  `id_product` bigint(20) NOT NULL COMMENT 'Producto visto',
  `visit_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Métricas de visualización para el Dashboard';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `providers`
--

CREATE TABLE `providers` (
  `id_provider` bigint(20) NOT NULL COMMENT 'ID del proveedor',
  `name` varchar(150) NOT NULL COMMENT 'Razón social o nombre del proveedor',
  `contact_email` varchar(150) DEFAULT NULL COMMENT 'Correo de contacto',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Teléfono de contacto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Directorio de proveedores de materiales (RF09)';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reviews`
--

CREATE TABLE `reviews` (
  `id_review` bigint(20) NOT NULL COMMENT 'ID de la reseña',
  `id_user` bigint(20) NOT NULL COMMENT 'Autor',
  `id_product` bigint(20) NOT NULL COMMENT 'Producto calificado',
  `rating` int(11) NOT NULL COMMENT 'Estrellas (1-5)',
  `comment` text DEFAULT NULL COMMENT 'Opinión',
  `seller_response` text DEFAULT NULL COMMENT 'Respuesta pública del administrador',
  `is_verified_purchase` tinyint(1) DEFAULT 1 COMMENT 'Validación de compra',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Valoraciones de productos';

--
-- Volcado de datos para la tabla `reviews`
--

INSERT INTO `reviews` (`id_review`, `id_user`, `id_product`, `rating`, `comment`, `seller_response`, `is_verified_purchase`, `created_at`) VALUES
(1, 32, 1, 2, 'muy bonito', NULL, 1, '2026-06-04 22:18:57'),
(3, 23, 10, 2, 'ss', NULL, 1, '2026-06-05 09:52:06'),
(4, 23, 10, 2, 'g', NULL, 1, '2026-06-05 10:10:49'),
(5, 23, 10, 3, 'hola', NULL, 1, '2026-06-05 10:11:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipments`
--

CREATE TABLE `shipments` (
  `id_shipment` bigint(20) NOT NULL COMMENT 'ID de guía de envío',
  `id_order` bigint(20) NOT NULL COMMENT 'Orden despachada',
  `carrier_name` varchar(100) NOT NULL COMMENT 'Paquetería',
  `tracking_number` varchar(150) NOT NULL COMMENT 'Número de guía',
  `shipping_status` varchar(50) NOT NULL DEFAULT 'processing' COMMENT 'Estados de tránsito',
  `estimated_delivery` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Logística y despachos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) NOT NULL COMMENT 'Identificador único del usuario',
  `username` varchar(100) NOT NULL COMMENT 'Nombre de usuario',
  `email` varchar(150) NOT NULL COMMENT 'Correo electrónico único',
  `password_hash` varchar(255) NOT NULL COMMENT 'Contraseña encriptada',
  `role` varchar(50) NOT NULL DEFAULT 'client' COMMENT 'Rol del sistema: client o admin',
  `description` text DEFAULT NULL COMMENT 'Descripción o biografía del perfil',
  `created_at` datetime DEFAULT current_timestamp() COMMENT 'Fecha de registro en UTC'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabla principal de acceso y perfiles';

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password_hash`, `role`, `description`, `created_at`) VALUES
(23, 'Axdiael Trinidad', 'axtc@gmail.com', '$2y$10$FwKA6bOGHwmu0dJNo0noFukmGVxg5cel6Jv8fJBQEcrECeUqt4S/.', 'admin', '', '2026-06-01 23:55:54'),
(28, 'Angel Mendoza', 'angelmv@gmail.com', '$2y$10$D3OypprZp3CJ8wR2XBfeE.SYx8ngOKLONeLBDeB7/XiHZamumL4Jy', 'admin', '', '2026-06-01 23:55:54'),
(29, 'Luis Lucatero', 'luislp@gmail.com', '$2y$10$lJ6/cFBwi2203TPCgGGx3e9JmW76g091xNmU9L/QSklbsh9Ek99e.', 'client', 'QA', '2026-06-01 23:55:54'),
(30, 'susan', 'susan@gmail.com', '$2y$10$cRpnH/8xOC.b6hpBwEPF7.tmH5hE/MeSOKOvRJqwkHWQp6JsM8j6e', 'client', 'hola', '2026-06-04 00:35:49'),
(31, 'gabriel', 'gabi@gmail.com', '$2y$10$6GSzot3JEWWsiCKLAx29YO9ja8nsFNxO0O5x5u1ZjHkouLGQLozcG', 'admin', '', '2026-06-04 22:10:24'),
(32, 'jesus', 'jesus@gmail.com', '$2y$10$YWreySrkE6QpPtjvguXdM.rJh4ODmpjFec18LY6LJjvB.BR5oPe.O', 'admin', 'hola soy jesus', '2026-06-04 22:11:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `fk_carts_users` (`id_user`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id_cart_item`),
  ADD KEY `fk_cart_items_carts` (`id_cart`),
  ADD KEY `fk_cart_items_products` (`id_product`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `chatbot_extra_questions`
--
ALTER TABLE `chatbot_extra_questions`
  ADD PRIMARY KEY (`id_chatbot_extra_question`),
  ADD KEY `fk_chatbot_extras_options` (`id_chatbot_option`);

--
-- Indices de la tabla `chatbot_interactions`
--
ALTER TABLE `chatbot_interactions`
  ADD PRIMARY KEY (`id_chatbot_interaction`),
  ADD KEY `fk_interactions_options` (`id_chatbot_option`),
  ADD KEY `fk_interactions_extras` (`id_chatbot_extra_question`),
  ADD KEY `fk_interactions_users` (`id_user`);

--
-- Indices de la tabla `chatbot_options`
--
ALTER TABLE `chatbot_options`
  ADD PRIMARY KEY (`id_chatbot_option`);

--
-- Indices de la tabla `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id_coupon`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id_loyalty_point`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `fk_orders_users` (`id_user`),
  ADD KEY `fk_orders_coupons` (`id_coupon`);

--
-- Indices de la tabla `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id_order_detail`),
  ADD KEY `fk_order_details_orders` (`id_order`),
  ADD KEY `fk_order_details_products` (`id_product`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `fk_payments_orders` (`id_order`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_products_categories` (`id_category`),
  ADD KEY `fk_products_providers` (`id_provider`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id_product_image`),
  ADD KEY `fk_product_images_products` (`id_product`);

--
-- Indices de la tabla `product_views`
--
ALTER TABLE `product_views`
  ADD PRIMARY KEY (`id_view`);

--
-- Indices de la tabla `product_visits`
--
ALTER TABLE `product_visits`
  ADD PRIMARY KEY (`id_product_visit`),
  ADD KEY `fk_product_visits_users` (`id_user`),
  ADD KEY `fk_product_visits_products` (`id_product`);

--
-- Indices de la tabla `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id_provider`);

--
-- Indices de la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `fk_reviews_users` (`id_user`),
  ADD KEY `fk_reviews_products` (`id_product`);

--
-- Indices de la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id_shipment`),
  ADD UNIQUE KEY `id_order` (`id_order`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id_cart` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del carrito', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id_cart_item` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del renglón en el carrito', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la categoría', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `chatbot_extra_questions`
--
ALTER TABLE `chatbot_extra_questions`
  MODIFY `id_chatbot_extra_question` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de la sub-pregunta', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `chatbot_interactions`
--
ALTER TABLE `chatbot_interactions`
  MODIFY `id_chatbot_interaction` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro métrico', AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `chatbot_options`
--
ALTER TABLE `chatbot_options`
  MODIFY `id_chatbot_option` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de la opción principal', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id_coupon` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del cupón';

--
-- AUTO_INCREMENT de la tabla `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id_loyalty_point` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del registro de puntos';

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Número de orden / Pedido', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id_order_detail` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Renglón de la compra';

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del pago';

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id_product` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del producto', AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id_product_image` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de la imagen', AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `product_views`
--
ALTER TABLE `product_views`
  MODIFY `id_view` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `product_visits`
--
ALTER TABLE `product_visits`
  MODIFY `id_product_visit` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de interacción';

--
-- AUTO_INCREMENT de la tabla `providers`
--
ALTER TABLE `providers`
  MODIFY `id_provider` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID del proveedor';

--
-- AUTO_INCREMENT de la tabla `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id_review` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de la reseña', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id_shipment` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID de guía de envío';

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario', AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_carts` FOREIGN KEY (`id_cart`) REFERENCES `carts` (`id_cart`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Filtros para la tabla `chatbot_extra_questions`
--
ALTER TABLE `chatbot_extra_questions`
  ADD CONSTRAINT `fk_chatbot_extras_options` FOREIGN KEY (`id_chatbot_option`) REFERENCES `chatbot_options` (`id_chatbot_option`) ON DELETE CASCADE;

--
-- Filtros para la tabla `chatbot_interactions`
--
ALTER TABLE `chatbot_interactions`
  ADD CONSTRAINT `fk_interactions_extras` FOREIGN KEY (`id_chatbot_extra_question`) REFERENCES `chatbot_extra_questions` (`id_chatbot_extra_question`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_interactions_options` FOREIGN KEY (`id_chatbot_option`) REFERENCES `chatbot_options` (`id_chatbot_option`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_interactions_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Filtros para la tabla `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `fk_loyalty_points_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_coupons` FOREIGN KEY (`id_coupon`) REFERENCES `coupons` (`id_coupon`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Filtros para la tabla `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_order_details_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_details_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_categories` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `fk_products_providers` FOREIGN KEY (`id_provider`) REFERENCES `providers` (`id_provider`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product_visits`
--
ALTER TABLE `product_visits`
  ADD CONSTRAINT `fk_product_visits_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_visits_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Filtros para la tabla `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_products` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reviews_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `fk_shipments_orders` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
