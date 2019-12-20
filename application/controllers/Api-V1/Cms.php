<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Cms extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Images_model');
        $this->load->model('Iconos_model');
        $this->load->model('Redes_model');
        $this->load->model('Textos_model');
    }

    /**
     * returns the images to carrusel.
     *
     * @return Response
    */
    public function logos_get(){  
        $this->response (array ('logos'=>$this->Images_model->get(2)), REST_Controller :: HTTP_OK);
    }

    /**
     * returns the images logos.
     *
     * @return Response
    */
    public function slider_get(){  
        $this->response (array ('sliders'=>$this->Images_model->get(1)), REST_Controller :: HTTP_OK);
    }

    /**
     * returns text secc 6.
     *
     * @return Response
    */
    public function section6_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_texto_secc6()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns text secc 7-1.
     *
     * @return Response
    */
    public function section71_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_texto_secc7_pos1()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns text secc 7-2.
     *
     * @return Response
    */
    public function section72_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_texto_secc7_pos2()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns text secc 7-3.
     *
     * @return Response
    */
    public function section73_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_texto_secc7_pos3()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns text footer link 1.
     *
     * @return Response
    */
    public function link1_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_textos_links1_footer()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns text footer link 2.
     *
     * @return Response
    */
    public function link2_get(){  
        $this->response (array ('text'=>$this->Textos_model->get_textos_links2_footer()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns tel.
     *
     * @return Response
    */
    public function tel_get(){  
        $this->response (array ('tel'=>$this->Textos_model->get_texto_tel()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns mail.
     *
     * @return Response
    */
    public function mail_get(){  
        $this->response (array ('mail'=>$this->Textos_model->get_texto_email()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns address.
     *
     * @return Response
    */
    public function address_get(){  
        $this->response (array ('mail'=>$this->Textos_model->get_texto_dir()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns icons social net.
     *
     * @return Response
    */
    public function iconsSocial_get(){  
        $this->response (array ('icons'=>$this->Redes_model->get_redes_header()), REST_Controller :: HTTP_OK);
    }
    /**
     * returns icons social net footer.
     *
     * @return Response
    */
    public function iconsSocialFooter_get(){  
        $this->response (array ('icons'=>$this->Redes_model->get_redes_footer()), REST_Controller :: HTTP_OK);
    }
    
}
