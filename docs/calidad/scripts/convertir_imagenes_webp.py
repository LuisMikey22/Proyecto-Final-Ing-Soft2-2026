# Script de optimización de imágenes del catálogo (Mejora de Rendimiento - Plan de Calidad)
# Convierte los PNG/JPEG originales a WebP y los redimensiona a un máximo de 1000 px,
# suficiente para las tarjetas del catálogo y la vista de detalle.
#
# Uso:  python docs/calidad/scripts/convertir_imagenes_webp.py
# Requisito: Pillow  (pip install pillow)
#
# Genera el reporte de tamaños antes/después en docs/calidad/evidencias/conversion-imagenes.json

import json
from pathlib import Path
from PIL import Image

RAIZ = Path(__file__).resolve().parents[3]
CARPETA_IMAGENES = RAIZ / "Sitio-Web" / "public" / "assets" / "images"
REPORTE = RAIZ / "docs" / "calidad" / "evidencias" / "conversion-imagenes.json"
LADO_MAXIMO = 1000
CALIDAD_WEBP = 82

def convertir():
    resultados = []
    total_antes = 0
    total_despues = 0

    for archivo in sorted(CARPETA_IMAGENES.iterdir()):
        if archivo.suffix.lower() not in (".png", ".jpg", ".jpeg"):
            continue  # los SVG (iconos/logo) se quedan igual

        destino = archivo.with_suffix(".webp")
        antes = archivo.stat().st_size

        with Image.open(archivo) as im:
            im = im.convert("RGB")
            im.thumbnail((LADO_MAXIMO, LADO_MAXIMO), Image.LANCZOS)
            im.save(destino, "WEBP", quality=CALIDAD_WEBP, method=6)

        despues = destino.stat().st_size
        total_antes += antes
        total_despues += despues
        resultados.append({
            "archivo_original": archivo.name,
            "archivo_webp": destino.name,
            "kb_antes": round(antes / 1024, 1),
            "kb_despues": round(despues / 1024, 1),
            "reduccion_porcentaje": round((1 - despues / antes) * 100, 1),
        })
        print(f"{archivo.name}: {antes/1024:.0f} KB -> {despues/1024:.0f} KB")

    reporte = {
        "fecha": "2026-11-20",
        "parametros": {"lado_maximo_px": LADO_MAXIMO, "calidad_webp": CALIDAD_WEBP},
        "total_imagenes": len(resultados),
        "total_mb_antes": round(total_antes / 1048576, 1),
        "total_mb_despues": round(total_despues / 1048576, 2),
        "reduccion_total_porcentaje": round((1 - total_despues / total_antes) * 100, 1),
        "detalle": resultados,
    }
    REPORTE.parent.mkdir(parents=True, exist_ok=True)
    REPORTE.write_text(json.dumps(reporte, indent=2, ensure_ascii=False), encoding="utf-8")
    print(f"\nTOTAL: {reporte['total_mb_antes']} MB -> {reporte['total_mb_despues']} MB "
          f"(-{reporte['reduccion_total_porcentaje']}%)")

if __name__ == "__main__":
    convertir()
