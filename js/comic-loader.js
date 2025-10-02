document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.getElementById("comics-tbody");

  if (!tableBody) {
    console.error(
      'Error: No se encontró el elemento <tbody> con id "comics-tbody".'
    );
    return;
  }

  fetch("comics.json")
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Error de red: ${response.statusText}`);
      }
      return response.json();
    })
    .then((data) => {
      tableBody.innerHTML = "";

      data.forEach((comic, index) => {
        const row = document.createElement("tr");

        // --- SECCIÓN MODIFICADA ---
        // Se elimina la primera celda <td> y se añade el número al título.
        row.innerHTML = `
                    <td>
                        <a href="${
                          comic.url_producto
                        }" target="_blank" rel="noopener noreferrer">
                            <img src="${comic.url_imagen}" alt="Portada de ${
          comic.nombre
        }">
                        </a>
                    </td>
                    <td>
                        <a href="${
                          comic.url_producto
                        }" target="_blank" rel="noopener noreferrer">
                            ${index + 1}. ${comic.nombre}
                        </a>
                    </td>
                    <td>${comic.precio}</td>
                `;
        // --- FIN DE LA SECCIÓN MODIFICADA ---

        tableBody.appendChild(row);
      });
    })
    .catch((error) => {
      console.error("Error al cargar los cómics:", error);
      // Revertimos el colspan a "3" porque ahora solo hay 3 columnas
      tableBody.innerHTML =
        '<tr><td colspan="3">No se pudieron cargar los datos de los cómics.</td></tr>';
    });
});
