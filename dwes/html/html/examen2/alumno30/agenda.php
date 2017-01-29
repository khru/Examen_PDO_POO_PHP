<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	include_once MODEL . 'contactomodel.php';
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

		$contactos = ContactoModel::getAll($id_usu);
		if (!$_POST) {
			// llamamos a la vista empleando la plantilla
			include_once VIEWFORMULARIO . 'formulario_busqueda.php';
			include_once VIEWFORMULARIO . 'boton_insertar_contacto.php';
			if (!$contactos) {
				include_once VIEWFORMULARIO . 'lista_vacia.php';
			} else {
				include_once VIEWFORMULARIO . 'listado_aplicacion.php';
			}
		} else {
			// realizar la busqueda
			// si se borran los campos los pasamos a string vacio
			$array = ["busqueda", "orden", "cont-busqueda"];
			$_POST = Funciones::comprobarParametros($_POST, $array);
			$_POST = Validaciones::sanearEntrada($_POST);
			try {
				$contactos  = new ContactoModel();
				$err = $contactos->buscar($_POST, $id_usu);
				if(is_array($err)){
					if (isset($err["estado"])) {
						$errores = $err;
						$contactos = $contactos::getAll($id_usu);
						// llamamos a la vista empleando la plantilla sin el once
						// para poder recoger los errores, sino nos deja la vista de antes de que existieran los errores
						include VIEWFORMULARIO  . 'formulario_busqueda.php';
						include VIEWFORMULARIO . 'boton_insertar_contacto.php';
						if (!$contactos) {
							include_once VIEWFORMULARIO . 'lista_vacia.php';
						} else {
							include_once VIEWFORMULARIO . 'listado_aplicacion.php';
						}
						$array_pie["Volver"] = "agenda.php";
					} else {
						$contactos = $err;
						include VIEWFORMULARIO  . 'formulario_busqueda.php';
						include VIEWFORMULARIO . 'boton_insertar_contacto.php';
						if (!$contactos) {
							include_once VIEWFORMULARIO . 'lista_vacia.php';
						} else {
							include_once VIEWFORMULARIO . 'listado_aplicacion.php';
						}
						$array_pie["Volver"] = "agenda.php";
					}

				}
			} catch (PDOException $e) {
				// mostramos error 500
				Plantilla::error("500");
			}
		}

		// escribimos el pie
		Plantilla::pie($array_pie);
	}

?>