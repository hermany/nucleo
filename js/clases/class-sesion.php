<?PHP
	header('Content-Type: text/html; charset=utf-8_spanish_ci');
	class SESION{

		var $fmt;

	  function sesion($fmt){
	    $this->fmt = $fmt;
	  }

		function iniciar_sesion(){
			session_start();
		}

		function set_nombre_sesion($nombre){
			session_name($nombre);
		}

		function get_nombre(){
			return session_name();
		}

		function get_id(){
			return session_id();
		}

		function liberar_sesion($nombre){
			session_unset();
			if(isset($nombre))
			{
				session_destroy();
			}
		}

		function cerrar_sesion(){
			session_destroy();
		}

		function imprimir(){
			var_dump($_SESSION);
		}

    function liberar_variable($nombre){
      if(session_is_registered($nombre)){
        session_unregister($nombre);
    	}
    }

		function existe_variable($nombre){
	  	if(isset($_SESSION[$nombre])){
	  		return true;
	  	} else {
	  		return false;
	  	}
		}

		function set_variable($nombre,$valor){
			//session_register($nombre);
			$_SESSION[$nombre]=$valor;
		}

		function get_variable($nombre){
			if($this->existe_variable($nombre)){
				return $_SESSION[$nombre];
			} else {
				return false;
			}
		}
	};

?>
