<div id="perfil">
    <?php if (isset($mensaje)): ?>
        <div class="col-sm-9 p-3">
            <div class="alert <?= ($mensaje['tipo']) ?> alert-dismissible fade show" role="<?= ($mensaje['rol']) ?>">
                <h4 class="alert-heading"><?= ($mensaje['titulo']) ?></h4>
                <p><?= ($mensaje['contenido']) ?></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>


    <form x-data action="/privado/perfil/guardar" method="post" class="col-sm-9 p-3">
            
            <div class="form-floating mb-3">
                <input class="form-control" type="text" id="realname" name="realname" placeholder="aqui el nombre" value="<?= ($perfil['realname']) ?>"></input>
                <label for="realname">Nombre y apellido</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" id="nick" name="nickname" placeholder="nickname" value="<?= ($perfil['nickname']) ?>" required></input>
                <label for="nick">nick name</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Regiones" name="ciudad" id="ciudades" >
                    
                    <?php foreach (($ciudades?:[]) as $ciudad): ?>
                        <?php if ($ciudad['city'] == $perfil['ciudad']): ?>
                            
                                <option value="<?= ($ciudad['city']) ?>" selected><?= ($ciudad['city']) ?></option>
                            >
                            <?php else: ?>
                                <option value="<?= ($ciudad['city']) ?>"><?= ($ciudad['city']) ?></option>
                                 
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <label for="ciudades">ciudad</label>          
            </div> 

            
            <label for="wsp" class="form-label">Teléfono</label>
            <div class="input-group mb-3">
                <span class="input-group-text">56</span>
                <span class="input-group-text">9</span>
                <input x-mask="9 9999 9999" type="text" class="form-control" id="wsp" name="wsp" value="<?= ($perfil['wsp']) ?>" required>
            </div>
        
            <div class="form-floating mb-3">
                <textarea class="form-control" id="descripcion" name="descripcion" placeholder="descripcion" rows="4"><?= ($perfil['descripcion']) ?></textarea>
                <label for="descripcion">Descripcion</label>
            </div>
            
            <div class="form-floating mb-3">
                <input class="form-control" id="tags" name="tags" placeholder="tags" value="<?= ($etiquetas) ?>"></input>
                <label for="tags">tags (separados por espacio)</label> 
            </div>        

            <div class="row">
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="activo" disabled <?php if (($perfil['activo'] == 1)): ?>checked<?php endif; ?>/>
                        <label class="form-check-label" for="activo">Perfil Activo
                        </label>
                    </div>

                </div>
            </div>
        <div class="m-3">
            <a class="btn btn-secondary" href="/privado" role="button">cancelar</a>
            <button type="submit" class="btn btn-primary">guardar</button>
        </div>    

    </form>
</div>
