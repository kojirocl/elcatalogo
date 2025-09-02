
<div class="container-fluid">
    <div class="fs-4 mb-3">
        <i class="bi bi-person-lines-fill"></i> Historial de suscripciones
    </div>    
    <?php if ($contratos != null): ?>
        
            <div class="table-responsive">
                <table class="table table-sm caption-top ">
                    <thead>
                        <th scope="col">id</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">duracion</th>
                        <th scope="col">Valor</th>
                        <th scope="col">vigente</th>
                    </thead>
                    <tbody>
                        <?php foreach (($contratos?:[]) as $valor): ?>
                            <?php if ($valor['vigente'] == 1): ?>
                                
                                    <tr class="table-active">
                                
                                <?php else: ?>
                                    <tr>
                                
                            <?php endif; ?>
                            
                                <th scope="row"><?= ($valor['id']) ?></th>
                                <td><?= (date('d-m-Y', $valor['fecha_registro'])) ?></td>
                                <td><?= ($valor['vigencia']) ?></td>
                                <td><?= ($valor['valor_pagado']) ?></td>
                                <td><?= ($valor['vigente']) ?></td> 
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        
        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                <span class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></span> No hay historial de suscripciones
            </div>
        
    <?php endif; ?>
</div>
<br>

<form method="post" action="/privado/suscripcion/contratar">
    <div class="container-fluid">
        <div class="fs-4 mb-3">
            <i class="bi bi-ticket-detailed-fill"></i> Cupones disponibles
        </div>
        <?php if ($cupones != null): ?>
            
                <div class="table-responsive">
                    <table class="table table-sm caption-top ">

                        <thead>
                            <th scope="col">id</th>
                            <th scope="col">codigo</th>
                            <th scope="col">descripcion</th>
                            <th scope="col">descuento</th>
                            <th scope="col">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="radio" id="0" name="cupon" value="0" checked> 
                                    <label class="form-check-label" for="0">no usar</label>
                                </div>
                            </th>
                        </thead>
                        <tbody>
                            <?php foreach (($cupones?:[]) as $valor): ?>
                                <tr>
                                    <th scope="row"><?= ($valor['idCupon']) ?></th>
                                    <td><?= ($valor['codigo']) ?></td>
                                    <td><?= ($valor['descripcion']) ?></td>
                                    <td><?= ($valor['descuento']) ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="radio" id="<?= ($valor['idCupon']) ?>" name="cupon" value="<?= ($valor['idCupon']) ?>">
                                            <label class="form-check-label" for="<?= ($valor['idCupon']) ?>">usar</label>
                                        </div>
                                        
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <span class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></span> No hay cupones disponibles
                </div>
            
        <?php endif; ?>
    </div>
    <br>
    <div class="container-fluid">
        <div class="fs-4 mb-3">
            <i class="bi bi-list-check"></i> Suscripciones disponibles
        </div>
        <div class="table-responsive">
            <table class="table table-sm caption-top ">
                <thead>
                    <th scope="col">id</th>
                    <th scope="col">descripcion</th>
                    <th scope="col">dias</th>
                    <th scope="col">Valor</th>
                    <th scope="col"></th>
                </thead>
                <tbody>
                    <?php foreach (($suscripciones?:[]) as $valor): ?>
                        <tr>
                            <th scope="row"><?= ($valor['id']) ?></th>
                            <td><?= ($valor['descripcion']) ?></td>
                            <td><?= ($valor['dias']) ?></td>
                            <td>CLP $<?= (number_format($valor['valor'],0,",",".")) ?></td>
                            <td>
                                <button type="submit" name="boton" value="<?= ($valor['id']) ?>" class="btn btn-primary">la quiero!</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<div>
    <small>
        Si necesitas otro tipo, <a href="/privado/contacto/inicio">contactanos</a> 
        <span class="badge text-bg-secondary">New</span>
    </small>
</div>

<div>
    <small>
        Por el momento está habilitado MercadoPago para los pagos 
        <span class="badge text-bg-secondary">New</span>
    </small>
</div>
