<?php

class Compromisos_model extends CI_Model
{

	private $dbO;

	function __construct(){
        parent::__construct();
	    $this->dbO = $this->load->database("oracle", TRUE); 
	}

	public function find($cod)
	{
	  $sql = "SELECT * FROM presup01.maestro_compromiso@loteria WHERE CODIGO_COMPROMISO = ".$cod."";				
	  $data = $this->dbO->query($sql);
	  	if($data->num_rows() > 0){
			return $data->row();
		} else{
			return false;
		}
	} 

	public function update($cod,$val)
	{
		$sql = "UPDATE presup01.maestro_compromiso@loteria SET SALDO_COMPROMISO = $val WHERE CODIGO_COMPROMISO = ".$cod."";
		$query = $this->dbO->query($sql);
		$this->validateBalance($cod);
		if($this->dbO->affected_rows() > 0){
			return true;
		} else{
			return false;
		}
	}

	public function validateBalance($cod)
	{
		$sql = "SELECT * FROM presup01.maestro_compromiso@loteria WHERE CODIGO_COMPROMISO = ".$cod." AND SALDO_COMPROMISO <= 300000";				
	    return $this->dbO->query($sql)->row();
	}
}
?>
