<?php
	// cargamos la configuración de la base de datos
	// deberia de estar oculta
	require_once '../../../dwes/examen2/alumno30/includes/config/config.php';

	/**
	* Clase de conexión a la base de datos
	*/
	class DBPDO {
		// Atributos de la clase
		// Objeto de PDO
		private $db;
		// Instancia para realizar el patron de diseño Singleton
		private static $instancia = null;

		// Atributos de gestión
		public $errors = false;
		// Persistencia de la conexión
		private $persistent = true;

		/**
		 * Constructor privado, inaccesible sin Singleton
		 */
		private function __construct(){
			$options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,PDO::ATTR_PERSISTENT => $this->persistent,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            try{
            	// Creamos una conexión con la base de datos
            	$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
            } catch (PDOException $e){
            	echo "<pre>";
            	exit("No ha sido posible establecer la conexión con la base de datos");
            }
		}// __construct()

		/**
		 * Patrón Singleton, se utiliza para
		 * evitar problemas de concurrencia
		 * @return Object $intancia
		 */
		public static function getInstance(){
			if (is_null(self::$instancia)) {
				/* Si la instancia es nula significa, que un objeto de esta clase jamás ha sido instanciado en la clase desde la cual se le hace referencia*/
				self::$instancia = new DBPDO();

			}
			/* Siempre devolvemos la instancia, ya sea la creada en el if, o la ya existente */
			return self::$instancia;
		}// getInstance()

		/**
		 * Método getDatos
		 * @return Object Objeto de tipo PDOStatment
		 */
		public function getDatabase(){
			return $this->db;
		}// getDatabase()

		// ===================================================
		// Métodos de Persistencia para las calses que hereden
		// ===================================================

		/**
		 * Método para normalizar el array asociativo que se le pasa
		 * @param  Array $params Array asociativo a modificar
		 */
		private function normalizeArray($params){
	        foreach ($params as $key => $value) {
	            $params[':'.$key] = $value;
	            unset($params[$key]);
	        }
	        return $params;
	    }// normalizeArray()

	    /**
	     * Método de obtención de un ID
	     * @param  Int $id Id que se busca
	     * @return Array     Resultados de la consulta
	     */
	    public function getID($id){
	    	$conn = self::getInstance()->getDatabase();
	        $prepare = $conn->prepare("SELECT * FROM $this->table WHERE id = :id");
	        $prepare->bindParam(':id', $id, PDO::PARAM_INT);
	        $prepare->execute();
	        return $prepare->fetchAll(PDO::FETCH_ASSOC);
	    }// getID()

	    /**
	     * Método que inserta un array de parametros
	     * @param  Array $params Parametros a insertar
	     * @return Boolean   True = si se inserta, False = si no se inserta
	     */
	    public function insert($params){
	        if(!empty($params)){
	        	$conn = self::getInstance()->getDatabase();
	            //Extraigo las claves del array y lo separo por comas
	            $fields = '(' . implode(',',array_keys($params)) . ')';
	            //Extraigo los valores del array los separo por comas
	            $values = "(:" . implode(",:",array_keys($params)) . ")";
	            $prepare = $conn->prepare('INSERT INTO ' . $this->table . ' ' . $fields . ' VALUES ' . $values);
	            $prepare->execute($this->normalizeArray($params));
	            // devolvemos el último id para generar una sesión con el
	            return $conn->lastInsertId();
	        } else {
	            throw new Exception('Los parámetros están vacíos');
	        }
	    }// insert()

	    /**
	     * Método que actualiza los registros de la tabla que le llame
	     * @param  Array $params Array de parametros que queremos actualizar a de ser asociativo
	     * @param  Array $where  Array asociativo con el nombre del campo y el valor (SOLO UNO)
	     * @return [type]         [description]
	     */
	    public function update($params, $where){
	        if(!empty($params)){
	            $fields = '';
	            foreach($params as $key => $value){
	                $fields .= $key . ' = :' . $key . ',';
	            }
	            $fields = rtrim($fields, ',');
	            // Obtenemos la primera key
	            $key = key($where);
	            // Obtenemos el primer valor
	            $value = $where[$key];
	            $conn = self::getInstance()->getDatabase();
	            $prepare = $conn->prepare("UPDATE $this->table SET $fields WHERE $key = '$value'");
	            // Normalización de parametros
	            $prepare->execute($this->normalizeArray($params));
	            // devolvemos true o false
	            $filas = $prepare->rowCount();
	            return self::comprobarConsulta($filas);
	        } else {
	        	// si se llega aqui, es por un error de programación
	            throw new Exception("Los Parámetros están vacíos");
	        }
	    }// update()

	    /**
	     * Método de borrado, el problema es que puede borrar cualquier cosa incluso
	     * contactos que no son deĺ usuario que la invoca, es por eso que he decidido sobreescribirlo
	     * en el método del hijo
	     * @param  Array $param Array asociativo con la clave de lo que deseas borrar
	     * @return Boolean    true = cuando se ha borrado, false = cuando no se ha borrado
	     */
	    public function delete($param){
	        if(!empty($param)){
	            //Obtenemos la key
	            $key = key($param);
	            $conn = DBPDO::getInstance()->getDatabase();
	            $prepare = $conn->prepare("DELETE FROM $this->table WHERE $key = :$key");
	            $prepare->execute($this->normalizeArray($param));
	            // devolvemos true o false
		    	$filas = $prepare->rowCount();
		    	return self::comprobarConsulta($filas);
	        } else {
	            throw new Exception('Los parámetros están vacíos');
	        }
	    }// delete

	    /**
	     * Método que comprueba si existe un email o no
	     * @param  String $email El email a comprobar
	     * @return   true | false
	     */
	    public function existeEmail($email,$tabla){
	    	$conn = self::getInstance()->getDatabase();
	    	$ssql = "SELECT * FROM $tabla WHERE email = :email";
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->bindParam(':email', $email, PDO::PARAM_STR);
	    	$prepare->execute();
	    	// devolvemos true o false
	    	$filas = $prepare->rowCount();
	    	return self::comprobarConsulta($filas);
	    }//existeEmail()

	    /**
	     * Método de obtención de todos los registros de la base de datos
	     * de una tabla, la cual dependerá de quien la invoque
	     * @return Array todos los registros
	     */
	    public static function getAll($tabla){
	    	$conn = self::getInstance()->getDatabase();
	    	$ssql = "SELECT * FROM $tabla";
	    	$prepare = $conn->prepare($ssql);
	    	$prepare->execute();
	    	// devolvemos todos los registros
	    	return $prepare->fetchAll();
	    }// getAll()

	    /**
	     * Método para empezar ha realizar transacciones
	     */
	    public function setTransaction(){
	        return self::getInstance()->getDatabase()->beginTransaction();
	    }// setTransaction()

	    /**
	     * Método que termina una transacción
	     * @return PDO Termina una transacción
	     */
	    public function endTransaction(){
	        return self::getInstance()->getDatabase()->commit();
	    }// endTransaction()

	    /**
	     * Cancela una transacción
	     * @return Object Cancela una transacción
	     */
	    public function cancelTransaction(){
	        return self::getInstance()->getDatabase()->rollback();
	    }// cancelTransaction()

	    /**
	     * Método que comprueba el Número de filas afectadas en una consulta
	     * @param  PDOStatement $prepare Consulta ejecutada
	     * @return Boolean   true = Si ha habido algun cambio, false = si no hay cambios
	     */
	    public static function comprobarConsulta($filas){
	    	if ($filas === 0) {
	            return false;
	        }
	        return true;
	    }//comprobarConsulta()

	    /**
     * Método que ejecuta la consulta pasada como parametro
     * @param  String  $ssql   Consulta con el bindeo de parametros
     * @param  Array  $parms  Array asociativo, listo para realizar el bindeo de parametros
     * @param  boolean $return true = devuelve un Booleano, falso = devuelve el resultado
     */
    public function consulta($ssql, $parms, $return = 2)
    {
        // creamos la conexión
        $conn = self::getInstance()->getDatabase();
        // preparamos la consulta
        $prepare = $conn->prepare($ssql);
        // ejecutamos la consulta
        $prepare->execute($parms);
        if ($return === 2) {
            $count = $prepare->rowCount();
            return DBPDO::comprobarConsulta($count);
        } elseif ($return === 1) {
            return $prepare->fetchAll();
        } elseif($return === 0) {
            return $prepare->fetch();
        } else {
            $_POST['last_id'] = $conn->lastInsertId();
            $count = $prepare->rowCount();
            return DBPDO::comprobarConsulta($count);
        }
    }// $consulta

	}// Final de la clase DBPDO
?>