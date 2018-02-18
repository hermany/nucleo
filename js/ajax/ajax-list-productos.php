<?php
  $id= $_POST["inputId"];
  echo '<div class="finder">';
  echo ' <div class="finder-head">';
  echo '   <div class="group-tabs">';
  echo '     <div class="tab">';
  echo '       <label class="title title-productos">PRODUCTOS</label>';
  echo '       <span class="group group-tabs-productos">';
  echo '         <a class="category active" id="tab-todos" idtab="todos">Todos</a>';
  echo '       </span>';
  echo '     </div>'; // tab
  echo '   </div>'; // group-tab
  echo ' </div>'; // finder-head
  echo ' <div class="finder-body">';
  echo '   <div class="body-todos tbody">';
  echo '    <div class="box-buscador box-buscador-producto">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar-producto" tipo="" type="text" class="form-control" placeholder="Buscar producto">';
  echo '    </div>';
  echo '   <div class="body-productos">';
$sql="select mod_prod_id, mod_prod_nombre, mod_prod_imagen, mod_prod_activar from mod_productos ORDER BY mod_prod_id desc";
$rs =$fmt->query->consulta($sql);
$num=$fmt->query->num_registros($rs);
if($num>0){
  for($i=0;$i<$num;$i++){
    $row=$fmt->query->obt_fila($rs);
    $fila_id=$row["mod_prod_id"];
    $fila_nombre=$row["mod_prod_nombre"];
    $fila_imagen=$row["mod_prod_imagen"];
    $imgx=_RUTA_IMAGES.$fmt->archivos->convertir_url_mini( $fila_imagen );
    $fila_activar=$row["mod_prod_activar"];
    echo "<div class='item' id='item-$fila_id' nombre='$fila_nombre' iditem='$fila_id'><span class='imagen' style='background:url($imgx)no-repeat center center'></span><span class='nombre'>$fila_nombre</span> <i class='icn icn-checkmark-circle'></i></div>";
  }
}

  echo '     </div>';
  echo ' <div class="finder-footer">';
  echo '   <a class="btn btn-full btn-list-cancelar">Cancelar</a>';
  echo '   <a class="btn btn-success btn-list-insertar">Insertar</a>';
  echo ' </div>';// fin footer
  echo '   </div>';
  echo ' </div>'; // finder-head
?>

<script type="text/javascript">
  $(document).ready(function() {
    $(".item").click(function(event) {
      var id = $(this).attr('iditem');
      $("#item-"+id).toggleClass('seleccionar');
    });
    $(".btn-list-cancelar").click(function(event) {
      $(".modal-list .modal-list-inner").html("");
      $(".modal-list").removeClass('on');
    });
    $(".btn-list-insertar").click(function(event) {
      $(".body-productos .seleccionar").each(function(i) {
        var id = $(this).attr('iditem');
        var nombre = $(this).attr('nombre');
        //console.log(id);
        $("#sortable-prod").prepend('<div class="item-producto" id="item-prod-'+id+'"><i class="icn icn-reorder"></i> '+nombre+'<input type="hidden" id="<?php echo $id; ?>[]" name="<?php echo $id; ?>[]" value="'+ i +'" /><i class="btn icn icn-close" iditem="'+id+'"></i></div>');
        $(".btn.icn-close").click(function(event) {
          var id = $(this).attr('iditem');
          $("#item-prod-"+id).remove();
        });
      });

      $(".modal-list .modal-list-inner").html("");
      $(".modal-list").removeClass('on');
    });
  });
</script>
