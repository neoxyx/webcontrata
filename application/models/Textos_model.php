<?php
class Textos_model extends CI_Model
{

        public function get_textos()
        {				
                $query = $this->db->get('textos');
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

		public function get_textos_links1_footer()
        {				
                $query = $this->db->get_where('textos', array('pagina'=>'footer','seccion'=>1));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

		public function get_textos_links2_footer()
        {				
                $query = $this->db->get_where('textos', array('pagina'=>'footer','seccion'=>2));
				if ($query->num_rows() > 0) {
					return $query->result();
				} else {
					return false;
				}
        }

		public function get_texto_tel()
        {				
                $query = $this->db->get_where('textos', array('pagina'=>'footer','seccion'=>3, 'posicion'=>1));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }

		public function get_texto_email()
        {				
                $query = $this->db->get_where('textos', array('pagina'=>'footer','seccion'=>3, 'posicion'=>2));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }

		public function get_texto_dir()
        {				
                $query = $this->db->get_where('textos', array('pagina'=>'footer','seccion'=>3, 'posicion'=>3));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }

		public function get_texto_secc6()
        {				
                $query = $this->db->get_where('textos',array('seccion'=>6,'posicion'=>1));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }

		public function get_texto_secc7_pos1()
        {				
                $query = $this->db->get_where('textos',array('seccion'=>7,'posicion'=>1));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }
		public function get_texto_secc7_pos2()
        {				
                $query = $this->db->get_where('textos',array('seccion'=>7,'posicion'=>2));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }
		public function get_texto_secc7_pos3()
        {				
                $query = $this->db->get_where('textos',array('seccion'=>7,'posicion'=>3));
				if ($query->num_rows() > 0) {
					return $query->row();
				} else {
					return false;
				}
        }

		
		public function insert($param)
		{			
			$this->db->insert('textos', $param);
		}

		public function update($id,$data)
		{			
			$this->db->where('idTexto', $id);
			$this->db->update('textos', $data);
		}

		public function delete_texto($id)
		{			
			$this->db->where('idTexto', $id);
			$this->db->delete('textos');
			if($this->db->affected_rows()>0){
				return true;
			} else {
				return false;
			}
		}

}
?>
