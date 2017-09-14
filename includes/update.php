<?php
header("Content-Type: text/html;charset=utf-8");
require_once("../../mainter/config.php");
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR;

 // $consulta_m ="SELECT mul_rel_mul_id, mul_rel_cat_id FROM multimedia_rel ORDER BY mul_rel_id asc";
 //  $rs_m =$fmt->query->consulta($consulta_m);
 //  $num_m=$fmt->query->num_registros($rs_m);
 //  if($num_m>0){
 // 	  for($im=0;$im<$num_m;$im++){
 //   		list($fila_1,$fila_2)=$fmt->query->obt_fila($rs_m);
 //      echo $fila_1.":".$fila_2."</br>";
 //      echo $sql="UPDATE multimedia_categoria SET
 //            mul_cat_mul_id='".$fila_1."',
 //            mul_cat_cat_id='".$fila_2."'";
 //      echo "</br>";
 //      $fmt->query->consulta($sql);
 //    }
 //  }

 // $consulta_m ="SELECT mod_prod_mar_id, mod_prod_mar_imagen FROM mod_productos_marcas ORDER BY mod_prod_mar_id asc";
 //   $rs_m =$fmt->query->consulta($consulta_m);
 //   $num_m=$fmt->query->num_registros($rs_m);
 //   if($num_m>0){
 //  	  for($im=0;$im<$num_m;$im++){
 //    		list($id, $fila_1)=$fmt->query->obt_fila($rs_m);
 //        echo $fila_1."</br>";
 //        $fila_1 = str_replace("sitios/mainter/","",$fila_1);
 //        echo $sql="UPDATE mod_productos_marcas SET
 //              mod_prod_mar_imagen='".$fila_1."' where mod_prod_mar_id=$id";
 //        echo "</br>";
 //        //$fmt->query->consulta($sql);
 //      }
 //    }


 $consulta_m ="SELECT mod_prod_id, mod_prod_imagen FROM mod_productos ORDER BY mod_prod_id asc";
   $rs_m =$fmt->query->consulta($consulta_m);
   $num_m=$fmt->query->num_registros($rs_m);
   if($num_m>0){
     for($im=0;$im<$num_m;$im++){
       list($id, $fila_1)=$fmt->query->obt_fila($rs_m);
        echo $fila_1."</br>";
        $fila_1 = str_replace("sitios/mainter/","",$fila_1);
        $fila_1 = str_replace("sitios/beceratti/","",$fila_1);
        echo $sql="UPDATE mod_productos SET
              mod_prod_imagen='".$fila_1."' where mod_prod_id=$id";
        echo "</br>";
        //$fmt->query->consulta($sql);
      }
    }

?>
