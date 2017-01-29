<?php
	// Includes de plantilla, para poder utilizar las constantes
	include_once '../../../dwes/examen2/alumno30/includes/libs/plantilla.php';
	include_once MODEL . 'grupomodel.php';
	// Si hay sesión no dejamos que vea el login, le redireccionamos a agenda.php
	// dentro de la carpeta publica
	if (!Funciones::existeSesion($_SESSION, "email")) {
		Plantilla::error("403");
	} else {
		// Variables
		$id_usu = $_SESSION["id_usu"];
		$errores = [];
		$contacto = $_POST;
		$array_pie = ["Volver" => "grupo.php", "Cerrar sesión" => "./logout.php"];
		// escribimos la cabecera
		Plantilla::cabecera("Insertar");
		$array_enlaces = ['Agenda' => 'agenda.php',
						  'Grupos' => 'grupo.php'];
		Plantilla::menu($array_enlaces);
		if (!$_POST) {
			// mostramos los datos
			include_once VIEWFORMULARIO . 'formulario_grupo.php';
		} else {
			$array = ["nombre", "descripcion"];
			$_POST = Funciones::comprobarParametros($_POST, $array);
			$_POST = Validaciones::sanearEntrada($_POST);
			$_POST["id_usu"] = $_SESSION["id_usu"];
			try {
				$grupos  = new GrupoModel();
				// le pasamos al modelo $_files
				if(is_array(($err = $grupos->insert($_POST)))){
					$errores = $err;
					include VIEWFORMULARIO . 'formulario_grupo.php';
				} else {
					// si hay sesión le redireccionamos a la agenda
					Plantilla::accion("grupocreado", "grupo.php");
				}
			} catch(Exception $e){
				echo "<pre>";
				echo $e->getMessage();
			} catch (PDOException $e) {
				//Plantilla::error("500");
				echo "<pre>";
				echo $e->getMessage();
			}

		}

		Plantilla::pie($array_pie);
	}

?>