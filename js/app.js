document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('inputBusqueda');
    const contenedorResultados = document.getElementById('resultados');

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', async (e) => {
            const query = e.target.value.trim();

            try {
                const response = await fetch(`api/buscar_productos.php?q=${encodeURIComponent(query)}`);
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }

                const res = await response.json();

                if (res.status === 'success') {
                    renderizarResultados(res.data);
                }
            } catch (error) {
                console.error("Error al realizar la petición:", error);
            }
        });
    }

    function renderizarResultados(productos) {
        contenedorResultados.innerHTML = '';

        if (productos.length === 0) {
            contenedorResultados.innerHTML = '<p class="text-muted">No se encontraron resultados.</p>';
            return;
        }

        productos.forEach(producto => {
            const item = document.createElement('div');
            item.className = 'card mb-2 p-2 shadow-sm';
            item.innerHTML = `
                <h5>${producto.nombre}</h5>
                <p class="mb-0">Precio: $${producto.precio}</p>
            `;
            contenedorResultados.appendChild(item);
        });
    }
});