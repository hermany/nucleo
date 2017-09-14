<?php
header('Content-Type: text/html; charset=utf-8');

class NAV{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function activado_sistemas_rol($id_sis,$id_rol){
	  $sql ="SELECT DISTINCT sis_rol_sis_id FROM sistema_roles WHERE sis_rol_rol_id='$id_rol' and sis_rol_sis_id=$id_sis ";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if($num>0){
      return true;
    }else{
      return false;
    }
    $this->query->liberar_consulta($rs);
  }

  function activado_modulos_rol($id_mod,$id_rol){
	  echo $sql ="SELECT mod_rol_mod_id FROM modulo_roles where mod_rol_mod_id='$id_mod' and mod_rol_rol_id=$id_rol ";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
    $sw=false;
    if($id_rol==1){ return true; }
    if($num>0){
      return true;
    }else{
      return false;
    }
    $this->fmt->query->liberar_consulta($rs);
  }
  function construir_sistemas_rol($id_rol,$id_usu){  // revisar por roles
    $sql ="SELECT sis_id, sis_nombre, sis_icono, sis_color FROM sistema  where sis_activar='1' ORDER BY  sis_orden ASC";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
      if($num>0){
        //for($i=0;$i < $num; $i++){
           //$row = $this->fmt->query->obt_fila($rs);
        while ($row = $this->fmt->query->obt_fila($rs)){

           $fila_id = $row["sis_id"];
           $fila_nombre = $row["sis_nombre"];
           $fila_icono = $row["sis_icono"];
           $color = $row["sis_color"];

          if($id_rol==1){
          	$aux .= $this->lista_horizontal($fila_id, "btn-menu-sidebar", $fila_nombre, $fila_icono,$id_rol,$color,$fila_nombre); //$nombre, $menu, $id_sistema, $id_modulo
            //echo $color;
            $aux .= '<ul class="box-nav-modulos box-nav-'.$fila_id.'"><h3 class="title"><i class="icn icn-chevron-left btn-nav-back" ></i><i class="'.$fila_icono.'" color="'.$color.'" style="color:'.$color.' !important;"></i><span>'.$fila_nombre.'</span></h3>'.$this->traer_modulos($fila_id,$id_rol,$id_usu).'</ul>';

          }else{
            if($this->activado_sistemas_rol($fila_id,$id_rol)){
	       	   $aux .= $this->lista_horizontal($fila_id, "btn-menu-sidebar", $fila_nombre, $fila_icono,$id_rol, $color,$fila_nombre);
             $aux .= '<ul class="box-nav-modulos box-nav-'.$fila_id.'"><h3 class="title"><i class="icn icn-chevron-left btn-nav-back" ></i><i class="'.$fila_icono.'" color="'.$color.'" style="color:'.$color.' !important;"></i><span>'.$fila_nombre.'</span></h3>'.$this->traer_modulos($fila_id,$id_rol,$id_usu).'</ul>';
            }
          }
        }
      }
    $this->fmt->query->liberar_consulta($rs);
    return $aux;
  }

function traer_cat_hijos_menu_raiz($cat,$nivel,$nivel_tope){
  	$inputDominio = _RUTA_WEB;
    $sql="SELECT cat_id, cat_nombre, cat_id_padre, cat_icono, cat_imagen, cat_url, cat_destino, cat_ruta_amigable FROM categoria WHERE cat_id_padre='$cat' and cat_activar='1' ORDER BY cat_orden ASC";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if ($num>0){
	   $nivel++;
	   //echo $nivel."</br>";
	   for ($i=0; $i<$num; $i++){
       $row = $this->fmt->query->obt_fila($rs);
       $fila_id = $row["cat_id"];
       $fila_nombre= $row["cat_nombre"];
       $fila_id_padre= $row["cat_id_padre"];
       $fila_icono= $row["cat_icono"];
       $fila_imagen= $row["cat_imagen"];
       $fila_url= $row["cat_url"];
       $fila_destino= $row["cat_destino"];
       $fila_ruta_amigable= $row["cat_ruta_amigable"];

        if (!empty($fila_url)){
          $url= $fila_url; $destino=$fila_destino;
        }else{
          $url= $fila_ruta_amigable; $destino="";
        }

        if ($nivel < $nivel_tope){
	        if ( $this->tiene_cat_hijos($fila_id) ){
	          $aux .= $this->fmt_li_hijos($fila_id, $fila_nombre,$nivel);
	        } else {
	          $aux .= $this->fmt_li($fila_id,"","",$fila_nombre, $url, $destino, $fila_imagen,$this->fmt->categoria->traer_id_cat_dominio($inputDominio));
	        }
        }else{
	        $aux .= $this->fmt_li($fila_id,"","",$fila_nombre, $url, $destino, $fila_imagen,$this->fmt->categoria->traer_id_cat_dominio($inputDominio));
        }
      }
      return $aux;
    }
    $this->query->liberar_consulta($rs);
  }


