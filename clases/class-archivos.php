<?php
// require_once(_RUTA_HOST."/nucleo/config.php");
// require_once(_RUTA_HOST."/nucleo/clases/class-mysql.php");
// //echo "Class BRAND";
header('Content-Type: text/html; charset=utf-8');

class ARCHIVOS{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function select_archivos($sitio,$directorio_p,$id){
    //echo _RUTA_HOST.$sitio."</br>";
    //echo $directorio_p;
    echo $sitio;
    //echo _RUTA_SERVER;
    //$this->listar_directorios_ruta($sitio,"1");
	if(empty($id)){
		$aux_id ="inputRutaArchivos";
	}else{
		$aux_id = $id;
	}
    ?>
    <div class="box-upload-s">
      <select class="form-control " id="<?php echo $aux_id; ?>" name="<?php echo $aux_id; ?>">
        <?php
        $this->listar_directorios_ruta($sitio,"1",$directorio_p);
        ?>
      </select>
      <button class="btn-docs btn btn-default" name="button"><i class="icn-folder"></i></button>
    </div>
    <?php
  }



  function listar_directorios_ruta($ruta,$nivel,$directorio_p){
    //if(_MULTIPLE_SITE=="on")
		$rutax = _RUTA_SERVER.$ruta;
	/*else
		$rutax = _RUTA_HT.$ruta;*/


    $directorio = opendir($rutax);
    for ($i=0;$i<$nivel;$i++){
      $aux .= "-";
    }

    while ($file = readdir($directorio)) {
        if ((is_dir($rutax."/".$file)) && ( $file!=".") && ($file!="..")){
          //echo $ruta."/".$file."</br>";
          //if (is_dir($ruta."/".$file)) { echo 'archivo'; }else{ echo "no archivo";}
          $nivel++;
          //echo $aux.$file."</br>";

            //echo $aux." ".$ruta."/".$file;
          $this->option_directorio_hijo( $ruta."/".$file, $directorio_p );

          $this->listar_directorios_ruta($ruta."/".$file,$nivel,$directorio_p);
        }

    }
    closedir($directorio);
  }

  function option_directorio_hijo($ruta,$directorio_p){
    $rx = explode ("/",$ruta);
    $con = count($rx);
    $ar = str_split($ruta);

    if ($ar[0]=="/"){ $ruta = substr($ruta, 1); }

    /*$ruta_v = explode ("/",$ruta);
    if ($ruta_v[0]==_RUTA_DEFAULT){
      $c = strlen ($ruta_v[0] );
      $ruta_valor = substr($ruta, $c +1 );
    } else {
      $ruta_valor = $ruta;
    }*/



    //for ($i=0; $i < $con ; $i++) {

     // if( $rx[$i] == $directorio_p ){

	 if( $this->existe_palabra($ruta,$directorio_p) ){
        echo "<option value='".$ruta."'>";
        echo $ruta;
        echo "</option>";
      }
      /*if( $rx[$i] == $directorio ){
        return true;
      }else{
        return false;
      }*/
    //}
  }

  function formato_size_archivo($bytes){
     if ($bytes >= 1073741824)
     {
         $bytes = round( number_format($bytes / 1073741824, 2),0) . ' GB';
     }
     elseif ($bytes >= 1048576)
     {
         $bytes = round( number_format($bytes / 1048576, 2),0) . ' MB';
     }
     elseif ($bytes >= 1024)
     {
         $bytes = round( number_format($bytes / 1024, 2),0) . ' KB';
     }
     elseif ($bytes > 1)
     {
         $bytes = $bytes . ' bytes';
     }
     elseif ($bytes == 1)
     {
         $bytes = $bytes . ' byte';
     }
     else
     {
         $bytes = '0 bytes';
     }

     return  $bytes;
  }

 function existe_palabra($cadena,$palabra){
	$palabra=preg_quote($palabra);
	if(eregi($palabra,$cadena)) {
	    return true;
	} else {
	    return false;
	}
 }


