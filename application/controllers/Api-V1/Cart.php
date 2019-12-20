<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Cart extends REST_Controller {
    
    // se accede con http://miservidor/v1/cart?format=json
   /**
     * Get All Data from this method an Get Data x Id.
     *
     * @return Response
    */
	public function index_get($ip)
	{       
        if(!empty($ip)){

            $data = $this->db->get_where("cart_temp", array('ip'=> $ip))->result();

        } 
     
        $this->response($data, REST_Controller::HTTP_OK);
    }
      
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {        
        $input = $this->post();                              
        $this->db->insert('cart_temp',$input);            
        $this->response($input, REST_Controller::HTTP_OK);
    } 
    /**
     * Validate hour end draw
     *
     * @return Response
    */
    public function validateCod_get()
    {        
        $input = $this->get();
        $this->load->model('Descuentos_model');
        $res = $this->Descuentos_model->getValueCodDiscount($input['cod']);
        if(!$res){
            $this->response(['error'=>'El código ingresado no tiene promocional ó se agotó la promoción.'], REST_Controller::HTTP_OK);  
        } else {
            $this->response(number_format($res->VRCUPON,0,'.',''), REST_Controller::HTTP_OK);  
        }
               
    }   

    /**
     * Validate hour end draw
     *
     * @return Response
    */
    public function validateDraw_post()
    {   
        $message = [];  
        $input = $this->post();
        $cont = count($input);
        $this->load->model('Sorteos_model');
        for($i=0;$i<$cont;$i++){
            $closeDraw = $this->Sorteos_model->getClose($input[$i]['lotteryId'],$input[$i]['draw']);
            $hournow = date('d-m-Y H:i:s');            
            if($hournow>=$closeDraw){
                $message = ['error'=>'El sorteo: '.$input[$i]['draw'].' de la loteria '.$input[$i]['name'].' ya cerró, por favor elije otro sorteo.<br>'];                                
            } else {
                $message = ['valido'=>'Sorteos validos'];                                                               ;
            }
        }   
        $this->response($message, REST_Controller::HTTP_OK);         
    }                            
    
    /**
     * Update Data x Id from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
        $this->db->update('cart_temp', $input, array('idcart_temp'=>$id));     
        $this->response(['message'=>'Item actualizado exitosamente.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Delete Data x Id from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        $this->db->delete('cart_temp', array('idcart_temp'=>$id));
       
        $this->response(['Item eliminado satisfactoriamente.'], REST_Controller::HTTP_OK);
    }

    public function clear_delete($ip)
    {
        $this->db->delete('cart_temp', array('ip'=>$ip));
       
        $this->response(['Carrito vaciado satisfactoriamente.'], REST_Controller::HTTP_OK);
    }
}