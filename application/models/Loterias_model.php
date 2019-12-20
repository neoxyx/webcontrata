<?php
class Loterias_model extends CI_Model {

private $dbO;

 function __construct(){
   $this->dbO = $this->load->database('oracle', TRUE); 
 }

 		public function find($id = '')
        {
		  if(!$id){
			$this->db->select('*');
			$this->db->from('loterias');
			$this->db->order_by('order', 'ASC');
			$this->db->order_by('loteria', 'ASC');
			$query = $this->db->get();
			  if( $query->num_rows() > 0){
			   return $query->result();
			  } else {
			  	  return false;
			  }
		  } else {
			$query = $this->db->get_where('loterias',array('loteria'=>$id));
			if( $query->num_rows() > 0){
			   return $query->row();
			  } else {
			  	  return false;
			  }
		  }
		}  

		public function update($id,$data){
			$this->db->where('idLoteria',$id);
			$this->db->update('loterias',$data);
			if($this->db->affected_rows()>0){
				return true;
			} else {
				return false;
			}
		}

        public function findAll($id = '')
        {
		  if(!$id){
			$sql = "SELECT lot.*, sor.SORTEO AS SORTEO,sor.DIASORTEO AS DIA,sor.FECHASORTEO AS FECHAJUEGA, lot.NOMBRE,TO_CHAR(sor.HORACIERRE,'DD-MM-YY HH24:MI:SS') AS CIERREJUEGO,sor.VALORPREMIOMAYOR,sor.FECHASORTEO FROM PORTAL_DML.LOTERIAS lot INNER JOIN CYGNUS.SORTEOS sor ON lot.LOTERIA=sor.LOTERIA AND lot.SORTEO=sor.SORTEO AND lot.ESTADO = 01";
			$query = $this->dbO->query($sql);
			  if( $query->num_rows() > 0){
			   return $query->result();
			  } else {
			  	  return false;
			  }
		  } else {
			$sql = "SELECT * from (SELECT lot.ABREVIATURA,sor.DIASORTEO AS DIA,sor.FECHASORTEO AS FECHAJUEGA, sor.SORTEO AS SORTEO,sor.VALORFRACCION, lot.NOMBRE,TO_CHAR(sor.HORACIERRE,'DD-MM-YY HH24:MI:SS') AS CIERREJUEGO,sor.VALORPREMIOMAYOR,sor.FECHASORTEO FROM PORTAL_DML.LOTERIAS lot INNER JOIN CYGNUS.SORTEOS sor ON sor.LOTERIA = lot.LOTERIA WHERE sor.loteria = ".$id." AND lot.ESTADO = 01 order by FECHASORTEO desc) WHERE ROWNUM = 1";
			$query = $this->dbO->query($sql);
			if( $query->num_rows() > 0){
			   return $query->row();
			  } else {
			  	  return false;
			  }
		  }
        }  
		
        public function get_ganador($loteria,$sorteo,$numero)
        {
            $query = $this->dbO->get_where('PORTAL_DML.LOTERIAS',array('LOTERIA'=>$loteria, 'SORTEO' => $sorteo, 'ULTIMONUMERO'=>$numero, 'ESTADO'=>01));
            if( $query->num_rows() > 0){
               return true;
            } else {
               return false;
            }
        }
        
        public function getResulSorteo($loteria)
        {
            $sql = "SELECT * FROM (SELECT * FROM PORTAL_DML.PREMIOSLOTERIAS WHERE PRODUCTO = ".$loteria." order by FECHAJUEGA desc) WHERE ROWNUM = 1";
            $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
               return $query->row();
            } else {
               return false;
            }
		}

		public function budgetCommitment($compromiso)
        {
            $sql = "SELECT saldo_compromiso FROM presup01.maestro_compromiso@loteria WHERE CODIGO_COMPROMISO = ".$compromiso;
            $query = $this->dbO->query($sql);
            if( $query->num_rows() > 0){
               return $query->row();
            } else {
               return false;
            }
		}

}

?>

