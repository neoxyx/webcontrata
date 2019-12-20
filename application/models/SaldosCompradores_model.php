<?php
class SaldosCompradores_model extends CI_Model {

	private $dbO;

    function __construct(){
      $this->dbO = $this->load->database('oracle', TRUE); 
	}
	
	public function getBalance($id)
	{
		$sql = "SELECT * FROM Saldos_Compradores s WHERE s.cedula in (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$id."')";
		$query = $this->dbO->query($sql);
		if($query->num_rows() > 0){
			return $query->row()->SALDO;
		} else {
			return 0;
		}
	}

	public function setBalance($saldo, $id)
	{
		$sql = "UPDATE PORTAL_DML.Saldos_Compradores SET SALDO = '".$saldo."' WHERE CEDULA in (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$id."')";
		return $this->dbO->query($sql);
	}

	public function getMoves($id)
	{
		$sql = "SELECT * FROM Movimientos_Saldos m WHERE m.cedula in (SELECT CEDULA FROM COMPRADORES WHERE ID = '".$id."') ORDER BY m.id DESC";
		$query = $this->dbO->query($sql);
		if($query->num_rows() > 0){
			return $query->result();
		} else {
			return array();
		}
	}

	public function addMove($move, $id, $balance)
	{
		$aDate = getdate();
		$date = $aDate['mday']."/".$aDate['mon']."/".$aDate['year'];
		$sql = "INSERT INTO MOVIMIENTOS_SALDOS VALUES((SELECT ID+1 FROM(SELECT ID FROM MOVIMIENTOS_SALDOS ORDER BY ID DESC) WHERE ROWNUM = 1), ".$move.", to_date('".$date."','dd/mm/yyyy'),".$balance.",(SELECT CEDULA FROM COMPRADORES WHERE ID = '".$id."'))";
		return $this->dbO->query($sql);
	}

	public function initializeBalance($cedula){
		$saldo = 0;
		$sql = "SELECT SALDO_COMPROMISO FROM presup01.maestro_compromiso@loteria WHERE CODIGO_COMPROMISO = (SELECT ID_COMPROMISO FROM SALDO_INICIAL WHERE ESTADO = 1)"; 
		if($this->dbO->query($sql)->row() != NULL)
			$saldo = $this->dbO->query($sql)->row()->SALDO_COMPROMISO;

		$sql = "SELECT VALOR FROM SALDO_INICIAL WHERE ESTADO = 1";
		$saldoIni = $this->dbO->query($sql)->row()->VALOR;
		$val = $saldo - $saldoIni;

		if($val >= 0){
			$sql = "INSERT INTO Saldos_Compradores VALUES ('".$saldoIni."', ENCRIPTARDATOS('".$cedula."') ,(SELECT ID from PORTAL.COMPRADORES WHERE CEDULA = ENCRIPTARDATOS('".$cedula."')))";
			$this->dbO->query($sql);
	
			$sql = "UPDATE presup01.maestro_compromiso@loteria SET SALDO_COMPROMISO = SALDO_COMPROMISO - (SELECT VALOR FROM SALDO_INICIAL WHERE ESTADO = 1) WHERE CODIGO_COMPROMISO = (SELECT ID_COMPROMISO FROM SALDO_INICIAL WHERE ESTADO = 1)";
			$this->dbO->query($sql);
		}
	}  

	public function getBalanceInitial(){
		$sql = "SELECT VALOR FROM SALDO_INICIAL WHERE ESTADO = 1";
		return $this->dbO->query($sql)->row()->VALOR;
	}  

	public function getBudgetId(){
		$sql = "SELECT ID_COMPROMISO FROM SALDO_INICIAL WHERE ESTADO = 1";
		return $this->dbO->query($sql)->row()->ID_COMPROMISO;
	}  

	public function subtractBudgetCommitment($budgetId, $value){
		$sql = "UPDATE presup01.maestro_compromiso@loteria SET saldo_compromiso = saldo_compromiso - ".$value." WHERE CODIGO_COMPROMISO = ".$budgetId;
		$this->dbO->query($sql);
	}  
}
?>
