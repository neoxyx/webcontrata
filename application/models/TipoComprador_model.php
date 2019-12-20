<?php
class TipoComprador_model extends CI_Model
{
	private $dbO;

	function __construct(){
	$this->dbO = $this->load->database("oracle", TRUE); 
	}

    public function findAll($id = '')
	{
	  if(empty($id)){			
			$result = $this->dbO->get('tipo_comprador');
		
			if(!$result->num_rows() == 1)
			{
				return false;
			}
		
			return $result->result();
	  } else {
		
			$result = $this->dbO->get_where('tipo_comprador', array('idtipo_comprador',$id));
		
			if(!$result->num_rows() == 1)
			{
				return false;
			}
		
			return $result->row();
	  }
	}  

	public function insert_update()
	{
		$id = $this->input->post('id');
		if(empty($id)){
			$data = array(
				'desc' => $this->input->post('tipoc')
			);
			$this->dbO->insert('tipo_comprador', $data);
			if($this->dbO->affected_rows()>0){
				return true;
			} else {
				return false;
			}		
	  	} else {
			$data = array(
				'desc' => $this->input->post('tipoc')
			);
			$this->dbO->where('idtipo_comprador', $id);
			$this->dbO->update('tipo_comprador', $data);
			if($this->dbO->affected_rows()>0){
				return true;
			} else {
				return false;
			}
	 	}
        
	}	

}
?>