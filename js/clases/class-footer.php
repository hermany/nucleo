<?php
header('Content-Type: text/html; charset=utf-8');

class CLASSFOOTER{

  var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function footer_html(){

    $aux .= $this->js();
    $aux .= '</body>'."\n";
    $aux .= '</html>'."\n";

    return $aux;
  }

  

  function footer_modulo($modo){


    // $aux .='<script type="text/javascript" language="javascript" src="'._RUTA_WEB_SERVER.'nucleo/js/moment.js"></script>'."\n";
    // $aux .='<script src="'._RUTA_WEB_SERVER.'nucleo/js/bootstrap-datetimepicker.js"></script>'."\n";
    // $aux .='<script type="text/javascript" language="javascript" src="'._RUTA_WEB_SERVER.'nucleo/js/bootstrap-datepicker.es.js" charset="UTF-8"></script>'."\n";

  $aux .= $this->js();
  $aux .= '</div> <!-- body-page --> '."\n";


  if ($modo!="modal") {
	    // $aux  = '<div class="footer-pag" class="container-fluid">'."\n";
	    // $aux .= '2016 <i class="icn-cc"></i> Wappcom &nbsp;| &nbsp;  power  <i class="icn-zundi-o"></i>'._VZ."\n";
	    // $aux .= '</div>'."\n";
    }
    // $aux .= '</body>'."\n";
    // $aux .= '</html>'."\n";
    return $aux;


  }

  function js(){
   //$aux  = '       <script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.js"></script>'."\n";

      //$aux  = '    <script type="text/javascript" language="javascript" src="'._RUTA_WEB_SERVER.'nucleo/js/bootstrap.min.js"></script>'."\n";
    //$aux .= '       <script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/jquery.dataTables.min.js"></script>'."\n";
	//$aux .= '       <script type="text/javascript" language="javascript" src="'._RUTA_WEB.'js/dataTables.bootstrap.js"></script>'."\n";
    //$aux .= '       <script type="text/javascript" language="javascript" src="'._RUTA_WEB_SERVER.'nucleo/js/core.js"></script>'."\n";

    //$aux .= '		<script type="text/javascript" language="javascript" src="'._RUTA_WEB_SERVER.'nucleo/js/jQuery.print.min.js"></script>'."\n";

    return $aux;
  }

}

?>
