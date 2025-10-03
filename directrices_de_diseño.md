¡Vamos a pulirlo! Primero, un mini‑diagnóstico de la versión anterior y qué mejoro ahora:

- Más accionable: añado umbrales claros por categoría (peso, JS ejecutable, fuentes, terceros, Core Web Vitals, CO2/visita) y cómo se validan.
- Más operable: incluyo plantillas listas para usar (config del CLI, carbon.txt y CI) para que GEMINI CLI actúe como “guardián” automático.
- Más trazable: conecto cada directriz con KPIs y con las prácticas de Green UX, PUE y Green Hosting de tus documentos.
- Más realista: presupuestos por defecto conservadores, con notas de ajuste por contexto.

Directrices GEMINI CLI de Diseño Web Sostenible v2 (25 puntos)

Estrategia y gobernanza (Shift‑Left)
1) Mandato Shift‑Left: la sostenibilidad se define en el brief y vive en el repo. GEMINI CLI debe generar una política eco y un checklist de PR desde el inicio.
2) Presupuestos obligatorios: por defecto, CO2/visita ≤ 0.5 g, carga inicial ≤ 1 MB, JS ejecutable inicial ≤ 150 KB, recurso LCP ≤ 100 KB, CLS ≤ 0.1. El build falla si se superan.
3) Transparencia por diseño: crear/actualizar carbon.txt en cada release con método de cálculo, hosting, PUE, CDN, métricas de CO2 y CWV.
4) Content‑first y mobile‑first: el esqueleto que genera el CLI prioriza contenido esencial y layouts móviles; desktop es progresivo.
5) Responsables claros: en la plantilla del repo, asigna owners de accesibilidad, rendimiento/CO2 y hosting. La sostenibilidad tiene dueños.

Frontend eficiente (código y activos)
6) HTML primero y mejora progresiva: la página es útil sin JS. El CLI falla si el “render sin JS” no entrega contenido base.
7) Imágenes responsables: pipeline a AVIF/WebP, srcset/sizes, dimensiones explícitas, decoding=async; solo preload del LCP. Límite: total imágenes inicial ≤ 250 KB.
8) Lazy loading por defecto: loading=lazy en imágenes/iframes/videos fuera del viewport; fetchpriority=high solo para el LCP.
9) Fuentes sobrias: prioriza sistema; si webfonts, subset + variable font + font-display: swap + preconnect. Límite: ≤ 2 archivos, ≤ 100 KB total.
10) CSS crítico y mínimo: extrae CSS crítico inline; purga utilidades no usadas. Presupuesto: CSS inicial ≤ 35 KB gzip.
11) JavaScript con dieta: tree‑shaking, code‑splitting y dynamic import. Evita frameworks pesados si no son necesarios (SSG preferente). Límite: tiempo de main thread ≤ 2 s en móvil de gama media.
12) Terceros con justificación: lista blanca estricta, preferible self‑host y sin fingerprinting. Límite: ≤ 1 script de terceros en la carga inicial.
13) Animaciones con cabeza: CSS sobre JS, respeta prefers-reduced-motion; video sin autoplay, preload=metadata. Nada de sliders por defecto.
14) Accesibilidad WCAG 2.1 AA: semántica, foco visible, contraste, ALT en media, navegación por teclado. Tests automáticos integrados en el CLI.
15) Modo bajo consumo: si Save‑Data o prefers‑reduced‑data, reduce calidad de media, desactiva efectos y recorta requests no esenciales.

Red, servidor e infraestructura
16) Compresión y protocolos: Brotli para texto, HTTP/2 o HTTP/3, TLS 1.3. Reduce redirecciones y usa preconnect/dns‑prefetch cuando aplique.
17) Caché inteligente: assets estáticos con immutable y max‑age largo; HTML/API con ETag/Last‑Modified. Incluye plantilla de Service Worker para visitas repetidas.
18) CDN verde y cercana: configura CDN con energía renovable y edge cercano al usuario. Asegura compressión y image‑transforms en el edge.
19) Green hosting verificable: valida con The Green Web Foundation y exige PUE objetivo ≤ 1.2 (o el menor disponible documentado).
20) Arquitectura estática por defecto: SSG/ISR como primera opción; SSR solo con justificación de valor. Menos servidor = menos energía.
21) Eficiencia de datos: limita payloads de API, pagina y filtra; comprime respuestas y evita N+1. Límite: HTML inicial ≤ 50 KB, JSON inicial ≤ 50 KB.

Green UX y sostenibilidad social
22) Menos pasos, más claridad: optimiza “tiempo a tarea completada” y reduce páginas/flows. Green UX = menos bytes + mejor experiencia.
23) Inclusión real: prueba en móviles modestos y redes 3G/4G. La UX debe ser rápida y legible en condiciones adversas.
24) Privacidad por defecto: analítica sin cookies cuando sea posible, formularios mínimos, no retargeting. Privacidad, rendimiento y sostenibilidad se refuerzan.

