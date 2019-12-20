<?php
class Puntos_model extends CI_Model {

	private $dbO;

    function __construct(){
      $this->dbO = $this->load->database('oracle', TRUE); 
	}
	
	public function getMyPoints($id, $result=false)
	{
		$taps = "SUM(PUNTOS) AS PUNTOS"; 
		if($result)
			$taps = "*";

		$sql = "SELECT ".$taps." FROM PUNTOS WHERE USERID = '".$id."'"; 
		$query = $this->dbO->query($sql); 
		if ($query->num_rows() > 0) {	
			if($result)		
				return $query->result();
			else		
				return $query->row();
		} else {
			return false;
		}
	}

	public function insert($userId, $puntos){		
    	$sql = "INSERT into PUNTOS (USERID, PUNTOS, FECHA, ID_COMPROMISO, NUMGUIAENVIO) values (".$userId.",".$puntos.", to_date('".date('d-m-y')."','dd/mm/yyyy'),0,0)";
		$query = $this->dbO->query($sql);
		if($this->dbO->affected_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function getBeatPoints($id, $result=false)
	{
		$taps = "SUM(PUNTOS) AS PUNTOS"; 
		if($result)
			$taps = "*";

		$dateInit = '01/' . date('m') . "/" . (date('Y')-1);
		$dateEnd = $this->fullDays((date('Y')-1),date('m')).'/' . date('m') . "/" . (date('Y')-1);

		$sql = "SELECT ".$taps." FROM PUNTOS WHERE USERID = '".$id."' AND FECHA BETWEEN to_date('".$dateInit."','dd/mm/yyyy') AND to_date('".$dateEnd."','dd/mm/yyyy')";    
		
		$query = $this->dbO->query($sql);
		if ($query->num_rows() > 0) {					
			return $query->result();
		} else {
			return false;
		}
	}

	public function updatePoints($id, $upPoints)
	{
        $this->dbO->set('PUNTOS', $upPoints);
        $this->dbO->where('ID', $id);
        return $this->dbO->update('PORTAL_DML.PUNTOS');
	}

	public function expiredPointsDown($month)
	{
		
		$dateInit = '01/' . $month . "/" . (date('Y')-1);
		$dateEnd = $this->fullDays((date('Y')-1),$month).'/' . $month . "/" . (date('Y')-1);
		
		$sql = "UPDATE PUNTOS set PUNTOS = 0 WHERE FECHA BETWEEN to_date('".$dateInit."','dd/mm/yyyy') AND to_date('".$dateEnd."','dd/mm/yyyy')";    
		return $this->dbO->query($sql);
	}

	function fullDays($anho,$mes){
		if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) {
			$dias_febrero = 29;
		} else {
			$dias_febrero = 28;
		}
		if($mes == 2)
			return $dias_febrero;

		if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12)
			return 31;
		else
			return 30;
	 }

	function getPointReferens(){
		$sql = "SELECT * FROM PUNTOS_REFERIDOS WHERE ESTADO = 1";
		$query = $this->dbO->query($sql);
		return $query->row();
	}

	function insert_point_referens($input){
		$date = new DateTime($input['fechaIni']);
		$fechaIni = $date->format('d/m/Y');
		$date = new DateTime($input['fechaFin']);
		$fechaFin = $date->format('d/m/Y');

		$sql = "UPDATE PUNTOS_REFERIDOS SET ESTADO = 0";
		$this->dbO->query($sql);

		$sql = "INSERT INTO PUNTOS_REFERIDOS (PUNTOS, FECHA_INICIO, FECHA_FIN, ESTADO) values (".$input['puntos'].",to_date('".$fechaIni."','dd/mm/yyyy'), to_date('".$fechaFin."','dd/mm/yyyy'), 1)";
		$this->dbO->query($sql);
	}

	function getPointPurchase(){
		$sql = "SELECT (SELECT NOMBRE FROM LOTERIAS WHERE LOTERIA = pc.ID_LOTERIA) AS LOTERIA, pc.* FROM PUNTOS_COMPRAS pc WHERE ESTADO = 1";
		$query = $this->dbO->query($sql);
		return $query->result();
	}

	function insert_point_purchase($input){
		$date = new DateTime($input['fechaIni']);
		$fechaIni = $date->format('d/m/Y');
		$date = new DateTime($input['fechaFin']);
		$fechaFin = $date->format('d/m/Y');

		$sql = "INSERT INTO PUNTOS_COMPRAS (ID_LOTERIA, VALOR, PUNTOS, FECHA_INICIO, FECHA_FIN, ESTADO) values (".$input['loterias'].",".$input['valor'].",".$input['puntos'].",to_date('".$fechaIni."','dd/mm/yyyy'), to_date('".$fechaFin."','dd/mm/yyyy'), 1)"; 
		$this->dbO->query($sql);
	}

	function changeStatePointsPurchase($state, $id){
		$sql = "UPDATE PUNTOS_COMPRAS SET ESTADO = $state WHERE ID = $id";
		$this->dbO->query($sql);
	}

	public function getBudgetId(){
		$sql = "SELECT ID_COMPROMISO FROM PUNTOS WHERE ROWNUM = 1";
		$data = $this->dbO->query($sql)->row();
		if($data)
			return $data->ID_COMPROMISO;
		
		return '';
	}

	function changeCodeBudgetPoints($idBudget){
		$sql = "UPDATE PUNTOS SET ID_COMPROMISO = $idBudget";
		$this->dbO->query($sql);
	}

	function getPointLottery($idLottery){
		$sql = "SELECT VALOR,PUNTOS FROM PUNTOS_COMPRAS WHERE ID_LOTERIA = $idLottery AND ESTADO = 1";
		$query = $this->dbO->query($sql);
		if($query->num_rows() > 0){
			return $query->row();
		} else {
			return false;
		}
	}
}
?>
