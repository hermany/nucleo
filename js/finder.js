
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
    $('.modal-finder').appendTo('body');
    $(".modal-finder").addClass("on");
    //$(".finder-item").height(w);
    //resize_item();
    //console.log (w);
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
    x_url = $(".finder-item[seleccionado='on']").attr("url");

    <?php if ($tipo_upload=="individual"){ ?>
      $("#<?php echo $id; ?>").attr("value",x_url );

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
    $("#finder-figures").append("<li class='finder-item' id='item-"+xd+"' url=''></li>");
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
          var id = ar[1];
          $("#item-"+id).html("");
          $("#item-"+id).attr("url",url);
          $("#item-"+id).attr("style","background:url(<?php echo _RUTA_WEB; ?>"+url+") no-repeat; background-size:100% auto");
          //resize_item();
        }
      });

      $(".finder-item").click(function(){
        var id = $(this).attr("id");
        //console.log (id);
        $(".finder-item").removeClass("on")
        $(".finder-item").attr("seleccionado","")
        $("#finder-figures #"+id).addClass("on");
        $("#finder-figures #"+id).attr("seleccionado","on");
        $("#content-imagenes #"+id).addClass("on");
        $("#content-imagenes #"+id).attr("seleccionado","on");
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
    $(".finder-item").removeClass("on")
    $(".finder-item").attr("seleccionado","");
    $("#content-imagenes #"+id).addClass("on");
    $("#content-imagenes #"+id).attr("seleccionado","on");
    //resize_item();
  });
