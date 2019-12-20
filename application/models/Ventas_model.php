<?php
class Ventas_model extends CI_Model 
{

private $dbO;

    function __construct()
    {
      $this->dbO = $this->load->database('oracle', TRUE); 
    }

    public function updateDetailsSale($id,$args)
    {
      $date = date('d-m-Y H:i:s',strtotime($args['FECHA_JUEGA']));
      $sql = "UPDATE HISTORIALVENTAS SET CODIGO_BARRAS = ".$args['CODIGO_BARRAS'].",
       FECHA_JUEGA = to_date('".$date."','DD/MM/YYYY HH24:MI:SS')
        WHERE ID = $id";
      $this->dbO->query($sql);      
      if($this->dbO->affected_rows()>0){
        return true;
      } else {
        return false;
      }
    }
}
?>