  function construir_sistemas_esenciales($id_rol, $id_usu){
    $sql ="SELECT mod_id  FROM modulo where mod_tipo='2' and mod_activar=1 and mod_id_padre=0 ORDER BY mod_id DESC";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
      if($num>0){
        for($i=0;$i < $num; $i++){
          $fila = $this->fmt->query->obt_fila($rs);
          $aux .=  $this->construir_btn_sidebar("btn-menu-sidebar", $fila["mod_id"]);
        }
      }
    $this->fmt->query->liberar_consulta($rs);
    return $aux;
  }

  function construir_title_menu($nombre){
    $aux ="<div class='title-menu'>$nombre</div>";
    return $aux;
  }

  function construir_btn_sidebar($clase, $id_mod){
    $sql ="SELECT mod_nombre, mod_icono, mod_color, mod_url,mod_ruta_amigable FROM modulo where mod_id='".$id_mod."' and mod_activar='1'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
    $fila = $this->fmt->query->obt_fila($rs);
    $fila_nombre = $fila['mod_nombre'];
    $fila_icono  = $fila['mod_icono'];
    $fila_url    = $fila['mod_url'];
    $color    = $fila['mod_color'];
    $rutaa    = $fila['mod_ruta_amigable'];
    if ($num > 0){
      $aux  ="<li class='dropdown'>";
      $aux .='<a class="'.$clase.'" href="'._RUTA_WEB."dashboard/".$rutaa.'" target="_self" title="'.$fila_nombre.'"  icn="'.$fila_icono.'" id="btn-m'.$id_mod.'" id_mod="'.$id_mod.'">';
      $aux .= "<i class='".$fila_icono."' color='".$color."' style='color:".$color." !important'></i> <span>".$fila_nombre."</span></a>";
      $aux .='<a  class="btn-skip" href="'._RUTA_WEB."dashboard/".$rutaa.'" target="_blank" title="'.$fila_nombre.'"  icn="'.$fila_icono.'" id="btn-m'.$id_mod.'" id_mod="'.$id_mod.'"><i class="icn icn-skip"></i></a>';
      $aux .= "</li>";
    }
    return $aux;
  }

  function construir_btn_atajo ($clase, $id_atj){

    $sql ="SELECT atj_nombre, atj_icono, atj_url FROM atajos where atj_id='".$id_atj."' and atj_activar='1'";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    $num =$this->fmt->query->num_registros($rs);
    $fila_nombre = $fila['mod_nombre'];
    $fila_icono  = $fila['mod_icono'];
    $fila_url    = $fila['mod_url'];
    if ($num > 0){
    $aux ='<li class="dropdown">';
    $aux .='  <a  class="'.$clase.'"  title="'.$fila_nombre.'"  icn="'.$fila_icono.'" id="btn-a'.$id_atj.'" id_atj="'.$id_atj.'">';
    $aux .='  <i class="'.$fila_icono.'"></i>  <span>'.$fila_nombre.'</span> </a>';
    $aux .='</li>';
    }
    $this->query->liberar_consulta($rs);
    return $aux;
  }

