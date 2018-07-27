<?php
header('Content-Type: text/html; charset=utf-8');

class CATEGORIA{

  var $fmt;
  var $id_familia;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function categoria_id_tipo($cat){
	  $this->fmt->get->validar_get($cat);
	  $consulta = "SELECT cat_tipo FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $tipo=$fila["cat_tipo"];
    return $tipo;
  }

  function ruta_amigable($cat){
    $this->fmt->get->validar_get($cat);
    $consulta = "SELECT cat_ruta_amigable FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $tipo=$fila["cat_ruta_amigable"];
    return $tipo;
  }    
  function color_categoria($cat){
    $this->fmt->get->validar_get($cat);
    $consulta = "SELECT cat_color FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["cat_color"];
  }  

  function ruta_amigable_padre($cat){
    $cat_padre = $this->categoria_id_padre($cat);
    $consulta = "SELECT cat_ruta_amigable FROM categoria WHERE cat_id='$cat_padre' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $tipo=$fila["cat_ruta_amigable"];
    return $tipo;
  }

  function categoria_id_padre($cat){
    $this->fmt->get->validar_get($cat);
    $consulta = "SELECT cat_id_padre FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $id=$fila["cat_id_padre"];
    return $id;
  }  

  function tipo_categoria($cat){
	  $this->fmt->get->validar_get($cat);
	  $consulta = "SELECT cat_tipo FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $id=$fila["cat_tipo"];
    return $id;
  }

  function id_padre($cat,$from,$prefijo){
    $this->fmt->get->validar_get($cat);
    $consulta = "SELECT ".$prefijo."id_padre FROM ".$from." WHERE ".$prefijo."id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $id=$fila[$prefijo."id_padre"];
    return $id;
  }

  function nombre_categoria($cat){
	  $this->fmt->get->validar_get($cat);
	  if ($cat==0){
	    return 'raiz (0)';
	  }
	  $consulta = "SELECT cat_nombre FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $nombre=$fila["cat_nombre"];
    return $nombre;
  }

  function metas($cat){
  	$consulta="SELECT cat_meta FROM categoria WHERE cat_id=$cat ";
  	$rs = $this->fmt->query->consulta($consulta,__METHOD__);
  	$fila = $this->fmt->query->obt_fila($rs);
  	$meta=$fila["cat_meta"];

  	if (!empty($meta)){
      	return $meta;
    	}else{
      	$est=false;
      	$this->tiene_padre_sitio($cat,$est);
      	if($est!=false){
	      	echo $est;
      	}else{
	      	return false;
      	}
      	//echo $this->nombre_categoria($cat);

  	}
	}

	function padre_sitio($cat){

	}


	function tiene_padre_sitio($cat,&$est){
	    $consulta="SELECT cat_id_padre, cat_tipo FROM categoria WHERE cat_id=".$cat ;
	    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
	    $num = $this->fmt->query->num_registros($rs);
	    if ($num > 0){
		  $fila = $this->fmt->query->obt_fila($rs);

	      if ($fila["cat_tipo"]=="2"){
		    $est =  $cat;
	      }else{
		      $this->tiene_padre_sitio($fila["cat_id_padre"],$est);
	      }
	    }else{
	       $est = false;
    	}
	}

  function cat_imagen($cat){
	  $consulta = "SELECT cat_imagen FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $nombre=$fila["cat_imagen"];
    if($nombre=="")
    	$nombre="sitios/intranet/images/default-noticias-cat.png";
    return $nombre;
  }

  function categorias_noticias($not_id,$orden="asc",$tipo_orden="'noticia_categoria','not_cat_orden'"){
    if($tipo_orden=="fecha"){
      $tipo_orden = "'categoria','cat_fecha'";
    }
    if($tipo_orden=="id"){
      $tipo_orden = "'categoria','cat_id'";
    }
    $consulta="SELECT cat_nombre,cat_ruta_amigable FROM categoria,nota_categorias,nota WHERE cat_id=not_cat_cat_id and not_id=not_cat_not_id and not_id=$not_id and not_activar=1 ORDER BY $tipo_orden $orden" ;
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if ($num > 0){
      for ($i=0; $i < $num ; $i++) {
        //list($nombre,$ruta) = $this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $nombre = $row["cat_nombre"];
        $ruta= $row["cat_ruta_amigable"];
        $cat_not .= "<li class='item-cat item-cat-$ruta'><a href='"._RUTA_WEB.$ruta."'>".$nombre."</a></li>";
      }
      return "<ul>".$cat_not."</ul>";
    }

    $this->fmt->query->liberar_consulta($rs,__METHOD__);
  }

