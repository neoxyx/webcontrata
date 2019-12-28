<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Register extends CI_Controller {

	public function index()
	{
		$this->load->view('templates/jobply/header');
		$this->load->view('templates/jobply/register-company');
		$this->load->view('templates/jobply/footer');
	}
	
}