<?php
header('Content-Type: text/html; charset=utf-8');

class ACCIONES{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function cargar_billetera_usuario($usuario,$billetera,$accion,$fechaHora="0000-00-00 00:00:00"){
  	$ingresar ="mod_bill_usu_usu_id,mod_bill_usu_bill_id,mod_bill_usu_inta_id,mod_bill_usu_valor,mod_bill_usu_fecha_hora";
    $valor = $this->valor_interaccion($accion);
		$valores  ="'".$usuario."','".$billetera."','".$accion."','".$valor."','".$fechaHora."'";
		$sql="insert into mod_billetera_usuarios (".$ingresar.") values (".$valores.")";
		$this->fmt->query->consulta($sql);
  }

  function valor_interaccion($accion){
    $consulta = "SELECT mod_inta_valor FROM mod_interaccion WHERE mod_inta_id='".$accion."'";
    $rs =$this->fmt->query->consulta($consulta);
    $row=$this->fmt->query->obt_fila($rs);
    return $row["mod_inta_valor"];
    $this->fmt->query->liberar_consulta();
  }

  function puntos_usuario($usu){
    $sql="SELECT mod_bill_usu_valor FROM mod_billetera_usuarios WHERE mod_bill_usu_usu_id='$usu'";
    $rs= $this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    $valor=0;
    if($num>0){
       for($i=0;$i<$num;$i++){
         $row=$this->fmt->query->obt_fila($rs);
         $valor = $valor + $row["mod_bill_usu_valor"];
       }
    }
    return $valor;
    $$this->fmt->query->liberar_consulta();
  }
}