Medición y mejora continua
25) Medir para mejorar: integra CO2.js en build/CI, Lighthouse y Core Web Vitals con gates (Rendimiento/Accesibilidad ≥ 90; LCP ≤ 2.5 s; INP ≤ 200 ms; CLS ≤ 0.1). Publica tendencias en carbon.txt y changelog.

Artefactos listos para usar

1) Configuración base del proyecto (gemini.config.yml)
```yaml
# gemini.config.yml
budgets:
  co2_per_visit_g: 0.5
  page_weight_initial_kb: 1000
  js_executable_initial_kb: 150
  css_initial_kb: 35
  images_initial_kb: 250
  html_initial_kb: 50
  json_initial_kb: 50
  third_party_scripts_max: 1
  lcp_resource_kb: 100
  cwv:
    lcp_ms: 2500
    inp_ms: 200
    cls: 0.1
hosting:
  require_green: true
  verify_tgwf: true
  target_pue: 1.2
cdn:
  enabled: true
  image_transforms: [avif, webp]
  edge_compression: brotli
images:
  formats: [avif, webp]
  quality_avif: 45
  quality_webp: 70
  generate_srcset: true
fonts:
  prefer_system_stack: true
  max_files: 2
  max_total_kb: 100
  subset: true
accessibility:
  wcag_level: "2.1 AA"
  automated_tests: true
performance:
  ssr_allowed: false
  ssg_default: true
  service_worker_template: true
ci:
  fail_on_budget_violation: true
  lighthouse_min_score: 90
  report_artifacts: ["lighthouse.json","co2.json","carbon.txt"]
```

2) Plantilla de carbon.txt
```txt
# carbon.txt
project: Sitio GEMINI CLI
version: 1.0.0
date: 2025-10-02
methodology:
  tool: CO2.js
  dataset: The Green Web Foundation + Ember
  notes: Estimaciones según modelo Sustainable Web Design
infrastructure:
  hosting_provider: <nombre>
  green_verified: true
  pue: 1.20
  cdn: <proveedor> (nodos energía renovable)
budgets:
  co2_per_visit_g: 0.5
  page_weight_initial_kb: 1000
  js_executable_initial_kb: 150
  images_initial_kb: 250
  lcp_resource_kb: 100
core_web_vitals:
  lcp_ms_p75: <valor>
  inp_ms_p75: <valor>
  cls_p75: <valor>
accessibility:
  wcag: "2.1 AA"
  audit_tool: <axe/pa11y>
commit: <sha>
changes_since_previous: <resumen de mejoras y ahorro estimado>
```

3) Gate de CI (GitHub Actions de ejemplo)
```yaml
name: eco-check
on: [push, pull_request]
jobs:
  audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with: { node-version: 20 }
      - run: npm ci
      - run: npm run build
      - run: npm run audit:co2   # usa CO2.js y genera co2.json
      - run: npm run audit:lighthouse -- --output=json --output-path=./lighthouse.json
      - run: npm run audit:accessibility
      - run: npx gemini-cli check --config gemini.config.yml
```

Métricas clave y cómo se validan
- CO2/visita: CO2.js en build/CI con dataset público. Publica resultado en carbon.txt.
- Core Web Vitals: Lighthouse en CI y, si es posible, telemetría de campo (RUM) para p75.
- Presupuestos de recursos: auditorías automatizadas del CLI (peso de HTML/CSS/JS/imágenes, número de terceros).
- Infraestructura: verificación de hosting verde con The Green Web Foundation y reporte de PUE documentado por proveedor.

Cómo se traduce esto en GEMINI CLI (orientativo)
- gemini init --green: crea estructura, gemini.config.yml, pipelines de imagen, headers de caché y carbon.txt.
- gemini audit:co2 / audit:perf / audit:a11y: ejecuta CO2.js, Lighthouse y pruebas de accesibilidad.
- gemini check: aplica los budgets y falla si hay violaciones.
- gemini fix: sugiere optimizaciones (bajar calidad de imagen, recortar bundles, eliminar terceros).

Notas de aplicación
- Los valores propuestos son un buen punto de partida; ajústalos por contexto (tipo de sitio, audiencia y contenido). Mantén siempre la filosofía “menos es más”: menos bytes, menos energía, mejor UX.
- Prioriza arquitectura estática y Green UX: accesibilidad, claridad, menos pasos y respeto a preferencias del usuario. Esto alinea sostenibilidad ambiental, social y económica de tus documentos.

¿Quieres que convierta esto en una checklist de PR o en un archivo JSON en lugar de YAML? Puedo entregarte ambas variantes en el siguiente paso.