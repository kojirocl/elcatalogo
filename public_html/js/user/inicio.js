function cargarPerfilAjax(url, push = true) {
    const contenido = document.getElementById('contenido');

    axios.get(url)
        .then(res => {
            contenido.innerHTML = res.data;
            if (push) history.pushState({ url }, '', url);
            inicializarVerPerfil(); // Re-asigna eventos
        })
        .catch(err => {
            console.error('Error al cargar el perfil:', err);
            contenido.innerHTML = '<p>Error al cargar el perfil.</p>';
        });
}

function inicializarVerPerfil() {
    console.log('[init] ver-perfil');

    const enlaces = document.querySelectorAll('a.ver-perfil');
    enlaces.forEach(enlace => {
        enlace.addEventListener('click', event => {
            event.preventDefault();
            const url = enlace.getAttribute('href');
            cargarContenido(url);
        });
    });
}

function manejarClickVerPerfil(e) {
    e.preventDefault();
    const url = this.getAttribute('href');
    cargarPerfilAjax(url);
}

document.addEventListener('DOMContentLoaded', () => {
    inicializarVerPerfil();

    // Manejo del historial del navegador
    window.addEventListener('popstate', e => {
        if (e.state && e.state.url) {
            cargarPerfilAjax(e.state.url, false);
        }
    });
});