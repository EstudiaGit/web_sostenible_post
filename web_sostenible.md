- Un **informe ejecutivo** en español (MVP entregable).
- **20+ fuentes** anotadas (bibliografía mínima exigida: aquí hay 20 seleccionadas y descritas).
- Una **checklist técnica priorizada** para equipos (UX / Front / Back / Infra).
- **3 auditorías ejemplo** reproducibles usando Website Carbon (resultados públicos), más instrucciones y comandos para reproducir la auditoría (cómo obtener bytes con PageSpeed/WebPageTest y cómo usar la API de Website Carbon o CO2.js).
- Unos **siguientes pasos** recomendados (implementación rápida / mediana / larga).

Todo está basado en las fuentes más relevantes (W3C WSG, Sustainable Web Design, Website Carbon, Green Web Foundation, CO2.js, artículos y papers sobre _digital sobriety_ y modelos de cálculo). Las referencias clave están citadas en cada sección.

---

# Informe ejecutivo (MVP — 1 página)

**Qué**: Las _Directrices de Diseño Web Sostenible_ (Web Sustainability Guidelines, WSG) son un conjunto de recomendaciones medibles y prácticas para reducir la huella ambiental de sitios y servicios web — cubren UX, desarrollo, infraestructura y gobernanza. ([W3C][1])

**Por qué importa**: El peso de las páginas y la ineficiencia en delivery y hosting se traducen en consumo eléctrico y emisiones. Herramientas como Website Carbon y librerías como CO2.js permiten estimar emisiones por carga de página; buenas prácticas (optimizar imágenes, reducir JS, hospedar con energía renovable, minimizar requests) bajan tanto emisiones como costes de infraestructura y mejoran la experiencia usuario. ([Website Carbon Calculator][2])

**Hallazgos rápidos (MVP)**:

- Existen ~94 recomendaciones WSG con criterios medibles; son compatibles con prácticas de performance (Core Web Vitals / Lighthouse). ([Sustainable Web Design][3])
- Herramientas prácticas: Website Carbon (estimación pública), CO2.js (library), PageSpeed / WebPageTest / Lighthouse (métricas de peso y UX). ([Website Carbon Calculator][4])
- Promesa de impacto rápido: priorizando **optimización de imágenes**, eliminación de scripts terceros innecesarios y reducción de payload total se consigue la mayor reducción de emisiones por unidad de esfuerzo. (Regla práctica: atacar lo que representa >50% de bytes de la página). ([Website Carbon Calculator][4])

**Recomendación prioritaria (0–3 meses)**:

1. Implementar un **performance & carbon triage**: medir peso por página, identificar los 3 recursos más pesados, reemplazar/optimizar (formatos modernos, lazy-load), y evaluar host (¿energía renovable?).
2. Añadir en el pipeline CI/CD una auditoría automática (PageSpeed API + CO2.js) para cada deploy de producción.
3. Definir KPI: `g CO2 / pageview` objetivo (ej. <0.5 g/pageview para páginas clave) y `TotalBytes <= 1.6 MiB` por página (guía Lighthouse). ([Chrome for Developers][5])

---

# Auditorías ejemplo (MVP: 3 URLs — resultados públicos de Website Carbon)

> Nota: Website Carbon muestra estimaciones por URL; debajo incluyo cómo reproducir estos tests automáticamente (comandos y API).

1. **EL PAÍS — página de artículo (ejemplo detectado)**

   - Resultado Website Carbon: existe una prueba pública para una URL de elpais.com (ej. artículo) con calificación **C** (testado 25 Oct 2024). Página mostrada en Website Carbon. ([Website Carbon Calculator][6])
   - Interpretación rápida: calificación C → sitio con margen de mejora (optimización de imágenes/media / revisar hosting). Website Carbon indica posible reducción si hosting es sostenible. ([Website Carbon Calculator][6])

2. **Greenpeace (global)**

   - Resultado Website Carbon: página `greenpeace.org/global` con calificación **A** (testado 18 Feb 2025). Indica site eficiente y/o hospedado con energía renovable. ([Website Carbon Calculator][7])

3. **El Confidencial — ejemplo de artículo (ejemplo público en Website Carbon)**

   - Resultado Website Carbon: artículo con calificación **B** (testado 27 Jul 2025 en la entrada mostrada). ([Website Carbon Calculator][8])

**Cómo reproducir la auditoría (procedimiento reproducible, comandos):**

A) **Obtener peso de la página (bytes transferidos)** — usar PageSpeed Insights API (ejemplo cURL):

```bash
# Reemplaza API_KEY por tu clave de Google Cloud
curl "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://elpais.com&key=API_KEY" \
  | jq '.lighthouseResult.audits["total-byte-weight"].numericValue'
```

