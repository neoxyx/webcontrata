<?php
class Locations_model extends CI_Model {

	public function find($id = '')
    {
		if(!$id){
			$query = $this->db->get('locations');
			  if( $query->num_rows() > 0){
			   return $query->result();
			  } else {
			  	  return false;
			  }
		} else { 
            $query = $this->db->get_where('locations',array('idlocation'=>$id));
            if( $query->num_rows() > 0){
                return $query->row();
            } else {
                return false;
            }
		}
	}
}
?>