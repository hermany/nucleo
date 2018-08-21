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

  public function doc_link_cat_mes($id_cat,$mes,$ano){
    $sql = "SELECT  doc_url  FROM documento,documento_categorias WHERE doc_cat_cat_id='$id_cat' and doc_activar=1 and doc_cat_doc_id=doc_id and  MONTH(doc_fecha) = '$mes' AND YEAR(doc_fecha) = '$ano' ORDER BY doc_cat_orden desc";
    $rss=$this->fmt->query->consulta($sql);
    $row= $this->fmt->query->obt_fila($rss);    
    $this->fmt->query->liberar_consulta();
    return $row["doc_url"];
  }  

  public function doc_link($id_cat){
    $sql = "SELECT  doc_url  FROM documento,documento_categorias WHERE doc_cat_cat_id='$id_cat' and doc_activar=1 and doc_cat_doc_id=doc_id  ORDER BY doc_cat_orden desc";
    $rss=$this->fmt->query->consulta($sql);
    $row= $this->fmt->query->obt_fila($rss);    
    $this->fmt->query->liberar_consulta();
    return $row["doc_url"];
  }
}