<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <main class="px-3">
        <h1>Confirmacion email</h1>
        <p class="lead">
            Bienvenido a El Catalogo, gracias por confiar en nosotros.<br>
            Por favor confirma tu correo haciendo click en el boton.
        </p>
        <p class="lead">Saludos, tu equipo de El Catalogo.</p>
        <p class="lead">
            <form action="/verificar" method="POST">
                <?= ($CSRF)."
" ?>
                <input type="hidden" name="token" value="<?= ($CSRF) ?>">
                <div class="col-5 mb-3">
                  <label for="InputEmail" class="form-label">Email</label>
                  <input type="email" class="form-control" id="InputEmail" name="mail" value="<?= ($GET['mail']) ?>">
                </div>
                <div class="col-5 mb-3">
                  <label for="InputCodigo" class="form-label">Codigo verificacion</label>
                  <input type="numeric" class="form-control" id="InputCodigo" name="codigo" value="<?= ($GET['codigo']) ?>">
                </div>
                <button class="btn btn-sm btn-success fw-bold">confirmar email</button>
              </form>
        
        </p>
    </main>

    <footer class="mt-auto"><?= (date('d-m-Y h:m:s A',time())) ?></footer>
    <p><?= (date_default_timezone_get()) ?></p>
</div>
