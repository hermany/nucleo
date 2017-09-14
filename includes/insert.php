<?php
header("Content-Type: text/html;charset=utf-8");
require_once("../../mainter/config.php");
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR;

 $consulta_m ="SELECT mul_rel_mul_id, mul_rel_cat_id FROM multimedia_rel ORDER BY mul_rel_id asc";
  $rs_m =$fmt->query->consulta($consulta_m);
  $num_m=$fmt->query->num_registros($rs_m);
  if($num_m>0){
 	  for($im=0;$im<$num_m;$im++){
   		list($fila_1,$fila_2)=$fmt->query->obt_fila($rs_m);
      echo $fila_1.":".$fila_2."</br>";
      $ingresar ="mul_cat_mul_id,mul_cat_cat_id";
      $valores  ="'".$fila_1."','".
  									 $fila_2."'";
      echo $sql="insert into multimedia_categoria (".$ingresar.") values (".$valores.")";
      echo "</br>";
      $fmt->query->consulta($sql);
    }
  }
?>
