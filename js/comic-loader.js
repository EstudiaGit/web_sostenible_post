document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.getElementById("comics-tbody");
  const paginationControls = document.getElementById("pagination-controls");
  const comicsSection = document.querySelector('section[data-label="[ DATABASE: COMICS ]"]');
  const searchInput = document.getElementById("search-input");

  if (!tableBody || !paginationControls || !searchInput) {
    console.error("Error: Elementos de tabla, paginación o búsqueda no encontrados.");
    return;
  }

  const ITEMS_PER_PAGE = 6;
  let currentPage = 1;
  let allComics = [];
  let currentComicList = [];
  let isInitialLoad = true;

  const renderTablePage = (page) => {
    currentPage = page;
    tableBody.innerHTML = "";

    const start = (page - 1) * ITEMS_PER_PAGE;
    const end = start + ITEMS_PER_PAGE;
    const paginatedComics = currentComicList.slice(start, end);

    if (paginatedComics.length === 0) {
      tableBody.innerHTML = '<tr><td colspan="3">No se encontraron cómics que coincidan con la búsqueda.</td></tr>';
      return;
    }

    paginatedComics.forEach((comic) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td data-label="Portada">
          <a href="${comic.url_producto}" target="_blank" rel="noopener noreferrer">
            <img src="${comic.url_imagen}" alt="Portada de ${comic.nombre}">
          </a>
        </td>
        <td data-label="Título">
          <a href="${comic.url_producto}" target="_blank" rel="noopener noreferrer">
            ${comic.nombre}
          </a>
        </td>
        <td data-label="Precio">${comic.precio}</td>
      `;
      tableBody.appendChild(row);
    });
  };

  const renderPagination = () => {
    const totalPages = Math.ceil(currentComicList.length / ITEMS_PER_PAGE);
    paginationControls.innerHTML = "";

    if (totalPages <= 1) return;

    const prevButton = document.createElement("button");
    prevButton.textContent = "« Anterior";
    prevButton.disabled = currentPage === 1;
    prevButton.addEventListener("click", () => {
      if (currentPage > 1) renderPage(currentPage - 1);
    });
    paginationControls.appendChild(prevButton);

    for (let i = 1; i <= totalPages; i++) {
      const pageButton = document.createElement("button");
      pageButton.textContent = i;
      if (i === currentPage) pageButton.classList.add("active");
      pageButton.addEventListener("click", () => renderPage(i));
      paginationControls.appendChild(pageButton);
    }

    const nextButton = document.createElement("button");
    nextButton.textContent = "Siguiente »";
    nextButton.disabled = currentPage === totalPages;
    nextButton.addEventListener("click", () => {
      if (currentPage < totalPages) renderPage(currentPage + 1);
    });
    paginationControls.appendChild(nextButton);
  };

  const renderPage = (page) => {
    renderTablePage(page);
    renderPagination();
    if (!isInitialLoad && comicsSection) {
      comicsSection.scrollIntoView({ behavior: "smooth" });
    }
  };

  searchInput.addEventListener('input', () => {
    const searchTerm = searchInput.value.toLowerCase();
    currentComicList = allComics.filter(comic => 
      comic.nombre.toLowerCase().includes(searchTerm)
    );
    isInitialLoad = false; // Consider search a user action that should scroll
    renderPage(1);
  });

  fetch("comics.json")
    .then((response) => {
      if (!response.ok) throw new Error(`Error de red: ${response.statusText}`);
      return response.json();
    })
    .then((data) => {
      allComics = data;
      currentComicList = allComics;
      renderPage(1);
      isInitialLoad = false;
    })
    .catch((error) => {
      console.error("Error al cargar los cómics:", error);
      tableBody.innerHTML = '<tr><td colspan="3">No se pudieron cargar los datos de los cómics.</td></tr>';
    });
});