<section class="buscador">
	<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="POST" name="search-company">
		<fieldset class="buscador">
			<legend>Tipo de busqueda</legend>
			<p>
				<label for="nombre">Nombre</label>
				<input class="radio" type="radio" name="busqueda" id="nombre" value="nombre" checked>
				<label for="categoria">Categoría</label>
				<input class="radio" type="radio" name="busqueda" id="categoria" value="categoria">
				<label for="direccion">Direccion</label>
				<input class="radio" type="radio" name="busqueda" id="direccion" value="direccion">
			</p>
				<?php isset($errores["busqueda"]) ? Funciones::mostrarErrores($errores["busqueda"]) : ""; ?>
		</fieldset>
		<fieldset>
			<legend>Orden</legend>
			<label for="alfabetico">Nombre:(alfabético)</label>
				<input class="radio" type="radio" name="orden" id="alfabetico" value="nombre">
				<label for="fecha_nueva">Fecha de alta(Nuevos primero):</label>
				<input class="radio" type="radio" name="orden" id="fecha_nueva" value="nuevo" checked>
				<label for="fecha_vieja">Fecha de alta(Viejos primero)</label>
				<input class="radio" type="radio" name="orden" id="fecha_vieja" value="viejo">
				<?php isset($errores["orden"]) ? "<p>" . Funciones::mostrarErrores($errores["orden"]) . "</p>" : ""; ?>
		</fieldset>
		<fieldset>
			<legend>Buscar</legend>
				<p>
					<input  type="search" name="cont-busqueda" placeholder="Busqueda" <?= isset($contacto["cont-busqueda"]) ? Funciones::recuperarCampo("cont-busqueda",$contacto) : ""; ?>>
				<?php isset($errores["cont-busqueda"]) ? "<p>" . Funciones::mostrarErrores($errores["cont-busqueda"]) . "<p>" : ""; ?>

					<input type="submit" class="enviar" name="search-company" id="enviar" value="Buscar">
				</p>
		</fieldset>
	</form>
</section>
