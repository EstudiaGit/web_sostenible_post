<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Productos Leprechaun - Mi Blog CRT</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=IBM+Plex+Mono:wght@400;600&display=swap"
      rel="stylesheet"
    />

    <style>
      :root {
        color-scheme: dark;
        --bg-main: #030303;
        --bg-panel: rgba(4, 12, 4, 0.94);
        --accent: #9dff78;
        --accent-strong: #54ff42;
        --text-primary: #a2ff91;
        --text-secondary: #6aed78;
        --border: 1px solid rgba(157, 255, 120, 0.7);
        --card-gap: 16px;
        --container-pad: clamp(18px, 5vw, 36px);
        font-family: "Share Tech Mono", "IBM Plex Mono", monospace;
      }

      * {
        box-sizing: border-box;
      }

      body {
        margin: 0;
        min-height: 100vh;
        background: radial-gradient(
            circle at 12% 18%,
            rgba(90, 255, 140, 0.2),
            transparent 58%
          ),
          radial-gradient(
            circle at 86% 74%,
            rgba(80, 240, 120, 0.18),
            transparent 55%
          ),
          var(--bg-main);
        color: var(--text-primary);
        padding: 16px 0 56px;
        position: relative;
        overflow-x: hidden;
      }

      body::before {
        content: "";
        position: fixed;
        inset: 0;
        background-image: linear-gradient(
            rgba(120, 255, 150, 0.08) 1px,
            transparent 1px
          ),
          linear-gradient(90deg, rgba(120, 255, 150, 0.08) 1px, transparent 1px);
        background-size: 120px 120px;
        opacity: 0.42;
        pointer-events: none;
        z-index: -2;
      }

      #node-canvas {
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
      }

      .container {
        width: 100%;
        margin: 0;
        background: var(--bg-panel);
        border: var(--border);
        padding: var(--container-pad);
        box-shadow: none;
        position: relative;
        z-index: 1;
        min-height: calc(100vh - 56px);
      }

      .container::after {
        content: "";
        position: absolute;
        inset: 6px;
        border: 1px dashed rgba(120, 255, 150, 0.27);
        pointer-events: none;
      }

      header {
        padding: clamp(20px, 6vw, 50px);
        border: var(--border);
        border-color: var(--accent-strong);
        background: rgba(1, 9, 3, 0.9);
        margin-bottom: clamp(24px, 7vw, 46px);
        position: relative;
        overflow: hidden;
      }

      header::before {
        content: "[STATUS: LINKED]";
        position: absolute;
        top: 14px;
        right: clamp(12px, 4vw, 28px);
        font-size: 0.78rem;
        letter-spacing: 4px;
        color: var(--text-secondary);
      }

      header::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          120deg,
          transparent 42%,
          rgba(157, 255, 120, 0.08),
          transparent 64%
        );
        mix-blend-mode: screen;
        pointer-events: none;
      }

      header h1 {
        margin: 0 0 14px;
        font-size: clamp(1.6rem, 5.8vw, 2.6rem);
        text-transform: uppercase;
        letter-spacing: 5px;
        padding-bottom: 12px;
        border-bottom: var(--border);
      }

      header p {
        margin: 0;
        font-size: clamp(0.9rem, 3.3vw, 1.06rem);
        color: var(--text-secondary);
        line-height: 1.6;
      }

      .nav-link {
        display: inline-block;
        margin-top: clamp(16px, 4vw, 22px);
        padding: 9px 18px;
        border: var(--border);
        color: var(--accent);
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 3px;
        transition: all 0.25s ease;
      }

      .nav-link:hover {
        background: var(--accent);
        color: #021402;
        box-shadow: 0 0 14px rgba(157, 255, 120, 0.45);
      }

      main {
        display: grid;
        gap: clamp(24px, 6vw, 38px);
      }

      .section-wrapper {
        position: relative;
      }

      .section-wrapper::before {
        content: "";
        position: absolute;
        inset: -12px;
        border: 1px dashed rgba(97, 206, 117, 0.22);
        pointer-events: none;
      }

      .section-title {
        position: absolute;
        top: -18px;
        left: clamp(10px, 6vw, 28px);
        background: #000c03;
        padding: 6px 16px;
        border: var(--border);
        font-size: 0.76rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        z-index: 2;
      }

      .section-box {
        border: var(--border);
        padding: 16px 0 32px;
        background: rgba(0, 10, 2, 0.92);
        position: relative;
      }

      .swipe-hint {
        margin: 0 0 14px;
        padding: 0 var(--container-pad);
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: rgba(157, 255, 120, 0.65);
      }

      /* --- MOBILE: carrusel táctil con overlay --- */
      .carousel {
        position: relative;
      }

      .carousel-btn {
        display: none;
      }

      .carousel-viewport {
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        scroll-snap-stop: always;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
      }

      .carousel-viewport::-webkit-scrollbar {
        width: 0;
        height: 0;
      }

      .product-grid {
        display: flex;
        flex-direction: row;
        gap: 0;
      }

      .product-card {
        flex: 0 0 100%;
        max-width: 100%;
        position: relative;
        border: none;
        padding: 0;
        background: transparent;
        scroll-snap-align: start;
        scroll-snap-stop: always;
        overflow: hidden;
      }

      .product-card img {
        display: block;
        width: 100%;
        height: auto;
      }

      .product-info {
        position: absolute;
        inset-inline: 0;
        bottom: 0;
        padding: clamp(14px, 5vw, 22px) clamp(16px, 6vw, 26px);
        background: linear-gradient(
          180deg,
          rgba(3, 10, 4, 0) 0%,
          rgba(3, 10, 4, 0.78) 40%,
          rgba(3, 10, 4, 0.92) 100%
        );
        display: flex;
        flex-direction: column;
        gap: 6px;
      }

      .product-card h3 {
        margin: 0;
        font-size: clamp(0.86rem, 4vw, 1rem);
        letter-spacing: 1px;
        text-transform: uppercase;
        line-height: 1.35;
        text-align: left;
        color: var(--text-primary);
      }

      .product-card p {
        margin: 0;
        font-size: clamp(0.92rem, 4.2vw, 1.05rem);
        font-weight: 600;
        color: var(--accent-strong);
        text-align: left;
        letter-spacing: 1px;
      }

      @media (max-width: 639px) {
        #node-canvas {
          display: none;
        }
      }

      /* --- TABLET / DESKTOP --- */
      @media (min-width: 640px) {
        body {
          padding: 32px 0 72px; /* Eliminado padding horizontal */
        }

        .container {
          max-width: 1226px; /* Ajuste de ancho según el inspector de Chrome */
          margin: 0 auto; /* Centrar el contenedor */
          box-shadow: 10px 10px 0 rgba(0, 0, 0, 0.65); /* Restaurar la sombra */
        }

        .container::after {
          inset: 12px;
        }

        .section-box {
          padding: clamp(
            18px,
            6vw,
            48px
          ); /* Aumentado el padding para más distancia */
        }

        .swipe-hint {
          display: none;
        }

        .carousel {
          display: flex;
          align-items: center;
          gap: 16px;
          overflow: hidden; /* Medida de seguridad para evitar desbordamientos */
        }

        .carousel-btn {
          display: grid;
          place-items: center;
          width: 40px;
          height: 40px;
          border: var(--border);
          background: rgba(157, 255, 120, 0.22);
          color: var(--accent);
          font-size: 1.4rem;
          cursor: pointer;
          transition: all 0.25s ease;
        }

        .carousel-btn:hover:not(:disabled) {
          background: var(--accent);
          color: #021402;
          box-shadow: 0 0 12px rgba(157, 255, 120, 0.5);
        }

        .carousel-btn:disabled {
          opacity: 0.25;
          cursor: not-allowed;
        }

        .carousel-viewport {
          overflow-x: auto; /* Allow scrolling */
          flex: 1;
          min-width: 0; /* Solución para el desbordamiento del carrusel en flexbox */
          scroll-snap-type: none;
        }

        .product-grid {
          gap: 18px;
        }

        .product-card {
          flex: 0 0 clamp(220px, 42vw, 260px);
          padding: 16px;
          border: 1px solid rgba(157, 255, 120, 0.38);
          background: rgba(4, 12, 4, 0.95);
          scroll-snap-align: unset;
          scroll-snap-stop: normal;
          position: relative;
          overflow: visible;
          animation: fadeIn 0.25s ease both;
        }

        .product-card::after {
          content: "";
          position: absolute;
          inset: 6px;
          border: 1px dashed rgba(157, 255, 120, 0.16);
          pointer-events: none;
        }

        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(12px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .product-card img {
          width: 100%;
          aspect-ratio: 3 / 4;
          object-fit: cover;
          border: 1px solid rgba(157, 255, 120, 0.38);
          background: #fff;
        }

        .product-info {
          position: static;
          background: none;
          padding: 12px 0 0;
          align-items: center;
          gap: 10px;
        }

        .product-card h3 {
          text-align: center;
          min-height: 3rem;
        }

        .product-card p {
          text-align: center;
        }
      }

      @media (min-width: 960px) {
        main > .section-wrapper {
          /* Aplicado a todas las secciones */
          max-width: 1150px;
          margin: 0 auto 2rem auto;
        }

        .carousel {
          gap: 18px;
        }

        .carousel-btn {
          width: 46px;
          height: 46px;
        }

        .product-card {
          flex: 0 0 clamp(210px, 22vw, 240px);
        }
      }

      @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
          animation-duration: 0.001ms !important;
          animation-iteration-count: 1 !important;
          transition-duration: 0.001ms !important;
          scroll-behavior: auto !important;
        }
      }
    </style>

  </head>

  <body>
    <canvas id="node-canvas" aria-hidden="true"></canvas>

    <div class="container">
      <header>
        <h1>Productos Leprechaun.es</h1>
        <p>
          Listado de productos extraídos de la web de Leprechaun.es el
          29/09/2025.
        </p>
        <a href="index.html" class="nav-link">[ VOLVER A S.Y.N.T.H. BLOG ]</a>
        <a href="comics.html" class="nav-link"
          >[ VER LISTADO COMPLETO DE CÓMICS ]</a
        >
      </header>

      <main>
        <!-- NUEVOS PRODUCTOS -->
        <section class="section-wrapper">
          <div class="section-title">[ NUEVOS PRODUCTOS ]</div>
          <div class="section-box">
            <p class="swipe-hint">Desliza para ver más productos</p>
            <div class="carousel" data-carousel="nuevos">
              <button
                class="carousel-btn prev"
                data-carousel-btn="prev"
                aria-label="Anterior"
              >
                ‹
              </button>
              <div class="carousel-viewport">
                <div class="product-grid" data-carousel-track="nuevos">
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47987-home_default/frigth-zone-playset-masters-of-the-universe-origins.jpg"
                      alt="Fright Zone Playset Masters Of The Universe Origins"
                    />
                    <div class="product-info">
                      <h3>
                        Fright Zone Playset Masters Of The Universe Origins
                      </h3>
                      <p>101,58&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47983-home_default/rom-02-la-etapa-marvel-original.jpg"
                      alt="Rom 02 La Etapa Marvel Original"
                    />
                    <div class="product-info">
                      <h3>Rom 02. La Etapa Marvel Original</h3>
                      <p>71,25&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47981-home_default/rom-01-la-etapa-marvel-original.jpg"
                      alt="Rom 01 La Etapa Marvel Original"
                    />
                    <div class="product-info">
                      <h3>Rom 01. La Etapa Marvel Original</h3>
                      <p>76,00&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47977-home_default/bohemian-rhapsody-blu-ray-4k-ultra-hd.jpg"
                      alt="Bohemian Rhapsody Blu-Ray 4K Ultra HD"
                    />
                    <div class="product-info">
                      <h3>Bohemian Rhapsody Blu-Ray 4K Ultra HD</h3>
                      <p>16,96&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47975-home_default/rambo-acorralado-blu-ray-4k-ultra-hd.jpg"
                      alt="Rambo Acorralado Blu-Ray 4K Ultra HD"
                    />
                    <div class="product-info">
                      <h3>Rambo: Acorralado Blu-Ray 4K Ultra HD</h3>
                      <p>16,96&nbsp;€</p>
                    </div>
                  </article>
                </div>
              </div>
              <button
                class="carousel-btn next"
                data-carousel-btn="next"
                aria-label="Siguiente"
              >
                ›
              </button>
            </div>
          </div>
        </section>

        <!-- LOS MÁS VENDIDOS -->
        <section class="section-wrapper">
          <div class="section-title">[ LOS MÁS VENDIDOS ]</div>
          <div class="section-box">
            <p class="swipe-hint">Desliza para ver más productos</p>
            <div class="carousel" data-carousel="vendidos">
              <button
                class="carousel-btn prev"
                data-carousel-btn="prev"
                aria-label="Anterior"
              >
                ‹
              </button>
              <div class="carousel-viewport">
                <div class="product-grid" data-carousel-track="vendidos">
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/3475-home_default/figura-18-cm-police-disguise-t-1000-terminator-genysis.jpg"
                      alt="Figura 18 cm Police Disguise T-1000 Terminator Genysis"
                    />
                    <div class="product-info">
                      <h3>
                        Figura 18 cm Police Disguise T-1000 Terminator Genysis
                      </h3>
                      <p>33,58&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/2019-home_default/figura-scout-trooper-star-wars-black-series-archive-15-cm.jpg"
                      alt="Figura Scout Trooper Star Wars Black Series Archive 15 cm"
                    />
                    <div class="product-info">
                      <h3>
                        Figura Scout Trooper Star Wars Black Series Archive 15
                        Cm
                      </h3>
                      <p>25,08&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/17839-home_default/figura-spider-man-2099-15-cm-marvel-legends-vintage-f0230.jpg"
                      alt="Figura Spider-Man 2099 15 cm Marvel Legends Vintage"
                    />
                    <div class="product-info">
                      <h3>
                        Figura Spider-Man 2099 15 Cm Marvel Legends Vintage
                        (F0230)
                      </h3>
                      <p>42,08&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/2965-home_default/vehiculo-star-wars-resistance-a-wing-fighter-c1249c1248-force-link.jpg"
                      alt="Vehículo Star Wars Resistance A-Wing Fighter"
                    />
                    <div class="product-info">
                      <h3>
                        Vehículo Star Wars Resistance A-Wing Fighter (Force
                        Link)
                      </h3>
                      <p>27,65&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/12575-home_default/figura-dr-doom-15-cm-marvel-legends-f2796-baf-xemnu.jpg"
                      alt="Figura Dr. Doom 15 cm Marvel Legends"
                    />
                    <div class="product-info">
                      <h3>Figura Dr. Doom 15 Cm Marvel Legends (BAF: Xemnu)</h3>
                      <p>25,08&nbsp;€</p>
                    </div>
                  </article>
                </div>
              </div>
              <button
                class="carousel-btn next"
                data-carousel-btn="next"
                aria-label="Siguiente"
              >
                ›
              </button>
            </div>
          </div>
        </section>

        <!-- CÓMICS -->
        <section class="section-wrapper">
          <div class="section-title">[ CÓMICS ]</div>
          <div class="section-box">
            <p class="swipe-hint">Desliza para ver más productos</p>
            <div class="carousel" data-carousel="comics">
              <button
                class="carousel-btn prev"
                data-carousel-btn="prev"
                aria-label="Anterior"
              >
                ‹
              </button>
              <div class="carousel-viewport">
                <div class="product-grid" data-carousel-track="comics">
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47387-home_default/blue-lock-27.jpg"
                      alt="Blue Lock 27"
                    />
                    <div class="product-info">
                      <h3>Blue Lock 27</h3>
                      <p>8,08&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/30049-home_default/komi-san-no-puede-comunicarse-1.jpg"
                      alt="Komi San No Puede Comunicarse 1"
                    />
                    <div class="product-info">
                      <h3>Komi San No Puede Comunicarse 1</h3>
                      <p>12,83&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47067-home_default/solo-leveling-8.jpg"
                      alt="Solo Leveling 8"
                    />
                    <div class="product-info">
                      <h3>Solo Leveling 8</h3>
                      <p>13,78&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/47983-home_default/rom-02-la-etapa-marvel-original.jpg"
                      alt="Rom 02 La Etapa Marvel Original"
                    />
                    <div class="product-info">
                      <h3>Rom 02. La Etapa Marvel Original</h3>
                      <p>71,25&nbsp;€</p>
                    </div>
                  </article>
                  <article class="product-card">
                    <img
                      src="https://www.leprechaun.es/38983-home_default/unleash.jpg"
                      alt="Unleash"
                    />
                    <div class="product-info">
                      <h3>Unleash</h3>
                      <p>19,00&nbsp;€</p>
                    </div>
                  </article>
                </div>
              </div>
              <button
                class="carousel-btn next"
                data-carousel-btn="next"
                aria-label="Siguiente"
              >
                ›
              </button>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script>
      /* Carruseles adaptativos con scrollLeft y cálculo de ancho explícito */
      class Carousel {
        constructor(root) {
          this.root = root;
          this.viewport = root.querySelector(".carousel-viewport");
          this.track = root.querySelector("[data-carousel-track]");
          this.prevBtn = root.querySelector('[data-carousel-btn="prev"]');
          this.nextBtn = root.querySelector('[data-carousel-btn="next"]');

          if (!this.viewport || !this.track || !this.prevBtn || !this.nextBtn) {
            console.error("Carousel elements not found in", root);
            return;
          }

          this.cards = Array.from(this.track.children);
          this.scrollAmount = 0;

          this.prevBtn.addEventListener("click", () => this.prev());
          this.nextBtn.addEventListener("click", () => this.next());
          this.viewport.addEventListener("scroll", () => this.updateButtons());
          window.addEventListener("resize", () => this.refresh());

          // Usamos un observador para refrescar cuando las imágenes carguen o algo cambie
          const observer = new MutationObserver(() => this.refresh());
          observer.observe(this.track, { childList: true, subtree: true });

          // También refrescamos en carga de imágenes
          this.track.addEventListener("load", () => this.refresh(), true);

          this.refresh();
        }

        refresh() {
          // Esperamos un ciclo de renderizado para asegurar que los estilos están aplicados
          requestAnimationFrame(() => {
            if (window.innerWidth < 640) {
              this.prevBtn.hidden = true;
              this.nextBtn.hidden = true;
              this.track.style.width = ""; // Reset width
              return;
            }

            this.prevBtn.hidden = false;
            this.nextBtn.hidden = false;

            const styles = getComputedStyle(this.track);
            const gap = parseFloat(styles.columnGap || styles.gap || 0);
            let totalWidth = 0;

            this.cards.forEach((card) => {
              totalWidth += card.offsetWidth;
            });

            totalWidth +=
              this.cards.length > 1 ? (this.cards.length - 1) * gap : 0;

            this.track.style.width = `${totalWidth}px`;

            if (this.cards.length > 0) {
              this.scrollAmount = this.cards[0].offsetWidth + gap;
            }

            this.updateButtons();
          });
        }

        updateButtons() {
          if (window.innerWidth < 640) return;

          // Usamos requestAnimationFrame para leer las propiedades de scroll después del renderizado
          requestAnimationFrame(() => {
            const scrollLeft = this.viewport.scrollLeft;
            const maxScroll =
              this.viewport.scrollWidth - this.viewport.clientWidth;

            this.prevBtn.disabled = scrollLeft < 1;
            // Añadimos un pequeño umbral para mayor fiabilidad
            this.nextBtn.disabled = scrollLeft >= maxScroll - 5;
          });
        }

        next() {
          this.viewport.scrollBy({
            left: this.scrollAmount,
            behavior: "smooth",
          });
        }

        prev() {
          this.viewport.scrollBy({
            left: -this.scrollAmount,
            behavior: "smooth",
          });
        }
      }

      window.carousels = [];
      document.addEventListener("DOMContentLoaded", () => {
        document
          .querySelectorAll("[data-carousel]")
          .forEach((carouselRoot) =>
            window.carousels.push(new Carousel(carouselRoot))
          );
      });
    </script>
    <script>
      const canvas = document.getElementById("node-canvas");
      const ctx = canvas.getContext("2d");
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;

      let nodes = [];
      const numNodes = 50;
      const maxDist = 200;
      let mouse = { x: null, y: null };

      // Node class
      class Node {
        constructor(x, y, vx, vy) {
          this.x = x;
          this.y = y;
          this.vx = vx;
          this.vy = vy;
          this.radius = 2;
        }

        draw() {
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
          ctx.fillStyle = "rgba(157, 255, 120, 0.5)";
          ctx.fill();
        }

        update() {
          if (this.x < 0 || this.x > canvas.width) this.vx = -this.vx;
          if (this.y < 0 || this.y > canvas.height) this.vy = -this.vy;
          this.x += this.vx;
          this.y += this.vy;
        }
      }

      // Create nodes
      function init() {
        nodes = [];
        for (let i = 0; i < numNodes; i++) {
          const x = Math.random() * canvas.width;
          const y = Math.random() * canvas.height;
          const vx = (Math.random() - 0.5) * 0.5;
          const vy = (Math.random() - 0.5) * 0.5;
          nodes.push(new Node(x, y, vx, vy));
        }
      }

      // Draw lines between nodes
      function drawLines() {
        for (let i = 0; i < nodes.length; i++) {
          for (let j = i + 1; j < nodes.length; j++) {
            const dist = Math.hypot(
              nodes[i].x - nodes[j].x,
              nodes[i].y - nodes[j].y
            );
            if (dist < maxDist) {
              ctx.beginPath();
              ctx.moveTo(nodes[i].x, nodes[i].y);
              ctx.lineTo(nodes[j].x, nodes[j].y);
              ctx.strokeStyle = `rgba(157, 255, 120, ${1 - dist / maxDist})`;
              ctx.lineWidth = 0.5;
              ctx.stroke();
            }
          }
        }
      }

      // Draw lines to mouse
      function drawMouseLines() {
        if (mouse.x === null) return;
        for (let i = 0; i < nodes.length; i++) {
          const dist = Math.hypot(nodes[i].x - mouse.x, nodes[i].y - mouse.y);
          if (dist < maxDist) {
            ctx.beginPath();
            ctx.moveTo(nodes[i].x, nodes[i].y);
            ctx.lineTo(mouse.x, mouse.y);
            ctx.strokeStyle = `rgba(157, 255, 120, ${1 - dist / maxDist})`;
            ctx.lineWidth = 0.5;
            ctx.stroke();
          }
        }
      }

      // Animation loop
      function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        nodes.forEach((node) => {
          node.update();
          node.draw();
        });
        drawLines();
        drawMouseLines();
        requestAnimationFrame(animate);
      }

      // Event listeners
      window.addEventListener("resize", () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        init();
      });
      window.addEventListener("mousemove", (e) => {
        mouse.x = e.x;
        mouse.y = e.y;
      });
      window.addEventListener("mouseout", () => {
        mouse.x = null;
        mouse.y = null;
      });

      init();
      animate();
    </script>

  </body>
</html>
