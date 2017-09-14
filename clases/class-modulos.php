<?PHP
header('Content-Type: text/html; charset=utf-8');
header('content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

class CLASSMODULOS{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

	function estado_publicacion($estado,$id_mod,$disabled,$id,$activar){
    //echo "id:".$id;

    if($activar==""){
			$activar="activar";
    }

		if( $estado==1){
      		echo "<a title='activo' class='btn btn-fila-activar $disabled' id='btn-p-$id_mod-$id' id_mod='$id_mod' vars='$activar,$id,0' id_item='$id' estado='0' ><span style='display:none'>0</span><i class='icn-eye-open color-text-negro-b'></i></a>";
  		}else{
      		echo "<a title='desactivado' class='btn btn-fila-activar  $disabled' id='btn-p-$id_mod-$id' id_mod='$id_mod' id_item='$id' estado='1' vars='$activar,$id,1' ><span style='display:none'>1</span><i class='icn-eye-close color-text-gris-a'></i></a>";
  		};
    ?>
    <?php
	}

  function script_tabs(){
    return '$(".group-tabs .category").click(function(){
      var idf = $(this).attr("idtab");
      // alert(idf);
      $(".tab-content").removeClass("on");
      $(".category").removeClass("active");
      $("#tab-"+idf).addClass("active");
      $("#content-" + idf).addClass("on");
    });';
  }


  function estado_activar( $estado,$link,$id_mod,$disabled,$id){
		$link = _RUTA_WEB.$link;
    if (!empty($id_mod)){ $mod="&id_mod=".$id_mod; }else{ $mod=""; }
		if( $estado==1){
      		echo "<a title='activo' class='btn btn-fila-activar $disabled' href='$link&estado=0&id=$id$mod' ><i class='icn-eye-open color-text-negro-b'></i></a>";
  		}else{
      		echo "<a title='desactivado' class='btn btn-fila-activar $disabled' href='$link&estado=1&id=$id$mod' ><i class='icn-eye-close color-text-gris-a'></i></a>";
  		};
	}

	function script_busqueda($FileModulo){
  	?>
  		<script language="JavaScript">
  			function confirma_eliminacion(mod_id, mod_nombre, mod_tarea){
  			  url = "<?php echo $FileModulo; ?>&tarea="+ mod_tarea + "&id="+ mod_id;
  			  if (confirm('¿Está seguro que desea eliminar "'+ mod_nombre +'" \n el Registro de la Base de Datos?'))
  			  location=(url)
  			}

  		</script>
  	<?php
	}

	function script_page($FileModulo){
		?>
			<script language="JavaScript">
			$(document).ready(function()
			{

			});
			</script>
		<?php
	}

	function script_accion_modulo(){
		?>
    <script type="text/javascript">
      $(document).ready(function(){
        $(".btn-menu-ajax").click( function(e){
          $(".modal-form").addClass("on");
          $(".modal-form").addClass("<?php echo $url_a; ?>");
          $(".body-page").css("overflow-y","hidden");
          var variables = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');
          var ruta='ajax-adm';
          var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
          abrir_modulos(datos);
        });

        $('a.btn-m-activar').on('click',function (e) {
          e.preventDefault();
          var variables = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');
          var ruta='ajax-activar';
          var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
          //alert(datos);
          accion_modulo(datos);
        });

        $("a.btn-m-eliminar").on('click',function (e) {
          e.preventDefault();
          var nom= $(this).attr('nombre');
          var variablesx = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');

          $(".modal-form").addClass("on");
          $(".content-page").css("overflow-y","hidden");
          $(".modal-form .modal-inner").addClass("mensaje-eliminar");
          $(".modal-form .modal-inner").html('<div class="modal-title"></div><div class="modal-body"> <i class="icn icn-trash"></i> <label>"'+nom+'" se eliminará, estas seguro de eliminarlo. </label><span>No podrás deshacer esta acción.<span> </div><div class="modal-footer"><a class="btn btn-cancelar btn-small btn-full">Cancelar</a><a class="btn btn-info btn-m-eliminar btn-small" id_mod="'+id_mod+'" vars="'+variablesx+'" >Eliminar</a></div>');

          $(".btn-cancelar").on("click",function(e){
            e.preventDefault();
            $(".modal-form").removeClass("on");
            $(".modal-form .modal-inner").removeClass("mensaje-eliminar");
            $(".modal-form .modal-inner").html(" ");
            $(".content-page").css("overflow-y","auto");
          });

          $(".btn-m-eliminar").on("click",function(e){
            e.preventDefault();
            var variables = $(this).attr('vars');
            var id_mod= $(this).attr('id_mod');
            var ruta='ajax-eliminar';
            var datos= { ajax:ruta,inputIdMod:id_mod,inputVars:variables };
            //alert(variables);
            accion_modulo(datos);
          });

        });


        function accion_modulo(datos){
          $.ajax({
            url:"<?php echo _RUTA_WEB; ?>ajax.php",
            type:"post",
            async: true,
            data:datos,
            success: function(msg){
              //console.log( msg );
              var variables = msg;
              var cadena = variables.split(':');
              var accion = cadena[0];
              var id_item = cadena[1];
              var estado = cadena[2];
              var id_mod = cadena[3];
              var errores = cadena[4];
              switch ( accion ){
                case 'activar':
                  //console.log(estado);
                  $("#btn-p-"+id_mod+"-"+id_item+" i").removeClass();
                  if(estado==1){
                    $("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-open");
                    $("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",0");
                  }else{
                    $("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-close");
                    $("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",1");
                  }
                  $(".content-page").css("overflow-y","auto");
                break;
                case 'eliminar':
                  //console.log(id_item);
                  $(".modal-form").removeClass("on");
                  $(".modal-form .modal-inner").removeClass("mensaje-eliminar");
                  $(".modal-form .modal-inner").html("");
                  $(".row-"+id_item).addClass("removiendo");
                  $("#btn-nav-sis-"+id_item).addClass("removiendo");
                  setTimeout(function() {
                    $(".row-"+id_item).remove();
                    $("#btn-nav-sis-"+id_item).remove();
                    $(".content-page").css("overflow-y","auto");
                  }, 1500 );

                break;
                default:
                alert("no hay una acción determinada, revisar error base de datos " + errores);
              }
            }
          });
        };

        function abrir_modulos(datos){
          $.ajax({
            url:"<?php echo _RUTA_WEB; ?>ajax.php",
            type:"post",
            data:datos,
            success: function(msg){

              $("#modal .modal-inner").html(msg);
              var wbm = $(".modal .modal-inner").height();
              var wbmx = wbm - 108;
              console.log("body-modulo:"+wbmx);
              $(".body-modulo").height(wbmx);
            },
            complete : function() {
              $('.preloader-page').fadeOut('slow');
              // var wmi =   $("#modal .modal-inner").width();
              // var hmi =   $("#modal .modal-inner").height();
              // var x_wmi = Math.round(wmi /2);
              // var y_hmi = Math.round(hmi /2);
              // $("#modal .modal-inner").css("margin-left","-"+x_wmi+"px");
              // $("#modal .modal-inner").css("margin-top","-"+y_hmi+"px");
            }
          });
        }
      });
    </script>
		<?php
	}  // fin script_busqueda()

  function script_table($id,$id_mod,$tipo="asc",$orden="0",$cant=25,$pag_up=fals){
    ?>
		<script type="text/javascript" language="javascript" src="<?php echo _RUTA_WEB_NUCLEO; ?>js/jquery.dataTables.min.js"></script>
      <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/datatables.css" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/datatables-theme.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
      $(document).ready( function() {
        $('#<?php echo $id; ?>').DataTable({
          <?php
            if($pag_up){
          ?>
          "dom": '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
          <?php
            }
          ?>
          "language": {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "<i class='icn icn-zoom'></i>",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
          "sFirst":    "Primero",
          "sLast":     "Último",
          "sNext":     "<i class='btn btn-small icn-chevron-right'></i>",
          "sPrevious": "<i class='btn btn-small icn-chevron-left'></i>"
          },
          "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
          },
                "bSortable": true,
                "pageLength": <?php echo $cant; ?>,
                <?php if ($orden!=""){ ?>
                "order": [[ <?php echo $orden; ?>, '<?php echo $tipo; ?>' ]]
                <?php } ?>
        });

        $('#<?php echo $id; ?>').on('click','a.btn-fila-activar', function (e) {
          e.preventDefault();
          var variables = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');
          var ruta='ajax-activar';
          var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
          //alert(datos);
          accion_modulo(datos);
        });

        $("#<?php echo $id; ?>").on('click','a.btn-fila-eliminar', function (e) {
          e.preventDefault();
          var nom= $(this).attr('nombre');
          var variablesx = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');

          $(".modal-form").addClass("on");
          $(".content-page").css("overflow-y","hidden");
          $(".modal-form .modal-inner").addClass("mensaje-eliminar");
          $(".modal-form .modal-inner").html('<div class="modal-title"></div><div class="modal-body"> <i class="icn icn-trash"></i> <label>"'+nom+'" se eliminará, estas seguro de eliminarlo. </label><span>No podrás deshacer esta acción.<span> </div><div class="modal-footer"><a class="btn btn-cancelar btn-small btn-full">Cancelar</a><a class="btn btn-info btn-m-eliminar btn-small" id_mod="'+id_mod+'" vars="'+variablesx+'" >Eliminar</a></div>');

          $(".btn-cancelar").on("click",function(e){
            e.preventDefault();
            $(".modal-form").removeClass("on");
            $(".modal-form .modal-inner").removeClass("mensaje-eliminar");
            $(".modal-form .modal-inner").html(" ");
            $(".content-page").css("overflow-y","auto");
          });

          $(".btn-m-eliminar").on("click",function(e){
            e.preventDefault();
            var variables = $(this).attr('vars');
            var id_mod= $(this).attr('id_mod');
            var ruta='ajax-eliminar';
            var datos= { ajax:ruta,inputIdMod:id_mod,inputVars:variables };
            //alert(variables);
            accion_modulo(datos);
          });

        });


        function accion_modulo(datos){
          $.ajax({
            url:"<?php echo _RUTA_WEB; ?>ajax.php",
            type:"post",
            async: true,
            data:datos,
            success: function(msg){
              //console.log( msg );
              var variables = msg;
              var cadena = variables.split(':');
              var accion = cadena[0];
              var id_item = cadena[1];
              var estado = cadena[2];
              var id_mod = cadena[3];
              var errores = cadena[4];
              switch ( accion ){
                case 'activar':
                  //console.log(estado);
                  $("#btn-p-"+id_mod+"-"+id_item+" i").removeClass();
                  if(estado==1){
                    $("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-open");
                    $("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",0");
                  }else{
                    $("#btn-p-"+id_mod+"-"+id_item+" i").addClass("icn-eye-close");
                    $("#btn-p-"+id_mod+"-"+id_item).attr("vars","activar,"+id_item+",1");
                  }
                  $(".content-page").css("overflow-y","auto");
                break;
                case 'eliminar':
                  //console.log(id_item);
                  $(".modal-form").removeClass("on");
                  $(".modal-form .modal-inner").removeClass("mensaje-eliminar");
                  $(".modal-form .modal-inner").html("");
                  $(".row-"+id_item).addClass("removiendo");
                  $("#btn-nav-sis-"+id_item).addClass("removiendo");
                  setTimeout(function() {
                    $(".row-"+id_item).remove();
                    $("#btn-nav-sis-"+id_item).remove();
                    $(".content-page").css("overflow-y","auto");
                  }, 1500 );

                break;
                default:
                alert("no hay una acción determinada, revisar error base de datos " + errores);
              }
            }
          });
        };

        $("#<?php echo $id; ?>").on('click','a.btn-m-editar', function (e) {
          e.preventDefault();
          $(".modal-form").addClass("on");
          $(".modal-form").addClass("<?php echo $url_a; ?>");
          $(".body-page").css("overflow-y","hidden");
          var variables = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');
          var ruta='ajax-adm';
          var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
          abrir_modulos(datos);
        });



        function abrir_modulos(datos){
          $.ajax({
            url:"<?php echo _RUTA_WEB; ?>ajax.php",
            type:"post",
            data:datos,
            success: function(msg){

              $("#modal .modal-inner").html(msg);
              var wbm = $(".modal .modal-inner").height();
              var wbmx = wbm - 108;
              console.log("body-modulo:"+wbmx);
              $(".body-modulo").height(wbmx);
            },
            complete : function() {
              $('.preloader-page').fadeOut('slow');
              // var wmi =   $("#modal .modal-inner").width();
              // var hmi =   $("#modal .modal-inner").height();
              // var x_wmi = Math.round(wmi /2);
              // var y_hmi = Math.round(hmi /2);
              // $("#modal .modal-inner").css("margin-left","-"+x_wmi+"px");
              // $("#modal .modal-inner").css("margin-top","-"+y_hmi+"px");
            }
          });
        }

      });
    </script>
    <?php
  }

  function script_form($ruta,$id_mod,$tipo="asc",$orden=0,$cant=25,$pag_up=false){
		?>
			<script language="JavaScript">
			$(document).ready(function() {
        $('.requerido').before('<span class="obligatorio">*</span>');


				$('#table_id').DataTable({
					<?php
						if($pag_up){
					?>
					"dom": '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
					<?php
						}
					?>
					"language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
		            "pageLength": <?php echo $cant; ?>,
		            "order": [[ <?php echo $orden; ?>, '<?php echo $tipo; ?>' ]]
				});

				$('#table_id_modal').DataTable({
					"language": {
              "sProcessing":     "Procesando...",
              "sLengthMenu":     "Mostrar _MENU_ registros",
              "sZeroRecords":    "No se encontraron resultados",
              "sEmptyTable":     "Ningún dato disponible en esta tabla",
              "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
              "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
              "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
              "sInfoPostFix":    "",
              "sSearch":         "Buscar:",
              "sUrl":            "",
              "sInfoThousands":  ",",
              "sLoadingRecords": "Cargando...",
              "oPaginate": {
                  "sFirst":    "Primero",
                  "sLast":     "Último",
                  "sNext":     "Siguiente",
                  "sPrevious": "Anterior"
              },
              "oAria": {
                  "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                  "sSortDescending": ": Activar para ordenar la columna de manera descendente"
              }
          },
		            "pageLength": <?php echo $cant; ?>,
		            "order": [[ <?php echo $orden; ?>, '<?php echo $tipo; ?>' ]]
				});

				$('#table_id_modal_aux').DataTable({
					"language": {
		            "url": "<?php echo _RUTA_WEB_NUCLEO; ?>js/spanish_datatable.json"
		            },
		            "pageLength": <?php echo $cant; ?>,
		            "order": [[ <?php echo $orden; ?>, '<?php echo $tipo; ?>' ]]
				});

				$(".btn-eliminar").click(function() {
					tarea = $( this ).attr("tarea");

          id = $( this ).attr("ide");
					nombre = $( this ).attr("nombre");
          idx = $( this ).attr("idEliminar");
					if (idx){
					  id = idx;
					  nombre = $( this ).attr("nombreEliminar");
            if (nombre!=""){
              nombre = $( this ).attr("nombre");
            }
          }

          <?php
            if (!empty($id_mod)){ $m ="&id_mod=".$id_mod; }else{ $m =""; }
          ?>

					if(confirm('¿Estas seguro de ELIMINAR: "'+ nombre +'" ?')){
					  //alert(url);
					  //document.location.href=url;
					  var id_mod = $( this ).attr("id_mod");
					  var variables = $( this ).attr("vars");
					  var ruta = "ajax-adm";
					  var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
            $("#popup-div").html("");
					  abrir_modulo(datos);
					}
				});


				var adicionarImagen = function (context) {
				  var ui = $.summernote.ui;

				  // create button
				  var button = ui.button({
				    contents: '<i class="fa fa-picture-o"/>',
				    tooltip: 'imagen',
				    click: function () {
				      // invoke insertText method with 'hello' on editor module.
				       $( ".note-editable" ).append( "<p>hola</p>" );
				    }
				  });

				  return button.render();   // return button as jquery object
				}

				$('.summernote').summernote({
						height: 300,                 // set editor height
						minHeight: null,             // set minimum height of editor
						maxHeight: null,             // set maximum height of editor
						lang: 'es-ES',
						focus: false,
						toolbar: [
							['style', ['style','bold', 'italic', 'underline', 'clear','hr']],
						    ['font', ['strikethrough', 'superscript', 'subscript']],
						    ['fontsize', ['fontsize']],
						    ['color', ['color']],
						    ['table', ['table']],
						    ['para', ['ul', 'ol', 'paragraph']],
						    ['height', ['height']],
						    ['codeview',['codeview','fullscreen']],
						    ['mybutton', ['imagen','link']],

						  ],

						  buttons: {
						    imagen: adicionarImagen
						  }
				});

				$('#select_all').click(function(event) {
				  if(this.checked) {
				      // Iterate each checkbox
				      $(':checkbox').each(function() {
				          this.checked = true;
				      });
				  }
				  else {
				    $(':checkbox').each(function() {
				          this.checked = false;
				      });
				  }
				});

				$("#restaurar_all").click(function(){
					var link = $(this).attr("link");
					var sw = false;
					$(':checkbox').each(function() {
						if(this.checked)
							sw=true;
					});
					if(sw){
						$("#form_papelera").attr("action",link);
						$("#form_papelera").submit();
					}
					else{
						alert("Seleccione por lo menos una fila");
					}
				});

				$("#vaciar_all").click(function(){
					var link = $(this).attr("link");
					var sw = false;
					$(':checkbox').each(function() {
						if(this.checked)
							sw=true;
					});
					if(sw){
						if(confirm('¿Estas seguro de ELIMINAR todo lo seleccionado?')){
							$("#form_papelera").attr("action",link);
							$("#form_papelera").submit();
						}
					}
					else{
						alert("Seleccione por lo menos una fila");
					}
				});


			$(".btn-form").click(function(){
					var formk = $(this).attr("form");

					var act = '&estado-mod='+$(this).val();
					var datos = $("#"+formk).serialize()+act;
          //console.log(datos);
          //alert(datos);
          $("#popup-div").html("");
          $(".footer-pag").removeClass("on");
					abrir_modulo(datos);
			});


			} );

      $(".footer-pag").addClass("on");

      function ordenarCat(id_mod, cat){
  		var ruta = "ajax-adm";
  		var variables = "ordenar,"+cat;
  		var datos = {ajax:ruta, inputIdMod:id_mod , inputVars : variables };
  		$("#popup-div").html("");
  		$(".footer-pag").removeClass("on");
  		abrir_modulo(datos);
  	}

  			</script>
  		<?php
	}

	function script_location($id_mod,$variables){
  	?>
  		<script language="JavaScript">
	  		var datos = {ajax:"ajax-adm", inputIdMod:"<?php echo $id_mod; ?>" , inputVars : "<?php echo $variables; ?>" };
  			abrir_modulo(datos);
  		</script>
  	<?php
	}

  function modal_page($id_mod){
    ?>
    <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/modal.adm.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/modal-theme.adm.css" rel="stylesheet" type="text/css">
    <div class="modal" id="modal"><div class="modal-inner"></div></div>
    <?php
  }

  function modal_editor_texto($id){

    ?>
      <script>
      $(document).ready(function() {
        var adicionarImagen = function (context) {
				  var ui = $.summernote.ui;

				  // create button
				  var button = ui.button({
				    contents: '<i class="note-icon-picture"/>',
				    tooltip: 'imagen',
				    click: function () {
				      // invoke insertText method with 'hello' on editor module.
				       //$( ".note-editable" ).append( "<p>hola</p>" );

               $('#<?php echo $id; ?>').summernote('editor.saveRange');
               $('.modal-finder').appendTo('body');
               $(".modal-finder").addClass("on");
               insert = '<?php echo $id; ?>';
               var upload = 'insertar-editor-texto';
               var vars= '';
               var seleccion = 'simple';
               cargar_finder(insert,upload,vars,seleccion);

               <?php $this->fmt->finder->carga_finder(); ?>
               //context.invoke('editor.insertText', window.globalVar );
				    }
				  });

				  return button.render();   // return button as jquery object
				}

        $('#<?php echo $id; ?>').summernote({
            height: 430,                 // set editor height
            minHeight: null,             // set minimum height of editor
            maxHeight: null,             // set maximum height of editor
            lang: 'es-ES',
            paragraph: 'justifyLeft',
            focus: false,
            toolbar: [
                ['style', ['style','bold', 'italic' ]],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph','table']],
                ['fontsize', ['fontsize']],
                ['codeview',['clear','codeview','fullscreen']],
                ['mybutton', ['hello','link','imagen','video']],
              ],
              onInit : function(){
                $('.note-btn').attr('title', '');
              },
              buttons: {
                imagen: adicionarImagen
              }
        });
        $('.note-btn').attr('title', '');
      });
      </script>

      <?php
  }

  function modal_script($id_mod){
    ?>
    <script type="text/javascript">
      $(document).ready(function(){

        var hm = $("#modal .modal-inner").innerHeight();
        //var pmi = $("#modal .modal-inner").offset();
        //var hmh = hm - 110;
        //console.log(pmi.left);
        //$("#modal .modal-inner .body-modulo").height(hmh);
        //$("#modal .modal-inner .form-botones").css("left",pmi.left+2);

        $(".btn-menu-ajax").on("click", function(e){
          // alert("btn-menu-ajax click");
          e.preventDefault();

          $(".modal-form").addClass("on");
          $(".modal-form").addClass("<?php echo $this->ruta_amigable_modulo($id_mod); ?>");
          $(".content-page").css("overflow-y","hidden");
          var variables = $(this).attr('vars');
          var id_mod= $(this).attr('id_mod');
          var ruta='ajax-adm';
          var datos= { ajax:ruta,inputIdMod:id_mod, inputVars:variables };
          //alert(datos);
          abrir_modulos(datos);
        });

        $(".btn-collapse").click( function(e){
          var id = $(this).attr("collapse");
          $("#"+id).toggleClass("on");
          //console.log(id);
        });

        $(".btn-form").on("click",function(e){
          e.preventDefault();
           //alert("btn-form click");
           var formk = $(this).attr("form");
           var act = '&estado-mod='+$(this).val();
           var datos = $("#"+formk).serialize()+act;
           //console.log(datos);
           //alert(datos);
          //  $(".modal").html("");
          //  $(".modal").empty();
          //  $(".modal").removeClass("<?php echo $this->ruta_amigable_modulo($id_mod); ?>");
          //  $(".modal").removeClass("on");
           abrir_modulos(datos);
        });

        function abrir_modulos(datos){
          $.ajax({
            url:"<?php echo _RUTA_WEB; ?>ajax.php",
            type:"post",
            data:datos,
            cache:false,
            async: true,
            success: function(msg){
              $("#modal .modal-inner").html(msg);
              $('.preloader-page').fadeOut('slow');

            },
            error: function() {
              alert( "Ha ocurrido un error" );
            }
          });
        }

      });
    </script>
    <?php
  }

  function redireccionar($ruta,$tiempo){
    ?>
      <script type="text/javascript">
        setTimeout(function() {
          //console.log("llegue al cierre");
            $(".modal-form").html("");
            $(".modal-form .modal-inner").html("");
            $(".body-page").css("overflow-y","auto");
            document.location.href="<?php echo $ruta; ?>";
        }, <?php echo $tiempo; ?> );
      </script>
    <?php
  }

	function fecha_zona($zona){
  	date_default_timezone_set($zona);
  	setlocale(LC_TIME, "es_ES");
  }

function traer_fecha_literal($fecha_hora){
		$month = array(' ','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
		$dato=explode(" ", $fecha_hora);
		$data=explode("-", $dato[0]);
		$mes=(string)(int)$data[1];
		return "<span class='dia dia-inicio'>".$data[2]."</span> <span class='mes mes-inicio'>de ".$month[$mes]."</span> <span class='ano ano-inicio'>del ".$data[0]."</span>";
	}

  function estructurar_hashtags($tags){
    $tagx = explode(",",$tags);
    $num_tags = count($tagx);
    $aux="";
    if ($num_tags>1){
      for ($i=0; $i < $num_tags; $i++) {
         $aux .= " #".$tagx[$i];
      }
    }
    return $aux;
  }

  function estructurar_fecha_var($fecha,$modo=""){
    $fechaHora = explode(" ", $fecha);
    $fechas = explode("-", $fechaHora[0]);
    $tiempo = explode (":", $fechaHora[1]);
    $ano=$fechas[0];
    $mes=(string)(int)$fechas[1];
    $dia=$fechas[2];
    $hora = $tiempo[0];
    $min = $tiempo[1];
    $seg = substr($tiempo[2], 0, 2);

      if ($modo=="min"){
        $day = array(' ','Lun','Mar','Mie','Jue','Vie','Sab',"Dom");
  	    $month = array(' ','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
      }else{
	      $day = array(' ','Lunes','Martes','Miercoles','Jueves','Viernes','S&aacute;bado',"Domingo");
	      $month = array(' ','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
      }

      $F .= "<span class='dia-semana'>".$day[date('N', strtotime($fecha))]."</span>";
	    $F .= "<span class='dia'> ".$dia."</span>";
	    $F .= "<span class='mes'> ".$month[$mes]."</span>";
	    $F .= "<span class='ano'> ".$ano." </span>";

      if (!empty($hora)){
        //$F .= "<div class='box-hora'>";
        $F .= "<span class='hora'>".$hora."</span>";
        $F .= "<span class='min'>:".$min."</span>";
        $F .= "<span class='seg'>:".$seg."</span>";
        //$F .= "</div>";
      }

		return $F;
	}

  function formatear_hora($hora){
    $h = explode(":",$hora);
    $hr="<span class='hora'>$h[0]</span>:";
    $hr.="<span class='min'>$h[1]</span>";
    $hr.="<span class='seg'>:$h[2]</span>";
    return $hr;
  }

	function traer_fecha_literal_2($fecha1, $fecha2){
		if($fecha1==$fecha2){
			return $this->traer_fecha_literal($fecha1." 00:00:00");
		}
		else{
			$month = array(' ','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			$dato_1=explode("-", $fecha1);
			$dato_2=explode("-", $fecha2);
			if($dato_1[0]==$dato_2[0]){
				$mes1=(string)(int)$dato_1[1];
				if($dato_1[1]==$dato_2[1]){
					return "<span class='dia dia-inicio'>".$dato_1[2]."</span> al <span class='dia dia-fin'>".$dato_2[2]."</span> de ".$month[$mes1]." del ".$dato_1[0];
				}
				else{
					$mes2=(string)(int)$dato_2[1];
					return "<span class='dia dia-inicio'>".$dato_1[2]."</span> de ".$month[$mes1]." al ".$dato_2[2]." de ".$month[$mes2]." del ".$dato_1[0];
				}
			}
			else{
				return $this->traer_fecha_literal($fecha1." 00:00:00")." al ".$this->traer_fecha_literal($fecha2." 00:00:00");
			}
		}
	}


	function tiempo_restante($desde,$hasta) {
	    $ini = explode(" ",$desde);
	    $fIni = $ini[0];
	    $hIni = $ini[1];
	    $fIni = explode("-",$fIni);
	    $hIni = explode(":",$hIni);

	    $fin = explode(" ",$hasta);
	    $fFin = $fin[0];
	    $hFin = $fin[1];
	    $fFin = explode("-",$fFin);
	    $hFin = explode(":",$hFin);

	    $anos = $fFin[0] - $fIni[0];
	    $meses = $fFin[1] - $fIni[1];
	    $dias = $fFin[2] - $fIni[2];

	    $horas = $hFin[0] - $hIni[0];
	    $minutos = $hFin[1] - $hIni[1];
	    $segundos = $hFin[2] - $hIni[2];

	    if ($segundos < 0) {
	        $minutos--;
	        $segundos = 60 + $segundos;
	    }
	    if ($minutos < 0) {
	        $horas--;
	        $minutos = 60 + $minutos;
	    }

	    if ($horas < 0) {
	        if ($dias!=0){
            //$dias--;
            $horas = 24 + $horas;
          }
	    }

      //echo "d:".$dias;

      if ($dias << 0){
        //echo "ingrese";
        if ($meses!=0){
	        //$meses--;

	        switch ($fIni[1]) {
	            case 1:     $dias_mes_anterior=30; break;
              case 2:
                          if (checkdate(2,29,$fIni[0])){
                              $dias_mes_anterior=29; break;
                          } else {
                              $dias_mes_anterior=28; break;
                          }
	            case 3:     $dias_mes_anterior=31; break;
	            case 4:     $dias_mes_anterior=31; break;
	            case 5:     $dias_mes_anterior=30; break;
	            case 6:     $dias_mes_anterior=31; break;
	            case 7:     $dias_mes_anterior=30; break;
	            case 8:     $dias_mes_anterior=31; break;
	            case 9:     $dias_mes_anterior=31; break;
	            case 10:     $dias_mes_anterior=30; break;
	            case 11:     $dias_mes_anterior=31; break;
	            case 12:     $dias_mes_anterior=30; break;
	        }
        }
	    }

	    // if ($meses < 0)
	    // {
	    //     --$anos;
	    //     $meses = $meses + 12;
	    // }

      if($anos==0){
        if($meses==0){
          if($dias==0){
            if($horas==0){
              if($minutos==0){
                $tiempo="Hace instantes";
              }else{
                $tiempo="Hace ".$minutos." min.";
              } // fin min
            }else{
              if ($horas<0){
                $tiempo .=$this->fmt->mensaje->programado();
                $tiempo .=$this->fecha_hora_compacta($desde);
              }else{
                $tiempo="Hace ".$horas." hr.";
              }
            }// fin horas
          }else{
            if ($dias<0){
              // menus de un día
              $tiempo .=$this->fmt->mensaje->programado();
              $tiempo .=$this->fecha_hora_compacta($desde);
            }else{
              // más de 1 día
              if($dias==1){
                $tiempo="Ayer";
              }else{
                if($dias>7){
                  $tiempo=$this->fecha_hora_compacta($desde);
                }else{
                  $tiempo="Hace ".$dias." días.";
                }
              }
            }
          } // fin +7 días
        }else{
          $d = $dias + $dias_mes_anterior;
          if($d<=7){
            $tiempo="Hace ".$d." días.";
          }else{
            //echo $meses;
            if ($meses<0){
              $tiempo .=$this->fmt->mensaje->programado();
              $tiempo .=$this->fecha_hora_compacta($desde);
            }else{
              $tiempo=$this->fecha_hora_compacta($desde);
            }
          }
        }// fin meses
      }else{
        $tiempo=$this->fecha_hora_compacta($desde);
      } // fin años
    return $tiempo;
	}

  function fecha_hoy($zona){
    setlocale(LC_TIME,"es_ES");
	  date_default_timezone_set($zona);
    return date("Y-m-d H:i:s");
  }

	function Estructurar_Fecha($Fecha){
	    $Fechas = explode("-", $Fecha);
	    $ano=$Fechas[0];
	    $mes=(string)(int)$Fechas[1];
	    $dia=(string)(int)$Fechas[2];


	    $day = array(' ','Lunes','Martes','Miercoles','Jueves','Viernes');
	    $month = array(' ','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');


	    $F .= "<span class='Dia'>".$dia." </span>";
	    $F .= "<span class='Mes'>".$month[$mes]." </span>";
	    $F .= "<span class='Ano'>".$ano." </span>";

		return $F;
	}

	function Estructurar_Fecha_input($Fecha){
	    $Fechas = explode("-", $Fecha);
	    $ano=$Fechas[0];
	    $mes=(string)(int)$Fechas[1];
	    $dia=(string)(int)$Fechas[2];
	    return $dia."/".$mes."/".$ano;
	}

	function Restructurar_Fecha($Fecha){
	    $Fechas = explode("/", $Fecha);
	    $ano=$Fechas[2];
	    $mes=(string)(int)$Fechas[1];
	    $dia=(string)(int)$Fechas[0];
	    return $ano."-".$mes."-".$dia;
	}

	/* function Fecha_Hora_Compacta($Fecha){
	    $FechaHora = explode(" ", $Fecha);
	    $Fechas = explode("-", $FechaHora[0]);
	    $Tiempo = explode (":", $FechaHora[1]);
	    $ano=$Fechas[0];
	    $mes=(string)(int)$Fechas[1];
	    $dia=$Fechas[2];
	    $hora = $Tiempo[0];
	    $min = $Tiempo[1];
	    $seg = substr($Tiempo[2], 0, 2);


	    $day = array(' ','Lun','Mar','Mie','Jue','Vie');
	    $month = array(' ','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');


	    $F .= " <span class='dia'>".$dia." </span>";
	    $F .= " <span class='mes'>".$month[$mes]." </span>";
	    $F .= " <span class='ano'>".$ano." </span>";
	    $F .= "<span class='hora'>".$hora."</span>";
	    $F .= "<span class='min'>".$min."</span>";
	    $F .= "<span class='seg'>".$seg."</span>";

		return $F;
	}*/

  function fecha_hora_compacta($fecha){
	    $fechaHora = explode(" ", $fecha);
	    $fechas = explode("-", $fechaHora[0]);
	    $tiempo = explode (":", $fechaHora[1]);
	    $ano=$fechas[0];
	    $mes=(string)(int)$fechas[1];
	    $dia=$fechas[2];
	    $hora = $tiempo[0];
	    $min = $tiempo[1];
	    $seg = substr($tiempo[2], 0, 2);


	    $day = array(' ','Lun','Mar','Mie','Jue','Vie');
	    $month = array(' ','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');

      $F .= "<div class='block-date'>";
	    $F .= " <span class='dia'>".$dia." </span>";
	    $F .= " <span class='mes'>".$month[$mes]." </span>";
	    $F .= " <span class='ano'>".$ano." </span>";
	    $F .= "<span class='hora'>".$hora."</span>";
	    $F .= "<span class='min'>".$min."</span>";
	    $F .= "<span class='seg'>".$seg."</span>";
      $F .= "</div>";

		return $F;
	}
  function estructurar_fecha_hora($fecha){
      $fechaHora = explode(" ", $fecha);
      $fechas = explode("-", $fechaHora[0]);
      $tiempo = explode (":", $fechaHora[1]);


      $ano=$fechas[0];
      $mes=(string)(int)$fechas[1];
      $dia=$fechas[2];
      $hora = $tiempo[0];
      $min = $tiempo[1];
      $seg = substr($tiempo[2], 0, 2);
      return $dia."-".$mes."-".$ano." ".$hora.":".$min;

  }
  function desestructurar_fecha_hora($fecha){
      $fechaHora = explode(" ", $fecha);
      $fechas = explode("-", $fechaHora[0]);
      $tiempo = explode (":", $fechaHora[1]);
      $ano=$fechas[2];
      $mes=(string)(int)$fechas[1];
      $dia=$fechas[0];
      $hora = $tiempo[0];
      $min = $tiempo[1];
      $seg = substr($tiempo[2], 0, 2);
      return $ano."-".$mes."-".$dia." ".$hora.":".$min.":00";
  }
  function fecha_hora($fecha){
	    $fechaHora = explode(" ", $fecha);
	    $fechas = explode("-", $fechaHora[0]);
	    $tiempo = explode (":", $fechaHora[1]);
	    $ano=$fechas[0];
	    $mes=(string)(int)$fechas[1];
	    $dia=$fechas[2];
	    $hora = $tiempo[0];
	    $min = $tiempo[1];
	    $seg = substr($tiempo[2], 0, 2);

	    $day = array(' ','Lun','Mar','Mie','Jue','Vie');
	    $month = array(' ','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');


	    $F .= " <span class='dia'>".$dia." </span>";
	    $F .= " <span class='mes'>".$month[$mes]." </span>";
	    $F .= " <span class='ano'>".$ano." </span>";
	    $F .= "<span class='hora'>".$hora."</span>";
	    $F .= "<span class='min'>".$min."</span>";
	    $F .= "<span class='seg'>".$seg."</span>";

		return $F;
	}

	function Fecha_Compacta($Fecha){
	    $Fechas = explode("-", $Fecha);
	    $ano=$Fechas[0];
	    $mes=(string)(int)$Fechas[1];
	    $dia=(string)(int)$Fechas[2];


	    $day = array(' ','Lun','Mar','Mie','Jue','Vie');
	    $month = array(' ','Ene','Feb','Mar','Abr','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');


	    $F .= "<span class='Dia'>".$dia." </span>";
	    $F .= "<span class='Mes'>".$month[$mes]." </span>";
	    $F .= "<span class='Ano'>".$ano." </span>";

		return $F;
	}

	function icono_modulo($id){
		$sql="select mod_icono from modulo where mod_id=$id";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_icono"];
	} //Fion nombre usuario

	function bd_modulo($id){
		$sql="select mod_bd from modulo where mod_id=$id";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_bd"];
	} //Fion nombre usuario

  function bd_prefijo_modulo($id){
    $sql="select mod_bd_prefijo from modulo where mod_id=$id";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    return $fila["mod_bd_prefijo"];
  } //Fion nombre usuario

  function bd_relaciones_modulo($id){
    $sql="select mod_bd_relaciones from modulo where mod_id=$id";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    return $fila["mod_bd_relaciones"];
  } //Fion nombre usuario

	function nombre_modulo($id){
		$sql="select mod_nombre from modulo where mod_id=$id";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_nombre"];
	} //Fion nombre usuario

  function ruta_amigable_modulo($id){
		$sql="select mod_ruta_amigable from modulo where mod_id=$id";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila["mod_ruta_amigable"];
	} //Fion nombre usuario

  function fila_modulo($id,$fila,$from,$prefijo){
		$sql="select ".$fila." from ".$from." where ".$prefijo."id=$id";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
		$filax=$this->fmt->query->obt_fila($rs);
		return $filax[$fila];
	} //Fion nombre usuario

  function cambiar_tumb($ruta){
    $arrayName = array(".jpg",".png");
    $arrayVar = array("_thumb.jpg","_thumb.png");
    $ruta = str_replace($arrayName, $arrayVar, $ruta);
    return $ruta;
  }

  function get_modulo_id ($nombre){
		$sql="select mod_id from modulo where mod_nombre='$nombre'";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num = $this->fmt->query->num_registros($rs);
		if ($num>0){
		$filax=$this->fmt->query->obt_fila($rs);
		return $filax['mod_id'];
		}else {
		return 0;
		}

	}

	function eliminar_get_id($from,$prefijo,$id){
		$sql="DELETE FROM ".$from." WHERE ".$prefijo."id='".$id."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE ".$from." AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);
		return;
	}

	function eliminar_fila($id,$from,$fila,$imprimir=0){
		$sql="DELETE FROM ".$from." WHERE ".$fila."='".$id."'";
		$this->fmt->query->consulta($sql,__METHOD__);
		$up_sqr6 = "ALTER TABLE ".$from." AUTO_INCREMENT=1";
		$this->fmt->query->consulta($up_sqr6,__METHOD__);
    if($imprimir!=0){
      echo $sql;
    }
		return;
	}

	function activar_get_id($from,$prefijo,$estado,$id){
		$this->fmt->get->validar_get ( $estado );
	    $this->fmt->get->validar_get ( $id );
	    $sql="update ".$from." set
	        ".$prefijo."activar='".$estado."' where ".$prefijo."id='".$id."'";
	    $this->fmt->query->consulta($sql,__METHOD__);
	}

  function activar_fila($from,$prefijo,$estado,$id,$imprimir=0){
	    $sql="update ".$from." set
	        ".$prefijo."activar='".$estado."' where ".$prefijo."id='".$id."'";
      if($imprimir!=0){
        echo $sql;
      }
	    $this->fmt->query->consulta($sql,__METHOD__);
	}

	function sistemas_modulos_select($label,$id,$id_rol,$class_div){
	  ?>
		<div class="form-group <?php echo $class_div; ?>">
			<label><?php echo $label; ?></label>
			<div class='arbol-cat group arbol-sis-mod'>
		<?php

		$sql="SELECT DISTINCT sis_id, sis_nombre, sis_icono,sis_color FROM sistema WHERE sis_activar=1 ORDER BY sis_orden";
		$rs=$this->fmt->query->consulta($sql,__METHOD__);
		$num = $this->fmt->query->num_registros($rs);

		if($num>0){
			for($i=0;$i<$num;$i++){
				list($fila_id, $fila_nombre, $fila_icono,$fila_color)=$this->fmt->query->obt_fila($rs);
				$aux_s="";

        $sqlsis="SELECT DISTINCT sis_rol_sis_id FROM sistema_roles WHERE sis_rol_sis_id='".$fila_id."' and sis_rol_rol_id='".$id_rol."' ";
    		$rsis=$this->fmt->query->consulta($sqlsis,__METHOD__);
        $numsis = $this->fmt->query->num_registros($rsis);
        if ($numsis>0){
          $aux_s="checked";
        }else{
          $aux_s="";
        }
        $this->fmt->query->liberar_consulta($rsis);

				echo "<label class='label-sis'><input name='inputSis[]' type='checkbox' id='sis-$fila_id' class='cbx-sis' value='$fila_id' $aux_s > <i class='$fila_icono' style='color:$fila_color' ></i><span>".$fila_nombre."</span></label>";

				$sql1="SELECT DISTINCT mod_id, mod_nombre, mod_icono,mod_color FROM modulo,sistema,sistema_modulos WHERE sis_mod_sis_id='$fila_id' and mod_id=sis_mod_mod_id and mod_activar='1' ORDER BY sis_mod_orden asc" ;
				$rs1=$this->fmt->query->consulta($sql1,__METHOD__);
				$num1 = $this->fmt->query->num_registros($rs1);

				if($num1>0){
					for($j=0;$j<$num1;$j++){
						$row=$this->fmt->query->obt_fila($rs1);
            $fila1_id = $row["mod_id"];
            $fila1_nombre = $row["mod_id"];
            $fila1_icono= $row["mod_icono"];
            $color= $row["mod_color"];

            $sqlmod="SELECT DISTINCT mod_rol_mod_id,mod_rol_permisos FROM modulo_roles WHERE mod_rol_mod_id='".$fila1_id."' and mod_rol_rol_id='".$id_rol."' ";
        		$rmod=$this->fmt->query->consulta($sqlmod,__METHOD__);
            $nummod = $this->fmt->query->num_registros($rmod);
            if ($nummod>0){
              $aux_m="checked";
              //list($mod_id,$permisos)=$this->fmt->query->obt_fila($rmod);
              $mod_id= $row["mod_rol_mod_id"];
              $permisos= $row["mod_rol_permisos"];
              if (empty($permisos)){
                $permisos ="0,0,0,0,0";
              }
            }else{
              $aux_m="";
              $permisos ="0,0,0,0,0";
            }
            $this->fmt->query->liberar_consulta($rmod);

            $permisos_a= explode(",",$permisos);

						$ver_m="";
						$act_m="";
						$agr_m="";
						$edt_m="";
						$elm_m="";

						$ver=$permisos_a[0];
						$act=$permisos_a[1];
						$agr=$permisos_a[2];
						$edt=$permisos_a[3];
						$elm=$permisos_a[4];

            if ($j%2==0){
              $franja="";
            }else{
              $franja="row-franja";
            }

							$ver= $permisos_a[0];
							if($ver==1)
								$ver_m="on";

							$act=$permisos_a[1];
							if($act==1)
								$act_m="on";

							$agr=$permisos_a[2];
							if($agr==1)
								$agr_m="on";

							$edt=$permisos_a[3];
							if($edt==1)
								$edt_m="on";

							$elm=$permisos_a[4];
							if($elm==1)
								$elm_m="on";



						echo "<div class='box-ms $franja'><label style='margin-left:20px'> <input name='inputMod[]' type='checkbox' id='mod-$fila1_id' ids='$fila_id' class='cbx-mod msis-$fila_id' value='$fila1_id' $aux_m > <i class='$fila1_icono' style='color:$color'></i><span>".$fila1_nombre."</span> </label>
            <div class='permisos'>
              <i ids='".$fila1_id."' value='$ver' id='bv-".$fila1_id."' title='Ver' nom='v' class='btn-permiso btn-ver icn-search $ver_m'></i>
              <input type='hidden' name='input_v$fila1_id' id='v-".$fila1_id."'   value='$ver' >
              <i ids='".$fila1_id."' value='$act' id='bp-".$fila1_id."' title='Activar' nom='p' class='btn-permiso btn-publicar icn-eye-open $act_m' ></i>
              <input type='hidden' name='input_p$fila1_id' id='p-".$fila1_id."'   value='$act' >
              <i ids='".$fila1_id."' value='$agr' id='ba-".$fila1_id."' title='Activar' nom='a' class='btn-permiso btn-a icn-plus $agr_m' ></i>
              <input type='hidden' name='input_a$fila1_id' id='a-".$fila1_id."'   value='$agr' >
              <i ids='".$fila1_id."'  value='$edt' id='be-".$fila1_id."' title='Editar' nom='e' class='btn-permiso btn-editar icn-pencil $edt_m' ></i>
              <input type='hidden' name='input_e$fila1_id' id='e-".$fila1_id."'    value='$edt' >
              <i ids='".$fila1_id."' value='$elm' id='bt-".$fila1_id."' title='Eliminar' nom='t' class='btn-permiso btn-trash icn-trash $elm_m' ></i>
              <input type='hidden' name='input_t$fila1_id' id='t-".$fila1_id."'   value='$elm' >
            </div>
            </div>";
  					}
  				}
  			}
  		}

		  ?>
			</div>
			<script language="JavaScript">
				$(document).ready(function() {


					$(".arbol-sis-mod :checkbox").change(function() {
						var id = $(this).val();
						if ($(this).is(':checked')) {
							$(".msis-"+id).prop('checked', true );
						}else{
							$(".msis-"+id).prop('checked', false );
						}
						if ($(".cbx-mod").is(':checked')) {
							var ids = $(this).attr('ids');
							//alert(ids);
							$("#sis-"+ids).prop('checked', true );
						}
					});

          $(".btn-permiso").click(function(event) {
            var id = $(this).attr('ids');
            var nom = $(this).attr('nom');
            var valor = $("#"+nom+"-"+id).val();
            //console.log("#"+nom+"-"+id+":"+ valor);
            $(this).toggleClass('on');
            if (valor=="0"){
              $("#"+nom+"-"+id).val("1");
            }else{
              $("#"+nom+"-"+id).val("0");
            }
          });

				});
			</script>
		</div>
		<?php
	}

	function grupos_select($label,$id,$class_div,$group){
		?>
		<div class="form-group <?php echo $class_div; ?>">
			<label><?php echo $label; ?></label>
			<div class='arbol-cat group'>
				<?php
    		$sql="SELECT grupo_id, grupo_nombre, grupo_detalle FROM grupo WHERE grupo_activar=1";
    		$rs=$this->fmt->query->consulta($sql,__METHOD__);
    		$num = $this->fmt->query->num_registros($rs);
    		if($num>0){
    			for($i=0;$i<$num;$i++){
    				$row=$this->fmt->query->obt_fila($rs);
            $fila_id = $row["grupo_id"];
            $fila_nombre = $row["grupo_nombre"];
            $fila_detalle = $row["grupo_detalle"];
    				$ck="";
    				if(in_array($fila_id, $group))
    					$ck="checked";
    				echo "<label><input name='".$id."[]' ".$ck." type='checkbox' id='sis-$fila_id' class='cbx-sis' value='$fila_id'> <i class='$fila_icono'></i><span>$fila_nombre : $fila_detalle</span></label>";
    			}
    		}
		    ?>
			</div>
		</div>
		<?php
	}

  function traer_sistemas_roles($id){
    $sql="SELECT DISTINCT sis_rol_sis_id FROM sistema_roles WHERE sis_rol_rol_id='$id' and sis_rol_sis_id not in (0) ";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila['sis_rol_sis_id'];
  }

  function traer_modulos_roles($id){
    $sql="SELECT DISTINCT mod_rol_mod_id FROM modulo_roles WHERE mod_rol_rol_id='$id' and mod_rol_mod_id not in (0) ";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if($num>0){
			for($i=0;$i<$num;$i++){
        $fila_id=$this->fmt->query->obt_fila($rs);
        $aux[$i] = $fila_id["mod_rol_mod_id"];
      }
    }
    return $aux;
  }

  function traer_modulo_rol_permisos($id_rol,$id_mod){

  	// $sql="SELECT DISTINCT rol_rel_mod_id, rol_rel_mod_p_ver, rol_rel_mod_p_activar,  rol_rel_mod_p_agregar, rol_rel_mod_p_editar, rol_rel_mod_p_eliminar FROM roles_rel WHERE rol_rel_rol_id='$id' and rol_rel_mod_id not in (0) ";
    //   $rs=$this->fmt->query->consulta($sql,__METHOD__);
    //   $num = $this->fmt->query->num_registros($rs);
    //   if($num>0){
  	// 		for($i=0;$i<$num;$i++){
    //       list($fila_id, $fila_ver, $fila_activar, $fila_agregar, $fila_editar, $fila_eliminar)=$this->fmt->query->obt_fila($rs);
    //       $aux[$fila_id]["ver"] = $fila_ver;
    //       $aux[$fila_id]["act"] = $fila_activar;
    //       $aux[$fila_id]["agr"] = $fila_agregar;
    //       $aux[$fila_id]["edt"] = $fila_editar;
    //       $aux[$fila_id]["eli"] = $fila_eliminar;
    //     }
    //   }
    //

    $sql="SELECT DISTINCT mod_rol_permisos FROM modulo_roles,rol WHERE mod_rol_rol_id='$id_rol' and mod_rol_mod_id = '$id_mod' ";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if($num>0){
        $fila_id=$this->fmt->query->obt_fila($rs);
        $aux = $fila_id["mod_rol_permisos"];
    }
    return $aux;
  }

  function actualizar_tabla($from,$filas,$valores_post,$print_consulta=0){
    $filas = str_replace("[\n|\r|\n\r|\t| ]","",$filas);
    $valores_post = eregi_replace("[\n|\r|\n\r|\t| ]","",$valores_post);
    $filas1= explode(',',$filas);
    $valores_post1= explode(',',$valores_post);
    //var_dump($valores_post1);
    $num_filas = count($filas1);
    $num_post = count($valores_post1);
    $valores ="";
        if ($num_filas==$num_post){
          for ($i=1; $i < $num_filas; $i++) {
            $x=$valores_post1[$i];
            $y= $_POST[$x];
            if ( $i==$num_filas-1){ $aux=""; }else{ $aux=","; };
            $valores .=$filas1[$i].'="'.$y.'"'.$aux;
          }
        }else{
          $this->fmt->error->error_parametrizacion();
        }

        $campo_id=$filas1[0];
        $f=$valores_post1[0];
        $id= $_POST[$f];
    		$sql="UPDATE $from SET ".$valores." WHERE ".$campo_id."='".$id."'";
        if($print_consulta==1){
          echo $sql;
        }
  			$this->fmt->query->consulta($sql,__METHOD__);
  	}

  function ingresar_tabla($from,$filas,$valores_post){
    $filas = str_replace("[\n|\r|\n\r|\t| ]","",$filas);
    $valores_post = eregi_replace("[\n|\r|\n\r|\t| ]","",$valores_post);
    $filas1= explode(',',$filas);
    $valores_post1= explode(',',$valores_post);
    //var_dump($valores_post1);
    //echo $valores_post;
    $num_filas = count($filas1);
    $num_post = count($valores_post1);
    $valores ="";
        if ($num_filas==$num_post){
          for ($i=0; $i < $num_filas; $i++) {
            $x=$valores_post1[$i];
            $y= $_POST[$x];
            if ( $i==$num_filas-1){
              $aux="";
              $d= explode('=',$valores_post1[$i]);
              if ($d[0]=='inputActivar'){
                $y=$d[1];
              }
            }else{
              $aux=",";
            };
            $valores .='"'.$y.'"'.$aux;
          }
        }else{
          $this->fmt->error->error_parametrizacion();
        }

        $sql="insert into $from (".$filas.") values (".$valores.")";
        $this->fmt->query->consulta($sql,__METHOD__);
  }

  function traer_clima($lugar){
  	$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
  	$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$lugar.'")';
  	$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&u=c&format=json";
  			    // Make call with cURL
  	$session = curl_init($yql_query_url);
  	curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
  	$json = curl_exec($session);
  	    // Convert JSON to PHP object
  	$phpObj =  json_decode($json);

      $temp=$phpObj->query->results->channel->item->condition->temp;
      $temp_max=$phpObj->query->results->channel->item->forecast[0]->high;
      if($temp_max==0)
      	$temp_max = $temp;
      $temp_min=$phpObj->query->results->channel->item->forecast[0]->low;
  	$data["code"]=$phpObj->query->results->channel->item->condition->code;
  	$data["actual"]=round(($temp-32)/1.8000);
  	$data["max"]=round(($temp_max-32)/1.8000);
  	$data["min"]=round(($temp_min-32)/1.8000);
  	$data["humedad"]=$phpObj->query->results->channel->atmosphere->humidity;
  	$data["fecha"]=$phpObj->query->results->channel->item->forecast[0]->date;
  	return $data;
  }

  function id_padre_modulo($id){
      $sqlx="SELECT mod_id_padre FROM modulo WHERE mod_id='".$id."' and mod_activar=1";
      $rs=$this->fmt->query->consulta($sqlx,__METHOD__);
      $num = $this->fmt->query->num_registros($rs);
      $fila = $this->fmt->query->obt_fila($rs);
      return $fila["mod_id_padre"];
  }

  function botones_hijos_modulos($id_mod){
    $id_padre=$this->id_padre_modulo($id_mod);
    //echo "padre:".$id_padre;
    if (!empty($id_padre) && ($id_padre!=0)){
      $sql="SELECT mod_id,mod_nombre, mod_icono, mod_url, mod_ruta_amigable FROM modulo WHERE mod_id='$id_padre' and mod_activar=1";
      $rs=$this->fmt->query->consulta($sql,__METHOD__);
      $num = $this->fmt->query->num_registros($rs);
      if($num>0){
  			for($i=0;$i<$num;$i++){
          $row=$this->fmt->query->obt_fila($rs);
          $fila_id =$row["mod_id"];
          $fila_nombre =$row["mod_nombre"];
          $fila_icono =$row["mod_icono"];
          $fila_url =$row["mod_url"];
          $url =$row["mod_ruta_amigable"];
          //$vars = "busqueda";
          //$btn .= $this->fmt->class_pagina->crear_btn_m($fila_nombre,$fila_icono,$fila_nombre,"btn btn-link btn-menu-ajax",$fila_id,$vars);
          $link = _RUTA_WEB."dashboard/".$url;
          $btn .= $this->fmt->class_pagina->crear_btn($link,"btn btn-link",$fila_icono,$fila_nombre);
        }
      }
    }
    $sql2="SELECT mod_id,mod_nombre, mod_icono, mod_url,mod_ruta_amigable FROM modulo WHERE mod_id_padre='$id_mod' and mod_activar=1";
      $rs2=$this->fmt->query->consulta($sql2,__METHOD__);
      $num2 = $this->fmt->query->num_registros($rs2);
      if($num2>0){
  			for($i=0;$i<$num2;$i++){
          $row=$this->fmt->query->obt_fila($rs2);
          $fila_id =$row["mod_id"];
          $fila_nombre =$row["mod_nombre"];
          $fila_icono =$row["mod_icono"];
          $fila_url =$row["mod_url"];
          $url =$row["mod_ruta_amigable"];
          //$vars = "busqueda";
          //$btn .= $this->fmt->class_pagina->crear_btn_m($fila_nombre,$fila_icono,$fila_nombre,"btn btn-link btn-menu-ajax",$fila_id,$vars); //$nom,$icon,$title,$clase,$id_mod,$vars{ //tarea,id,estado}
          $link = _RUTA_WEB."dashboard/".$url;
          $btn .= $this->fmt->class_pagina->crear_btn($link,"btn btn-link",$fila_icono,$fila_nombre);
        }
      }
      return $btn;
  }

  function opciones_modulos($id_sis){
    $sql ="SELECT mod_id, mod_nombre, mod_descripcion, mod_icono, mod_color FROM modulo where mod_activar='1' and mod_tipo<>'2'";
    $rs = $this->fmt->query -> consulta($sql,__METHOD__);
    $num = $this->fmt->query -> num_registros($rs);
    $ck="";
    if ($num > 0){
      for ( $i=0; $i < $num; $i++){
        $row  = $this->fmt->query->obt_fila($rs);
        $fila_id =$row["mod_id"];
        $fila_nombre=$row["mod_nombre"];
        $descripcion=$row["mod_descripcion"];
        $fila_icono=$row["mod_icono"];
        $fila_color=$row["mod_color"];
        if(!empty($id_sis)){
          $sql_mod ="SELECT sis_mod_mod_id FROM sistema_modulos WHERE sis_mod_sis_id='$id_sis' and sis_mod_mod_id='$fila_id'";
          $rs_mod = $this->fmt->query -> consulta($sql_mod,__METHOD___);
          $fila_mod = $this->fmt->query -> obt_fila($rs_mod);
          if ($fila_mod['sis_mod_mod_id']==$fila_id) { $ck="checked"; }else{ $ck=""; }
          $this->fmt->query->liberar_consulta($rs_mod);
        }
        $des ="";
        if (!empty($descripcion)){
          $des = "<span class='text-ref'>(".$descripcion.")<span>";
        }
        $aux .= '<div class="checkbox">';
        $aux .= '<label>';
        $aux .= '<input type="checkbox" name="inputModulo[]" value="'.$fila_id.'" '.$ck.'>';
        $aux .= '<i class="'.$fila_icono.'" style="color:'.$fila_color.'"></i> '.$fila_nombre." $des ";
        $aux .= '</label>';
        $aux .= '</div>';
      }
    } else {
      $aux =" no existen modulos registrados";
    }
    return $aux;
  }

  function recortar_texto($texto,$cantidad="0"){
    $cn=$this->cantidad_caracteres($texto);
    if($cn>$cantidad){
      $aux="...";
    }else{
      $aux="";
    }
    return substr($texto, 0, $cantidad).$aux;
  }


  function cantidad_caracteres($texto){
    return strlen($texto);
  }

}
?>
