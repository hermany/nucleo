<?php
$fmt = new CONSTRUCTOR();
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
    <div class="form-actions">
      <button type="submit" class="btn btn-info btn-lg btn-intro hvr-fade" id="btn-ingresar">Restrablecer contraseña</button>
    </div>

  </form>
  <div class="box-signup">
    ¿No tienes una cuenta? <a href="<?php echo _RUTA_WEB; ?>signup">Crear una cuenta</a>
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
		var ip = $("#password").val( );
		var ruta = "ajax-login";
		$.ajax({
			url:"<?php echo _RUTA_WEB; ?>ajax.php",
			type:"post",
			data:{ ajax:ruta, inputEmail:ie , password:ip },
			success: function(msg){
        //alert(msg);

if ((msg!="false")&&(msg!="sin-rol")&&(msg!="rol-desactivado")) {
	          $("#mensaje-login").html("<?php echo $fmt->mensaje->login_ok(); ?>");
	          redireccionar_tiempo(msg,800); // core.js
	        }

	        if(msg=="sin-rol"){
	          $("#mensaje-login").html("<?php echo $fmt->error->error_rol(); ?>");
	          toggleIdCerrar("error_login", 8000);  // core.js
	        }

	        if(msg=="rol-desactivado"){
	          $("#mensaje-login").html("<?php echo $fmt->error->error_rol_desactivado(); ?>");
	          toggleIdCerrar("error_login", 6000);  // core.js
	        }

	        if (msg=="false") {
	          $("#mensaje-login").html("<?php echo $fmt->error->error_rol(); ?>");
	          toggleIdCerrar("error_login", 6000); // core.js
	        }
			//$("#mensaje-login").html(msg);
		  }
		});
		elemento = document.getElementById("btn-ingresar");
 		elemento.blur();
		return false;
	}
</script>