(Esto devuelve bytes totales reportados por Lighthouse / PageSpeed). ([Kinsta®][9])

B) **Calcular emisiones con Website Carbon API** (endpoint `/data`) — ejemplo:

```bash
# bytes: valor obtenido; green: 1 si hosting verde, 0 si no
curl "https://api.websitecarbon.com/data?bytes=1500000&green=0"
```

(Respuesta JSON con `co2` estimado y `rating`). ([api.websitecarbon.com][10])

C) **Automatizar con CO2.js (Node.js)** — ejemplo básico (usar la librería para estimar con modelo local):

```js
// npm i @greenwebfoundation/co2
const co2 = require("@greenwebfoundation/co2")(
  // nombre ilustrativo
  async () => {
    const result = await co2.estimate({
      bytes: 1500000,
      region: "ES", // opcional para mix energético local
    });
    console.log(result); // gCO2 / pageview etc.
  }
)();
```

(Ver repositorio CO2.js para ejemplos y parámetros). ([GitHub][11])

**Limitaciones de la estimación**: Website Carbon y CO2.js estiman a partir de bytes transferidos + supuestos sobre mix energético; resultados son aproximaciones con incertidumbre (depende de la exactitud del `bytes`, del mix energético del datacenter y de la metodología). Siempre documentar supuestos (región, si hosting es verde, cache, PV hits). ([Website Carbon Calculator][2])

---

# Checklist técnico priorizada (rápidas / medianas / largas)

**Rápidas (impacto alto, esfuerzo bajo — 0–2 semanas)**

- Comprimir y servir imágenes en formatos modernos (AVIF/WebP); activar `srcset` y `lazy-loading` para imágenes no críticas.
- Eliminar _autoplay_ en vídeos; convertir vídeos pesados a thumbnails y lazy-load o usar streaming adaptativo.
- Reemplazar icon-fonts personalizados por SVG sprites o system fonts.
- Auditar scripts de terceros (analytics, widgets) y eliminar los no esenciales (o hacer tag manager asíncrono y por consentimiento).
- Implementar cache HTTP apropiado (CDN + long cache control para assets versionados).
  (Impacto: alto; esfuerzo: bajo). ([Website Carbon Calculator][4])

**Medianas (impacto medio, esfuerzo medio — 2–8 semanas)**

- Reducir y dividir bundles JS (code-splitting), eliminar polyfills innecesarios.
- Implementar Critical CSS / cargar el resto de CSS asíncronamente.
- Usar fuentes del sistema o hosting de fuentes optimizado; limitar variantes de fuente.
- Hacer pruebas A/B de versiones ligeras para medir impacto UX vs ahorro de bytes.
  (Impacto: medio-alto; esfuerzo: medio). ([Chrome for Developers][5])

**Largas (impacto alto, esfuerzo alto — 2–6 meses)**

- Rediseñar páginas principales para una versión _low-carbon_ (p. ej. versión core para usuarios con conexiones lentas).
- Migrar a hosting con energía renovable o contratos de energía renovable (o cloud con compromisos claros de energía).
- Establecer governance (policy, KPIs, incorporación de `g CO2/pageview` en dashboard de producto).
  (Impacto: alto; esfuerzo: alto). ([Sustainable Web Design][3])

---

# Taxonomía mínima operativa (conceptos clave — definiciones operacionales cortas)

- **Eco-design / Sustainable Web Design**: diseño digital que reduce consumo de recursos y emisiones, sin sacrificar accesibilidad ni usabilidad. ([Sustainable Web Design][3])
- **Digital sobriety**: estrategia organizacional para reducir uso digital (menor streaming, optimizar almacenamiento, duración de dispositivos). ([Wavestone][12])
- **Low-carbon UX**: decisiones de UX que priorizan menor consumo (p. ej. no autoplay, paginación en vez de infinite scroll).
- **Carbon budget (digital)**: asignación máxima de emisiones permitidas para un servicio en un periodo (horario/día/mes). ([ScienceDirect][13])
- **Performance budget**: límite en KB / requests / JS para mantener objetivos de UX y, secundariamente, emisiones. ([Chrome for Developers][5])

---

# Lista anotada de 20 fuentes (selección clave)

> (Cada entrada: título — porqué es útil — nota corta)

