<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/REST_Controller.php';

class PLaceToPay extends REST_Controller {
    // se accede con http://miservidor/v1/cart?format=json
   /**
     * Get All Data from this method an Get Data x Id.
     *
     * @return Response
    */
	public function index_get($reference)
	{        
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        date_default_timezone_set('America/Bogota');
        $login = LOGIN;
        $seed = date(DATE_ISO8601);
        $secretKey = SECRET_KEY;
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }
        $nonceBase64 = base64_encode($nonce);
        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));
        $data  = array();  
        // Carga de modelos a usar
        $this->load->model('Transacciones_model');
        
        $requestId = $this->Transacciones_model->obtenerRequestId($reference)->IDPASARELA;
        $idventasloteriasportal = $this->Transacciones_model->obtenerRequestId($reference)->ID;
        $valor_saldo = $this->Transacciones_model->obtenerRequestId($reference)->VALOR_SALDO;
        // Array para pasar a consumo placetopay
        $dataArray = array(
            'auth' => array(
                'login' => $login,
                'seed' => $seed,
                'nonce' => $nonceBase64,
                'tranKey' => $tranKey
            )
        );
        // Consumo a placetopay
        //url contra la que atacamos
        $ch = curl_init(PTP_URL."redirection/api/session/".$requestId);
        //$ch = curl_init(PTP_URL."redirection/api/session/".$requestId);
        
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //enviamos el array data
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($dataArray));
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);				
        
        $request = json_decode($response);        
        $data['name'] = $request->request->payer->name;
        $data['mail'] = $request->request->payer->email;
        $data['ipaddress'] = $request->request->ipAddress;
        $data['reference'] = $reference;
        $data['description'] = $request->request->payment->description;
        $data['date'] = $request->status->date;
        $data['status'] = $request->status->status;
        $data['reason'] = $request->status->reason;
        $data['amount'] = $request->request->payment->amount->total;
        $data['iva'] = 0;
        $data['franchise'] = $request->payment[0]->franchise;
        $data['authorization'] = $request->payment[0]->authorization;
        $data['receipt'] = $request->payment[0]->receipt;
        $data['paysaldo'] = $valor_saldo;
        $data['details'] = $this->Transacciones_model->get_detail($idventasloteriasportal);        
        $data['msgPlaceToPay'] = $request->status->message;

        $args = array(
            'NOMBRECOMPRADOR' => $request->request->payer->name,
            'FRANQUICIA' => $request->payment[0]->franchise,
            'NUMEROAUTORIZACIONTX' => $request->payment[0]->authorization,
            'NUMERORECIBO' => $request->payment[0]->receipt,
            'FECHATRANSACCION' => $request->status->date,
            'NOMBREBANCO' => $request->payment[0]->issuerName,
            'RESPUESTATRANSACCION' => $request->payment[0]->status->message,
            'ESTADOTRANSACCION' => $request->status->status,
            'NUMEROFACTURA' => $request->payment[0]->internalReference,
            'FECHAPAGO' => $request->payment[0]->status->date,
        );
        $this->Transacciones_model->update($reference,$args);		                         
        $this->response($data, REST_Controller::HTTP_OK);
	}
      
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {        
        $pay = $this->post('payer');
        $cart = $this->post('cart');
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        date_default_timezone_set('America/Bogota');
        $login = LOGIN;
        $seed = date(DATE_ISO8601);
        $secretKey = SECRET_KEY;
        if (function_exists('random_bytes')) {
            $nonce = bin2hex(random_bytes(16));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $nonce = bin2hex(openssl_random_pseudo_bytes(16));
        } else {
            $nonce = mt_rand();
        }
        $nonceBase64 = base64_encode($nonce);
        $reference = round(microtime(true) * 1000);
        $expiration_date = date(DATE_ISO8601, $timestamp = time() + 60*60);
        $tranKey = base64_encode(sha1($nonce . $seed . $secretKey, true));
        $totalbank = $pay['values']['total'] - $pay['balance'] - $pay['values']['dcto'];
            // Array para pasar a consumo placetopay
            $dataArray = array(
                'auth' => array(
                    'login' => $login,
                    'seed' => $seed,
                    'nonce' => $nonceBase64,
                    'tranKey' => $tranKey
                ),
                'payment'=> array(
                    'reference' => $reference,
                    'description' => 'Pago básico de prueba Lottired',
                    'amount' => array (
                        'currency' => 'COP',
                        'total' => $totalbank
                    )
                ),
                'expiration' => $expiration_date,
                'returnUrl' => ANGULAR_URL.'cart?reference='. $reference,
                'ipAddress' => $pay['values']['ip'],
                'userAgent' => 'PlacetoPay Sandbox'
            );
       
        // Consumo a placetopay
        //url contra la que atacamos
        $ch = curl_init(PTP_URL."redirection/api/session");
        //$ch = curl_init(PTP_URL."redirection/api/session");
        
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //establecemos el verbo http que queremos utilizar para la petición
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //enviamos el array data
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($dataArray));
        //obtenemos la respuesta
        $response = curl_exec($ch);
        // Se cierra el recurso CURL y se liberan los recursos del sistema
        curl_close($ch);
        $rqId = json_decode($response);
        // Persistencia de transaccion 
        $this->load->model('Transacciones_model');
		$this->load->model('Comprador_model');
        $idComprador = $this->Comprador_model->findXid($pay['buyer']['userId']);
        $totalpay = $totalbank + $pay['balance'] + $pay['values']['dcto'];
		$data = array(
			'ID' => $this->Transacciones_model->obtenerUltimoId()->ID+1,
			'REFERENCIA'=>$reference,
			'MONEDA'=>'COP',
			'VALORTOTAL'=>$totalpay,
			'VALORIMPUESTO'=>0,
			'MONEDABANCARIA'=>'COP',
			'FACTORCONVERSION'=>1,
			'NOMBRECOMPRADOR'=> $idComprador->NOMBRES.' '.$idComprador->APELLIDOS,
			'EMAILCOMPRADOR'=> $idComprador->EMAIL,
			'CIUDADCOMPRADOR'=>13001,
			'VALOR_BANCO'=>$totalbank,
			'VALOR_SALDO'=>$pay['balance'],
			'DESCUENTO_CLIENTE'=>$pay['values']['dcto'],
			'PORCENTAJE_DESCUENTO'=>0,
			'IDCOMPRADOR'=> $idComprador->CEDULA,
			'IDPASARELA'=> $rqId->requestId
		);
        $this->Transacciones_model->insert($data);         
        //Grabar detalles bbdd
        $cont = count($cart);
        for($i=0;$i<$cont;$i++){ 
            $item = null; 
            $contfractions = $cart[$i]['fractions'];
            for($x=1;$x<=$contfractions;$x++){    
                    if($contfractions==1){
                        $item = 1;
                    }                   
                    $data = array(
                        'ID' => $this->Transacciones_model->obtenerUltimoIdDetail()->ID+1,
                        'SERIE'=>$cart[$i]['serie'],
                        'NUMERO'=>$cart[$i]['number'],
                        'LOTERIA'=>$cart[$i]['lotteryId'],
                        'SORTEO'=>$cart[$i]['draw'],
                        'FRACCION'=>$x,
                        'IDVENTASLOTERIASPORTAL'=>$this->Transacciones_model->obtenerUltimoId()->ID,
                        'VENTABRUTA'=>$cart[$i]['price']*$cart[$i]['fractions'],
                        'VALORFRACCION'=>$cart[$i]['price'],
                        'CODIGO_BARRAS' => '',
                        'FECHA_JUEGA'=>$cart[$i]['date'],                
                        'NOMBRE_LOTERIA'=>$cart[$i]['name'],
                        'CANTIDAD_SORTEOS'=>$cart[$i]['qty'],
                        'ITEM'=> $item                                                          
                    );            
                $this->Transacciones_model->insert_detail($data);
            }
        }        
                
        $this->response(json_decode($response), REST_Controller::HTTP_OK);
              
    }
     
    /**
     * Update Data x Id from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
       
    }
     
    /**
     * Delete Data x Id from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        
    }    
}