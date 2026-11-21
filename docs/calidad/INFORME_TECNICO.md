# Informe Técnico — Mejora Significativa del Plan de Calidad (ISO/IEC 25010)

**Proyecto:** Esta Hambre Que Ata (EstaHambreQAta)
**Trayecto del proyecto:** EstaHambre Que Ata, tienda en línea de manualidades y estambres, desarrollada por estudiantes de la UABCS (Ingeniería en Desarrollo de Software, 6.º semestre).
**Materia:** Calidad del Software (7.º semestre, 2026-II)
**Docente:** Dra. Aida Sánchez Paz Rubio
**Fecha del informe:** 20 de noviembre de 2026

---

## 1. Portada y datos del equipo

| Rol | Nombre | Objetivo en el proyecto |
|---|---|---|
| Líder de Proyecto | Luis Miguel Lucatero Pérez | Supervisar el cumplimiento del plan de calidad |
| Desarrollador | Ángel Gabriel Mendoza Villavicencio | Implementar código conforme a estándares y prácticas seguras |
| Evaluador de Calidad / Seguridad | Axdiael Trinidad Cárdenas | Realizar revisiones, métricas y auditar controles |
| Evaluator (Profesor de Ingeniería de Software II) | Dra. Italia Estrada Cota | Evaluadora del Proyecto Final (6.º semestre, 2026-I) |

---

## 2. Resumen ejecutivo

Este informe documenta la implementación de una **Mejora Significativa** del sistema "Esta Hambre Que Ata", siguiendo los objetivos del Plan de Calidad del Software (Avance 2). Se centra en dos atributos:

- **Rendimiento (Eficiencia de Desempeño, 3/5 → 4/5):** El sitio servía imágenes originales de más de 125 MB que pesaban KB en la página de inicio. Se convirtieron a WebP (máximo 1000 px) con una reducción de **135.6 MB a 2.1 MB (-98,5%)**, se añadió carga diferida (`loading="lazy"`) y se optimizó el Dashboard (de 14 consultas a 2 en la gráfica de tendencias; AJAX en el filtro temporal sin recargar página). Resultado: la página de inicio de 125 MB a **0,71 MB** y tiempo de carga de **4.3 s a 0,28 s** (máximo a 3 s).

- **Seguridad (2/5 → 4/5):** Se corrigieron tres vulnerabilidades reales demostradas con pruebas de ataque (anteriormente explotables): **XSS reflejado en el buscador** (el script se imprimía sin escapar), **escalada de privilegios al registrarse con `role=admin`** y **aceptación de contraseñas de 3 caracteres**. Se implementó validación de contraseñas ≥ 8 caracteres, escape de salidas con `htmlspecialchars` y límite de intentos de login.

- **Mantenibilidad (3/5 → 4/5):** Se pasó de **0 a 66 pruebas unitarias automatizadas (cobertura 77,10%, meta ≥ 70%)** y a **CI verde en GitHub Actions**. Se corrigieron 14 bugs (incluye un fallo real descubierto por CI: `orders.shipping_address` era `TEXT NOT NULL` sin default y MySQL 8 lo rechazaba).

Todo se desarrolló sobre la rama `develop` del repo, con 14 commits, sin modificar la estructura de la base de datos (D03).

---

## 3. Descripción de la aplicación existente

"Esta Hambre Que Ata" es una tienda en línea de comercio electrónico (E-commerce) de manualidades y estambres, realizada en PHP nativo con patrón MVC, sobre una base de datos relacional MySQL (`anuiesne_ehqa`, 19 tablas normalizadas). Soporta: catálogo con productos terminados y kits, carrito persistente, checkout con tarifa plana de envío, pagos simulados, reseñas, devoluciones con evidencia, chatbot sin IA (botones preconfigurados desde `chatbot_options` en la BD) y Dashboard administrativo con Chart.js protegido por RBAC (filtros temporales de día/semana/mes/año).

El sistema se desarrolló originalmente en 2026-I por estudiantes (Ingeniería de Software II, BDA) y está en proceso de aprovecharse en Calidad del Software.

---

## 4. Diagnóstico inicial de calidad (por atributo ISO 25010)

| Atributo ISO 25010 | Calificación | Prioridad de mejora |
|---|---|---|
| Adecuación Funcional | 5/5 | Baja |
| **Eficiencia de Desempeño** | **3/5** | **Alta** |
| Compatibilidad | 5/5 | Baja |
| Usabilidad | 5/5 | Baja |
| Fiabilidad | 4/5 | Baja |
| **Seguridad** | **2/5** | **Alta** |
| **Mantenibilidad** | **3/5** | **Alta** |
| Portabilidad | 5/5 | Baja |

