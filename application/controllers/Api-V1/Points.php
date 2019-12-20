<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';

class Points extends REST_Controller {
    
    function __construct(){
        parent::__construct(); 
        $this->load->model('Puntos_model');
        $this->load->model('Premio_model');
        $this->load->model('SaldosCompradores_model');
        $this->load->model('Compromisos_model');
    }

    /**
     * Return the points of logged-in user.
     *
     * @return Response
    */
    public function index_get($userId){
        $today = getdate();
        if($today['mday']!=1)
            $this->Puntos_model->expiredPointsDown($today['mon']-1); 
        $this->response ($this->Puntos_model->getMyPoints($userId),REST_Controller :: HTTP_OK);
    }
    /**
     * insert points
     */
    public function index_post(){
        $userId = $this->post('userId');
        $sale = $this->post('sale');
        $sum = 0;
        for($i=0; $i<count($sale["return"]); $i++) {            
            $punto = $this->Puntos_model->getPointLottery($sale["return"][$i]["loteria"]);
            if($punto)
                $puntos = $punto->PUNTOS;
            $val = $this->Puntos_model->getPointLottery($sale["return"][$i]["loteria"]);
            if($val) 
                $valor = $val->VALOR;
            $puntosLoteria = ($sale["return"][$i]["totalVenta"] * $puntos) / $valor;
            $sum = $sum + $puntosLoteria;
        }  
        $res = $this->Puntos_model->insert($userId, $sum);
        if($res){
            $this->response($this->Puntos_model->getMyPoints($userId),REST_Controller::HTTP_OK);
        } else {
            $this->response(["error"=>"error al incluir los puntos"],REST_Controller::HTTP_OK);
        }
        
    }
    /**
     * update points
     */
    public function index_put($userId){
        $points = $this->put('points');
        $this->Puntos_model->updatePoints($userId,$points);
        $this->response("Puntos agregados exitosamente", REST_Controller :: HTTP_OK);
    }

    /**
     * returns the logged-in user points that are about to expire.
     *
     * @return Response
    */
    public function beat_points_get($userId){
        $data = $this->Puntos_model->getBeatPoints($userId);
        if($data[0]->PUNTOS != null)
            $this->response ($data, REST_Controller :: HTTP_OK);
        else    
            $this->response ([array('PUNTOS'=>0)], REST_Controller :: HTTP_OK);
    }

