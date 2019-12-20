<?php
class Redes_model extends CI_Model
{

        public function get_redes()
        {				
                $query = $this->db->get('iconos');
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

        public function get_redes_header()
        {				
                $query = $this->db->get_where('iconos',array('estado'=>1, 'seccion'=>'header'));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

        public function get_redes_footer()
        {				
                $query = $this->db->get_where('iconos',array('estado'=>1, 'seccion'=>'footer'));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

		public function update($id,$data)
		{			
			$this->db->where('idIcono', $id);
			$this->db->update('iconos', $data);
		}

}
?>