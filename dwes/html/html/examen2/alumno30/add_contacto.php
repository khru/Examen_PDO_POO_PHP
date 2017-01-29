<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	// Para obtener todos los contactos de un usuario si es necesario
	include_once MODEL . 'grupomodel.php';
	// GESTION DE LA TABLA N:M
	include_once MODEL . 'grupocontactosmodel.php';
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
					// COMPROBAMOS SI EL GRUPO ES TUYO
					if (($grupo = GrupoModel::getOwnGrupo($id_usu, $_GET["id"])) === false) {
						// si intentas editar un contacto que no sea tuyo no se te permite
						Funciones::redireccion("grupo.php");
					} else {
						$_SESSION["id_grupo"] = $_GET["id"];
						// COMPROBAMOS SI EL GRUPO TIENE CONTACTOS AÑADIDOS
						if ($contactos = GrupoContactosModel::getContactosGrupo($_GET["id"])) {
							//var_dump($contactos);
							//include VIEWFORMULARIO . 'listado_contactos_grupo.php';
							$no_contactos = GrupoContactosModel::getContactosNoGrupo($_GET["id"],$id_usu);
							include VIEWFORMULARIO . 'listado_contactos_grupo.php';
						} else {
							$no_contactos = GrupoContactosModel::getAllContactos($id_usu);
							include VIEWFORMULARIO . 'listado_contactos_no_grupo.php';
						}
					}
				}
			} else {
				// Si existe $_POST, editamos
				$grupo = new GrupoContactosModel();
				$_POST["id_usu"] = $_SESSION["id_usu"];
				$_POST["id_grupo"] = $_SESSION["id_grupo"];
				if(is_array(($err = $grupo->update($_POST)))){
					$errores = $err;
					Plantilla::accion("errorgrupocontactos", 'grupo.php',$errores);
				} else {
					Plantilla::accion("grupocontactosactualizado", 'grupo.php');
				}
			}
		Plantilla::pie($array_pie);
	}// fin del editar
?>