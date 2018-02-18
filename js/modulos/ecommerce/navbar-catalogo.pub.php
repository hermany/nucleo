<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_HOST."nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
require_once("navbar.pub.php");
$ruta_cat= $fmt->categoria->ruta_amigable($cat);



if((!isset($_GET["catg"]))&&(!isset($_GET["prod"])) ){
  //$fmt->error->error_404();

  $consulta2 = "SELECT mod_kdx_empresa_actual FROM mod_kardex WHERE mod_kdx_id_usuario=$id_usu and mod_kdx_activar=1";
  $rs2 = $this->fmt->query->consulta($consulta2);
  $fila2=$this->fmt->query->obt_fila($rs2);
   $e=$fila2['mod_kdx_empresa_actual'];
  if (!empty($e)){
      $consulta3 = "SELECT mod_prod_catg_id, mod_prod_catg_ruta_amigable FROM mod_productos_catalogo WHERE mod_prod_catg_id_empresa=$e and mod_prod_catg_activar=1";
      $rs3 = $this->fmt->query->consulta($consulta3);
      $fila3=$this->fmt->query->obt_fila($rs3);
      $ruta_padre = $fila3['mod_prod_catg_ruta_amigable'];
      $url= _RUTA_WEB.'intranet/'.$ruta_cat."/".$ruta_padre."-catg";
      $this->fmt->class_modulo->script_location($url);
  }else{
    if (($id_usu==1)||($id_usu==2)){
        $consulta3 = "SELECT mod_prod_catg_id, mod_prod_catg_ruta_amigable FROM mod_productos_catalogo WHERE mod_prod_catg_activar=1 ORDER BY mod_prod_catg_id asc";
        $rs3 = $this->fmt->query->consulta($consulta3);
        $fila3=$this->fmt->query->obt_fila($rs3);
        $ruta_padre = $fila3['mod_prod_catg_ruta_amigable'];

        $url= _RUTA_WEB.'intranet/'.$ruta_cat."/".$ruta_padre."-catg";
        $this->fmt->class_modulo->script_location($url);
      }else{
        $fmt->error->error_404();
      }
  }

}else{
  $ruta_catg=$_GET["catg"];

  $consulta = "SELECT mod_prod_catg_id,mod_prod_catg_nombre, mod_prod_catg_ruta_amigable FROM mod_productos_catalogo WHERE mod_prod_catg_id_padre=0 and mod_prod_catg_activar=1 ORDER BY mod_prod_catg_orden";
  $rs = $this->fmt->query->consulta($consulta);
  $num=$this->fmt->query->num_registros($rs);

?>
<link rel="stylesheet" href="<?php echo _RUTA_WEB; ?>sitios/intranet/css/catalogo.css" type="text/css" media="screen" />
<div class="navbar-catalogo">
  <div class="navbar-izq">
    <label class="navbar-titulo"><a href="<?php echo _RUTA_WEB; ?>intranet/catalogo<?php echo $id_catg; ?>"><i class="icn icn-cart"></i><?php echo $e; ?>Catálogo corporativo</a></label>
    <ul class="navbar-navs">
      <?php
      if ($num>0){
        for($i=0;$i<$num;$i++){
          list($fila_id,$fila_nombre,$fila_ruta)=$this->fmt->query->obt_fila($rs);
          $active ="";
          $consulta_x = "SELECT mod_prod_catg_rol_rol_id FROM  mod_productos_catg_rol,mod_productos_catalogo  WHERE mod_prod_catg_rol_rol_id=$id_rol and mod_prod_catg_rol_catg_id=$fila_id";
          $rs_x = $this->fmt->query->consulta($consulta_x);
          $num_x=$this->fmt->query->num_registros($rs_x);
          if ($num_x>0){
            if( $fila_ruta == $ruta_catg ){  $active="active"; $catg_padre=$fila_id; $ruta_nombre= $fila_ruta;  define("_RUTA_CATG",$ruta_catg); }
            echo "<li ><a href='"._RUTA_WEB.'intranet/'.$ruta_cat."/".$fila_ruta."-catg' class='btn-item-catalogo $active' id='item-".$fila_id."'>".$fila_nombre."</a></li>";
          }
        }
      }
      ?>
    </ul>
  </div>
  <div class="navbar-der">
    <a class="btn-buscar"><i class="icn icn-search"></i></a>
  </div>
</div>

<?php

}
?>
