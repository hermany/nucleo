<?php
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

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
    require_once(_RUTA_NUCLEO."modulos/login/forgot.form.php");
  ?>
  <div class="login-footer">
    2016-2017 <strong>®</strong> Wappcom &nbsp; | &nbsp;  power <i class="icn-cc"></i> <?php echo _VZ; ?>
  </div>
  <script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/core.js"></script>
</body>
</html>
