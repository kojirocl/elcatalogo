<h4>Codigo verificacion <?= ($SITIO['titulo']) ?></h4>
<p>Bienvenid@ a <?= ($SITIO['titulo']) ?>, tu codigo de verificacion es: <?= ($datos['codigo']) ?></p>
<p></p>Para validarlo favor has click en el siguiente enlace: <?= ($SCHEME.'://'. $HOST .':'. $PORT.'/'."verificar?mail=".urlencode($datos['email'])."&codigo=".$datos['codigo'])."
" ?>
O ingresalo directo en el sitio en la seccion <a href="<?= ($SCHEME.'://'. $HOST .':'. $PORT.'/verificar') ?>">'Validar correo'</a>.</p>
<p>Si no te has registrado en <?= ($SITIO['titulo']) ?>, por favor ignora este mensaje.</p>
<p>Saludos, tu equipo de <?= ($SITIO['titulo']) ?></p>
<div><?= (date('l d, F \d\e Y')) ?></div>
