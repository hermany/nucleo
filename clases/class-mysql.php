<?php
header('Content-Type: text/html; charset=utf-8');

class MYSQL {

	/* variables de conexión */
	var $BaseDatos;
	var $Servidor;
	var $Usuario;
	var $Clave;

	var $conexion;
	var $fechmode = PDO::FETCH_ASSOC;
	var $error;
	var $sql_query;
	var $sql_array;
	var $sql_row;


	/* Método Constructor: Cada vez que creemos una variable de esta clase, se ejecutará esta función */
	function MYSQL($bd = "", $host ="", $user ="", $pass = ""){
		$this->BaseDatos = $bd;
		$this->Servidor = $host;
		$this->Usuario = $user;
		$this->Clave = $pass;
	}

	function conectar($bd,$host,$user,$pass){

		if ($bd != "")	{ $this->BaseDatos = $bd; }
    	if ($host != ""){ $this->Servidor = $host; }
    	if ($user != ""){ $this->Usuario = $user; }
    	if ($pass != ""){ $this->Clave = $pass; }

		try {
			// $this->conexion = new PDO("mysql:dbname=$bd;host=$host",$user,$pass);
			$this->conexion  = new PDO('mysql:host='.$host.';dbname='.$bd.';charset=utf8', $user, $pass, array(
    		PDO::ATTR_PERSISTENT => true,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			));
		} catch (PDOException $e) {
			echo "Error de conexión de base de datos: ".$e->getMessage();
			$this->error = $e->getMessage();
			exit(0);
		}

		//echo "conectado exitosamente";
    return $this->conexion;
	}

	function ErrorInfo(){
		return $this->error;
	}

	function SetFetchMode($tipo){
		//FETCH_ASSOC o 	FETCH_NUM
		$this->fechmode = $tipo;
	}

	function consulta($sql="",$clase="",$array = NULL){
		if ($sql == ""){
			$this->error = "No ha especificado una consulta SQL";
			return 0;
		}
		if ($clase!=""){
			$clase = " - Clase: ".$clase;
		}

		//ejecutamos la consulta
		$consulta = $this->conexion->prepare($sql);
		$consulta->setFetchMode($this->fechmode);
		$retorna = $consulta->execute($array);
		if(!$retorna){
			$errorinfo = $consulta->ErrorInfo();
			if(conf_debug){
				echo $errorinfo[2];
			}
			echo "-".$clase;
		}else{
			$this->sql_query = $consulta;
			//return $retorna;
			return $consulta;
		}
	}

	function obt_fila($rn){
		return $rn->fetch();
	}

	function num_registros($rn){
		return  $rn->rowCount();
	}

	function obt_registros($rn){
		return $rn->fetchAll();
	}

	function liberar_consulta($rn){
		$this->sql_query = null;
	}
}
?>
