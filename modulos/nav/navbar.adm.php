<?php
  header('Content-Type: text/html; charset=utf-8');
  define(_EDITANDO,"true");

  $id_usu = $fmt->sesion->get_variable("usu_id");
  $id_rol = $fmt->usuario->id_rol_usuario($id_usu);

  $usu_nombre    = $fmt->usuario->nombre_usuario($id_usu);
  $usu_apellidos = $fmt->usuario->apellidos_usuario($id_usu);
  $usu_imagen    = $fmt->usuario->imagen_usuario_mini($id_usu);
  $rol_nombre    = $fmt->usuario->nombre_rol($id_rol);

  define("_USU_NOMBRE_COMPLETO",$usu_nombre." ". $usu_apellidos);
  define("_USU_IMAGEN",$usu_imagen);
  define("_USU_ID",$id_usu);
  define("_USU_ID_ROL",$id_rol);
 
  $menu_sistemas = $fmt->nav->construir_sistemas_rol($id_rol,$id_usu);
  //echo "menu1".$menu_sistemas;
  if (($id_rol==1)||($id_rol==2)){
    // $ref_inicio= _RUTA_WEB."dashboard";
    $menu_config .= $fmt->nav->construir_title_menu("Administración de sitios");
    $menu_config .= $fmt->nav->construir_sistemas_esenciales("",$id_rol, $id_usu);
  }

