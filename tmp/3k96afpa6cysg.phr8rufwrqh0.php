
<div x-data="logo" x-bind:class="clase"  x-on:mouseover="animate">
    <a class="navbar-brand"  href="/" >
        <i class="bi bi-book"></i> <?= ($SITIO['nombre']) ?> <?= (isset($saludo)? $saludo : '')."
" ?>
    </a>
</div>

<script>
    function logo() {
        return {
            clase: "",
            init() {
                console.log('Logo component initialized');
            },
            animate() {
                this.clase= "animate__animated animate__rubberBand";
                setTimeout(() => {
                    this.clase = "";
                }, 1000);
            }
        }
    }
</script>