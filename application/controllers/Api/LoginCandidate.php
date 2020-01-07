<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class LoginCandidate extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    
    public function index_get()
    {
        $email = $this->input->get('email');
        $pass = $this->input->get('pass');
        $res = $this->db->get_where('users', array('email'=> $email,'pass'=>$pass));
        if($res->num_rows()>0){
            $this->load->library('session');
            $newdata = array(
                'name'  => $res->row()->name.' '.$res->row()->surname,
                'email'     => $res->row()->email,
                'logged_in' => TRUE
            );    
            $this->session->set_userdata($newdata);
            $this->response ( ['mens'=>'ok'], REST_Controller :: HTTP_OK );
        } else {
            $this->response ( ['mens'=>'ko'], REST_Controller :: HTTP_OK );
        }        
    }
}