<!-- Mensaje de verificación -->
<div class="container" id="mensaje">
  <?php if (isset($mail_verificado) && ($mail_verificado == 1)): ?>
    <div class="alert alert-success shadow-sm rounded-3" role="alert">
      <h5 class="fw-bold mb-1">¡Correo verificado con éxito!</h5>
      <p class="mb-0">Ahora puedes iniciar sesión para activar algunas funciones...</p>
    </div>
  <?php endif; ?>
</div>

<!-- Formulario de Login -->
<div class="container py-5" x-data="loginForm()">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0 rounded-4 p-4">
        <!-- Título y subtítulo -->
        <h3 class="fw-bold mb-2 tc-heading">Iniciar Sesión</h3>
        <p class="mb-4 tc-subheading">Accede a tu cuenta con tus credenciales</p>

        <form action="login" method="POST" @submit="loading = true">
          <input type="hidden" name="token" value="<?= ($CSRF) ?>">

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="form-label tc-text">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" 
                   placeholder="ejemplo@correo.com" x-model="email" value="<?= (isset($emailConfirmado)?$emailConfirmado:'') ?>" required>
          </div>

          <!-- Password con toggle -->
          <div class="mb-4" x-data="{ show: false }">
            <label for="password" class="form-label tc-text">Contraseña</label>
            <div class="input-group">
              <input :type="show ? 'text' : 'password'" class="form-control" id="password" name="password"
                     placeholder="Ingrese su contraseña" x-model="password" required>
              <button type="button" class="btn btn-outline-secondary" @click="show = !show">
                <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
              </button>
            </div>
          </div>

          <!-- Link recuperar contraseña -->
          <div class="mb-3 text-end">
            <a href="/recuperar" class="small text-decoration-none tc-link">¿Olvidaste tu contraseña?</a>
          </div>

          <!-- Botón -->
          <button type="submit" class="btn tc-btn w-100 rounded-3" 
                  :disabled="loading">
            <span x-show="!loading"><i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión</span>
            <span x-show="loading">Ingresando...</span>
          </button>

          <!-- Error -->
          <p class="mt-3" style="color: red;" x-show="error" x-text="error"></p>
        </form>

        <!-- Link registro -->
        <div class="text-center mt-4">
          <a href="/registro" class="text-decoration-none tc-link">¿No tienes cuenta? Regístrate aquí</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function loginForm() {
  return {
    email: '',
    password: '',
    loading: false,
    error: '',
  }
}
</script>

    