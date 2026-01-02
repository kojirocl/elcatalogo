
<div class="col-md-10 offset-md-1" >
  <div class="container text-center">
    <h5 class="fw-light custom-nickname"><?= (strtoupper($infoPerfil['datos']['nickname']).' Portafolio') ?></h5>
    <div class="row">
      <div class="col">
        <div id="carrusel" class="py-2">
          <?= ($this->raw($carrusel))."
" ?>
        </div>
        <div>
          <h3 class="lead py-1"><em><?= ($infoPerfil['datos']['descripcion']) ?></em></h3>
          <a href="/whatsapp/<?= ($infoPerfil['datos']['idUser']) ?>" target="_blank" class="btn btn-success" role="button">
            <i class="bi bi-whatsapp"></i> Enviar WhatsApp
          </a>
        </div>

      </div>
    </div>
  </div>


  <div class="container py-3" >
    <input type="hidden" x-ref="idUserDestino" value="<?= ($infoPerfil['datos']['idUser']) ?>">
    <div class="row">
      <div class="col">
        <div id="lista">
          <?= ($this->raw($lista_comentarios))."
" ?>
        </div>
      </div>
    </div>
  </div>
</div>
 


