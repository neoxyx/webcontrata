<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Logos extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        if(!$this->session->has_userdata('user')){			
			redirect('admin/Login');
		}
		$this->load->model('Images_model');
        $data['images'] = $this->Images_model->get(2);
        $data['js_to_load']= '';
		$this->load->view('admin/header');
		$this->load->view('admin/logos',$data);
		$this->load->view('admin/footer');
	}

    public function set_logo_lottired()
    {
    $this->load->model('Images_model');
    $file = $_FILES['file']['name'];
        if(!empty($file)){

            // Set preference
            $config['upload_path'] = './dist/portal/images/home/'; 
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = '1024'; // max_size in kb
            $config['file_name'] = $_FILES['file']['name'];
       
            //Load upload library
            $this->load->library('upload',$config); 
       
            // File upload
            if($this->upload->do_upload('file')){
              // Get data about the file
              $uploadData = $this->upload->data();
              $this->Images_model->insert($file,2,'lottired');
                redirect('admin/Logos');
            }
          }
    }

    public function set_logo_lotmedellin()
    {
        $this->load->model('Images_model');
        $file = $_FILES['file']['name'];
            if(!empty($file)){
    
                // Set preference
                $config['upload_path'] = './dist/portal/images/home/'; 
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '1024'; // max_size in kb
                $config['file_name'] = $_FILES['file']['name'];
           
                //Load upload library
                $this->load->library('upload',$config); 
           
                // File upload
                if($this->upload->do_upload('file')){
                  // Get data about the file
                  $uploadData = $this->upload->data();
                  $this->Images_model->insert($file,2,'medellin');
                    redirect('admin/Logos');
                }
            }
    }

    public function set_logo_placetopay()
    {
        $this->load->model('Images_model');
        $file = $_FILES['file']['name'];
            if(!empty($file)){
    
                // Set preference
                $config['upload_path'] = './dist/portal/images/home/'; 
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = '1024'; // max_size in kb
                $config['file_name'] = $_FILES['file']['name'];
           
                //Load upload library
                $this->load->library('upload',$config); 
           
                // File upload
                if($this->upload->do_upload('file')){
                  // Get data about the file
                  $uploadData = $this->upload->data();
                  $this->Images_model->insert($file,2,'placetopay');
                    redirect('admin/Logos');
                }
            }
    }
	
	public function update_logo() {
        $this->load->model('Images_model');
        $idImage = $this->input->post('id');
        //obtenemos el archivo a subir
        $file = $_FILES['file']['name'];
        if(!empty($file)){

            // Set preference
            $config['upload_path'] = './dist/portal/images/home/'; 
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = '1024'; // max_size in kb
            $config['file_name'] = $_FILES['file']['name'];
       
            //Load upload library
            $this->load->library('upload',$config); 
       
            // File upload
            if($this->upload->do_upload('file')){
              // Get data about the file
              $uploadData = $this->upload->data();
              $this->Images_model->update($file,$idImage);
                redirect('admin/Logos');
            }
        }

    }

	public function delete_logo() {
	$this->load->model('Images_model');        
		$idImage = $this->input->post('id');
        unlink("./dist/portal/images/home/".$this->input->post('name'));            
        $res = $this->Images_model->delete_slide_home($idImage);  
		if($res === true){
			echo 'ok';
		} else {
			echo 'ko';
		}
    }
}
