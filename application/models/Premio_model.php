<?php
class Premio_model extends CI_Model
{
	private $dbO;

    function __construct(){
      $this->dbO = $this->load->database('oracle', TRUE); 
	}

	public function get()
	{	
		$sql = "SELECT ID, puntos as points, imagen as img, UNIDADES AS units, ESTADO AS statte FROM CATALOGO WHERE UNIDADES > 0 AND ESTADO = 1";
		$query = $this->dbO->query($sql);
		if( $query->num_rows() > 0){
			return $query->result();
		} else {
			return [];
		}               			
	}

	public function addExchangeArticle($userId, $idArticle, $points){
		$aDate = getdate();
		$date = $aDate['mday']."/".$aDate['mon']."/".$aDate['year'];
		$sql = "INSERT INTO PREMIOS_CANJEADOS (USERID, CATALOGOID, COSTO, FECHACANJE, FECHAENTREGA, ESTADO, NUMGUIAENVIO) VALUES ('".$userId."','".$idArticle."','".$points."', to_date('".$date."','dd/mm/yyyy'), to_date('01/01/9999','dd/mm/yyyy'),0,0)";
		return $this->dbO->query($sql);           
	}
	
	public function discountArticle($idArticle){
		$sql = "UPDATE CATALOGO SET UNIDADES = UNIDADES-1 WHERE ID = '".$idArticle."'";
		return $this->dbO->query($sql);
	}

	public function topes(){
		$sql = "SELECT * FROM portal.parametros_web";
		$datos1 = $this->dbO->query($sql)->row();
		$sql = "SELECT * FROM cygnus.retenciones where RETENCION = 06";
		$datos2 = $this->dbO->query($sql)->row();
		$res = array(
			'MIN_PAY_WITH_BALANCE'=>$datos1->TOPE_MINIMO_PAGO_SALDO,
			'MAX_PAY_WITH_BALANCE'=>$datos1->TOPE_MAXIMO_PAGO_SALDO,
			'USER_TRANSACTION_CAP'=>$datos1->TOPE_TRANSACCION_USUARIO,
			'USER_SALE_CAP'=>$datos1->TOPE_VENTA_USUARIO,
			'PERCENT'=>$datos2->PORCENTAJE,
			'TOP'=>$datos2->TOPE
		);
		return $res;
	}

	public function findAll(){
		$sql = "SELECT pc.ID, 
					a.DESCRIPCION,
					c.NOMBRE1, 
					c.NOMBRE2, 
					c.APELLIDO1, 
					c.APELLIDO2, 
					pc.COSTO, 
					pc.FECHACANJE, 
					pc.FECHAENTREGA, 
					pc.NUMGUIAENVIO,
					pc.ESTADO 
					FROM PREMIOS_CANJEADOS pc 
					INNER JOIN COMPRADORES c ON c.ID = pc.USERID
					INNER JOIN CATALOGO a ON a.ID = pc.CATALOGOID
					ORDER BY pc.ESTADO ASC";
		return $this->dbO->query($sql)->result();
	}

	public function enviarPremio($numguiaenvio, $id){
		$sql = "UPDATE PREMIOS_CANJEADOS SET NUMGUIAENVIO = '".$numguiaenvio."', ESTADO = 1 WHERE ID = '".$id."'";
		return $this->dbO->query($sql);
	}

	public function cerrarEnvio($fecha, $id){
		$date = new DateTime($fecha);
		$fech = $date->format('d/m/Y');
		$sql = "UPDATE PREMIOS_CANJEADOS SET FECHAENTREGA = to_date('".$fech."','dd/mm/yyyy'), ESTADO = 2 WHERE ID = ".$id;
		return $this->dbO->query($sql);
	}

	public function findAllproducts(){
		$sql = "SELECT * FROM CATALOGO";
		return $this->dbO->query($sql)->result();
	}

	public function changeStateProduct($state, $idCatalogue){
		$sql = "UPDATE CATALOGO SET ESTADO = $state WHERE ID = ".$idCatalogue;
		return $this->dbO->query($sql);
	}

	public function addProduct($data, $imagen)
	{
		$sql = "INSERT INTO CATALOGO (PUNTOS, UNIDADES, ESTADO, IMAGEN, DESCRIPCION) VALUES ('".$data->puntos."','".$data->unidades."','1','".$imagen."', '".$data->descripcion."')";
		return $this->dbO->query($sql);  
	}

	public function updateProduct($data, $imagen)
	{
		if($imagen != "")
			$imagen = ", IMAGEN = '".$imagen."'";

		$sql = "UPDATE CATALOGO SET PUNTOS = ".$data->puntos.", UNIDADES = ".$data->unidades.", DESCRIPCION = '".$data->descripcion."'".$imagen." WHERE ID = ".$data->idProducto;
		return $this->dbO->query($sql);  
	}
	
	public function cancelarEnvio($id){
		$sql = "UPDATE PREMIOS_CANJEADOS SET ESTADO = 3 WHERE ID = ".$id;
		return $this->dbO->query($sql);
	}

}
?>
