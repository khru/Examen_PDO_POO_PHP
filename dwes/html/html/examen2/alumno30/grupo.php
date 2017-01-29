<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	include_once MODEL . 'grupomodel.php';
	// Si hay sesión no dejamos que vea el login, le redireccionamos a agenda.php
	// dentro de la carpeta publica
	if (!Funciones::existeSesion($_SESSION, "id_usu")) {
		Plantilla::error("403","index.php");
	} else {
		$id_usu = $_SESSION["id_usu"];
		// Variables
		$errores = [];
		$contacto = $_POST;
		$array_pie = ["Cerrar sesión" => "./logout.php"];
		$array_enlaces = ['Agenda' => 'agenda.php',
						  'Grupos' => 'grupo.php'];
		// escribimos la cabecera
		Plantilla::cabecera("Lista de contactos");
		Plantilla::menu($array_enlaces);

		$grupos = GrupoModel::getAll($id_usu);
		if (!$_POST) {
			// llamamos a la vista empleando la plantilla
			include_once VIEWFORMULARIO . 'boton_insertar_grupo.php';
			if (!$grupos) {
				include_once VIEWFORMULARIO . 'lista_vacia.php';
			} else {
				include_once VIEWFORMULARIO . 'listado_grupo.php';
			}
		}
		// escribimos el pie
		Plantilla::pie($array_pie);
	}
?>