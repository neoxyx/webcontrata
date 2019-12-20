<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/REST_Controller.php';

class Sale extends REST_Controller {
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {		
        $pay = $this->post('payer');
        $cart = $this->post('cart');  
        // Persistencia de transaccion 
        $this->load->model('Transacciones_model');
		$this->load->model('Comprador_model');
        $idComprador = $this->Comprador_model->findXid($pay['buyer']['userId']);
        $totalbank = $pay['values']['total'] - $pay['balance'] - $pay['values']['dcto'];
        $totalpay = $totalbank + $pay['balance'] + $pay['values']['dcto'];      
        //Grabar cabecera venta tbl
        $arr = array(
            'ID' => $this->Transacciones_model->obtenerUltimoId()->ID+1,
            'VALORTOTAL'=>$totalpay,
            'NOMBRECOMPRADOR'=> $idComprador->NOMBRES.' '.$idComprador->APELLIDOS,
            'EMAILCOMPRADOR'=> $idComprador->EMAIL,
            'VALOR_BANCO'=>$totalbank,
            'VALOR_SALDO'=>$pay['balance'],
            'DESCUENTO_CLIENTE'=>$pay['values']['dcto'],
            'IDCOMPRADOR'=> $idComprador->CEDULA,          
        );
        $this->Transacciones_model->insert_sale($arr);        
        //Grabar detalles bbdd
        $cont = count($cart);
        for($i=0;$i<$cont;$i++){ 
            $contfractions = intval($cart[$i]['fractions']);
            for($x=1;$x<=$contfractions;$x++){  
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
                'CANTIDAD_SORTEOS'=>$cart[$i]['qty']                                                               
            );
                $this->Transacciones_model->insert_detail($data);
            }
        }	
		$this->response(['message' => 'Venta grabada exitosamente'], REST_Controller::HTTP_OK);            
    }
}