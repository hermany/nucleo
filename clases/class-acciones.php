<?php
header('Content-Type: text/html; charset=utf-8');

class ACCIONES{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  

  function cargar_interaccion_usuario($usuario,$accion,$lugar,$estado="0",$fechaHora="0000-00-00 00:00:00"){
    $ingresar ="mod_inta_usu_usu_id, mod_inta_usu_inta_id, mod_inta_usu_lugar_id, mod_inta_usu_estado, mod_inta_usu_fecha_hora";
    $valores  ="'".$usuario."','".$accion."','".$lugar."','".$estado."','".$fechaHora."'";
    $sql="insert into mod_interaccion_usuarios (".$ingresar.") values (".$valores.")";
    $this->fmt->query->consulta($sql);
  }

  function actualizar_estado_interaccion_usuario($usuario,$acc,$lugar,$estado,$fechaHora){
    $sql="UPDATE mod_interaccion_usuarios SET mod_inta_usu_estado='".$estado."' WHERE mod_inta_usu_usu_id ='".$usuario."' and mod_inta_usu_lugar_id='".$lugar."' and mod_inta_usu_inta_id='".$acc."' and mod_inta_usu_fecha_hora='".$fechaHora."'";
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

  function puntos_usuario_billetera($usu,$bill){
    $sql="SELECT mod_bill_usu_valor FROM mod_billetera_usuarios WHERE mod_bill_usu_usu_id='$usu' and mod_bill_usu_bill_id='$bill'";
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

  //BILLETERAS

  function cargar_billetera_usuario($usuario,$billetera,$accion,$fechaHora="0000-00-00 00:00:00"){
    $ingresar ="mod_bill_usu_usu_id,mod_bill_usu_bill_id,mod_bill_usu_inta_id,mod_bill_usu_valor,mod_bill_usu_fecha_hora";
    $valor = $this->valor_interaccion($accion);
    $valores  ="'".$usuario."','".$billetera."','".$accion."','".$valor."','".$fechaHora."'";
    $sql="insert into mod_billetera_usuarios (".$ingresar.") values (".$valores.")";
    $this->fmt->query->consulta($sql);
  }

  function existe_billetera_usuario($usuario,$billetera,$acc){
    $consulta = "SELECT * FROM mod_billetera_usuarios WHERE mod_bill_usu_usu_id='$usuario' and mod_bill_usu_inta_id='$acc' and mod_bill_usu_bill_id='$billetera' ";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      return true;
    }else{
      return false;
    }
    $this->fmt->query->liberar_consulta();
  }
}