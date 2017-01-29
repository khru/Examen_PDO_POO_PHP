<?php
	// Obtenemos las claves del array asociativo
	// teniendo en cuenta que si no hubiera grupos esto no se mostraría
	// por eso accedemos sin miedo a la posición 0 del array
	$claves = array_keys($grupos[0]);
?>
<table class="lista" border="1">
	<tr>
		<?php 	foreach ($claves as $indice => $valor) : ;	//Se listan las cabeceras de la tabla?>

				<th><?= $valor;?></th>

		<?php 	endforeach ?>
			<th colspan="3">Acciones</th>
	</tr>
	<?php   foreach ($grupos as $indice => $valor) : //Se listan los datos?>
	<tr>
		<?php foreach ($valor as $clave => $datos) : ?>
			<td><?=$datos?></td>
		<?php endforeach ?>
			<td><a href="<?='editar_grupo.php?id=' . $valor['id']?>">Editar</a></td>
			<td><a href="<?='borrar_grupo.php?id=' . $valor['id']?>">Borrar</a></td>
			<td><a href="<?='add_contacto.php?id=' . $valor['id']?>">Contactos</a></td>
	</tr>
  <?php endforeach ?>
</table>
