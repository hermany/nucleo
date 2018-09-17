<?php
header('Content-Type: text/html; charset=utf-8');

class USUARIO{

  var $constructor;

  function __construct($fmt){
    $this->fmt = $fmt;
  }
  function nombre_grupo_usuario($id_grupo){
    $sql ="SELECT grupo_nombre FROM grupo WHERE grupo_id='$id_grupo'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query-> obt_fila($rs);
    return $fila["grupo_nombre"];
  }
  function nombre_rol($id_rol){
    $sql ="SELECT rol_nombre  FROM rol WHERE rol_id='$id_rol'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query-> obt_fila($rs);
    return $fila["rol_nombre"];
  }

  function id_rol_usuario($id_usu){
    $sql ="SELECT usu_rol_rol_id FROM usuario_roles WHERE usu_rol_usu_id='$id_usu'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_rol_rol_id"];
  }

  function traer_ruta_rol($id_rol){
    $sql ="SELECT sitio_ruta_amigable FROM sitio_roles,sitio WHERE sitio_rol_rol_id='$id_rol' and sitio_rol_sitio_id=sitio_id";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["sitio_ruta_amigable"];
  }

  function traer_id_usuario_id_fb($idrs){
    $sql ="SELECT usu_rs_usu_id FROM usuario_redes_sociales WHERE usu_rs_fb_id='$idrs'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_rs_usu_id"];
  }

  function siguiendo($usu,$usu_seguido){
    $sql ="SELECT * FROM usuario_seguidos WHERE usu_sgd_seguidor_usu_id='$usu' and usu_sgd_seguido_usu_id='$usu_seguido'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true ;
    }else{
      return false;
    }
  }

  function traer_num_seguidores($usu){
    $sql ="SELECT usu_sgd_seguidor_usu_id FROM usuario_seguidos WHERE usu_sgd_seguido_usu_id='$usu'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    return  $num;
  }  

  function traer_num_seguidos($usu){
    $sql ="SELECT usu_sgd_seguido_usu_id FROM usuario_seguidos WHERE usu_sgd_seguidor_usu_id='$usu'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    return  $num;
  }

  function traer_seguidos($usu){
    $sql ="SELECT usu_sgd_seguido_usu_id FROM usuario_seguidos WHERE usu_sgd_seguidor_usu_id='$usu'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    
    if($num>0){
      for ($i=0; $i < $num; $i++) { 
        $fila = $this->fmt->query->obt_fila($rs);
        $aux[$i] = $fila["usu_sgd_seguido_usu_id"];
      }
      return $aux;
    }else{
      return 0;
    }
  }

  function rol_usuario($id_usu){
    $sql ="SELECT usu_rol_rol_id FROM usuario_roles WHERE usu_rol_usu_id='$id_usu'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $id = $fila["usu_rol_rol_id"];
    if (isset($id)){
      $sql1 ="SELECT rol_nombre FROM rol WHERE rol_id='$id'";
      $rs1 = $this->fmt->query-> consulta($sql1,__METHOD__);
      $fila1 = $this->fmt->query->obt_fila($rs1);
      return $fila1["rol_nombre"];
    }else {
      return "sin rol";
    }
  }


  function cambiar_estado($id_usuario,$valor){
    $valor = (int)$valor;
    $sql="UPDATE usuario SET usu_estado='$valor' WHERE usu_id='$id_usuario'";
    $this->fmt->query-> consulta($sql,__METHOD__);
  }

  function nombre_usuario($usuario){
    $sql="select usu_nombre from usuario where usu_id='$usuario'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_nombre"];
  }  
  function email_usuario($usuario){
    $sql="select usu_email from usuario where usu_id='$usuario'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_email"];
  }  
  function password_usuario($usuario){
    $sql="select usu_password from usuario where usu_id='$usuario'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return base64_decode($fila["usu_password"]);
  }
  function  usuario_estado($usuario){
    $sql="select usu_estado from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_estado"];
  }  
  function  usuario_email($email){
    $sql="select usu_id from usuario where usu_email='$email'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_id"];
  }
  function  usuario_nivel($usuario){
    $sql="select usu_nivel from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_nivel"];
  }  

  function  numero_usuarios_rol($rol){
    $sql="SELECT usu_id FROM usuario,usuario_roles WHERE usu_rol_usu_id=usu_id and usu_rol_rol_id='$rol' and usu_activar=1";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $num = $this->fmt->query -> num_registros($rs);
    return $num;
  }

  function  usuario_amigos($usuario){
    $sql="select usu_amigos from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_amigos"];
    return $fila["usu_amigos"];
  }

  function nombre_apellidos($usuario,$apellidos="0"){
    $sql="select usu_nombre, usu_apellidos from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    if ($apellidos=="1"){
       $apx = explode(" ",$fila["usu_apellidos"]);
       $ap = $apx[0];
    }else{
      $ap = $fila["usu_apellidos"];
    }
    return $fila["usu_nombre"]." ".$ap;
  } 

  function siglas_nombre($nombre){
    $nombrex = explode(" ", $nombre);
    if (!empty($nombrex[1])){
      $ap = substr($nombrex[1], 0, 1);
    }else{
      $ap="";
    }
    return  substr($nombrex[0], 0, 1).$ap;
  } 

  function usuario_siglas($usuario){
    $sql="select usu_nombre, usu_apellidos from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return  substr($fila["usu_nombre"], 0, 1).substr($fila["usu_apellidos"], 0, 1);
  }  

  function acortar_nombre($nombre){
    $nom = explode(" ",$nombre);
    if (count($nom) > 2){
    //  //$anom= sanitize_words($obj->name);  
    //  $a_nom= str_word_count($nombre, 1);
      if (count($nom) > 3){
        $nomx= $nom[0]." ".$nom[1]." ".$nom[2];
      }else{
        $nomx= $nom[0]." ".$nom[1];
      }
    }else{
      $nomx = $nombre;
    }
    return $nomx;
  }

  function nombre_id_usuario($usuario_n,$usuario_a){
    $sql="select usu_id from usuario where usu_nombre='$usuario_n' and usu_apellidos='$usuario_a'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_id"];
  }

  function apellidos_usuario($usuario){
    $sql="select usu_apellidos from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_apellidos"];
  }

  function imagen_usuario($usuario){
    $sql="select usu_imagen from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    if (empty($fila["usu_imagen"])){
      $r = "images/user/user-default.png";
    }else{
      $r = $fila["usu_imagen"];
    }
    return $r;
  }   

  function img_usuario($usuario){
    $sql="select usu_imagen from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["usu_imagen"];
  }  
  function imagen_usuario_thumb($usuario){
    $sql="select usu_imagen from usuario where usu_id=$usuario";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $this->fmt->archivos->convertir_url_thumb($fila["usu_imagen"]);
  }
  function imagen_usuario_mini($usuario){
    if (!empty($usuario)){
      $sql="select usu_imagen from usuario where usu_id=$usuario";
      $rs = $this->fmt->query-> consulta($sql,__METHOD__);
      $fila = $this->fmt->query->obt_fila($rs);
      if (!empty($fila["usu_imagen"])){
        $im = _RUTA_HOST.$fila["usu_imagen"];
        if ($this->fmt->archivos->existe_archivo($im)){
          $r = _RUTA_IMAGES.$this->fmt->archivos->convertir_url_mini($fila["usu_imagen"]);
        }else{
          $r = _RUTA_WEB_NUCLEO."images/user/user-mini.png";
        }
      }else{
        $r = _RUTA_WEB_NUCLEO."images/user/user-mini.png";
      }
    }else{
      $r = _RUTA_WEB_NUCLEO."images/user/user-mini.png";
    }

    return $r;
  }

  public function datos_id($id){
    $consulta = "SELECT * FROM  usuario WHERE usu_id='$id'";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
      if($num>0){
        $row=$this->fmt->query->obt_fila($rs);
        return $row;
      }else{
        return 0;
      }
    $this->fmt->query->liberar_consulta();
  }  

  function opciones_roles_list(){
		$sql ="SELECT rol_id, rol_nombre FROM rol";
		$rs = $this->fmt->query -> consulta($sql,__METHOD__);
		$num = $this->fmt->query -> num_registros($rs);
		$aux="";
		if ($num > 0){
			for ( $i=1; $i <= $num; $i++){
				list($fila_id, $fila_nombre) = $this->fmt->query->obt_fila($rs);
				$aux .= '<div class="checkbox">';
				$aux .= '<label>';
				$aux .= '<input type="checkbox" name="inputRol[]" value="'.$fila_id.'">';
				$aux .= '<i class="'.$fila_icono.'"></i> '.$fila_nombre;
				$aux .= '</label>';
				$aux .= '</div>';
			}
		} else {
			$aux =" no existen roles registrados";
		}
		return $aux;
	}

  function opciones_roles($rol){
    $sql="select rol_id, rol_nombre from rol ORDER BY rol_id asc";
            $rs =$this->fmt->query->consulta($sql,__METHOD__);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
              for($i=0;$i<$num;$i++){
                list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
                $ch="";
        if (in_array($fila_id, $rol))
          $ch="checked";
    ?>
    <div class="checkbox">
          <label>
            <input name="inputRol" <?php echo $ch; ?> id="inputRol<?php echo $fila_id; ?>" type="radio" value="<?php echo $fila_id; ?>"> <?php echo $fila_nombre; ?>
          </label>
        </div>
    <?php
        }
      }
  }

	function opciones_grupos(){
		$sql ="SELECT rol_grupo_id, rol_grupo_nombre FROM rol_grupos";
		$rs = $this->fmt->query -> consulta($sql,__METHOD__);
		$num = $this->fmt->query -> num_registros($rs);
		$aux="";
		if ($num > 0){
			for ( $i=1; $i <= $num; $i++){
				list($fila_id, $fila_nombre) = $this->fmt->query->obt_fila($rs);
				$aux .= '<div class="checkbox">';
				$aux .= '<label>';
				$aux .= '<input type="checkbox" name="inputRolGrupo[]" value="'.$fila_id.'">';
				$aux .= '<i class="'.$fila_icono.'"></i> '.$fila_nombre;
				$aux .= '</label>';
				$aux .= '</div>';
			}
		} else {
			$aux =" no existen roles registrados";
		}
		return $aux;
	}

  function nombre_cat($id_rol){
    $sql="select cts_nombre from cats where cts_id_roles=$id_rol";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["cts_nombre"]." ".$fila["usu_apellidos"];
  }

  function id_cat_roles($id_rol){
    $sql="select cts_id from cats where cts_id_roles='$id_rol'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["cts_id"];
  }

  function nombre_cat_id($id_cat){
    $sql="select cts_nombre from cats where cts_id='$id_cat'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["cts_nombre"];
  }

  function permisos_roles_mod($id_rol,$id_mod){
    $sql="select rol_rel_mod_p_ver, rol_rel_mod_p_activar, rol_rel_mod_p_agregar, rol_rel_mod_p_editar, rol_rel_mod_p_eliminar from roles_rel where rol_rel_rol_id='$id_rol' and rol_rel_mod_id='$id_mod'";
    $rs = $this->fmt->query-> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $perm["ver"]=$fila["rol_rel_mod_p_ver"];
    $perm["activar"]=$fila["rol_rel_mod_p_activar"];
    $perm["agregar"]=$fila["rol_rel_mod_p_agregar"];
    $perm["editar"]=$fila["rol_rel_mod_p_editar"];
    $perm["eliminar"]=$fila["rol_rel_mod_p_eliminar"];
    return $perm;
  }

  function agregar_usuarios_input($id_input,$from,$seleccion="simple"){
    ?>
    <div class="box-agregar-usu" id="box-<?php echo $id_input; ?>">
      <a class="btn btn-add-user" ><i class="icn icn-user-plus"></i></a>
    </div>
    <script type="text/javascript">
      $(window).ready(function() {
        var h= $("#<?php echo $id_input; ?>").height();
        var hy = -38;
        var hx = 15;
        //console.log("top"+hx);
        $("#box-<?php echo $id_input; ?>").css("margin-right",hx );
        $("#box-<?php echo $id_input; ?>").css("margin-top",hy);
        $(".btn-add-user").click(function(event) {
          $(".modal-list").addClass('on');
          var form_list = new FormData();
    			form_list.append("insert", "<?php echo $id_input; ?>");
    			form_list.append("seleccion", "<?php echo $seleccion; ?>");
    			// form_list.append("vars", vars);
    			form_list.append("ajax", "ajax-list-usuarios");
    			var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
    			$.ajax({
    				url:ruta,
    				type:"post",
    				data:form_list,
    				processData: false,
    				contentType: false,
    				success: function(msg){
               $(".modal-list-inner").html(msg);
             }
          });
        });
      });
    </script>
    <?
  }

}
