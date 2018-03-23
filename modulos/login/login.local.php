<?php
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

if ($fmt->sesion->get_variable("usu_id")!=false){
  $id_usu = $fmt->sesion->get_variable("usu_id");
  $id_rol=$fmt->usuario->id_rol_usuario($id_usu);
  $ruta_rol=_RUTA_WEB.$fmt->usuario->traer_ruta_rol($id_rol);
  if ($ruta_rol==""){
    echo $fmt->errores->error_rol();
    exit(0);
  }else{
  ?>
  <script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/core.js"></script>
  <script type="text/javascript" >
    redireccionar_tiempo("<?php echo $ruta_rol; ?>",1);
  </script>
  <?php
  }
}
echo $fmt->header->header_html();
//$fmt->header->title_page("Logeo - Next Sistemas Integrados");
?>
</head>
<body class='body-login container-fluid'>

  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/estilos.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/theme.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/icon-font.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/font-sourse-sans.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/animate.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/login.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/login-theme.adm.css" rel="stylesheet" type="text/css">
  <?PHP if (_THEME_DEFAULT){ ?>
  <link rel="stylesheet" href="<? echo _THEME_DEFAULT;?>" rel="stylesheet" type="text/css">
  <?php } ?>
  <?php
  echo $fmt->header->js_jquery();
  require_once(_RUTA_NUCLEO."modulos/login/login.form.php");
  ?>

  <div class="login-footer">
    2016-2017 <strong>®</strong> Wappcom &nbsp; | &nbsp;  power <i class="icn-cc"></i> <?php echo _VZ; ?>
  </div>
  <script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/core.js"></script>
</body>
</html>