?>
<!-- inicio navbar  -->
<style>
#page-content-wrapper{ margin-top: 43px; position:relative; }
.nav-bar-m, .side-bar-m, .boby-page-m{
  margin-top: 43px;
}
</style>
<div class="navbar navbar-top-fixed"><!-- inicio navbar  -->
  <div class="navbar-inner">
    <div class="navbar-left"> <!-- inicio left  -->
      <div class="navbar-header">
        <div class="navbar-brand">
          <a  href="<?php echo $ref_inicio; ?>" >
            <?php echo  $fmt->brand->brand_favicon($cat,"navbar-logo"); ?>
          </a>
        </div>
        <?php
          $consulta= "SELECT * FROM sitio WHERE sitio_activar=1";
          $rs =$fmt->query->consulta($consulta,__METHOD__);
          $num=$fmt->query->num_registros($rs);
          if($num>0){
            ?>
            <div class="navbar-sites" id="navbar-sites" sitios="<?php echo $num; ?>" >
              <a class="btn btn-salto"> <i class="icn  icn-salto icn-boxs"></i></a>
              <div class="box-sites" style="display:none">
                <?php
                for($i=0;$i<$num;$i++){
                  $row=$fmt->query->obt_fila($rs);
                  $nombre = $row["sitio_nombre"];
                  $target = $row["sitio_target"];
                  $tipo = $row["sitio_tipo"];
                  if (!empty($row['sitio_icono'])){
                    $icono = "<i class='icn-site ".$row["sitio_icono"]."'></i>";
                  }else{
                    $icono="";
                  }
                  
                  $ruta = $row["sitio_ruta_amigable"];

                  if ($tipo==2){
                    if (!empty($ruta)){
                      $url =  $row["sitio_url"];
                    }else{
                      $url = _RUTA_WEB;
                    }
                  }else{
                    if (!empty($ruta)){
                      $url = _RUTA_WEB.$ruta;
                    }else{
                      $url = _RUTA_WEB;
                    }
                  }
                  echo "<a href='$url' target='$target'>$icono $nombre <i class='icn icn-skip'></i></a>";
                }
                ?>
              </div>
            </div>
            <?php
          }else{
            ?>
            <div class="navbar-sites" id="navbar-sites" sitios="0">
              <a href="<?php echo _RUTA_WEB; ?>" title="saltar a la web" target="_blank" class="btn btn-salto-unico" > <i class="icn icn-salto icn-arrow-circle-up"></i></a>
            </div>
            <?php
          }
        ?>
      </div><!-- fin navbar-header  -->
    </div><!-- fin left  -->
    <div class="navbar-right"> <!-- inicio rigth  -->
      <div class="navbar-header" id="bs-menu">
        <ul class="nav">
          <li class="">
            <!-- <a class="btn-navbar btn-search" href="#" ><i class="icn-search"></i></a> -->
          </li>
          <?php
            $consulta= "SELECT app_id,  app_ruta_amigable, app_nav_url, app_icono, app_color FROM aplicacion WHERE app_activar=1 ORDER BY app_orden";
            $rs =$fmt->query->consulta($consulta,__METHOD__);
            $num=$fmt->query->num_registros($rs);
            if($num>0){
              for($i=0;$i<$num;$i++){
               $row=$fmt->query->obt_fila($rs);
               $fila_id = $row["app_id"];
               $fila_nombre = $row["app_nombre"];
               $fila_ra = _RUTA_WEB."dashboard/webapp/".$row["app_ruta_amigable"];
               $fila_nav_url = $row["app_nav_url"];
               $fila_icono = $row["app_icono"];
               $fila_color = $row["app_color"];
                ?>
                <li class="">
                  <a class="btn-navbar btn-site-<?php echo $fila_id; ?>" href="<?php echo $fila_ra; ?>" sitio="<?php echo $fila_id; ?>" ><i style="color:<?php echo $fila_color; ?>"class="icn <?php echo $fila_icono; ?>"></i></a>
                </li>
                <?php
              }
            }
          ?>
          <li class="collapse dropdown collapse-menu">
            <a class="btn-collapse-menu dropdown-toggle btn-point-menu">
              <i class="icn-point-menu"></i>
            </a>
            <ul class="profile dropdown-menu">
              <li class="dropdown user">
                <a href="#" target="_blank"><img class="img-user" alt="" src="<?php echo $usu_imagen; ?>"></a>
                <h3 class="name-user"><?php echo $usu_nombre." ".$usu_apellidos; ?>
                <a class="perfil">
                  <i class="icn-credential"></i>
                  <strong class="e-rol">ROL</strong>
                  <span> <?php echo $rol_nombre ;?></span>
                </a>
                <a href="#" target="_blank" class="btn btn-perfil">Editar perfil</a></h3>
              </li>
              <li class="dropdown">
                <a  class="btn-salir" href="<?php echo _RUTA_WEB; ?>logout.php?cat=<?php echo $cat;?>&pla=<?php echo $pla;?>" >
                  <i class="icn-off"></i>
                  <span>Cerrar Sesión</span>
                </a>
              </li>
            </ul>
          </li><!-- fin collampse-menu mobile -->
          <li class="collapse-in dropdown">
            <a class="dropdown-toggle btn-profile-normal">
              <span class="user-img" style="background:url(<?php echo   $usu_imagen; ?>) no-repeat center center" > </span>
              <span class="user-name"><?php echo   $usu_nombre; ?></span>
              <i class="btn-donw icn icn-chevron-donw"></i>
            </a><!-- fin collampse-in perfil -->
            <ul class="profile dropdown-menu">
              <li class="dropdown user">
                <a href="#" target="_blank"><img class="img-user" alt="" src="<?php echo $usu_imagen; ?>"></a>
                <h3 class="name-user"><?php echo $usu_nombre." ".$usu_apellidos; ?>
                <a href="#" target="_blank" class="btn-perfil">Editar perfil</a></h3>
              </li>
              <li class="dropdown rol">
                <a class="perfil disabled"  >
                  <i class="icn-credential"></i>
                  <strong class="e-rol">ROL</strong>
                  <span> <?php echo $rol_nombre ;?></span>
                </a>
              </li>
              <li class="dropdown">
                <a  class="btn-salir" href="<?php echo _RUTA_WEB; ?>logout" >
                  <i class="icn-off"></i>
                  <span>Cerrar Sesión</span>
                </a>
              </li>
            </ul>
          </li><!-- fin perfil general -->
          <?php 
          if (($fmt->nav->num_sistemas_rol($id_rol)>0)||($id_rol==1)){ ?>
          <li class="nav-menu dropdown">
            <a class="btn-navbar btn-menu dropdown-toggle" >
              <i class="icn-reorder"></i>
            </a>
            <ul class="dropdown-menu">
              <li class="block-sistemas">
                <label class="title-menu">Sistemas activos</label>
                <ul class="list-sistemas">
                  <?php echo $menu_sistemas; ?>
                </ul>
              </li>
              <?php if (($id_rol==1)||($id_rol==2)){ ?>
              <li class="config-sitios">
                <ul>
                  <?php echo $menu_config; ?>
                </ul>
              </li>
              <?php }  ?>
            </ul>
          </li><!-- fin nav-menu -->
          <?php  }  ?>
        </ul><!-- fin nav -->
      </div><!-- fin navbar-header  rigth -->
    </div><!-- fin rigth  -->
  </div><!-- fin navbar-inner  -->
</div><!-- fin navbar  -->
<script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/nav.adm.js"></script>
