<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';

class User extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Comprador_model');
        $this->load->model('Puntos_model');
        $this->load->model('Compromisos_model');
        $this->load->model('SaldosCompradores_model');
        $this->load->helper(['jwt', 'authorization']);
    }

    /**
     * Return the data of user with started session.
     *
     * @return Response
    */
    public function index_get($userId){
        $data = $this->Comprador_model->findXid($userId);
        $res = array(
            'FECHANACIMIENTO' => (new DateTime($data->FECHANACIMIENTO))->format('Y-m-d'),
            'SEXO' => $data->SEXO,
            'EMAIL' => $data->EMAIL,
            'TELEFONO' => $data->TELEFONO,
            'CELULAR' => $data->CELULAR,
            'DEPARTAMENTO' => $data->DEPARTAMENTO,
            'CIUDAD' => $data->CIUDAD,
            'DIRECCION' => $data->DIRECCION,
            'PRIMER_NOMBRE' => $data->NOMBRE1,
            'SEGUNDO_NOMBRE' => $data->NOMBRE2,
            'PRIMER_APELLIDO' => $data->APELLIDO1,
            'SEGUNDO_APELLIDO' => $data->APELLIDO2,
            'CEDULA' => $data->CEDULA1
        );
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * Update buyer information.
     *
     * @return Response
    */
    public function index_put(){
        $fnac = date('d/m/Y', strtotime($this->put('dateNac')));
        $data = $this->Comprador_model->updateUser($this->put('userId'), $fnac, $this->put('sex'), $this->put('email'), $this->put('phone'), $this->put('cel'), $this->put('departament'), $this->put('city'), $this->put('address'));
        if($data){
            $res = array(
                'tittle' => 'Actualización',
                'message' => 'La actualización se realizó con exito',
                'state' => 'success',
            );
        }
        else{
            $res = array(
                'tittle' => 'Error',
                'code' =>  http_response_code(),
                'message' => "No se pudo realizar la actualización.",
                'description' => "No se pudo realizar la actualización.",
                'state' => 'error'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK );
    }
    
    /**
     * Return the departaments.
     *
     * @return Response
    */
    public function departaments_get(){
        $data = $this->Comprador_model->get_departamentos();
        $this->response ($data, REST_Controller :: HTTP_OK);
    }

    /**
     * Return the cities of a departament.
     *
     * @return Response
    */
    public function cities_get($city){
        $data = $this->Comprador_model->getCiudades($city);
        $this->response ($data, REST_Controller :: HTTP_OK);
    }

    /**
     * Register a user.
     *
     * @return Response
    */
    function index_post(){
        $res = array(
            'tittle' => 'Error',
            'code' =>  http_response_code(),
            'message' => 'Ocurrió un error mientras se realizaba el registro, intente nuevamente.',
            'description' => 'error',
            'state' => 'error'
        );

        $val = $this->exists_dni($this->post('dni'));
        $val2 = $this->exists_email($this->post('email'));
        if(count($val)==0 && $val2){
            try {
                $afec = explode("-",$this->post('dateNac'));
                $fecha = $afec[0]."/".$afec[1]."/".$afec[2];
                //url contra la que atacamos
                $ch = curl_init(URL_WS_INT."usuario/crearComprador?cedula=".$this->post('dni')."&nombre1=".$this->post('firstName')."&nombre2=".$this->post('lastName')."&apellido1=".$this->post('surname')."&apellido2=".$this->post('surname2')."&direccion=&ciudad=&genero=m&fechaNacimiento=".$fecha."&departamento=&correo=".$this->post('email')."&celular=&codigoBanco=&numeroCuenta=&clave=".$this->post('pass')."&tipoCuenta=&telefono=");
                //a true, obtendremos una respuesta de la url, en otro caso, 
                //true si es correcto, false si no lo es
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //establecemos el verbo http que queremos utilizar para la petición
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                //obtenemos la respuesta
                $response = curl_exec($ch);
                // Se cierra el recurso CURL y se liberan los recursos del sistema
                curl_close($ch);                        
                $request = json_decode($response);
                if($request->return){
                    $this->validateReferral($this->post('email'));
                    $this->SaldosCompradores_model->initializeBalance($this->post('dni'));
                    $budgetId = $this->SaldosCompradores_model->getBudgetId();
                    $val = $this->Compromisos_model->validateBalance($budgetId);
                    if($val)
                        $this->sendMailbudget($budgetId);
                    $code = base64_encode($this->post('email')."-".$this->post('pass')); 
                    if($this->sendActivation($this->post('email'), $code)){
                        $this->Comprador_model->saveCodAct($this->post('email'), $code);
                        $res = array(
                            'tittle' => 'Bienvenido',
                            'code' =>  http_response_code(),
                            'message' => 'Se envió un correo de activación de su cuenta a su email, revise su correo y active su cuenta, si no encuentra su correo, por favor valide en su buzón de spam de correo o dirijase a la opción de cambio de contraseña. Se ha asignado un saldo inicial por su registro.',
                            'description' => 'success',
                            'state' => 'success'
                        );
                    }
                    else{
                        $res = array(
                            'tittle' => 'Error',
                            'code' =>  http_response_code(),
                            'message' => 'Ocurrió un error en el envío del correo electrónico, cambie su contraseña para activar la cuenta.',
                            'description' => 'Error en la función de envío de correo',
                            'state' => 'error'
                        );
                    }
                }
            } catch ( SoapFault $e ) {
                
            }
        }
        else{
            if(count($val)!=0){
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'message' => 'Ya se encuentra un usuario registrado con esta cédula, el correo asociado a la cédula ingresada es: '.$val['pista'],
                    'description' => "Violación de llave primaria, cédula ya existe",
                    'state' => 'error'
                );
            }
            else{
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'message' => 'Ya se encuentra un usuario registrado con este correo',
                    'description' => "Violación de llave primaria, cédula ya existe",
                    'state' => 'error'
                );
            }
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
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
    
    public function validateReferral($email){
        $data = $this->Comprador_model->validarReferido($email); 
        if($data){
            $points =  $this->Puntos_model->getPointReferens();
            $budget = $this->Puntos_model->getBudgetId();
            $body = array(
                "USERID" => $data->USERID,
                "PUNTOS" =>$points->PUNTOS,
                "ID_COMPROMISO" =>$budget
            );
            $this->Puntos_model->insert($body);
            $this->Comprador_model->updatePadre($data->USERID, $email);
            $this->Comprador_model->deleteReferido($email);
        }
    }

    public function exists_dni($dni){
        $data = $this->Comprador_model->findCed($dni);
        $res = array();
        if($data){
            $dat = explode('@', $data->EMAIL);
            $p1 = substr($dat[0], 0, 3);
            $pista = $p1.'*****'.$dat[1];
            $res = array('pista' => $pista);
        }
        return $res;
    }

    public function exists_email($email){
        $data = $this->Comprador_model->findEmail($email);
        if($data){
            return false;
        }
        return true;
    }

    public function sendActivation($email, $code){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $email;
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
                                Gracias por suscribirse en LottiRed.Net.<br><br>
                                Para completar el proceso de activación de cuenta,
                                por favor vaya a la siguiente dirección en su navegador:<br><br>
                                '.ANGULAR_URL.'home?code='.$code.'<br><br>
                                Para su tranquilidad, le recordamos que usted ha ingresado 
                                sus datos personales en un portal de juegos de suerte y azar 
                                confiable y seguro de la Beneficiencia de Antioquia. Si presenta 
                                alguna duda o inquietud relacionada con el sitio LottiRed.Net, 
                                acerca de nuestros servicios o nuestra tecnología, escríbanos a 
                                servicio@lottired.com o llámenos a la línea nacional gratuita 01 8000 41 56 84.<br><br>
                                Con la lotería de su preferencia, puede comprar el número 
                                favorito que usted desea, con la posibilidad de hacerse 
                                millonario y hacer realidad sus sueños.<br><br>
                                Si quiere ganar, confirme su suscripción y juegue ahora mismo, no espere más.<br><br>
                                Cordialmente.
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
     * active the count of a user.
     *
     * @return Response
    */
    public function active_count_get(){ 
        $cod = $this->Comprador_model->verificarCodigoActivacion($this->get('code'));
        $res = array(
            'tittle' => 'Error',
            'code' =>  http_response_code(),
            'text' => 'El código ingresado no es valido.',
            'description' => 'El código ingresado no es valido.',
            'type' => 'error'
        );

        if($cod){	
            try {
                $data = array(
                    'ESTADO' => 1
                );		
                $activar = $this->Comprador_model->activarCuenta($this->get('code'),$data);
                if($activar){	
                    $res = array(
                        'tittle' => 'Activación',
                        'code' =>  http_response_code(),
                        'text' => 'Su cuenta ha sido activada',
                        'description' => "Activación de cuenta",
                        'type' => 'success'
                    );	
                } 
            } catch (Exception $e) {
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'text' => 'La cuenta no pudo ser activada, intente nuevamente',
                    'description' => $e,
                    'type' => 'error'
                );
            }
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * active the count of a user.
     *
     * @return Response
    */
    public function change_code_request_post(){
        $data = $this->Comprador_model->find($this->post('email'));
        if($data){
            if($this->send_code($this->post('email'), $data->CLAVE))
                $res = array(
                    'tittle' => 'Código enviado',
                    'code' =>  http_response_code(),
                    'message' => 'Se ha enviado un correo electrónico con un código de confirmación para el cambio, revise su email.',
                    'description' => 'success',
                    'state' => 'success'
                );
            else{
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'message' => 'No se pudo enviar el correo electrónico, por favor intente mas tarde.',
                    'description' => 'Error al enviar el correo electrónico revise la función de envío.',
                    'state' => 'error'
                );
            }
        }
        else{
            $res = array(
                'tittle' => 'Error',
                'code' =>  http_response_code(),
                'message' => 'No hay ninguna cuenta asociada, a el correo electrónico ingresado.',
                'description' => 'No se encontró un registro con el correo electrónico suministrado asociado.',
                'state' => 'error'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK );
    }

    public function send_code($email, $code){
        $code2 = base64_encode($email."-".$code); 
        
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $email;
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Recuperación de Clave';
        $bodyHtml = '<div style="border-style: groove;">
                        <div style="background-color: #f57c00; color: white">
                        <h2>APRECIADO CLIENTE,</h2>
                            <div style="text-align:right;">
                                <img src="'.ANGULAR_URL.'assets/images/home/logo_lottired.png">
                            </div>
                        </div>
                        <div style="text-align: justify;">
                            <br><br>
                            <p style="margin: 5%;">De acuerdo con la solicitud realizada, anexamos en este correo el enlace 
                            que deberá copiar y pegar en su navegador, para poder proceder a digitar la nueva clave 
                            de acceso al portal de juegos de suerte y azar de la Lotería de Medellín.<br><br>
                            Enlace:<br>
                            '.ANGULAR_URL.'home?codeChangePassword='.$code2.'<br><br>
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
            $mail->IsHTML(true);
            $mail->CharSet    = 'utf-8';
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
     * validate the code for change password
     *
     * @return Response
    */
    public function validate_code_post(){
        $datos = explode('-', base64_decode($this->post('code')));
        try{
            if(count($datos)==2){
                $data = $this->Comprador_model->findAll($datos[0], $datos[1], true);

                if($data){
                    $res = array(
                        'state' => 'success'
                    );
                }
                else{
                    $res = array(
                        'tittle' => 'Error',
                        'code' =>  http_response_code(),
                        'message' => 'Error en el código ingresado verifique el código.',
                        'description' => 'fallo la decodificación de base 64 del código.',
                        'state' => 'error'
                    );
                }
            }
            else{
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'message' => 'Error en el código ingresado verifique el código.',
                    'description' => 'fallo la decodificación de base 64 del código.',
                    'state' => 'error'
                );
            }
        }catch(Exception $e){
            $res = array(
                'tittle' => 'Error',
                'code' =>  http_response_code(),
                'message' => 'Error en el código ingresado verifique el código.',
                'description' => $e,
                'state' => 'error'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK );
    }

     /**
     * Update buyer information.
     *
     * @return Response
    */
    public function change_password_put(){
        $datos = explode('-', base64_decode($this->put('code')));
        $res = array();
        try{
            if(count($datos)==2){
                $data = $this->Comprador_model->changePassword($datos[0], $this->put('password')); 
                if($data){
                    $res = array(
                        'tittle' => 'Actualización',
                        'message' => 'El password se cambió exitosamente',
                        'state' => 'success',
                    );
                }
            }
            else{
                $res = array(
                    'tittle' => 'Error',
                    'code' =>  http_response_code(),
                    'message' => "No se pudo cambiar el password, verifique el código de cambio de contraseña o solicite uno nuevo.",
                    'description' => "No se pudo realizar la actualización.",
                    'state' => 'error'
                );
            }
        }
        catch (Exception $e) {
            $res = array(
                'tittle' => 'Error',
                'code' =>  http_response_code(),
                'message' => "No se pudo cambiar el password, intente nuevamente mas tarde.",
                'description' => $e,
                'state' => 'error'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK );
    }

    /**
     * get the referals of a session user.
     *
     * @return Response
    */
    public function my_referals_get($userId){
       $data = $this->Comprador_model->get_misReferidos($userId);
       $this->response ($data, REST_Controller :: HTTP_OK);
    }

    /**
     * get the referals of a session user, that they are register in the portal.
     *
     * @return Response
    */
    public function my_referals_registers_get($userId){
        $data = $this->Comprador_model->get_mrAprobados($userId);
        $this->response ($data, REST_Controller :: HTTP_OK);
    }

    /**
     * sending a request to a person to register for the lottired portal.
     *
     * @return Response
    */
    public function invite_post(){
        $correo = $this->post('email');
        $userId = $this->post('userId');
        $veri = $this->Comprador_model->verificarReferido($correo);
        $res = array(
            'tittle' => 'Error',
            'code' =>  http_response_code(),
            'text' => "Ya se envío una solicitud de registro a este correo o ya se encuentra registrado en el portal.",
            'description' => "Se encontró este correo ya registrado en la base de datos.",
            'type' => 'error'
        );
        if($veri){
            $this->enviarInvitacion($correo, $this->post('name'));
            $veri = $this->Comprador_model->agregarReferido($correo, $userId);
            $res = array(
                'tittle' => 'Invitación enviada',
                'code' =>  http_response_code(),
                'text' => 'Se envío una invitación al correo '.$correo,
                'description' => 'Success',
                'type' => 'success'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    public function enviarInvitacion($correo, $name){
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $correo;
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
        $subject = 'Bienvenido a LottiRed.Net';
        $bodyHtml = '<div style="border-style: groove;">
                        <div style="background-color: #f57c00; color: white">
                        <h2>INVITACIÓN A LOTTIRED,</h2>
                            <div style="text-align:right;">
                                <img src="'.ANGULAR_URL.'admin-lottired/dist/portal/images/home/logo.png">
                            </div>
                        </div>
                        <div style="text-align: justify;">
                            <br><br>
                            <p style="margin: 5%;">
                                Hola, Acabas de ser invitado(a) por '.$name.' a registrarte en el portal de venta de juegos de suerte y azar de la Lotería de Medellín, 
                                solo debes hacer clic en la siguiente dirección '.ANGULAR_URL.'home?invite=1
                                y empezar a ganar premios por tus compras. 
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
     * add initial balance to user
     *
     * @return Response
    */
    public function add_initial_balance_post()
    {
        $this->load->model('Comprador_model');
        $valor = $this->input->post('valor');
        $compromiso = $this->input->post('compromiso');
        $data = $this->Comprador_model->registrarSaldoInicial($valor, $compromiso);
        $this->response ( ['Item created successfully.'], REST_Controller :: HTTP_OK );
    }
}
