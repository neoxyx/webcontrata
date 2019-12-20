<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/REST_Controller.php';

class Transaction extends REST_Controller {
    /**
     * Obtiene estado de la transaccion
     */
    public function index_get($reference)
    {        
        // Carga de modelo a usar
        $this->load->model('Transacciones_model');
        
        $status = $this->Transacciones_model->obtenerRequestId($reference)->ESTADOTRANSACCION;
        if($status == 'APPROVED'){
            $data = true;
        } else {
            $data = false;
        }
        $this->response(["status"=>$data], REST_Controller::HTTP_OK);
    }
}