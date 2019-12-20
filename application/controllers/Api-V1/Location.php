<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Location extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Comprador_model');
    }

    /**
     * Search the cities associated with a department.
     *
     * @return Response
    */
    public function index_get(){
        $dpt = $this->get('department');
        $cities = $this->Comprador_model->getCiudades($dpt);
        $this->response ($cities, REST_Controller :: HTTP_OK);
    }
}
