<?php
header('Content-Type: text/html; charset=utf-8');


class CLASSSISTEMAS{

  var $fmt;

  function __construct($fmt){
    $this->fmt = $fmt;
  }

  function id_sistemas(){

  }

  function nombre_sistema($id_sis){
    $sql="SELECT mod_nombre FROM modulo WHERE  mod_id='$id_sis'";
    $rs = $this->fmt->query -> consulta($sql,__METHOD__);
    $fila = $this->fmt->query->obt_fila($rs);
    return $fila["mod_nombre"];
  }

  function modulos_sistema($id_sis){
    ?>
    <ul class="list-sorteable connectedSortable" id="list-mod-<?php echo $id_sis; ?>">
    <?php
    $sql="SELECT DISTINCT sis_mod_mod_id FROM sistema_modulos,sistema,modulo WHERE sis_mod_sis_id='".$id_sis."' and mod_activar=1 and sis_mod_mod_id=mod_id ORDER BY sis_mod_orden";
    // exit(0);
    $rs = $this->fmt->query -> consulta($sql,__METHOD__);
    $num = $this->fmt->query -> num_registros($rs);
    if ($num > 0){
      for ( $i=0; $i < $num; $i++){
				$row = $this->fmt->query->obt_fila($rs);
         $fila_id = $row["sis_mod_mod_id"];
        echo  "<li mod='$fila_id' sis='$id_sis' id='mod-$i'><i class='icn icn-reorder'></i> <span>".$this->nombre_sistema($fila_id)."</span></li>";
      }
    }
    ?>
    </ul>
    <script type="text/javascript">
    $( function() {
      $("#list-mod-<?php echo $id_sis; ?>" ).sortable({
        start: function(event, ui) {
          var start_pos = ui.item.index();
          ui.item.data('start_pos', start_pos);
        },
        change: function(event, ui) {
            var start_pos = ui.item.data('start_pos');
            var index = ui.placeholder.index();
            var cant = <?php echo $num; ?>;
        },
        update: function(event, ui) {
          var count=0;
          var lista="";
          $("#list-mod-<?php echo $id_sis; ?> li").each(function(i) {
            var am = $(this).attr("mod");
            if (count==0){
              lista  = am;
            }else{
              lista  = lista +":"+ am;
            }
            count++;
          });
          //console.log(lista);
          var formdata = new FormData();
          formdata.append("inputVars", lista );
          formdata.append("inputCant", count );
          formdata.append("inputSis", "<?php echo $id_sis; ?>" );
          formdata.append("ajax", "ajax-orden-sistemas-modulos");
          var ruta = "<?php echo _RUTA_WEB; ?>ajax.php";
          $.ajax({
            url:ruta,
            type:"post",
            data:formdata,
            processData: false,
            contentType: false,
            success: function(msg){
              console.log(msg);
            }
          })
        }
      });
      $( "#list-mod-<?php echo $id_sis; ?>" ).disableSelection();
    });
    </script>
    <?php
  }



  function get_data_on($ruta){
    $rx = explode ("/",$ruta);
    $con = count($rx);
    $ruta_amig = $rx[$con-2];
    $sql="SELECT cat_id,cat_id_plantilla	from categoria WHERE cat_tipo=2 AND cat_ruta_amigable ='$ruta_amig'";
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $fila=$this->fmt->query->obt_fila($rs);
    $data = array($fila['cat_id'],$ruta_amig,$fila['cat_id_plantilla']);
    return $data;
  }

  function get_data_off($ruta){
    $rx = explode ("/",$ruta);
    $con = count($rx);
    $ruta_amig = $rx[$con-2];

    return $ruta_amig;
  }

