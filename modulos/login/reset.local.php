<?php
require_once(_RUTA_NUCLEO."clases/class-constructor.php");
$fmt = new CONSTRUCTOR();

echo $fmt->header->header_html();
//$fmt->header->title_page("Logeo - Next Sistemas Integrados");

?>
</head>
<body class='body-login container-fluid'>

  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/estilos.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/theme.adm.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/icon-font.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/font-sourse-sans.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/animate.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/login.adm.css?reload" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO; ?>css/login-theme.adm.css" rel="stylesheet" type="text/css">
  <?PHP if (_THEME_DEFAULT){ ?>
  <link rel="stylesheet" href="<? echo _THEME_DEFAULT;?>" rel="stylesheet" type="text/css">
  <?php } ?>
  <?php
    echo $fmt->header->js_jquery();


    if ($fmt->get->validar_get($_GET["c"])){
      $vars = explode("-",$_GET["c"]);    
      $email = base64_decode($vars[0]);
      $pw =  base64_decode($vars[1]);
      $cd =  $vars[2];

      if($cd=='RXN0YSBlcyB1bmEgY2xhdmUgcGFyYSBmb3Jnb3QgenVuZGk'){

        $sql="SELECT usu_id FROM usuario WHERE usu_email='".$email."' and usu_password='".$pw."' ";
        $rs  = $fmt->query->consulta($sql,__METHOD__);
        $num = $fmt->query->num_registros($rs);
        if($num>0){
          $row = $fmt->query->obt_fila($rs);
          $id_usu = $row['usu_id'];
        }else{
          echo $fmt->errores->error_pag_no_encontrada();
          exit(0);
        }
      }else{
        echo $fmt->errores->error_pag_no_encontrada();
        exit(0);
      }
    }else{
      echo $fmt->errores->error_pag_no_encontrada();
      exit(0);
    }
  ?>
  <div class="box-login box-forgot box-reset" >
  <!-- <div class="btn btn-cerrar color-text-gris-b"  onclick="toggleId('block-login');"  >
  <i class="icn-close"></i>
  </div> -->
  <form class="form" onsubmit="return action_form(this)" method="POST" id="form-ingreso">
    <div class="brand-login">
    <?php  echo $fmt->brand->brand_login();?>
    </div>
    <div class="control-group mensaje-top" id="mensaje-login"></div> <!--    Mensaje login ajax  -->
    <div class="box-ini">
    <div class="control-group box-text">
      <label class="title" lang="es"> <h3>Restablece tu contraseña</h3></label>
      <p lang="es">Las contraseñas fuertes incluyen números, letras y signos.</p>
    </div>
    <?php 
      

      
      //echo "num:".$num;
    ?>
    <input type="hidden" id="inputId" value="<?php echo base64_encode($id_usu); ?>">
    <div class="control-group  controls box-md-12">
        <input type="password"  autocomplete="false" class="form-control input-lg color-border-gris-a  color-text-gris" id="password" name="passwordSignup" placeholder="Password"  >
        <div class='msg msg-pw' id="mensaje-pw"></div>
    </div>     
    <div class="control-group controls box-md-12">
      <input type="password" class="form-control input-lg color-border-gris-a  color-text-gris" id="confirmarPassword" name="confirmarPasswordSignup" autocomplete="false" placeholder="Confirmar Password"  >
      <div class='msg msg-pw'  id="mensaje-cpw"></div>
    </div>
    <div class="form-actions">
      <a class="btn btn-info btn-lg btn-intro hvr-fade disable" id="btn-ingresar">Resetear contraseña</a>
    </div>
    </div>
  </form>
 <!--  <div class="box-signup">
    ¿No tienes una cuenta? <a href="<?php echo _RUTA_WEB.$link_crear_cuenta; ?>">Crear una cuenta</a>
  </div> -->

</div>
  <div class="login-footer">
    <?php 
      echo _PIE_PAGINA;  
      echo _VZ; 
    ?>
  </div>

