<?PHP
header("Content-Type: text/html;charset=utf-8");

class ENLACE{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  public function datos_id($id){
  	$consulta = "SELECT * FROM  enlace WHERE enl_id='$id'";
  	$rs =$this->fmt->query->consulta($consulta);
  	$num=$this->fmt->query->num_registros($rs);
  		if($num>0){
  			$row=$this->fmt->query->obt_fila($rs);
  			return $row;
  		}else{
  			return 0;
  		}
  	$this->fmt->query->liberar_consulta();
  }
}
