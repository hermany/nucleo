<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR;

function cantidad($id,$fmt){
$consulta = "SELECT cat_id
FROM  categoria
WHERE cat_id_padre =$id
AND cat_activar =1";
  $rs = $fmt->query->consulta($consulta);
  $num=$fmt->query->num_registros($rs);
  return $num;
}

if(isset($_GET["c"])){
  //echo "c:".$ruta_catg_cat=$_GET["c"];
  $ruta_catg_cat=$_GET["c"];
}

  $consulta = "SELECT cat_id,cat_nombre, cat_ruta_amigable FROM mod_productos_catg_cat, categoria WHERE mod_prod_catg_cat_catg_id=$catg_padre and mod_prod_catg_cat_cat_id=cat_id and cat_activar=1 ORDER BY cat_orden";
  $rs = $fmt->query->consulta($consulta);
  $num= $fmt->query->num_registros($rs);

  if ($num>0){
  ?>
  <div class="box-sibebar-catalogo">
     <label><i class="fa fa-chevron-down"></i> Categorias</label>
     <ul class="list-catalogo">
     <?php
       for($i=0;$i<$num;$i++){
         list($fila_id,$fila_nombre,$fila_ruta)=$fmt->query->obt_fila($rs);
          $sbactive="";
          if (empty($ruta_catg_cat)){
           if ($i==0){ $cat_inicio=$fila_id; $sbactive="active";}
          }else{
            if ($ruta_catg_cat==$fila_ruta){  $sbactive="active"; $cat_inicio=$fila_id; }
          }
         echo "<li><a class=' ".$sbactive."' href='"._RUTA_WEB."intranet/".$ruta_cat."/".$ruta_nombre."-catg/".$fila_ruta."' class='btn-item-cat-catalogo' id='item-cat-".$fila_id."'>".$fila_nombre."</a>";
         echo '<span class="cantidad">'.cantidad($fila_id,$fmt).'</span>';
         echo "</li>";
       }
     ?>
     </ul>
  </div>
  <?php
  }
 ?>
