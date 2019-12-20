<?php
class Numeros_model extends CI_Model {

private $dbO;

 function __construct(){
   $this->dbO = $this->load->database('oracle', TRUE); 
 }

        public function findAll()
        {		  
            $query = $this->dbO->get('PORTAL_DML.PREMIOSLOTERIAS');            
            if( $query->num_rows() > 0){
                return $query->result();
            } else {
                return false;
            }
        
        }  

        public function find($sorteo, $serie, $num, $loteria)
        {		  
            $query = $this->dbO->get_where('PORTAL_DML.PREMIOSLOTERIAS',array( 'SORTEOLOTERIA' => $sorteo,'SERIE'=> $serie,'NUMERO'=>$num,'PRODUCTO'=>$loteria));            
            if( $query->num_rows() > 0){
                return $query->row();
            } else {
                    return false;
            }  
        }  
               
        public function findDates($loteria)
        {		  
            $sql = 'select count(*), FECHAJUEGA as drawDate, sorteoloteria as draw from PORTAL_DML.PREMIOSLOTERIAS WHERE PRODUCTO = '.$loteria.' GROUP BY FECHAJUEGA, SORTEOLOTERIA ORDER BY FECHAJUEGA DESC';
            $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
                return $query->result();
              } else {
                return false;
              }  
        } 
        
        public function registrosHistoricos($loteria, $fecha)
        {		  
            $date = strtotime($fecha);
            $fech = date('d/M/y',$date); 
            $sql = "select * from PORTAL_DML.PREMIOSLOTERIAS WHERE PRODUCTO = ".$loteria."  AND FECHAJUEGA = '".$fech."' AND FRACCION = '1' ORDER BY CODIGOPREMIO DESC"; 
            $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
               return $query->result();
              } else {
                      return false;
              }  
        } 
}
?>