<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	include_once MODEL . 'contactomodel.php';
	// Si hay sesión no dejamos que vea el login, le redireccionamos a agenda.php
	// dentro de la carpeta publica
	if (!Funciones::existeSesion($_SESSION, "id_usu")) {
		Plantilla::error("403");
	} else {
		// Variables
		$errores = [];
		$contacto = $_POST;
		$array_pie = ["Volver" => "agenda.php", "Cerrar sesión" => "./logout.php"];
		// escribimos la cabecera
		Plantilla::cabecera("Editar");
		$array_enlaces = ['Agenda' => 'agenda.php',
						  'Grupos' => 'grupo.php'];
		Plantilla::menu($array_enlaces);
		$errores = [];
			if (!$_POST) {
				if (!isset($_GET["id"]) || empty($_GET["id"])) {
					Funciones::redireccion("agenda.php");
				} else {
					// validamos $_GET["id"] y la saneamos
					$_GET = Validaciones::sanearEntrada($_GET);
					$_GET["id"] = (int) $_GET["id"];
					$id_usu = $_SESSION["id_usu"];
					if (($contacto = ContactoModel::getOwnContacto($id_usu, $_GET["id"])) === false) {
						// si intentas editar un contacto que no sea tuyo no se te permite
						Funciones::redireccion("agenda.php");
					} else {
						// si estas intentando acceder a uno de tus contactos se muestra el formulario
						include_once VIEWFORMULARIO . 'formulario_contacto.php';
					}
				}
			} else {
				// Si existe $_POST, editamos
				$array = ["nombre", "apellidos", "telf", "email", "direccion", "id_cat", "id_con"];
				$_POST = Funciones::comprobarParametros($_POST, $array);
				$_POST = Validaciones::sanearEntrada($_POST);
				$contactos = new ContactoModel();
				$_POST["id_usu"] = $_SESSION["id_usu"];
				if(is_array(($err = $contactos->update($_POST, $_FILES)))){
					$errores = $err;
					include VIEWFORMULARIO . 'formulario_contacto.php';
				} else {
					Plantilla::accion("contactoeditado");
				}
			}
		Plantilla::pie($array_pie);
	}// fin del editar
?>