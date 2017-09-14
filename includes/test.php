<?php
require_once ("../../next/config.php");
header("Content-Type: text/html;charset=utf-8");
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR;
echo $fmt->header->js_jquery();
 ?>
<script type="text/javascript" language="javascript" src="<? echo _RUTA_WEB_NUCLEO; ?>js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/dataTables.bootstrap.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/estilos.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/theme.adm.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/icon-font.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/datatables.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<? echo _RUTA_WEB_NUCLEO;?>css/datatables-theme.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
  $(document).ready( function() {
    $('#tabla_id').DataTable({
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
            "bSortable": true

    });

    $('#tabla_id').on('click','a.btn-accion', function (e) {
      e.preventDefault();
      alert ($(this).attr("idmod"));
    });
  });
</script>

<table class="table table-hover" id="tabla_id">
  <thead>
    <tr>
      <th>id</th>
      <th>Nombre del modulo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $sql="select mod_prod_id, mod_prod_nombre, mod_prod_imagen,  mod_prod_id_dominio, mod_prod_activar from mod_productos where mod_prod_activar=1 ORDER BY mod_prod_id desc";
      $rs =$fmt->query->consulta($sql);
      $num=$fmt->query->num_registros($rs);
      if($num>0){
        for($i=0;$i<$num;$i++){
          list($fila_id,$fila_nombre,$fila_imagen,$fila_dominio,$fila_activar)=$fmt->query->obt_fila($rs);
          ?>
            <tr>
              <td><?php echo $fila_id; ?></td>
              <td><?php echo $fila_nombre; ?></td>
              <td>
                <a class="btn-accion" id="id-<?php echo $fila_id; ?>" idmod="<?php echo $fila_id; ?>" href="javascript:void(0)">EDIT</a>
                <a class="btn-accion" idmod="2" href="#">DELETE</a>
              </td>
            </tr>
          <?php
        }
      }
     ?>

  </tbody>
</table>
