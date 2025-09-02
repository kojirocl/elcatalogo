document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-comentario');

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            try{
                const response = await axios.post('/comentar', formData);

                // Reemplazar el contenido del div con los nuevos comentarios
                document.getElementById('lista_comentarios').innerHTML = response.data;
                
                document.querySelector('#lista_comentarios .comentario:first-child')?.classList.add('destacado');
                
                const listaComentarios = document.getElementById('lista_comentarios');

                listaComentarios.style.opacity = 0; // fade-out antes de actualizar
                
                setTimeout(() => {
                    listaComentarios.innerHTML = response.data;
                    listaComentarios.style.transition = 'opacity 0.5s ease-in';
                    listaComentarios.style.opacity = 1; // fade-in después de actualizar
                }, 200);

                // Limpiar el textarea
                form.querySelector('#comentario_texto').value = '';
            }catch (error) {
                console.error('Error al enviar comentario:', error);
                alert('Ocurrió un error al enviar tu comentario');
            }
        });
    }
});



