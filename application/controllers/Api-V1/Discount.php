<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Discount extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model(array('Descuentos_model','Compromisos_model'));
    }
    /**
     * update the catn and balance compromise.
     *
     * @return Response
    */
    public function substractCantCodDiscount_put($cod){        
        $newCantAvailable = $this->Descuentos_model->getCant($cod)->CANTCUPONESDISP - $this->put('cant');         
        $res = $this->Descuentos_model->setCant($newCantAvailable, $cod);
        $compromiso = $this->Descuentos_model->getCompromiso($cod);
        $newBalanceComp = $this->Compromisos_model->find($compromiso->COMPROMISO)->SALDO_COMPROMISO - $compromiso->VRCUPON;
        $r = $this->Compromisos_model->update($compromiso->COMPROMISO,$newBalanceComp); 
        if($res && $r) {
            $this->response (['message:'=>'Cupos disponibles y saldo compromiso actualizados'], REST_Controller :: HTTP_OK);        
        } else {
            $this->response (['error:'=>'Error en bbdd no se pudo actualizar'], REST_Controller :: HTTP_INTERNAL_SERVER_ERROR);        
        }        
    }
}
