<?php
class Iconos_model extends CI_Model
{

        public function get_iconos_redes()
        {				
                $query = $this->db->get_where('images', array('types_idType'=> '2'));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }
		
		public function insert_slide_home($file)
		{
			$data = array(
				'name' => $file,
				'types_idType' => 1,
				'class' => 'item'
				);

			$this->db->insert('images', $data);
		}

		public function update_slide_home($file,$id)
		{
			$data = array(
				'name' => $file,
				);

			$this->db->where('idImage', $id);
			$this->db->update('images', $data);
		}

		public function delete_slide_home($id)
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
