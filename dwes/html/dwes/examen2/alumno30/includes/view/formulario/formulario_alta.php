<section class="formulario">
	<form action="<?= $_SERVER["PHP_SELF"] ; ?>" name="form-regis" method="POST">
		<?php if(isset($contacto["id_usu"])): ?>
			<p>
				<!-- Campo oculto en caso de edición de usuarios -->
				<input type="hidden" name="id_usu" <?= Funciones::recuperarCampo("id_usu", $contacto);?>>
			</p>
		<?php endif; ?>
		<p>
			<!-- Campo texto -->
			<input type="text" name="nombre" maxlength="25" placeholder="Nombre" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ ]{3,}" <?= Funciones::recuperarCampo("nombre", $contacto); ?>>
		</p>
		<p>
			<?php isset($errores["nombre"]) ? Funciones::mostrarErrores($errores["nombre"]) : ""; ?>
		</p>
		<p>
			<!-- Campo texto -->
			<!--  -->
			<input type="text" name="apellidos" placeholder="Apellidos" maxlength="50" required autofocus pattern="[a-zA-ZÑñáéíóúàèìòùÀÈÌÒÙÁÉÍÓÚäëïöüÄËÏÖÜ ]{3,}" <?= Funciones::recuperarCampo("apellidos", $contacto); ?>>
		</p>
		<p>
			<?php isset($errores["apellidos"]) ? Funciones::mostrarErrores($errores["apellidos"]) : ""; ?>
		</p>
		<p>
			<!-- Campo email -->
			<input type="email" name="email" placeholder="E-mail" maxlength="50" required autofocus  <?= Funciones::recuperarCampo("email", $contacto); ?> >
		</p>
		<p>
			<?php
				isset($errores["email"]) ? Funciones::mostrarErrores($errores["email"]) : "";
			?>
		</p>
		<p>
		<?php
			isset($errores["distintas"]) ? Funciones::mostrarErrores($errores["distintas"]) : "";
		?>
		</p>
		<p>
			<!-- Campo password -->
			<input type="password" name="pass" placeholder="Contraseña" maxlength="25" required autofocus pattern="[a-zA-Z0-9ÑñáéíóúÁÉÍÓÚÀÈÌÒÙÄËÏÖÜäëïöü_-]{6,}">
		</p>
		<p>
			<?php
				isset($errores["pass"]) ? Funciones::mostrarErrores($errores["pass"]) : "";
			?>
		</p>
		<p>
			<!-- Campo password -->
			<!--  -->
			<input type="password" name="pass2" placeholder="Repetir contraseña" maxlength="25" required autofocus pattern="[a-zA-Z0-9ÑñáéíóúÁÉÍÓÚÀÈÌÒÙÄËÏÖÜäëïöü]{6,}">
		</p>
		<p>
			<?php
				isset($errores["pass2"]) ? Funciones::mostrarErrores($errores["pass2"]) : ""
			?>
		</p>
		<p>
			<input type="checkbox" class="check" name="terminos" required <?= Funciones::recuperarCampoCheck("terminos", $contacto); ?>>
			<label for="terminos">Acepta los terminos de uso de esta aplicación</label>
		</p>
		<p>
			<?php
				isset($errores["terminos"]) ? Funciones::mostrarErrores($errores["terminos"]) : "";
			?>
		</p>
		<p>
			<!-- Boton submit -->
			<input class="enviar" type="submit" name="form-regis" value="Enviar">
		</p>
		<p>
			<!-- boton reset -->
			<input class="borrar" type="reset" name="form-regis" value="Borrar">
		</p>
	</form>
</section>