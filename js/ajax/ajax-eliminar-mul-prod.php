<?php
$prod = $_POST["inputProd"];
$mul = $_POST["inputMul"];

$sql1="DELETE FROM mod_productos_mul WHERE  mod_pro_mul_ruta='$mul' and mod_pro_mul_id_prod='$prod'";
$fmt->query->consulta($sql1,__METHOD__);

 ?>
