<?PHP
header("Content-Type: text/html;charset=utf-8");

class CLASSDOCUMENTO{

	var $fmt;

  function __construct($fmt) {
    $this->fmt = $fmt;
  }

  function traer_docs_contenido($conte_id){
		$sql = "SELECT doc_id, doc_nombre, doc_url,doc_tipo_archivo FROM documento,contenido_documentos WHERE conte_doc_conte_id='".$conte_id."' and doc_activar=1 and conte_doc_doc_id=doc_id ORDER BY conte_doc_orden asc";
    $rss=$this->fmt->query->consulta($sql);
    $nums=$this->fmt->query->num_registros($rss);
    $aux="";
    if($nums>0){
      for($w=0;$w<$nums;$w++){
        $row= $this->fmt->query->obt_fila($rss);
        $doc_id=$row["doc_id"];
        $doc_nombre=$row["doc_nombre"];
        $doc_url=$row["doc_url"];
        $doc_tipo= $row["doc_tipo_archivo"];
        $aux .='<div class="doc-link">
          <a href="'.$doc_url.'" target="_blank" class="btn-link"><i class="icon icon-doc icon-'.$doc_tipo.'"></i> <span>'.$doc_nombre.'</span></a>
        </div>';
      }
    }
		$this->fmt->query->liberar_consulta();
		return $aux;
  }
}