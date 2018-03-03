<?php
if(isset($_POST["action"])){
	$accion=$_POST["action"];
	if($accion=="contenedor"){
		$pla=$_POST["pla"];
		$cat=$_POST["cat"];
		if (!empty($pla)){
			$sql="SELECT contenedor_cont_id FROM contenedor_plantilla WHERE plantilla_pla_id=$pla order by contenedor_cont_id asc";
				$rs=$fmt->query->consulta($sql,__METHOD__);
				while($filax=$fmt->query->obt_fila($rs)){
					$conte = $filax["contenedor_cont_id"];
					traercontenedor($cat,$pla,$conte,$fmt);
				}
			?>
			<script>
			$(function(){
					$('ul.pub-cont').sortable({
					    items : ':not(.ui-state-disabled)'
					});

			    $( "ul.pub-all, ul.pub-cont" ).sortable({
			      connectWith: ".connectedSortable"
			    }).disableSelection();

			    $(".icn-eye").click(function(){
				   var at = $(this).attr("act");
				   var pub = $(this).attr("pub");

				   var act;
				   if(at=="1"){
					   act = 0;
					   $(this).addClass("icn-eye-close");
						 $(this).removeClass("icn-eye-open");
				   }
				   else{
					   act = 1;
						 $(this).addClass("icn-eye-open");
					   $(this).removeClass("icn-eye-close");
				   }
				   $("#pub-"+pub).attr("act", act);
				   $("#pub-c-"+pub).attr("act", act);
				   $(this).attr("act", act);
			    });

			  });
			  </script>
			<?php
		}

	}

	if($accion=="actualizar"){
		$pla=$_POST["pla"];
		$cat=$_POST["cat"];
		$cont=$_POST["cont"];

		$sqld="DELETE FROM publicacion_rel WHERE pubrel_cat_id='$cat' and pubrel_pla_id='$pla'";
	    $fmt->query->consulta($sqld,__METHOD__);
	    $up_sqr6 = "ALTER TABLE publicacion_rel AUTO_INCREMENT=1";
	    $fmt->query->consulta($up_sqr6);

		$num = count($cont);
		for($i=0;$i<$num;$i++){
			$con = $cont[$i];
			$pubs=$_POST["pub".$con];
			$acts=$_POST["act".$con];
			$num_p = count($pubs);
			for($j=0;$j<$num_p;$j++){
				$pub=$pubs[$j];
				$act=$acts[$j];
				$sql="insert into publicacion_rel (pubrel_cat_id, pubrel_pla_id, pubrel_cont_id, pubrel_pub_id, pubrel_activar, pubrel_orden) values ('$cat','$pla','$con','$pub','$act','$j')";
				$rs=$fmt->query->consulta($sql,__METHOD__);
				//echo $sql.":";
			}
		}
		//echo $sqld.":".$cat.":".$pla.":".$num;
	} // fin actualizar
} // fin action

function traercontenedor($cat,$pla,$cont,$fmt){
	$sql="SELECT cont_nombre FROM contenedor WHERE cont_id=$cont";
	$rs=$fmt->query->consulta($sql,__METHOD__);
	while($filax=$fmt->query->obt_fila($rs)){
		$nombre = $filax["cont_nombre"];

		echo '<ul id="cont-'.$cont.'" cont="'.$cont.'" class="pub-cont connectedSortable" >';
		echo '<h3 class="ui-state-disabled">'.$nombre.'</h3>';
		traerpublicaciones($cat,$pla,$cont,$fmt);
		traerhijoscontenedor($cat,$pla,$cont,$fmt);
		echo "</ul>";
	}
}
function traerhijoscontenedor($cat,$pla,$cont,$fmt){
	$sql="SELECT cont_id FROM contenedor WHERE cont_id_padre=$cont order by cont_orden asc";
	$rs=$fmt->query->consulta($sql,__METHOD__);
	while($filax=$fmt->query->obt_fila($rs)){
		$cont=$filax["cont_id"];
		traercontenedor($cat,$pla,$cont,$fmt);
	}
}
function traerpublicaciones($cat,$pla,$cont,$fmt){
	$sql="SELECT pubrel_pub_id, pubrel_activar, pub_nombre, pub_imagen FROM publicacion_rel, publicacion WHERE pubrel_pub_id=pub_id and pubrel_cat_id=$cat and pubrel_pla_id=$pla and pubrel_cont_id=$cont order by pubrel_orden asc";

	$rs=$fmt->query->consulta($sql,__METHOD__);
	while($filax=$fmt->query->obt_fila($rs)){
		$nombre = $filax["pub_nombre"];
		$act = $filax["pubrel_activar"];
		$imagen = $filax["pub_imagen"];
		$pub = $filax["pubrel_pub_id"];
		$cls="";
		if($act=="0"){
			$cls="icn-eye-close";
		}else{
			$cls="icn-eye-open";
		}

		if(empty($imagen)){
			$im = _RUTA_WEB_NUCLEO."images/pub-icon.png";
		}
		else{
			$im = _RUTA_WEB.$imagen;
		}
		echo "<li id='pub-c-".$pub."' pub='".$pub."' act='".$act."' class='ui-state'><i class='ui-state-disabled icn-move'></i><span class='ui-state-disabled'>".$nombre;
		echo '</span><span class="box-accion pull-right ui-state-disabled"><i pub="'.$pub.'" class="icn icn-pencil btn-editar-pub ui-state-disabled"></i>';
		echo '<i pub="'.$pub.'" act="'.$act.'" class="ui-state-disabled icn icn-eye '.$cls.'"></i>';
		echo '<i pub="'.$pub.'" class="ui-state-disabled icn icn-trash" aria-hidden="true"></i></span>';
		echo "</li>";
	}
}
?>
