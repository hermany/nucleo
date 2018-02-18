<?php
header("Content-Type: text/html;charset=utf-8");
require_once(_RUTA_HOST."nucleo/clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
require_once("navbar-catalogo.pub.php");

if (!empty($catg_padre)){
?>
<div class="box-body-catalogo container-fluid">
      <!-- <div class="row novedades">
        <div class="col-md-8 novedad-1">novedad-1</div>
        <div class="col-md-4 novedad-2">novedad-2</div>
      </div> -->
      <div class="row box-catalogo">
        <div class="sidebar-catalogo col-md-3">
          <?php require_once("sidebar-catalogo.pub.php"); ?>
        </div>
        <div class="body-catalogo col-md-9">
          <?php require_once("catalogo.pub.php"); ?>
        </div>
      </div>
</div>

<?php
}else{
  ?>
  <div class="box-body-catalogo container-fluid">
  <?php
  $fmt->error->error_404();
  ?>
  </div>
  <?php
}
?>
