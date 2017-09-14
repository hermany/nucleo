<?php
  echo $fmt->header->header_modulo();
  $fmt->class_modulo->modal_editor_texto();

  $fmt->class_pagina->crear_head("27", $botones); // bd, id
?>
<div class="body-modulo container-fluid">
  <div class="container">
    <?php
      $botones = $fmt->class_pagina->crear_btn_m("Crear","icn-plus","Crear","btn btn-primary btn-menu-ajax btn-new btn-small","27","form_nuevo");
    ?>
<textarea class="editor-texto" rows="8" cols="80"></textarea>
</div>
</div>
<?php

echo $fmt->footer->footer_modulo();
 ?>
