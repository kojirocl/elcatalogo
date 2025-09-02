<div class="row row-cols-1 row-cols-md-4 g-4 " >

    <?php foreach (($usuarios?:[]) as $usuario): ?>
        <div class="col">
            <div class="card h-100 tarjeta" >
                <div class="card-img-container">
                    <a href="/perfil/<?= ($usuario['idUser']) ?>" class="ver-perfil">

                    <?php if ($usuario['idFotoPerfil']): ?>
                        
                            <img src="<?= ('uploads/thumbs/'.$usuario['foto_perfil']) ?>" class="card-img-top img-thumbnail">   
                        
                        <?php else: ?>
                            <svg class="bd-placeholder-img img-thumbnail img-rounded" role="img" aria-label="Aqui va foto!" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <rect width="100%" height="100%" fill="#868e96"></rect>
                                <text x="35%" y="50%" fill="#dee2e6" dy=".3em">90x90</text>
                            </svg>
                          
                    <?php endif; ?>
                    </a>
                </div>
                <div class="card-body">
                    <h5 class="card-title custom-nickname"><?= ($usuario['nickname']) ?></h5>
                    <figure>
                        <blockquote class="blockquote">
                            <p class="fs-6"><em><?= (substr($usuario['descripcion'],0,35)) ?>...</em></p>
                        </blockquote>
                        <figcaption class="blockquote-footer text-end">
                            <?= ($usuario['ciudad'])."
" ?>
                        </figcaption>
                    </figure>
                    
                </div>

                <div class="card-footer text-end">
                    <a href="/perfil/<?= ($usuario['idUser']) ?>" class="btn btn-outline-info btn-sm ver-perfil">
                        <span>ver mas </span><i class="bi bi-arrow-right-circle-fill"></i>
                    </a> 
                </div>            
            </div>

        </div>
    <?php endforeach; ?>

</div>

<style>
[data-bs-theme="dark"] .card-img-container {
  background-color: inherit; 
}
</style>