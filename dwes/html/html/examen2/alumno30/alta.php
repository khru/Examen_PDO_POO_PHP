<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	// Si hay sesión no dejamos que vea el login, le redireccionamos a agenda.php
	// dentro de la carpeta publica
	if (Funciones::existeSesion($_SESSION, "email")) {
		Funciones::redireccion("agenda.php");
	}
	include_once MODEL . 'usuariomodel.php';
	// Variables
	$errores = [];
	$contacto = $_POST;
	// Array para el pie puesto que no puede entrar si hay sessión
	$array_pie = ["Volver" => "."];
	// muestro la cabecera
	Plantilla::cabecera("Crear un Usuario");
	// compruebo si hay post
	if (!$_POST) {
		include VIEWFORMULARIO . 'formulario_alta.php';
	} else {
		$usuario  = new UsuarioModel();
		if(is_array(($err = $usuario->insert($_POST)))){
			$errores = $err;
			include VIEWFORMULARIO . 'formulario_alta.php';
		} else {
			// Iniciamos la sesión
			Funciones::crearSession("email", $_POST["email"]);
			Funciones::crearSession("id_usu", $err);
			Funciones::crearSession("fecha", date("Y-m-d H-i-s"));

			// si hay sesión le redireccionamos a la agenda
			Plantilla::accion("usuariocreado");
		}

	}
	// muestro el pie de página
	Plantilla::pie($array_pie);

?>