<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Usuario creado</title>
		<link rel="stylesheet" href="<?= $css; ?>">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<META HTTP-EQUIV="Refresh" CONTENT="5; URL=<?= $pagina; ?>">
	</head>
	<body>

		<section class="usuario-creado">
			<hgroup>
				<h3><span><?= isset($errores["contacto"]) ? Funciones::mostrarErrores($errores["contacto"]) : "Error actualizando contactos de un grupo"; ?></span></h3>
				<h3>Espere, será redirecionado en 5 segundo...</h3>
			</hgroup>
		</section>
	</body>
</html>