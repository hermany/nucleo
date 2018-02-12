<?php
header('Content-Type: text/html; charset=utf-8');

class CONSTRUCTOR{

  var $query;
  var $autentificacion;
  var $get;
  var $sesion;
  var $plantilla;
  var $brand;
  var $mensaje;
  var $error;
  var $usuario;
  var $redireccion;
  var $header;
  var $footer;
  var $nav;
  var $categoria;
  var $class_pagina;
  var $class_modulo;
  var $class_multimedia;
  var $class_documento;
  var $class_sistema;
  var $mail;
  var $archivos;
  var $form;
  var $publicacion;
  var $class_sitios;
  var $finder;
  var $valores;
  var $cookies;


  function __construct() {

	  array_walk($_POST, 'limpiar_cadena');
	  array_walk($_GET, 'limpiar_cadena');

    if(isset($_GET["mod_id"])){
      if (!is_numeric($_GET["mod_id"])){
        define('_ID_MODULO', $_GET["mod_id"]);
      }
    }

    if(isset($_GET["query"])){
      echo "error";
      exit(0);
    }

    if(isset($_GET["id"])){
    	if (!is_numeric($_GET["id"])){
    		header("Location:../index.php?s="._RUTA_DEFAULT."&cat=".$cat."&pla=".$pla."&tarea=error-id");
    	}
    }



    require_once(_RUTA_NUCLEO."clases/class-sesion.php");
    require_once(_RUTA_NUCLEO."clases/class-mysql.php");

    $sesion = new SESION($this);
    $sesion->iniciar_sesion();
    $this->sesion = $sesion;

    $query = new MYSQL();
    $query->conectar(_BASE_DE_DATOS,_HOST,_USUARIO,_PASSWORD);
    $this->query = $query;

    require_once(_RUTA_NUCLEO."clases/class-get.php");
    require_once(_RUTA_NUCLEO."clases/class-plantilla.php");
    require_once(_RUTA_NUCLEO."clases/class-errores.php");
    require_once(_RUTA_NUCLEO."clases/class-redireccion.php");
    require_once(_RUTA_NUCLEO."clases/class-mensajes.php");

    require_once(_RUTA_NUCLEO."clases/class-autentificacion.php");

    require_once(_RUTA_NUCLEO."clases/class-brand.php");
    require_once(_RUTA_NUCLEO."clases/class-usuarios.php");
    require_once(_RUTA_NUCLEO."clases/class-header.php");
    require_once(_RUTA_NUCLEO."clases/class-footer.php");
    require_once(_RUTA_NUCLEO."clases/class-sistemas.php");

    require_once(_RUTA_NUCLEO."clases/class-modulos.php");
    require_once(_RUTA_NUCLEO."clases/class-paginas.php");
    require_once(_RUTA_NUCLEO."clases/class-nav.php");
    require_once(_RUTA_NUCLEO."clases/class-categorias.php");
    require_once(_RUTA_NUCLEO."clases/class-mail.php");
    require_once(_RUTA_NUCLEO."clases/class-archivos.php");
    require_once(_RUTA_NUCLEO."clases/class-form.php");
    require_once(_RUTA_NUCLEO."clases/class-publicacion.php");
    require_once(_RUTA_NUCLEO."clases/class-sitios.php");
    require_once(_RUTA_NUCLEO."clases/class-multimedia.php");
    require_once(_RUTA_NUCLEO."clases/class-documentos.php");
    require_once(_RUTA_NUCLEO."clases/class-finder.php");
    require_once(_RUTA_NUCLEO."clases/class-valores.php");
    require_once(_RUTA_NUCLEO."clases/class-cookies.php");




    $this->get = new GET($this);
    $this->autentificacion = new AUTENTIFICACION($this);
    $this->plantilla = new PLANTILLA($this);

    $this->brand = new BRAND($this);
    $this->mensaje = new MENSAJE($this);
    $this->error = new ERROR($this);
    $this->usuario = new USUARIO($this);
    $this->redireccion =  new REDIRECCION($this);
    $this->header = new CLASSHEADER($this);
    $this->footer = new CLASSFOOTER($this);
    $this->class_pagina = new CLASSPAGINAS($this);
    $this->class_modulo = new CLASSMODULOS($this);
    $this->class_sistema = new CLASSSISTEMAS($this);
    $this->class_multimedia = new CLASSMULTIMEDIA($this);
    $this->class_documento = new CLASSDOCUMENTO($this);
    $this->nav = new NAV($this);
    $this->categoria = new CATEGORIA($this);
    $this->mail = new MAIL($this);
    $this->archivos = new ARCHIVOS($this);
    $this->form = new FORM($this);
    $this->publicacion = new PUBLICACION($this);
    $this->class_sitios = new CLASSSITIOS($this);
    $this->finder = new FINDER($this);
    $this->valores = new VALORES($this);
    $this->cookies = new COOKIES($this);

  }

	function limpiar_cadena($valor){
		$valor = str_ireplace("SELECT","",$valor);
		$valor = str_ireplace("COPY","",$valor);
		$valor = str_ireplace("DELETE","",$valor);
		$valor = str_ireplace("DROP","",$valor);
		$valor = str_ireplace("DUMP","",$valor);
		$valor = str_ireplace(" OR ","",$valor);
		$valor = str_ireplace(" AND ","",$valor);
		$valor = str_ireplace("AND ","",$valor);
		$valor = str_ireplace("%","",$valor);
		$valor = str_ireplace("LIKE","",$valor);
		$valor = str_ireplace("--","",$valor);
		$valor = str_ireplace("^","",$valor);
		$valor = str_ireplace("[","",$valor);
		$valor = str_ireplace("]","",$valor);
		$valor = str_ireplace("\'","",$valor);
		$valor = str_ireplace("!","",$valor);
		$valor = str_ireplace("¡","",$valor);
		$valor = str_ireplace("?","",$valor);
		$valor = str_ireplace("=","",$valor);
		$valor = str_ireplace("&","",$valor);
		//$valor = addslashes($valor);
		return $valor;
	}


}


?>
