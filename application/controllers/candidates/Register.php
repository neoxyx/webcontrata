<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Register extends CI_Controller {

	public function index()
	{
		$this->load->model('Locations_model');
		$data['locations'] = $this->Locations_model->find();
		$data['js_to_load']=array("candidates/register.js");
		$this->load->view('templates/jobply/header');
		$this->load->view('templates/jobply/register-candidate',$data);
		$this->load->view('templates/jobply/footer');
	}
	
}