1. **W3C — Introducing Web Sustainability Guidelines (WSG)** — oficial sobre WSG 1.0; punto de partida normativo. ([W3C][1])
2. **Web Sustainability Guidelines (sustyweb GH pages)** — versión técnica y lista de directrices (94 recomendaciones). ([W3C GitHub][14])
3. **Sustainable Web Design — guidelines (sustainablewebdesign.org)** — guía práctica con ejemplos y recursos. ([Sustainable Web Design][3])
4. **Website Carbon — Calculator & methodology** — la herramienta pública más usada para estimaciones rápidas. Incluye API. ([Website Carbon Calculator][4])
5. **CO2.js (Green Web Foundation)** — librería para estimar emisiones desde código, integrable en pipelines. ([GitHub][11])
6. **Wholegrain Digital — Blog / Website Carbon rating system** — discusión sobre rating y recursos de consultoría. ([Wholegrain Digital][15])
7. **The Shift Project — Lean ICT / Digital sobriety report** — análisis influyente sobre consumo digital y propuestas de sobriedad digital. ([The Shift Project][16])
8. **Wavestone — Digital Sobriety (2024)** — enfoque empresarial y tácticas. ([Wavestone][12])
9. **WebPageTest docs / metrics** — fuente técnica para métricas reproducibles (bytes, TTFB, etc.). ([docs.webpagetest.org][17])
10. **PageSpeed Insights API docs (Google)** — cómo extraer métricas programáticamente (Lighthouse-like). ([Google for Developers][18])
11. **Wired — 'Your website is killing the planet'** — artículo divulgativo que resume el problema y ejemplos. Útil para stakeholders no técnicos. ([WIRED][19])
12. **Greenpeace — informes y recursos sobre energía y emisiones** — contexto climático y metodologías de reporting. ([Greenpeace][20])
13. **Academic / ScienceDirect — Adaptive Green Cloud Applications (2025)** — artículos recientes sobre presupuestos de carbono operativos. ([ScienceDirect][13])
14. **Tom Greenwood / Sustainable Web Design resources (Wholegrain)** — prácticas probadas y casos. ([Website Carbon Calculator][4])
15. **Web.dev / Lighthouse docs — Total byte weight guidance** — objetivo técnico: <1.6 MiB sugerido para buena performance. ([Chrome for Developers][5])
16. **GitHub topic: co2-emissions repos** — código y herramientas comunitarias para medir emisiones digitales. ([GitHub][21])
17. **Case studies (CO2.js, Sitigreen)** — ejemplos de implementación de CO2.js. ([Green Web Foundation][22])
18. **Green Web Foundation — resources & green hosting / tools** — para verificar si hosting es "verde". (Relacionado a CO2.js). ([Green Web Foundation][23])
19. **Mercadona — informes de sostenibilidad (ejemplos corporativos de reporting)** — ejemplo de cómo empresas informan huella; útil para gobernanza. ([Mercadona][24])
20. **WebPageTest / DebugBear / Kinsta guías** — guías prácticas para ejecutar pruebas y automatización. ([Kinsta®][25])

(Si quieres, te devuelvo estas 20 en CSV/JSON con metadatos: URL, fecha, por qué relevante — lo puedo generar ahora mismo.)

---

# Plantilla rápida de política interna (1–2 líneas clave)

- KPI principal: `g CO2 / pageview (mensual)` + `TotalBytes` por página (meta: <1.6 MiB).
- Requisito de pre-lanzamiento: cada release mayor debe pasar **automated carbon check** (PageSpeed API → bytes → CO2.js/Website Carbon API) y aprobar si no aumenta >5% del KPI.
- Gobernanza: equipo Product + Infra responsable; reporte trimestral al COO/CTO. ([Sustainable Web Design][3])

---

# Siguientes pasos recomendados (prácticos)

1. **Implementación inmediata (hoy):** añade en CI un job que ejecute PageSpeed API para las pages claves y calcule `g CO2/pageview` vía CO2.js; subir resultados a CSV/JSON y crear alertas si se superan umbrales. (Te puedo darte el YAML de ejemplo para GitHub Actions ahora mismo si quieres). ([Kinsta®][9])
2. **3–6 semanas:** ejecutar auditoría completa (mapeo de 20-50 URLs principales) usando WebPageTest + Website Carbon + Lighthouse y priorizar optimizaciones por ROI (impacto/efort).
3. **3–6 meses:** migración/contrato con hosting/Cloud Carrier con política de energía renovable y crear la versión _low-carbon_ de las pantallas críticas.

---

Si quieres que haga **ya** cualquiera de las siguientes cosas, lo hago inmediatamente (en éste mismo chat — no esperes — lo ejecuto ahora):

1. **Generar el CSV/JSON con las 20 fuentes** (URL, fecha, breve resumen) y entregarlo descargable.
2. **Crear el script Node.js** (listo para CI) que: 1) llama a PageSpeed API, 2) extrae `total-byte-weight`, 3) llama a Website Carbon API o CO2.js, 4) produce CSV/JSON con `url, bytes, gCO2_per_view, rating`. (Te doy el repo/archivo listo).
3. **Probar automáticamente 3–10 URLs** (si me das las URLs) y devolverte el CSV con resultados y recomendaciones por URL.
4. **Proveer una plantilla GitHub Actions** para ejecutar auditorías en cada push.

