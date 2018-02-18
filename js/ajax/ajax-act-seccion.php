<?php
require_once("../clases/class-constructor.php");
$fmt = new CONSTRUCTOR();
if(isset($_POST["action"])){
	$accion=$_POST["action"];
	if($accion=="pestana"){
		$valor=$_POST["valor"];
		if (!empty($valor)){
			$sql="SELECT DISTINCT mod_pro_pes_pes_id FROM mod_productos_pestana
 WHERE mod_pro_pes_pro_id=$valor ";
				$rs=$fmt->query->consulta($sql,__METHOD__);
				$num =$fmt->query->num_registros($rs);
				if ($num>0){
					for($i=0;$i<$num;$i++){
						$filax=$fmt->query->obt_fila($rs);
						$valor_ids[$i] = $filax["mod_pro_pes_pes_id"];
					}

				}
			}
		require_once(_RUTA_HOST."modulos/config-ec/config-ec.class.php");
		$form_e =new CONFIG_EC($fmt);
		$form_e->busqueda_seleccion('modal',$valor_ids);
	}

	if($accion=="documento"){
		$valor=$_POST["valor"];
		if (!empty($valor)){
				$sql="SELECT DISTINCT mod_prod_rel_doc_id FROM mod_productos_rel WHERE mod_prod_rel_prod_id=$valor ";
				$rs=$fmt->query->consulta($sql,__METHOD__);
				$num =$fmt->query->num_registros($rs);
				if ($num>0){
					for($i=0;$i<$num;$i++){
						$filax=$fmt->query->obt_fila($rs);
						$valor_ids[$i] = $filax["mod_prod_rel_doc_id"];
					}
				}
		}
		require_once(_RUTA_HOST."modulos/documentos/documentos.class.php");
		$form =new DOCUMENTOS($fmt);
		$form->busqueda_seleccion('modal',$valor_ids);
	}
}
?>
