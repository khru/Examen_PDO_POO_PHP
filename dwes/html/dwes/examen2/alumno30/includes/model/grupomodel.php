<?php
	// Como van a ser utilizados desde un archivo con plantilla posiblemente no sena
	// necesarios, por lo tanto posiblemente se eliminen
	require_once '../../../dwes/examen2/alumno30/includes/libs/funciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/libs/validaciones.php';
	require_once '../../../dwes/examen2/alumno30/includes/core/DBPDO.php';

	/**
	 * Clase ContactoModel
	 */
	class GrupoModel extends DBPDO
	{
	    // Variable de conexión del usuario
		public $db;
		public $table = "grupo";

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

	    public function comprobarNombre($nombre, $id_usu)
	    {
	    	$ssql = "SELECT * FROM grupo, usuario WHERE grupo.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND grupo.nombre = :nombre";
	    	$params =  [':nombre' => $nombre,
	    				':id_usu' => $id_usu];
	    	return parent::consulta($ssql, $params, $estado = 2);
	    }

	    public function comprobarId($id, $id_usu)
	    {
	    	$ssql = "SELECT id FROM grupo,usuario WHERE grupo.id_usu = usuario.id_usu AND usuario.id_usu = :id_usu AND grupo.id = :id";
	    	$params = [':id_usu' => $id_usu,
	    				':id'	 => $id];
	    	return parent::consulta($ssql, $params, $estado = 2);
	    }

	    public static function getOwnGrupo($id_usu, $id)
	    {
	    	$ssql = "SELECT id, grupo.nombre,descripcion FROM grupo, usuario WHERE grupo.id_usu = usuario.id_usu AND grupo.id_usu = :id_usu AND grupo.id = :id";
	    	$params = [ ':id_usu' 	=> ((int) $id_usu),
	    				':id' 		=> ((int) $id)];
	    	return parent::consulta($ssql, $params, $estado = 0);
	    }

	    public function insert($params)
	    {
	        //Hacemos el saneamiento de los parametros
	        $params = Validaciones::sanearEntrada($params);
	        $id_usu = $params['id_usu'];
	        // Hacemos las validaciones
	        // Creamos la variable de errores
	        $errores = [];
	        // Validamos el nombre del grupo
	        if (isset($params['nombre']) && !empty($params['nombre'])) {
	        	if (($erro = Validaciones::validarNombre($params["nombre"])) !== true) {
	        		$errores["nombre"] = $erro;
	        	}elseif ($this->comprobarNombre($params['nombre'], $id_usu)) {
	        		// comprobamos que el nombre no está ocupado
	        		$errores["nombre"][] = "El nombre ya está en uso";
	        	}
	        } else {
	        	$errores["nombre"][] = "El nombre no está vacio";
	        }
	        // Validamos el descripción del grupo
	        if (isset($params['descripcion']) && !empty($params['descripcion'])) {
	        	// comprobamos que el descripcion no está ocupado
	        	if (($erro = Validaciones::validarDescripcion($params["descripcion"])) !== true) {
	        		$errores["descripcion"] = $erro;
	        	}
	        } else {
	        	$errores["descripcion"][] = "La descripcion no está vacio";
	        }

	        if ($errores) {
	        	return Validaciones::resultado($errores);
	        }

	        $ssql = "INSERT INTO grupo (nombre, descripcion, id_usu) VALUES(UPPER(:nombre), :descripcion, :id_usu)";
	        $params = [ ':nombre' 		=> $params['nombre'],
	        			':descripcion' 	=> $params['descripcion'],
	        			':id_usu'		=> $id_usu];
	        return parent::consulta($ssql, $params, $estado = 2);

	    }// insert()

	    public function update($params)
	    {
	    	//Hacemos el saneamiento de los parametros
	        $params = Validaciones::sanearEntrada($params);
	        $id_usu = $params['id_usu'];
	        // Hacemos las validaciones
	        // Creamos la variable de errores
	        $errores = [];
	        // validamos el id
	        if (isset($params['id']) && !empty($params['id'])) {
	        	// comprobamos que el id es valido
	        	if (!$this->comprobarId($params['id'], $id_usu)) {
	        		$errores["DB"][] = "No puedes editar un contacto que no es tuyo";
	        	}
	        } else {
	        	$errores["DB"][] = "No se que grupo estas intentando editar";
	        }

	        // Validamos el nombre del grupo
	        if (isset($params['nombre']) && !empty($params['nombre'])) {
	        	if (($erro = Validaciones::validarNombre($params["nombre"])) !== true) {
	        		$errores["nombre"] = $erro;
	        	}elseif ($this->comprobarNombre($params['nombre'])) {
	        		// comprobamos que el nombre no está ocupado
	        		$errores["nombre"][] = "El nombre ya está en uso";
	        	}
	        } else {
	        	$errores["nombre"][] = "El nombre no está vacio";
	        }
	        // Validamos el descripción del grupo
	        if (isset($params['descripcion']) && !empty($params['descripcion'])) {
	        	// comprobamos que el descripcion no está ocupado
	        	if (($erro = Validaciones::validarDescripcion($params["descripcion"])) !== true) {
	        		$errores["descripcion"] = $erro;
	        	}
	        } else {
	        	$errores["descripcion"][] = "La descripcion no está vacio";
	        }

	        if ($errores) {
	        	return Validaciones::resultado($errores);
	        }
	        $ssql = "UPDATE grupo SET nombre = :nombre, descripcion = :descripcion WHERE id_usu = :id_usu AND id = :id";
	        $params = [ ':nombre' 		=> $params['nombre'],
	        			':descripcion' 	=> $params['descripcion'],
	        			':id_usu'		=> $id_usu,
	        			':id'			=> $params['id']];
	        return parent::consulta($ssql, $params, $estado = 2);
	    }// update()

	    // borrar el grupo seleccionado
	    // En el ejercicio final deberemos de borrar con una
	    // transacción los contactos que pertenecen a dicho grupo
	    public function delete($params)
	    {
	    	$ssql = "SELECT * FROM grupocontactos WHERE id_grupo = :id_grupo";
	    	$p = [':id_grupo' => $params[':id']];
	    	if($resultado = parent::consulta($ssql, $p, $estado = 2)){
	    		// si hay contactos que dependan del grupo se borran primero
	    		$ssql = "DELETE FROM grupocontactos WHERE id_grupo = :id_grupo";
	    		$p = [':id_grupo' => $params[':id']];
	    		if ($resultado = parent::consulta($ssql, $p, $estado = 2)) {
	    			$ssql = "DELETE FROM $this->table WHERE id = :id";
	   				return parent::consulta($ssql, $params, $estado = 2);
	    		}
	    		return false;

	    	} else {
	    		// Si no hay contactos que dependan del grupo se borra
	    		$ssql = "DELETE FROM $this->table WHERE id = :id";
	   			return parent::consulta($ssql, $params, $estado = 2);
	    	}
	    }


	}// fin de la clase
?>