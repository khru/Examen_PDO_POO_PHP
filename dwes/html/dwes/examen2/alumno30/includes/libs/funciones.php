<?php
	// Incluimos la conexión a la BD, para poder recuperar un campo select
	include_once '../../../dwes/examen2/alumno30/includes/core/DBPDO.php';
	/**
	 * Clase de funciones del formulario y auxiliares
	 */
	class Funciones
	{
		/**
		 * Método decomprobación de parametros
		 * @param  Array $comprobar Array que quieres comprobar ($_POST)
		 * @param  Array $indices   Array de parametros que queramos que existan
		 * @return Array  Array con parametros
		 */
		public function comprobarParametros($comprobar, $indices){
			foreach ($indices as $key => $value) {
				if (!isset($comprobar[$value])) {
					$comprobar[$value] = "";
				}
			}
			return $comprobar;
		}// comprobarParametros()

		/**
		 * Método para recuperar el contenido de un campo
		 * @param  String $campo    Campo a recuperar
		 * @param  Array $contacto  Como accedio al campo
		 */
		public static function recuperarCampo($campo, $contacto){
			if (isset($contacto[$campo])) {
				echo 'value="' . $contacto[$campo] . '"';
			}
		}// recuperarCampo

		/**
		 * Método que recupera un campo check
		 * @param  String $campo    Campo que queremos recuperar
		 * @param  Array $contacto  Array desde el que entro el contacto
		 */
		public static function recuperarCampoCheck($campo, $contacto){
			if (isset($contacto[$campo]) && $contacto[$campo] == "on") {
				echo ' checked';
			}
		}// recuperaCampoCheck()

		public static function recurperarCampeoTextarea($campo, $contacto){
			if (isset($contacto[$campo])) {
				echo $contacto[$campo];
			}
		}// recuperarCampoTextarea()

		public static function recuperarCategoria($table, $selected = null){?>
			<select name="id_cat" required>
			 		<option value="0">Seleciona una opción</option>
			 		<?php
			 		try{
			 			// getAll del padre, no de contactomodel
			 			$resultado = DBPDO::getAll($table);
			 			if ($resultado) {
			 				foreach ($resultado as $indice => $valor) {

				 				echo '<option ';
				 				if (isset($selected)) {
			 						if ($selected === $valor["id_cat"]) {
			 							echo ' selected ';
			 						}
			 					}
				 				echo 'value ="' . $valor["id_cat"] . '">';
				 				echo $valor['nombre_cat'];
				 				echo '</option> ';
				 			}
			 			}

			 		} catch (PDOException $e) {
			 			$errores["BD"][] = "Error en la base de datos";
			 			return $errores;
			 		}
			 echo "</select>";
		 }

		// ==================================================
		// Funciones de errores
		// ==================================================

		/**
		 * Método que muestra los errores de un campo
		 * @param  String $campo Campo el cual se desea modificar
		 * @param  Array $errores Array de errores
		 * @return Muestra en el campo los errores
		 */
		public static function mostrarErroresCampo($campo, $errores){
			if (isset($errores[$campo])) {
				echo '<span class="error">' . $errores[$campo] . '</span>';
			}
		}// mostrarErroresCampo()

		/**
		 * Muestra un listado de errores con todos los errores del formulario
		 * @param  Array $errores Array que contiene los errores según se produzcan
		 */
		public static function  mostrarErrores($errores){
			echo '<ul class="listaerrores">';
			foreach ($errores as $clave => $error){
				echo '<li>' . $error . '</li>';
			}
			echo '</ul>';
		}// mostrarErrores()

		/**
		* Función que generá una cooki reciviendo 2 o 3 parametros,
		* esta función existe para darle más semantica a el código
		* @param  String $id        [Identificador de la cookie(como hacceder a ella)]
		* @param  String $contenido [description]
		* @param $expira    [cuando expira la cookie, por defecto 2 anios]
		*
		*/
		public static function generarCookie($id ,$contenido, $expira){
			if(!isset($expira)){
				$expira = time() + ((60*60*24*365)*2);
			}
			return setcookie($id, $contenido, $expira);
		}// generarCookie()

		/**
		 * Método que encripta la contraseña
		 * @param  String $passwd Contraseña a cifrar
		 * @return Hash  contraseña cifrada
		 */
		public static function encriptarPasswd($passwd){
			$passwd = md5($passwd);
			return $passwd;
		}// encriptarPasswd()

		/**
		 * Método que crea una sesión si no la hay
		 */
		public static function generarSesion(){
			if (session_id() === "") {
				session_start();
			}
		}// generarSesion()

		/**
		 * Método que crea una sesión si no la hay
		 */
		public static function existeSesion($array, $campo){
			if (isset($array[$campo])) {
				return true;
			}
			return false;
		}// existeSesion()

		/**
		 * Método que cierra las sesiones de la aplicación
		 */
		public static function cerrarSession(){
			self::generarSesion();
			if (isset($_SESSION["email"])) {
				$_SESSION["email"] = null;
			}
			if (isset($_SESSION["id_usu"])) {
				$_SESSION["id_usu"] = null;
			}
			session_destroy();
		}// cerrarSession()

		/**
		 * Método que crea una sesión con los datos proporcionados
		 * @param  String $nombre Nombre que quieres que tenga la sesion
		 * @param  String $dato   La información que quieres que se almacene dentro de la sesión
		 */
		public static function crearSession($nombre, $dato)
		{
			$_SESSION[$nombre] = $dato;
		}//cerrarSession()

		/**
		 * Método que redirecciona a una página dentro de la misma carpeta que te encuentras
		 * @param  String $pagina Página a la que redireccionarse
		 */
		public static function redireccion($pagina = "index.php"){
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			header("Location: http://$host$uri/$pagina");
			exit;
		}// redireccion()

		/**
		 * Mëtodo de redirección a una ppágina en 5 segundos siempre en la misma carpeta
		 * @param  String $pagina Página a la que redireccionar o archivo
		 * @param  String $tiempo Tiempo de espera para redireccionar
		 */
		public static function redireccioConTiempo($pagina, $tiempo = 5){
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			header("refresh: " . $tiempo . "; url=http://$host$uri/$pagina");
			exit;
		}

		public static function generarEnlacesMenu($pagina = "index.php")
		{
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			return "http://$host$uri/$pagina";
		}

	}// fin de la clase
?>