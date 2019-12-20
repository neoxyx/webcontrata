<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/REST_Controller.php';

class SaleWeb extends REST_Controller {
    /**
     * Obtiene detalles de la venta aceptada
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
        $idventasloteriasportal =  $this->Transacciones_model->obtenerRequestId($reference)->ID;
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
        $this->response($data, REST_Controller::HTTP_OK);
    }
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {	
		$details = array();
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
		//Fin obtener token
        $pay = $this->post('payer');
        $cart = $this->post('cart'); 
        $franchise = $this->post('franchise');       
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }        
        // Persistencia de transaccion 
        $this->load->model('Transacciones_model');
		$this->load->model('Comprador_model');
		$id = $this->Transacciones_model->obtenerUltimoId()->ID;
        $idComprador = $this->Comprador_model->findXid($pay['buyer']['userId']);
        $cedula = $this->Comprador_model->getCedula($idComprador->CEDULA)->CEDULA1;
        $totalbank = $pay['values']['total'] - $pay['balance'] - $pay['values']['dcto'];
        $totalpay = $totalbank + $pay['balance'] + $pay['values']['dcto'];        
        $originalDate = date("Y-m-d H:i:s");
        //detalles numeros vendidos
        $cont = count($cart);
        for($i=0;$i<$cont;$i++){    
            $fractions = array();          
            $endDate = date(/*$cart[$i]['date'],*/"Y-m-d H:i:s");
            if($cart[$i]['check'] == 0){
                $incentive = false;
            } else {
                $incentive = true;
            }
            $contfractions = intval($cart[$i]['fractions']);
            for($x=1;$x<=$contfractions;$x++){
                $fractions[] = $x;
            }
            $obj = array(
                'permiteSaldo' => 'S',                
                'nombreLoteria'=>$cart[$i]['name'],
                'comprador' => 'wservices',
                'fechaInicioJuego' => $originalDate,
                'idReservaAbonado' => null,
                'valorFraccion' => intval($cart[$i]['price']),
                'fracciones' => $fractions,
                'numero' => $cart[$i]['number'],
                'codigoLoteria' => $cart[$i]['lotteryId'],
                'fechaFinJuego' => $endDate,//$cart[$i]['date'],
                'idReserva' => $cart[$i]['idReserva'],
                'valorTotal' => $cart[$i]['price']*$cart[$i]['fractions']*$cart[$i]['qty'],
                'cantidadFracciones' => intval($cart[$i]['fractions']),
                'serie' => $cart[$i]['serie'],
                'cantidadSorteos' => intval($cart[$i]['qty']),
                'conIncentivo' => $incentive,
                'sorteo' => $cart[$i]['draw'],                
                'sorteoFinal' => $cart[$i]['draw']                                                                                            
			);
			$details[] = $obj;
        }
				           
		//Objeto VentaWeb	
		$payload = json_encode(
						array(
                            'valorTotal'=> $totalpay,
                            'numerosVendidos' => $details,
                            'valorVentaBrutaBanco' => $totalbank,
                            'fechaPago' => date(DATE_ATOM),
                            'valorVentaBrutaSaldo' => $pay['balance'],														
							'valorImpuesto' => 0,
							'idVentasLoteriasPortal' => $id,
							'franquicia' => $franchise,
							'descuento' => $pay['values']['dcto'],
							'cedulaCliente' => intval($cedula),
							'fechaTransaccion' => date(DATE_ATOM)
						)
                    );	
        log_message('info', 'Enviando Venta WEB: '.$payload);
		//consumo ventaLoteriaPortal
        //url contra la que atacamos
        $ch = curl_init(URL_WS_INT."venta/ventaLoteriaVirtual");
        //a true, obtendremos una respuesta de la url, en otro caso, 
        //true si es correcto, false si no lo es
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//attach encoded JSON string to the POST fields
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
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