  function lista_horizontal($id, $clase,$nombre, $icono,$id_rol,$color,$fila_nombre){
    //echo $color;
    $aux ='<li class="dropdown" id="btn-nav-sis-'.$id.'">
            <a class="btn-nav-sis" collapse="'.$id.'" rol="'.$id_rol.'" usu="'.$id_usu.'" >
             <i class="'.$icono.'" style="background-color:'.$color.'"  ></i> <span>'.$nombre.'</span></i>
            </a></li>';
    //$this->traer_modulos($id,$id_rol)
    return $aux;
  }

  function traer_modulos($id,$id_rol,$id_usu){
    $sql ="SELECT sis_mod_mod_id FROM sistema_modulos where sis_mod_sis_id='$id' ORDER BY sis_mod_orden ASC ";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
    $aux="";
    if ($num>0){
      while ($row = $this->fmt->query->obt_fila($rs)){
        //var_dump($row);
        $fila_id = $row["sis_mod_mod_id"];

        if($id_rol==1){
          //echo "t:".$fila_id."</br>";
          $aux .=  $this->construir_btn_sidebar("btn btn-sm-sidebar",$fila_id);
          //echo "</br>";
        }else{
	        if($this->activado_modulos_rol($fila_id,$id_rol)){
            $aux .= $this->construir_btn_sidebar("btn btn-sm-sidebar",$fila_id);
          }
        }
      }
    }
    //echo "a:".$aux;
    $this->fmt->query->liberar_consulta();
    return $aux;
  }

