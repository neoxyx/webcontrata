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

class Security extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Comprador_model');
        $this->load->helper(['jwt', 'authorization']);
    }

    /**
     * Login.
     *
     * @return Response
    */
    public function login_post()
    {
        $email = $this->post('email');
        $pass = $this->post('password'); 
        $data = $this->Comprador_model->findAll($email,$pass);
       
        $val = $this->Comprador_model->findCount($email);
        if($val){
            $res = array ();
            if($data){
                $valActive = ($data->ESTADO==1) ? true:false;
                if($valActive){
                    // Mes y dia actual
                    $nowDay = date('d');
                    $nowMonth = date('m');
                    //Variable para indicar beneficio por cumpleaños
                    $benefit = false;
                    // Create a token
                    $time = time();
                    $key = 'my_secret_key';

                    $token = array(
                        'iat' => $time, // Tiempo que inició el token
                        'exp' => $time + (0*1), // Tiempo que expirará el token (+1 hora)
                        'data' => [ // información del usuario
                            'id' => $email,
                            'pass' => $pass
                        ]
                    );

                    $jwt = JWT::encode($token, $key);

                    $mac = getHostByName(getHostName())." ".$_SERVER['HTTP_USER_AGENT']; 
                    $this->Comprador_model->registrarDispositivo($data->EMAIL, $mac, $data->ID);
                    $name2=($data->NOMBRE2) ? $data->NOMBRE2.' ':'';
                    $lastName2=($data->APELLIDO2) ? " ".$data->APELLIDO2:'';
                    $birthDay = $data->FECHANACIMIENTO;
                    $dateInt = strtotime($birthDay);
                    $month = date("m", $dateInt);
                    $day = date("d", $dateInt);
                    if($day == $nowDay && $month == $nowMonth){
                        $benefit = true;
                    }
                    $res = array (
                        'userId' => (int) $data->ID,
                        'firstName' => $data->NOMBRE1,
                        'lastName' => $data->APELLIDO1,
                        'fullName' => $data->NOMBRE1." ".$name2.$data->APELLIDO1.$lastName2,
                        'email' => $data->EMAIL,
                        'token' => $jwt,
                        'benefit' => $benefit
                    );
                }
                else{
                    $res = array (
                        'code' =>  http_response_code(),
                        'message' => "La cuenta no esta activa, por favor active su cuenta.",
                        'description' => "El estado de la cuenta es 0"
                    );
                }
            }
            else{
                $res = array (
                    'code' =>  http_response_code(),
                    'message' => "Usuario o contraseña erronea.",
                    'description' => "No se encontró email y password relacionados en la BD"
                );
            }          
        }     
        else{
            $res = array (
                'code' =>  http_response_code(),
                'message' => "No hay ninguna cuenta, asociada a ese correo electrónico.",
                'description' => "No se encontró un registro con ese email"
            );
        }     
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }
    
    /**
     * Register the device from where you started session.
     *
    */
    public function register_device($correo, $mac){
        $this->Comprador_model->registrarDispositivo($correo, $mac);
    }

    /**
     * Validate the code sent for the password change.
     *
     * @return Response
    */
    public function change_password_code_post(){
        $code = $this->post('validationCode');
        $datos = explode('-', base64_decode($code));
        $data = array();
        if(count($datos)==2){
            $data = $this->Comprador_model->findAll($datos[0], $datos[1], true);
        }
        $this->response ($data, REST_Controller :: HTTP_OK );
    }

    /**
     * Update the password with the new value.
     *
     * @return Response
    */
    public function password_change_post(){
        $email = $this->input->post('email');
        $nPass = $this->input->post('password');
        
        $res= $this->Comprador_model->update($email, $nPass);
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * Validate that the login device is secure.
     *
     * @return Response
    */
    public function validate_divice_get(){
        $res = $this->Comprador_model->validarPC($this->get('email'));
        $this->response ($res, REST_Controller :: HTTP_OK);
    }
}
