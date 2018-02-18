<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_HOST."nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
?>
<div class="publicacion container-fluid" id="pub-<?php echo $pud_id; ?>">
<?php
$sql="select cat_id, cat_nombre, cat_ruta_amigable, cat_activar from  categoria	where cat_activar='1' and cat_id_padre='0' ORDER BY cat_id ASC";
$rs = $fmt->query -> consulta($sql);
$num = $fmt->query -> num_registros($rs);
if($num>0){
for($i=0;$i<$num;$i++){
  list($mod_id,$mod_nombre,$mod_url,$mod_activar)=$fmt->query->obt_fila($rs);
  if ($mod_id!=1){
?>
    <a type="button" href="<?php echo _RUTA_WEB.$mod_url; ?>" class="btn btn-default"><?php echo $mod_nombre; ?></a>
<?php
  }
}
}
if ($num ==1){ echo $fmt->mensaje->no_existe_categorias_hijas(); }
 ?>
</div>
