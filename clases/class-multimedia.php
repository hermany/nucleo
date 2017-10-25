<?php
header("Content-Type: text/html;charset=utf-8");

class CLASSMULTIMEDIA{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

	function traer_id_multimedia_ruta_archivo($ruta){
		$consulta= "SELECT DISTINCT mul_id FROM multimedia WHERE mul_url_archivo='$ruta'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila['mul_id'];
	}
	function traer_nombre_multimedia($id){
		$consulta= "SELECT DISTINCT mul_nombre FROM multimedia WHERE mul_id='$id'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila['mul_nombre'];
	}
	function traer_ruta_archivo_multimedia($id){
		$consulta= "SELECT DISTINCT mul_url_archivo FROM multimedia WHERE mul_id='$id'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila['mul_url_archivo'];
	}
	function traer_embed_multimedia($id){
		$consulta= "SELECT DISTINCT mul_embed FROM multimedia WHERE mul_id='$id'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila['mul_embed'];
	}
	function traer_tipo_multimedia($id){
		$consulta= "SELECT DISTINCT mul_tipo_archivo FROM multimedia WHERE mul_id='$id'";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
		$fila=$this->fmt->query->obt_fila($rs);
		return $fila['mul_tipo_archivo'];
	}

  function multimedia_cat($cat){
    $consulta= "SELECT DISTINCT mul_url_archivo FROM multimedia,multimedia_categorias,categoria WHERE mul_id=mul_cat_mul_id and mul_cat_cat_id='$cat' and mul_activar=1 ORDER BY mul_cat_orden asc";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
    $num =$this->fmt->query->num_registros($rs);
    if ($num>0){
      for ($i=0; $i < $num; $i++) {
				$fila=$this->fmt->query->obt_fila($rs);
        $row[$i]= $fila["mul_url_archivo"];
      }
      return $row;
    }else{
      return false;
    }
  }
	function imagen_album($id_album){
		$consulta= "SELECT  mul_tipo_archivo,mul_url_archivo FROM album,album_multimedia,multimedia WHERE mul_id=alb_mul_mul_id and alb_mul_alb_id='$id_album'  ORDER BY alb_mul_orden asc";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$fila =$this->fmt->query->obt_fila($rs);
		$img = "";
		$fila_tipo = $fila["mul_tipo_archivo"];
		if ( $fila_tipo=="jpeg" || $fila_tipo=="jpg" || $fila_tipo=="png" || $fila_tipo=="gif" ){
			$img = "img";
		}
		$fila_url = $fila["mul_url_archivo"];
		if ((!empty($fila["mul_tipo_archivo"])) && ($img == "img")){
			$url = $this->fmt->archivos->convertir_url_mini($fila_url);
			return $url;
		}else{
			return "images/image-icon.png";
		}
	}

	function cantidad_mul_albums($id_album){
		$consulta= "SELECT  mul_tipo_archivo FROM album,album_multimedia,multimedia WHERE mul_id=alb_mul_mul_id and alb_mul_alb_id='$id_album'  ORDER BY alb_mul_orden asc";
		$rs =$this->fmt->query->consulta($consulta,__METHOD__);
		$num =$this->fmt->query->num_registros($rs);
		$count_img=0;
		$count_video=0;
		$count_embed=0;
		$count_audio=0;
		if ($num>0){
			for ($i=0; $i < $num; $i++) {
				list($fila_tipo)=$this->fmt->query->obt_fila($rs);
				if ( $fila_tipo=="jpeg" || $fila_tipo=="jpg" || $fila_tipo=="png" || $fila_tipo=="gif" ){
					$count_img++;
				}
				if ($fila_tipo=="mp4"){
					$count_video++;
				}
				if ($fila_tipo=="embed"){
					$count_embed++;
				}
				if ($fila_tipo=="mp3"){
					$count_audio++;
				}
			}
			$aux="";
			if ( $count_img != 0){
				$aux .= " <i class='icn icn-picture'></i> ".$count_img;
			}
			if ( $count_video != 0){
				$aux .= " <i class='icn icn-video'></i> ".$count_video;
			}
			if ( $count_audio != 0){
				$aux .= " <i class='icn icn-music'></i> ".$count_audio;
			}

			if ( $count_embed != 0){
				$aux .= " <i class='icn icn-code'></i> ".$count_embed;
			}

			return $aux;
		}
	}


}