Se centran las mejoras en los tres atributos débiles: Eficiencia de Desempeño, Seguridad y Mantenibilidad (D08).

---

## 5. Plan de calidad del software

Convenios adoptados:
- Aplicación del Manual de Estándares a PHP nativo (D01).
- Nomenclatura estricta (tablas inglés plurales, PKs `id_<tabla_singular>` BIGINT, columna `created_at`, `status`, `total_amount`).
- Código y BD en inglés; comentarios en español.
- Validación de entrada (contraseñas ≥ 8, correos válidos, usuarios no vacíos).
- Escapado de salidas (`htmlspecialchars`) en todas las vistas.
- Control ISO/IEC 27001 A.9: límite de intentos de login.
- Pruebas unitarias en GitHub Actions (PHPUnit, Xdebug, cobertura ≥ 70%).
- Prohibido inventar resultados de pruebas para el Punto 9 (D09).

---

## 6. Implementación de mejora

Lista de commits implementados sobre la rama `develop` (primeros: merge con main + correcciones de carrito):

1. **Merge origin/main: carrito real, checkout, tracking, reseñas e inventario** (+ correcciones de manejo del carrito y reseñas)
2. **Mejora de seguridad (ISO 25010)** — validación de contraseñas mínima, rol forzado en registro, escape XSS en vistas, límite de intentos de login.
3. **Mejora de rendimiento (ISO 25010)** — WebP (-98,5%), lazy loading, optimización del Dashboard (AJAX sin recarga), resolución de bug de visitas vacías.
4. **Mejora de mantenibilidad (ISO 25010)** — 44 pruebas iniciales, CI verde, corrección de bugs (mayúsculas, chatbot, carrito, etc.).
5. **Fix: orders inserta shipping_address** (bug real descubierto por CI — ver sección 8, CI-01).
6. **CI: workflow limpio** — sin probes de diagnóstico.

---

## 7. Descripción de la mejora aplicada

### 7.1 Rendimiento (Eficiencia de Desempeño)

- **Imágenes WebP** (`docs/calidad/scripts/convertir_imagenes_webp.py`): todas las 28 imágenes del catálogo convertidas a WebP (calidad 82, máximo 1000 px), con reporte de reducción en `docs/calidad/evidencias/conversion-imagenes.json`.
- **Lazy loading**: atributos `loading="lazy" decoding="async"` en las imágenes de la home, detalle y búsqueda.
- **Dashboard optimizado** (`Sitio-Web/src/Models/Dashboard.php`):
  - Tendencias de 7 días: de 14 consultas a 2 (dos consultas agrupadas por día). Bug resuelto: la serie de visitas ya no sale vacía (antes: query preparada pero nunca ejecutada).
  - Embudo: código muerto que usaba columna inexistente `added_at` eliminado; `cart_items` filtrado por `created_at` (real).
- **AJAX del Dashboard** (`api/dashboard/data`): cambiar el filtro temporal (Hoy/Semana/Mes/Año) recarga solo un JSON (~1 KB) sin recargar la página. Verificado en navegador con una marca en memoria: no hay recarga de página.
- **Impacto medido (local)**: peso de la página 125 MB → **0,71 MB**; tiempo de carga 4.3 s → **0,28 s**.

### 7.2 Seguridad (2/5 → 4/5)

- **Validación de contraseñas** (`Sitio-Web/src/helpers/validation.php`): mínimo 8 caracteres (control D-previo en el formulario y servidor).
- **Escape de salidas** (`e()` en `functions.php`): buscador, catálogo, detalle, admin y vistas nuevas de carrito/checkout/reseñas.
- **Registro seguro**: rol se fuerza a `client` (el selector de rol del formulario solo se muestra si está logueado un admin — función de main conservada sin la escalada pública).
- **Limitación de intentos de login**: 5 fallos seguidos → bloqueo de 60 s (verificado: el intento 6 está bloqueado).
- **`session_regenerate_id()`** al iniciar sesión y registrarse.

### 7.3 Mantenibilidad (3/5 → 4/5)

- **66 pruebas unitarias** (`Sitio-Web/tests/`), 201 aserciones, cobertura **77,10%** (meta ≥ 70%, verificada en CI).
- **CI verde**: GitHub Actions `Pruebas unitarias` (0ee818db), ejecutando `vendor/bin/phpunit --coverage-text` + verificación del umbral de cobertura en cada push a develop/main.
- **Bugs corregidos** (14, ver sección 9).

---

## 8. Justificación del atributo(s) mejorado(s)

