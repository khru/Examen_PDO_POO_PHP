<?php
	// Obtenemos las claves del array asociativo
	// teniendo en cuenta que si no hubiera grupos esto no se mostraría
	// por eso accedemos sin miedo a la posición 0 del array
	$claves = array_keys($no_contactos[0]);
?>
<section class="formulario" id="insertar_contacto">
<h2>Contactos</h2>
	<form action="<?= $_SERVER["PHP_SELF"] ; ?>" name="form-cont" method="POST" enctype="multipart/form-data">
		<table class="lista" border="1">
			<tr>
				<?php 	foreach ($claves as $indice => $valor) : ;	//Se listan las cabeceras de la tabla?>

						<th><?= $valor;?></th>

				<?php 	endforeach ?>
					<th>Añadir / Borrar</th>
			</tr>
			<?php   foreach ($contactos as $indice => $valor) : //Se listan los datos?>
			<tr>
				<?php foreach ($valor as $clave => $datos) : ?>
					<td><?=$datos?></td>

				<?php endforeach ?>
					<td><input type="checkbox" name="check[]" checked="" value="<?= $valor['id']; ?>" /></a></td>
			</tr>
			<?php endforeach ?>
		<?php if(isset($no_contactos)) :?>
			<?php   foreach ($no_contactos as $indice => $valor) : //Se listan los datos?>
			<tr>
				<?php foreach ($valor as $clave => $datos) : ?>
					<td><?=$datos?></td>

				<?php endforeach ?>
					<td><input type="checkbox" name="check[]" value="<?= $valor['id']; ?>" /></a></td>
			</tr>
			<?php endforeach ?>
		<?php endif; ?>
		</table>
		<br>
		<p>
			<!-- Boton submit -->
			<input class="enviar" type="submit" name="add" value="Añadir">
		</p>
		<br>
		<p>
			<input class="borrar" type="submit" name="del" value="Borrar">
		</p>

	</form>
</section>