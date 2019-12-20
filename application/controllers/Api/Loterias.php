<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Loterias extends REST_Controller {
    // Migrado...
    public function index_get() {
        $this->load->model('Loterias_model');
        $id = $this->input->get('EMPRESA');
        $data = $this->Loterias_model->findAll($id);  
        $this->response ( $data, REST_Controller :: HTTP_OK );
    }

    // 
    public function datosModalJugar_get(){
        $this->load->model('Loterias_model');
        $loteria = $this->input->get('loteria');
        $dataS = $this->Loterias_model->find($loteria);
        $data = $this->Loterias_model->findAll($loteria);
        if($data){
            $sec1MJ='<div class="imgLogJugar col-xs-6 col-md-6 col-sm-6 col-lg-6">
                        <image src="'. base_url().'dist/portal/images/home/'.$dataS->logo.'"/>
                    </div>
                    <div class="col-xs-6 col-md-6 col-sm-6 col-lg-6">
                        <label>Lotería: '.$data->NOMBRE.'</label><br>
                        <label>Premio mayor: $'.$data->VALORPREMIOMAYOR.'</label><br>
                        <label>Valor fraccion: $'.$data->VALORFRACCION.'</label><br>
                        <label>Fracciones por billete: '.$data->TOTALFRACCIONES.'</label><br>
                        <label>Sorteo: '.$data->SORTEO.'</label><br>
                        <label>Fecha sorteo: '.$data->FECHASORTEO.'</label>
                    </div>';
            $sec2MJ='<label>Premio mayor</label>
                    <table class="table table-bordered table-responsive table-striped">
                        <thead>
                            <tr>
                                <td colspan="2" class="tdJugar"><h3 class="h3Jugar">$'.$data->VALORPREMIOMAYOR.'</h3></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1 seco de $400.000.000</td>
                                <td>1 seco de $200.000.000</td>
                            </tr>
                            <tr>
                                <td>2 seco de $100.000.000</td>
                                <td>3 secos de $50.000.000</td>
                            </tr>
                            <tr>
                                <td>15 secos de $20.000.000</td>
                                <td>20 secos de $10.000.000</td>
                            </tr>
                        </tbody>
                    </table>';

            $datos = array(
                'sec1MJ' => $sec1MJ,
                'sec2MJ' => $sec2MJ,
                'sorteo' => $data->SORTEO,
                'fracciones' => $data->TOTALFRACCIONES,
                'loteria' => $data->LOTERIA,
                'nombre' => $data->NOMBRE,
                'valFrag' => $data->VALORFRACCION
            );
            $this->response ( $datos, REST_Controller :: HTTP_OK );
        }
        else
            $this->response (false, REST_Controller :: HTTP_OK );
    }
    
}