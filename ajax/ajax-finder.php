<?php
  $insert= $_POST["insert"];
  $upload= $_POST["upload"];
  $seleccion= $_POST["seleccion"];
  $vars= $_POST["vars"];
  echo '<div class="finder">';
  echo ' <div class="finder-head">';
  echo '   <div class="group-tabs">';
  echo '     <div class="tab">';
  echo '       <label class="title title-finder">FINDER</label>';
  echo '       <span class="group">';
  echo '         <a class="category active" id="tab-todos" idtab="todos">Todos</a>';
  echo '         <a class="category" id="tab-imagenes" idtab="imagenes">Imagenes</a>';
  echo '         <a class="category" id="tab-albums" idtab="albums">Albums</a>';
  echo '         <a class="category" id="tab-videos" idtab="videos">Videos</a>';
  echo '         <a class="category" id="tab-audio" idtab="audio">Audio</a>';
  echo '         <a class="category" id="tab-documentos" idtab="documentos">Documentos</a>';
  echo '       </span>';
  echo '     </div>'; // tab
  echo '   </div>'; // group-tab
  echo ' </div>'; // finder-head
  echo ' <div class="finder-body">';
  echo '  <div class="tbody tab-content on" id="content-todos">';
  echo '    <div class="head-todos">';
  echo '      <form class="form box-upload-img"  method="post" id="formup">';
  echo '        <div class="box-upload">';
  echo '          <input type="file" class="inputArchivo btn" multiple name="inputArchivoFinder" id="inputArchivoFinder" >';
  echo '          <a class="btn  btn-full btn-subir-imagen"><i class="icn icn-upload"></i>Subir Archivo</a>';
  echo '        </div>';
  echo '      </form>';
  echo '      <div class="box-upload box-upload-docs">';
  echo '          <input type="file" class="inputArchivo inputDoc btn" multiple name="inputDocFinder" id="inputDocFinder" >';
  echo '          <a class="btn  btn-full btn-subir-doc"><i class="icn icn-upload"></i>Subir Documento</a>';
  echo '      </div>';
  echo '      <div class="box-upload box-upload-embed">';
  echo '        <a class="btn  btn-full btn-subir-embed" open="block-form-embed"><i class="icn icn-video-plus icn-picture"></i>Subir Embed</a>';
  echo '        <div class="block-form block-form-embed" id="block-form-embed" style="display:none">';
  echo '          <form class="form-embed" method="post" id="form-embed">';
                    $fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","","","");
                    $fmt->form->input_form("Tags:","inputTags","","","","","");
                    $fmt->form->textarea_form("Embed:","inputEmbed","","","","","5");
  echo '            <a class="btn btn-success pull-right  btn-agregar-embed">Agregar</a>';
  echo '          </form>';
  echo '        </div>';
  echo '      </div>';
  echo '      <div class="box-upload box-album">';
  echo '        <a class="btn  btn-full btn-crear-album" activo="0"><i class="icn icn-plus"></i>Crear Album</a>';
  echo '        <div class="block-form block-form-album" id="block-form-album" style="display:none">';
  echo '          <form class="form-album" method="post" id="form-album">';
  echo '            <label class="title"><h3>Nuevo Album</h3></label>';
                    $fmt->form->input_form("<span class='obligatorio'>*</span> Nombre:","inputNombre","","","","","");
                    $fmt->form->input_form("Descripción:","inputDescripcion","","","","","");
                    $fmt->form->input_form("Tags:","inputTags","","","","","");
  echo '            <a class="btn btn-success pull-right  btn-agregar-album">Agregar</a>';
  echo '            <a class="btn btn-full pull-right  btn-cancelar-album">Cancelar</a>';
  echo '          </form>';
  echo '        </div>';
  echo '      </div>';
  echo '    </div>';
  echo '    <div class="body-todos tbody">';
  echo '      <ul id="finder-figures">';
  echo '      </ul>';
  echo '    </div>';
  echo '  </div>';
  echo '  <div class="tbody tab-content" id="content-imagenes">';
  echo '    <div class="box-buscador">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar" tipo="imagenes" type="text" class="form-control" placeholder="Buscar imagen">';
  echo '    </div>';
  echo '    <ul id="finder-imagenes"></ul>';
  echo '  </div>';
  echo '  <div class="tbody tab-content" id="content-albums">';
  echo '    <div class="box-buscador">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar" tipo="albums" type="text" class="form-control" placeholder="Buscar album">';
  echo '    </div>';
  echo '    <ul id="finder-albums"></ul>';
  echo '  </div>';
  echo '  <div class="tbody tab-content" id="content-videos">';
  echo '    <div class="box-buscador">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar" tipo="videos" type="text" class="form-control" placeholder="Buscar videos">';
  echo '    </div>';
  echo '    <ul id="finder-videos"></ul>';
  echo '  </div>';
  echo '  <div class="tbody tab-content" id="content-audio">';
  echo '    <div class="box-buscador">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar" tipo="audio" type="text" class="form-control" placeholder="Buscar videos">';
  echo '    </div>';
  echo '    <ul id="finder-audio"></ul>';
  echo '  </div>';
  echo '  <div class="tbody tab-content" id="content-documentos">';
  echo '    <div class="box-buscador">';
  echo '      <i class="icn icn-search"></i>';
  echo '      <input id="filtrar" tipo="documentos" type="text" class="form-control" placeholder="Buscar Documentos">';
  echo '    </div>';
  echo '    <ul id="finder-documentos"></ul>';
  echo '  </div>';

  echo ' </div>';
  echo ' <div class="finder-footer">';
  echo '   <a class="btn btn-full btn-finder-cancelar">Cancelar</a>';
  echo '   <a class="btn btn-success btn-finder-insertar">Insertar</a>';
  echo ' </div>';// fin footer
  echo '</div>'; // fin finder
?>
