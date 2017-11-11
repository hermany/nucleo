<?php
header("Content-Type: text/html;charset=utf-8");

class PEDIDOS{

	var $fmt;
	var $id_mod;
	var $id_item;
	var $id_estado;
	var $ruta_modulo;

	function PEDIDOS($fmt,$id_mod=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_mod = $id_mod;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
		$this->ruta_modulo= _RUTA_WEB."dashboard/".$this->fmt->class_modulo->ruta_amigable_modulo($id_mod);
	}

	function busqueda(){

    require_once(_RUTA_NUCLEO."modulos/crm/clientes.class.php");
    require_once(_RUTA_NUCLEO."modulos/adm/sucursales.class.php");
    $cliente = new CLIENTES($this->fmt);
    $sucursales = new SUCURSALES($this->fmt);

    echo '<link rel="stylesheet" href="'._RUTA_WEB_NUCLEO.'css/m-pedidos.css?reload" rel="stylesheet" type="text/css">';

    $this->fmt->class_pagina->crear_head( $this->id_mod, $botones);
    $this->fmt->class_pagina->head_mod();
    $botones = $this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","Nuevo pedido","btn btn-primary btn-menu-ajax btn-new btn-small",$this->id_mod,"form_nuevo");  //$nom,$icon,$title,$clase,$id_mod,$vars
    $this->fmt->class_pagina->head_modulo_inner("Lista de Pedidos E-commerce", $botones); // bd, id modulo, botones
    $this->fmt->form->head_table("table_id");
    $this->fmt->form->thead_table('Id:Pedido:Estado:Acciones');
    $this->fmt->form->tbody_table_open();
    $consulta = "SELECT mod_ped_cli_id,
    mod_ped_cli_id_cli,
    mod_ped_cli_tipo,
    mod_ped_cli_sucursal,
    mod_ped_cli_nro,
    mod_ped_cli_fecha_registro,
    mod_ped_cli_fecha_aprovacion,
    mod_ped_cli_fecha_entrega,
    mod_ped_cli_estado FROM mod_pedidos_clientes";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id=$row["mod_ped_cli_id"];
        $id_cliente=$row["mod_ped_cli_id_cli"];
        $tipo_pedido=$row["mod_ped_cli_tipo"];
        $sucursal=$row["mod_ped_cli_sucursal"];
        $num_pedido=$row["mod_ped_cli_nro"];
        $fr=$row["mod_ped_cli_fecha_registro"];
        $fa=$row["mod_ped_cli_fecha_aprovacion"];
        $fe=$row["mod_ped_cli_fecha_entrega"];
        $estado=$row["mod_ped_cli_estado"];
        echo "<tr class='row row-".$fila_id."'>";
        echo "  <td class='col-id'>$fila_id</td>";
        echo "  <td><div class='col-block'><strong>Pedido: </strong>";
        echo    $cliente->nombre_cliente($id_cliente);
        echo "    &nbsp;&nbsp; <strong> Tipo de pedido: </strong>  ".$this->nombre_tipo_pedido($tipo_pedido);
        echo "    &nbsp;&nbsp; <strong> Sucursal Salida: </strong>  ".$sucursales->nombre_sucursal($sucursal)." </div>";
                $this->traer_pedido_productos($this->fmt,$fila_id,$i);
        echo "  </td>";
        echo "  <td>";
        echo     $this->nombre_estado_pedido($estado);
        echo "  </td>";
        echo '  <td class="col-acciones acciones">';
        echo $this->fmt->class_pagina->crear_btn_m("","icn-search","ver ".$fila_id,"btn btn-accion btn-m-ver ",$this->id_mod,"form_ver,".$fila_id);
        echo $this->fmt->class_pagina->crear_btn_m("","icn-pencil","editar ".$fila_id,"btn btn-accion btn-m-editar ",$this->id_mod,"form_editar,".$fila_id);
        echo $this->fmt->class_pagina->crear_btn_m("","icn-trash","eliminar ".$fila_id,"btn btn-accion btn-fila-eliminar",$this->id_mod,"eliminar,".$fila_id.",".$cliente->nombre_cliente($id_cliente));
        echo '  </td>';
        echo "</tr>";

      }
    }
    $this->fmt->form->tbody_table_close();
    $this->fmt->form->footer_table();
    ?>
      <script type="text/javascript">
        $(document).ready(function() {
          $(".btn-m-ver").click(function(event) {
            var variables = $(this).attr("vars");
            var ver = variables.split(",");
            //console.log(ver[1]);
            $(".group-panel-"+ver[1]).toggleClass('on');
          });
        });
      </script>
    <?php
    $this->fmt->class_modulo->script_accion_modulo();
		$this->fmt->class_modulo->script_table("table_id",$this->id_mod,"desc","0","25",true);
    $this->fmt->class_pagina->footer_mod();
  }

  function nombre_estado_pedido($estado){
    $consulta = "SELECT mod_ped_est_nombre FROM mod_pedidos_estados WHERE mod_ped_est_id=$estado";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    $fila = $this->fmt->query->obt_fila($rs);
    if ($fila["mod_ped_est_nombre"]){
      return  $fila["mod_ped_est_nombre"];
    }else{
      return  "sin estado";
    }
    $this->fmt->query->liberar_consulta($rs);
  }
  function nombre_tipo_pedido($estado){
    $consulta = "SELECT mod_ped_tipo_nombre FROM mod_pedidos_tipo WHERE mod_ped_tipo_id=$estado";
    $rs =$this->fmt->query->consulta($consulta);
    $num=$this->fmt->query->num_registros($rs);
    $fila = $this->fmt->query->obt_fila($rs);
    if ($fila["mod_ped_tipo_nombre"]){
      return  $fila["mod_ped_tipo_nombre"];
    }else{
      return  "sin nombre";
    }
    $this->fmt->query->liberar_consulta($rs);
  }

  function traer_pedido_productos($fmt,$id_pedido,$n){
      require_once(_RUTA_NUCLEO."modulos/finanzas/finanzas.class.php");
      $finanzas = new FINANZAS($fmt);

      $consulta = "SELECT mod_prod_id, mod_prod_nombre,mod_ped_prod_precio_unidad,mod_ped_prod_cantidad FROM mod_pedidos_productos, mod_productos, mod_pedidos_clientes WHERE mod_ped_prod_ped_id=$id_pedido and mod_ped_prod_prod_id=mod_prod_id";
      $rs =$fmt->query->consulta($consulta);
      $num=$fmt->query->num_registros($rs);
      if($num>0){
        $tn=""; if ($n!="0"){ $tn="style='display:none'"; }
        echo "<div class='group-panel group-panel-$id_pedido' $tn >";
        echo " <label><strong>Lista del pedido</strong></label>";
        echo "  <table class='table'>";
          echo "<tr>";
          echo "  <th>Producto</th>";
          echo "  <th class='align-right'>Precio/u Bs.</th>";
          echo "  <th class='align-right'>Precio Bs.</th>";
          echo "  <th class='align-right'>Cantidad</th>";
          echo "</tr>";
        $suma=0;
        for($i=0;$i<$num;$i++){
          list($fila_id,$prod_nombre,$precio,$cantidad)=$fmt->query->obt_fila($rs);
          echo "<tr>";
          echo "  <td>".$prod_nombre."</td>";
          echo "  <td class='align-right'>".$finanzas->convertir_moneda($precio)."</td>";
          echo "  <td class='align-right'>".$finanzas->convertir_moneda($precio * $cantidad)."</td>";
          echo "  <td class='align-right'>".$cantidad."</td>";
          echo "</td>";
          $suma = $suma + ( $precio * $cantidad );
        }
        echo "  </table>";
        echo "  <div class='hr'></div>";
        echo "  <div class='row-top align-right'>";
        echo "    <label>SubTotal</label> ";
        //echo $suma;
        echo "<h3  class='sub-precio'>Bs. ".$finanzas->convertir_moneda($suma)."</h3>";
        echo "  </div>";
        echo "</div>";
      }
  }

}// fin clase
