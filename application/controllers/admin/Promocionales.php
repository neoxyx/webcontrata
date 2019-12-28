<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Promocionales extends CI_Controller {

	public function registrarPromocional()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Loterias_model");
		$this->load->model("Promotional_model");
		$data["tipos"] = $this->Promotional_model->getTypesPromotionals();
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("promocionales.js");
		$this->load->view('admin/header');
		$this->load->view('admin/promocionales/registrarPromocional',$data);
		$this->load->view('admin/footer');
	}
	
	public function solicitudesEnvio()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Promotional_model");
		$data['js_to_load']=array("promocionales.js");
		$data["solicitudes"] = $this->Promotional_model->getPromotionalsUsers();
		$this->load->view('admin/header');
		$this->load->view('admin/promocionales/solicitudesEnvio',$data);
		$this->load->view('admin/footer');
    }
}