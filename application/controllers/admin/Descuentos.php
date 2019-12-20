<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Descuentos extends CI_Controller {

	public function diasEspeciales()
	{		
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}		
		$this->load->model("Descuentos_model");
		$this->load->model("TipoComprador_model");
		$this->load->model("Loterias_model");
		$data["descuentos"] = $this->Descuentos_model->findAll(DCTO_ESP);
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("descuentos.js");
		$this->load->view('admin/header');
		$this->load->view('admin/descuentos/diasEspeciales',$data);
		$this->load->view('admin/footer');
	}
    
    public function rangoCompras()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Descuentos_model");
		$this->load->model("Loterias_model");
		$data["descuentos"] = $this->Descuentos_model->findAll(DCTO_COMPRA);
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("descuentos.js");
		$this->load->view('admin/header');
		$this->load->view('admin/descuentos/rangoCompras',$data);
		$this->load->view('admin/footer');
    }
    
    public function paqueteLoterias()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Descuentos_model");
		$this->load->model("Loterias_model");
		$data["descuentos"] = $this->Descuentos_model->findAll(DCTO_PAQUETE);
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("descuentos.js");
		$this->load->view('admin/header');
		$this->load->view('admin/descuentos/paqueteLoterias',$data);
		$this->load->view('admin/footer');
    }
    
    public function abonado()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Descuentos_model");
		$this->load->model("Loterias_model");
		$data["descuentos"] = $this->Descuentos_model->findAll(DCTO_ABONADO);
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("descuentos.js");
		$this->load->view('admin/header');
		$this->load->view('admin/descuentos/abonado',$data);
		$this->load->view('admin/footer');
    }
    
    public function codPromocionales()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Descuentos_model");
		$this->load->model("Loterias_model");
		$data["descuentos"] = $this->Descuentos_model->findAll(DCTO_CODPROMO);
		$data["loterias"] = $this->Loterias_model->findAll();
		$data['js_to_load']=array("descuentos.js");
		$this->load->view('admin/header');
		$this->load->view('admin/descuentos/codPromocionales',$data);
		$this->load->view('admin/footer');
	}
}