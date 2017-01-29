<!DOCTYPE html>
	<html lang="es">
	<head>
		<!-- Codificación de la página -->
		<meta charset="UTF-8">
		<!-- Titulo de la página  -->
		<title> <?= $titulo; ?> </title>
        <!-- Estilos css -->
		<link rel="stylesheet" href="<?= $css; ?>">
        <!-- Favicon de la página -->
         <link rel="shortcut icon" type="image/png" href="<?= $favicon; ?>">
		<!-- Evitamos que en dispositivos móviles se pueda ampliar -->
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	</head>
	<body>
        <!-- Cabecera de la página -->
        <header id="header" class="cabecera">
            <!-- Titulo de la página -->
            <h1><?= isset($area) ? $area : $titulo; ?></h1>
        </header><!-- /header -->
        <!-- Contenedor del cuerpo de la página web-->
        <div class="wrap">