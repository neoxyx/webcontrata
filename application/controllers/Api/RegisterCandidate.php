<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class RegisterCandidate extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    
    public function index_post()
    {
        $this->load->model('Candidates_model');
        $this->response ( ['Item created successfully.'], REST_Controller :: HTTP_OK );
    }
}
