<?php
?>
<div class="finder">
  <script type="text/javascript" src="<?php echo _RUTA_WEB_NUCLEO;?>js/jquery-ui.min.js"></script>
  <div class="finder-head">
      <div class="group-tabs">
        <div class="tab">
          <label class="title title-finder">FINDER</label>
          <span class="group">
            <a class="category active" id="tab-todos" idtab="todos">Todos</a>
            <?php
            if ($tipo_upload=="individual" || $tipo_upload=="insertar-editor-texto" || $tipo_upload=="individual-complejo" || $tipo_upload=="individual-multimedia" ){

              if($tipo_archivo=="imagenes"  || $tipo_archivo=="imagenes-videos"){
                ?>
                <a class="category" id="tab-imagenes" idtab="imagenes">Imagenes</a>
                <!-- <a class="category" id="tab-otros" idtab="otros">Otros</a> -->
                <?php
              }
              if($tipo_archivo=="documentos"){
                ?>
                <a class="category" id="tab-documentos" idtab="documentos">Documentos</a>
                <?php
              }
              if($tipo_archivo=="videos" || $tipo_archivo=="imagenes-videos" ){
                ?>
                <a class="category" id="tab-videos" idtab="videos">Videos</a>
                <?php
              }
              if($tipo_archivo=="audio"){
                ?>
                <a class="category" id="tab-audio" idtab="audio">Audio</a>
                <?php
              }
            }
            if ($tipo_upload=="multiple-accion") {
              ?>
                <a class="category" id="tab-imagenes" idtab="imagenes">Imagenes</a>
                <a class="category" id="tab-videos" idtab="videos">Videos</a>
                <a class="category" id="tab-audio" idtab="audio">Audio</a>
                <a class="category" id="tab-documentos" idtab="documentos">Documentos</a>
              <?php
            }
            ?>
          </span>
        </div>
      </div>
  </div>

  <div class="finder-body">
    <div class="tbody tab-content on" id="content-todos">
      <div class="head-todos">
        <form class="form"  method="post" id="formup">
          <div class="box-upload">
            <input type="file" class="inputArchivo btn" multiple name="inputArchivoFinder" id="inputArchivoFinder" >
            <a class="btn  btn-full btn-subir-imagen"><i class="icn icn-upload"></i>Subir Archivo</a>
          </div>
          <!--<a class="btn  btn-full"><i class="icn icn-link"></i>Añadir archivo desde URL</a>
           <a class="btn  btn-full"><i class="icn icn-picture-plus icn-picture"/>Crear Álbum de imagenes</a> -->
          <?php
          if($tipo_archivo=="imagenes-videos" || $tipo_upload=="multiple-accion"){
            ?>

            <div class="box-upload box-upload-embed">
              <a class="btn  btn-full btn-subir-embed" open="block-form-embed"><i class="icn icn-video-plus icn-picture"></i>Subir Embed</a>
              <div class="block-form" id="block-form-embed" style="display:none">
                <form class="form-embed" method="post" id="form-embed">
                  <?php
                    $this->fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","","","");
                    $this->fmt->form->input_form("Tags:","inputTags","","","","","");
                    $this->fmt->form->textarea_form("Embed:","inputEmbed","","","","","5");
                  ?>
                  <a class="btn btn-full pull-right btn-agregar-embed">Agregar</a>
                </form>
              </div>
            </div>
            <script type="text/javascript">
              $(document).ready(function() {
                $(".btn-subir-embed").click(function(){
                  $(".block-form").toggleClass('on');
                });
                $(".btn-agregar-embed").click(function(){
                  var formdata_e = new FormData();
                  var inputNombre = $("#inputNombre").val();
                  var inputTags = $("#inputTags").val();
                  var inputEmbed = $("#inputEmbed").val();
              		formdata_e.append("inputNombre", inputNombre);
              		formdata_e.append("inputTags", inputTags);
              		formdata_e.append("inputEmbed", inputEmbed);
                  formdata_e.append("ajax", "ajax-save-embed");
                  var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
              		$.ajax({
                  			url:ruta,
                  			type:"post",
                  			data:formdata_e,
                  			processData: false,
              				  contentType: false,
                  			success: function(msg){
                          //console.log(msg);
                          var res = msg.split(":");

                          $("#finder-figures").append("<li class='finder-item' id='item-s-"+res[0]+"' item='"+res[0]+"' url='' tipo_item='embed'  style='background:url(<?php echo _RUTA_WEB_NUCLEO; ?>images/embed-icon.png) no-repeat; background-size:cover'><span class='texto-embed'>"+res[1]+"</span></li>");
                          $(".block-form").removeClass('on');
                          $("#inputNombre").attr("value","");
                          $("#inputTags").attr("value","");
                          $("#inputEmbed").attr("value","");

                          $(".finder-item").click(function(){
                            var id = $(this).attr("id");
                            //console.log (id);
                            $(".finder-item").removeClass("on");
                            $(".finder-item").attr("seleccionado","");
                            $("#finder-figures #"+id).addClass("on");
                            $("#finder-figures #"+id).attr("seleccionado","on");
                            $("#content-imagenes #"+id).addClass("on");
                            $("#content-imagenes #"+id).attr("seleccionado","on");
                            $("#content-videos #"+id).addClass("on");
                            $("#content-videos #"+id).attr("seleccionado","on");
                          });

                        }
                  });
                });
              });
            </script>
            <?php
          }
          ?>
        </form>
      </div>
      <div class="body-todos tbody">
        <ul id="finder-figures">
          <?
          // echo "id:".$id; echo "<br/>";
          // echo "id_mod:".$id_mod; echo "<br/>";
          // echo "url:".$url; echo "<br/>";
          // echo "tipo_upload:".$tipo_upload; echo "<br/>";
          // echo "tipo_archivo:".$tipo_archivo; echo "<br/>";

          if ($tipo_upload=="individual" || $tipo_upload=="individual-multimedia"){
            $urlmini = $this->fmt->archivos->convertir_url_mini($url);
            if( !empty($url) ) {
            echo "<li class='finder-item on' item='' tipo_item='".$tipo."'  id='item-0' url_mini='".$urlmini."' url='".$url."' style='background:url("._RUTA_IMAGES.$url.") no-repeat; background-size: 100% auto;'></li>";
            }
          }
          ?>
        </ul>
      </div>
    </div>
    <div class="tbody tab-content" id="content-imagenes" >

      <div class="box-buscador">
        <i class="icn icn-search"></i>
        <input id="filtrar" type="text" class="form-control" placeholder="Buscar imagen">
      </div>
      <ul id="mul-all">
      <?php
      $consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia ORDER BY mul_id asc";
      $rs =$this->fmt->query->consulta($consulta);
      $num=$this->fmt->query->num_registros($rs);
      if($num>0){
        for($i=0;$i<$num;$i++){
          list($fila_id,$fila_nombre,$etiquetas,$fila_url,$fila_tipo,$fila_dominio,$fila_usuario,$fila_fecha,$fila_embed,$fila_activar)=$this->fmt->query->obt_fila($rs);
          $url = $this->fmt->archivos->convertir_url_mini($fila_url);
          // echo "<li class='finder-item' id='item-$i' url='".$fila_url."' >".$fila_nombre."</li>";
          if ( $fila_tipo=="jpeg" || $fila_tipo=="jpg" || $fila_tipo=="png" || $fila_tipo=="gif" ){
          echo "<li class='finder-item' item='".$fila_id."' tipo_item='".$fila_tipo."'  id='item-s-$i' url='".$fila_url."' url_mini='".$url."' style='background:url("._RUTA_WEB.$url.") no-repeat; background-size: 100% auto;' ><span class='nombre'>".$fila_nombre."</span><span class='etiquetas'>".$etiquetas."</span></li>";
          }
          // if   ( $fila_tipo=="mp4" ){
          //     echo "<li class='finder-item' id='item-s-$i' url='".$fila_url."' style='background:url("._RUTA_WEB_NUCLEO."/images/video-icon.png) no-repeat; background-size: 100% auto;' >$fila_nombre</li>";
          // }
        }
      }

      ?>

      </ul>
    </div>

    <div class="tbody tab-content" id="content-documentos"></div>

    <div class="tbody tab-content" id="content-videos">
      <div class="box-buscador">
        <i class="icn icn-search"></i>
        <input id="filtrar" type="text" class="form-control" placeholder="Buscar videos">
      </div>
      <ul id="mul-video-all">
      <?php
      $consulta = "SELECT mul_id,mul_nombre,mul_tags,mul_url_archivo,mul_tipo_archivo,mul_id_dominio,mul_usuario,mul_fecha, mul_embed,mul_activar FROM multimedia ORDER BY mul_id asc";
      $rs =$this->fmt->query->consulta($consulta);
      $num=$this->fmt->query->num_registros($rs);
      if($num>0){
        for($i=0;$i<$num;$i++){
          list($fila_id,$fila_nombre,$etiquetas,$fila_url,$fila_tipo,$fila_dominio,$fila_usuario,$fila_fecha,$fila_embed,$fila_activar)=$this->fmt->query->obt_fila($rs);
          //$url = $this->fmt->archivos->convertir_url_mini($fila_url);

          $url = "images/video-icon.png";
          // echo "<li class='finder-item' id='item-$i' url='".$fila_url."' >".$fila_nombre."</li>";
          $nom= $this->fmt->class_modulo->recortar_texto($fila_nombre,"8")."...";
          if ( $fila_tipo=="mp4" || $fila_tipo=="embed"){
            if ($fila_tipo=="embed"){
              $fila_url=$url;
              echo "<li class='finder-item item-video' embed='".$fila_embed."' item='".$fila_id."' tipo_item='".$fila_tipo."' id='item-s-$i' url='".$fila_url."'  url_mini='".$url."' style='background:url("._RUTA_WEB_NUCLEO.$url.") no-repeat; background-size: 100% auto;' ><span class='video-embed'>".$fila_embed."</span><span class='nombre'>".$nom."</span><span class='etiquetas'>".$etiquetas."</span></li>";
            }else{
              echo "<li class='finder-item item-video' item='".$fila_id."' tipo_item='".$fila_tipo."' id='item-s-$i' url='".$fila_url."' url_mini='".$url."' style='background:url("._RUTA_WEB_NUCLEO.$url.") no-repeat; background-size: 100% auto;' ><span class='nombre'>".$nom."</span><span class='etiquetas'>".$etiquetas."</span></li>";
            }

          }
          // if   ( $fila_tipo=="mp4" ){
          //     echo "<li class='finder-item' id='item-s-$i' url='".$fila_url."' style='background:url("._RUTA_WEB_NUCLEO."/images/video-icon.png) no-repeat; background-size: 100% auto;' >$fila_nombre</li>";
          // }
        }
      }

      ?>

      </ul>
    </div>

    <div class="tbody tab-content" id="content-audio"></div>

    <div class="tbody tab-content" id="content-otros"></div>
  </div><!-- fin body-->

  <div class="finder-footer">
    <a class="btn btn-lg btn-full btn-finder-cancelar">Cancelar</a>
    <a class="btn btn-lg btn-success btn-finder-insertar">Insertar</a>
  </div><!-- fin footer-->

