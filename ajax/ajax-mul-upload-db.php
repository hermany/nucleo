<?php

  //if(_MULTIPLE_SITE=="on")
  $ruta_server=_RUTA_SERVER;
  /*else
  $ruta_server=_RUTA_HT;*/

  $input=$_POST["input_img"];
  $cmd="cmd";
  $dominio=$_POST["dominio"];
  $thumb_s= explode("x",$_POST["thumb"]);
  /*
if(_MULTIPLE_SITE=="on"){
	  $data=explode("/", $dominio);
	  $dato=$data[3]."/sitios/".$data[3]."/";
	  $dato_1="sitios/".$data[3]."/";
  }
  else{
  	$data=explode("/", _RUTA_DEFAULT);
	$dato="/sitios/".$data[0]."/";
	$dato_1="sitios/".$data[0]."/";
  }
*/
  $output_dir = $ruta_server.$_POST["ruta"];
  $id_dominio = $fmt->categoria->traer_id_cat_dominio($dominio);
  $table=$_POST["table"];

if (empty($_FILES[$input])) {
    echo json_encode(['error'=>'No existe archivos para subir.']);
    // or you can throw an exception
    return; // terminate
}

// get the files posted
$images = $_FILES[$input];

$success = null;

// file paths to store
$paths= [];
$widths= [];
$heigths= [];
// get file names
$filenames = $images['name'];

// loop and process files
for($i=0; $i < count($filenames); $i++){
    $ext = explode('.', basename($filenames[$i]));
    $nombre_arc=$fmt->archivos->saber_nombre_archivo($filenames[$i]);
    $nombre_amigable=$fmt->nav->convertir_url_amigable($nombre_arc);
    $target_aux = $output_dir . $nombre_amigable . "_original." . strtolower(array_pop($ext));
    $dimensiones = getimagesize($images['tmp_name'][$i]);
	$width = $dimensiones[0];
	$heigth = $dimensiones[1];

    if(move_uploaded_file($images['tmp_name'][$i], $target_aux)) {
    	$paths[] = $target_aux;
    	$widths[] = $width;
    	$heigths[] = $heigth;
		$success = true;
    } else {
        $success = false;
        $error = $target_aux;
        break;
    }
}

// check and process based on successful status
if ($success === true) {
	$nom="";
	for($i=0; $i < count($paths); $i++){
		$nombre_aux=$fmt->archivos->convertir_nombre_thumb($paths[$i]);
		$nombre_t=str_replace("_original", "", $nombre_aux);
		$nombre_normal = str_replace("_original", "", $paths[$i]);
		if($widths[$i]>960){
			$fmt->archivos->crear_thumb($paths[$i],$nombre_normal,"960","720",0);
		}
		else{
			$fmt->archivos->crear_thumb($paths[$i],$nombre_normal,$widths[$i],$heigths[$i],0);
		}
        $fmt->archivos->crear_thumb($paths[$i],$nombre_t,$thumb_s[0],$thumb_s[1],1);
		$nombre_arc=$fmt->archivos->saber_nombre_archivo($paths[$i]);
		$ext= $fmt->archivos->saber_extension_archivo($paths[$i]);
         if($ext=="mp4"){
	        $ffmpeg = "/usr/bin/ffmpeg";
	        $videoFile = $paths[$i];

	        $nombre_arc=$fmt->archivos->saber_nombre_archivo($paths[$i]);

	        $magenfile = $output_dir . $nombre_arc . ".jpg";
	        $imagenFile= $fmt->archivos->convertir_nombre_thumb($magenfile);
	        $size = $_POST["thumb"];
	        $second = 5;
	        $cmd = "$ffmpeg -i $videoFile -an -ss $second -s $size $imagenFile";
	        shell_exec($cmd);
        }
        else if($ext!="mp3"){
	        $paths[$i]=$nombre_normal;
        }

        $ruta_bd=$_POST["ruta"];
        $data=explode($ruta_bd, $paths[$i]);

        $sql1="insert into $table (".$_POST["col_id"].", ".$_POST["col_ruta"].", ".$_POST["col_dom"].") values ('".$_POST["id_mul"]."','".$ruta_bd.$data[1]."','".$id_dominio."')";
		$fmt->query->consulta($sql1,__METHOD__);
	}
     $output = ['uploaded' => $nombre_normal];
} elseif ($success === false) {
    $output = ['error'=>$error];
    // delete any uploaded files
    foreach ($paths as $file) {
        unlink($file);
    }
} else {
    $output = ['error'=>'Los archivos no se pueden procesar.'];
    //$output = ['uploaded' => $paths];
}

// return a json encoded response for plugin to process successfully
echo json_encode($output);

?>