  function descripcion($cat,$from,$prefijo){
	  $this->fmt->get->validar_get($cat);
	  if ($cat==0){
    	return 'sin descripción';
  	}
	  $consulta = "SELECT ".$prefijo."descripcion FROM ".$from." WHERE ".$prefijo."id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $nombre=$fila[$prefijo."descripcion"];
    return $nombre;
  }

  function descripcion_cat($cat){
    $consulta = "SELECT cat_descripcion FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $id=$fila["cat_descripcion"];
    return $id;
  }

  function nombre($cat,$from,$prefijo){
    $this->fmt->get->validar_get($cat);
    if ($cat==0){
    return 'raiz (0)';
    }
    $consulta = "SELECT ".$prefijo."nombre FROM ".$from." WHERE ".$prefijo."id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $nombre=$fila[$prefijo."nombre"];
    return $nombre;
  }



  function categoria_padre_sitio($cat){
  	//$this->fmt->get->validar_get($cat);
  	$cat_padre = $this->categoria_id_padre($cat);
  	$cat_tipo = $this->categoria_id_tipo($cat_padre);
  	if ($cat_tipo=='2'){
  		return $cat_padre;
  	}else {
  	  $this->categoria_padre_sitio($cat_padre);
  	}
  	//return $cat_padre;
  }

  function favicon_categoria($cat){
	  $this->fmt->get->validar_get($cat);
	  $consulta = "SELECT cat_favicon FROM categoria WHERE cat_id='$cat' ";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["cat_favicon"];
  }

  function arbol_editable($from,$prefijo,$id_mod){
    echo "<div class='arbol'>";

    $consulta = "SELECT ".$prefijo."id, ".$prefijo."nombre FROM ".$from." WHERE ".$prefijo."id_padre='0' ORDER BY ".$prefijo."orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);

    if($num>0){
      if ($num>1){
        $tr = "<i class='icn icn-sort btn-i btn-ordenar-i' id_padre='0' ></i>";
      }else{
        $tr="";
      }
      echo "<div class='arbol-nuevo'><a class='btn-nuevo-i' cat='0'><i class='icn-plus'></i> nuevo</a> $tr</div>";
      for($i=0;$i<$num;$i++){
        $row= $this->fmt->query->obt_fila($rs);
        $fila_id = $row[$prefijo."id"];
        $fila_nombre = $row[$prefijo."nombre"];
        //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        if ($i==$num-1) { $aux = 'icn-point-4'; } else { $aux = 'icn-point-1'; }
        echo "<div class='nodo' id='nodo-$fila_id'><i class='".$aux." i-nodo'></i> ".$fila_nombre;
        $this->accion($fila_id,$from,$prefijo,$id_mod);
        echo "</div>";
        if ($this->tiene_hijos($fila_id,$from,$prefijo)){
          $this->hijos($fila_id,'0',$from,$prefijo,$id_mod);
        }
      }
    }else{
      echo "<div class='arbol-nuevo'><a href='"._RUTA_WEB.$url_modulo."' ><i class='icn-plus'></i> nuevo</a></div>";
    }
    echo "</div>";
    return;
  }

  function arbol_editable_nodo($from,$prefijo,$raiz,$id_mod){
    echo "<div class='arbol'>";
  	if (empty($raiz)){
      $raiz=0;
    }

    $consulta = "SELECT ".$prefijo."id, ".$prefijo."nombre FROM ".$from." WHERE ".$prefijo."id='".$raiz."' ORDER BY ".$prefijo."orden asc";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);

