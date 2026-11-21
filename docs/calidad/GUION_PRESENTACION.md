# Guion de Presentación y Demostración Funcional

**Duración:** 10–12 minutos por equipo
**Formato:** Exposición oral + demo técnica en la computadora del proyecto (o pantalla compartida)
**Equipo:** Luis Miguel (líder), Ángel (desarrollador), Axdiael (evaluador)
**Dirección:** Dra. Aida Sánchez Paz subscriptible / La Paz, 20 de noviembre de 2026

---

## Preparación antes de la presentación (5 min)

1. Abrir una terminal en `Sitio-Web/` (repo clonado).
2. Habilitar la base de datos de pruebas si no está: `C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS anuiesne_ehqa_test;"` y asegurarse de que `anuiesne_ehqa_test` tiene el esquema (si no: importar `docs/calidad/evidencias/esquema-anuiesne_ehqa.sql`).
3. Poner el servidor local: `C:\xampp\php\php.exe -S localhost:8000 -t public router-dev.php` (en segundo plano).
4. Abrir el navegador en `http://localhost:8000/`.
5. Tener a mano: la URL del repositorio GitHub (`LuisMikey22/Proyecto-Final-Ing-Soft2-2026`), y los archivos de `docs/calidad/evidencias/`.

---

## Cronograma del guion

| Tiempo | Sección | Desarrollo |
|---|---|---|
| 0:00–1:00 | Apertura | Presentar a los integrantes + contexto breve del proyecto |
| 1:00–2:30 | 1. Problema detectado | Pruebas de ataque en vivo |
| 2:30–4:30 | 2. Mejora implementada | Commits y código estrella |
| 4:30–6:30 | 3. Demo técnica | Tres demos: bloqueo de ataques + Dashboard AJAX + suite verde |
| 6:30–8:00 | 4. Evidencia de resultados medibles | Tabla de métricas antes/después |
| 8:00–9:30 | 5. Conclusión y recomendaciones | Máximo + despliegue en lunes |
| 9:30–10:00 | Cierre | Preguntas + agradecimientos |

---

## 1. Problema detectado (1 min)

> "Buenas tardes. Somos Luis Miguel Lucatero Pérez (líder), Ángel Gabriel Mendoza Villavicencio (desarrollador) y Axdiael Trinidad Cárdenas (evaluador). Hoy presentamos una mejora al Plan de Calidad del Software."

Dos ejemplos breves, tomados de `linea-base-antes.json`:

> "**Aquí el sitio servía 125 MB en la home**: 28 imágenes PNG originales, varias de más de 4 MB, hacían que la página tardara cerca de 4 segundos en cargar."

> "**Y había 3 vulnerabilidades reales**, que demostramos ahora:"

## 1.1 Demo técnica A — Ataques bloqueados (3 min)

1. **Escalada de priviliegios (crítica):** En una ventana aparte, ejecutar (cambia los datos si es necesario):
   ```
   POST http://localhost:8000/account/register
   Username: Hacker Demo
   Email: hackDemo@test.com
   Contraseña: 123
   Role: admin
   ```
   → **Antes:** se creaba un usuario `admin` (lo verificamos en phpMyAdmin). **Después:** muestra el error "La contraseña debe tener al menos 8 caracteres" y ningún usuario se crea. (Luego verificar en phpMyAdmin que `hackDemo@test.com` no existe.)

2. **XSS reflejado:** Abre `http://localhost:8000/search/<script>alert(1)</script>`.
   → **Antes:** el script se imprimía sin escapar (código ejecutado). **Después:** muestra el texto escapado como texto visible, sin ejecutar.

3. **Límite de intentos de login:** Hacer 5 registros erróneos de login en `/login` (cualquier email/contraseña). En el intento 6 muestra: "Demasiados intentos fallidos. Espera un minuto e inténtalo de nuevo."

## 2. Mejora implementada (2 min)

> "Se enfocó el Plan de Calidad (D08) en los atributos débiles: **Eficiencia de Desempeño (3/5), Seguridad (2/5) y Mantenibilidad (3/5)**."

Lista concisa de los commit en desarrollo (con mensajes de los 14 commits):

```
1. Merge origin/main: carrito real, checkout, tracking, reseñas e inventario
2. Mejora de seguridad (ISO 25010): contraseñas mínimas, rol forzado en registro, escape XSS, límites de login
3. Mejora de rendimiento (ISO 25010): imágenes WebP (-98,5%), lazy loading, Dashboard optimizado + AJAX
4. Mejora de mantenibilidad (ISO 25010): 66 pruebas, CI verde, bugs corregidos
5. Fix: orders inserta shipping_address (bug real descubierto por CI)
6. CI: workflow limpio
```

## 3. Demo técnica B — Señalización de código + cambios de comportamiento (3 min)

1. **Código estrella:** en la terminal, mostrar el diff de `Order::processCheckout` (el shipping_address que CI descubrió). En el navegador, cambiar físicamente el carrito: **entrar al carrito, ir a la pestaña Red (Network), tamaño del nombre de arhivo**.
2. **Dashboard AJAX:** Ir a `http://localhost:8000/admin/dashboard` (login como admin — su contraseña admin de Axdiael, o el proporcionado por el líder). En la consola: `window.__sinRecarga = true;`. Clic en el botón "Este Año" → KPIs se actualizan. Confirmar en la consola: `window.__sinRecarga === true` (sin recarga de página).
3. **Home con WebP:** Recargar la home y en la pestaña de Red verificar que las imágenes cargan como `.webp` y el total transferido es **~1 MB** (vs 125 MB en develop antes, 5,3 MB en producción antes).

## 4. Evidencia de resultados medibles (2 min)

Tabla resumen (sacarla de `linea-base-despues.json`):

| Métrica | Antes | Después | Mejora |
|---|---|---|---|
| Peso de imágenes (repo develop) | 135.6 MB | 2.1 MB | -98,5% |
| Peso de la home (desarrollo) | 125.0 MB | 0.71 MB | -99,4% |
| Tiempo de carga home (desarrollo) | 4.3 s | 0.28 s | ~93% |
| Imágenes cargadas inicialmente | 28 | 9 (lazy) | — |
| Consultas tendencias del Dashboard | 14 | 2 | -85% |
| Vulnerabilidades explotables | 3 | 0 | — |
| Pruebas automatizadas | 0 | 66 | — |
| Cobertura (phpunit.xml) | 0% | 77,10% | meta 70% ✅ |
| CI GitHub Actions | ❌ fallando | ✅ verde (0ee818db) | — |

## 5. Conclusiones y recomendaciones (1 min)

> "Conclusión: las mejoras quedan documentadas, verificables y reproducibles."
> "Recomendaciones: primero desplegar y re-medir en producción; ejecutar el SQL de producción de imágenes; seguir con los RFC IEEE 830 en el lunes; los nuevos commits requieren `git push` al repo remoto para mantener el CI verde."

## 6. Cierre (1 min)

Agradecer a Dra. Aida, a la profe de Cátedra 6to y a la de Evaluación. Preguntas: "¿Tienen alguna pregunta sobre el flujo o las métricas?"

---

## Notas para los evaluadores

- Si preguntan por qué no se mejoró el Dashboard con AJAX en `Order.php`: explicar que el módulo de seguimiento de pedidos es simulado (no existe UI real) — se ajustó el AJAX a lo que sí existe.
- Si preguntan por la razón de `shipping_address = ''`: es solo valor por defecto en la inserción para cumplir `NOT NULL`; el flujo de tracking no captura dirección todavía.
- Si preguntan por los 0 errores de consola: se verificaron en home, login, detalle de producto, búsqueda y carrito.
