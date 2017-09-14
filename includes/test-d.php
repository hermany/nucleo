<?php
require_once ("../../next/config.php");
header("Content-Type: text/html;charset=utf-8");
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
echo $fmt->header->js_jquery();
$id="inputFecha";
$fecha = $fmt->class_modulo->estructurar_fecha_hora("2017-2-3 10:00");
?>
<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote-bs3.css" rel="stylesheet"/>

<link href="<?php echo _RUTA_WEB_NUCLEO; ?>css/summernote.css" rel="stylesheet">
<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/summernote.js"></script>

</head>
<body class='body-dashboard'>
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/theme.adm.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/estilos.adm.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/icon-font.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/animate.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/bootstrap-datetimepicker.min.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/datetimepicker.adm.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet" type="text/css">


<script type="text/javascript">
        $(function () {
            $('#<?php echo $id; ?>').datetimepicker({
              language:  'es',
              format: 'dd-mm-yyyy hh:ii',
              autoclose: true,
               weekStart: 1,
              forceParse: 0,
              todayBtn: true
            });
        });

</script>
<div class="form-group <?php echo $class_div; ?>" >
  <label><?php echo $label; ?></label>
  <input class="form-control  date form_datetime <?php echo $class; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" validar="<?php echo $validar; ?>" placeholder="" value="<?php echo $fecha; ?>" <?php echo $disabled; echo $otros; ?> />
  <?php if (!empty($mensaje)){ ?>
  <p class="help-block"><?php echo $mensaje; ?></p>
  <?php } ?>
</div>



<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/modal.adm.css?reload" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/modal-theme.adm.css?reload" rel="stylesheet" type="text/css">

<div class="modal modal-m-<?php echo $url_a; ?>" id="modal">
  <div class="modal-inner">
    <div class="preloader-page"></div>
  </div>
</div>

<script src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/bootstrap-datetimepicker.es.js"></script>
<script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/core.js?reload"></script>
</body>
</html>
