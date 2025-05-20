// recursos/js/user/comentarios.js
function comentarios() {
    return {
        comentario: '',
        cargando: false,
        mensaje: "",
        exito: false,

        // Enviar comentario
        async enviarComentario() {
            if (!this.comentario.trim()) return;

            this.cargando = true;
            this.mensaje = "";

            try {
                const { data } = await axios.post("/api/comentar", {
                    idUserOrigen: document.getElementById("idUser_origen").value,
                    idUserDestino: document.getElementById("idUser_destino").value,
                    comentario: this.comentario,
                });

                if (data.success) {
                    this.exito = true;
                    this.mensaje = data.message || "¡Comentario enviado!";
                    this.comentario = ""; // Limpiar textarea
                    await this.recargarComentarios();
                } else {
                    this.exito = false;
                    this.mensaje = data.message || "Error al enviar el comentario.";
                }
            } catch (error) {
                this.exito = false;
                this.mensaje = this.obtenerMensajeError(error);
                console.error("Error:", error);
            } finally {
                this.cargando = false;
                setTimeout(() => (this.mensaje = ""), 5000); // Limpiar mensaje después de 5s
            }
        },

        // Recargar comentarios después de enviar uno nuevo
        async recargarComentarios() {
            try {
                const idUserDestino = document.getElementById("idUser_destino").value;
                const { data } = await axios.get("/cargar-comentarios", {
                    params: { idUser: idUserDestino },
                });

                const lista = document.getElementById("lista-comentarios");
                if (lista) {
                    lista.innerHTML = data;
                    this.destacarNuevoComentario();
                }
            } catch (error) {
                console.error("Error al recargar comentarios:", error);
            }
        },

        // Efecto visual para el nuevo comentario
        destacarNuevoComentario() {
            const lista = document.getElementById("lista-comentarios");
            if (lista?.firstElementChild) {
                lista.firstElementChild.classList.add("nuevo-comentario");
                setTimeout(() => {
                    lista.firstElementChild.classList.remove("nuevo-comentario");
                }, 3000);
            }
        },

        // Manejo de errores
        obtenerMensajeError(error) {
            if (error.response) {
                return error.response.data?.message || `Error ${error.response.status}`;
            } else if (error.request) {
                return "Error de conexión con el servidor.";
            } else {
                return "Error al enviar la solicitud.";
            }
        },
    };
}