  function get_sub_cat($archivo,$id_cat_recibido,$ruta){
    $sql="SELECT cat_id, cat_ruta_amigable, cat_id_plantilla FROM categoria WHERE cat_id_padre=".$id_cat_recibido;
    $rs=$this->fmt->query->consulta($sql,__METHOD__);
    $num=$this->fmt->query->num_registros($rs);
    if($num>0){
      while ($R = $this->fmt->query->obt_fila($rs)) {
             $id_cat=$R["cat_id"];
             $ruta1=$R["cat_ruta_amigable"];
             $pla=$R["cat_id_plantilla"];

             if(!empty($ruta1)){
                $ruta_com = $ruta."/".$ruta1;
                // echo '<script type="text/javascript">alert("id_cat' . $id_cat .' ruta='.$ruta_com.' ");</script>';
                fwrite($archivo, "Rewriterule ^".$ruta_com."$  index.php?cat=".$id_cat."&pla=".$pla.PHP_EOL) or die(print_r(error_get_last(),true));
                fwrite($archivo, "Rewriterule ^".$ruta_com."/([^/]*).html$  index.php?cat=".$id_cat."&pla=3&nota=$1".PHP_EOL);

                $sql2="SELECT mod_prod_id, mod_prod_ruta_amigable FROM mod_productos, mod_productos_categorias WHERE mod_prod_id=mod_prod_cat_prod_id and mod_prod_cat_cat_id=".$id_cat;

                $rs2=$this->fmt->query->consulta($sql,__METHOD__2);
                $num2=$this->fmt->query->num_registros($rs2);
                if($num2>0){
                	while ($s = $this->fmt->query->obt_fila($rs2)) {
                	  $id_prod= $s['mod_prod_id'];
                    $ruta2 = $s['mod_prod_ruta_amigable'];
                    if (!empty($ruta2)) {
                    	fwrite($archivo, "Rewriterule ^".$ruta."/".$ruta1."/".$ruta2."$  index.php?cat=".$id_cat."&pla=2&prod=".$id_prod.PHP_EOL);
                        fwrite($archivo, "Rewriterule ^".$ruta."/".$ruta1."/".$ruta2."/pag=([0-9]+)$  index.php?cat=".$id_cat."&pla=1&prod=".$id_prod."&pag=$1".PHP_EOL);

                    }
                 }
                }

                $this->get_sub_cat($archivo,$id_cat,$ruta_com);
             }
      }
    }

  }

  function update_htaccess(){
      ini_set('memory_limit', '-1');

      if(_MULTIPLE_SITE=="on"){
         $carpetas_sitios=$this->fmt->class_sitios->traer_carpeta_sitios();
         //var_dump($carpetas_sitios);
         //exit(0);
         $nc = count($carpetas_sitios);
         for ($i=0; $i < $nc; $i++) {
          $nombre_archivo = _RUTA_SERVER.$carpetas_sitios[$i]."/.htaccess";
          $this->escribir_htaccess($nombre_archivo);
         }
         //exit(0);
      }else{
         $nombre_archivo = _RUTA_HOST.".htaccess";
      	 $this->escribir_htaccess($nombre_archivo);
      }

      //this->chmod_R($nombre_archivo, 0777);
      //$this->chmod_R($nombre_archivo,"0777");

      //if($this->fmt->archivos->existe_archivo($nombre_archivo)){
      //  $this->fmt->archivos->permitir_escritura($nombre_archivo); }

        //$this->fmt->archivos->quitar_escritura($nombre_archivo);
    }

