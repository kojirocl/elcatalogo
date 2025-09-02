document.addEventListener('DOMContentLoaded', () => {
    // Selecciona todos los enlaces que deben manejarse con navegación AJAX
    const enlaces = document.querySelectorAll('a[data-ajax]');
  
    enlaces.forEach(enlace => {
      enlace.addEventListener('click', async (event) => {
        event.preventDefault(); // Evita la navegación normal
  
        const url = enlace.getAttribute('href'); // Obtén la URL del enlace
        const contenido = document.getElementById('contenido'); // Contenedor donde se cargará el contenido
  
        try {
          // Realiza la solicitud con Axios
          const response = await axios.get(url);
          contenido.innerHTML = response.data; // Actualiza el contenido dinámicamente
        
          // Reejecuta los scripts del contenido cargado
          const scripts = contenido.querySelectorAll('script');
          scripts.forEach(script => {
              const newScript = document.createElement('script');
              newScript.textContent = script.textContent;
              document.body.appendChild(newScript);
              document.body.removeChild(newScript); // Limpia el script después de ejecutarlo
          });
        
        
        
        } catch (error) {
          console.error('Error al cargar el contenido:', error);
          alert('Hubo un problema al cargar el contenido.');
        }
      });
    });
  });