  function traer_cat_hijos($cat){
    $sql="SELECT cat_id, cat_nombre, cat_id_padre, cat_icono, cat_imagen, cat_url, cat_destino, cat_ruta_amigable FROM categoria WHERE cat_id_padre='$cat' and cat_activar='1' ORDER BY cat_orden ASC";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0; $i<$num; $i++){
        $row = $this->fmt->query->obt_fila($rs);

        $fila_id = $row["cat_id"];
        $fila_nombre= $row["cat_nombre"];
        $fila_id_padre= $row["cat_id_padre"];
        $fila_icono= $row["cat_icono"];
        $fila_imagen= $row["cat_imagen"];
        $fila_url= $row["cat_url"];
        $fila_destino= $row["cat_destino"];
        $fila_ruta_amigable= $row["cat_ruta_amigable"];

        if (!empty($fila_url)){
          $url= $fila_url; $destino=$fila_destino;
        }else{
          $url= $fila_ruta_amigable; $destino="";
        }
        $aux .= $this->fmt_li($fila_id,"","",$fila_nombre, $url, $destino );
      }
    }
    $this->fmt->query->liberar_consulta($rs);
    return $aux;
  }

  function traer_cat_hijos_menu($cat,$nivel,$nivel_tope,$cat_active){
    $sql="SELECT cat_id, cat_nombre, cat_id_padre, cat_icono, cat_imagen, cat_url, cat_destino, cat_ruta_amigable FROM categoria WHERE cat_id_padre='$cat' and cat_activar='1' ORDER BY cat_orden ASC";
    $rs = $this->fmt->query->consulta($sql,__METHOD__);
    $num = $this->fmt->query->num_registros($rs);
    if ($num>0){
	   $nivel++;
	   //echo $nivel."</br>";
	   for ($i=0; $i<$num; $i++){
         $row =  $this->fmt->query->obt_fila($rs);
         $fila_id = $row["cat_id"];
         $fila_nombre= $row["cat_nombre"];
         $fila_id_padre= $row["cat_id_padre"];
         $fila_icono= $row["cat_icono"];
         $fila_imagen= $row["cat_imagen"];
         $fila_url= $row["cat_url"];
         $fila_destino= $row["cat_destino"];
         $fila_ruta_amigable= $row["cat_ruta_amigable"];

        //echo "url".$url;

        if ((!empty($cat_active)) && ( $fila_id==$cat_active)){
          $cat_a="active";
        }else{
          $cat_a="";
        }

        if ($nivel < $nivel_tope){
	        if ( $this->tiene_cat_hijos($fila_id) ){
	          $aux .= $this->fmt_li_hijos($fila_id, $fila_nombre,$nivel);
	        } else {
	          $aux .= $this->fmt_li($fila_id,"","",$fila_nombre, $fila_ruta_amigable,$fila_url, $fila_destino, $fila_imagen,$cat, $cat_a);
	        }
        }else{
	        $aux .= $this->fmt_li($fila_id,"","",$fila_nombre,$fila_ruta_amigable,$fila_url, $fila_destino, $fila_imagen,$cat, $cat_a);
        }
      }
      return $aux;
    }
    $this->fmt->query->liberar_consulta($rs);
  }

  function fmt_li($id, $clase, $icono, $nombre,$ruta_amigable,$url,$destino, $imagen, $cat, $cat_active){

    $nombre_x = $this->convertir_url_amigable($nombre);
    // if(_MULTIPLE_SITE=="on"){
    // 	$url=_RUTA_WEB_SERVER.$this->fmt->categoria->traer_ruta_amigable_padre($id, $cat);
    // }else{

    if (empty($url)){
	    $urlx=_RUTA_WEB.$this->fmt->categoria->traer_ruta_amigable_padre($id, $cat);
	    //$url=_RUTA_WEB.$this->fmt->categoria->ruta_amigable($id);
    }else{
      $urlx=_RUTA_WEB.$url;
    }
    //echo "url:".$urlx."</br>";
    // }

    //$url=$this->fmt->categoria->traer_ruta_amigable_padre($id);
    //echo $url;
    if (empty($imagen)){ $aux_x=""; }else{ $aux_x="<img class='img-m' src='"._RUTA_WEB.$imagen."' border=0>"; }
    $aux  = '<li id="btn-m'.$id.'" class="item-m btn-m'.$id.' '.$clase.' '.$cat_active.' btn-m-'.$nombre_x.'">';
    $aux .= '<a class="btn-nav-item btn-nav-item-'.$id.'" href="'.$urlx.'" target="'.$destino.'">';
    $aux .= $aux_x;
    $aux .= '<i class="'.$icono.'"></i>';
    $aux .= '<span>'.$nombre.'</span></a>';
    $aux .= '</li>';
    return $aux;
  }

  function tiene_cat_hijos($id){
    $sql="SELECT cat_id FROM categoria WHERE cat_id_padre=$id and cat_activar=1";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      return true;
    }else{
      return false;
    }
    $this->query->liberar_consulta($rs);
  }

  function fmt_li_hijos($id, $nombre){
	$nombre_x = $this->convertir_url_amigable($nombre);

    $aux  = '<li class="dropdown dropdown-'.$nombre_x.'">';
    $aux .= ' <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$nombre.'<span class="fa fa-angle-down"></span></a>';
    $aux .=   '<ul class="dropdown-menu animated fadeIn">';
    $aux .=     $this->traer_cat_hijos_menu($id);
    $aux .=   '</ul>';
    $aux .= '</li>';
    return $aux;
  }

  function convertir_url_amigable($cadena){
    $cadena= utf8_decode($cadena);
    $cadena = strtolower($cadena);
    $cadena = str_replace(' ', '-', $cadena);
    $cadena = str_replace('?', '', $cadena);
    $cadena = str_replace('+', '', $cadena);
    $cadena = str_replace(':', '', $cadena);
    $cadena = str_replace('??', '', $cadena);
    $cadena = str_replace('`', '', $cadena);
    $cadena = str_replace('!', '', $cadena);
    $cadena = str_replace('¿', '', $cadena);
    $cadena = str_replace(',', '-', $cadena);
    $cadena = str_replace('(', '', $cadena);
    $cadena = str_replace(')', '', $cadena);
    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ??';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);

    return $cadena;
  }


}

?>