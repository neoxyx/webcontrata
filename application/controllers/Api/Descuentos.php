<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class DESCUENTOS extends REST_Controller {
    private $dbO;

	function __construct(){
        parent::__construct();
	    $this->dbO = $this->load->database("oracle", TRUE); 
	}
    // se accede con http://miservidor/Api/DESCUENTOS?format=json
   /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = 0)
	{
        if(!empty($id)){
            $data = $this->dbO->get_where("DESCUENTOS", ['IDDESCUENTO' => $id])->row_array();
        }else{
            $data = $this->dbO->get("DESCUENTOS")->result();
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
        $this->load->model('Descuentos_model');
        $input = $this->post();
        $iddcto = $this->Descuentos_model->obtenerUltimoIdDcto()->IDDESCUENTO+1;               
        $sql = "INSERT INTO DESCUENTOS (IDDESCUENTO,NOMBRE,COMPROMISO,FECHAHORAINICIO,FECHAHORAFIN,DCTO,ESTADO,VRINICIAL,VRFINAL,LOTERIA,CANTSORTEOSABONADOS,CODIGO,CANTCUPONESDISP,VRCUPON,IDTIPO_COMPRADOR,IDTIPO_PROMOCION,IDTIPO_DCTO) VALUES ($iddcto,'".$input['NOMBRE']."',".$input['COMPROMISO'].",TO_DATE('".$input['FECHAHORAINICIO']."','yy-mm-dd HH24:MI:SS'),TO_DATE('".$input['FECHAHORAFIN']."','yy-mm-dd HH24:MI:SS'),".$input['DCTO'].",".$input['ESTADO'].",".$input['VRINICIAL'].",".$input['VRFINAL'].",'".$input['LOTERIA']."',".$input['CANTSORTEOSABONADOS'].",'".$input['CODIGO']."',".$input['CANTCUPONESDISP'].",".$input['VRCUPON'].",".$input['IDTIPO_COMPRADOR'].",".$input['IDTIPO_PROMOCION'].",".$input['IDTIPO_DCTO'].")";
        $this->dbO->query($sql); 
        $this->response(['message'=>'Item updated successfully.'], REST_Controller::HTTP_OK);
    } 
     
    /**
     * Update All Data from this method.
     *
     * @return Response
    */
    public function index_put($id)
    {
        $input = $this->put();
        $sql = "UPDATE DESCUENTOS SET NOMBRE='".$input['NOMBRE']."',COMPROMISO=".$input['COMPROMISO'].",FECHAHORAINICIO=TO_DATE('".$input['FECHAHORAINICIO']."','yy-mm-dd HH24:MI:SS'),FECHAHORAFIN=TO_DATE('".$input['FECHAHORAFIN']."','yy-mm-dd HH24:MI:SS'),DCTO=".$input['DCTO'].",ESTADO=".$input['ESTADO'].",VRINICIAL=".$input['VRINICIAL'].",VRFINAL=".$input['VRFINAL'].",LOTERIA='".$input['LOTERIA']."',CANTSORTEOSABONADOS=".$input['CANTSORTEOSABONADOS'].",CODIGO='".$input['CODIGO']."',CANTCUPONESDISP=".$input['CANTCUPONESDISP'].",VRCUPON=".$input['VRCUPON'].",IDTIPO_COMPRADOR=".$input['IDTIPO_COMPRADOR'].",IDTIPO_PROMOCION=".$input['IDTIPO_PROMOCION'].",IDTIPO_DCTO=".$input['IDTIPO_DCTO']." WHERE IDDESCUENTO = $id ";
        $this->dbO->query($sql);    
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }

    /**
     * Update estado from this method.
     *
     * @return Response
    */
    public function estado_put($id)
    {
        $input = $this->put();
        $sql = "UPDATE DESCUENTOS SET ESTADO= ".$input['ESTADO']." WHERE IDDESCUENTO = $id ";        
        $this->dbO->query($sql);    
        $this->response(['Item updated successfully.'], REST_Controller::HTTP_OK);
    }

    
     
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_delete($id)
    {
        $this->dbO->delete('DESCUENTOS', array('IDDESCUENTO'=>$id));
       
        $this->response(['Item deleted successfully.'], REST_Controller::HTTP_OK);
    }

    
}