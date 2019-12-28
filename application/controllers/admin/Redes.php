<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Redes extends CI_Controller {

	public function index()
	{
        if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model('Redes_model');
		$data['redes'] = $this->Redes_model->get_redes();
		$data['js_to_load']= '';
		$this->load->view('admin/header');
		$this->load->view('admin/redes',$data);
		$this->load->view('admin/footer');
	}

	public function add(){
        $id = $this->input->post('id');
		$this->load->model('Redes_model');
		$data = array(
		'estado' => $this->input->post('estado'),
		'link' => $this->input->post('link')
		);
		$this->Redes_model->update($id,$data);
		redirect('admin/Redes');
    }

}