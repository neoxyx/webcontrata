<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Puntos extends CI_Controller {

	public function reglas_compras()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Puntos_model");
		$this->load->model("Loterias_model");
		$data['js_to_load']=array("puntos.js");
		$data["puntosCompras"] = $this->Puntos_model->getPointPurchase();
		$data["loterias"] = $this->Loterias_model->findAll();
		$this->load->view('admin/header');
		$this->load->view('admin/puntos/reglas_compras', $data);
		$this->load->view('admin/footer');
    }

    public function reglas_referido()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Puntos_model");
		$data['js_to_load']=array("puntos.js");
		$data["puntosReferidos"] = $this->Puntos_model->getPointReferens();
		$this->load->view('admin/header');
		$this->load->view('admin/puntos/reglas_referido', $data);
		$this->load->view('admin/footer');
    }

    public function productos()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Premio_model");
		$data['js_to_load']=array("puntos.js");
		$data["productos"] = $this->Premio_model->findAllproducts();
		$this->load->view('admin/header');
		$this->load->view('admin/puntos/productos', $data);
		$this->load->view('admin/footer');
    }

    public function pedidos()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Premio_model");
		$data['js_to_load']=array("puntos.js");
		$data["canjeos"] = $this->Premio_model->findAll();
		$this->load->view('admin/header');
		$this->load->view('admin/puntos/pedidos', $data);
		$this->load->view('admin/footer');
	}
	
	public function puntos_saldo_compromiso()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("Puntos_model");
		$data['js_to_load']=array("puntos.js");
		$data["codigo"] = $this->Puntos_model->getBudgetId();
		$this->load->view('admin/header');
		$this->load->view('admin/puntos/puntos_saldo_compromiso', $data);
		$this->load->view('admin/footer');
    }
}