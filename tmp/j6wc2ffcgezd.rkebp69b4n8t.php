
  <div class="container" id="mensaje">
    <?php if (($mail_verificado != NULL) && ($mail_verificado == 1)): ?>
      <div class="alert alert-success" role="alert">
        <p>Mail verificado con exito!!</p>
        <p>Ahora puedes logearte para activar algunas funciones...</p>
      </div>
    <?php endif; ?>
  </div>


    <div class="container" x-data="loginForm()">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form action="login" method="POST" class="p-4 border rounded shadow-sm" @submit="loading = true">
                    <h2 class="mb-4">Iniciar Sesión</h2>
                    <input type="hidden" name="token" value="<?= ($CSRF) ?>">
                    <!-- Campo de correo electrónico -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="ejemplo@correo.com" x-model="email">
                    </div>
    
                    <!-- Campo de contraseña -->
                    <div class="mb-3" x-data="{ show: false }">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <input :type="show ? 'text' : 'password'" class="form-control" id="password" name="password" required placeholder="Ingrese su contraseña" x-model="password">
                            <button type="button" class="btn btn-outline-secondary" @click="show = !show">
                                <template x-if="!show">
                                    <i class="bi bi-eye"></i>
                                </template>
                                <template x-if="show">
                                    <i class="bi bi-eye-slash"></i>
                                </template>
                            </button>
                        </div>
                    </div>
    
                    <!-- Link de olvidaste contraseña -->
                    <div class="mb-3 text-end">
                        <a href="/recuperar" class="small text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
    
                    <!-- Botón de envío -->
                    <button type="submit" class="btn btn-secondary w-100" :disabled="loading">
                        <span x-show="!loading">Iniciar Sesión</span>
                        <span x-show="loading">Ingresando...</span>
                    </button>
    
                    <!-- Mensaje de error simple -->
                    <p class="text-danger mt-2" x-show="error" x-text="error"></p>
                </form>
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
    