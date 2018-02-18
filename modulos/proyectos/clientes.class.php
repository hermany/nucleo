<?php
header("Content-Type: text/html;charset=utf-8");

class CLIENTES_PROY{

  var $fmt;
  var $id_mod;
  var $id_item;
  var $id_estado;
  var $ruta_modulo;

  function CLIENTES_PROY($fmt,$id_mod=0,$id_item=0,$id_estado=0){
    $this->fmt = $fmt;
    $this->id_mod = $id_mod;
    $this->id_item = $id_item;
    $this->id_estado = $id_estado;
    $this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
  }

  function busqueda(){
    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
    $this->fmt->class_pagina->head_mod();
    
    $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo Cliente","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Clientes de proyectos y operaciones", $botones); // bd, id modulo, botones
    echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/m-clientes-proyectos.css?reload" rel="stylesheet" type="text/css">';
    
    echo "<div class='body-modulo-inner'>";
    $consulta = "SELECT  mod_cli_proy_id,mod_cli_proy_nombre,mod_cli_proy_codigo,mod_cli_proy_logo, mod_cli_proy_etiqueta FROM mod_cliente_proyectos ORDER BY mod_cli_proy_etiqueta,mod_cli_proy_id asc";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        // list($fila_id,$nombre,$codigo,$img,$etiqueta)=$this->fmt->query->obt_fila($rs);
        $row_id = $row["mod_cli_proy_id"];
        $row_nombre = $row["mod_cli_proy_nombre"];
        $row_logo = $row["mod_cli_proy_logo"];
        $row_codigo = $row["mod_cli_proy_codigo"];
        $row_etiqueta = $row["mod_cli_proy_etiqueta"];


        if (empty($row_logo)){
          $logo = "";
          $aux_codigo="<h2>".$row_codigo."</h2>";
          $aux_nombre= $row_nombre;
        }else{
          $logo = _RUTA_IMAGES.$row_logo;
          $aux_codigo="";
          $aux_nombre = $row_codigo." - ".$row_nombre;
        }
        ?>
        <div class="box-cliente box-cliente-<?php echo $row_id; ?> row-<?php echo $row_id; ?>" >
          <div class="logo btn-cliente" style="background:url(<?php echo $logo; ?>)no-repeat center center" idCliente="<?php echo $row_id; ?>" ><?php echo $aux_codigo; ?></div>
          <div class="nombre btn-cliente" idCliente="<?php echo $row_id; ?>" ><?php echo $aux_nombre; ?></div>
          <icon class="etiqueta btn-etiqueta etiqueta-<?php echo $row_etiqueta; ?>" valor="<?php echo $row_etiqueta; ?>" id="etiqueta-cliente-<?php echo $row_id; ?>" item="<?php echo $row_id; ?>" title="Cambiar etiqueta"></icon>
          <div class="bloque-etiquetas bloque-etiquetas-<?php echo $row_id; ?>"></div>
          <?php
          echo $this->fmt->class_pagina->crear_btn_m("Editar","icn-pencil","editar ".$row_id,"btn btn-full btn-accion btn-editar btn-menu-ajax  btn-small",$this->id_mod,"form_editar,".$row_id);
          echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$row_id,"btn btn-accion btn-full btn-small btn-m-eliminar",$this->id_mod,"eliminar,".$row_id,"",$row_nombre);
          ?>
        </div>
        <?php
      }
    }
    echo "</div>";
    echo "<div class='footer-modulo'>";
    ?> 
      <script type="text/javascript">
        $(document).ready(function() {
          $(".btn-etiqueta").click(function(event) {
            /* Act on the event */
            var id = $(this).attr("item");

            $(".bloque-etiquetas").removeClass('on');
            $(".box-cliente").removeClass('nivel');

            $(".bloque-etiquetas-"+id).addClass('on');
            $(".box-cliente-"+id).addClass('nivel');
            
            var botones="";
            for (var i = 1; i < 6; i++) {
              botones =  botones+ "<a class='boton btn-etiqueta-"+i+" etiqueta-"+i+"' item='"+id+"' valor='"+i+"'></a>";
            }
            $(".bloque-etiquetas-"+id).html("<div class='inner'><i class='icn icn-arrow-o-up'></i><div class='botones'>"+botones+" </div><a class='boton btn-cerrar'><i class='icn icn-close'></i></a><div class='texto'></div> </div>");
            $(".bloque-etiquetas-"+id+" .btn-cerrar").click(function(event) {
                $(".bloque-etiquetas").removeClass('on');
                $(".box-cliente").removeClass('nivel');
            });

            $(".boton").click(function(event) {
              /* Act on the event */
               var valor = parseInt($(this).attr("valor"));
               var item = parseInt($(this).attr("item"));
               var ruta_ajax="ajax-etiqueta-cliente-proyectos";
               var variables = valor+","+item;
               var datos = {ajax:ruta_ajax, inputIdMod:<?php echo $this->id_mod; ?> , inputVars : variables };

               //console.log(datos);

               var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
                $.ajax({ 
                  url:ruta,
                  type:"post",  
                  async: true,   
                  data:datos,       
                  success:function(msg){  
                    // $(".bloque-etiquetas").removeClass('on');
                    // $(".box-cliente").removeClass('nivel');
                    // //console.log(msg);
                    // var vars = msg.split(":");
                    // $("#etiqueta-cliente-"+vars[0]).attr("class","");
                    // $("#etiqueta-cliente-"+vars[0]).addClass('etiqueta btn-etiqueta animated flash etiqueta-'+vars[1]);
                    // $("#etiqueta-cliente-"+vars[0]).attr("valor",vars[1]);
                    redireccionar_tiempo("<?php echo $this->ruta_modulo; ?>",1);
                  }
                });
             });

            
            $(".boton").hover(function() {
              /* Stuff to do when the mouse enters the element */
              var valor = parseInt($(this).attr("valor"));
              // console.log("btn:"+parseInt(valor));
              
              $texto = ["","Prioridad Alta","Prioridad Media","Prioridad Normal","Prioridad Baja","Cliente sin Actividad"];

              $(".bloque-etiquetas-"+id+" .texto").html( $texto[valor] );
              // console.log("btnx:"+$texto[ valor ]);
            }, function() {
              /* Stuff to do when the mouse leaves the element */
              $(".bloque-etiquetas .texto").html("");
            });
          });
        });
      </script>
    <?php
    $this->fmt->class_pagina->footer_mod();
    $this->fmt->class_modulo->script_accion_modulo();
    
  }

  function form_nuevo(){
    $this->fmt->class_pagina->crear_head_form("Nuevo Cliente","","");
    $this->fmt->class_pagina->head_form_mod();
    $id_form="form_nuevo";
    $this->fmt->class_pagina->form_ini_mod($id_form,"form-nuevo-clientos-proyectos");

    $this->fmt->form->input_form("* Nombre:","inputNombre","","","input-lg","","","","literal"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
    $this->fmt->form->input_form("Codigo:","inputCodigo","","");
    $this->fmt->form->textarea_form('Descripción:','inputDescripcion','','','','3','');
    $this->fmt->form->imagen_unica_form("inputLogo","","","form-normal","Logo:");
    $this->fmt->form->input_form("Dirección:","inputDireccion","","");
    $this->fmt->form->input_form("Ciudad:","inputCiudad","","");
    $this->fmt->form->input_form("Pais:","inputPais","","");
    $this->fmt->form->input_form("Teléfonos:","inputTelefono","","");
    // $this->fmt->form->input_form("Nit:","inputNit","","");
    //$this->fmt->form->agenda_form("inputAgenda",$this->id_mod,"","Interesados:","mod_agenda_cliente_proyectos","mod_agd_proy_","mod_agd_"); 

    $this->fmt->form->boton_guardar($id_form,$this->id_mod,"","ingresar");
    $this->fmt->class_pagina->form_fin_mod();
    $this->fmt->class_pagina->footer_form_mod();

    $this->fmt->finder->finder_window();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function form_editar(){
    $this->fmt->class_pagina->crear_head_form("Editar Cliente","","");
    $this->fmt->class_pagina->head_form_mod();
    $id_form="form_editar";

    $id = $this->id_item;
    $consulta= "SELECT * FROM mod_cliente_proyectos WHERE mod_cli_proy_id='".$id."'";
    $rs =$this->fmt->query->consulta($consulta);
    $row=$this->fmt->query->obt_fila($rs);

    $this->fmt->class_pagina->form_ini_mod($id_form,"form-editar-clientos-proyectos");

    $this->fmt->form->input_form("* Nombre:","inputNombre","",$row["mod_cli_proy_nombre"],"input-lg","","","","literal"); //$label,$id,$placeholder,$valor,$class,$class_div,$mensaje,$disabled,$validar,$otros
    $this->fmt->form->input_hidden_form("inputId",$id);

    $this->fmt->form->input_form("Codigo:","inputCodigo","",$row["mod_cli_proy_codigo"]);
    $this->fmt->form->textarea_form('Descripción:','inputDescripcion','',$row["mod_cli_proy_descripcion"],'','3','');
    $this->fmt->form->imagen_unica_form("inputLogo",$row["mod_cli_proy_logo"],"","form-normal","Logo:");
    $this->fmt->form->input_form("Dirección:","inputDireccion","",$row["mod_cli_proy_direccion"] );
    $this->fmt->form->input_form("Ciudad:","inputCiudad","",$row["mod_cli_proy_ciudad"]);
    $this->fmt->form->input_form("Pais:","inputPais","",$row["mod_cli_proy_pais"]);
    $this->fmt->form->input_form("Teléfonos:","inputTelefono","",$row["mod_cli_proy_telefono"]);
    // $this->fmt->form->input_form("Nit:","inputNit","","");
    //$this->fmt->form->agenda_form("inputAgenda",$this->id_mod,"","Interesados:","mod_agenda_cliente_proyectos","mod_agd_proy_","mod_agd_"); 

    $this->fmt->form->btn_actualizar($id_form,$this->id_mod,"modificar");
    $this->fmt->class_pagina->form_fin_mod();
    $this->fmt->class_pagina->footer_form_mod();

    $this->fmt->finder->finder_window();
    $this->fmt->class_modulo->modal_script($this->id_mod);
  }

  function ingresar(){
    $ingresar = "mod_cli_proy_nombre, mod_cli_proy_codigo, mod_cli_proy_descripcion, mod_cli_proy_logo, mod_cli_proy_direccion, mod_cli_proy_coordenadas, mod_cli_proy_ciudad, mod_cli_proy_pais, mod_cli_proy_telefono, mod_cli_proy_etiqueta";
    $valores  = "'".$_POST["inputNombre"]."','".
                    $_POST["inputCodigo"]."','".
                    $_POST["inputDescripcion"]."','".
                    $_POST["inputLogo"]."','".
                    $_POST["inputDireccion"]."','".
                    $_POST["inputCoordenadas"]."','".
                    $_POST["inputCiudad"]."','".
                    $_POST["inputPais"]."','".
                    $_POST["inputTelefono"]."','".
                    $_POST["inputEtiqueta"]."'";

   $sql="insert into mod_cliente_proyectos (".$ingresar.") values (".$valores.")";
   $this->fmt->query->consulta($sql,__METHOD__);
   $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

  function modificar(){
    $sql="UPDATE mod_cliente_proyectos SET
                mod_cli_proy_nombre='".$_POST['inputNombre']."',
                mod_cli_proy_codigo='".$_POST['inputCodigo']."', 
                mod_cli_proy_descripcion='".$_POST['inputDescripcion']."', 
                mod_cli_proy_logo='".$_POST['inputLogo']."', 
                mod_cli_proy_direccion='".$_POST['inputDireccion']."', 
                mod_cli_proy_ciudad='".$_POST['inputCiudad']."', 
                mod_cli_proy_pais='".$_POST['inputPais']."', 
                mod_cli_proy_telefono='".$_POST['inputTelefono']."', 
                mod_cli_proy_etiqueta='".$_POST['inputEtiqueta']."'
                WHERE mod_cli_proy_id ='".$_POST['inputId']."'";
    $this->fmt->query->consulta($sql);
    $this->fmt->class_modulo->redireccionar($ruta_modulo,"1");
  }

}
