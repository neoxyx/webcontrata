<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';

class Compradores extends REST_Controller {
    
    public function index_post()
    {
        $this->load->model('Comprador_model');
        $valor = $this->input->post('valor');
        $data = $this->Comprador_model->registrarSaldoInicial($valor);
        $this->response ( ['Item created successfully.'], REST_Controller :: HTTP_OK );
    }
    
}
