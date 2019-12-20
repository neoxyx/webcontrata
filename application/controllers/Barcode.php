<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/Zend.php';
class Barcode extends CI_Controller {

	public function index()
	{
		//I'm just using rand() function for data example
		$code=$this->input->get('code');
		$this->set_barcode($code);
	}
	
	private function set_barcode($code)
	{
		//load library
		$this->load->library('zend');
		//load in folder Zend
		$this->zend->load('Zend/Barcode');
		//generate barcode
		Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
	}
	
}
