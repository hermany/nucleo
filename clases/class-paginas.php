<?PHP
header("Content-Type: text/html;charset=utf-8");

class CLASSPAGINAS{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

/* ---------------- Funcion Verificar ---------------------- */

	function verificar_session(){
		include_once("sesion.php");
		$sesion= new SESION();
		$sesion->iniciar_sesion();
		$mysite_username = $sesion->get_variable("mysite_username");
		if(!$sesion->existe_variable("mysite_username"))
		{
			echo "<script>";
			echo "window.location = '../registrarse.php'";
			echo "</script>";
		}
	}

/* ---------------- Funcion crear head ---------------------- */

	function crear_head($id_mod,$botones,$var,$div_clas,$css_nucleo){

		$sql ="SELECT mod_nombre,mod_icono,mod_ruta_amigable,mod_color FROM modulo WHERE mod_id=$id_mod";

		$rs = $this->fmt->query -> consulta($sql,__METHOD__);
		$row = $this->fmt->query -> obt_fila ($rs);
		$nom = $row["mod_nombre"];
		$icon = $row["mod_icono"];
		$color = $row["mod_color"];
		$ruta_a = $row["mod_ruta_amigable"];
		$this->fmt->header->title_page($nom);
		if(!empty($css_nucleo)){
			$url_css = _RUTA_WEB_NUCLEO."css/".$css_nucleo;
			echo '<link rel="stylesheet" href="'.$url_css.'?reload" type="text/css" media="screen" />';
		}
		?>
		<div class="head-modulo head-modulo-<?php echo $ruta_a." ".$div_class; ?>">
			<div class="container">
				<h1 class="title pull-left"><i class="<?php echo $icon; ?>" style="color:<?php echo $color; ?>"></i> <?php echo $nom; ?></h1>
					<!--<a class='small btn-sync btn-menu-ajax' href="<?php echo _RUTA_WEB."dashboard/".$ruta_a; ?>" vars="<?php echo $vars; ?>">
						 <i class='icn icn-sync btn-sync'></i> -->
					</a>
					<?php if (!empty($botones)){ ?>
					<div class="head-botones pull-right">
						<?php echo $botones; ?>
					</div>
					<?php } ?>
			</div>
		</div>
		<?php
	}  // fin crear head

	function head_mod($class){
		?>
		<div class="body-modulo container-fluid <?php echo $class; ?>">
			<div class="container">
		<?php
	}
	function head_form_mod($class){
		?>
		<div class="body-modulo container-fluid <?php echo $class; ?>">
		<?php
	}

	function footer_form_mod($end=""){
    ?>
    </div> <!-- fin container-fluid mod -->
    <?php
    if ($end=="end_mod"){
    	echo "<div class='footer-modulo'></div>";
    }
  }	

  function head_body_modulo_inner($class){
    ?>
    <div class="body-modulo-inner <?php echo $class; ?>">
    <?php
  }  
  function footer_body_modulo_inner(){
    ?>
    </div> <!-- fin body_modulo_inner -->
    <?php
  }  
  function footer_modulo(){
    ?>
    <div class='footer-modulo'></div>
    <?php
  }
	function footer_mod($end=""){
    ?>  
      <?php 
        if ($end=="end_mod"){
    			echo "<div class='footer-modulo'></div>";
    		}
      ?>
      </div> <!-- fin container mod -->
    </div> <!-- fin container-fluid mod -->
    <?php
  }

	function form_ini_mod($id_form,$class){
		?>
		<form class="form form-modulo <?php echo $class; ?>"  method="POST" id="<?php echo $id_form; ?>">
		<?php
	}

	function form_fin_mod(){
		?>
			</form>
		<?php
	}

