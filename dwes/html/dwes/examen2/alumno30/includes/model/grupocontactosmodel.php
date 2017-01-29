<?php
	// Como van a ser utilizados desde un archivo con plantilla posiblemente no sena
	// necesarios, por lo tanto posiblemente se eliminen
	require_once '../../../dwes/examen2/alumno30/includes/libs/funciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/libs/validaciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/core/DBPDO.php';

	/**
	 * Clase ContactoModel
	 */
	class GrupoContactosModel extends DBPDO
	{
	    // Variable de conexión del usuario
		public $db;
		public $table = "grupocontactos";

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
	    	$ssql = "SELECT id, grupo.nombre as nombre, descripcion FROM grupo, usuario WHERE grupo.id_usu = usuario.id_usu AND grupo.id_usu = :id_usu order by id";
	    	$params = [':id_usu' => $id_usu];
	    	return parent::consulta($ssql, $params, $estado = 1);
	    }// getAll()

	   	public static function getContactosGrupo($id_grupo)
	   	{
	   		$ssql = "SELECT contacto.id_con as id, contacto.nombre as nombre, contacto.apellidos as apellidos FROM contacto, grupo, grupocontactos WHERE contacto.id_con = grupocontactos.id_con AND grupo.id = grupocontactos.id_grupo AND grupo.id = :id_grupo";
	   		$params = [':id_grupo' => $id_grupo];
	   		return parent::consulta($ssql, $params, $estado = 1);
	   	}


	   	public function comprobarContactoGrupo($id_con, $id_grupo)
	   	{
	   		$ssql = "SELECT contacto.id_con as id, contacto.nombre as nombre, contacto.apellidos as apellidos FROM contacto, grupo, grupocontactos WHERE contacto.id_con = grupocontactos.id_con AND grupo.id = grupocontactos.id_grupo AND grupo.id = :id_grupo AND grupocontactos.id_con = :id_con";
	   		$params = [':id_grupo' => $id_grupo, ':id_con' => $id_con];
	   		return parent::consulta($ssql, $params, $estado = 2);
	   	}

	   	public static function getContactosNoGrupo($id_grupo, $id_usu)
	   	{
	   		$ssql = "SELECT id_con as id, contacto.nombre, contacto.apellidos FROM contacto, usuario WHERE contacto.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND id_con NOT IN (SELECT contacto.id_con FROM contacto, grupo, grupocontactos WHERE contacto.id_con = grupocontactos.id_con AND grupo.id = grupocontactos.id_grupo AND grupo.id = :id_grupo)";
	   		$params = [':id_grupo' => $id_grupo, ':id_usu' => ((int)$id_usu)];
	   		return parent::consulta($ssql, $params, $estado = 1);
	   	}

	   	public static function getAllContactos($id_usu)
	   	{
	   		$ssql = "SELECT id_con as id, contacto.nombre, contacto.apellidos FROM usuario, contacto WHERE contacto.id_usu = usuario.id_usu AND contacto.id_usu = :id_usu";
	   		$params = [':id_usu' => $id_usu];
	   		return parent::consulta($ssql, $params, $estado = 1);
	   	}

	   	public function comprobarContacto($id_con, $id_usu)
	   	{
	   		$ssql = "SELECT * FROM contacto, usuario WHERE contacto.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND id_con = :id_con";
	   		$params = [':id_usu' => $id_usu, ':id_con' => $id_con];
	   		return parent::consulta($ssql, $params);
	   	}

	   	public function update($params)
	   	{
	   		if (!isset($params['check'])) {
	   			// ERROR
	   			$errores["contacto"][] = "No se que contactos quiere actualizar";
	   			return Validaciones::resultado($errores);
	   		} else {
	   			// validamos los id
	   			if($this->validarContactos($params['check'], $params['id_usu'])){
	   				if (isset($params['add'])) {
	   					// Comprobamos si el usuario estaba en la tabla y ahora no está
		   				// Obtenemos todos los contactos del grupo
		   				// para comparar si están o no en la lista enviada
		   				foreach ($params['check'] as $key => $value) {
		   					if (!$this->comprobarContactoGrupo($value, $params['id_grupo'])) {
		   						// Insertar contacto en la tabla
		   						$ssql = "INSERT INTO grupocontactos (id_con, id_grupo, fecha_alta) VALUES (:id_con, :id_grupo, :fecha_alta)";
		   							$fecha = date('Y-m-d');
		   							$params1 = [':id_con' => $value, ':id_grupo' => $params['id_grupo'], ':fecha_alta' => $fecha];
		   							//echo $ssql; var_dump($params1);die();
		   							parent::consulta($ssql, $params1, $estado = 2);
		   					}
		   				}
	   				} elseif (isset($params['del'])) {
	   					foreach ($params['check'] as $key => $value) {
   							$ssql = "DELETE FROM grupocontactos WHERE  id_con = :id_con AND id_grupo = :id_grupo";
   							$params2 = [':id_con' => $value, ':id_grupo' => $params['id_grupo']];
   							parent::consulta($ssql, $params2, $estado = 2);
	   					}
	   				} else {
	   					$errores["contacto"][] = "No se que acción desea realizar";
	   					return Validaciones::resultado($errores);
	   				}

	   			} else {
	   				// Error
	   				$errores["contacto"][] = "No puede añadir un contacto que no es suyo";
	   				return Validaciones::resultado($errores);
	   			}
	   		}
	   	}

	   	public function validarContactos($params, $id_usu)
	   	{
	   		$estado = true;
	   		if (is_array($params)) {
	   			foreach ($params as $key => $value) {
	   				if(!$this->comprobarContacto($value, $id_usu)){
	   					$estado = false;
	   				}
	   			}
	   			return $estado;
	   		}
	   		return false;
	   	}
	}
?>