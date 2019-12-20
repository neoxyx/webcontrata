<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Compromisos extends REST_Controller {

    private $dbO;

	function __construct(){
        parent::__construct();
	    $this->dbO = $this->load->database("oracle", TRUE); 
	}
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = '')
	{
        if(!empty($id)){
            $data = $this->dbO->get_where("presup01.maestro_compromiso@loteria ", ['CODIGO_COMPROMISO' => $id])->row_array();
        }else{
            $sql = "SELECT CODIGO_COMPROMISO FROM presup01.maestro_compromiso@loteria ";
            $data = $this->dbO->query($sql)->result();
        }     
        $this->response($data, REST_Controller::HTTP_OK);
	}
}