</div>
<script type="text/javascript">
$(".modal-finder" ).ready(function() {
  var insert="";

  $('#filtrar').keyup(function () {
    var rex = new RegExp($(this).val(), 'i');
    $('#mul-all li').hide();
    $('#mul-all li').filter(function () {
        return rex.test($(this).text());
    }).show();
  });

  $(".btn-up-finder").click( function(){
    insert = $(this).attr("insert");
    item = $(this).attr("item");
    tipo_item = $(this).attr("tipo_item");
    tipo_video = $(this).attr("tipo_video");
    id_mod = $(this).attr("id_mod");
    seleccion = $(this).attr("seleccion");

    $(".finder-item").removeClass("on");
    $(".finder-item").attr("seleccionado","");

    $('.modal-finder').appendTo('body');
    $(".modal-finder").addClass("on");

    if (tipo_item=="imagen_unica"){
      $("#tab-imagenes").show();
      $("#tab-videos").hide();
      $("#tab-documentos").hide();
      $("#tab-audio").hide();
      $(".box-upload-embed").hide();
    }
    if (tipo_item=="video_unico"){
      $("#tab-imagenes").hide();
      $("#tab-videos").show();
      $("#tab-documentos").hide();
      $("#tab-audio").hide();
      $(".box-upload-embed").show();
    }
    if (tipo_item=="multimedia"){
      $("#tab-imagenes").show();
      $("#tab-videos").show();
      $("#tab-documentos").hide();
      $("#tab-audio").hide();
      $(".box-upload-embed").show();
    }
    //$(".finder-item").height(w);
    //resize_item();
  });

  $(".btn-finder-cancelar").click( function(){
    //resize_item();
    $(".modal-finder").removeClass("on");
    //$(".modal-finder .modal-finder-inner").html("");
    //$(".finder-item").height(w);

    //console.log (w);
  });

  $(".btn-finder-insertar").click( function(){
    x_id = $(".finder-item[seleccionado='on']").attr("id");
    xn_id = $(".finder-item[seleccionado='on']").size();
    x_url = $(".finder-item[seleccionado='on']").attr("url");
    x_mul_item = $(".finder-item[seleccionado='on']").attr("item");
    x_urlmini = $(".finder-item[seleccionado='on']").attr("url_mini");
    x_tipo_item = $(".finder-item[seleccionado='on']").attr("tipo_item");
    x_id_item = $(".finder-item[seleccionado='on']").attr("id_item");
    x_item_embed = $(".finder-item[seleccionado='on']").attr("embed");


    <?php
    if ($tipo_upload=="individual"){ ?>
      $("#<?php echo $id; ?>").attr("value",x_url );

      //$(".img-catalogo").attr("src","<?php echo _RUTA_WEB; ?>"+x_url);
      //console.log(x_id);
      $(".box-image").html("");
      $(".box-image").append("<div class='box-acciones-img'><a class='btn-eliminar-img btn'><i class='icn icn-close'/></a><a class='btn btn-editar-img'><i class='icn icn-pencil' /></a></div>");

      $(".box-image").append("<img class='img-file img-responsive img-catalogo' src='<?php echo _RUTA_WEB; ?>"+x_url+"' >");

      $(".btn-up-finder span").html("Actualizar");

      $(".modal-finder").removeClass("on");
      //$(".modal-finder .modal-finder-inner").html("");
      $(".btn-eliminar-imagen").click(function(){
        $(".box-image").html("");
        $("#<?php echo $id; ?>").attr("value","");
        $(".img-file").addClass("img-no-file");
        $(".img-file").attr("src","");
        $(".btn-up-finder span").html("Cargar archivo");
        $(this).remove();
      });
      $(".btn-eliminar-img").click( function(){
        //console.log("hola");
        $("#<?php echo $id; ?>").attr("value","");
        $(".image-block").html("<a item='' tipo_item='' class='btn btn-adicionar-imagen btn-adicionar-imagen-nuevo btn-up-finder'><i class='icn icn-media-plus' /><span>Imagen principal</span></a>");
        $(".btn-up-finder").click( function(){
          insert = $(this).attr("insert");
          item = $(this).attr("item");
          tipo_item = $(this).attr("tipo");
          $('.modal-finder').appendTo('body');
          $(".modal-finder").addClass("on");
          //$(".finder-item").height(w);
          //resize_item();
          //console.log (w);
        });
      });
    <?php
    }

    if ($tipo_upload=="insertar-editor-texto"){
      ?>

        //$(".note-editable").append("hola mundo");
        var data = $('#<?php echo $id; ?>').val();


        var imgx =  "<img src='<?php echo _RUTA_WEB; ?>"+x_url+"' >";
        var ix = "<?php echo _RUTA_WEB; ?>"+x_url;
        //$('#<?php echo $id; ?>').summernote('insertText', imgx );


          //$('#<?php echo $id; ?>').summernote('focus');
          $('#<?php echo $id; ?>').summernote('editor.restoreRange');
          $('#<?php echo $id; ?>').summernote('editor.focus');
          $('#<?php echo $id; ?>').summernote('editor.insertImage',ix);
          // $('.note-editable').focus();
          // $('.note-editable').html(data + emoticon);

          $(".modal-finder").removeClass("on");
        <?php
    }

    if ($tipo_upload=="individual-complejo"){
      ?>
        if (insert =="" ){
          var data = $('#<?php echo $id; ?>').val();


          var imgx =  "<img src='<?php echo _RUTA_WEB; ?>"+x_url+"' >";
          var ix = "<?php echo _RUTA_WEB; ?>"+x_url;
          //$('#<?php echo $id; ?>').summernote('insertText', imgx );


            //$('#<?php echo $id; ?>').summernote('focus');
            $('#<?php echo $id; ?>').summernote('editor.restoreRange');
            $('#<?php echo $id; ?>').summernote('editor.focus');
            $('#<?php echo $id; ?>').summernote('editor.insertImage',ix);
            // $('.note-editable').focus();
            // $('.note-editable').html(data + emoticon);

            $(".modal-finder").removeClass("on");
      }else{

          $("#"+insert).attr("value",x_url);

          //$(".img-catalogo").attr("src","<?php echo _RUTA_WEB; ?>"+x_url);
          //console.log(x_id);
          $(".box-image").html("");
          $(".box-image").append("<a class='btn-eliminar-imagen btn'><i class='icn icn-close'/></a>");

          $(".box-image").append("<img class='img-file img-responsive img-catalogo' src='<?php echo _RUTA_WEB; ?>"+x_url+"' >");

          $(".btn-up-finder span").html("Actualizar");

          $(".modal-finder").removeClass("on");
          //$(".modal-finder .modal-finder-inner").html("");
          $(".btn-eliminar-imagen").click(function(){
            $(".box-image").html("");
            $("#<?php echo $id; ?>").attr("value","");
            $(".img-file").addClass("img-no-file");
            $(".img-file").attr("src","");
            $(".btn-up-finder span").html("Cargar archivo");
            $(this).remove();
          });
        }
      <?php
    }
    ?>
    <?php
    if ($tipo_upload=="multiple-accion"){
      ?>
        $(".modal-finder").removeClass("on");
        console.log("item:"+item+" tipo_item:"+tipo_item+" id_item:"+id_item);
        if (tipo_item=="imagen_unica"){
          $("#"+id_item).attr("value",x_url);
          $("#img-"+id_item).attr("src","<?php echo _RUTA_WEB; ?>"+x_urlmini);
          $(".btn-adicionar-imagen-nuevo").css("display","none");
          $(".box-image-block-unica").css("display","inline-block");
        }
        if (tipo_item=="video_unico"){

          $("#video-"+id_item).attr("src","<?php echo _RUTA_WEB_NUCLEO; ?>"+x_url);
          $("#"+tipo_video).attr("value",x_tipo_item);

          if (x_item_embed!=""){
            $(".box-video-unico").html(x_item_embed);
            $("#"+id_item).attr("value",x_item_embed);
          }else{
            $("#"+id_item).attr("value",x_url);
          }
          $(".btn-adicionar-video").css("display","none");
          $(".box-image-video").css("display","inline-block");
        }
        if(tipo_item=="multimedia"){

          var elementos = $(".finder-item[seleccionado='on']");
          var size = elementos.size();
          //var arrayID = [];
          var aux="";

          $.each( elementos, function(i, val){
              var temp_id = $(val).attr('id');
              var temp_id_mul = $("#"+temp_id).attr('item');
              var temp_tipo_item = $("#"+temp_id).attr('tipo_item');
              var temp_url = $("#"+temp_id).attr('url');
              var temp_url_mini = $("#"+temp_id).attr('url_mini');
              var temp_embed = $("#"+temp_id).attr('embed');

              aux = aux + "<li item='"+item+"' id_mul='"+temp_id_mul+"' id='mul-"+temp_id_mul+"' orden='"+i+"' class='box-image box-mul-"+temp_tipo_item+" box-image-block box-image-mul ui-state-default'>";
      		    aux = aux + "<i class='icn icn-sorteable icn-reorder'></i>";
      				aux = aux +  "<div class='box-acciones-img'>";
      				aux = aux +  "<a class='btn btn-eliminar-mul' id_mul='"+temp_id_mul+"'><i class='icn icn-close' /></a>";
      				aux = aux +  "<a id_mul='"+temp_id_mul+"'  class='btn btn-editar-mul'><i class='icn icn-pencil' /></a>";
      				aux = aux + "</div>";

              if (temp_tipo_item=="embed" ){
                ruta = "<?php echo _RUTA_WEB_NUCLEO; ?>";
                aux = aux + "<div class='box-embed'>"+temp_embed+"</div>";
                url_final = "images/video-icon.png";
              }else{
                ruta = "<?php echo _RUTA_IMAGES; ?>";
                url_final = temp_url_mini;
              }
              if(temp_tipo_item=="mp4"){
                ruta = "<?php echo _RUTA_WEB_NUCLEO; ?>";
                aux = aux + "<i class='icon-play icn icn-play-circle'></i>";
                url_final = "images/video-icon.png";
                aux = aux + "<video muted  src='<?php echo _RUTA_IMAGES; ?>"+temp_url+"' ></video>";
              }
      		    aux = aux +  "<img class='img-catalogo img-file img-responsive' id='img-"+i+"' src='"+ruta+url_final+"' />";
              aux = aux +  "<input type='hidden' id='inputModItem[]' name='inputModItem[]' value='"+temp_id_mul+"'  />"
      		    aux = aux +  "</li>";
          });
          //console.log(  arrayID );
          //console.log(
          $(".box-multimedia").prepend( aux );

        }

      <?php
    }
    if ($tipo_upload=="individual-multimedia"){
      ?>
        $(".modal-finder").removeClass("on");
        console.log("bt:"+item+" tipo:"+tipo_item);
        if (item=="" && tipo_item=="" ){
          $("#<?php echo $id; ?>").attr("value",x_url);
          $(".image-block").html("");
          $(".image-block").append("<div class='box-acciones-img'><a class='btn-eliminar-img btn'><i class='icn icn-close'/></a><a class='btn btn-editar-img'><i class='icn icn-pencil' /></a></div>");
          $(".image-block").append("<img class='img-file img-responsive img-catalogo' src='<?php   echo _RUTA_WEB; ?>"+x_urlmini+"' >");

          $(".btn-eliminar-img").click( function(){
            //console.log("hola");
            $("#<?php echo $id; ?>").attr("value","");
            $(".image-block").html("<a item='' tipo_item='' class='btn btn-adicionar-imagen btn-adicionar-imagen-nuevo btn-up-finder'><i class='icn icn-media-plus' /><span>Imagen principal</span></a>");
            $(".btn-up-finder").click( function(){
              insert = $(this).attr("insert");
              item = $(this).attr("item");
              tipo_item = $(this).attr("tipo");
              $('.modal-finder').appendTo('body');
              $(".modal-finder").addClass("on");
              //$(".finder-item").height(w);
              //resize_item();
              //console.log (w);
            });
          });

        }else{
          var formdata = new FormData();
      		formdata.append("inputImg", x_url);
      		formdata.append("inputImgMini", x_urlmini);
      		formdata.append("inputItem", item);
      		formdata.append("inputTipoItem", x_tipo_item);
      		formdata.append("inputMul", x_mul_item);
      		formdata.append("inputIdMod", <?php echo $id_mod; ?>);
          formdata.append("ajax", "ajax-upload-multimedia");
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
      		$.ajax({
          			url:ruta,
          			type:"post",
          			data:formdata,
          			processData: false,
      				  contentType: false,
                xhr: function() {
                  var xhr = $.ajaxSettings.xhr();
                  xhr.upload.onprogress = function(e) {
                    var dat = Math.floor(e.loaded / e.total *100);
                    //console.log(Math.floor(e.loaded / e.total *100) + '%');
                    $(".box-multimedia").html("<li class='box-image-block progress'></li>");

                    //resize_item();
                  };
                  return xhr;
                },
          			success: function(msg){
                  //console.log(msg);
                  $(".box-multimedia").html(msg);
                  $(".btn-up-finder").click( function(){
        						insert = $(this).attr("insert");
        						item = $(this).attr("item");
        						tipo_item = $(this).attr("tipo_item");
        						$('.modal-finder').appendTo('body');
        						$(".modal-finder").addClass("on");
        						//$(".finder-item").height(w);
        						//resize_item();
        						//console.log (w);
        					});
                  $(".btn-eliminar-file").click( function(){
          					var id= $(this).attr("idm");
          					var mul = $(this).attr("mul");
          					var prodx = $(this).attr("prod");
          					$("#"+id).remove();
          					var formdata = new FormData();
          					formdata.append("inputMul", mul );
          					formdata.append("inputProd", prodx );
          					formdata.append("ajax", "ajax-eliminar-mul-prod");
          					var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          					$.ajax({
          								url:ruta,
          								type:"post",
          								data:formdata,
          								processData: false,
          								contentType: false,
          								success: function(msg){
          									//console.log(msg);
          								}
          					});
          				});
          			}
      		});

        }
      <?php
    }
    ?>
  });


  var xd = 0;

  $(".group-tabs .category").click(function(){
    var idf = $(this).attr("idtab");
    // alert(idf);
    $(".tab-content").removeClass("on");
    $(".category").removeClass("active");
    $("#tab-"+idf).addClass("active");
    $("#content-" + idf).addClass("on");
  });

  $("#inputArchivoFinder").change( function(){
    xd = xd + 1;
    //resize_item();
    var formData = new FormData();
    var arc =document.getElementById("inputArchivoFinder");
    var archivo = arc.files;
    //console.log( archivo.length );
    formData.append("cant_file",archivo.length);
    for(i=0; i<archivo.length; i++){
      //formData.append('file',archivo[i]);
      formData.append("file-"+i,archivo[i]);
    }

    formData.append("ajax","ajax-finder");
    formData.append("x_item",xd);
    // for (var i = 0; i < n; i++){
    //     if (elementos[i].type == "file"){
    //         var archivo = elementos[i].files;
    //         formData.append(elementos[i].name, archivo[0]);
    //     }
    //     else{
    //         formData.append(elementos[i].name, elementos[i].value);
    //     }
    // }
    //console.log(   formData  );
    var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
    $("#finder-figures").append("<li class='finder-item' id='item-"+xd+"' url_mini='' url=''></li>");
    resize_item();
    $.ajax({
        url: ruta,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        xhr: function() {
          var xhr = $.ajaxSettings.xhr();
          xhr.upload.onprogress = function(e) {
            var dat = Math.floor(e.loaded / e.total *100);
            //console.log(Math.floor(e.loaded / e.total *100) + '%');
            $("#finder-figures #item-" + xd).html("<div class='progreso'>"+ dat +"%</div>");
            //resize_item();
          };
          return xhr;
        },
        success: function(datos){
          console.log (datos);
          var ar = datos.split(":");
          var url = ar[0];
          var url_mini = ar[3];
          var rut='';
          var tipo_archivo = ar[4];
          var id = ar[1];
          if (tipo_archivo=="mp4"){
            rut = "<?php echo _RUTA_WEB_NUCLEO; ?>";
            url_final = "images/video-icon.png";
          }else{
            rut = "<?php echo _RUTA_WEB; ?>";
            url_final = url;
          }
          $("#item-"+id).html("");
          $("#item-"+id).attr("url",url);
          $("#item-"+id).attr("url_mini",url_mini);
          $("#item-"+id).prepend('<video muted controls src="<?php echo _RUTA_IMAGES; ?>'+url+'" poster="'+rut+url_final+'"></video>');
          $("#item-"+id).attr("style","background:url("+rut+url_final+") no-repeat; background-size:100% auto");
          //resize_item();
        }
      });

      $(".finder-item").click(function(){
        var id = $(this).attr("id");
        // console.log (id);
        // console.log ("1-"+seleccion);
        if(seleccion!="multiple"){
          $(".finder-item").removeClass("on");
          $(".finder-item").attr("seleccionado","");
        }
        $("#finder-figures #"+id).addClass("on");
        $("#finder-figures #"+id).attr("seleccionado","on");
        $("#content-imagenes #"+id).addClass("on");
        $("#content-imagenes #"+id).attr("seleccionado","on");
        $("#content-videos #"+id).addClass("on");
        $("#content-videos #"+id).attr("seleccionado","on");
      });

  });

  $(".modal-finder .modal-finder-inner").resize(function() {
    resize_item();
  });

  function resize_item(){
    w = $(".finder-item").innerWidth();
    console.log(w);
    $(".finder-item").height(w);
  }

  $(".finder-item").click(function(){
    var id = $(this).attr("id");
    //console.log (id);
    // console.log ("2-"+seleccion);
    if(seleccion!="multiple"){
      $(".finder-item").removeClass("on");
      $(".finder-item").attr("seleccionado","");
    }
    $("#finder-figures #"+id).addClass("on");
    $("#finder-figures #"+id).attr("seleccionado","on");
    $("#content-videos #"+id).addClass("on");
    $("#content-videos #"+id).attr("seleccionado","on");
    $("#content-imagenes #"+id).addClass("on");
    $("#content-imagenes #"+id).attr("seleccionado","on");
    //resize_item();
  });

});
</script>
