<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Shopping extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Comprador_model');
        $this->load->model('Loterias_model');
    }

    /**
     * Return the detail of a purchase.
     *
     * @return Response
    */
    public function purchase_detail_post(){
        $dataDetails = $this->Comprador_model->detalleCompra($this->post('id'));
        
        $res = array();
        $lotteries = array();
        
        foreach($dataDetails as $detail){
            $band=true;
            foreach($lotteries as $lottery){
                if($lottery==$detail->LOTERIA)
                    $band=false;
            }
            
            if($band){ 
                $details = array();
                $idLoteria = $detail->LOTERIA;
                $name = $detail->NOMBRE_LOTERIA;
                $dataLottery = $this->Loterias_model->find($idLoteria); 
                $allFractions = 0;
                $lotteries[] = $idLoteria; 
                foreach($dataDetails as $detail2){
                    if($idLoteria == $detail2->LOTERIA){
                        $allFractions = $detail2->FRACCION;
                        for($x=1; $x<=$detail2->FRACCION; $x++){
                            $details[] = array(
                                "SERIE" => $detail2->SERIE,
                                "NUMERO" => $detail2->NUMERO,
                                "FRACCION" => $x
                            );
                        }
                    }
                }
                $fDate='';
                if(!is_null($detail2->FECHATRANSACCION)){
                    $f = explode(' ', $detail2->FECHATRANSACCION);
                    $fDate = date_format(date_create($f[0]), 'd/m/y');
                }
                $res[] = array(
                    "LOTERIA" => $idLoteria,
                    "NOMBRE" => $name,
                    "LOGO" => $dataLottery->logo,
                    "FECHA_JUEGA" => $detail->FECHA_JUEGA,
                    "SORTEO" => $detail->SORTEO,
                    "FECHATRANSACCION" => $fDate,
                    "DETAILS" => $details,
                    "FRACCIONES" => $allFractions,
                    "VALORTOTAL" => $detail->VALORTOTAL
                );
            }
        }

        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * Return the shoppings od the user started session.
     *
     * @return Response
    */
    public function index_get($userId){
        $data = $this->Comprador_model->misCompras($userId);
        foreach($data as $val){ 
            $fDate='';
            if(!is_null($val->FECHATRANSACCION)){
                $f = explode(' ', $val->FECHATRANSACCION);
                $fDate = date_format(date_create($f[0]), 'd/m/y');
            }
            $state="APROBADA";
            if($val->ESTADOTRANSACCION == 'PENDING')
                $state="PENDIENTE";
            if($val->ESTADOTRANSACCION == 'REJECTED')
                $state="RECHAZADA";

            $res[] = array(
                'ID'=>$val->ID,
                'FECHATRANSACCION'=>$fDate,
                'REFERENCIA'=>$val->REFERENCIA,
                'NUMEROAUTORIZACIONTX'=>$val->NUMEROAUTORIZACIONTX,
                'ESTADOTRANSACCION'=>$state,
                'VALOR_SALDO'=>$val->VALOR_SALDO,
                'VALOR_BANCO'=>$val->VALOR_BANCO,
                'VALORTOTAL'=>$val->VALORTOTAL,
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * Return the shoppings user per day.
     *
     * @return Response
    */
    public function day_get($userId){
        $data = $this->Comprador_model->misComprasXdia($userId);
        $this->response ($data, REST_Controller :: HTTP_OK);
    }
}
