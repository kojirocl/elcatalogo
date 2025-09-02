
<div x-data="media()" class="container" style="margin-top: 10px;">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="text-center">Galeria de fotos</h2>
        </div>
    <form role="form" method="post" action="/privado/media/guardar" enctype="multipart/form-data">
        <div class="input-group mb-1">
            <input class="form-control" type="file" name="fotos[]" id="fileupload" data-url="server/php/" accept="image/*" multiple>
            <button type="submit" class="btn btn-primary">subir archivos</button>
        </div>
        <p><small> para subir (solo acepta imagenes)</small></p>
    </form>

    <br><br>

    <div class="container">
        <table class="table table-hover table-sm table-responsive">
            <thead>
                <tr>
                    <th scope="col">foto</th>

                    
                    <th scope="col"></th>
                  </tr>
            </thead>
            <tbody>
                <?php foreach (($medios?:[]) as $foto): ?>
                    <tr>
                        <td><img src="<?= ('/'.str_replace('uploads','thumbs',$foto['ubicacion'])) ?>" class="card-img-top" style="width: 7rem;"></td>

                        <td>
                            <div class="btn-group" role="group">
                                <a href="/privado/media/fijar/<?= ($foto['id']) ?>" class="btn btn-outline-success btn-sm" role="button"><span class="bi bi-file-earmark-person"></span> fijar</a>
                                <a href="/privado/media/borrar/<?= ($foto['id']) ?>" class="btn btn-outline-danger btn-sm" role="button"><span class="bi bi-file-earmark-x"></span> borrar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<script>
    function media() {
        return {
            init() {
                console.log('media component initialized');
            },
            // Add any other methods or properties you need here
        };
    }
</script>