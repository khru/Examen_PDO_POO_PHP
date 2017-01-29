<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	include_once MODEL . 'grupomodel.php';
	// Si hay sesión no dejamos que vea el login, le redireccionamos a agenda.php
	// dentro de la carpeta publica
	if (!Funciones::existeSesion($_SESSION, "id_usu")) {
		Plantilla::error("403");
	} else {
		// Variables
		$errores = [];
		$contacto = $_POST;
		$array_pie = ["Volver" => "grupo.php", "Cerrar sesión" => "./logout.php"];
		// escribimos la cabecera
		Plantilla::cabecera("Editar");
		$array_enlaces = ['Agenda' => 'agenda.php',
						  'Grupos' => 'grupo.php'];
		Plantilla::menu($array_enlaces);
		$errores = [];
			if (!$_POST) {
				if (!isset($_GET["id"]) || empty($_GET["id"])) {
					Funciones::redireccion("grupo.php");
				} else {
					// validamos $_GET["id"] y la saneamos
					$_GET = Validaciones::sanearEntrada($_GET);
					$_GET["id"] = (int) $_GET["id"];
					$id_usu = $_SESSION["id_usu"];
					if (($contacto = GrupoModel::getOwngrupo($id_usu, $_GET["id"])) === false) {
						// si intentas editar un contacto que no sea tuyo no se te permite
						Funciones::redireccion("grupo.php");
					} else {
						// si estas intentando acceder a uno de tus contactos se muestra el formulario
						include_once VIEWFORMULARIO . 'formulario_grupo.php';
					}
				}
			} else {
				// Si existe $_POST, editamos
				$array = ["nombre", "descripcion"];
				$_POST = Funciones::comprobarParametros($_POST, $array);
				$_POST = Validaciones::sanearEntrada($_POST);
				$grupo = new GrupoModel();
				$_POST["id_usu"] = $_SESSION["id_usu"];
				if(is_array(($err = $grupo->update($_POST)))){
					$errores = $err;
					include VIEWFORMULARIO . 'formulario_grupo.php';
				} else {
					Plantilla::accion("grupoeditado");
				}
			}
		Plantilla::pie($array_pie);
	}// fin del editar
?>