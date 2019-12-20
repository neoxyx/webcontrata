<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Saldos extends CI_Controller {

	public function index()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model("SaldosCompradores_model");
		$data['js_to_load']=array("saldos.js");
		$data['saldo'] = $this->SaldosCompradores_model->getBalanceInitial();
		$this->load->view('admin/header');
		$this->load->view('admin/saldos/saldo_inicial',$data);
		$this->load->view('admin/footer');
    }
}