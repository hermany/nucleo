 $(document).ready(function() {
	
	$('.btn-agregar-instructor-list').click(function(event) {
		$('.box-seleccion-instructor').addClass('on');
	});

  $('.box-seleccion-instructor').mouseleave(function(event) {
    $('.box-seleccion-instructor').removeClass('on');
  });

	$("#inputBuscadorIns").keyup(function () {
    var rex = new RegExp($(this).val(), "i");
    $(".instructores .item-ins").hide();
    $(".instructores .item-ins").filter(function () {
        return rex.test($(this).text());
    }).show();
  });

  $(".item-ins").click(function(event) {
  	var item = $(this).attr('item')[0];
  	var nombre = $(this).attr('nom');

  	$('.list-instructores').addClass('on');

  	$('.list-instructores').append('<div class="item item-ins-list-'+item+'">'+nombre+' <i class="icn icn-close btn-quitar-item-ins" item="'+item+'"></i><input name="inputIns[]" id="cat-'+item+'" type="hidden" value="'+item+'"></div>');
  	$('.box-seleccion-instructor').removeClass('on');
    $(".item-ins-"+item).addClass('ocultar');

    $("#inputBuscadorIns").val("");
    $(".item-ins").show();

    $(".btn-quitar-item-ins").click(function(event) {
      var item = $(this).attr('item');
      $(".item-ins-list-"+item).remove();
      $(".item-ins-"+item).removeClass('ocultar');

      if ($('.list-instructores').html()){
        $('.list-instructores').removeClass('on');
      } 

    });
  });

});
 