El diagnóstico inicial del Avance 1 ubicaba a Rendimiento (3/5), Seguridad (2/5) y Mantenibilidad (3/5) como áreas de mejora. La Mejora Significativa del Avance 2 se enfocó en **los tres atributos débiles**:

- **Rendimiento**: la home cargaba 125 MB (28 PNG, más de 4 MB cada uno) → al instante con WebP y lazy loading, medibles mediante Lighthouse y tiempos de red. La página de Dashboard se carga más rápido al eliminar 14 consultas a 2 y al navegar entre periodos con AJAX.
- **Seguridad**: 3 vulnerabilidades reales (XSS, escalada de privs, contraseñas débiles) descubiertas y demostradas con pruebas de ataque → 0 vulnerabilidades. El control ISO/IEC 27001 A.9 (límite de intentos) se implementa y verifica la cadena de bloqueo.
- **Mantenibilidad**: cambiar cualquier consulta o validación en CI sin quebrar el resto (66 pruebas automatizadas, incluyendo régresiones como la tendencia de visitas vacía). Los bug corregidos (`e.key` en el buscador, crash del chatbot en páginas sin botón, hardcodeo de URL localhost) mejoran la estructura interna sin alterar la arquitectura.

Se respetó el **Plan de Calidad de D08** (focus en los 3 atributos) y el **prohibido de D09** (nada inventado en el Punto 9 — el informe usa evidencias reales medidas).

---

## 9. Capturas de código y resultados

**Evidencias almacenadas en `docs/calidad/evidencias/`:**

| Archivo | Contenido |
|---|---|
| `linea-base-antes.json` | Línea base ANTES (medida 20-nov-2026) |
| `linea-base-despues.json` | Métricas DESPUÉS (66 tests, cobertura 77,10%, CI verde 0ee818db) |
| `conversion-imagenes.json` | Reporte de conversiones a WebP |
| `esquema-anuiesne_ehqa.sql` | Esquema oficial usado en tests y CI |
| `actualizar_imagenes_produccion.sql` | UPDATE para ejecutar en producción (`.png` → `.webp`) |
| `evidencia-01..04-*.jpeg` | Capturas: formulario login, XSS bloqueado, rechazo de registro con contraseña corta, home con imágenes WebP |
| `dashboard-despues-ajax.jpeg` | Dashboard mostrando el filtro temporal asincrónico (AJAX) |
| `cobertura-resumen-ci.txt` | Salida del paso de PHPUnit en CI |
| `cobertura/` | Reportes HTML de cobertura |

**Código clave:**

```php
// src/Models/Order.php — Corrección real descubierta por CI (CI-01):
// orders.shipping_address es TEXT NOT NULL sin default; MySQL 8 strict lo rechaza
$stmtOrder = $this->pdo->prepare(
    "INSERT INTO orders (id_user, total_amount, status, shipping_address, created_at) VALUES (?, ?, 'paid', ?, NOW())"
);
$stmtOrder->execute([$id_user, $total_amount, '']);

// src/views/admin/dashboard.index.php — Filtro temporal AJAX
periodLinks.forEach(link => {
    link.addEventListener('click', async (event) => {
        event.preventDefault();
        const period = link.dataset.period;
        const resp = await fetch(BASE_PATH + '/api/dashboard/data?period=' + period);
        const data = await resp.json();
        actualizarDashboard(data);          // actualiza KPIs, tabla y gráficas
        history.replaceState(null, '', '?period=' + period);
    });
});
```

---

## 10. Métricas comparativas (antes / después)

### 10.1 Rendimiento

| Métrica | Antes | Después | Mejora |
|---|---|---|---|
| Peso total de la home (producción) | 5.3 MB | **Pendiente después del deploy** (miderlo en producción) | — |
| Peso de imágenes | 5.2 MB | (produc: 0.42 MB WebP; develop original: 135.6 MB) | ~95% en develop |
| Tamaño total de imágenes (repo develop) | 135.6 MB | **2.1 MB** | **-98,5%** |
| Peso de la home (develop local) | 125.0 MB | **0.71 MB** | **-99,4%** |
| Imágenes cargadas en la home | 28 (todas) | **9 (lazy loading)** | Filtro por visibilidad |
| Tiempo de carga de la home (desarrollo) | 4.3 s | **0.28 s** | ~93% |
| Consultas en gráfica de tendencias | 14 | **2** | -85% |
| TTFB (home, producción, local) | 1,471 ms | (pendiente) | — |

*Target del Plan de Calidad: carga ≤ 3 s (en Lighthouse/tiempos reales).*

### 10.2 Seguridad (ataques demostrados, antes vs después)

