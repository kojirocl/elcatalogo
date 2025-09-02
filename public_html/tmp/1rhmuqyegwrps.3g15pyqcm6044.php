<div class="container container-fluid">
    <div id="descuento">
        <div class="table-responsive">
            <table class="table table-sm caption-top ">
                <caption>Detalle compra</caption>
                <thead>
                    <th scope="col">Item</th>
                    <th scope="col">codigo</th>
                    <th scope="col">descripcion</th>
                    <th scope="col">descuento</th>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Cupon</th>
                        <td><?= ($descuento['codigo']) ?></td>
                        <td><?= ($descuento['descripcion']) ?> (<?= ($descuento['descuento']) ?>%)</td>
                        <td>CLP $<?= (number_format($suscripcion['valor']*$descuento['descuento']/100,0,",",".")) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Suscripcion</th>
                        <td><?= ($suscripcion['id']) ?></td>
                        <td><?= ($suscripcion['descripcion']) ?> (<?= ($suscripcion['dias']) ?> días)</td>

                        <td>CLP $<?= (number_format($suscripcion['valor'],0,",",".")) ?></td>
                    </tr>
                    <tr>
                        <th scope="col">Total</th>
                        <td colspan="2"></td>
                        <th scope="col">CLP $<?= (number_format($suscripcion['valor']* (100 - $descuento['descuento'])/100,0,",",".")) ?></td>
                    </tr>
                    

                </tbody>
            </table>
        </div>
    </div>
    <br>
    

    <div id="pago">
        <form action="/privado/suscripcion/guardar" method="post">
            <input type="hidden" name="cod_cupon" value="<?= ($cupon) ?>" />
            <input type="hidden" name="cod_suscripcion" value="<?= ($suscripcion['id']) ?>" />
            <button type="button" class="btn btn-secondary" id="btnCancelar" onclick="history.back()">Cancelar</button>
            <button type="submit" class="btn btn-primary" id="btnConfirmar">Confirmar</button>
        </form>
    </div>


</div>