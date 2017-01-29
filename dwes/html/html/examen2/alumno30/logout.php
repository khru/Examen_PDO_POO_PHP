<?php
	require_once '../../../dwes/examen2/alumno30/includes/model/usuariomodel.php';
	// la plantilla ya tiene
	require_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	if (!Funciones::existeSesion($_SESSION, "email")) {
		plantilla::error("404");
	} else {
		UsuarioModel::logout();
	}
?>