  function crear_thumb($src, $dst, $width, $height, $crop=0){

    if(!list($w, $h) = getimagesize($src)) return "Unsupported picture type!";

    $type = strtolower(substr(strrchr($src,"."),1));
    if($type == 'jpeg') $type = 'jpg';
    switch($type){
      case 'bmp': $img = imagecreatefromwbmp($src); break;
      case 'gif': $img = imagecreatefromgif($src); break;
      case 'jpg': $img = imagecreatefromjpeg($src); break;
      case 'png': $img = imagecreatefrompng($src); break;
      default : return "Unsupported picture type!";
    }
    $w_org =$w;
    $h_org =$h;
    // resize
    if($crop){
      if(($w < $width) and ($h < $height)) return "Picture is too small crop!";
      $ratio = max($width/$w, $height/$h);
      $h = $height / $ratio;
      $x = ($w - $width / $ratio) / 2;
      $w = $width / $ratio;
    }
    else{
      if($w < $width and $h < $height) return "Picture is too small!";
      $ratio = min($width/$w, $height/$h);
      $width = $w * $ratio;
      $height = $h * $ratio;
      $x = 0;
    }

    if ($crop=='2'){
      $ratio = $w_org/$h_org  ;
      if ($width/$height > $ratio) {
         $width = $height*$ratio;
      } else {
         $height = $width/$ratio;
      }
      $x = 0;
    }

    $new = imagecreatetruecolor($width, $height);

    // preserve transparency
    if($type == "gif" or $type == "png"){
      imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
      imagealphablending($new, false);
      imagesavealpha($new, true);
    }

    imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);
    $q=9/100;
    $quality*=$q;

    switch($type){
      case 'bmp': imagewbmp($new, $dst,98); break;
      case 'gif': imagegif($new, $dst,98); break;
      case 'jpg': imagejpeg($new, $dst,98); break;
      case 'png': imagepng($new, $dst,$quality); break;
    }
    return true;
  }


  function convertir_url_thumb($url){
    $ruta = explode("/", $url);
    $nombre = end($ruta);
    $ruta_x = str_replace($nombre,'',$url);
    return $ruta_x.$this->convertir_nombre_thumb($nombre);
  }

  function convertir_url_thumb_ext($url){
    $ruta = explode("/", $url);
    $nombre = end($ruta);
    $ruta_x = str_replace($nombre,'',$url);
    $n1=$this->convertir_nombre_thumb($nombre);
    $nombre_fichero = _RUTA_HOST."archivos/multimedia/".$n1;

    if (file_exists($nombre_fichero)) {
        //echo "El fichero $nombre_fichero existe";
        return $ruta_x.$n1;
    } else {
        //Aecho "el archuivo no existe";
        $n2= $this->convertir_extension($n1,"png");
        return $ruta_x.$n2;
    }

  }

  function url_add($url,$add){
	  $ruta = explode("/", $url);
    $nombre = end($ruta);
    $ruta_x = str_replace($nombre,'',$url);
    $nombrex = $this->saber_nombre_archivo($nombre);
    $extencion=$this->saber_extension_archivo($url);
    //return $ruta_x.$this->convertir_nombre_thumb($nombrex."-mini.".$extencion);
    return  $ruta_x.$nombrex.$add.".".$extencion;
  }

  function convertir_url_mini($url){
	  $ruta = explode("/", $url);
    $nombre = end($ruta);
    $ruta_x = str_replace($nombre,'',$url);
    $nombrex = $this->saber_nombre_archivo($nombre);
    $extencion=$this->saber_extension_archivo($url);
    //return $ruta_x.$this->convertir_nombre_thumb($nombrex."-mini.".$extencion);
    return  $ruta_x.$nombrex."-mini-thumb.".$extencion;
  }

  function convertir_url_extension($url,$ext){
    $ruta = explode("/", $url);
    $nombre = end($ruta);
    $ruta_x = str_replace($nombre,'',$url);
    return $ruta_x.$this->convertir_extension($nombre,$ext);
  }

  function convertir_nombre_thumb($archivo){
    $extencion=$this->saber_extension_archivo($archivo);
    $nombre = $this->saber_nombre_archivo($archivo);
    if ( ($extencion=='jpg') || ($extencion=='jpeg') || ($extencion=='png') || ($extencion=='gif')) {
        $nombre_tumb = $nombre."-thumb.".$extencion;
        return str_replace($nombre.".".$extencion, $nombre_tumb, $archivo);
    }else{
        return 'error no es una imagen';
    }
  }

  function convertir_extension($archivo,$ext){
	  $nombre = $this->saber_nombre_archivo($archivo);
	  return $nombre.".".$ext;
  }

  function saber_extension_archivo($archivo){
    $trozos = pathinfo($archivo);
    return $trozos["extension"];
  }
  function saber_nombre_archivo($archivo){
    $trozos = pathinfo($archivo);
    return $trozos["filename"];
  }

  function existe_archivo($ruta_archivo){
    if (file_exists($ruta_archivo)) { return true;}  else { return false; }
  }

  function permitir_escritura($ruta_archivo){
    chmod($ruta_archivo, 0766) or die(print_r(error_get_last(),true));
  }

  function quitar_escritura($ruta_archivo){
    chmod($ruta_archivo, 0766) or die(print_r(error_get_last(),true));
  }

}
