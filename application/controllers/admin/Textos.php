<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Textos extends CI_Controller {

	public function index()
	{
        if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model('Textos_model');
		$data['textos'] = $this->Textos_model->get_textos();
		$data['js_to_load']= '';
		$this->load->view('admin/header');
		$this->load->view('admin/textos',$data);
		$this->load->view('admin/footer');
	}

	public function add(){
		$this->load->model('Textos_model');
		$data = array(
		'pagina' => $this->input->post('pagina'),
		'seccion' => $this->input->post('seccion'),
		'posicion' => $this->input->post('posicion'),
		'texto' => $this->input->post('texto'),
		'titulo' => $this->input->post('titulo'),
		'link' => $this->input->post('link')
		);
		$this->Textos_model->insert($data);
		redirect('admin/Textos');
	}

	public function edit(){
		$this->load->model('Textos_model');
		$data = array(
			'titulo' => $this->input->post('titulo'),
			'texto' => $this->input->post('texto'),
			'link' => $this->input->post('link')
		);
		$this->Textos_model->update($this->input->post('id'),$data);
		redirect('admin/Textos');
	}

}
