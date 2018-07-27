<?php
header("Content-Type: text/html;charset=utf-8");

class EMPRESAS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;

	function EMPRESAS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function busqueda(){
		$clase_activa = "empresas";
		if ($this->id_estado!=''){
			$clase_activa = $this->id_estado;
		}
		$this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
    ?>
    <div class="body-modulo container-fluid">
			<div class="container">
				<?php 
				$botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
				$this->fmt->class_pagina->head_modulo_inner("Lista de Empresas", $botones); // bd, id modulo, botones
        $this->fmt->form->head_table("table_id");
        $this->fmt->form->thead_table('Id:Nombre Empresa:Teléfono:Email:Tipo:Categoria:Estado:Acciones');
        $this->fmt->form->tbody_table_open();

        $consulta = "SELECT emp_id, emp_nombre, emp_logo, emp_telefono, emp_email, emp_activar FROM empresa WHERE emp_activar=1";
        $rs =$this->fmt->query->consulta($consulta);
        $num=$this->fmt->query->num_registros($rs);
        if($num>0){
          for($i=0;$i<$num;$i++){
            $row=$this->fmt->query->obt_fila($rs);
            $fila_id=$row["emp_id"];
            $nombre=$row["emp_nombre"];
            $telefono=$row["emp_telefono"];
            $email=$row["emp_email"];
            $activar=$row["emp_activar"];
            // $tip=$row["emp_tip_tip_id"];
            // switch ($tip) {
            // 	case '1':
            // 		$tipo="socio";
            // 		break;       	
            // 	case '2':
            // 		$tipo="Afiliado";
            // 		break;
            	
            // 	default:
            // 		$tipo="desconocido";
            // 		break;
            // }

            echo "<tr class='row row-".$fila_id."'>";
            echo '  <td class="col-id">'.$fila_id.'</td>';
            echo '  <td class="col-name"><strong>'.$nombre.'</strong>';
            echo '  <td class="">'.$telefono.'</td>';
            echo '  <td class="">'.$email.'</td>';
            echo '  <td class="">';
            $this->traer_relacion($fila_id);
            echo '</td>';
            echo '  <td class="">';
            $this->traer_rel_cat_nombres($fila_id);
            echo '  </td>';
            echo '  <td class="">';
             $this->fmt->class_modulo->estado_publicacion($activar,$this->id_mod,"",$fila_id);
            echo '  </td>';
            echo '  <td class="">';
            echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
            echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-fila-eliminar",$this->id_mod,"eliminar,".$fila_id,"",$nombre);
            echo '  </td>';
            echo "</tr>";
          }
        }

        $this->fmt->form->tbody_table_close();
        $this->fmt->form->footer_table();
				?>
			</div>
		</div>
		<?php
		$this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
  }

  public function traer_rel_cat_nombres($fila_id){
    $consulta = "SELECT DISTINCT cat_id, cat_nombre FROM categoria, empresa_categorias WHERE emp_cat_emp_id='".$fila_id."' and cat_id = emp_cat_cat_id";
    $rs = $this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
        for ($i=0;$i<$num;$i++){
            $row=$this->fmt->query->obt_fila($rs);
            echo "- <a class='btn-menu-ajax' id_mod='".$this->id_mod."' vars='ordenar,".$row["cat_id"]."' > ".$row["cat_nombre"]."</a><br/>";
        }
    }
  }

  function traer_relacion($id){
    $relacion="";
    $div="";
      $sql="SELECT tip_emp_id,tip_emp_nombre FROM tipo_empresa, empresa_tipos where tip_emp_id=emp_tip_tip_id and emp_tip_emp_id=$id ORDER BY tip_emp_id asc";
            $rs =$this->fmt->query->consulta($sql);
            $num=$this->fmt->query->num_registros($rs);
            if($num>0){
                for($i=0;$i<$num;$i++){
                    $row=$this->fmt->query->obt_fila($rs);
                    $fila_id = $row["tip_emp_id"];
                    $fila_nombre = $row["tip_emp_nombre"];
                    echo "- <a class='btn-menu-ajax' id_mod='".$this->id_mod."' vars='ordenar_tipo,".$row["tip_emp_id"]."' > ".$row["tip_emp_nombre"]."</a><br/>"; 
                }
            }
    // return $relacion;
  }

  public function ordenar(){
    $id_cat = $this->id_item;
    $this->fmt->class_pagina->crear_head_form("Ordenar: ".$this->fmt->categoria->nombre_categoria($id_cat),"","");
    $id_form="form-editar";
    ?>
    <div class="body-modulo">
      <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
            <ul id="orden-cat" class="list-group">
                <?php
                $sql="SELECT emp_id, emp_nombre, emp_cat_orden FROM empresa, empresa_categorias where emp_cat_emp_id=emp_id and emp_cat_cat_id='$id_cat' ORDER BY emp_cat_orden asc";

        $rs =$this->fmt->query->consulta($sql,__METHOD__);
        $num=$this->fmt->query->num_registros($rs);
        if($num>0){
            for($i=0;$i<$num;$i++){
              $row=$this->fmt->query->obt_fila($rs);
                        $row_id=$row["emp_id"];
                        $row_nom=$row["emp_nombre"];
                        echo "<li id_var='$row_id'><i class='icn icn-reorder'></i><span class='nombre'>$row_nom</span></li>";
                    }
                }
                ?>
            </ul>
            <div class="form-group form-botones box-botones-form">
                <div class="group">
                    <?php
                    echo $this->fmt->class_pagina->crear_btn_m("Actualizar","icn-sync","update","btn btn-info btn-update",$this->id_mod,"ordenar_update");
                     ?>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#orden-cat" ).sortable();
                $(".btn-update").click(function(){
                    var formdata = new FormData();
                    var id_mod = $(this).attr("id_mod");
                    var vars = $(this).attr("vars");
                    formdata.append("inputVars", vars);
                    formdata.append("cat", "<?php echo $id_cat;?>");
                    formdata.append("ajax", "ajax-adm");
                    formdata.append("inputIdMod", id_mod);
                    $('#orden-cat li').each(function(index){
                      var id_var = $(this).attr("id_var");
                      console.log(id_var);
                      var orden = index+1;
                      formdata.append("id_item[]", id_var);
                      //formdata.append("orden[]", orden);
                    });

                    var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";

                    $.ajax({
                        url:ruta,
                        type:"post",
                        data:formdata,
                        processData: false,
                        contentType: false,
                        success: function(msg){

                        $("#popup-div").html(msg);
                            document.location.href="<?php echo $this->ruta_modulo; ?>";
                        }
                    });
                });
            });
        </script>
    </div>
    <?php
  }

  function ordenar_update(){
    $id_cat=$_POST["cat"];
    $this->fmt->class_modulo->eliminar_fila($id_cat,"empresa_categorias","emp_cat_cat_id");
    $ingresar2 ="emp_cat_emp_id,emp_cat_cat_id,emp_cat_orden";
    $valor_doc= $_POST['id_item'];
    $num=count( $valor_doc );
    for ($i=0; $i<$num;$i++){
        $valores2 = "'".$valor_doc[$i]."','".$id_cat."','".$i."'";
        $sql2="insert into empresa_categorias (".$ingresar2.") values (".$valores2.")";
        $this->fmt->query->consulta($sql2);
    }
    $this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
  }

  public function ordenar_tipo(){
    $id_cat = $this->id_item;
    $this->fmt->class_pagina->crear_head_form("Ordenar Tipos ","","");
    $id_form="form-editar";
    ?>
    <div class="body-modulo">
      <form class="form form-modulo form-ordenar"  method="POST" id="<?php echo $id_form?>">
            <ul id="orden-cat" class="list-group">
                <?php
                $sql="SELECT emp_id, emp_nombre FROM empresa, empresa_tipos where emp_tip_emp_id=emp_id and emp_tip_tip_id='$id_cat' ORDER BY emp_tip_orden asc";

        $rs =$this->fmt->query->consulta($sql,__METHOD__);
        $num=$this->fmt->query->num_registros($rs);
        if($num>0){
            for($i=0;$i<$num;$i++){
              $row=$this->fmt->query->obt_fila($rs);
                        $row_id=$row["emp_id"];
                        $row_nom=$row["emp_nombre"];
                        echo "<li id_var='$row_id'><i class='icn icn-reorder'></i><span class='nombre'>$row_nom</span></li>";
                    }
                }
                ?>
            </ul>
            <div class="form-group form-botones box-botones-form">
                <div class="group">
                    <?php
                    echo $this->fmt->class_pagina->crear_btn_m("Actualizar","icn-sync","update","btn btn-info btn-update",$this->id_mod,"ordenar_update_tipo");
                     ?>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#orden-cat" ).sortable();
                $(".btn-update").click(function(){
                    var formdata = new FormData();
                    var id_mod = $(this).attr("id_mod");
                    var vars = $(this).attr("vars");
                    formdata.append("inputVars", vars);
                    formdata.append("cat", "<?php echo $id_cat;?>");
                    formdata.append("ajax", "ajax-adm");
                    formdata.append("inputIdMod", id_mod);
                    $('#orden-cat li').each(function(index){
                      var id_var = $(this).attr("id_var");
                      console.log(id_var);
                      var orden = index+1;
                      formdata.append("id_item[]", id_var);
                      //formdata.append("orden[]", orden);
                    });

                    var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";

                    $.ajax({
                        url:ruta,
                        type:"post",
                        data:formdata,
                        processData: false,
                        contentType: false,
                        success: function(msg){

                        $("#popup-div").html(msg);
                            document.location.href="<?php echo $this->ruta_modulo; ?>";
                        }
                    });
                });
            });
        </script>
    </div>
    <?php  
  }

  function ordenar_update_tipo(){
    $id_cat=$_POST["cat"];
    $this->fmt->class_modulo->eliminar_fila($id_cat,"empresa_tipos","emp_tip_tip_id");
    $ingresar2 ="emp_tip_emp_id,emp_tip_tip_id,emp_tip_orden";
    $valor_doc= $_POST['id_item'];
    $num=count( $valor_doc );
    for ($i=0; $i<$num;$i++){
        $valores2 = "'".$valor_doc[$i]."','".$id_cat."','".$i."'";
        $sql2="insert into empresa_tipos (".$ingresar2.") values (".$valores2.")";
        $this->fmt->query->consulta($sql2);
    }
    $this->fmt->class_modulo->redireccionar($this->ruta_modulo,"1");
  }

  public function datos_empresa($id){
    $consulta = "SELECT * FROM empresa WHERE emp_id='$id'";
    $rs =$this->fmt->query->consulta($consulta);
      
    $row=$this->fmt->query->obt_fila($rs);
    $datos["emp_id"] = $row["emp_id"];  
    $datos["emp_nombre"]= $row["emp_nombre"];      
    $datos["emp_descripcion"] = $row["emp_descripcion"];         
    $datos["emp_ruta_amigable"] = $row["emp_ruta_amigable"];  
    $datos["emp_logo"] = $row["emp_logo"];        
    $datos["emp_icon"] = $row["emp_icon"];        
    $datos["emp_razon_social"] = $row["emp_razon_social"];        
    $datos["emp_banner"] = $row["emp_banner"];        
    $datos["emp_nit"] = $row["emp_nit"];         
    $datos["emp_direccion"] = $row["emp_direccion"];       
    $datos["emp_coordenadas"] = $row["emp_coordenadas"];         
    $datos["emp_rubro"] = $row["emp_rubro"];       
    $datos["emp_telefono"] = $row["emp_telefono"];        
    $datos["emp_email"] = $row["emp_email"];       
    $datos["emp_web"] = $row["emp_web"];         
    $datos["emp_pais"] = $row["emp_pais"]; 
    $datos["emp_ciudad"] = $row["emp_ciudad"]; 
    $datos["emp_nombre_contacto"] = $row["emp_nombre_contacto"];  
    $datos["emp_telefono_contacto"] = $row["emp_telefono_contacto"];
    $datos["emp_email_contacto"] = $row["emp_email_contacto"];      
    $datos["emp_activar"] = $row["emp_activar"];

    return $datos;
 
    $this->fmt->query->liberar_consulta();
      
  }

  

  function form_nuevo(){
    //$botones .= $this->fmt->class_pagina->crear_btn("empresas.adm.php?tarea=busqueda&p=empresas&id_mod=".$this->id_mod,"btn btn-link  btn-volver","icn-chevron-left","volver"); // link, clase, icono, nombre
    $id_form="form-nuevo";
    
    $this->fmt->class_pagina->crear_head_mod( "", "Nueva Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form-nuevo">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','','','requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->hidden_modulo($this->id_mod,"ingresar_empresa");
        $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','','3','','');
        $this->fmt->form->input_form('Telefono piloto:','inputTelfPiloto','','','','','');

        $this->fmt->form->imagen_unica_form("inputLogo","","","form-row","Logotipo:");

        $this->fmt->form->imagen_unica_form("inputBanner","","","form-row","Banner:");

        $this->fmt->form->input_form('Razón social:','inputRazonSocial','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Nombre representante:','inputNombreRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail representante:','inputEmailRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Telefono representante:','inputTelefonoRpr','','','','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje

	    $sql="SELECT * from tipo_empresa where tip_emp_activar='1' order by tip_emp_id asc";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);

		if($num>0){
			for($i=0;$i<$num;$i++){
                $row=$this->fmt->query->obt_fila($rs);
                $fila_id=$row["tip_emp_id"];
                $fila_nombre=$row["tip_emp_nombre"];

				$label[$i]=$fila_nombre;
				$valor[$i]=$fila_id;
			}
		}

	    $this->fmt->form->check_form("Tipo:","InputTipo",$valor,$label);
	    $this->fmt->form->categoria_form('Categoria','inputCat',"0","","","");
        $this->fmt->form->botones_nuevo($id_form,$this->id_mod,"","ingresar");
        ?>
      </form>
    </div>
    <?php
        $this->fmt->finder->finder_window();
        $this->fmt->class_modulo->modal_script($this->id_mod);
  }

  


  function form_editar(){
    $id_form="form-editar";
    $this->fmt->class_pagina->crear_head_mod( "", "Editar Empresa",'',$botones,'col-xs-6 col-xs-offset-4');
		$this->fmt->get->validar_get ( $_GET['id'] );
		$id = $this->id_item;
		$sql="SELECT emp_id,emp_nombre,emp_descripcion,emp_telefono,emp_logo,emp_razon_social,emp_nit,emp_direccion,emp_coordenadas,emp_rubro,emp_email,emp_web,emp_banner, emp_activar, emp_nombre_contacto, emp_telefono_contacto, emp_email_contacto from empresa	where emp_id='".$id."'";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);
			if($num>0){
				for($i=0;$i<$num;$i++){
					$row=$this->fmt->query->obt_fila($rs);
                    $fila_id_emp=$row["emp_id"];
                    $fila_nombre=$row["emp_nombre"];
                    $fila_descripcion=$row["emp_descripcion"];
                    $fila_telf=$row["emp_telefono"];
                    $fila_logo=$row["emp_logo"];
                    $fila_banner=$row["emp_banner"];
                    $fila_razon_social=$row["emp_razon_social"];
                    $fila_nit=$row["emp_nit"];
                    $fila_direccion=$row["emp_direccion"];
                    $fila_coordenadas=$row["emp_coordenadas"];
                    $fila_rubro=$row["emp_rubro"];
                    $fila_email=$row["emp_email"];
                    $fila_web=$row["emp_web"];
                    $fila_activar=$row["emp_activar"];
                    $fila_nomb_cont=$row["emp_nombre_contacto"];
                    $fila_tel_cont=$row["emp_telefono_contacto"];
                    $fila_mail_cont=$row["emp_email_contacto"];
				}
			}
    ?>
    <div class="body-modulo col-xs-6 col-xs-offset-3">
      <form class="form form-modulo" enctype="multipart/form-data" method="POST" id="form-editar">
        <div class="form-group" id="mensaje-form"></div> <!--Mensaje form -->
        <?php
        $this->fmt->form->input_form('Nombre de la empresa:','inputNombre','',$fila_nombre,'requerido requerido-texto input-lg','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->hidden_modulo($this->id_mod,"modificar_empresa");
		
        $this->fmt->form->input_hidden_form("inputId",$fila_id_emp);
		$this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$fila_descripcion,'','','3','','');
		$this->fmt->form->input_form('Telefono piloto:','inputTelfPiloto','',$fila_telf,'','','');

        $this->fmt->form->imagen_unica_form("inputLogo",$fila_logo,"","form-row","Logotipo:");
        $this->fmt->form->imagen_unica_form("inputBanner",$fila_banner,"","form-row","Banner:");

        $this->fmt->form->input_form('Razón social:','inputRazonSocial','',$fila_razon_social,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('NIT:','inputNit','',$fila_nit,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Dirección:','inputDireccion','',$fila_direccion,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Coordenadas:','inputCoordenadas','',$fila_coordenadas,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Rubro:','inputRubro','',$fila_rubro,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail:','inputEmail','',$fila_email,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Web:','inputWeb','',$fila_web,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$this->fmt->form->input_form('Nombre representante:','inputNombreRpr','',$fila_nomb_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('E-mail representante:','inputEmailRpr','',$fila_mail_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
        $this->fmt->form->input_form('Telefono representante:','inputTelefonoRpr','',$fila_tel_cont,'','',''); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje
		$tipo_id = $this->fmt->categoria->traer_rel_cat_id($id,'empresa_tipos','emp_tip_tip_id','emp_tip_emp_id');




		$sql="SELECT * from tipo_empresa where tip_emp_activar='1' order by tip_emp_id asc";
		$rs=$this->fmt->query->consulta($sql);
		$num=$this->fmt->query->num_registros($rs);

		if($num>0){
			for($i=0;$i<$num;$i++){
				$row=$this->fmt->query->obt_fila($rs);
                $fila_id=$row["tip_emp_id"];
                $fila_nombre=$row["tip_emp_nombre"];

				$label[$i]=$fila_nombre;
				$valor[$i]=$fila_id;
			}
		}

        // var_dump($tipo_id);
        // var_dump($valor);

	    $this->fmt->form->check_form("Tipo:","InputTipo",$valor,$label,$tipo_id); //$label,$id,$valor,$campo,$check
	    $cats_id = $this->fmt->categoria->traer_rel_cat_id($id,'empresa_categorias','emp_cat_cat_id','emp_cat_emp_id');
	    $this->fmt->form->categoria_form('Categoria','inputCat',"0",$cats_id,"","");
		$this->fmt->form->radio_activar_form($fila_activar);
		$this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
        ?>
      </div>
    </div>
    <?php
    $this->fmt->finder->finder_window();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }


  function ingresar(){
    if ($_POST["estado-mod"]=="activar"){
			$activar=1;
		}else{
			$activar=0;
		}
    $ingresar ="emp_nombre,
                emp_descripcion,
                emp_telefono,
                emp_logo,
                emp_banner,
                emp_razon_social,
                emp_nit,
                emp_direccion,
                emp_coordenadas,
                emp_rubro,
                emp_email,
                emp_web,
                emp_nombre_contacto,
                emp_telefono_contacto,
                emp_email_contacto,
                emp_activar";
    $valores_post  ="inputNombre,
									inputDescripcion,
									inputTelfPiloto,
                                    inputLogo,
									inputBanner,
									inputRazonSocial,
									inputNit,
									inputDireccion,
									inputCoordenadas,
									inputRubro,
									inputEmail,
									inputWeb,
									inputNombreRpr,
									inputTelefonoRpr,
									inputEmailRpr,
									inputActivar=".$activar;

    $this->fmt->class_modulo->ingresar_tabla('empresa',$ingresar,$valores_post);
		//$from,$filas,$valores_post
	$sql="select max(emp_id) as id from empresa";
	$rs= $this->fmt->query->consulta($sql);
	$fila = $this->fmt->query->obt_fila($rs);
	$id = $fila ["id"];

	$valor_cat=$_POST["InputTipo"];
	$num = count($valor_cat);
	$ingresar1 ="emp_tip_emp_id, emp_tip_tip_id";
	for($i=0;$i<$num;$i++){
		$valores1 = "'".$id."','".$valor_cat[$i]."'";
		$sql1="insert into empresa_tipos (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1);
	}

	$valor_cat=$_POST["inputCat"];
	$num = count($valor_cat);
	$ingresar1 ="emp_cat_emp_id, emp_cat_cat_id";
	for($i=0;$i<$num;$i++){
		$valores1 = "'".$id."','".$valor_cat[$i]."'";
		$sql1="insert into empresa_categorias (".$ingresar1.") values (".$valores1.")";
		$this->fmt->query->consulta($sql1);
	}

    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  

	function modificar(){
       

		$filas='emp_id,
			  emp_nombre,
              emp_descripcion,
              emp_telefono,
              emp_logo,
              emp_banner,
              emp_razon_social,
              emp_nit,
              emp_direccion,
              emp_coordenadas,
              emp_rubro,
              emp_email,
              emp_web,
              emp_nombre_contacto,
			  emp_telefono_contacto,
			  emp_email_contacto,
              emp_activar';
			$valores_post='inputId,inputNombre,
										inputDescripcion,
										inputTelfPiloto,
                                        inputLogo,
										inputBanner,
										inputRazonSocial,
										inputNit,
										inputDireccion,
										inputCoordenadas,
										inputRubro,
										inputEmail,
										inputWeb,
										inputNombreRpr,
										inputTelefonoRpr,
										inputEmailRpr,
										inputActivar';

			$this->fmt->class_modulo->actualizar_tabla('empresa',$filas,$valores_post); //$from,$filas,$valores_post
			$id=$_POST["inputId"];
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"empresa_tipos","emp_tip_emp_id");
			$this->fmt->class_modulo->eliminar_fila($_POST['inputId'],"empresa_categorias","emp_cat_emp_id");
			
            $valor_cat=$_POST["InputTipo"];
            $num = count($valor_cat);
			$ingresar1 ="emp_tip_emp_id, emp_tip_tip_id";
			for($i=0;$i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into empresa_tipos (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

			$valor_cat=$_POST["inputCat"];
			$num = count($valor_cat);
			$ingresar1 ="emp_cat_emp_id,emp_cat_cat_id";
			for($i=0;$i<$num;$i++){
				$valores1 = "'".$id."','".$valor_cat[$i]."'";
				$sql1="insert into empresa_categorias (".$ingresar1.") values (".$valores1.")";
				$this->fmt->query->consulta($sql1);
			}

		    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");

	}
}
?>
