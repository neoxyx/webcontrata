<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';
class Descuentos_model extends CI_Model
{

	private $dbO;

	function __construct(){
        parent::__construct();
	    $this->dbO = $this->load->database("oracle", TRUE); 
	}

	public function findAll($tipo,$id = "")
	{
	  if(!$id){
			$sql ="SELECT d.*,tc.DESCR TIPOC,tp.DESCP TIPOP FROM DESCUENTOS d
			JOIN TIPO_COMPRADOR tc ON d.IDTIPO_COMPRADOR = tc.IDTIPO_COMPRADOR 
			JOIN TIPO_PROMOCION tp ON d.IDTIPO_PROMOCION = tp.IDTIPO_PROMOCION
			WHERE d.IDTIPO_DCTO = $tipo";
			$result = $this->dbO->query($sql);
		
			if(!$result->num_rows() > 0)
			{
				return false;
			}
		
			return $result->result();
	  } else {
			$sql = "select d.*,tc.DESCR tipoc,tp.DESCP tipop from descuentos d
					join tipo_comprador tc on d.idtipo_comprador = tc.idtipo_comprador 
					join tipo_promocion tp on d.idtipo_promocion = tp.idtipo_promocion
					where idDescuento = $id";
			$result = $this->dbO->query($sql);
		
			if(!$result->num_rows() == 1)
			{
				return false;
			}
		
			return $result->row();
	  }
	}  

	public function getValueCodDiscount($cod)
	{
		$anio = date('Y');
		$now = date('d/m/y H:i:s');
		$sql1 = "SELECT * FROM DESCUENTOS WHERE CODIGO = '$cod' AND ESTADO = 1 AND CANTCUPONESDISP > 0 AND TO_DATE('$now','dd/mm/yy HH24:MI:SS') BETWEEN FECHAHORAINICIO AND FECHAHORAFIN";				
		$query = $this->dbO->query($sql1);
		if($query->num_rows() > 0){
			$sql = "SELECT SALDO_COMPROMISO FROM presup01.maestro_compromiso@loteria WHERE codigo_compromiso = ".$query->row()->COMPROMISO." AND vigencia = $anio";				
			$data = $this->dbO->query($sql);
			if($data->num_rows()>0){
				if($data->row()->SALDO_COMPROMISO < $query->row()->VRCUPON){				
					$this->sendMailAdmin($query->row()->COMPROMISO);
					return false;
				} else {				
					return $query->row();
				}
			} else {
				return false;
			}			
		} else{
			return false;
		}
	}

	public function getCant($cod)
	{
		$sql = "SELECT CANTCUPONESDISP FROM DESCUENTOS WHERE CODIGO = '".$cod."'";
		$query = $this->dbO->query($sql);
		if($query->num_rows() > 0){
			return $query->row();
		} else{
			return false;
		}
	}

	public function getCompromiso($cod)
	{
		$sql = "SELECT COMPROMISO,VRCUPON FROM DESCUENTOS WHERE CODIGO = '".$cod."'";
		$query = $this->dbO->query($sql);
		if($query->num_rows() > 0){
			return $query->row();
		} else{
			return false;
		}
	}

	public function setCant($cant,$cod)
	{
		$sql = "UPDATE DESCUENTOS SET CANTCUPONESDISP = $cant WHERE CODIGO = '".$cod."'";
		$query = $this->dbO->query($sql);
		if($this->dbO->affected_rows() > 0){
			return true;
		} else {
			return false;
		}
	}

	public function obtenerUltimoIdDcto()
	{
		$this->dbO->select_max('IDDESCUENTO');
		$result= $this->dbO->get('DESCUENTOS');
		return $result->row(); 
	}

	function sendMailAdmin($cod) 
	{
		$sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = 'yinara.velasquez@sunbeltfactory.com';
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
		$subject = 'Aviso presupuestal LottiRed.Net';
		$data = array('nombre' => 'Administrador','compromiso' => $cod);
        $bodyHtml = $this->load->view('templates/plantilla_admin.php', $data, TRUE);  
        
        $mail = new PHPMailer(true);
        try {
            // Specify the SMTP settings.
            $mail->isSMTP();
            $mail->setFrom($sender, $senderName);
            $mail->Username   = $usernameSmtp;
            $mail->Password   = $passwordSmtp;
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->CharSet    = 'utf-8';
            $mail->IsHTML(true);
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);
            // Specify the message recipients.
            $mail->addAddress($recipient);
            // You can also add CC, BCC, and additional To recipients here.
            // Specify the content of the message.
            $mail->isHTML(true);
            $mail->Subject    = $subject;
			$mail->Body       = $bodyHtml;			
            $mail->Send();
		}
		catch (phpmailerException $e) {
		$this->response('An error occurred. {$e->errorMessage()}', REST_Controller::HTTP_OK);      
		} catch (Exception $e) {
		$this->response('Email not sent. {$mail->ErrorInfo}', REST_Controller::HTTP_OK);           
		}
	}
}
?>
