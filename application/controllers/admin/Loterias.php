<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Loterias extends CI_Controller {

	public function index()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Loterias_model");
        $data['js_to_load']=array("loterias.js");
        $loteries = $this->Loterias_model->find();
        $res = array();
        foreach($loteries as $lotery){
            $lot =  $this->Loterias_model->findAll($lotery->loteria);  
            if($lot){                           
                $obj = array(
                    'idLoteria' => $lotery->idLoteria,
                    'name' => $lot->NOMBRE,
                    'order' => $lotery->order			
                );     
                $res[] = $obj;
            }         
        }        
        $data['lotterys'] = $res;
		$this->load->view('admin/header');
		$this->load->view('admin/loterias/index',$data);
		$this->load->view('admin/footer');
    }

    public function updateOrder(){
        $this->load->model("Loterias_model");
        $cont = count($_POST["idLoteria"]);
        if($cont > 0){
            for($i=0;$i<$cont;$i++){
                $data = array(
                    'order' => $_POST["order"][$i]
                );
                $this->Loterias_model->update($_POST["idLoteria"][$i],$data);                
            }
        }  
        redirect('admin/Loterias');      
    }
}