<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Welcome extends CI_Controller {

	public function index()
	{
		$data['js_to_load']="";
		$this->load->view('templates/jobply/header');
		$this->load->view('templates/jobply/index');
		$this->load->view('templates/jobply/footer');
	}
	
}
