<?php
class TipoPromocion_model extends CI_Model
{
	private $dbO;

	function __construct(){
	$this->dbO = $this->load->database("oracle", TRUE); 
	}

        public function get()
        {				
                $query = $this->dbO->get('tipo_promocion');
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }
}
?>