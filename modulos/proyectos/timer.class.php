<?php
header("Content-Type: text/html;charset=utf-8");

class TIMER{

	var $fmt;
	var $id_app;
	var $id_item;
	var $id_estado;
 

	function TIMER($fmt,$id_app=0,$id_item=0,$id_estado=0){
		$this->fmt = $fmt;
		$this->id_app = $id_app;
		$this->id_item = $id_item;
		$this->id_estado = $id_estado;
	}

	function dashboard_timer(){
		$fecha = $this->fmt->class_modulo->date_formateado("America/La_Paz","d-m-Y");
		?>
		<link rel="stylesheet" href="<?php echo _RUTA_WEB_NUCLEO;?>css/m-timer.css?reload=<?php echo $rand; ?>" rel="stylesheet" type="text/css">
		<div class="main container-fluid">
			<div class="sidebar">
				<ul>
					<li><a  class="list active"><i class="icn icn-cover"></i><span>General</span></a></li>
				</ul>
			</div>	
			<div class="body-timer">
				<div class="btn-agregar">
					<i class="icn icn-plus"></i> 
					<input type="text" id='inputTarea' placeholder="Agregar una tarea temporizada">
					<a class="btn-asigar-tiempo btn"><i class="icn icn-clock"></i><span>Asigar Tiempo</span></a>
					<span class='calendar'><i class='btn-calendar icn icn-calendar'></i> <input type='text' id='inputFecha' name='inputFecha' value='<?php echo $fecha; ?>'></span>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				resize();

				$(window).resize(function(event) {
					/* Act on the event */
					resize();
				});

				$('#inputFecha').datetimepicker({
					language:  'es',
					format: 'dd-mm-yyyy',
					autoclose: true,
					minView: 2,
					minuteStep: 5,
					weekStart: 1,
					forceParse: 0,
					todayBtn: true,
					inline: true,
          sideBySide: true
				});
				$(".datetimepicker .prev i").removeClass();
				$(".datetimepicker .next i").removeClass();
				$(".datetimepicker .prev i").addClass("icn icn-chevron-left");
				$(".datetimepicker .next i").addClass("icn icn-chevron-right");

				function resize(){
					var w = $(window).width();
					var h = $(window).outerHeight();
					var wt = w - 250;
					$(".body-timer").width(wt);
				}
			});
		</script>
		<?
	}
}