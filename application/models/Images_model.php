<?php
class Images_model extends CI_Model
{

        public function get($type,$class='')
        {		
			if($class!=''){
				$query = $this->db->get_where('images', array('types_idType'=> $type, 'class' => $class));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
			} else {
				$query = $this->db->get_where('images', array('types_idType'=> $type));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
			}		                			
        }
		
		public function insert($file,$type,$class)
		{
			$data = array(
				'name' => $file,
				'types_idType' => $type,
				'class' => $class
				);

			$this->db->insert('images', $data);
		}

		public function update($file,$id)
		{
			$data = array(
				'name' => $file,
				);

			$this->db->where('idImage', $id);
			$this->db->update('images', $data);
		}

		public function delete($id)
		{			
			$this->db->where('idImage', $id);
			$this->db->delete('images');
			if($this->db->affected_rows()>0){
				return true;
			} else {
				return false;
			}
		}

}
?>
