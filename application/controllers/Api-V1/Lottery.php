<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Lottery extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('Loterias_model');
        $this->load->model('Numeros_model');
    }

    /**
     * Check the lottery data.
     *
     * @return Response
    */
    public function index_get() {
        $id = $this->input->get('lottery');
        $res = $this->Loterias_model->findAll($id);  
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }

    /**
     * Load the lotteries with the result of the last draw.
     *
     * @return Response
    */
    public function results_get() {
        $loteries = $this->Loterias_model->find();	 

        $res = array();

		foreach($loteries as $lotery){
			$lot =  $this->Loterias_model->findAll($lotery->loteria);
            $premLot = $this->Loterias_model->getResulSorteo($lotery->loteria);

            if($lot && $premLot){
                if($lot->DIA == 1)
                    $day = 'Lunes';
                if($lot->DIA == 2)
                    $day = 'Martes';
                if($lot->DIA == 3)
                    $day = 'Miércoles';
                if($lot->DIA == 4)
                    $day = 'Jueves';
                if($lot->DIA == 5)
                    $day = 'Viernes';
                if($lot->DIA == 6)
                    $day = 'Sábados';
                if($lot->DIA == 7)
                    $day = 'Domingos'; 

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
                    'drawDay' => $day,
                    'drawDate' =>date_format(date_create($premLot->FECHAJUEGA), 'm/d/y'),
                    'number' => $premLot->NUMERO,
                    'serie' => $premLot->SERIE,
                    'jackPot' => $lot->VALORPREMIOMAYOR,
                    'id' => $lotery->loteria,
                    'closed' => $valid				
                );
                $res[] = $obj;
            }
        }
        
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }

    /**
     * check the historical results of a raffle.
     *
     * @return Response
    */
    public function historical_results_get($id) {
        $drawDate = $this->input->get('drawDate');
        $datas = $this->Numeros_model->registrosHistoricos($id, $drawDate); 
        if($datas){
            $jackpot = array();
            $firPrize = array();
            $secPrize = array();
            $thiPrize = array();
            $fourPrize = array();
            $fiftPrize = array();
            $sixPrize = array();
           
            foreach($datas as $data){ 
                if($data->CODIGOPREMIO == 101){
                    $jackpot[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE
                    );
                }
                if($data->CODIGOPREMIO == 201){
                    $firPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE
                    );
                }
                if($data->CODIGOPREMIO == 202){
                    $secPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE
                    );
                }
                /*if($data->CODIGOPREMIO == 503){ 
                    $thiPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE,
                        'ticketValue' => $data->VALORBILLETE,
                        'fractionValue' => $data->VALORFRACCION
                    ); 
                }
                if($data->CODIGOPREMIO == 422){
                    $fourPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE,
                        'ticketValue' => $data->VALORBILLETE,
                        'fractionValue' => $data->VALORFRACCION
                    ); 
                }*/
                if($data->CODIGOPREMIO == 203){
                    $fiftPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE,
                        'ticketValue' => $data->VALORBILLETE,
                        'fractionValue' => $data->VALORFRACCION
                    );
                }
                if($data->CODIGOPREMIO == 204){
                    $sixPrize[] = array(
                        'number' => $data->NUMERO,
                        'serie' => $data->SERIE,
                        'ticketValue' => $data->VALORBILLETE,
                        'fractionValue' => $data->VALORFRACCION
                    );
                }
            }
            
            $regHistoricos = array (
                'id' => $id,
                'date' => $drawDate,
                'draw' => $datas[0]->SORTEOLOTERIA,
                'jackpot' => $jackpot,
                'firPrize' => $firPrize,
                'secPrize' => $secPrize,
                'thiPrize' => $thiPrize,
                'fourPrize' => $fourPrize,
                'fiftPrize' => $fiftPrize,
                'sixPrize' => $sixPrize
            );
            $this->response ( $regHistoricos, REST_Controller :: HTTP_OK );
        }
        else
            $this->response (false, REST_Controller :: HTTP_OK );
    }

    /**
     * check the lottery draw dates.
     *
     * @return Response
    */
    public function draws_dates_get($id){
        $fechas = $this->Numeros_model->findDates($id);  
        $this->response ( $fechas, REST_Controller :: HTTP_OK );
    }

     /**
     * Check the lottery data and the logo of the lottery.
     *
     * @return Response
    */
    public function lottery_logo_get() {
        $id = $this->input->get('lottery');
        $res['datos'] = $this->Loterias_model->findAll($id);
        $res['logo'] = $this->Loterias_model->find($id)->logo;  
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }

    /**
     * return de balance of budget.
     *
     * @return Response
    */
    public function budget_commitment_get($compromiso){
        $res = $this->Loterias_model->budgetCommitment($compromiso);  
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }

    /**
     * validate of the number is win or not.
     *
     * @return Response
    */
    public function validate_winning_get($lottery){
        $sorteo = $this->input->get('sorteo');
        $serie = $this->input->get('serie');
        $number = $this->input->get('number');
        $data = $this->Numeros_model->find($sorteo, $serie, $number, $lottery);  

        $res = array(
            'tittle' => 'Lo sentimos',
            'code' =>  http_response_code(),
            'message' => 'Su número no fue el ganador en este sorteo.',
            'description' => 'Número no encontrado en la base de datos como ganador',
            'state' => 'error'
        );

        if($data){
            $res = array(
                'tittle' => 'Felicidades',
                'code' =>  http_response_code(),
                'message' => 'Su número y serie fueron las ganadoras en este sorteo.',
                'description' => 'Se encontro registro en la BD',
                'state' => 'success'
            );
        }

        $this->response ( $res, REST_Controller :: HTTP_OK );
    }    
}