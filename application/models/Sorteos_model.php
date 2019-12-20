<?php
class Sorteos_model extends CI_Model {

private $dbO;

 function __construct(){
   $this->dbO = $this->load->database('oracle', TRUE); 
 }

        public function getClose($lotteryId,$draw)
        {		  
			$sql = "SELECT TO_CHAR(HORACIERRE,'DD-MM-YYYY HH24:MI:SS') AS CIERRE FROM  cygnus.sorteos  WHERE LOTERIA = $lotteryId AND SORTEO = $draw";
			$query = $this->dbO->query($sql);
			if( $query->num_rows() > 0){
			   return $query->row();
			} else {
			  	return false;
			}		
		}
		
		public function find($lotteryId)
		{
			$sql = "SELECT * FROM SORTEOS WHERE LOTERIA = $lotteryId";
			$query = $this->dbO->query($sql);
			if( $query->num_rows() > 0){
			   return $query->result();
			} else {
			  	return false;
			}	
		}
}

?>
