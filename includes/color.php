
<?php
$c= array(
  // @color-white:
  "#FFFFFF",
  // @color-white-a:
  "#FEFEFE",
  // @color-white-b:
  "#FAFAFA",
  // @color-white-c:
  "#F9F9F9",
  // @color-white-d:
  "#F6F7F9",
  // @color-gray:
  "#E6E7E9",
  // @color-gray-a:
  "#D7D9DE",
  // @color-gray-b:
  "#B4BBC5",
  // @color-gray-c:
  "#879099",
  // @color-gray-d:
  "#5d6569",
  // @color-gray-e:
  "#3F444B",
  // @color-black:
  "#191B1C",
  // @color-red:
  "#EB5C43",
  // @color-red-a:
  "#E83759",
  // @color-lilac:
  "#E71882",
  // @color-lilac-a:
  "#F4A9CB",
  // @color-violet:
  "#8B3B8F",
  // @color-violet-a:
  "#DDABCE",
  // @color-violet-b:
  "#806AAD",
  // @color-violet-c:
  "#C2B4D9",
  // @color-blue:
  "#386DB4",
  // @color-blue-a:
  "#9DBAE2",
  // @color-blue-b:
  "#2D9EE0",
  // @color-blue-c:
  "#0076FF",
  // @color-light-blue:
  "#33AADD",
  // @color-light-blue-a:
  "#94D4F0",
  // @color-light-blue-b:
  "#00BDC6",
  // @color-light-blue-b:
  "#A4D9DD",
  // @color-green:
  "#24AA5B",
  // @color-green-a:
  "#CBE6B2",
  // @color-green-b:
  "#99C14C",
  // @color-green-c:
  "#DCDC07",
  // @color-green-d:
  "#FBF29D",
  // @color-green-e:
  "#0D803C",
  // @color-yellow:
  "#FFCF08",
  // @color-yellow-a:
  "#FFEBAB",
  // @color-yellow-b:
  "#FFDC51",
  // @color-orange:
  "#FCBE14",
  // @color-orange-a:
  "#F39333",
  // @color-orange-b:
  "#FAC078",
  // @color-brown:
  "#C2975C",
  // @color-brown-a:
  "#E0CBAE",
  // @color-brown-b:
  "#8A7354"
);

?>
<script type="text/javascript">
  $(".btn-color").click( function(){
    $(".colors").toggleClass("on");
  });
  $(".btn-close-color").click( function(){
    $(".colors").toggleClass("on");
  });
  $(".color").click( function(){
    var cc = $(this).attr("color");
    $("#inputColor").val(cc);
    $(".form-input-color").val(cc);
    $(".colors").removeClass("on");
  });
</script>
<div class="block-color">
  <a class="btn-color"></a>
  <div class="colors">
    <div class="theader">
      <label for="">Colores</label>
      <a class="btn-close-color"><i class="icn icn-close"></i> </a>
    </div>
    <div class="tbody">
      <?php
        $nc= count($c);
        for ($i=0; $i < $nc; $i++) {
          echo '<div class="color" color="'.$c[$i].'" style="background-color:'.$c[$i].'"><span>'.$c[$i].'</span></div>';
        }

      ?>
    </div>
  </div>
</div>
