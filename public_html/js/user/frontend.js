async function enviarDatos(evento) {
    let formData = new FormData();

    let region = document.getElementById('region').value;
    let ciudad = document.getElementById('ciudades').value;

    // Obtener los botones activos
    let activeButtons = document.querySelectorAll('.btn.active.tag');
    let tags = Array.from(activeButtons).map(button => button.id);

    // Agregar los datos al FormData
    formData.append('region', region);
    formData.append('ciudad', ciudad);
    formData.append('tag', tags);
    formData.append('cuantos', tags.length);

    try {
        // Si el evento fue causado por el cambio de región, actualizar ciudades
        if (evento.target.id === 'region') {
            let responseCiudades = await axios.post('regiones', formData);
            document.getElementById('ciudades').innerHTML = responseCiudades.data;
        }

        // Actualizar tarjetas en cualquier caso
        let responseTarjetas = await axios.post('filtrar', formData);
        document.getElementById('tarjetas').innerHTML = responseTarjetas.data;

    } catch (error) {
        console.error('Error en la carga:', error);
        alert('Hubo un problema con la actualización.');
    }
}

// Función para inicializar eventos
function inicializarEventos() {
    // Verifica si el elemento #region existe antes de asignar el evento
    const region = document.getElementById('region');
    if (region) {
        region.addEventListener('change', enviarDatos);
    }

    // Verifica si el elemento #ciudades existe antes de asignar el evento
    const ciudades = document.getElementById('ciudades');
    if (ciudades) {
        ciudades.addEventListener('change', enviarDatos);
    }

    // Verifica si hay botones con la clase .btn.tag antes de asignar eventos
    const botonesTags = document.querySelectorAll('.btn.tag');
    if (botonesTags.length > 0) {
        botonesTags.forEach(button => {
            button.addEventListener('click', enviarDatos);
        });
    }
}

// Función para cargar contenido dinámico con AJAX
async function cargarContenido(url) {
    try {
        const response = await axios.get(url);
        document.getElementById('contenido').innerHTML = response.data;

        // Reinicializar eventos después de cargar contenido
        inicializarEventos();
    } catch (error) {
        console.error('Error cargando contenido:', error);
        alert('No se pudo cargar el contenido.');
    }
}

// Inicializar eventos cuando el DOM está listo
document.addEventListener('DOMContentLoaded', () => {
    inicializarEventos();

    // Manejo de navegación AJAX
    const enlaces = document.querySelectorAll('a[data-ajax]');
    enlaces.forEach(enlace => {
        enlace.addEventListener('click', (event) => {
            event.preventDefault();
            const url = enlace.getAttribute('href');
            cargarContenido(url);
        });
    });
});