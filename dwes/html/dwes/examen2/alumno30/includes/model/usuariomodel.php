<?php
	// Como van a ser utilizados desde un archivo con plantilla posiblemente no sena
	// necesarios, por lo tanto posiblemente se eliminen
	require_once '../../../dwes/examen2/alumno30/includes/libs/funciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/libs/validaciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/core/DBPDO.php';

	/**
	 * Clase UsuarioModel
	 */
	class UsuarioModel extends DBPDO
	{
		// Variable de conexión del usuario
		private $db;
		public $table = "usuario";

		/**
		 * Constructor de un usuario, el cual crea una conexión a la base de datos
		 */
	    public function __construct()
	    {
	    	$this->db = parent::getInstance()->getDatabase();
	    }// __construct()

	    /**
	     * @return PDO
	     */
	    public function getDb()
	    {
	        return $this->db;
	    }// detDb()
	    /**
	     * @return String
	     */
	    public function getTable()
	    {
	        return $this->table;
	    }
	    /**
	     * Método insert que emplea el método de inserción del padre
	     * @param  Array $params Parametros que queremos insertar
	     * @return Boolean | Array    True = si se inserta, False = si no se inserta, Array = cuando hay errores
	     */
	    public function insert($params){
	    	$contacto = "insertar";
	    	if (($erro = $this->validateParams($params,$contacto)) !== true) {
	    		if (isset($erro["estado"])) {
	    			return Validaciones::resultado($erro);
	    		}
	    		// pasamos el array de parametros con los nombres estandarizados
	    		$params = ["nombre" => $params["nombre"],
	    					"apellidos" => $params["apellidos"],
	    					"email" => $params["email"],
	    					"pass" => $params["pass"]];
	    		// tratamiento de los parametros antes de la inserción
	    		$params["pass"] = MD5($params["pass"]);
	    		$params["email"] = mb_strtolower(($params["email"]));
	    		return parent::insert($params);
	    	}
	    }// insert()

	    /**
	     * Método de validación y saneamiento de los parametros
	     * @param  Array $params Se le pasa $_POST para poder validar cada uno de sus atributos
	     * @return Array   Errores = cuando existan, Parametros = cuando se hayan validado y saneado
	     */
	    private function validateParams($params, $contacto){
	    	$errores = [];
	        //Hacemos el saneamiento de los parametros
	        $params = Validaciones::sanearEntrada($params);
	        // Hacemos las validaciones
	        if ($contacto === "Editar") {
	        	if (isset($params["id_usu"])) {
	        		if (($erro = Validaciones::validarNombre($params["id_usu"])) !== true) {
		        		$errores["id_usu"] = $erro;
		        	}
	        	} else {
	        		$errores["id_usu"][] = "El id está vacio";
	        	}
	        }

	        // comprobamos que existe el indice puesto que sino, esto dará excepciones
	        if (isset($params["nombre"])) {
	        	//Validamos el nombre
		        if (($erro = Validaciones::validarNombre($params["nombre"])) !== true) {
		        	$errores["nombre"] = $erro;
		        }// fin de validación de nombre
	        } else {
	        	$errores["nombre"][] = "El nombre está vacio";
	        }

	        // comprobamos el indice de apellidos
	        if (isset($params["apellidos"])) {
	        	 // validamos los apellidos
		        if (($erro = Validaciones::validarApellidos($params["apellidos"])) !== true) {
		        	$errores["apellidos"] = $erro;
		        }// fin de validación de apellidos
	        } else {
	        	$errores["apellidos"][] = "Los apellidos está vacio";
	        }

	        if (isset($_POST["email"])) {
	        	// validacion del email
		        if (($erro = Validaciones::validarEmail($params["email"])) !== true) {
		        	$errores["email"] = $erro;
		        } else {
		        	$email = $params["email"];
		        	// comprobamos que el email no existe en la base de datos
		  			// parautilizar este método ha de existir un Objeto
		        	if (parent::existeEmail($email,$this->table)) {
		        		$errores["email"][] = "El email ya existe";
		        	}
		        }// fin de validación del email
	        } else {
	        	$errores["email"][] = "El email está vacio";
	        }

	        // comprobaqciones de contraseñas
	        if (isset($_POST["pass"]) && isset($_POST["pass2"])) {
	        	// validacion de las contraseñas
		        if (($erro = Validaciones::validarPassAlta($params["pass"], $params["pass2"])) !== true) {
		        	$errores["distintas"] = $erro;
		        }
	        } elseif (isset($_POST["pass"])) {
	        	$errores["pass2"][] = "La campo repetir contraseñas esta vacio";
	        } elseif (isset($_POST["pass2"])) {
	        	$errores["pass"][] = "La contraseñas esta vacia";
	        } else {
	        	$errores["distintas"][] = "Las contraseñas estan vacias";
	        }
	        // comprobación de terminos
	        if (isset($_POST["terminos"])) {
	        	if (($erro = Validaciones::validarCheckbox($params["terminos"])) !== true) {
	        		$errores["terminos"] = $erro;
	        	}
	        } else {
	        	$errores["terminos"][] = "Debe admitir los terminos de la aplicación";
	        }

	        // si hay errores enviamos el array de errores
	        if ($errores) {
	        	$errores["estado"] = true;
	        	return $errores;
	        }
	        // sino hay errores Mandamos los parametros
	        return $params;
	    }// validateParams()

	    /**
		 * Método que valida los campos del login
		 * @return Array | true Devuelve true si las validaciones han sido pasadas
		 *                 		Y un array de errores en caso de no pasen las validaciones
		 *                   	Este array es asociativo multidimensional se accedería al
		 *                    	array del método que le llama el cual es validarLogin()
		 */
		public static function validarEntradaLogin(){
			// creamos el array de posible errores a generar
			$errores = [];
			// Saneamos $_POST entero
			$_POST = Validaciones::sanearEntrada($_POST);
			if (isset($_POST['email'])) {
				// generamos errores para el email
				if (($err = Validaciones::validarEmail($_POST['email'])) !== true) {
					$errores['email'] = $err;
				}
			} else {
				$errores['email'][] = "El email está vacio";
			}

			if (isset($_POST['pass1'])) {
				// generamos errores para la contraseña
				if(($err = Validaciones::validarPassLogin($_POST['pass1'])) !== true){
					$errores['pass1'] = $err;
				}
			} else {
				$errores['pass1'][] = "La contraseña está vacia";
			}

			// lo paso por una función que comprueba si hay errores o no.
			return Validaciones::resultado($errores);
		}

		/**
		 * Método encargado de validar, el formularo de login
		 * @return  True | array de errores
		 */
		public static function validarLogin(){
			// creamos variable de errores para la función
			$errores = [];
			// Si al validar algun campo recogemos algun error lo igualamos a el array de errores
			// de este método
			if (($err = self::validarEntradaLogin()) !== true) {
				$errores = $err;
			}
			// comprobamos si no existen errores
			if (!$errores) {
				$email = $_POST['email'];
				$pass = Funciones::encriptarPasswd($_POST['pass1']);
				try{
					// creamos una conexión con ES NECESARIA, OBLIGATORIA !!!
					// Para que la función existeEmail tenga una conexión ya iniciada
					// Puesto que al ser este método estatico
					// jamas llegaremos a emplear el constructor de está clase
					$conn = DBPDO::getInstance()->getDatabase();
					$resultados = DBPDO::existeEmail($email,"usuario");
					// si no hay filas afectadas
					if ($resultados == 0) {
						// generamos error
						$errores['email'][] = "El email es incorrecto";
					} else {
						// comprobamos la contraseña en la base de datos
						$ssql2 = "SELECT * FROM usuario WHERE email = :email AND pass = :pass";
						$query2 = $conn->prepare($ssql2);
						$query2->bindParam(':email', $email, PDO::PARAM_STR);
						$query2->bindParam(':pass', $pass, PDO::PARAM_STR);
						$query2->execute();
						if ($query2->rowCount() == 0) {
							$errores['pass1'][] = "La contraseña introducida es incorrecta";
						} else {
							return true;
						}
					}
					// comprobamos si hay errores o no
					return Validaciones::resultado($errores);

				} catch (PDOException $e){
					$errores["DB"] = "Error con la base de datos";
					return Validaciones::resultado($errores);
				}
			} else {
				return Validaciones::resultado($errores);
			}
		}// validarLogin()

		public static function logout()
		{
			Funciones::cerrarSession();
			Funciones::redireccion();
		}// logout()

	}// final de la clase

?>