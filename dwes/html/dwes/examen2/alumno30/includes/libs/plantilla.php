<?php
            require_once '../../../dwes/examen2/alumno30/includes/config/constantes.php';
            require_once LIBS . 'funciones.php';
            // Comprobación de sesiones de forma que siempre que se emplee una plantilla
            Funciones::generarSesion();
	/**
	 * Clase plantilla la se encarga de el acceso a vistas
	 */
	class Plantilla
	{
        /**
         * Método cabecera
         * @param  string $titulo Titulo de la aplicación
         * @param  string $css    CSS que se emplea
         */
	    public static function cabecera($titulo = "Agenda", $css = "css/estilo.css", $favicon = "img/php.jpg",$area = null){

            require_once LIBS . 'validaciones.php';
            // incluimos la cabecera en HTML, a la cual le sustituimos
            // las variables por sus respectivos valores
            include_once VIEWPLANTILLA . 'cabecera.php';
		}// cabecera()

        /**
         * Método que escribe llama a una vista la cual muestra
         * un menú
         * @param  array  $array_enlaces Array asociativo, con el nombre del enlace y el enlace.
         */
        public static function menu($array_enlaces = []){
            include_once VIEWPLANTILLA . 'menu.php';
        }// menu()

        /**
         * Método que llama a la vista que escribe el pie
         * Se le pasa un array, el cual se convierte en enlaces
         * @param  array  $array_enlaces Enlaces que se le quieren añadir al pie
         */
        public static function pie($array_enlaces = []){
            include_once VIEWPLANTILLA . 'pie.php';
        }// pie();

        /**
         * Método que llama a la vista de error que se le indique con el CSS que se le pase
         * @param  string $pagina Página a la que quieres que se redireccione
         * @param  string $error  Tipo de error que quieres que se muestre
         * @param  string $css    Estilos de la página
         */
        public static function error($error = "403", $pagina = "index.php", $css = "css/estilo.css"){
            include VIEWERROR . 'error' . $error . '.php';
        }// error()

        /**
         * Método que muestra una pantalla informativa de que se a insertado un usuario
         * @param  string $pagina Página a la que quieres que haga referencia
         * @param  string $css    Ruta del estilo
         */
        public static function accion($archivo ,$pagina = "index.php", $errores, $css = "css/estilo.css"){
            include_once VIEWEACCION . $archivo . '.php';
        }// usuarioCreado()



        /**
         * Método que muestra la cabecerá de
         */
        public static function vistaAgenda(){
            include VIEWFORMULARIO . 'formulario_busqueda.php';
            include VIEWFORMULARIO . 'boton_insertar_contacto.php';
        }//vistaAgenda

	}
?>