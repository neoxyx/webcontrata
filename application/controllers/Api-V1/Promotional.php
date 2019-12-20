<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';

class Promotional extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Promotional_model');
        $this->load->model('SaldosCompradores_model');
    }

    /**
     * Generate of results of scratch.
     *
     * @return Response
    */
    public function scratch_result_post($userId){
        $data = $this->post('data'); 
        $res = array ();
        foreach($data as $val){
            $idLottery = $val['lotteryId'];
            for($x=0; $x<(int)$val['fractions']; $x++){ 
                $typesPromotionals = $this->Promotional_model->getTypesPromotionals();
                $num = rand(0, 99);
                $type = 0;
                foreach($typesPromotionals as $typeProm){
                    if ($num <= $typeProm->PROMEDIO){
                        $type = $typeProm->ID;
                        break;
                    }
                }
                if($type != 0){
                    $promotionals = $this->Promotional_model->getPromotionals($type, $idLottery);
                    if(count($promotionals)>0){
                        $num = rand(0, count($promotionals));
                        if($num!=0)
                            $num--; 
                        $y=0;
                        $id=0;
                        foreach($promotionals as $promotional){ 
                            if($y == $num){
                                if($promotional->ID_TIPO == 1){
                                    $res[] = array(
                                        'icon' => 'trophy',
                                        'name' => $promotional->NOMBRE
                                    );
                                }
                                if($promotional->ID_TIPO == 2){
                                    $res[] = array(
                                        'icon' => 'usd',
                                        'name' => 'BONO POR '.$promotional->NOMBRE
                                    );
                                }
                                if($promotional->ID_TIPO == 3){
                                    $res[] = array(
                                        'icon' => 'money',
                                        'name' => '1 FRACCION GRATIS'
                                    );
                                }
                                $id = $promotional->ID;
                            }
                           $y++; 
                        }
                        
                        $this->Promotional_model->updateStatePromotional($id, 02);
                        $data = array(
                            'ID_COMPRADOR' => $userId,
                            'ID_PROMOCIONAL' => $id,
                            'ID_LOTERIA' => $idLottery,
                            'FECHA_COMPRA' => $this->post('buyDate'),
                            'FECHA_RECLAMO' => '',
                            'ESTADO' => 1,
                            'NOMBRE' => '',
                            'DIRECCION' => '',
                            'EMAIL' => '',
                            'CEDULA' => '',
                            'TELEFONO' => '',
                            'CEDULA_ARCHIVO' => '',
                            'FORMULARIO_ARCHIVO' => ''
                        );
                        $this->Promotional_model->insert($data);
                    }
                    else{
                        $res[] = array(
                            'icon' => 'frown-o',
                            'name' => 'Sigue intentando'
                        );
                    }
                }
                else{
                    $res[] = array(
                        'icon' => 'frown-o',
                        'name' => 'Sigue intentando'
                    );
                }
            }
        }
       
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * this function change a bond for the balance.
     *
     * @return Response
    */
    public function change_bond_post($userId){
        $value = $this->post('value');
        $email = $this->post('email');
        $newBalance = $this->SaldosCompradores_model->getBalance($userId) + $value; 
        $this->SaldosCompradores_model->setBalance($newBalance, $userId);
        $budgetId = $this->Promotional_model->getBudgetId($this->post('promotionalId'));
        $this->SaldosCompradores_model->subtractBudgetCommitment($budgetId->ID_COMPROMISO, $value);
        $this->Compromisos_model->validateBalance($budgetId->ID_COMPROMISO);
        if($val)
            $this->sendMailbudget($budgetId);
        $this->SaldosCompradores_model->addMove($value, $userId, $newBalance);
        $this->sendMailClaimPromotional($email, $value, $newBalance);
        $newBalance = array ('newBalance'=>$this->SaldosCompradores_model->getBalance($userId));    
        $this->Promotional_model->updateStatePromotionalXUser($this->post('promotionalId'), 03, date('d/m/y')); 
        $this->response ($newBalance, REST_Controller :: HTTP_OK);
    }

    public function sendMailbudget($cod){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = 'servicio@lottired.com';
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Bienvenido a LottiRed.Net';
        $bodyHtml = '<div style="border-style: groove;">
                        <div style="background-color: #f57c00; color: white">
                        <h2>Bienvenido a LottiRed.Net,</h2>
                            <div style="text-align:right;">
                                <img src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="text-align: justify;">
                            <p style="margin: 5%;"> 
                                El saldo del compromiso presupuestal con codigo '.$cod.' tiene menos de
                                $100000. Por favor consignar efectivo al compromiso. 
                            </p><br><br>
                            <div style="text-align:center;">
                                <img class="navbar-brand logoNav" src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="background-color: #0c6bb0; color: white; text-align:center;">
                            SERVICIO AL CLIENTE: 01 8000 41 56 84 | LottiRed.Net | servicio@lottired.com
                        </div>
                    </div>';
    
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
            return $mail->Send();
        } catch (phpmailerException $e) {
            //echo "An error occurred. {$e->errorMessage()}", PHP_EOL;
            return false;//Catch errors from PHPMailer.
        } catch (Exception $e) {
            //echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
            return false;
        }
    }

    /**
     * return the prizes promotionals of to loggin user.
     *
     * @return Response
    */
    public function index_get($userId){
        $data = $this->Promotional_model->get($userId);
        $res = array();
        foreach($data as $val){
            $idCommitment = $this->Promotional_model->getCommitment($val->LOTERIA, $val->SORTEO);

            $state = 'PENDIENTE';
            if($val->ESTADO == 2)
                $state = 'PROCESO ENTREGA';
            if($val->ESTADO == 3)
                $state = 'RECLAMADO';
            if($val->ESTADO == 4)
                $state = 'ENVIADO';
            if($val->ESTADO == 5)
                $state = 'CANCELADO';

            $res[] = array(
                'ID_LOTTERY'=>$val->LOTERIA,
                'LOTTERY'=>$val->NOMBRE,
                'DRAW'=>$val->SORTEO,
                'ABREVIATION'=>$val->ABREVIACION,
                'BUY_DATE'=>$val->FECHAVENTA,
                'CLAIM_DATE'=>$val->FECHAPAGO,
                'STATE'=>$state,
                'CONSECUTIVE'=>$val->CONSECUTIVOVENTA,
                'ID_COMMITMENT'=>$idCommitment
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    //send a email to user loggined with details of claim of a prize promotional
    public function sendMailClaimPromotional($email, $value, $newBalance){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $email;
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Cobro de bono promocional.';
        $bodyHtml = "Felicidades, usted ha cobrado el siguiente bono promocional: <br>
                     Bono: $".$value." <br>".
                    "Nuevo saldo: ".$newBalance." <br>".
                    "Estado promocional: Reclamado <br>".
                    "Cordialmente,<br><br>
                    SERVICIO AL CLIENTE<br>
                    01 8000 41 56 84 <br>
                    LottiRed.Net<br>
                    servicio@lottired.com<br><br>".
                    "<img class='navbar-brand logoNav' src='".ANGULAR_URL."admin-lottired/dist/portal/images/home/logo.png'>";   
        
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
            return $mail->Send();
        } catch (phpmailerException $e) {
            //echo "An error occurred. {$e->errorMessage()}", PHP_EOL;
            return false;//Catch errors from PHPMailer.
        } catch (Exception $e) {
            //echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
            return false;
        }
    }

    /**
     * This service registers a request to send a user's promotional prize.
     *
     * @return Response
    */
    public function promotional_request_post($userId){
        $body = json_decode($this->post('body'));
        $today = date('dmyhms');
        $today2 = date('d/m/Y');
        if($this->upFile($userId, $_FILES['dniFile'], $today) && $this->upFile($userId, $_FILES['formFile'], $today)){
            try{
                $fileDniName = $userId.$today.$_FILES['dniFile']['name'];
                $fileFormName = $userId.$today.$_FILES['formFile']['name']; 
                $data = $this->Promotional_model->updateStatePromotionalXUser($body->userPromotionalId, 02, $today2, $body->name, $body->address, $body->email, $body->dni, $body->phone, $fileDniName, $fileFormName);
                $this->sendMail($body->email, $body->value, $body->userPromotionalId);
                $res = array (                
                    'title' => "Información",
                    'text' => 'Se ha registrado, su solicitud satisfactoriamente, un operador de la lotería gestionará su ordenen, en los próximos 5 días hábiles, recibirá un correo electrónico con la fecha y hora de entrega del premio ganado.',
                    'type' => 'success',
                    'descripcion' => 'success'
                ); 
            }
            catch(Exeption $e){
                $res = array (                
                    'title' => "Error",
                    'text' => 'Se presentó un error registrando los datos, por favor intente de nuevo',
                    'type' => 'error',
                    'descripcion' => $e
                ); 
            }
        }
        else{
            $res = array (                
                'title' => "Error",
                'text' => 'Se presentó un error subiendo los archivos, por favor intente de nuevo',
                'type' => 'error'
            ); 
        }
        
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    public function upFile($userId, $file, $today){
        if(isset($file)){
            $imagen_tipo = $file['type'];
            $imagen_nombre = $userId.$today.$file['name'];
            $directorio_final = $_SERVER['DOCUMENT_ROOT']."/admin-lottired/assets/promocionales/".$imagen_nombre; 
            
            if(move_uploaded_file($file['tmp_name'], $directorio_final)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //send a email to user with details of promocional prize 
    public function sendMail($email, $value, $userPromotionalId){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $email;
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Solicitud envío premio promocional';
        $bodyHtml = '<div style="border-style: groove;">
                        <div style="background-color: #f57c00; color: white">
                        <h2>Solicitud envío premio,</h2>
                            <div style="text-align:right;">
                                <img src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="text-align: justify;">
                            <br><br>
                            <p style="margin: 5%;"> 
                                Felicidades, se ha procesado su solicitud de envío del siguiente premio promocional ganado: <br>
                                Premio: '.$value.' <br>
                                Código de referencia: '.$userPromotionalId.' <br>
                                En unos 5 días hábiles recibirá un correo con la fecha y hora de entrega de su premio
                                Cordialmente.</p><br><br>
                            <div style="text-align:center;">
                                <img class="navbar-brand logoNav" src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="background-color: #0c6bb0; color: white; text-align:center;">
                            SERVICIO AL CLIENTE: 01 8000 41 56 84 | LottiRed.Net | servicio@lottired.com
                        </div>
                    </div>';
        
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
            return $mail->Send();
        } catch (phpmailerException $e) {
            //echo "An error occurred. {$e->errorMessage()}", PHP_EOL;
            return false;//Catch errors from PHPMailer.
        } catch (Exception $e) {
            //echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
            return false;
        }
    }

    /**
     * This service update a promotional prize to user.
     *
     * @return Response
    */
    public function change_state_promotional_get($change_state_promotional){
        $this->Promotional_model->updateStatePromotionalXUser($change_state_promotional, 03, date('d/m/y')); 
        $this->response ([], REST_Controller :: HTTP_OK);
    }
    
    /**
     * register a promotional.
     *
     * @return Response
    */
    public function index_post(){
        $body = $this->input->post();
        $today = date('d/m/Y');
        
        $res = array (                
            'title' => "Información",
            'text' => 'Se ha registrado, el promocional exitosamente',
            'type' => 'green'
        );

        try{
            for($x = 0; $x < $body['UNIDADES']; $x++){
                $this->Promotional_model->insertPromotional($body, $today);
            }
        }
        catch (Exception $e) {
            $res = array (                
                'title' => "Error",
                'text' => 'Ocurrio un error registrando el promocional.',
                'type' => 'red',
                'descripcion' => $e
            );
        }
         
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * update the state.
     *
     * @return Response
    */
    public function send_prize_put(){
        $numGuia = $this->put('numGuia');
        $idSolicitud = $this->put('idSolicitud');

        $this->Promotional_model->enviarPremio($numGuia, $idSolicitud);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

     /**
     * update the state.
     *
     * @return Response
    */
    public function closed_send_put(){
        $fecha = $this->put('fecha');
        $idSolicitud = $this->put('idSolicitud');

        $this->Promotional_model->cerrarEnvio($fecha, $idSolicitud);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

     /**
     * update the state.
     *
     * @return Response
    */
    public function cancel_send_put(){
        $idSolicitud = $this->put('idSolicitud');

        $this->Promotional_model->cancelarEnvio($idSolicitud);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

    /**
     * update the state.
     *
     * @return Response
    */
    public function collection_incentive_put(){
        $ID_LOTERIA = $this->put('ID_LOTERIA');
        $SORTEO = $this->put('SORTEO');
        $VALOR = $this->put('VALOR');
        $res = array (                
            'title' => "Error",
            'text' => 'Ocurrio un error al registrar el incentivo con cobro, intente nuevamente.',
            'type' => 'red'
        );

        $val = $this->Promotional_model->collectionIncentive($ID_LOTERIA, $SORTEO, $VALOR);
        if($val){
            $res = array (                
                'title' => "Información",
                'text' => 'Se ha registrado, el incentivo promocional exitosamente',
                'type' => 'green'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * update the state.
     *
     * @return Response
    */
    public function incentives_post(){
        $datas = $this->post();
        $res2[] = array (                
            'scratch' => array(),
            'data' => array()
        );
        foreach($datas as $data){
            $resul = $this->Promotional_model->getIncentives($data['lotteryId'] , $data['draw'], $data['number'], $data['serie']);
            if($resul){
                foreach($resul as $reg){
                    $res[] = array(
                        'icon' => 'money',
                        'name' => 'Fracción gratis Numero: '.$reg->NUMERO.' y Serie: '.$reg->SERIE
                    );
                }
                $res2[] = array (                
                    'scratch' => $res,
                    'data' => $resul
                );
            }
        }
        
        $this->response ($res2, REST_Controller :: HTTP_OK);
    }

    /**
     * update a product.
     *
     * @return Response
    */
    public function validate_code_claim_get($code){
        $validation = $this->Promotional_model->validateCodeClaim($code);
        $this->response (array('validation'=>$validation), REST_Controller :: HTTP_OK);
    }
}
