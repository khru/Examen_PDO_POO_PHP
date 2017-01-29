<section class="formulario" id="insertar_contacto">
	<!--h1 class="titulo">Formulario de contacto</h1-->
	<form action="<?= $_SERVER["PHP_SELF"] ; ?>" name="form-cont" method="POST" enctype="multipart/form-data">
		<?php
			isset($errores["DB"]) ? Funciones::mostrarErrores($errores["DB"]) : "";
		?>
		<?php if(isset($contacto["id_con"])): ?>
			<p>
				<!-- Campo oculto -->
				<input type="hidden" name="id_con" <?= Funciones::recuperarCampo("id_con", $contacto);?>>
			</p>
		<?php endif; ?>
		<p>
			<!-- Campo nombre -->
			<input type="text" name="nombre" placeholder="Nombre" maxlength="50" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ ]{3,}" <?= Funciones::recuperarCampo("nombre", $contacto); ?>>
		</p>
		<p>
		<?php
			isset($errores["nombre"]) ? Funciones::mostrarErrores($errores["nombre"]) : "";
		?>
		</p>
		<p>
			<!-- Campo apellidos -->
			<input type="text" name="apellidos" placeholder="Apellidos" maxlength="100" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ ]{3,}" <?= Funciones::recuperarCampo("apellidos", $contacto); ?>>
		</p>
		<p>
		<?php
			isset($errores["apellidos"]) ? Funciones::mostrarErrores($errores["apellidos"]) : "";
		?>
		</p>
		<p>
			<!-- Campo telf -->
			<input type="tel" name="telf" placeholder="Teléfono" required maxlength="13" pattern="[0-9]{9,13}" <?= Funciones::recuperarCampo("telf", $contacto); ?>>
		</p>
		<p>
		<?php
			isset($errores["telf"]) ? Funciones::mostrarErrores($errores["telf"]) : "";
		?>
		</p>
		<p>
			<!-- Campo email -->
			<input type="email" name="email" placeholder="E-mail" maxlength="100" required autofocus  <?= Funciones::recuperarCampo("email", $contacto); ?>>
		</p>
		<p>
			<?php
				isset($errores["email"]) ? Funciones::mostrarErrores($errores["email"]) : "";
			?>
		</p>
		<p><!-- fichero -->
  			<input type="hidden" name="MAX_FILE_SIZE" value="500000">
  			<label for="img_perf">Imagen de perfil: (MAX: 500KB)</label>
  			<input type="file" name="img_perf" id="" accept="image/*" autofocus
  			 <?= Funciones::recuperarCampo("img_perf", $contacto); ?> >
  		</p>
  		<p>
			<?php
				isset($errores["img_perf"]) ? Funciones::mostrarErrores($errores["img_perf"]) : "";
			?>
		</p>
		<p>
			<label for="direccion">Dirección:</label>
			<!-- Campo direccion -->
			<textarea name="direccion" id="direccion" cols="30" rows="10" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ 0-9]{6,}"><?= isset($contacto["direccion"]) ? Funciones::recurperarCampeoTextarea("direccion", $contacto) : ""; ?></textarea>
		</p>
		<p>
			<?php
				isset($errores["direccion"]) ? Funciones::mostrarErrores($errores["direccion"]) : "";
			?>
		</p>
		<p>
			<?php isset($contacto["id_cat"]) ? Funciones::recuperarCategoria("categoria", $contacto["id_cat"]) : Funciones::recuperarCategoria("categoria") ;?>
		</p>
		<p>
		<?php
			isset($errores["id_cat"]) ? Funciones::mostrarErrores($errores["id_cat"]) : "";
		?>
		</p>
		<p>
			<!-- Boton submit -->
			<input class="enviar" type="submit" name="form-cont" value="Enviar">
		</p>
		<p>
			<!-- boton reset -->
			<input class="borrar" type="reset" name="form-cont" value="Borrar">
		</p>
	</form>
</section>