    /**
     * change a value points for balance.
     *
     * @return Response
    */
    public function change_points_post($userId){
        $points = $this->post('points');
        $data = $this->Puntos_model->getBeatPoints($userId, true); 
        $newBalance = $this->SaldosCompradores_model->getBalance($userId) + $points; 
        $this->SaldosCompradores_model->setBalance($newBalance, $userId);
        $point = $points;
        if($data){
            foreach($data as $val){
                if($points>0){
                    $points = $this->discount_points($points, $val->PUNTOS, $val->ID);
                }
            }  

        }
        if($points>0){
            $data = $this->Puntos_model->getMyPoints($userId, true); 
            if(count($data)>0){
                foreach($data as $val){
                    $points = $this->discount_points($points, $val->PUNTOS, $val->ID);
                }
            }
        }
        $this->SaldosCompradores_model->addMove($point, $userId, $newBalance);
        $newBalance = array ('newBalance'=>$this->SaldosCompradores_model->getBalance($userId));     
        $budgetId = $this->Puntos_model->getBudgetId();
        $this->SaldosCompradores_model->subtractBudgetCommitment($budgetId, $point);
        $this->Compromisos_model->validateBalance($budgetId);
        if($val)
            $this->sendMailbudget($budgetId);
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
     * change a value points for an article.
     *
     * @return Response
    */
    public function points_x_articule_post($userId){
        try{
            $post = $this->post();
            $idArticle = $post[0]['idArticle'];
            $points = $post[1]['pointsArticle'];
            $email = $post[2]['email'];
            $image = $post[3]['image'];
            $data = $this->Puntos_model->getBeatPoints($userId, true); 
            if($data){
                foreach($data as $val){
                    $points = $this->discount_points($points, $val->PUNTOS, $val->ID);
                }
            }
            if((int)$points>0){
                $data = $this->Puntos_model->getMyPoints($userId, true); 
                foreach($data as $val){
                    $points = $this->discount_points($points, $val->PUNTOS, $val->ID);
                }
            }
            $res = array (
                'state' => 'success',
                'tittle' => 'Felicitaciones',
                'message' => "Se ha guardado su solicitud de redención de puntos, nuestros operadores gestionaran su solicitud y en el transcurso de 5 dias hábiles, estaremos enviando un correo con la fecha de envío de su premio.",
            ); 
            $this->Premio_model->addExchangeArticle($userId, $idArticle, $post[1]['pointsArticle']); 
            $data = $this->Puntos_model->getMyPoints($userId);
            $this->Premio_model->discountArticle($idArticle);
            $this->sendMail($email, $image, $post[1]['pointsArticle'], $data->PUNTOS);
        }
        catch (Exception $e) {
            $res = array (
                'state' => 'error',
                'tittle' => 'Error',
                'code' =>  http_response_code(),
                'message' => "No se pudo procesar su solicitud en este momento, por favor intente mas tarde",
                'description' => $e
            );
        }
        
        $this->response ($res, REST_Controller :: HTTP_OK);                
    }

    // discoint the points of the balance to the user
    public function discount_points($totalPoints, $points, $idPoints){
        if((int)$totalPoints>0){
            $totalPoints = $totalPoints - $points;
            $upPoints = 0;
            if($totalPoints<0){
                $upPoints = $totalPoints * -1;
            }
            $this->Puntos_model->updatePoints($idPoints, $upPoints);
        }
        return $totalPoints;
    }

    //send a email to user with details of prize excange for the points
    public function sendMail($email, $image, $points, $balancePoints){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $email;
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Canjeo de puntos';
        $bodyHtml = '<div style="border-style: groove;">
                        <div style="background-color: #f57c00; color: white">
                        <h2>Canjeo de puntos,</h2>
                            <div style="text-align:right;">
                                <img src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="text-align: justify;">
                            <br><br>
                            <p style="margin: 5%;"> 
                                Felicidades, se ha procesado su solicitud de canjeo de puntos por el siguiente premio: <br>
                                Premio: <img src="'.ANGULAR_URL.'assets/images/prizes/'.$image.'"> <br>
                                Puntos redimidos: '.$points.' <br>
                                Puntos restantes: '.$balancePoints.' <br>
                                Estado pedido: Solicitud enviada <br>
                                Cordialmente. </p><br><br>
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
     * update the state.
     *
     * @return Response
    */
    public function send_prize_put(){
        $numGuia = $this->put('numGuia');
        $idCanje = $this->put('idCanje');

        $this->Premio_model->enviarPremio($numGuia, $idCanje);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

     /**
     * update the state.
     *
     * @return Response
    */
    public function closed_send_put(){
        $fecha = $this->put('fecha');
        $idCanje = $this->put('idCanje');

        $this->Premio_model->cerrarEnvio($fecha, $idCanje);
        $this->response ([], REST_Controller :: HTTP_OK);
    }
    
     /**
     * insert a value of points for to refered.
     *
     * @return Response
    */
    public function insert_point_referens_post(){
        try{
            $data = $this->Puntos_model->insert_point_referens($this->input->post()); 
            $res = array (
                'content' => 'Los datos se registraron, exitosamente.',
                'title' => '¡Información!',
                'type' =>  'green'
            );
        }catch (Exception $e) {
            $res = array (
                'content' => 'Se produjo un error registrando los datos, intente nuevamente.',
                'title' => 'Error',
                'type' =>  'red',
                'description' => $e
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

     /**
     * update the state a product.
     *
     * @return Response
    */
    public function change_state_points_purchase_put(){
        $state = $this->put('state');
        $id = $this->put('id');

        $this->Puntos_model->changeStatePointsPurchase($state, $id);
        $this->response ([], REST_Controller :: HTTP_OK);
    } 

     /**
     * insert a value of points for to purchase.
     *
     * @return Response
    */
    public function insert_point_purchase_post(){
        try{
            $data = $this->Puntos_model->insert_point_purchase($this->input->post()); 
            $res = array (
                'content' => 'Los datos se registraron, exitosamente.',
                'title' => '¡Información!',
                'type' =>  'green'
            );
        }catch (Exception $e) {
            $res = array (
                'content' => 'Se produjo un error registrando los datos, intente nuevamente.',
                'title' => 'Error',
                'type' =>  'red',
                'description' => $e
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

     /**
     * update the state.
     *
     * @return Response
    */
    public function cancel_send_put(){
        $idCanje = $this->put('idCanje');

        $this->Premio_model->cancelarEnvio($idCanje);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

    /**
     * update the code of budget.
     *
     * @return Response
    */
    public function change_code_budget_points_put(){
        $idBudget = $this->put('compromiso');

        $this->Puntos_model->changeCodeBudgetPoints($idBudget);
        $this->response ([], REST_Controller :: HTTP_OK);
    }

     /**
     * return of code commitment the points.
     *
     * @return Response
    */
    public function code_commitment_get(){
        $this->response (array ('CODE' => $this->Puntos_model->getBudgetId()), REST_Controller :: HTTP_OK);
    }
}
