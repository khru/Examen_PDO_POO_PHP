<?php
	// Obtenemos las claves del array asociativo
	// teniendo en cuenta que si no hubiera contactos esto no se mostraría
	// por eso accedemos sin miedo a la posición 0 del array
	$claves = array_keys($contactos[0]);
?>
<table class="lista" border="1">
	<tr>
		<?php 	foreach ($claves as $indice => $valor) : ;	//Se listan las cabeceras de la tabla?>

				<th><?= $valor;?></th>

		<?php 	endforeach ?>
			<th colspan="2">Cambiar</th>
	</tr>
	<?php   foreach ($contactos as $indice => $valor) : //Se listan los datos?>
	<tr>
		<?php foreach ($valor as $clave => $datos) : ?>
				<?php if($clave == 'img' && !empty($datos)): ?>
					<td><img src="<?= $datos; ?>" alt="img" style="width: 100%"></td>
				<?php else : ?>
					<td><?=$datos?></td>
				<?php endif; ?>
		<?php endforeach ?>
				<td><a href="<?='editar.php?id=' . $valor['id']?>">Editar</a></td>
				<td><a href="<?='borrar.php?id=' . $valor['id']?>">Borrar</a></td>
	</tr>
  <?php endforeach ?>
</table>