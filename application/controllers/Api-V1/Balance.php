<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Balance extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('SaldosCompradores_model');
    }

    /**
     * returns the logged-in user balance.
     *
     * @return Response
    */
    public function index_get($id){  
        $this->response (array ('balance'=>$this->SaldosCompradores_model->getBalance($id)), REST_Controller :: HTTP_OK);
    }

    /**
     * returns the logged-in user historial moves.
     *
     * @return Response
    */
    public function my_moves_get($id){  
        $datas = $this->SaldosCompradores_model->getMoves($id);
        foreach($datas as $data){
            $fDate='';
            if(!is_null($data->FECHA_MOVIMIENTO)){
                $f = explode(' ', $data->FECHA_MOVIMIENTO);
                $fDate = date_format(date_create($f[0]), 'd/m/y');
            }
            $res[] = array(
                "ID" => $data->ID,
                "VALOR_MOVIMIENTO" => $data->VALOR_MOVIMIENTO,
                "FECHA_MOVIMIENTO" => $fDate,
                "SALDO" => $data->SALDO,
                "CEDULA" => $data->CEDULA
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * update the balance.
     *
     * @return Response
    */
    public function index_put($userId){
        $newBalance = $this->SaldosCompradores_model->getBalance($userId) + $this->put('value'); 
        $this->SaldosCompradores_model->setBalance($newBalance, $userId);
        if($this->put('value') != 0)
            $this->SaldosCompradores_model->addMove($this->put('value'), $userId, $newBalance);
        $this->response ($this->SaldosCompradores_model->getBalance($userId), REST_Controller :: HTTP_OK);        
    }
}
