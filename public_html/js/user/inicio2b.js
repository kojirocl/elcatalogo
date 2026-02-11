// Maneja el envío del formulario de filtros y carga dinámica de tarjetas y ciudades
async function enviarDatos(evento) {
    const formData = new FormData();
    const region = document.getElementById('region')?.value || '';
    const ciudad = document.getElementById('ciudades')?.value || '';

    const activeTags = document.querySelectorAll('.btn.active.tag');
    const tags = Array.from(activeTags).map(btn => btn.id);

    formData.append('region', region);
    formData.append('ciudad', ciudad);
    formData.append('tag', tags);
    formData.append('cuantos', tags.length);

    try {
        if (evento?.target?.id === 'region') {
            const responseCiudades = await fetch('regiones', {
                method: 'POST',
                body: formData
            });
            
            if (!responseCiudades.ok) {
                throw new Error(`Error HTTP: ${responseCiudades.status}`);
            }
            
            const ciudadesHTML = await responseCiudades.text();
            document.getElementById('ciudades').innerHTML = ciudadesHTML;
        }

        const responseTarjetas = await fetch('filtrar', {
            method: 'POST',
            body: formData
        });
        
        if (!responseTarjetas.ok) {
            throw new Error(`Error HTTP: ${responseTarjetas.status}`);
        }
        
        const tarjetasHTML = await responseTarjetas.text();
        document.getElementById('tarjetas').innerHTML = tarjetasHTML;

        //inicializarVerPerfil(); // Reasigna eventos de perfil en nuevas tarjetas

    } catch (error) {
        console.error('Error al filtrar:', error);
        alert('Hubo un problema al actualizar los resultados.');
    }
}

// Inicializa eventos para los filtros (región, ciudad y tags)
function inicializarFiltros() {
    document.getElementById('region')?.addEventListener('change', enviarDatos);
    document.getElementById('ciudades')?.addEventListener('change', enviarDatos);

    document.querySelectorAll('.btn.tag').forEach(btn => {
        btn.addEventListener('click', enviarDatos);
    });
}

// Carga contenido dinámico y reasigna eventos
async function cargarContenido(url, push = true) {
    try {
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status}`);
        }
        
        const contenidoHTML = await response.text();
        document.getElementById('contenido').innerHTML = contenidoHTML;
        
        if (push) history.pushState({ url }, '', url);

        inicializarFiltros();
        //inicializarVerPerfil();
    } catch (error) {
        console.error('Error al cargar contenido:', error);
        document.getElementById('contenido').innerHTML = '<p>Error al cargar el contenido.</p>';
    }
}

// Carga perfil dinámicamente
/* function inicializarVerPerfil() {
    document.querySelectorAll('a.ver-perfil').forEach(enlace => {
        enlace.addEventListener('click', e => {
            e.preventDefault();
            const url = enlace.getAttribute('href');
            cargarContenido(url);
        });
    });
} */

// Inicializa la navegación AJAX de enlaces con data-ajax
/* function inicializarNavegacionAjax() {
    document.querySelectorAll('a[data-ajax]').forEach(enlace => {
        enlace.addEventListener('click', e => {
            e.preventDefault();
            const url = enlace.getAttribute('href');
            cargarContenido(url);
        });
    });
} */

// Manejo del historial (botón atrás/adelante del navegador)
/* window.addEventListener('popstate', e => {
    if (e.state?.url) {
        cargarContenido(e.state.url, false);
    }
}); */

// Inicializar todo al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, Alpine.js activo');
    inicializarFiltros();
    //inicializarVerPerfil();
    //inicializarNavegacionAjax();
});