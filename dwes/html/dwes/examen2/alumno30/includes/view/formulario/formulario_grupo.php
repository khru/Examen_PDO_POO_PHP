<section class="formulario" id="insertar_contacto">
	<!--h1 class="titulo">Formulario de contacto</h1-->
	<form action="<?= $_SERVER["REQUEST_URI"] ; ?>" name="form-cont" method="POST" enctype="multipart/form-data">
		<?php
			isset($errores["DB"]) ? Funciones::mostrarErrores($errores["DB"]) : "";
		?>
		<?php if(isset($contacto["id"])): ?>
			<p>
				<!-- Campo oculto -->
				<input type="hidden" name="id" <?= Funciones::recuperarCampo("id", $contacto);?>>
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
			<label for="descripcion">Descripción:</label>
			<!-- Campo descripcion -->
			<textarea name="descripcion" id="descripcion" cols="30" rows="10" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ 0-9]{6,}"><?= isset($contacto["descripcion"]) ? Funciones::recurperarCampeoTextarea("descripcion", $contacto) : ""; ?></textarea>
		</p>
		<p>
			<?php
				isset($errores["descripcion"]) ? Funciones::mostrarErrores($errores["descripcion"]) : "";
			?>
		</p>
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