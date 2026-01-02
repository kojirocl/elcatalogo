<?php if (isset($infoPerfil['comentarios'])): ?>
    
        <div id="lista-comentarios" class="row g-2"
             x-data="{ 
                 editing: false, 
                 tempComment: '<?= ($infoPerfil['miComentario'] ? addslashes($infoPerfil['miComentario']['comentario']) : '') ?>'
             }">

            <!-- Solo si hay sesión y NO es el propio perfil: puede crear/editar -->
            <?php if (isset($SESSION['usuario']) && !$infoPerfil['esMiPerfil']): ?>
                
                    <!-- Si ya tiene comentario: mostrar / editar -->
                    <?php if ($infoPerfil['miComentario']): ?>
                        
                            <div class="col-12">
                                <template x-if="editing">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <textarea x-model="tempComment" class="form-control mb-2"></textarea>
                                            <button 
                                                @click="editing = false; $dispatch('update-comment', { id: <?= ($infoPerfil['miComentario']['id']) ?>, texto: tempComment })" 
                                                class="btn btn-sm btn-success me-2">
                                                Guardar
                                            </button>
                                            <button 
                                                @click="editing = false; tempComment = '<?= (addslashes($infoPerfil['miComentario']['comentario'])) ?>'" 
                                                class="btn btn-sm btn-outline-secondary">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!editing">
                                    <div class="card">
                                        <div class="card-body">
                                            <blockquote class="blockquote">
                                                <p class="small" x-text="tempComment"></p>
                                            </blockquote>
                                            <figcaption class="blockquote-footer small">
                                                <?= (date('l d, F \d\e Y', $infoPerfil['miComentario']['fecha']))."
" ?>
                                            </figcaption>
                                            <button @click="editing = true" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="bi bi-pencil"></i> Editar
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        
                        <?php else: ?>
                            <!-- Si no tiene comentario aún: formulario para publicar -->
                            <div class="col-12">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <textarea x-model="tempComment" placeholder="Escribe tu comentario..." class="form-control mb-2"></textarea>
                                        <button 
                                            @click="$dispatch('nuevo-comentario', { id: <?= ($infoPerfil['datos']['idUser']) ?>, texto: tempComment })" 
                                            class="btn btn-sm btn-success">
                                            Publicar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        
                    <?php endif; ?>
                
                <?php else: ?>
                    <!-- visitante es dueño del perfil o no hay sesión: no se muestra formulario -->
                
            <?php endif; ?>

            <!-- Lista de todos los comentarios (excluyendo el comentario del visitante si existe) -->
            <div id="lista_comentarios_todos">
                <?php foreach (($infoPerfil['comentarios']?:[]) as $comentario): ?>
                    <?php if (!$infoPerfil['miComentario'] || $comentario['id'] != $infoPerfil['miComentario']['id']): ?>
                        
                            <div class="col-12">
                                <div class="card mb-1">
                                    <div class="card-body">
                                        <blockquote class="blockquote">
                                            <p class="small"><?= (htmlspecialchars((string)$comentario->comentario, ENT_QUOTES, 'UTF-8')) ?></p>
                                        </blockquote>
                                        <figcaption class="blockquote-footer small">
                                            <?= (date('l d, F \d\e Y', $comentario['fecha']))."
" ?>
                                        </figcaption>
                                    </div>
                                </div>
                            </div>
                        
                        <?php else: ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>

        <script>
            document.addEventListener('update-comment', (e) => {
                fetch(`/actualizar-comentario/${e.detail.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ texto: e.detail.texto })
                }).then(response => {
                    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                    return response.json();
                }).then(data => {
                    console.log('Respuesta OK:', data);
                    // si el backend devuelve html parcial con la lista actualizada:
                    if (data.html) document.getElementById('lista_comentarios_todos').innerHTML = data.html;
                }).catch(error => console.error('Hubo un problema:', error.message));
            });

            document.addEventListener('nuevo-comentario', (e) => {
                fetch(`/nuevo-comentario/${e.detail.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ texto: e.detail.texto })
                }).then(response => {
                    if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                    return response.json();
                }).then(data => {
                    console.log('Respuesta OK:', data);
                    if (data.html) document.getElementById('lista').innerHTML = data.html;
                    // opcional: limpiar textarea
                    // document.querySelector('#lista-comentarios textarea')?.value = '';
                }).catch(error => console.error('Hubo un problema:', error.message));
            });
        </script>

        <style>
            [x-cloak] { display: none !important; }
            .card { transition: all 0.5s ease; }
        </style>
    
    <?php else: ?>
        <div class="alert alert-secondary" role="alert">
            <p><i class="bi bi-info-circle-fill"></i> no hay comentarios aún...</p>
        </div>
    
<?php endif; ?>
