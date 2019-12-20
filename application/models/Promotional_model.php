<?php
class Promotional_model extends CI_Model
{
	private $dbO;

    function __construct(){
      $this->dbO = $this->load->database('oracle', TRUE); 
	}

	public function getTypesPromotionals()
	{	
		$sql = "SELECT * FROM TIPO_PROMOCIONAL WHERE ID <> 4 ORDER BY PROMEDIO ASC";
		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return $query->result();
		} else {
			return false;
		}               			
	}

	public function getPromotionals($type, $idLottery)
	{	
		$sql = "SELECT * FROM PROMOCIONAL WHERE ID_TIPO = '".$type."' AND ESTADO = 1 AND ID_LOTERIA = '".$idLottery."'";
		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return $query->result();
		} else {
			return array();
		}               			
	}

	public function updateStatePromotional($id, $state)
	{	
		$data = array(
			'ESTADO' => $state
		);

		$this->dbO->where('ID', $id);
		$this->dbO->update('PROMOCIONAL', $data);         			
	}

	public function updateStatePromotionalXUser($id, $state, $claimDate="", $name="", $address="", $email="", $dni="", $phone="", $fileDniName="", $fileFormName="")
	{	$fechaRecepcion='';
		if($state==3)
			$fechaRecepcion="FECHA_RECEPCION = to_date('".$claimDate."','dd/mm/yyyy'),";

		$sql = "UPDATE PROMOCIONAL_X_USUARIO 
		SET ESTADO = '".$state."', 
		FECHA_RECLAMO = to_date('".$claimDate."','dd/mm/yyyy'), 
		".$fechaRecepcion." 
		NOMBRE = '".$name."', DIRECCION = '".$address."', 
		EMAIL = '".$email."', 
		CEDULA = '".$dni."', 
		TELEFONO = '".$phone."', 
		CEDULA_ARCHIVO = '".$fileDniName."', 
		FORMULARIO_ARCHIVO = '".$fileFormName."' 
		WHERE ID = '".$id."'";
		return $this->dbO->query($sql);
	}

	public function insert($data)
	{
		$sql = "INSERT INTO PROMOCIONAL_X_USUARIO 
		(ID_COMPRADOR, 
		ID_PROMOCIONAL, 
		ID_LOTERIA, 
		FECHA_COMPRA, 
		FECHA_RECLAMO, 
		FECHA_RECEPCION, 
		ESTADO, 
		NOMBRE, 
		DIRECCION, 
		EMAIL, 
		CEDULA, 
		TELEFONO, 
		NUMGUIAENVIO,
		CEDULA_ARCHIVO, 
		FORMULARIO_ARCHIVO) 
		values ('".$data['ID_COMPRADOR']."',
		'".$data['ID_PROMOCIONAL']."',
		'".$data['ID_LOTERIA']."',
		to_date('".$data['FECHA_COMPRA']."','dd/mm/yyyy'),
		'',
		'',
		'".$data['ESTADO']."',
		'".$data['NOMBRE']."',
		'".$data['DIRECCION']."',
		'".$data['EMAIL']."',
		'".$data['CEDULA']."',
		'".$data['TELEFONO']."',
		'',
		'".$data['CEDULA_ARCHIVO']."',
		'".$data['FORMULARIO_ARCHIVO']."')";
		return $this->dbO->query($sql);
	}

	public function get($userId)
	{	
		/*$sql = "SELECT  
				lot.NOMBRE,
				dpp.LOTERIA,
				dpp.SORTEO,
				PP.ABREVIACION,
				dpp.FECHAVENTA,
				dpp.FECHAPAGO,
				dpp.ESTADO,
				dpp.CONSECUTIVOVENTA
				FROM DetallePremiosPromocionales dpp 
				INNER JOIN PremiosPromocionales pp ON dpp.PREMIOPROMOCIONAL = pp.CODIGO
				INNER JOIN LOTERIAS lot ON dpp.LOTERIA = lot.LOTERIA
				INNER JOIN VENTASLOTERIAS vl ON dpp.CONSECUTIVOVENTA = vl.CONSECUTIVO
				INNER JOIN COMPRADORES cmp ON vl.COMPRADOR = cmp.CEDULA
				WHERE cmp.ID = $userId
				ORDER BY dpp.FECHAVENTA ASC";*/

		$sql = "SELECT  
		lot.NOMBRE,
		dpp.LOTERIA,
		dpp.SORTEO,
		PP.ABREVIACION,
		dpp.FECHAVENTA,
		dpp.FECHAPAGO,
		dpp.ESTADO,
		dpp.CONSECUTIVOVENTA
		FROM DetallePremiosPromocionales dpp 
		INNER JOIN PremiosPromocionales pp ON dpp.PREMIOPROMOCIONAL = pp.CODIGO
		INNER JOIN LOTERIAS lot ON dpp.LOTERIA = lot.LOTERIA
		WHERE dpp.SORTEO = 3968 and rownum <= 20
		ORDER BY dpp.FECHAVENTA ASC";		

		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return $query->result();
		} else {
			return array();
		}               			
	}
	
	public function insertPromotional($data, $today)
	{
		$sql = "INSERT INTO PROMOCIONAL (ID_TIPO, NOMBRE, VALOR, ESTADO, FECHA_REGISTRO, ID_LOTERIA, ID_COMPROMISO)
		values (".$data['ID_TIPO'].",
		'".$data['NOMBRE']."',
		1,
		1,
		to_date('".$today."','dd/mm/yyyy'),
		'".$data['ID_LOTERIA']."',
		'".$data['COMPROMISO']."')";
		return $this->dbO->query($sql);
	}

	public function getPromotionalsUsers()
	{	
		$sql = "SELECT PU.ID,
				LT.NOMBRE AS LOTTERY, 
				LT.LOTERIA AS ID_LOTTERY, 
				TP.NOMBRE AS TYPE_PROMOTIONAL, 
				PT.NOMBRE AS VALUE, 
				PU.FECHA_COMPRA AS BUY_DATE, 
				PU.FECHA_RECLAMO AS CLAIM_DATE,
				PU.FECHA_RECEPCION AS RECEPTION_DATE,
				PU.ESTADO AS STATE,
				PU.NOMBRE AS NAME,
				PU.EMAIL AS EMAIL, 
				PU.DIRECCION AS ADDRESS,
				PU.TELEFONO AS PHONE,
				PU.NUMGUIAENVIO AS NUMGUIAENVIO,
				PU.CEDULA_ARCHIVO AS DNI_FILE,
				PU.FORMULARIO_ARCHIVO AS FORM_FILE,
				PU.ID AS PROMOTIONAL_USER_ID
				FROM PROMOCIONAL_X_USUARIO PU
				INNER JOIN LOTERIAS LT ON PU.ID_LOTERIA = LT.LOTERIA
				INNER JOIN PROMOCIONAL PT ON PU.ID_PROMOCIONAL = PT.ID
				INNER JOIN TIPO_PROMOCIONAL TP ON PT.ID_TIPO = TP.ID
				INNER JOIN COMPRADORES C ON PU.ID_COMPRADOR = C.ID
				WHERE PU.ESTADO = 2 OR PU.ESTADO = 4 ORDER BY PU.FECHA_COMPRA DESC";

		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return $query->result();
		} else {
			return array();
		}               			
	}

	public function enviarPremio($numguiaenvio, $id){
		$sql = "UPDATE PROMOCIONAL_X_USUARIO SET NUMGUIAENVIO = '".$numguiaenvio."', ESTADO = 4 WHERE ID = '".$id."'";
		return $this->dbO->query($sql);
	}

	public function cerrarEnvio($fecha, $id){
		$date = new DateTime($fecha);
		$fech = $date->format('d/m/Y');
		$sql = "UPDATE PROMOCIONAL_X_USUARIO SET FECHA_RECEPCION = to_date('".$fech."','dd/mm/yyyy'), ESTADO = 3 WHERE ID = ".$id;
		return $this->dbO->query($sql);
	}

	public function cancelarEnvio($id){
		$sql = "UPDATE PROMOCIONAL_X_USUARIO SET ESTADO = 5 WHERE ID = ".$id;
		return $this->dbO->query($sql);
	}

	public function collectionIncentive($ID_LOTERIA, $SORTEO, $VALOR){
		$sql = "UPDATE SORTEOS SET VALORFRACCION = ".$VALOR." WHERE LOTERIA IN (SELECT PRODUCTO_INCENTIVO FROM SORTEOSINCENTIVO WHERE PRODUCTO = ".$ID_LOTERIA." AND SORTEO = ".$SORTEO.") AND SORTEO = ".$SORTEO;
		return $this->dbO->query($sql);
	}

	public function getBudgetId($promotionalId){
		$sql = "SELECT ID_COMPROMISO FROM PROMOCIONAL WHERE ID IN (SELECT ID_PROMOCIONAL FROM PROMOCIONAL_X_USUARIO WHERE ID = ".$promotionalId.")";
		return $this->dbO->query($sql)->row();
	}

	public function getIncentives($ID_LOTERIA, $SORTEO, $NUMERO, $SERIE){
		$sql = "SELECT * from NUMEROLOTERIAINCENTIVO WHERE PRODUCTO = ".$ID_LOTERIA." AND SORTEO = ".$SORTEO." AND NUMERO = ".$NUMERO." AND SERIE = ".$SERIE;
		return $this->dbO->query($sql)->result();
	}

	public function validateCodeClaim($code){
		$data = explode('-',$code);
		$sql = "SELECT * from DetallePremiosPromocionales WHERE CLAVERECLAMO = '".$data[0]."' AND CONSECUTIVOVENTA = ".$data[1];
		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return true;
		} else {
			return false;
		}  
	}

	public function getCommitment($lottery, $draw){
		$sql = "SELECT COMPROMISO FROM PROMOCIONALES_CONFIGURACION WHERE LOTERIA = $lottery AND SORTEO = $draw";
		$data = $this->dbO->query($sql);
		if( $data->num_rows() > 0)
			return $data->row()->COMPROMISO;
		return array();	
	}
}
?>
