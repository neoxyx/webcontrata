<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Reserve extends REST_Controller {
    
    // se accede con http://miservidor/v1/cart?format=json
   /**
     * Get All Data from this method an Get Data x Id.
     *
     * @return Response
    */
	public function index_get($idItem)
	{       
        $requestId = rand();
        $idReserva = $this->db->get_where('cart_temp',array('idcart_temp'=>$idItem))->row()->idReserva;
        // Consumo para obtener token
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."usuario/login?usuario=wservices&clave=test");
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);				        
        $request = json_decode($response);
        $token = $request->return;

        // Consumo para liberar reservas
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."venta/liberarReservaFracciones?requestId=".$requestId.
        "&idReserva=".$idReserva);
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Set your auth headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
            ));
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //obtenemos la respuesta
        $res = curl_exec($ch);                
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);                  
        $this->response($res, REST_Controller::HTTP_OK);
    }
      
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {        
        $rand = rand();
        $input = $this->post();
        // Consumo para obtener token
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."venta/login?usuario=wservices&clave=test");
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);				        
        $request = json_decode($response);
        $token = $request->return;

        $cont = count($input);
        for($i=0;$i<$cont;$i++){
        $requestId = $rand+$i;
        // Consumo para liberar reservas
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."venta/liberarReservaFracciones?requestId=".$requestId.
        "&idReserva=".$input[$i]['idReserva']);
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //Set your auth headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
            ));
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //obtenemos la respuesta
        $res = curl_exec($ch);                
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);        
        }        
        $this->response($res, REST_Controller::HTTP_OK);
    }                 
     
}