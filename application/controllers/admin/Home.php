<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Home extends CI_Controller {

	public function index()
	{
		if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$data['js_to_load']=array("footer.js");
		$this->load->view('admin/header');
		$this->load->view('admin/home');
		$this->load->view('admin/footer',$data);
	}
}
