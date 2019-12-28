<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class TipoPromocion extends REST_Controller {

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
	public function index_get($id = 0)
	{
        if(!empty($id)){
            $data = $this->dbO->get_where("PORTAL_DML.TIPO_PROMOCION", ['IDTIPO_PROMOCION' => $id])->row_array();
        }else{
            $data = $this->dbO->get("PORTAL_DML.TIPO_PROMOCION")->result();
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
	}
      
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        $input = $this->input->post();        
        $this->dbO->insert('TIPO_PROMOCION',$input);
     
        $this->response(['Item created successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
        $this->dbO->update('PORTAL_DML.TIPO_PROMOCION', $input, array('IDTIPO_PROMOCION'=>$id));
     
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        $this->dbO->delete('PORTAL_DML.TIPO_PROMOCION', array('IDTIPO_PROMOCION'=>$id));
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }
}