<script type="text/javascript">
  $(document).ready(function() {
    var xpw='';
    var ypw='';

    $('#password').blur(function(event) {
      var pw = $(this).val();
      // console.log($(this).val());
      if (pw.length > 0){
        if(fuerza_pass('password','mensaje-pw')){
        // console.log('ok');
          xpw='ok';
        }
      }else{
        $('#msg-pw').html("");
      }
    })

    $('#password').keyup(function(event) {
      var pw = $(this).val();
      // console.log($(this).val());
      if (pw.length > 0){
        if(fuerza_pass('password','mensaje-pw')){
          // console.log('ok');
            xpw='ok';
        }
      }else{
        $('#msg-pw').html("");
      }
    })

    $('#confirmarPassword').keyup(function(event) {
      /* Act on the event */
      var valor = $('#confirmarPassword').val();
      var valor_pw = $('#password').val();
      $('#confirmarPassword').removeClass('error');
      $('#mensaje-cpw').html("");

      // console.log("valor:"+valor_pw);
      
      if (valor_pw==valor){
        // console.log(valor);
        $("#btn-ingresar").removeClass('disable');
        $('#mensaje-cpw').addClass('fuerte');
        $('#mensaje-cpw').html("<span>Ok</span>");

        ypw='ok';

      }else{
        if (valor.length > 0){
          $('#confirmarPassword').addClass('error');
          $('#mensaje-cpw').addClass('none');
          $('#mensaje-cpw').html('<span>El password no coincide.</span>');
        }else{
          $('#confirmarPassword').removeClass('error');
          $('#mensaje-cpw').html("");
        }
      }
    });

    $('#btn-ingresar').click(function(event) {
      /* Act on the event */
      //console.log(xpw+":"+ypw);
      if ((xpw=='ok') && (ypw='ok')){
        var ie = $("#inputId").val();
        var valor_pw = $("#password").val();
            // console.log(ie+":"+valor_pw);
        var ruta = "ajax-reset-usuario";
        $.ajax({
          url:"<?php echo _RUTA_WEB; ?>ajax.php",
          type:"post",
          data:{ ajax:ruta, inputPw:valor_pw, inputId:ie },
          success: function(msg){
            console.log(msg);
            if (msg != 'ok'){
              $('#mensaje-login').html('error');
            }else{
              $('.box-ini').addClass('on');
              $('#mensaje-login').html('<span>Tu password ha sido restaurado exitosamente, en unos segundos te redirecionaremos al ingreso.</span>');
              setTimeout(function() {
                window.location.href = "<?php echo _RUTA_WEB; ?>ingreso";
              }, 5000 );
            }
          }
        });
      }
    });


    function fuerza_pass(id,mensaje_fuerza){
     var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
     var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
     var enoughRegex = new RegExp("(?=.{6,}).*", "g");
      $('#'+mensaje_fuerza).removeClass('error');
      $('#'+mensaje_fuerza).removeClass('media');
      $('#'+mensaje_fuerza).removeClass('fuerte');
      $('#'+mensaje_fuerza).html("");

     if (false == enoughRegex.test($('#'+id).val())) {
            
             $('#'+mensaje_fuerza).html('<span>+ caracteres.</span>');
             return false;
     } else if (strongRegex.test($('#'+id).val())) {
             $('#'+mensaje_fuerza).addClass('fuerte');
             $('#'+mensaje_fuerza).html('<span>Fuerte!</span>');
             return true;
     } else if (mediumRegex.test($('#'+id).val())) {
             $('#'+mensaje_fuerza).addClass('media');
             $('#'+mensaje_fuerza).html('<span>Media!</span>');
             return false;
     } else {
             $('#'+mensaje_fuerza).addClass('error');
             $('#'+mensaje_fuerza).html('<span>Débil!</span>');
             return false;
     }
      // console.log('id:'+ mensaje_fuerza);
      //return true;
    }
  });
</script>
<script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/core.js?reload"></script>
</body>
</html>