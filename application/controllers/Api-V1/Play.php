<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Play extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Loterias_model'); 
    }
      
	public function index_get()
	{               
        $id = $this->input->get('id');
        if($id){  
            $data['logo'] = $this->Loterias_model->find($id);          
            $data['datos'] = $this->Loterias_model->findAll($id);
        } else {
            $loteries = $this->Loterias_model->find();
            foreach($loteries as $lotery) {
                $lot =  $this->Loterias_model->findAll($lotery->loteria);                
                if($lot){
                    $now = strtotime("now");
                    $fcierre = strtotime($lot->CIERREJUEGO);
                    $dif = $now - $fcierre;
                    if($dif<=0){
                        $valid = false;
                    } else {
                        $valid = true;
                    }            
                    $obj = array(
                        'loterryLogo' => $lotery->logo,
                        'lotteryName' => $lot->NOMBRE,
                        'jackPot' => $lot->VALORPREMIOMAYOR,
                        'date' => $lot->FECHASORTEO,
                        'cierre' => $valid,
                        'id' => $lotery->loteria				
                    );
                    $data[] = $obj;
                }
            }
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
        $this->db->insert('', $input); 
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Update Data x Id from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
        $this->db->update('', $input, array('id'=>$id));
     
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Delete Data x Id from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        $this->db->delete('', array('id'=>$id));
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }

    public function luck_get(){
        $requestId = $this->get('requestId');
        $loteria = $this->get('lottery');
        $fracciones = $this->get('fractions');
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

        // Consumo para obtener dame suerte
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."soporte/generarYBloquearNumeroAleatorio?requestId=".$requestId."&loteria=".$loteria."&numero=&serie=&cantidadFracciones=".$fracciones."&estacion=portal");
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
        $this->response(json_decode($res), REST_Controller::HTTP_OK);
    }

    public function fractions_available_get(){
        $requestId = $this->get('requestId');
        $loteria = $this->get('lottery');
        $serie = $this->get('serie');
        $numero = $this->get('number');
        // Consumo para obtener token
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."ususario/login?usuario=wservices&clave=test");
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

        // Consumo para obtener dame suerte
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."soporte/consultaFracciones?requestId=".$requestId.
        "&producto=".$loteria."&serie=".$serie."&numero=".$numero);
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
        $this->response(json_decode($res), REST_Controller::HTTP_OK);
    }

    public function reserve_get(){
        $requestId = $this->get('requestId');
        $loteria = $this->get('lottery');
        $serie = $this->get('serie');
        $numero = $this->get('number');
        $fracciones = $this->get('fractions');
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

        // Consumo para obtener dame suerte
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."venta/reservarFraccionesBillete?requestId=".$requestId.
        "&producto=".$loteria."&serie=".$serie."&numero=".$numero."&cantidadFracciones=".$fracciones."&estacion=portal");
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
        $this->response(json_decode($res), REST_Controller::HTTP_OK);
    }
    
}