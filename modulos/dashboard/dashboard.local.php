<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado

require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();
/* ----- Saltar al login si no se esta en sesión ----- */
  if ($fmt->sesion->get_variable("usu_id")==false){
    $ruta_rol=_RUTA_WEB."login";
    ?>
    <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/core.js"></script>
    <script type="text/javascript" >
      redireccionar_tiempo("<?php echo $ruta_rol; ?>",1);
    </script>
    <?php
  }

echo $fmt->header->header_html();
echo $fmt->header->js_jquery();
$fmt->header->title_page("Dashboard");
?>
<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote-bs3.css" rel="stylesheet"/>
<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote.css" rel="stylesheet">
<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/summernote.js"></script>
  
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/icon-font.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/estilos.adm.css?reload" rel="stylesheet" type="text/css">
  
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/theme.adm.css?reload" rel="stylesheet" type="text/css">

  <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO; ?>css/nav.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO; ?>css/nav-theme.adm.css" rel="stylesheet" type="text/css">

</head>
<body class='body-dashboard'>

  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/animate.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/bootstrap-datetimepicker.min.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/datetimepicker.adm.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/bootstrap-glyphicons.css" rel="stylesheet" type="text/css">


  <?php
  if(_THEME_DEFAULT_ADMIN){ ?>
  <link rel="stylesheet" href="<?php echo _RUTA_WEB._THEME_DEFAULT_ADMIN; ?>?reload" rel="stylesheet" type="text/css">\
  <?php } ?>

  <div class='preloader-modulo'></div>
  <?php
  require_once(_RUTA_NUCLEO.'modulos/nav/navbar.adm.php');
  ?>
  <div class="container-fluid content-page" id="content-page">
  <?php

    if ($_GET["m"]){
      $sql ="SELECT mod_url,mod_id,mod_ruta_amigable FROM modulo WHERE mod_ruta_amigable='".$_GET["m"]."'";
      $rs = $fmt->query->consulta($sql,__METHOD__);
      $row = $fmt->query->obt_fila($rs);
      $url_mod = _RUTA_NUCLEO."".$row["mod_url"];
      $url = _RUTA_NUCLEO."".$row["mod_url"];
      $url_a= $row["mod_ruta_amigable"];
      if (file_exists($url)) {
        $id_mod = $row["mod_id"];
        require_once($url_mod);
      }else{
        $fmt->errores->error_pag_no_encontrada();
      }
    }

    if ($_GET["app"]){
      $sql ="SELECT app_url,app_id,app_ruta_amigable FROM aplicacion WHERE app_ruta_amigable='".$_GET["app"]."'";
      $rs = $fmt->query->consulta($sql,__METHOD__);
      $row = $fmt->query->obt_fila($rs);
      $url_app = _RUTA_NUCLEO."".$row["app_url"];
      $url_a = $row["app_ruta_amigable"];
      if (file_exists($url_app)) {
        $id_app = $row["app_id"];
        require_once($url_app);
      }else{
        $fmt->errores->error_pag_no_encontrada();
      }
    }
  ?>
  <!-- <a class="btn-menu-ajax" id_mod="27" vars="" >hello</a> -->

  </div><!--  content-pag -->
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/modal.adm.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/modal-theme.adm.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/finder.css?reload" rel="stylesheet" type="text/css">


  <div class="modal modal-eliminar"><div class="modal-eliminar-inner"></div></div>
  <div class="modal modal-list"><div class="modal-list-inner"></div></div>
  <div class="modal modal-editar"><div class="modal-editar-inner"></div></div>
  <div class="modal modal-finder"><div class="modal-finder-inner"></div></div>
  <div class="modal modal-form modal-m-<?php echo $url_a; ?>" id="modal"><div class="modal-inner"><div class="preloader-page"></div></div></div>

  <script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/jquery-ui.min.js"></script>
  <script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap.js"></script>
  <script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/summernote.js"></script>

  <script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap-datetimepicker.es.js"></script>
  <script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/core.js?reload"></script>

 </body>
</html>