    // En tu archivo JS (ej: `carrusel.js`)
    function likeSystem(id, estadoInicial, totalInicial) {
        return {
            id: id,
            meGusta: estadoInicial,
            total: totalInicial,
            async toggle() {
                const nuevoEstado = !this.meGusta;
                this.meGusta = nuevoEstado; // Cambio optimista (opcional)
                this.total += nuevoEstado ? 1 : -1;
            
                try {
                    const response = await fetch('/api/me-gusta', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id_medio: this.id,
                            me_gusta: nuevoEstado
                        })
                    });
                    
                    const data = await response.json();
                    console.log("Respuesta:", data);
            
                    if (!response.ok || !data.success) {
                        // Revertir cambios si hay error
                        this.meGusta = !nuevoEstado;
                        this.total += this.meGusta ? 1 : -1;
                        
                        if (data.error) {
                            alert(data.error); // Mostrar error al usuario
                        }
                    }
                } catch (error) {
                    console.error("Error de red:", error);
                    this.meGusta = !nuevoEstado;
                    this.total += this.meGusta ? 1 : -1;
                }
            }
        };
    }