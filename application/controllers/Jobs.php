<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Jobs extends CI_Controller {

	public function validateTransactions()
	{					
        $this->load->model("Transacciones_model");
		$res = $this->Transacciones_model->getTransactionsUnApproved();
		if($res){
			foreach($res as $ref){				
				$requestId = $ref->IDPASARELA;				
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
				//
				$request = json_decode($response); 
				//
				if($request->status->status == 'APPROVED'){
					//
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
					$this->Transacciones_model->update($ref->REFERENCIA,$args);
					$details = array();
					// Consumo para obtener token
					//url contra la que atacamos
					$ch0 = curl_init(URL_WS_INT."usuario/login?usuario=wservices&clave=test");
					//a true, obtendremos una respuesta de la url, en otro caso, 
					//true si es correcto, false si no lo es
					curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);
					//establecemos el verbo http que queremos utilizar para la petición
					curl_setopt($ch0, CURLOPT_CUSTOMREQUEST, "POST");
					//obtenemos la respuesta
					$response = curl_exec($ch0);
					// Se cierra el recurso CURL y se liberan los recursos del sistema
					curl_close($ch0);				        
					$request = json_decode($response);
					$token = $request->return;
					//Fin obtener token
					$method = $_SERVER['REQUEST_METHOD'];
					if($method == "OPTIONS") {
						die();
					}     
					$datas = $this->Transacciones_model->get_detail($ref->ID);        					
					//detalles numeros vendidos
					foreach($datas as $data){              
						$date = date(/*$cart[$i]['date'],*/"Y-m-d H:i:s");
						$obj = array(
							'permiteSaldo' => 'S',                
							'nombreLoteria'=>$data->NOMBRE_LOTERIA,
							'comprador' => 'wservices',
							'fechaInicioJuego' => $date,
							'idReservaAbonado' => null,
							'valorFraccion' => $data->VALORFRACCION,
							'fracciones' => [$data->FRACCION],
							'numero' => $data->NUMERO,
							'codigoLoteria' => $data->LOTERIA,
							'fechaFinJuego' => $date,//$cart[$i]['date'],
							'idReserva' => '',
							'valorTotal' => $data->VENTABRUTA,
							'cantidadFracciones' => $data->FRACCION,
							'serie' => $data->SERIE,
							'cantidadSorteos' => $data->CANTIDAD_SORTEOS,
							'conIncentivo' => '',
							'sorteo' => $data->SORTEO,                
							'sorteoFinal' => $data->SORTEO                                                                                            
						);
						$details[] = $obj;
					}				
					//Objeto VentaWeb	
					$payload = json_encode(
						array(
							'valorTotal'=> $ref->VALORTOTAL,
							'numerosVendidos' => $details,
							'valorVentaBrutaBanco' => $ref->VALOR_BANCO,
							'fechaPago' => date(DATE_ATOM),
							'valorVentaBrutaSaldo' => $ref->VALOR_SALDO,														
							'valorImpuesto' => 0,
							'idVentasLoteriasPortal' => $ref->ID,
							'franquicia' => $ref->FRANQUICIA,
							'descuento' => $ref->DESCUENTO_CLIENTE,
							'cedulaCliente' => $ref->IDCOMPRADOR,
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
					$sale = json_decode($res);
					//Envio emails
					$ch2 = curl_init(base_url().'/api/v1/emailbalance');
					//a true, obtendremos una respuesta de la url, en otro caso, 
					//true si es correcto, false si no lo es
					curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
					//attach encoded JSON string to the POST fields
					curl_setopt($ch2, CURLOPT_POSTFIELDS, $sale);					
					//establecemos el verbo http que queremos utilizar para la petición
					curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");
					//obtenemos la respuesta
					$resMail = curl_exec($ch2);                
					// Se cierra el recurso CURL y se liberan los recursos del sistema
					curl_close($ch2);									
				}				
			}
		}
	}			
}