 	function tabs_mod($titulo,$id,$tabs,$icono,$tab_active="0",$div_class, $class_categoria,$aux_lat){
		$bx ="";
		?>
		<div class="block-tabs <?php echo $div_class; ?>" id="<?php echo $id; ?>">
			<div class="group-tabs">
				<div class="tabs-header">
					<?php
						if (!empty($titulo)){
					?>
					<label class="title tabs-title"><?php echo $titulo; ?></label>
					<?php
						}else{
						$bx = "group-fluid";
						}
					?>
					<div class="group <?php echo $bx; ?>">
						<?php
							$count_tabs=count($tabs);
							for ($i=0; $i < $count_tabs; $i++) {
								$active ="";
								if ($tab_active==$i){ $active="active"; }
								if ($tab_active=="0" && $i==0 ){ $active="active";   }
								?>
								<a class="category <?php echo $active." ".$class_categoria; ?>" id="tab-<?php echo $i; ?>" idtab="<?php echo $i; ?>" ><i class="<?php echo $icono[$i]; ?>"></i><?php echo $tabs[$i]; ?></a>
								<?php
							}
							echo $aux_lat;
						?>
					</div>
				</div> <!-- fin   tabs-header -->
				</div> <!-- group-tabs-->
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#<?php echo $id; ?> .category").click(function(){
					var idtab = $(this).attr("idtab");
					console.log(idtab);
					$(".tab-content").removeClass('on');
					$(".category").removeClass('active');
					$("#content-<?php echo $id; ?>-"+idtab).addClass('on');
					$("#<?php echo $id; ?> #tab-"+idtab).addClass('active');
				});
			});
		</script>
		<?php
	}

	function head_modulo_inner($title,$botones,$vars,$id_mod){
		switch ($vars) {
			case 'crear':
				$btn =$this->fmt->class_pagina->crear_btn_m("Crear","icn-plus","","btn btn-primary btn-menu-ajax btn-new btn-small",$id_mod,"form_nuevo");
				break;
			
			default:
				 $btn="";
				break;
		}
		?>
			<div class="container-fluid head-modulo-inner">
				<div class="title"><?php echo $title; ?></div>
				<div class="buttons"><?php echo $botones.$btn; ?></div>
			</div>
		<?php
	}

	function crear_head_mod( $icon, $nom,$botones,$botones_left,$class,$id_mod,$vars){
		?>
		<div class="head-modulo">

		<?php if (!empty($botones_left)){ ?>
		<div class="head-botones pull-left">
			<?php echo $botones_left; ?>
		</div>
		<?php } ?>

		<h1 class="title pull-left <?php echo $class; ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $nom; ?> <a class='small btn-sync btn-menu-ajax' id_mod="<?php echo $id_mod; ?>" vars="<?php echo $vars; ?>"><i class='icn-sync'></i></a></h1>


			<?php if (!empty($botones)){ ?>
			<div class="head-botones pull-right">
				<?php echo $botones; ?>
			</div>
			<?php } ?>
		</div>
		<?php
	}  // fin crear head

	/* ---------------- Funcion crear form ---------------------- */

		function crear_head_form( $nombre,$botones_left, $botones_right, $class_modo,$id_mod,$vars){
			$nom_mod="";
			if(!empty($id_mod))
			$nom_mod=  strtolower($this->fmt->class_modulo->nombre_modulo($id_mod));
			?>
			<div class="head-modulo row <?php echo $class_modo." head-".$nom_mod; ?> ">
			<div class="head-botones pull-left">
				 	<?php echo $botones_left; ?>
			</div>
			<h1 class="title"><?php echo $nombre; ?></h1>
			<?php if ($botones_right!=""){ ?>
				<div class="head-botones pull-right">
						<?php echo $botones_right; ?>
				</div>
			<?php } ?>
			</div>
			<?php
		}  // fin crear head

		function inicio_form($label,$id_form,$class,$botones_pre,$botones_pos){
			$this->fmt->class_pagina->crear_head_form($label,$botones_pre,$botones_pos);
			$this->fmt->class_pagina->head_form_mod();
			$this->fmt->class_pagina->form_ini_mod($id_form,$class);
		}

		function fin_form(){
			$this->fmt->class_pagina->form_fin_mod();
			$this->fmt->class_pagina->footer_form_mod();
		}

/* ---------------- Funcion btn nuevo ---------------------- */

	function crear_btn($link,$clase,$icon,$nom){
		$botones ='<a href="'.$link.'" class="'.$clase.'">
			 <i class="'.$icon.'"></i><span> '.$nom.'
			 </span></a>';
		return $botones;
	} // fin btn nuevo

	// function crear_btn($Vars,$ID_Mod,$Clase,$Icon,$Nom,$Title){
	// 	$Botones ='<a class="'.$Clase.'" title="'.$Title.'" id="btn-m'.$ID_Mod.'" id_mod="'.$ID_Mod.'" vars="'.$Vars.'">
	// 		 <i class="'.$Icon.'"></i> '.$Nom.'
	// 		 </a> ';
	// 	return $Botones;
	// } // fin btn nuevo

	function crear_btn_m($nom,$icon,$title,$clase,$id_mod,$vars,$attr,$nombre=""){
		$Botones ='<a class="'.$clase.'" title="'.$title.'" id="btn-m-'.$id_mod.'" '.$attr.' id_mod="'.$id_mod.'" nombre="'.$nombre.'"  vars="'.$vars.'">
			 <i class="'.$icon.'"></i><span> '.$nom.'
			 </span></a> ';
		return $Botones;
	} // fin btn nuevo


/* ---------------- contenido ---------------------- */

}
?>