    if($num>0){
        //echo "<div class='arbol-nuevo'><a href='"._RUTA_WEB.$url_modulo."'><i class='icn-plus'></i> nuevo</a></div>";
        for($i=0;$i<$num;$i++){
          $row= $this->fmt->query->obt_fila($rs);
          //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
          $fila_id = $row[$prefijo."id"];
          $fila_nombre = $row[$prefijo."nombre"];
          if ($i==$num-1) { $aux = 'icn-point-4'; } else { $aux = 'icn-point-1'; }
          echo "<div class='nodo' id='nodo-$fila_id' ><i class='".$aux." i-nodo'></i> ".$fila_nombre;
          $this->accion($fila_id,$from,$prefijo,$id_mod);
          echo "</div>";
          if ($this->tiene_hijos($fila_id,$from,$prefijo)){
            $this->hijos($fila_id,'0',$from,$prefijo,$id_mod);
          }
        }
    }else{
      //echo "<div class='arbol-nuevo'><a href='"._RUTA_WEB.$url_modulo."'><i class='icn-plus'></i> nuevo</a></div>";
    }
    echo "</div>";
    return;
  }

  function arbol_editable_mod($from,$prefijo,$where,$url_modulo,$class_div,$id_mod){
    echo "<div class='arbol ".$class_div." '>";
    if (empty($raiz))
      $consulta = "SELECT ".$prefijo."id, ".$prefijo."nombre FROM ".$from." WHERE ".$where." ORDER BY ".$prefijo."orden";
      $rs = $this->fmt->query->consulta($consulta,__METHOD__);
      $num=$this->fmt->query->num_registros($rs);

      if($num>0){
        if ($num>1){
          $tr = "<i class='icn icn-sort btn-i btn-ordenar-i' id_padre='0' ></i>";
        }else{
          $tr="";
        }
        echo "<div class='arbol-nuevo'><a  class='btn-nuevo-i' cat='0' ><i class='icn-plus' ></i> nuevo</a> $tr</div>";
         for($i=0;$i<$num;$i++){
          $row=$this->fmt->query->obt_fila($rs);
          $fila_id = $row[$prefijo."id"];
          $fila_nombre = $row[$prefijo."nombre"];
          if ($i==$num-1) { $aux = 'icn-point-4'; } else { $aux = 'icn-point-1'; }
            echo "<div class='nodo'  id='nodo-$fila_id' ><i class='".$aux." i-nodo'></i> ".$fila_nombre;
            $this->accion($fila_id,$from,$prefijo);
            echo "</div>";
              if ($this->tiene_hijos($fila_id,$from,$prefijo)){
               $this->hijos($fila_id,'0',$from,$prefijo,$id_mod);
               //echo "tiene";
              }
         }
      }else{
        echo "<div class='arbol-nuevo'><a class='btn-nuevo-i' cat='0'  ><i class='icn-plus'></i> nuevo</a></div>";
      }
    echo "</div>";
    return;
  }


  function arbol($id,$cat,$cat_valor){
    //var_dump($cat_valor);
	//echo  $cat_valor[0];
    echo "<div class='arbol-cat'>";
    echo "<div class='header'>";
    echo "<input id='filtrar-cat' type='text' class='form-control' placeholder='Buscar'>";
    echo "<label id='check-la'><input name='check-a' type='checkbox' id='check-a' accion='check-todo'><span>Seleccionar Todo</span></label>";
    echo "</div>";
    echo "<div class='body-cats'>";
    $sql="SELECT cat_id, cat_nombre FROM categoria WHERE cat_id_padre='".$cat."' ORDER BY  cat_orden asc";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    $nivel=0;
    $espacio = 0;
    $num_v = count($cat_valor);
    if($num>0){
      for($i=0;$i<$num;$i++){
        //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id = $row["cat_id"];
        $fila_nombre = $row["cat_nombre"];

        echo "<label class='item_cat' style='margin-left:".$espacio."px'><input name='".$id."[]' type='checkbox' id='cat-$fila_id' value='$fila_id' $aux> <span>".$fila_nombre."</span></label>";
        if ($this->tiene_hijos_cat($fila_id)){
          $this->hijos_cat_check($id,$fila_id,$nivel);
        }
      }
    }
    echo "</div>";
    echo "</div>";

    ?>
    	<script language="JavaScript">
	    	$(document).ready( function () {
          $(".arbol-cat :checkbox").change(function() {
            var acc = $(this).attr("accion");
            //console.log(acc);
            if (acc=="check-todo"){
              $(".item_cat input").prop('checked', true );
              $("#check-a").attr('accion', 'check-nada' );
              $("#check-la span").html("Deseleccionar Todo");
            }
            if (acc=="check-nada"){
              $(".item_cat input").prop('checked', false );
              $("#check-a").attr('accion', 'check-todo' );
              $("#check-la span").html("Seleccionar Todo");
            }

          });
          $('#filtrar-cat').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.arbol-cat .item_cat').hide();
            $('.arbol-cat .item_cat').filter(function () {
                return rex.test($(this).text());
            }).show();
          });

		    	<?php
          if (!empty($cat_valor)){
            for ($j=0;$j<$num_v;$j++){
          ?>
		    	    var dato<?php echo $j; ?> = <?php echo $cat_valor[$j]; ?>;
		    	    $("#cat-<?php echo $cat_valor[$j]; ?>").prop("checked", true);
		    	<?php
            }
          }
          ?>
          // $(".arbol-cat").hover( function(){
          //   $(".modal-inner .body-modulo").css("overflow-y","hidden");
          // });
          // $(".arbol-cat").mouseleave( function(){
          //   $(".modal-inner .body-modulo").css("overflow-y","auto");
          // });

		    });
	    </script>
    <?php
  }

  function tiene_hijos($cat,$from,$prefijo){
    $consulta = "SELECT ".$prefijo."id  FROM ".$from." WHERE ".$prefijo."id_padre='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }
  }  

  function numero_hijos($cat,$from="categoria",$prefijo="cat_"){
    $consulta = "SELECT ".$prefijo."id  FROM ".$from." WHERE ".$prefijo."id_padre='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    return $num;
  }


  function tiene_hijos_cat($cat){
    $consulta = "SELECT cat_id  FROM categoria WHERE cat_id_padre='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }
  }

  function hijos_cat($cat,$nivel){
    $consulta = "SELECT cat_id,cat_nombre  FROM categoria WHERE cat_id_padre='$cat' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);

    if ($num>0){
	   $nivel++;
      for($i=0;$i<$num;$i++){
        //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row["cat_id"];
        $fila_nombre= $row["cat_nombre"];

        $valor_n= 25 * ($nivel+1);

        $aux_nivel = $this->img_nodo("linea",$nivel);
        echo "<div class='nodo-hijo'  id='nodo-$fila_id' style='padding-left:".$valor_n."px'> ".$aux_nivel."".$fila_nombre;
        //$this->accion($fila_id,$from,$prefijo_activar);
        echo "</div>";
        if ( $this->tiene_hijos_cat($fila_id) ){

          $this->hijos_cat($fila_id, $nivel);
        }
      }
    }
  }

  function hijos_cat_a($cat,$nivel){
    $consulta = "SELECT cat_id,cat_nombre,cat_ruta_amigable  FROM categoria WHERE cat_id_padre='$cat' and cat_activar=1 ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);

    if ($num>0){
	   $nivel++;
      for($i=0;$i<$num;$i++){
        //list($fila_id,$fila_nombre,$fila_ra)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id = $row["cat_id"];
        $fila_nombre = $row["cat_nombre"];
        $fila_ra = $row["cat_ruta_amigable"];

        $aux_nivel = $this->img_nodo("linea",$nivel);
        echo "<div class='nodo cat-".$cat." nodo-".$nivel."'  id='nodo-$fila_id'> ";
        //$this->accion($fila_id,$from,$prefijo_activar);
        $nombre_x = $this->fmt->nav->convertir_url_amigable($fila_nombre);
		$url= _RUTA_WEB.$fila_ra;

        echo '<a class="btn-'.$nombre_x.'" href="'.$url.'">'.$fila_nombre.'</a>';
        if ( $this->tiene_hijos_cat($fila_id) ){

          $this->hijos_cat_a($fila_id, $nivel);
        }
        echo "</div>";
      }
    }
  }

  function es_hijo($cat,$id_padre){
    $consulta = "SELECT cat_id_padre  FROM categoria WHERE cat_id='$cat' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }
  }
  function es_padre($cat,$id_padre){
    $consulta = "SELECT cat_id  FROM categoria WHERE cat_id_padre='$cat' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }
  }

  function hijo_nodo ($id_padre,$cadena){
    $consulta = "SELECT cat_id  FROM categoria WHERE cat_id_padre='$id_padre' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0; $i < $num ; $i++) {
        $row=$this->fmt->query->obt_fila($rs);
        $id = $row["cat_id"];
        if ($id == $cat){ return 1; }
        $cadena .= $id.",";
        //echo "id".$id.":cat:".$cat.":$aux</br>";
        if ( $this->tiene_hijos_cat($id) ){
          $this->hijo_nodo($id,$cadena);
        }
      }
      $this->id_familia .= $cadena;
    }
  }

  function traer_familia_nodo($nodo){
    if ( $this->tiene_hijos_cat($nodo) ){
      $this->id_familia = "";
      $this->hijo_nodo($nodo,"");
      return $this->id_familia;
    }else{
      return "";
    }
  }

  function es_nodo($cat,$id_padre){
    $this->id_familia = "";
    if ($cat==$id_padre){ return true; }
    if ( $this->tiene_hijos_cat($id_padre) ){
      $this->hijo_nodo($id_padre,"");
      $redo = explode(",",$this->id_familia);
      $num_redo = count($redo);
      for ($i=0; $i < $num_redo ; $i++) {
        if ($redo[$i]==$cat){
          return true;
        }
      }
      return false;
    }else{
      return false;
    }
  }

  function hijos_cat_check($id,$cat,$nivel){
    $consulta = "SELECT cat_id,cat_nombre  FROM categoria WHERE cat_id_padre='$cat' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
	  $nivel++;
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row["cat_id"];
        $fila_nombre= $row["cat_nombre"];
        	$espacio=  $nivel * 10;
			$aux_nivel = $this->img_nodo("linea",$nivel);
        echo $aux_nivel."<label class='item_cat' style='margin-left:".$espacio."px'><input name='".$id."[]' id='cat-$fila_id' type='checkbox' value='$fila_id' $aux> <span>".$fila_nombre."</span></label>";
        if ( $this->tiene_hijos_cat($fila_id) ){
          $this->hijos_cat_check($id,$fila_id,$nivel);
        }
      }
    }
  }

  function hijos($cat,$nivel,$from,$prefijo,$id_mod){
    $consulta = "SELECT ".$prefijo."id, ".$prefijo."nombre  FROM ".$from." WHERE ".$prefijo."id_padre='$cat' ORDER BY ".$prefijo."orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
	  $nivel++;
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row[$prefijo."id"];
        $fila_nombre= $row[$prefijo."nombre"];
        $valor_n= 25 * ($nivel+1);
        $aux_nivel = $this->img_nodo("linea",$nivel);
        if ($i==$num-1) { $aux = 'icn-point-4'; } else { $aux = 'icn-point-1'; }
        echo "<div class='nodo-hijo $aux' cat='$fila_id' id='nodo-$fila_id' style='padding-left:".$valor_n."px'>".$aux_nivel."".$fila_nombre;
        $this->accion($fila_id,$from,$prefijo,$id_mod);
        echo "</div>";
        if ( $this->tiene_hijos($fila_id,$from,$prefijo) ){
          $this->hijos($fila_id, $nivel,$from,$prefijo,$id_mod);
        }
      }
    }
  }

  function traer_hijos_array($cat,&$ids_cat){
	 $consulta = "SELECT cat_id FROM categoria WHERE cat_id_padre='$cat' ORDER BY cat_id asc";
	 //echo $consulta;
	 $rs = $this->fmt->query->consulta($consulta,__METHOD__);
	 $num=$this->fmt->query->num_registros($rs);
	 if ($num>0){
	    for($j=0;$j<$num;$j++){
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row["cat_id"];
        $i=count($ids_cat);
        $ids_cat[$i]=$fila_id;

			if($this->tiene_hijos_cat($fila_id)){
				$this->traer_hijos_array($fila_id,$ids_cat);
			}
		}
	 }
  }

  function img_nodo($modo,$nivel){
  }

  function accion($cat,$from,$prefijo,$id_mod){
    $var_activo=$this->estado_activo($cat,$from,$prefijo);
    $ra=$this->fmt->categoria->ruta_amigable($cat);

    if ($var_activo=="1"){ $i='icn-eye-open'; $a="0"; }else{ $i='icn-eye-close'; $a="1"; }
    echo " <i class='icn-plus btn-i btn-nuevo-i' cat='".$cat."' ></i>";
    echo " <i class='".$i." btn-i btn-activar-i' id='btn-pi-$cat' vars='activar,$cat,$a' estado='$a' cat='".$cat."' ></i>";
    echo " <i class='icn-pencil btn-i btn-editar-i' title='editar $cat' cat='".$cat."'></i>";
    // $this->categoria_id_padre
    $num = $this->numero_hijos($cat,$from,$prefijo);
    if ($num > 1){
      // $id_padre = $this->id_padre($cat,$from,$prefijo);
      echo "<i class='btn-i btn-ordenar-i icn icn-sort' id_padre='$cat' title='ordenar'></i>";
    }
    
    //if ($this->tiene_hijos($cat,$from,$prefijo)){
      //echo "<i class='btn-i btn-ordenar-i icn icn-sort' title='ordenar'></i>";
    //}
    echo " <i class='icn-block-page btn-i btn-contenedores' cat='".$cat."' ruta='$ra' ></i>";
    echo " <i class='icn-trash btn-i btn-eliminar-i' id_mod='".$id_mod."' vars='eliminar,".$cat."' nombre='".$this->nombre($cat,$from,$prefijo)."'  cat='".$cat."' ></i>";
    return;
  }

  function estado_activo($cat,$from,$prefijo){
    $consulta = "SELECT ".$prefijo."activar  FROM ".$from." WHERE ".$prefijo."id='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila[$prefijo.'activar'];
  }

  function id_plantilla_cat($cat){
    $consulta = "SELECT cat_id_plantilla  FROM categoria WHERE cat_id='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $p = $fila['cat_id_plantilla'];
    if ($p==0){
      $p = 1;
    }
    return $p;
  }

  function traer_opciones_cat($cat,$id_padre=""){

  	if($id_padre==""){
      $id_padrex="";
      $consulta = "SELECT cat_id, cat_nombre FROM categoria WHERE cat_id_padre='0'  ORDER BY cat_orden";
    }else{
      $id_padrex=$this->categoria_id_padre($cat);
      $consulta = "SELECT cat_id, cat_nombre FROM categoria WHERE cat_id_padre='$id_padre'  ORDER BY cat_orden";
    }

    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    echo "<option class='raiz' value='0'>Raiz (0)</option>";
      if($num>0){
        for($i=0;$i<$num;$i++){
          //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
          $row=$this->fmt->query->obt_fila($rs);
          $fila_id= $row["cat_id"];
          $fila_nombre= $row["cat_nombre"];
          //if ($fila_id==$id_padre){ $aux="disabled"; }else{ $aux=""; }
          //if (!empty($id_padrex)){ if ($fila_id==$id_padrex){ $aux2="selected"; }else{ $aux2=""; } }

          if ($fila_id==$cat){ $aux1="selected"; }else{ $aux1=""; }
          echo "<option class='' value='$fila_id' $aux1 > ".$fila_nombre;
          echo "</option>";
          if ($this->tiene_hijos_cat($fila_id)){
             $this->hijos_opciones_cat($cat,$fila_id,'1');
          }
      }
    }
  }

  function traer_opciones_cat_padre($padre,$cat){
    $id_padre=$this->categoria_id_padre($cat);
    $num_p = count($padre);
    for($j=0;$j<$num_p;$j++){
	    $nom_padre= $this->nombre_categoria($padre[$j]);
	    $consulta = "SELECT cat_id, cat_nombre FROM categoria WHERE cat_id_padre='".$padre[$j]."'  ORDER BY cat_orden";
	    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
	    $num=$this->fmt->query->num_registros($rs);
	    echo "<option class='' value='".$padre[$j]."'>$nom_padre</option>";
	    if($num>0){
			for($i=0;$i<$num;$i++){
		    	//list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
          $row=$this->fmt->query->obt_fila($rs);
          $fila_id= $row["cat_id"];
          $fila_nombre= $row["cat_nombre"];

		        if ($fila_id==$id_padre){ $aux="selected"; }else{ $aux=""; }
		        if ($fila_id==$cat){ $aux1="disabled"; }else{ $aux1=""; }
		        echo "<option class='' value='$fila_id' $aux $aux1 > -".$fila_nombre;
		        echo "</option>";
		        if ($this->tiene_hijos_cat($fila_id)){
		          $this->hijos_opciones_cat($fila_id,'1');
		        }
		    }
		}
	}
  }

  function traer_opciones($cat,$from,$prefijo){
    $id_padre=$this->id_padre($cat,$from,$prefijo);
    $consulta ="SELECT ".$prefijo."id, ".$prefijo."nombre FROM ".$from." WHERE ".$prefijo."id_padre='0'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    echo "<option class='' value='0'>Raiz (0)</option>";
      if($num>0){
      for($i=0;$i<$num;$i++){
        //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row[$prefijo."id"];
        $fila_nombre= $row[$prefijo."nombre"];

        if ($fila_id==$id_padre){ $aux="selected"; }else{ $aux=""; }
        if ($fila_id==$cat){ $aux1="disabled"; }else{ $aux1=""; }
        echo "<option class='' value='$fila_id' $aux $aux1 > ".$fila_nombre;
        echo "</option>";
        if ($this->tiene_hijos($fila_id,$from,$prefijo)){
          $this->hijos_opciones($fila_id,'1',$id_padre,$from,$prefijo);
        }
      }
    }
  }



  function hijos_opciones_cat($cat,$id_cat,$nivel){
    $consulta = "SELECT cat_id,cat_nombre  FROM categoria WHERE cat_id_padre='$id_cat' ORDER BY cat_orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    $nivel++;
    $valor_n="";
    for ($j=0;$j<$nivel;$j++){
      $valor_n .='--';
    }
    if ($num>0){
      for($i=0;$i<$num;$i++){
        //list($fila_idx,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_idx= $row["cat_id"];
        $fila_nombre= $row["cat_nombre"];
        //if ($fila_id==$id_padre){ $aux="selected"; }else{ $aux=""; }
        //echo $fila_idx.":".$cat;
        if ($fila_idx==$cat){ $aux1="selected"; }else{ $aux1=""; }
        echo "<option class='' value='$fila_idx'  $aux1 > ".$valor_n.$fila_nombre;
        echo "</option>";
        if ( $this->tiene_hijos_cat($fila_idx) ){
          $this->hijos_opciones_cat($cat,$fila_idx,$nivel);
        }
      }
    }
  }

  function hijos_opciones($cat,$nivel,$id_padre,$from,$prefijo){
    $consulta = "SELECT ".$prefijo."id, ".$prefijo."nombre  FROM ".$from." WHERE ".$prefijo."id_padre='$cat' ORDER BY ".$prefijo."orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for($i=0;$i<$num;$i++){
        //list($fila_id,$fila_nombre)=$this->fmt->query->obt_fila($rs);
        $row=$this->fmt->query->obt_fila($rs);
        $fila_id= $row[$prefijo."id"];
        $fila_nombre= $row[$prefijo."nombre"];
        $valor_n="";
        for ($j=0;$j<$nivel;$j++){
          $valor_n .='--';
        }
        if ($fila_id==$id_padre){ $aux="selected"; }else{ $aux=""; }
        if ($fila_id==$cat){ $aux1="disabled"; }else{ $aux1=""; }
        echo "<option class='' value='$fila_id' $aux  $aux1 > ".$valor_n.$fila_nombre;
        echo "</option>";
        if ( $this->tiene_hijos($fila_id,$from,$prefijo) ){
          $nivel++;
          $this->hijos_opciones($fila_id, $nivel,$from,$prefijo);
        }
      }
    }
  }

  function opciones_tipo_cat($cat){
    $consulta = "SELECT cat_tipo  FROM categoria WHERE cat_id='$cat'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);

    for($i=0;$i<4;$i++){
      if ($fila["cat_tipo"]==$i){ $aux="selected"; }else{ $aux=""; }
      echo "<option class='".$fila["cat_tipo"]."' value='".$i."' ".$aux." > ".$this->tipo_cat($i);
      echo "</option>";
    }
  }

  function tipo_cat($tipo){
    switch ($tipo) {
      case '0':
        return "Estandar";
        break;
      case '1':
        return "Logeada";
        break;
      case '2':
        return "Sitio";
        break; 
      case '3':
        return "Hijo Anidado";
        break;

      default:
        return "error";
        break;
    }
  }

  function opciones_destino($destino){
      $aux_s="";
      $aux_b="";
      if ($destino=="_self"){ $aux_s ="selected"; }
      echo "<option class='' value='_self'  $aux_d> La misma página (_self)</option>";
      if ($destino=="_blank"){ $aux_b ="selected"; }
      echo "<option class='' value='_blank' $aux_b> En otra pestaña (_blank)</option>";
  }

  function traer_rel_cat_nombres($fila_id,$from,$prefijo_cat,$prefijo_rel,$obviar){
    $consulta = "SELECT ".$prefijo_cat." FROM ".$from." WHERE ".$prefijo_rel."='".$fila_id."'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        if ($row[$prefijo_cat]!=$obviar){
        echo "- ".$this->nombre_categoria($row[$prefijo_cat])."<br/>";
        }
      }
    }
  }

  function traer_rel_cat_id($fila_id,$from,$prefijo_cat,$prefijo_rel){
    $consulta = "SELECT ".$prefijo_cat." FROM ".$from." WHERE ".$prefijo_rel."='".$fila_id."'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $aux[$i] = $row[$prefijo_cat];
      }
    }
    return $aux;
  }

  // function traer_dominio_cat_ruta($dato){
  //   $consulta = "SELECT cat_dominio FROM categoria WHERE cat_ruta_sitio='".$dato."' and cat_tipo='2'";
  //   $rs = $this->fmt->query->consulta($consulta,__METHOD__);
  //   $fila=$this->fmt->query->obt_fila($rs);
  //   return $fila["cat_dominio"];
  // }

  //   function traer_dominio_cat_id($dato){
  //   $consulta = "SELECT cat_dominio FROM categoria WHERE cat_id='".$dato."' and cat_tipo='2'";
  //   $rs = $this->fmt->query->consulta($consulta,__METHOD__);
  //   $fila=$this->fmt->query->obt_fila($rs);
  //   return $fila["cat_dominio"];
  // }

   function traer_meta_cat($cat){
    $consulta = "SELECT cat_meta FROM categoria WHERE cat_id=".$cat;
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    return $fila["cat_meta"];
  }

  function tiene_meta($cat){
    $consulta = "SELECT cat_meta FROM categoria WHERE cat_id=".$cat;
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      return true;
    }else{
      return false;
    }

  }

  function traer_id_cat_sitio($ruta_web){
    $consulta = "SELECT cat_id FROM sitio,sitio_categorias,categoria WHERE sitio_ruta_amigable='".$dato."' and sitio_cat_sitio_id=sitio_id and sitio_cat_cat_id=cat_id";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    if ( $fila["cat_id"] ){
      return $fila["cat_id"];
    }else{
      return 0;
    }
  }
  function traer_id_ruta_amigable($dato){
    $consulta = "SELECT cat_id FROM categoria WHERE cat_ruta_amigable='".$dato."'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    if ( $fila["cat_id"] ){
      return $fila["cat_id"];
    }else{
      return 0;
    }
  }
  function traer_ruta_amigable_padre($cat, $padre){
	  $ruta=$this->ruta_amigable($cat);
	  $cat_padre=$this->categoria_id_padre($cat);
	  if($cat_padre!=$padre){
		  if($cat_padre!=0){
			  $ruta=$this->traer_ruta_amigable_padre($cat_padre, $padre)."/".$ruta;
		  }
	  }
	  return $ruta;

  }

    function traer_ruta_amigable_padre_url($cat, $padre, $cat_activo){
	  $ruta=$this->ruta_amigable($cat);
	  $cat_padre=$this->categoria_id_padre($cat);
	  if($cat_padre!=$padre){
		  if($cat_padre!=0){
			  $ruta= $this->traer_ruta_amigable_padre_url($cat_padre, $padre, $cat_activo)."/".$ruta;
			  echo "<li>";
			  echo "<a>".$ruta."</a>";
			  echo "</li>";
		  }
	  }
	  return $ruta;

  }

  function traer_ruta_completa_cat($cat){
    $cat_padre=$this->categoria_id_padre($cat);
    $ruta=$this->traer_ruta_amigable_padre($cat, $cat_padre);
    return $ruta;
  }


  function traer_id_cat_producto($prod){
	$consulta = "SELECT mod_prod_cat_cat_id FROM mod_productos_categorias WHERE mod_prod_cat_prod_id='".$prod."' order by mod_prod_cat_prod_id asc";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    return $fila["mod_prod_rel_cat_id"];
  }

  function traer_cat_sitios(){
    $consulta = "SELECT cat_id FROM categoria WHERE cat_tipo='2' and cat_activar='1'";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0;$i<$num;$i++){
        $row =$this->fmt->query->obt_fila($rs);
        $varx[$i] = $row["cat_id"];
      }
      return $varx;
    }else{
      return 0;
    }
  }

  public function hijos_array($array){
    $from = $array["from"];
    $prefijo = $array["prefijo"];
    $item = $array["item"];

    $consulta = "SELECT ".$prefijo."id  FROM ".$from." WHERE ".$prefijo."id_padre='$item' ORDER BY ".$prefijo."orden";
    $rs = $this->fmt->query->consulta($consulta,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if ($num>0){
      for($i=0;$i<$num;$i++){
        $row=$this->fmt->query->obt_fila($rs);
        $id[$i] = $row[$prefijo."id"];
      }
    }

    return $id;
  }

}
?>