¿Qué quieres que haga **ahora**? (elige 1, 2, 3 o 4). Si no eliges, por defecto generaré el **CSV/JSON con las 20 fuentes** y un **script Node.js** para auditar (opción 1+2).

[1]: https://www.w3.org/blog/2023/introducing-web-sustainability-guidelines/?utm_source=chatgpt.com "Introducing Web Sustainability Guidelines | 2023 | Blog"
[2]: https://www.websitecarbon.com/faq/?utm_source=chatgpt.com "FAQ - Website Carbon Calculator"
[3]: https://sustainablewebdesign.org/guidelines/?utm_source=chatgpt.com "Sustainability Guidelines Archive - Sustainable Web Design"
[4]: https://www.websitecarbon.com/?utm_source=chatgpt.com "Website Carbon™ Calculator v4 | What's your site's carbon ..."
[5]: https://developer.chrome.com/docs/lighthouse/performance/total-byte-weight?utm_source=chatgpt.com "Avoid enormous network payloads | Lighthouse"
[6]: https://www.websitecarbon.com/website/elpais-com-espana-2024-10-25-la-policia-investiga-a-errejon-por-tres-supuestos-episodios-de-violencia-sexual-html/?utm_source=chatgpt.com "elpais.com/espana/2024-10-25/la-policia-investiga-a-errejon-por ..."
[7]: https://www.websitecarbon.com/website/greenpeace-org-global/?utm_source=chatgpt.com "greenpeace.org/global - Website Carbon Calculator"
[8]: https://www.websitecarbon.com/website/elconfidencial-com-amp-alma-corazon-vida-2025-07-27-jonathan-aguilar-entrenador-cinco-alimentos-mercadona-proteina-verdad-1qrt_4177420/?utm_source=chatgpt.com "elconfidencial.com/amp/alma-corazon-vida/2025-07-27/jonathan ..."
[9]: https://kinsta.com/blog/pagespeed-insights-api/?utm_source=chatgpt.com "Use the PageSpeed Insights API to monitor page ..."
[10]: https://api.websitecarbon.com/?utm_source=chatgpt.com "Website Carbon API"
[11]: https://github.com/thegreenwebfoundation/co2.js/?utm_source=chatgpt.com "thegreenwebfoundation/co2.js: An npm module for ..."
[12]: https://www.wavestone.com/en/insight/digital-sobriety-how-to-reduce-the-environmental-impact-of-digital-usage/?utm_source=chatgpt.com "Digital Sobriety: how to reduce the environmental impact of ..."
[13]: https://www.sciencedirect.com/science/article/pii/S0167739X25004376?utm_source=chatgpt.com "Adaptive Green Cloud Applications: Balancing Emissions ..."
[14]: https://w3c.github.io/sustyweb/?utm_source=chatgpt.com "Web Sustainability Guidelines (WSG) - W3C on GitHub"
[15]: https://www.wholegraindigital.com/blog/introducing-website-carbon-rating-system/?utm_source=chatgpt.com "Introducing the Website Carbon Rating System"
[16]: https://theshiftproject.org/app/uploads/2025/04/Lean-ICT-Report_The-Shift-Project_2019.pdf?utm_source=chatgpt.com "TOWARDS DIGITAL SOBRIETY"
[17]: https://docs.webpagetest.org/metrics/page-metrics/?utm_source=chatgpt.com "Page-Level Metrics"
[18]: https://developers.google.com/speed/docs/insights/v5/about?utm_source=chatgpt.com "About PageSpeed Insights"
[19]: https://www.wired.com/story/internet-carbon-footprint?utm_source=chatgpt.com "Your website is killing the planet"
[20]: https://www.greenpeace.org/international/publication/67950/annual-report-2023/?utm_source=chatgpt.com "Annual Report 2023 - Greenpeace International"
[21]: https://github.com/topics/co2-emissions?l=javascript&o=desc&s=forks&utm_source=chatgpt.com "co2-emissions"
[22]: https://www.thegreenwebfoundation.org/news/start-calculating-digital-carbon-emissions-in-5-minutes-with-co2-js/?utm_source=chatgpt.com "calculating digital carbon emissions in 5 minutes with CO2.js"
[23]: https://www.thegreenwebfoundation.org/co2-js/?utm_source=chatgpt.com "CO2.js"
[24]: https://info.mercadona.es/document/en/annual-report-2024.pdf?utm_source=chatgpt.com "2024 Annual Report"
[25]: https://kinsta.com/blog/webpagetest/?utm_source=chatgpt.com "Guide to Using WebPageTest (& Interpreting the Results)"
