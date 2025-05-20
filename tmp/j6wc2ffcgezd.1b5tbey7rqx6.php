
<div id="filtros">
    <div id="regiones" class="container">
        <form id="filtrar-form">
            <div class="row">
                <div class="col-auto">
                    <select class="form-select form-select-sm" aria-label="Regiones" id="region" name="region">
                        <option selected>Todas</option>
                        <?php foreach (($filtro['regiones']?:[]) as $region): ?>
                            <option value="<?= ($region['admin_name']) ?>"><?= ($region['admin_name']) ?></option>	
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-auto" >
                    <select class="form-select form-select-sm" aria-label="Regiones" name="ciudad" id="ciudades">
                        <option selected>Todas</option>
                        <?php foreach (($filtro['ciudades']?:[]) as $ciudad): ?>
                            <option value="<?= ($ciudad['city']) ?>"><?= ($ciudad['city']) ?></option>	
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </form>	
    </div>
    <br>
    <div id="tags" class="container" name="filtros">
        <div class="container">
        <div class="btn-group btn-group-sm" role="group">
        <?php foreach (($filtro['tags']?:[]) as $valor): ?>
            <button class="btn btn-outline-secondary tag" data-bs-toggle="button" id="<?= ($valor['idTag']) ?>"><?= ($valor['tag']) ?></button>
        <?php endforeach; ?>
        </div>  

        </div>
    </div>
</div>
<div id="tarjetas" class="py-4">
    <?php echo $this->render('frontend/templates/tarjetas_contenido.html',NULL,get_defined_vars(),0); ?>
</div>

<!-- 
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perfilModalLabel">Perfil de Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 Aquí se cargará el contenido dinámico 
                <div id="modal-content">
                    Cargando...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> 
-->

<style>
#tags .btn {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#tags .btn:hover {
    transform: scale(1.1); /* Aumenta el tamaño del botón */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Agrega una sombra */
}

/* Estilos personalizados para el modal */
.modal-content {
    background-color: #1e1e1e !important; /* Fondo oscuro */
    color: #8e0fc9 !important; /* Texto claro */
}

.modal-header, .modal-footer {
    background-color: #2a2a2a !important; /* Fondo más oscuro para el header y footer */
    border-color: #444 !important; /* Bordes oscuros */
}

.modal-body {
    background-color: #1e1e1e !important; /* Fondo oscuro */
    color: #8e0fc9 !important; /* Texto claro */
}

</style>

<!-- 
<script>
    function cargarPerfil(idUser) {
    const modalContent = document.getElementById('modal-content');
    modalContent.innerHTML = 'Cargando...';

    axios.get('/mostrar/perfil/${idUser}')
        .then(response => {
        modalContent.innerHTML = response.data;
        })
        .catch(error => {
        console.error('Error al cargar el perfil:', error);
        modalContent.innerHTML = '<p>Error al cargar el perfil.</p>';
        });
    }
</script> 
-->