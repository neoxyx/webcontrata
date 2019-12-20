<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Login extends CI_Controller {

	public function index()
	{
		$this->load->view('admin/login');
	}

	public function very_login(){		
		$this->load->model('Login_model');
		$res = $this->Login_model->get_login($this->input->post('user'),
		$this->input->post('pass'));
		$newdata = array(
			'user' => $res->user,
			'name' => $res->name.' '.$res->sname,
			'created' => $res->created_at,
			'iduser' => $res->iduser						
		);
		$session = $this->session->set_userdata($newdata);
		if ($res !== false) {
			echo 'ok';
		} else {
			echo 'ko';
		}
	}

	public function logout(){
		$array_items = array('user', 'name', 'created', 'iduser');
		$this->session->unset_userdata($array_items);
		redirect('admin/Login');
	}
}
