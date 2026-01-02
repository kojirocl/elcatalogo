<!DOCTYPE html>
<html lang="es"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      x-init="$watch('darkMode', val => {
          document.documentElement.setAttribute('data-bs-theme', val ? 'dark' : 'light');
          localStorage.setItem('darkMode', val);
      })"
      :data-bs-theme="darkMode ? 'dark' : 'light'"
>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <base href="<?= ($SCHEME.'://'. $HOST .':'. $PORT.'/') ?>">

	<title><?= ($SITIO['titulo']) ?></title>
  
  <!-- Favicon-->
  <link rel="icon" type="image/x-icon" href="<?= ($SITIO['favicon']) ?>" />  

  <link rel="stylesheet" href="css/animate.min.css">



  <script src="js/vendor/alpinejs.min.js" defer></script>
  
  <script src="js/vendor/axios.min.js"></script>
  <script src="js/user/inicio2.js"></script>

<!-- assets-head -->
<style>
  html {
    transition: background-color 0.5s ease;
  }
  .switch-lg .form-check-input {
    width: 3rem;
    height: 1.5rem;
    border-radius: 2rem;
    cursor: pointer;
  }
  .switch-lg .form-check-input:checked {
    background-color: #0d6efd;
  }
  .switch-lg label {
    cursor: pointer;
    font-size: 1.2rem;
    margin-left: 0.5rem;
  }
</style>

<!-- assets-head -->
</head>
<body>
  <div id="barra">
    <nav class="navbar navbar-expand-lg bg-dark border-bottom border-body" data-bs-theme="dark">
      <div class="container-fluid">

        
<?php echo $this->render('frontend/templates/logo.html',NULL,get_defined_vars(),0); ?>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barraNavegacion" aria-controls="barraNavegacion" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="barraNavegacion">
            <ul class="navbar-nav">
                
<?php foreach (($items_menu?:[]) as $titulo=>$item): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ($item['url']) ?>" data-ajax><i class="bi <?= ($item['icono']) ?>"></i> <?= ($titulo) ?></a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
      </div>
    </nav>
  </div>
  <div>
    
<?php echo $this->render('components/lightsComp.html',NULL,get_defined_vars(),0); ?>

  </div>

  <div class="principal container container-fluid">

    <div id="contenido">
      <?= ($this->raw($contenido))."
" ?>
    </div>

  </div>
  
  
<?php if (isset($paginas)): ?>
    <?php echo $this->render('/frontend/templates/barra_paginacion.html',NULL,get_defined_vars(),0); ?>
  <?php endif; ?>

  

  
    
  </div>

  <div id="footer">
    
<?php echo $this->render('/frontend/templates/footer.html',NULL,get_defined_vars(),0); ?>
  
  </div>
</div>
<!-- assets-footer -->


<!-- Inicializa el tema según localStorage -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const modo = localStorage.getItem('darkMode') === 'true';
      document.documentElement.setAttribute('data-bs-theme', modo ? 'dark' : 'light');
    });
  </script>

<!-- assets-footer -->
</body>
</html>