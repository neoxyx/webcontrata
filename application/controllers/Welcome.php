<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Welcome extends CI_Controller {

	public function index()
	{
		$data['js_to_load']=array("candidates/register.js","candidates/login.js");
		$this->load->view('templates/jobply/header');
		$this->load->view('templates/jobply/index',$data);
		$this->load->view('templates/jobply/footer');
	}

	public function logout()
    {
        $this->load->library('session');
        $data = array(
            'name',
            'email',
            'logged_in'
        );    
        $this->session->unset_userdata($data);
        redirect('Welcome');
    }
	
}
