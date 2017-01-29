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
	// Array para el pie, puesto que solo puedes acceder aqui si no tienes sesión solo ponemos uno
	$array_pie = ["Registrarme" => "alta.php"];
	// muestro la cabecera
	Plantilla::cabecera("Login");
	// compruebo si hay post
	if (!$_POST) {
		include VIEWFORMULARIO . 'formulario_login.php';
	} else {
		if(($err = UsuarioModel::validarLogin()) !== true){
			$errores = $err;
			include VIEWFORMULARIO . 'formulario_login.php';
		} else {
			// Iniciamos la sesión
			Funciones::crearSession("email", $_POST["email"]);
			Funciones::crearSession("id_usu", $err);
			Funciones::crearSession("fecha", date("Y-m-d H-i-s"));

			if (Funciones::existeSesion($_SESSION, "email")) {
				Funciones::redireccion("agenda.php");
			}
		}

	}
	// muestro el pie de página
	Plantilla::pie($array_pie);
?>