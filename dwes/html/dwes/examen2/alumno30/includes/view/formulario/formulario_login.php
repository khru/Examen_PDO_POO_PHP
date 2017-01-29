<section class="formulario">
	<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" name="login">
		<p>
			<?php
				isset($errores["DB"]) ? Funciones::mostrarErrores($errores["DB"]) : "";
			?>
		</p>
		<p><!-- email -->
			<input type="email" name="email" id="email" placeholder="E-mail" required autofocus <?= Funciones::recuperarCampo("email", $contacto); ?> >
		</p>
		<p>
			<?php
				isset($errores["email"]) ? Funciones::mostrarErrores($errores["email"]) : "";
			?>
		</p>
		<p><!-- Contraseña -->
			<input type="password" name="pass1" id="pass1"  required autofocus pattern="[a-zA-Z0-9_-]{6,}" placeholder="Contraseña">
		</p>
		<p>
			<?php
				isset($errores["pass1"]) ? Funciones::mostrarErrores($errores["pass1"]) : "";
			?>
		</p>
		<p><!-- Botones -->
			<input  class="enviar" type="submit" id="enviar" name="form" value="Enviar">
		</p>
		<p>
			<input class="borrar" type="reset" id="borrar" name="form" value="Borrar">
		</p>
		<section class="registro">
			<p><a href="alta.php">¿Quiero registrarme?</a></p>
		</section>
	</form>
</section>