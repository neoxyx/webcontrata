<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Numeros extends REST_Controller {
    // se accede con http://miservidor/restserver/users?format=json
    public function index_get() {
        $this->load->model('Numeros_model');
        $sorteo = $this->input->get('sorteo');
        $serie = $this->input->get('serie');
        $num = $this->input->get('numero');
        $loteria = $this->input->get('loteria');
        if (empty($num)) {
            $data = $this->Numeros_model->findAll(); 
        } else {              
            $data = $this->Numeros_model->find($sorteo,$serie,$num,$loteria); 
        }     
        $this->response ( $data, REST_Controller :: HTTP_OK );
    }
    
    public function fechasSorteos_get(){
        $this->load->model('Numeros_model');
        $loteria = $this->input->get('sLoteriasRH');
        $datas = $this->Numeros_model->findDates($loteria);        
        $fechas = "";
        foreach($datas as $data){
            $fechas .= '<option value="'.$data->FECHAJUEGA.'">'.$data->FECHAJUEGA.'</option>';
        }
        $this->response ( $fechas, REST_Controller :: HTTP_OK );
    }
    
    public function registrosHistoricos_get(){
        $this->load->model('Numeros_model');
        $loteria = $this->input->get('sLoteriasRH');
        $fecha = $this->input->get('sFechaSorRH');
        $datas = $this->Numeros_model->registrosHistoricos($loteria, $fecha); 
        if($datas){
            $pmNum = '';
            $pmSerie = '';
            $seco1 = '';
            $seco2 = '';
            $secos3 = '';
            $secos4 = '';
            $secos5 = '<tr>
                        <td colspan="8"><div class="titTab2"><b>15 secos de $20.000.000</b></div></td>
                    </tr>
                    <tr class="trTab2">
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                    </tr>';
            $secos6 = '<tr>
                        <td colspan="8"><div class="titTab2"><b>20 secos de $10.000.000</b></div></td>
                    </tr>
                    <tr class="trTab2">
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                        <td><b>Número</b></td>
                        <td><b>Serie</b></td>
                    </tr>';
            $x5=0;
            $x6=0;
            
            //BORRAR LUEGO
            $y3=0;
            $y4=0;
            $y5=0;
            $y6=0;
            // BORRAR LUEGO
            foreach($datas as $data){
                if($data->CODIGOPREMIO == 404){
                    $pmNum = $data->NUMERO;
                    $pmSerie = $data->SERIE;
                }
                if($data->CODIGOPREMIO == 404){
                    $seco1 = array(
                        'num' => $data->NUMERO,
                        'serie' => $data->SERIE
                    );
                }
                if($data->CODIGOPREMIO == 502){
                    $seco2 = array(
                        'num' => $data->NUMERO,
                        'serie' => $data->SERIE
                    );
                }
                if($data->CODIGOPREMIO == 503){ 
                    if($y3<2){
                    $secos3 .= '<td>'.$data->NUMERO.'</td>';
                    $secos3 .= '<td>'.$data->SERIE.'</td>';
                    $y3++;
                    }
                }
                if($data->CODIGOPREMIO == 422){
                    if($y4<3){
                    $secos4 .= '<td>'.$data->NUMERO.'</td>';
                    $secos4 .= '<td>'.$data->SERIE.'</td>';
                    $y4++;
                    }
                }
                if($data->CODIGOPREMIO == 427){
                    if($y5<15){
                    if($x5 == 0)
                        $secos5 .= '<tr class="trTab2">';
                    $x5++;
                    
                    $secos5 .= '<td>'.$data->NUMERO.'</td>';
                    $secos5 .= '<td>'.$data->SERIE.'</td>';
                                        
                    if($x5 == 4){
                        $secos5 .= '</tr>';   
                        $x5=0;
                    }
                    $y5++;
                    }
                }
                if($data->CODIGOPREMIO == 421){
                    if($y6<20){
                    if($x6 == 0)
                        $secos6 .= '<tr class="trTab2">';
                    $x6++;
                    
                    $secos6 .= '<td>'.$data->NUMERO.'</td>';
                    $secos6 .= '<td>'.$data->SERIE.'</td>';
                                        
                    if($x6 == 4){
                        $secos6 .= '</tr>';   
                        $x6=0;
                    }
                    }
                    $y6++;
                }
            }
            
            $regHistoricos = array(
                'sorteo' => $datas[0]->SORTEOLOTERIA,
                'pmNum' => $pmNum,
                'pmSerie' => $pmSerie,
                'seco1' => $seco1,
                'seco2' => $seco2,
                'secos3' => $secos3,
                'secos4' => $secos4,
                'secos5' => $secos5,
                'secos6' => $secos6,
            );
            $this->response ( $regHistoricos, REST_Controller :: HTTP_OK );
        }
        else
            $this->response (false, REST_Controller :: HTTP_OK );
    }
}