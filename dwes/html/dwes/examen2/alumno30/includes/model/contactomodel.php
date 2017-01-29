<?php
	// Como van a ser utilizados desde un archivo con plantilla posiblemente no sena
	// necesarios, por lo tanto posiblemente se eliminen
	require_once '../../../dwes/examen2/alumno30/includes/libs/funciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/libs/validaciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/core/DBPDO.php';

	/**
	 * Clase ContactoModel
	 */
	class ContactoModel extends DBPDO
	{
	    // Variable de conexión del usuario
		public $db;
		public $table = "contacto";

	    /**
		 * Constructor de un contacto, el cual crea una conexión a la base de datos
		 */
	    public function __construct()
	    {
	    	$this->db = parent::getInstance()->getDatabase();
	    }// __construct()

	    /**
	     * @return String
	     */
	    public function getTable()
	    {
	        return $this->table;
	    }

	    /**
	     * @return type
	     */
	    public function getDb()
	    {
	        return $this->db;
	    }

	    /**
	     * Método estatico que devuelve todos los contactos, solo vale para esta clase
	     * sobreescribe el del padre en esta clase
	     * @return Array Todos los resultados
	     */
	    public static function getAll($id_usu){
	    	$conn = self::getInstance()->getDatabase();
	    	$ssql = "SELECT id_con as id, contacto.nombre, contacto.apellidos, telf as telefono, contacto.email, direccion, nombre_cat as categoria, fech_al, img FROM contacto, categoria, usuario WHERE contacto.id_cat = categoria.id_cat AND contacto.id_usu = usuario.id_usu AND contacto.id_usu = :id_usu order by id";

	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindParam(':id_usu', $id_usu, PDO::PARAM_INT);
	    	$prepare->execute();
	    	// devolvemos todos los registros
	    	return $prepare->fetchAll(PDO::FETCH_ASSOC);
	    }// getAll()

	    // ==================================================================
	    // Funciones ejercicio 1
	    // ==================================================================

	    public function hayFoto($params){
	    	// sabemos que el id del usuario existe porque lo cojemos de la sesión
	    	$ssql = "SELECT * FROM contacto,usuario WHERE contacto.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND contacto.id_con = :id_con AND contacto.img  IS NULL";
	    	return parent::consulta($ssql, $params, $estado = 2);
	    }//hayFoto()

	    public function anyadirFoto($id_con ,$img)
	    {
	    	// moveremos la imagen a la carpeta y actualizaremos el registro
	    	if (($err = Validaciones::subirImg($id_con,$img)) !== true) {
	    		return Validaciones::resultado($err);
	    	} else {
	    		// actualizamos el registro para añadir la ruta de la imagen
	    		if (isset($_SESSION['destino'])) {
	    			$img = $_SESSION['destino'];
	    			$params  = [':img' 	  => $img,
	    						':id_con' => ($id_con)];
	    			$ssql = "UPDATE contacto SET img = :img WHERE id_con = :id_con";
	    			return parent::consulta($ssql, $params, $estado = 2);

	    		}
	    		$errores["DB"][] ="El usuario a sido creado, pero subiendo la imagen";
	    		return $errores;

	    	}
	    }

	    /**
	     * Método insert que emplea el método de inserción del padre
	     * @param  Array $params Parametros que queremos insertar
	     * @return Boolean | Array    True = si se inserta, False = si no se inserta, Array = cuando hay errores
	     */
	    public function insert($params, $img = null){
	    	$contacto = "insertar";
	    	if (($erro = $this->validateParams($params,$contacto)) !== true) {
	    		// Si existe el estado devolvemos los errores
	    		if (isset($erro["estado"])) {
	    			return Validaciones::resultado($erro);
	    		}
	    		// Saneamos los parametros, antes de insertarlos
	    		$params = Validaciones::sanearEntrada($params);
	    		// Validamos la img si existe
	    		if (isset($img['img_perf'])) {
	    			if (!empty($img['img_perf']['name'])) {
	    				if (($erro = $this->ValidateImg($img)) !== true) {
				    		// Si existe el estado devolvemos los errores
				    		if (isset($erro["estado"])) {
				    			return Validaciones::resultado($erro);
				    		}
				    		$nombre = Validaciones::generarCodigo();
				    		$nombre .= $img['img_perf']['name'];
				    		$ruta = FOTOS . $nombre;
				    	}
					}

	    		}
	    		// pasamos el array de parametros con los nombres estandarizados
	    		// solo por si acaso
	    		$params = [":nombre" => $params["nombre"],
	    					":apellidos" => $params["apellidos"],
	    					":telf" => $params["telf"],
	    					":email" => $params["email"],
	    					":direccion" => $params["direccion"],
	    					":fech_al" => date("Y-m-d"),
	    					":id_cat" => ((int)$params["id_cat"]),
	    					":id_usu" => ((int)$params["id_usu"])
	    					];
	    		// tratamiento de los parametros antes de la inserción
	    		$params[":email"] = mb_strtolower(($params[":email"]));
	    		$params[":direccion"] = mb_strtolower(($params[":direccion"]));
	    		$params[":direccion"] = htmlspecialchars(($params[":direccion"]));
	    		if (isset($ruta)) {
	    			$params[':img'] = $ruta;
	    			$ssql = 'INSERT INTO contacto (nombre, apellidos, telf, email, direccion, fech_al, id_cat, id_usu, img) VALUES (:nombre, :apellidos, :telf, :email, :direccion, :fech_al, :id_cat, :id_usu, :img)';
	    			parent::consulta($ssql, $params, $estado = 3);
	    			// Recuperamos el ID de la última inserción de nuestra conexión
	    			$id_con =  $_POST['last_id'];
	    			$id_usu = $_SESSION['id_usu'];
	    			$params =  [':id_usu' => ((int) $id_usu),
	    						':id_con' => ((int) $id_con)];
	    			// pruebas con un usuario que no tiene foto
	    			/*$params =  [':id_usu' => ((int) $id_usu),
	    						':id_con' => 1];*/

	    			if(!$this->hayFoto($params)){
	    				// debemos atualizal la imagen del contacto
	    				if(($err = $this->anyadirFoto($id_con,$img)) !== true){
	    					$errores = [];
	    					$errores['img_perf'] = $err;
	    					$errores["DB"][] ="El usuario a sido creado, pero ha habido un error con la imagen";
	    					return $errores;
	    				}
	    				return true;
	    			} else {
	    				$errores = [];
	    				$errores["DB"][] ="El usuario a sido creado, pero ha habido un error con la imagen";
	    				return $errores;
	    			}
	    		}
	    		$ssql = 'INSERT INTO contacto (nombre, apellidos, telf, email, direccion, fech_al, id_cat, id_usu) VALUES (:nombre, :apellidos, :telf, :email, :direccion, :fech_al, :id_cat, :id_usu)';
	    		return parent::consulta($ssql, $params, $estado = 2);
	    	}
	    }// insert()

	   	public function update($params, $img){
	   		$contacto = "editar";
	   		if (($erro = $this->validateParams($params,$contacto)) !== true) {
	    		if (isset($erro["estado"])) {
	    			return Validaciones::resultado($erro);
	    		}
	    		$id_con["id_con"] =  $params['id_con'];

	    		// Validamos la img si existe
	    		if (isset($img['img_perf'])) {
	    			if (!empty($img['img_perf']['name'])) {
	    				if (($erro = $this->ValidateImg($img)) !== true) {
				    		// Si existe el estado devolvemos los errores
				    		if (isset($erro["estado"])) {
				    			return Validaciones::resultado($erro);
				    		}
				    		$nombre = Validaciones::generarCodigo();
				    		$nombre .= $img['img_perf']['name'];
				    		$ruta = FOTOS . $nombre;
				    	}
					}
				}
	    		// pasamos el array de parametros con los nombres estandarizados
	    		// solo por si acaso

	    		$params = [":nombre" => $params["nombre"],
	    					":apellidos" => $params["apellidos"],
	    					":telf" => $params["telf"],
	    					":email" => $params["email"],
	    					":direccion" => $params["direccion"],
	    					":id_cat" => $params["id_cat"],
	    					":id_con" => $id_con["id_con"]
	    					];
	    		// tratamiento de los parametros antes de la inserción
	    		$params[":email"] = mb_strtolower(($params[":email"]));
	    		$params[":direccion"] = mb_strtolower(($params[":direccion"]));
	    		if (isset($ruta)) {
	    			$params[':img'] = $ruta;
	    			$ssql = "UPDATE contacto set nombre = :nombre, apellidos = :apellidos, telf = :telf, email = :email, direccion = :direccion, id_cat = :id_cat, img = :img WHERE id_con = :id_con";
	    			parent::consulta($ssql, $params, $estado = 2);
	    			// Recuperamos el ID de la última inserción de nuestra conexión
	    			$id_con =  $id_con["id_con"];
	    			$id_usu = $_SESSION['id_usu'];
	    			$params =  [':id_usu' => ((int) $id_usu),
	    						':id_con' => ((int) $id_con)];
	    			// pruebas con un usuario que no tiene foto
	    			/*$params =  [':id_usu' => ((int) $id_usu),
	    						':id_con' => 1];*/

	    			if(!$this->hayFoto($params)){
	    				// debemos atualizal la imagen del contacto
	    				if(($err = $this->anyadirFoto($id_con,$img)) !== true){
	    					$errores = [];
	    					$errores['img_perf'] = $err;
	    					$errores["DB"][] ="El usuario a sido creado, pero ha habido un error con la imagen";
	    					return $errores;
	    				}
	    			//var_dump($id_con);echo "<br>";var_dump($ssql);die();
	    				return true;
	    			} else {
	    				$errores = [];
	    				$errores["DB"][] ="El usuario a sido creado, pero ha habido un error con la imagen";
	    				return $errores;
	    			}
	    		}
	    		$ssql = "UPDATE contacto set nombre = :nombre, apellidos = :apellidos, telf = :telf, email = :email, direccion = :direccion, id_cat = :id_cat WHERE id_con = :id_con";
	    		return parent::consulta($ssql, $params, $estado = 2);
	    	}
	   	}// update()

	   	/**
	   	 * Método de borrado que delega su trabajo al delete del padre
	   	 * @param  array $param Array asociativo, con los parametros a borrar
	   	 * @return boolean   true = cuando se ha borrado, false = si no se ha borrado
	   	 */
	   	public function delete($param){
	   		return parent::delete($param);
	    }// delete

	    /**
	     * Método de busqueda
	     * @param  Array $params Parametros a buscar
	     * @return Array    Errores = En caso de los haya y
	     */
	     public function buscar($params, $id_usu){
	     	$contacto = "buscar";

	    	if (($erro = $this->validateParams($params, $contacto)) !== true) {
	    		if (isset($erro["estado"])) {
	    			return Validaciones::resultado($erro);
	    		}
	    		$params["cont-busqueda"] = Validaciones::limpiarString($params["cont-busqueda"]);
	    		$params["busqueda"] = $erro["busqueda"];
	    		$params["orden"] = $erro["orden"];
	    		// pasamos el array de parametros con los nombres estandarizados
	    		// solo por si acaso
	    		$params = ["busqueda" => $params["busqueda"],
	    					"orden" => $params["orden"],
	    					"cont-busqueda" => $params["cont-busqueda"]];
	    		// Llamar a la función que va a realizar la busqueda
	    		return $this->logicaBuscar($params, $id_usu);
	    	}
	    }// buscar()

	    private function logicaBuscar($params, $id_usu){
	    	$ssql = "SELECT id_con as id, contacto.nombre, contacto.apellidos, telf as telefono, contacto.email, direccion, nombre_cat as categoria FROM contacto, categoria, usuario WHERE contacto.id_cat = categoria.id_cat AND contacto.id_usu = usuario.id_usu AND contacto.id_usu = :id_usu AND {$params['busqueda']} LIKE '%{$params['cont-busqueda']}%' {$params['orden']}";
	    	$conn = $this->getDb();
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindValue(':id_usu', $id_usu, PDO::PARAM_INT);
	    	$prepare->execute();
	    	return $prepare->fetchAll(PDO::FETCH_ASSOC);
	    }


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

	        // Si estamos editando validamos esto
	        if ($contacto === "editar") {
	        	if (isset($params["id_con"])) {
	        		if (($erro = Validaciones::validarId($params["id_con"])) !== true) {
	        			$errores["id_con"] = $erro;
	        		}
	        	} else {
	        		$errores["id_con"][] = "No se que contacto deseas editar";
	        	}
	        }// si estamos editando

	        // campos a validar salvo en la busqueda
	        if ($contacto !== "buscar") {
	        	//Validamos el nombre
	        	if (($erro = Validaciones::validarNombre($params["nombre"])) !== true) {
	        		$errores["nombre"] = $erro;
	        	}// fin de validación de nombre

	        	// validamos los apellidos
		        if (($erro = Validaciones::validarApellidos($params["apellidos"])) !== true) {
		        	$errores["apellidos"] = $erro;
		        }// fin de validación de apellidos


	        	 // validamos el telefono
		        if (($erro = Validaciones::validarTelefono($params["telf"])) !== true) {
		        	$errores["telf"] = $erro;
		        }// fin de validación de telefono

	        	// validacion del email
		        if (($erro = Validaciones::validarEmail($params["email"])) !== true) {
		        	$errores["email"] = $erro;
		        } else {
		        	// Si queremos insertar el contacto
		        	if ($contacto === "insertar") {
	        			$email = $params["email"];
			        	// comprobamos que el email no existe en la base de datos
			  			// parautilizar este método ha de existir un Objeto
			        	if (self::existeEmailContacto($email,$params["id_usu"])) {
			        		$errores["email"][] = "El email ya existe";
			        	}
	        		} else { // si queremos editar
	        			// si no hay errores con el id_con, si los hay no hacemos nada
	        			if (!$errores) {
	        				$email = $params["email"];
		        			if (($erro = $this->emailNoRepetidos($params["id_usu"], $params["id_con"], $email)) !== true) {
		        				$errores["email"][] = "El email ya existe";
		        			}
	        			}

	        		}
		        }// fin de validación del email

	        	// validacion de la dirección
	        	$params['direccion'] = Validaciones::limpiarTextarea($params['direccion']);
		        if (($erro = Validaciones::validarDireccion($params["direccion"])) !== true) {
		        	$errores["direccion"] = $erro;
		        }

	        	// validamos el id de la categoría
		        if (($erro = Validaciones::validarId($params["id_cat"])) !== true) {
		        	$errores["id_cat"] = $erro;
		        }// fin de validación del id de la categoría
	        }// campos a editar salvo en la busqueda
	        else {
	        	// campos de validación para las busquedas

	        	// validamos el id del tipo de busqueda
		        if (($erro = Validaciones::validarTipoBusqueda($params["busqueda"])) === false) {
		        	$errores["busqueda"] = $erro;
		        } else{
		        	$params["busqueda"] = Validaciones::compruebaTipoBusqueda($params["busqueda"]);
		        }

		        // validaciones de la ordenación
		        if (($erro = Validaciones::validarTipoOrdenacion($params["orden"])) === false) {
		        	$errores["orden"] = $erro;
		        } else {
		        	// modificamos el parametro una vez validado, para que nos de
		        	// la parte de la consulta que necesitamos
		        	$params["orden"] = Validaciones::compruebaOrdenBusqueda($params["orden"]);
		        }

		        //
		        if (($erro = Validaciones::validarCampoBusqueda($params["cont-busqueda"])) !== true) {
		        	$errores["cont-busqueda"] = $erro;
		        }
	        }// fin de las validaciones del buscador

	        // si hay errores enviamos el array de errores
	        if ($errores) {
	        	$errores["estado"] = true;
	        	return $errores;
	        }
	        // sino hay errores Mandamos los parametros
	        return $params;
	    }// validateParams()

	    public function ValidateImg($img)
	    {
	    	$errores = [];
    		if (($erro = Validaciones::validarImg($img)) !== true) {
    			$errores["img_perf"] = $erro;
    		}

	    	// si hay errores enviamos el array de errores
	        if ($errores) {
	        	$errores["estado"] = true;
	        	return $errores;
	        }
	        // sino hay errores Mandamos los parametros
	        return $img;
	    }

	    /**
	     * Método que comprueba si un usuario tiene un email ya o no
	     * @param  String $email Email a comprobar para dicho id
	     * @param  String $id_usu variable de sesión que identifica al usuario
	     * @return [type]        [description]
	     */
	    public static function existeEmailContacto($email,$id_usu){
	    	$conn = self::getInstance()->getDatabase();
	    	$ssql = "SELECT * FROM contacto, usuario WHERE usuario.id_usu = contacto.id_usu AND contacto.email = :email AND usuario.id_usu = :id ";
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindParam(':email', $email, PDO::PARAM_STR);
	    	$prepare->bindParam(':id', $id_usu, PDO::PARAM_INT);
	    	$prepare->execute();
	    	// devolvemos true o false
	    	$filas = $prepare->rowCount();
	    	return parent::comprobarConsulta($filas);
	    }//existeEmailContacto()

	    /**
	     * Método que comprueba si el email de un contacto en edición es igual que el de otro contacto
	     * que ya exista
	     * @param  String $id_usu    ID del usuario a editar
	     * @param  String $id_con    ID del contacto
	     * @param  String $email_con Email del contacto a comparar
	     * @return Array | true     true = si el email que se está editando no existe Array = cuando exista
	     */
	    public function emailNoRepetidos($id_usu, $id_con, $email_con){
	    	$errores = [];
	    	$ssql = "SELECT contacto.email FROM contacto, usuario WHERE contacto.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND contacto.email NOT IN (SELECT email FROM contacto WHERE id_con = :id_con)";
	    	$conn = DBPDO::getInstance()->getDatabase();
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindParam(':id_usu', $id_usu);
	    	$prepare->bindParam(':id_con', $id_con);
	    	$prepare->execute();
	    	$resultados = $prepare->fetchAll(PDO::FETCH_ASSOC);
	    	if ($resultados) {
	    		foreach ($resultados as $indice => $valor) {
		    		if (isset($valor["email"])) {
		    			if (strcmp($email_con, $valor["email"]) === 0) {
		    				$errores["email"][] = "El email ya existe";
		    			}
		    		}
		    	}
	    	}
	    	return Validaciones::resultado($errores);
	    }// emailNoRepetidos()

	    /**
	     * Método que comprueba que un contacto pertenece a un usuario
	     * @param  Integer $id_usu Id del usuario
	     * @param  Integer $id_con Id del contacto
	     * @return boolean
	     */
	    public static function getOwnContacto($id_usu, $id_con){
	    	$conn = self::getInstance()->getDatabase();
	    	$ssql = "SELECT id_con, contacto.nombre, contacto.apellidos, contacto.telf, contacto.email, direccion, nombre_cat, categoria.id_cat FROM contacto, usuario, categoria WHERE usuario.id_usu = contacto.id_usu AND contacto.id_cat = categoria.id_cat AND usuario.id_usu = :id_usu AND id_con = :id_con";
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindParam(':id_usu', $id_usu);
	    	$prepare->bindParam(':id_con', $id_con);
	    	$prepare->execute();
	    	// devolvemos resultados o false
	    	if (!($resultado = $prepare->fetch(PDO::FETCH_ASSOC))) {
	    		return false;
	    	}
	    	return $resultado;
	    }
	}// Fin de la clase
?>