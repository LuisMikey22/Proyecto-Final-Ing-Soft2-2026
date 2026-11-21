# Instrucciones de despliegue en producción (anuies.net/ehqa)

Guía para subir la mejora significativa al servidor compartido (cPanel/Linux).
Tiempo estimado: 20–30 minutos. Hazlo con calma y sigue el orden.

## 0. Antes de empezar: respaldo (obligatorio)

1. **Archivos:** en cPanel → Administrador de archivos, comprime la carpeta `ehqa/` completa en un ZIP y descárgalo a tu compu.
2. **Base de datos:** en phpMyAdmin, selecciona `anuiesne_ehqa` → Exportar → método Rápido → SQL → Continuar. Guarda el archivo.

Si algo sale mal, restauras el ZIP y el SQL y todo vuelve a quedar como estaba.

## 1. Subir los archivos nuevos

Desde el repo (`ProyectoFinalWeb2025/Sitio-Web/`), sube por el Administrador de archivos de cPanel:

| Origen (repo) | Destino (servidor) |
|---|---|
| `public/index.php` | `ehqa/public/index.php` |
| `public/output.css` | `ehqa/public/output.css` (sobrescribir) |
| `public/scripts/script.js` | `ehqa/public/scripts/script.js` |
| `public/.htaccess` | `ehqa/public/.htaccess` (sobrescribir) |
| `public/assets/images/*.webp` (todos) | `ehqa/public/assets/images/` |
| `src/` completa (controllers, Models, views, helpers, css) | `ehqa/src/` (sobrescribir) |

**Importante:**
- **NO subas** el `.env` del repo (es el de tu máquina local). El servidor ya tiene su propio `.env` con la URL y credenciales de producción; se queda tal cual.
- **NO subas** `vendor/`, `node_modules/`, `tests/` ni `phpunit.xml` (son solo para desarrollo).
- En Linux, `index.php` y `Index.php` son archivos distintos. Después de subir, **borra del servidor** los viejos `public/Index.php` y `public/scripts/Script.js` (mayúsculas) para que no queden duplicados.

## 2. Actualizar las rutas de imágenes en la BD

1. Abre phpMyAdmin → base `anuiesne_ehqa` → pestaña **SQL**.
2. Pega y ejecuta el contenido de `docs/calidad/evidencias/actualizar_imagenes_produccion.sql`.
   (Solo cambia el texto `.png` → `.webp` en `product_images.image_url`; no toca la estructura.)
3. Verifica con la consulta del final del script: todas las rutas deben terminar en `.webp`.

**Ojo:** si en producción hay productos con imágenes que no existen en el repo
(por ejemplo, fotos subidas desde el admin después de junio), conviértelas también
a WebP con el script `docs/calidad/scripts/convertir_imagenes_webp.py` y sube esos
`.webp` antes de ejecutar el UPDATE. Para detectarlas:

```sql
SELECT image_url FROM product_images;
```

Compara esa lista contra los archivos `.webp` que subiste.

## 3. Verificación (lista de cotejo)

Abre el sitio en una ventana privada (para no arrastrar sesión vieja) y revisa:

- [ ] `https://anuies.net/ehqa/public/` carga el catálogo con imágenes (ahora `.webp`).
- [ ] Consola del navegador (F12) **sin errores 404/502** (antes había 2).
- [ ] `/login` muestra el formulario y puedes iniciar sesión con tu cuenta admin.
- [ ] `/products/1` muestra el detalle correcto.
- [ ] Buscar "bufanda" devuelve resultados; buscar `<script>alert(1)</script>` muestra el texto escapado, no ejecuta nada.
- [ ] Intentar registrarse con contraseña de 3 caracteres muestra el error de mínimo 8.
- [ ] El Dashboard (`/admin/dashboard`) carga gráficas; la línea "Visitas a Productos" ya tiene datos cuando hay visitas.
- [ ] Cambiar el filtro Hoy/Semana/Mes/Año actualiza las gráficas **sin recargar la página**.
- [ ] Sin sesión, `/api/dashboard/data` responde 401; con cuenta cliente, 403.

## 4. Medir el "después" en producción

Con el sitio ya desplegado, abre la home con F12 → pestaña Red (Network), recarga
y anota: peso total transferido y tiempo de carga (DOMContentLoaded / Load).
Compáralo contra la línea base: **5.3 MB y 6.3 s**. La meta del plan es ≤ 3 s.
Esa medición cierra la evidencia del informe.

## 5. Si algo falla (plan de reversa)

1. Restaura el ZIP de `ehqa/` del paso 0.
2. Restaura el SQL exportado en phpMyAdmin (Importar).
3. El sitio vuelve al estado anterior y se revisa el problema con calma en local.
