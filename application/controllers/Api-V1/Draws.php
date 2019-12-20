<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Draws extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('Sorteos_model');
    }

    /**
     * Check the lottery data.
     *
     * @return Response
    */
    public function index_get() {
        $id = $this->input->get('lottery');
        $draw = $this->input->get('draw');
        $res = $this->Sorteos_model->find($id,$draw);  
        $this->response ( $res, REST_Controller :: HTTP_OK );
    }
}