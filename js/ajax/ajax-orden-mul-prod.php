<?php
$aux = $_POST["inputVars"];
$co = explode(":",$aux);
$prod = $_POST["inputProd"];
$cant = $_POST["inputCant"];

for ($i=0; $i < $cant ; $i++) {
  $sql_upd="UPDATE mod_productos_mul SET mod_pro_mul_orden=$i WHERE mod_pro_mul_id_prod='$prod' and mod_pro_mul_ruta='$co[$i]'";
  $fmt->query->consulta($sql_upd,__METHOD__);
}

?>
