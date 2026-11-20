-- ============================================================
-- Actualización de rutas de imágenes para el servidor de producción
-- Mejora de Rendimiento - Plan de Calidad ISO/IEC 25010
-- Fecha: 2026-11-20
--
-- INSTRUCCIONES:
-- 1. Subir primero los archivos .webp de Sitio-Web/public/assets/images/
--    al servidor (cPanel -> Administrador de archivos -> ehqa/public/assets/images/).
-- 2. Ejecutar este script en phpMyAdmin sobre la base de datos anuiesne_ehqa.
--
-- NOTA: Solo actualiza DATOS (el nombre del archivo de imagen),
--       NO modifica la estructura de la base de datos.
-- ============================================================

UPDATE product_images
SET image_url = REPLACE(image_url, '.png', '.webp')
WHERE image_url LIKE '%.png';

-- Verificación: todas las rutas deben terminar en .webp
SELECT id_product_image, id_product, image_url FROM product_images;
