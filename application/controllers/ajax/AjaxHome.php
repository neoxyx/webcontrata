<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class AjaxHome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
        function registrarComprador(){
            $url = "http://10.201.31.2:8089/integrador/remote/LoteriaMedellin?wsdl";
            try {
                $client = new SoapClient($url);
                $afec = explode("-",$_GET['fecha']);
                $fecha = $afec[0]."/".$afec[1]."/".$afec[2];
                $parametros=array(); //parametros de la llamada
                $parametros['arg0']=$this->input->get('cedula');
                $parametros['arg1']=$_GET['correo'];
                $parametros['arg2']=$_GET['pName'];
                $parametros['arg3']=$_GET['sName'];
                $parametros['arg4']=$_GET['pApellido'];
                $parametros['arg5']=$_GET['sApellido'];
                $parametros['arg6']=sha1($_GET['pass']);
                $parametros['arg7']=$fecha;
                $result = $client->crearUsuarioMovil($parametros);
                $retornar = json_encode($result);
                $this->validarReferido($_GET['correo']);
                //$retornar = json_decode(json_encode($result), true);
                //print_r($retornar);
                
                echo $retornar;
                    
            } catch ( SoapFault $e ) {
                echo json_encode($e->getMessage());
            }
        }           
        
        public function validarReferido($correo){
            $this->load->model('Comprador_model');
            $data = $this->Comprador_model->validarReferido($correo); 
            if($data){
                $this->Comprador_model->updatePadre($data->REFERIDOR);
                $this->Comprador_model->deleteReferido($correo);
            }
        }
}




