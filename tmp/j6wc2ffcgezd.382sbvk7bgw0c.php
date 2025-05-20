
	<option selected>Todas</option>
	<?php foreach (($ciudades?:[]) as $ciudad): ?>
		<option value="<?= ($ciudad['city']) ?>"><?= ($ciudad['city']) ?></option>	
	<?php endforeach; ?>
