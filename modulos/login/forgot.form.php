<?php
$fmt = new CONSTRUCTOR();

//echo _LINK_SIGNUP;
$link =_LINK_SIGNUP;
if (empty($link)){
	$link_crear_cuenta = "signup";
}else{
	$link_crear_cuenta = $link;
}
?>
<div class="box-login box-forgot" >
  <!-- <div class="btn btn-cerrar color-text-gris-b"  onclick="toggleId('block-login');"  >
  <i class="icn-close"></i>
  </div> -->
  <form class="form" onsubmit="return action_form(this)" method="POST" id="form-ingreso">
    <div class="brand-login">
    <?php  echo $fmt->brand->brand_login();?>
    </div>
    <div class="control-group" id="mensaje-login"></div> <!--    Mensaje login ajax  -->
    <div class="control-group box-text">
      <label class="title" lang="es"> <h3>¿Olvidaste tu contraseña?</h3></label>
      <p lang="es">No te preocupes. Solo tienes que introducir tu dirección de e-mail a continuación y te enviaremos algunas instrucciones.</p>
    </div>
    <div class="control-group">
      <div class="input-group email controls box-md-12">
        <span class="input-group-addon"><i class="icn-email"></i></span>
        <input class="form-control input-lg color-border-gris-a color-text-gris" onClick="seleccionar(this);" onBlur="deseleccionarBuscar(this);" id="inputEmail" name="inputEmail" placeholder="Email" type="text">
      </div>
    </div>
    <div class="control-group">
    	<div class="mensaje-login"></div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-info btn-lg btn-intro hvr-fade" id="btn-ingresar">Restrablecer contraseña</button>
    </div>

  </form>
  <div class="box-signup">
    ¿No tienes una cuenta? <a href="<?php echo _RUTA_WEB.$link_crear_cuenta; ?>">Crear una cuenta</a>
  </div>

</div>
<?php
  //echo "cat:".$cat."pla:".$pla."usu_id:".$sesion->get_variable('usu_id');
  //echo "sec:".$sesion->existe_variable("usu_id");
?>
<script type="text/javascript" >
	function action_form(){
		//alert("entre a acción");
		var ie = $("#inputEmail").val( );
		var ruta = "ajax-forgot";
		$.ajax({
			url:"<?php echo _RUTA_WEB; ?>ajax.php",
			type:"post",
			data:{ ajax:ruta, inputEmail:ie },
			success: function(msg){
					console.log(msg);

	        if(msg=="ok"){
	          $("#mensaje-login").html("<?php echo $fmt->mensaje->alert_success(array('texto'=>'Se envio un e-mail a tu cuenta para restablecer tu cuenta.','id'=>'ok-lg')); ?>");
	            setTimeout(function() {
                window.location.href = "<?php echo _RUTA_WEB; ?>";
              }, 7000 );
	        }

          if(msg=="error-mail"){
            $("#mensaje-login").html("<?php 
              echo $fmt->errores->error(array('texto'=>'Ingresa un E-mail valido.','id'=>'error_mail')); 
            ?>");
            toggleIdCerrar("error_mail", 8000);  // core.js
          }

	        if (msg=="error-no-registro") {
	          $("#mensaje-login").html("<?php echo $fmt->errores->error_mail_no_registrado(); ?>");
	          toggleIdCerrar("error_login", 6500); // core.js
	        }
			//$("#mensaje-login").html(msg);
		  }
		});
		elemento = document.getElementById("btn-ingresar");
 		elemento.blur();
		return false;
	}
</script>
