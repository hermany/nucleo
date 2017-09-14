<?php

  $id=$_POST["key"];
  $table=$_POST["table"];
  $col_id=$_POST["col_id"];
  $col_ruta=$_POST["col_ruta"];

  $sql="SELECT $col_id, $col_ruta FROM $table WHERE $col_id=$id ";
  $rs=$fmt->query->consulta($sql,__METHOD__);
  $filax=$fmt->query->obt_fila($rs);
  $archivo=_RUTA_WEB.$filax[$col_ruta];
  $sql="DELETE FROM $table WHERE $col_id='".$id."'";
  $fmt->query->consulta($sql,__METHOD__);
  $up_sqr8 = "ALTER TABLE $table AUTO_INCREMENT=1";
  $fmt->query->consulta($up_sqr8,__METHOD__);
  unlink($archivo);
  $output = ['removed' => $id];
echo json_encode($output);
?>