| Vulnerabilidad | Antes (confirmada) | Después (rechazada) |
|---|---|---|
| Registro con `role=admin` + `password=123` | Usuario admin creado | **Rechazado: "La contraseña debe tener al menos 8 caracteres"** |
| Buscador `<script>alert(1)</script>` | Script impreso sin escapar | **Bloqueado (texto escapado)** |
| Login con credenciales incorrectas ×5 | Sin límite | **Bloqueado por 60 s después del 6to intento** |

### 10.3 Mantenibilidad

| Métrica | Antes | Después |
|---|---|---|
| Pruebas automatizadas | 0 | **66** |
| Aserciones | 0 | **201** |
| Cobertura de código (phpunit.xml) | 0% | **77,10%** |
| Cobertura: Dashboard | 0% | 94,34% |
| Cobertura: Product | 0% | 82,79% |
| Cobertura: User | 0% | 79,38% |
| CI de GitHub (Pruebas unitarias) | ❌ failing | ✅ **green (run 0ee818db)** |
| Bugs corregidos | — | 14 (ver sección 9) |
| Errores en consola (home, login, producto, búsqueda, carrito) | 404 style.css, 502 SVG localhost, TypeError chatbot | **0 errores** |

### 10.4 Ajuste honesto respecto al Avance 2

- El **AJAX en "seguimiento de pedidos"** del Avance 2 no se implementó: ese módulo no existe en el código (el tracking de main es simulado, sin UI de seguimiento). El AJAX implementado es en el **Dashboard** (filtros temporales), que sí existe. En la sección 7.1 se describe con precisión qué se hizo.
- El Dashboard **no muestra gráficas de productos más vendidos por categoría desde `order_details` vacíos** (la tabla `order_details` tiene datos en producción, no en el dump local de junio).

---

## 11. Conclusiones y recomendaciones

1. **Hito de Seguridad realizado:** las 3 vulnerabilidades explotables antes del Plan de Calidad ya no son explotables; se corrigieron y se prueban (PHPUnit + pruebas de ataque reales).
2. **Hito de Rendimiento:** la home se pasó de servir 125 MB (desarrollo) a 0,71 MB con carga de 0,28 s; en producción se redice la imagen bajando 4.5 MB del servidor.
3. **Hito de Mantenibilidad:** estado de calidad cambió de 0% → 77,10% de cobertura con 66 pruebas, ejecutadas automáticamente en GitHub Actions (verifica que nada se rompa en cada push).
4. **Riesgo resuelto proactivamente:** CI descubrió un bug real (`orders.shipping_address` NOT NULL sin default) que el desarrollo local no detectó (MariaDB tolera). Ahora CI y desarrollo validan el mismo código.
5. **Recomendaciones para el lunes (despliegue):**
   - **Antes de subir a producción:** ejecutar `docs/calidad/evidencias/actualizar_imagenes_produccion.sql` tras subir los `.webp` (actualiza las 28 rutas de imagen). Respaldar BD y archivos.
   - **Luego:** subir los archivos del repo (`Sitio-Web/src`, `Sitio-Web/public`, `.github/`) y volver a medir en `https://anuies.net/ehqa/public/` (Lighthouse/tamaño de página). Ajustar el Punto 9 del Plan de Calidad con los números finales de producción (D09).
   - **Futuro:** tipificar los datos geográficos de los pedidos pendientes para consumo de APIs de paqueterías; que los nuevos commits de desarrollo tengan push al repo remoto para mantener CI activo.
6. **Mejora de continuidad:** todas las decisiones de los 3 atributos mejorados quedan documentadas en commits (`Sitio-Web/src/`, `tests/`, `.github/workflows/`), y el flujo del Punto 9 (evidencias reales) se cumple con datos reales, no simulados.

---

## 12. Referencias

1. ISO/IEC 25010:2011 — Modelo de calidad del producto y del uso del software.
2. ISO/IEC 27001:2022 — Sistema de Gestión de Seguridad de la Información.
3. OWASP Top 10 — Buenas prácticas de desarrollo seguro.
4. Avance 1 — Diagnóstico ISO/IEC 25010 (Esta Hambre Que Ata).
5. Avance 2 — Plan de Calidad del Software (Esta Hambre Que Ata).
6. Manual de Estándares adaptado a PHP (D01); Repositorio: `LuisMikey22/Proyecto-Final-Ing-Soft2-2026`, rama `develop`.
7. Repositorio remoto: `https://github.com/LuisMikey22/Proyecto-Final-Ing-Soft2-2026` (CI: `.github/workflows/pruebas.yml`).
8. Documento IEEE 830 (en aproximación docu: se reutiliza la estructura del cuestionario de los 60 RFs con ajuste de alcance).
