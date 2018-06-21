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
		require _RUTA_NUCLEO.'modulos/proyectos/proyectos.class.php';
		$proyecto = new PROYECTO($this->fmt);
		$fecha = $this->fmt->class_modulo->date_formateado("America/La_Paz","d-m-Y");
		$id_usu = $this->fmt->sesion->get_variable("usu_id");
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
					<i class="icn icn-plus icon-inicio"></i> 
					<input type="text" id='inputTarea' placeholder="Agregar una tarea temporizada">
					<a class="btn-asigar-tiempo btn" valor="0.25"><i class="icn icn-clock"></i><span>Asigar Tiempo</span></a>
					<div class="box-asignar-tiempo">
						<a class="btn-a-tiempo" valor="0.10" tm="10 min.">10 min.</a>
						<a class="btn-a-tiempo" valor="0.25" tm="25 min.">25 min.</a>
						<a class="btn-a-tiempo" valor="1" tm="1 hr.">1 hr.</a>
						<a class="btn-a-tiempo" valor="2" tm="2 hr.">2 hr.</a>
						<a class="btn-a-tiempo" valor="3" tm="3 hr.">3 hr.</a>
						<a class="btn-a-tiempo" valor="4" tm="4 hr.">4 hr.</a>
						<a class="btn-a-tiempo" valor="8" tm="8 hr.">8 hr.</a>
						<a class="btn-a-tiempo" valor="0" tm="Sin Limite">Sin Limite</a>
					</div>
					<span class='calendar'><i class='btn-calendar icn icn-calendar'></i> <input type='text' id='inputFechax' name='inputFechax' value='<?php echo $fecha; ?>'></span>
					<a class="btn-proyecto" proy="0"><i class="icon icn icn-project-plan" style=""></i><span>Proyecto</span></a>
					<div class="box-proyectos">
						<div class="buscador">
							<i class="icn icn-search"></i>
							<input type="text" id='inputBuscarProyecto' placeholder="Buscar Proyecto">
						</div>
						<div class="list-proyectos">
						<?php 
							echo $proyecto->proyectos_activos();
						?>
						</div>
					</div>
					<a class="btn-iniciar">iniciar</a>
				</div>
				<div class="lista-tareas">
					<?php 
						$consulta = "SELECT mod_tar_id,mod_tar_descripcion FROM mod_tarea, mod_tarea_proyectos WHERE mod_tar_proy_usu_id='$id_usu' and mod_tar_estado='1' ORDER BY mod_tar_fecha_hora_inicio DESC";
						$rs =$this->fmt->query->consulta($consulta);
						$num=$this->fmt->query->num_registros($rs);
						if($num>0){
							for($i=0;$i<$num;$i++){
								$row=$this->fmt->query->obt_fila($rs);
								$row_id = $row["mod_tar_id"];
								$row_id = $row["mod_tar_descripcion"];
								echo '<div class="tarea"><span class="detalle">';
								echo '<div class="descripcion"><i class="icn icn-unchecked"></i> Aqui viene la descripción de la tarea</div>';
								echo '<div class="tiempo"><i class="icn icn-clock"></i><span>00:25</span></div>';
								echo '<div class="calendar"><i class="btn-calendar icn icn-calendar"></i> <span>hoy</span></div>';
								echo '<div class="proy"><i class="icon-proy"></i> <span>sistema...</span></div>';
								echo '<div class="botones">';
								echo '<a class="btn btn-full"><i class="icn icn-pencil"></i></a>';
								echo '<a class="btn btn-full"><i class="icn icn-trash"></i></a>';
								echo '<a class="btn btn-full">Cancelar</a>';
								echo '<a class="btn btn-full">Pausar</a>';
								echo '</div>';
								echo '</div>';
							}
						}
						$this->fmt->query->liberar_consulta($rs);
					?>
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

				$(".btn-iniciar").click(function(event) {
					/* Act on the event */
					var tarea = $("#inputTarea").val();
					var tiempo = $(".btn-asigar-tiempo").attr('valor');
					var proy = $(".btn-proyecto").attr('proy');

					var ruta_ajax = "ajax-timer-agregar-tarea";
          var variables = tarea + "," + tiempo + "," + proy;
          var datos = {ajax:ruta_ajax, inputIdMod:"" , inputVars : variables };
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";   
          $.ajax({ 
            url:ruta,
            type:"post",  
            async: true,   
            data:datos, 
            beforeSend: function () {},
            success:function(msg){  
               console.log(msg);
               var dat = msg.split(":");
               if (dat[0]=="ok"){
               	$(".list-proyectos").prepend('<div class="tarea"><span class="nombre"></div></div>')
               }
               // $('.btn-cambiar-estado[item='+dat[0]+']').attr("estado",dat[1]);
               // $('.btn-cambiar-estado[item='+dat[0]+']').removeClass('disabled');
               // var est = estados(dat[1]);
               // $('.btn-cambiar-estado[item='+dat[0]+']').html(est);
            },
            complete: function(){}
          });

				});

				//tiempo

				$('.box-asignar-tiempo').mouseleave(function(event) {
					$(this).removeClass('on');
				});

				$('.btn-asigar-tiempo').click(function(event) {
					$(".box-asignar-tiempo").addClass("on");
				});

				$('.btn-a-tiempo').click(function(event) {
					var tiempo = $(this).attr("valor");
					var tm = $(this).attr("tm");
					$('.btn-asigar-tiempo').attr('valor',tiempo);
					$('.btn-asigar-tiempo span').html(tm);
					$(".box-asignar-tiempo").removeClass('on');
				});

				$('#inputBuscarProyecto').keyup(function () {
			    var rex = new RegExp($(this).val(), 'i');
			    $('.list-proyectos .proy').hide();
			    $('.list-proyectos .proy').filter(function () {
			        return rex.test($(this).text());
			    }).show();
			  });

			  $('#inputTarea').keyup(function () {
			  	var text = $(this).val();
			  	$('.icon-inicio').removeClass('icn-plus');
			  	$('.icon-inicio').removeClass('icn-unchecked');
			  	if (text==""){
			  		$('.icon-inicio').addClass('icon-inicio icn icn-plus');
			  	}else{
						$('.icon-inicio').addClass('icon-inicio icn icn-unchecked');
			  	}
			  });

			  //proyectos

			  $('.btn-proyecto').click(function(event) {
			  	$(".box-proyectos").addClass("on");
			  });

			  $('.box-proyectos').mouseleave(function(event) {
					$(this).removeClass('on');
				});

				$('.box-proyectos .proy').click(function(event) {
					var proy = $(this).attr("proy");
					var logo = $(this).attr("logo");
					var nombre = $(this).attr("nombre");
					$(".btn-proyecto").attr('proy', proy);
					$(".btn-proyecto .icn").removeClass('icn-project-plan');
					$(".btn-proyecto .icn").addClass('icn-proy');
					$(".btn-proyecto .icn").attr('style','background:url('+logo+') no-repeat center center');
					$(".btn-proyecto span").html( nombre.substr(0,7)+"...");
					$(".btn-proyecto span").attr("title",nombre);
					$('.box-proyectos').removeClass('on');
				});


			  //calendario

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

					var pst = $(".btn-asigar-tiempo").offset();
					var psp = $(".btn-proyecto").offset();
					// console.log(pst.left);
					$(".box-asignar-tiempo").css("left",pst.left +"px");
					$(".box-proyectos").css("left",psp.left - 200 +"px");
				}
			});
		</script>
		<?
	}
}