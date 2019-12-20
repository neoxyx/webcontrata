<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Welcome extends CI_Controller {

	public function index()
	{
		echo ANGULAR_URL;
		//$this->load->view('templates/barcode');
	}
	
}