    function escribir_htaccess($nombre_archivo){

      if(_MULTIPLE_SITE=="on"){
        $datos = $this->get_data_on($nombre_archivo);
      }else{
        $datos[1] =  $nombre_archivo;
      }

      if($archivo = fopen($nombre_archivo, "w+") or die(print_r(error_get_last(),true)))
      {
        //categorias
        fwrite($archivo, "# htaccess ".$datos[1]. PHP_EOL);
        fwrite($archivo, "# Fecha de modificacion:". date("d m Y H:m:s").PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        if (_TIPO_HTML=="http://"){

          fwrite($archivo, "Options -Indexes".PHP_EOL);
          fwrite($archivo, "RewriteCond %{SCRIPT_FILENAME} !-d".PHP_EOL);
          fwrite($archivo, "RewriteCond %{SCRIPT_FILENAME} !-f".PHP_EOL);
          fwrite($archivo, "#".PHP_EOL);

          fwrite($archivo, "RewriteEngine On".PHP_EOL);
          $ruta = str_replace("http://","www.",_RUTA_WEB);
          $ruta = str_replace("https://","www.",_RUTA_WEB);
          $ruta = str_replace("/","",$ruta);

          fwrite($archivo, "RewriteCond %{HTTP_HOST} ^".$ruta." [NC]".PHP_EOL);
          // fwrite($archivo, "RewriteCond %{HTTP_HOST} ^www\.candire\.net$ [NC]".PHP_EOL);
          fwrite($archivo, "RewriteRule ^(.*)$ "._RUTA_WEB."$1 [L,R=301]".PHP_EOL);
          fwrite($archivo, "#".PHP_EOL);
        }

        if (_TIPO_HTML=="https://"){
          fwrite($archivo, "Options +FollowSymLinks".PHP_EOL);
          fwrite($archivo, "RewriteEngine on".PHP_EOL);
          $ruta = str_replace("https://www.","",_RUTA_WEB);
          $ruta = str_replace("/","",$ruta);
          fwrite($archivo, "RewriteCond %{HTTP_HOST} ^".$ruta." [NC]".PHP_EOL);
          fwrite($archivo, "RewriteRule ^(.*)$ "._RUTA_WEB."$1 [L,R=301]".PHP_EOL);
          fwrite($archivo, "#".PHP_EOL);
        }

        fwrite($archivo, "RewriteCond %{QUERY_STRING} (;|<|>|’|”|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|query|cast|set|declare|drop|update|md5|benchmark) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} \.\./\.\. [OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (localhost|loopback|127\.0\.0\.1) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} \.[a-z0-9] [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (<|>|’|%0A|%0D|%27|%3C|%3E|%00) [NC]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (eval\() [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (127\.0\.0\.1) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} ([a-z0-9]{2000}) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (javascript:)(.*)(;) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (base64_encode)(.*)(\() [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (GLOBALS|REQUEST)(=|\[|%) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (<|%3C)(.*)script(.*)(>|%3) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (\\|\.\.\.|\.\./|~|`|<|>|\|) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (boot\.ini|etc/passwd|self/environ) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (thumbs?(_editor|open)?|tim(thumb)?)\.php [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} (\'|\")(.*)(drop|insert|md5|select|union) [NC]".PHP_EOL);
        fwrite($archivo, "RewriteRule .* - [F]".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "RewriteCond %{HTTP_USER_AGENT} ^$ [OR]".PHP_EOL);

        if (_VS_PHP=="5+"){
        fwrite($archivo, "<IfModule mime_module>".PHP_EOL);
        fwrite($archivo, "  AddType application/x-httpd-ea-php56 .php .php5 .phtml".PHP_EOL);
        fwrite($archivo, "</IfModule>".PHP_EOL);
        }

        fwrite($archivo, "RewriteCond %{HTTP_USER_AGENT} ^(java|curl|wget) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{HTTP_USER_AGENT} (winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{HTTP_USER_AGENT} (libwww-perl|curl|wget|python|nikto|scan) [NC,OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{HTTP_USER_AGENT} (<|>|’|%0A|%0D|%27|%3C|%3E|%00) [NC]".PHP_EOL);
        fwrite($archivo, "RewriteRule .* - [F]".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "RewriteCond %{REQUEST_METHOD} GET".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=http:// [OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [OR]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC]".PHP_EOL);
        fwrite($archivo, "RewriteRule .* - [F]".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "RewriteRule ^(cache|includes|logs|tmp)/ - [F]".PHP_EOL);
        fwrite($archivo, "RewriteCond %{REQUEST_FILENAME} -f".PHP_EOL);
        fwrite($archivo, "  RewriteCond %{REQUEST_URI} \.php|\.ini|\.xml [NC]".PHP_EOL);
        fwrite($archivo, "  RewriteCond %{REQUEST_URI} \/library\/ [OR]".PHP_EOL);
        fwrite($archivo, "  RewriteCond %{REQUEST_URI} \/images\/ [OR]".PHP_EOL);
        fwrite($archivo, "  RewriteCond %{REQUEST_URI} \/cache\/".PHP_EOL);
        fwrite($archivo, "  RewriteRule ^(.*)$ index.php [R=404]".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        // fwrite($archivo, "#allow access to ajax_file.php".PHP_EOL);
        // fwrite($archivo, "RewriteCond %{THE_REQUEST} ajax_\.php [NC]".PHP_EOL);
        // fwrite($archivo, "RewriteRule ^ - [NC,L]".PHP_EOL);
        // fwrite($archivo, "#disallow access to other php files".PHP_EOL);
        // fwrite($archivo, " RewriteCond %{THE_REQUEST} .+\.php [NC]".PHP_EOL);
        // fwrite($archivo, "RewriteRule ^ - [F,L]".PHP_EOL);
        // fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "<FilesMatch \" \.(php|php\.)(.+)(\w|\d)$ \" >".PHP_EOL);
        fwrite($archivo, " Order Allow,Deny".PHP_EOL);
        fwrite($archivo, " Deny from all".PHP_EOL);
        fwrite($archivo, "</FilesMatch>".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "<IfModule mod_alias.c>
          RedirectMatch 403 (?i)([a-z0-9]{2000})
        	RedirectMatch 403 (?i)(https?|ftp|php):/
        	RedirectMatch 403 (?i)(base64_encode)(.*)(\()
        	RedirectMatch 403 (?i)(=\\\'|=\\\%27|/\\\'/?)\.
        	RedirectMatch 403 (?i)/(\\$(\&)?|\*|\"|\.|,|&|&amp;?)/?$
        	RedirectMatch 403 (?i)(\{0\}|\(/\(|\.\.\.|\+\+\+|\\\"\\\")
        	RedirectMatch 403 (?i)(~|`|<|>|:|;|,|%|\\|\s|\{|\}|\[|\]|\|)
        	RedirectMatch 403 (?i)/(=|\$&|_mm|cgi-|etc/passwd|muieblack)
        	RedirectMatch 403 (?i)(&pws=0|_vti_|\(null\)|\{\$itemURL\}|echo(.*)kae|etc/passwd|eval\(|self/environ)
        	RedirectMatch 403 (?i)\.(aspx?|bash|bak?|cfg|cgi|dll|exe|git|hg|ini|jsp|log|mdb|out|sql|svn|swp|tar|rar|rdf)$
          RedirectMatch 403 (?i)/(^$|(wp-)?config|mobiquo|phpinfo|shell|sqlpatch|thumb|thumb_editor|thumbopen|timthumb|webshell)\.php
        	RedirectMatch 403 (?i)\.(^$|(.*)query|union|select|insert|query|cast|set|declare|drop|update|md5|benchmark|config|mobiquo|phpinfo|shell|sqlpatch|thumb|thumb_editor|thumbopen|timthumb|webshell)\.*
        	RedirectMatch 403 (?i)\.(^$|wp-config)\.*</IfModule>".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "<IfModule mod_setenvif.c>".PHP_EOL);
        fwrite($archivo, "	SetEnvIfNoCase User-Agent ([a-z0-9]{2000}) bad_bot".PHP_EOL);
        fwrite($archivo, "	SetEnvIfNoCase User-Agent (archive.org|binlar|casper|checkpriv|choppy|clshttp|cmsworld|diavol|dotbot|extract|feedfinder|flicky|g00g1e|harvest|heritrix|httrack|kmccrew|loader|miner|nikto|nutch|planetwork|postrank|purebot|pycurl|python|seekerspider|siclab|skygrid|sqlmap|sucker|turnit|vikspider|winhttp|xxxyy|youda|zmeu|zune) bad_bot".PHP_EOL);
        fwrite($archivo, "<limit GET POST PUT>".PHP_EOL);
        fwrite($archivo, "		Order Allow,Deny".PHP_EOL);
        fwrite($archivo, "		Allow from All".PHP_EOL);
        fwrite($archivo, "		Deny from env=bad_bot".PHP_EOL);
        fwrite($archivo, "	</limit>".PHP_EOL);
        fwrite($archivo, "</IfModule>".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "<Limit GET HEAD OPTIONS POST PUT>".PHP_EOL);
        fwrite($archivo, "	Order Allow,Deny".PHP_EOL);
        fwrite($archivo, "	Allow from All".PHP_EOL);
        fwrite($archivo, "	# uncomment/edit/repeat next line to block IPs".PHP_EOL);
        fwrite($archivo, "	# Deny from 123.456.789".PHP_EOL);
        fwrite($archivo, "</Limit>".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "RewriteRule (.*\.php)s$ $1 [H=application/x-httpd-php-source]".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);

        fwrite($archivo, "<IfModule mod_headers.c>".PHP_EOL);
        fwrite($archivo, "    <FilesMatch \"\.(eot|font.css|otf|ttc|ttf|woff|woff2)$\">".PHP_EOL);
        fwrite($archivo, "        Header set Access-Control-Allow-Origin \"*\" ".PHP_EOL);
        fwrite($archivo, "    </FilesMatch>".PHP_EOL);
        fwrite($archivo, "</IfModule>".PHP_EOL);
        fwrite($archivo, "<IfModule mod_mime.c>".PHP_EOL);
        fwrite($archivo, "AddType application/font-woff woff".PHP_EOL);
        fwrite($archivo, "AddType application/font-woff woff2".PHP_EOL);
        fwrite($archivo, "AddType application/vnd.ms-fontobject eot".PHP_EOL);
        fwrite($archivo, "AddType application/x-font-ttf ttc ttf".PHP_EOL);
        fwrite($archivo, "AddType font/opentype otf".PHP_EOL);
        fwrite($archivo, "AddType     image/svg+xml svg svgz".PHP_EOL);
        fwrite($archivo, "AddEncoding gzip svgz".PHP_EOL);
        fwrite($archivo, "</IfModule>".PHP_EOL);
        fwrite($archivo, "<ifmodule mod_expires.c>".PHP_EOL);
        fwrite($archivo, " ExpiresActive On".PHP_EOL);
        fwrite($archivo, " ExpiresDefault A3600".PHP_EOL);
        fwrite($archivo, " <FilesMatch \".(gif|jpg|jpeg|png|swf|woff|svg)$\">".PHP_EOL);
        fwrite($archivo, " # 2 weeks".PHP_EOL);
        fwrite($archivo, " ExpiresDefault A604800".PHP_EOL);
        fwrite($archivo, " Header append Cache-Control \"public\" ".PHP_EOL);
        fwrite($archivo, " </FilesMatch>".PHP_EOL);
        fwrite($archivo, " <FilesMatch \".(xml|txt|html)$\">".PHP_EOL);
        fwrite($archivo, " # 2 hours".PHP_EOL);
        fwrite($archivo, " ExpiresDefault A604800".PHP_EOL);
        fwrite($archivo, " Header append Cache-Control \"public\"".PHP_EOL);
        fwrite($archivo, " </FilesMatch>".PHP_EOL);
        fwrite($archivo, " <FilesMatch \".(js|css)$\">".PHP_EOL);
        fwrite($archivo, " # 1 days".PHP_EOL);
        fwrite($archivo, " ExpiresDefault A604800".PHP_EOL);
        fwrite($archivo, " Header append Cache-Control \"public\"".PHP_EOL);
        fwrite($archivo, " </FilesMatch>".PHP_EOL);
        fwrite($archivo, "</ifmodule>".PHP_EOL);
        fwrite($archivo, "#".PHP_EOL);



        fwrite($archivo, "Rewriterule ^dashboard$  dashboard.php".PHP_EOL);
        fwrite($archivo, "Rewriterule ^dashboard/([^/]*)$   dashboard.php?m=$1".PHP_EOL);
        fwrite($archivo, "Rewriterule ^dashboard/([^/]*)/([0-9]*)$   dashboard.php?m=$1&cat=$2".PHP_EOL);
        fwrite($archivo, "Rewriterule ^dashboard/([^/]*)/([^/]*)-catg$   dashboard.php?m=$1&catg=$2".PHP_EOL);
        fwrite($archivo, "Rewriterule ^login$  login.php".PHP_EOL);
        fwrite($archivo, "Rewriterule ^logout$  logout.php".PHP_EOL);
        fwrite($archivo, "Rewriterule ^forgot$  forgot.php".PHP_EOL);
            // if(_MULTIPLE_SITE=="on"){
            //   fwrite($archivo, "Rewriterule ^".$datos[1]."$  index.php?cat=".$datos[0]."&pla=".$datos[2].PHP_EOL);
            //   fwrite($archivo, "Rewriterule ^buscar/([^/]*)$  index.php?cat=".$datos[0]."&pla=2&q=$1".PHP_EOL);
            //   $padre_cat=$datos[0];
            //   $sql="SELECT cat_id, cat_ruta_amigable, cat_id_plantilla FROM categoria WHERE cat_id_padre=".$padre_cat;
            // }else{
            //   fwrite($archivo, "Rewriterule ^".$datos."$  index.php?cat=1&pla=1".PHP_EOL);
            //   fwrite($archivo, "Rewriterule ^buscar/([^/]*)$  index.php?cat=1&pla=2&q=$1".PHP_EOL);
            //   $datos[0]=0;
            //   $sql="SELECT cat_id, cat_ruta_amigable, cat_id_plantilla FROM categoria WHERE cat_activar=1";
            // }
            //$idx=$this->fmt->categoria->traer_id_ruta_amigable($datos[1]);
            fwrite($archivo, "Rewriterule ^buscar/([^/]*)$  index.php?cat=1&pla=2&q=$1".PHP_EOL);
            //$datos[0]=0;
            // $sql="SELECT cat_id, cat_ruta_amigable, cat_id_plantilla FROM categoria WHERE  cat_activar=1";
            $sql="SELECT cat_id, cat_ruta_amigable, cat_id_plantilla FROM categoria";
            $rs=$this->fmt->query->consulta($sql,__METHOD__);
            while ($R = $this->fmt->query->obt_fila($rs)) {
                   $id_cat=$R["cat_id"];
                   $ruta1=$R["cat_ruta_amigable"];
                   $pla=$R["cat_id_plantilla"];

                   if(!empty($ruta1)){

                     fwrite($archivo, "Rewriterule ^".$ruta1."$  index.php?cat=".$id_cat."&pla=".$pla.PHP_EOL);
                     fwrite($archivo, "Rewriterule ^".$ruta1."#([^/]*)$  index.php?cat=".$id_cat."&pla=".$pla."#$1".PHP_EOL);
                     // sitios con pla!=1
                     fwrite($archivo, "Rewriterule ^".$ruta1."/p=([0-9]+)([^/]*)$  index.php?cat=".$id_cat."&pla=$1$2".PHP_EOL);
                     // sitios con paginación
                     fwrite($archivo, "Rewriterule ^".$ruta1."/pag=([0-9]+)$  index.php?cat=".$id_cat."&pla=".$pla."&pag=$1".PHP_EOL);
                     fwrite($archivo, "Rewriterule ^".$ruta1."/([^/]*).html$  index.php?cat=".$id_cat."&pla=3&nota=$1".PHP_EOL);
                     fwrite($archivo, "Rewriterule ^".$ruta1."/prod=([0-9]+)$  index.php?cat=".$id_cat."&pla=1&prod=$1".PHP_EOL);
                     fwrite($archivo, "Rewriterule ^".$ruta1."/([^/]*).prod$  index.php?cat=".$id_cat."&pla=3&ra_prod=$1".PHP_EOL);

                     fwrite($archivo, "Rewriterule ^".$ruta1."/([^/]*)-catg$  index.php?cat=".$id_cat."&pla=2&catg=$1".PHP_EOL);

                     fwrite($archivo, "Rewriterule ^".$ruta1."/prod=([0-9]+)&ruta=([^/]*)$  index.php?cat=".$id_cat."&pla=1&prod=$1&ruta=$2".PHP_EOL);

                     //escribir en htaccess las categorias del producdo
                     $sql2="SELECT mod_prod_id, mod_prod_ruta_amigable FROM mod_productos, mod_productos_categorias WHERE mod_prod_id=mod_prod_cat_prod_id and mod_prod_cat_cat_id=".$id_cat;


                     $rs2=$this->fmt->query->consulta($sql,__METHOD__2);
                     $num2=$this->fmt->query->num_registros($rs2);
                     if($num2>0){
                      while ($s = $this->fmt->query->obt_fila($rs2)) {
                         $id_prod= $s['mod_prod_id'];
                         $ruta2 = $s['mod_prod_ruta_amigable'];
                         if (!empty($ruta2)) {
                          // fwrite($archivo, "Rewriterule ^".$ruta1."/".$ruta2."$  index.php?cat=".$id_cat."&pla=2&prod=".$id_prod.PHP_EOL);
                          //   fwrite($archivo, "Rewriterule ^".$ruta1."/".$ruta2."/pag=([0-9]+)$  index.php?cat=".$id_cat."&pla=1&prod=".$id_prod."&pag=$1".PHP_EOL);
                          fwrite($archivo, "Rewriterule ^".$ruta."/".$ruta1."/".$ruta2."$  index.php?cat=".$id_cat."&pla=2&prod=".$id_prod.PHP_EOL);
                            fwrite($archivo, "Rewriterule ^".$ruta."/".$ruta1."/".$ruta2."/pag=([0-9]+)$  index.php?cat=".$id_cat."&pla=1&prod=".$id_prod."&pag=$1".PHP_EOL);
                         }
                        }
                      }

                      $this->get_sub_cat($archivo,$id_cat,$ruta1);
                  }
            }

            fclose($archivo);
        }
    }

    function chmod_R($path, $filemode) {
      echo $path."</br>";
      echo $filemode."</br>";
      //$path = str_replace(".htaccess","",$path);
      if (!is_dir($path)){
        //echo "es archivo";
        if (file_exists ($path)) {
          //echo "es archivo y existe"."</br>";
          if (@chmod ($path, $filemode)) { //Si se le pueden dar permisos 777 al archivo
          //echo "Permiso 777 dado al archivo ".$archivo.", corra denuevo el script para seguir su función";
           return TRUE;
          }
          else { //Si no se pudieron dar los permisos
          echo "Los permisos no pudieron ser dados, trate hacerlo a mano";
          }
        }else{
          echo "es archivo pero no existe"."</br>";
        }
      }else{
        echo "es directorio"."</br>";
      }
      // $p = str_replace(".htaccess","",$path);
      // echo $p."</br>";
      // if (!is_dir($path)){
      //     return chmod($path, $filemode);
      //     //return false;
      // }
      //
      // $dh = opendir($path);
      // while (($file = readdir($dh)) !== false) {
      //      if($file != '.' && $file != '..') {
      //          $fullpath = $path.'/'.$file;
      //          if(is_link($fullpath))
      //              return FALSE;
      //          elseif(!is_dir($fullpath))
      //              if (!chmod($fullpath, $filemode))
      //                  return FALSE;
      //          elseif(!chmod_R($fullpath, $filemode))
      //              return FALSE;
      //      }
      //  }
      //
      //  closedir($dh);
      // //
      //  if(chmod($path, $filemode))
      //      return TRUE;
      //  else
      //      return FALSE;
      }
  }
?>
