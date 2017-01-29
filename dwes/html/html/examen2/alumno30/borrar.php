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

		$errores = [];
		if (!$_POST) {
			if (!isset($_GET["id"]) || empty($_GET["id"])) {
				Funciones::redireccion("agenda.php");
			} else {
				// solo mostramos la plantilla si existe $_GET
				Plantilla::cabecera("Editar");
				// validamos $_GET["id"] y la saneamos
				$_GET = Validaciones::sanearEntrada($_GET);
				$id_con = (int) $_GET["id"];
				$id_usu = $_SESSION["id_usu"];
				if (($contacto = ContactoModel::getOwnContacto($id_usu, $id_con)) === false) {
					// si intentas editar un contacto que no sea tuyo no se te permite
					Funciones::redireccion("agenda.php");
				} else {
					// si estas intentando acceder a uno de tus contactos lo borramos sino no se borran
					$params = ["id_con" => $id_con];
					$contactomodel = new ContactoModel();
					if ($contactomodel->delete($params)) {
						Plantilla::accion("contactoborrado");
					} else {
						Plantilla::accion("contactonoborrado");
					}
				}
				Plantilla::pie($array_pie);
			}
		} else {
			// Si intentan cambiarle el action a un formulario y mandarlo aqui con un contacto
			// suyo o no lo redirecciono, puesto que esa conducto no está permitida en el uso de la aplicación
			Plantilla::error("403");
		}


	}// fin del editar
?>