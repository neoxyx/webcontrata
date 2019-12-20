<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

class Prize extends REST_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('Comprador_model');
        $this->load->model('SaldosCompradores_model');
        $this->load->model('Premio_model');
    }

    /**
     * change a prize for balance.
     *
     * @return Response
    */
    public function collect_prize_post(){
        $userId = $this->post('userId');
        $valueAward = $this->post('valueAward');
        $balance = $this->SaldosCompradores_model->getBalance($userId) + $valueAward;
        $this->SaldosCompradores_model->setBalance($balance, $userId);
        $this->SaldosCompradores_model->addMove($valueAward, $userId, $balance);
        $this->Comprador_model->cambiarEstadoPremio($this->post('consecutive'), '02');
        $res = array (
            'type' => 'success',
            'text' => 'Su nuevo saldo es de: '.$balance,
            'title' => "Felicidades",
        ); 
        $this->response ($res, REST_Controller :: HTTP_OK);
    }

    /**
     * Check the list of prizes receivable with points.
     *
     * @return Response
    */
    public function index_get(){
        $prizes = $this->Premio_model->get();
        $this->response ($prizes, REST_Controller :: HTTP_OK);
    }

    /**
     * Return the awards of the user started session.
     *
     * @return Response
    */
    public function my_prizes_get($userId){
        $data = $this->Comprador_model->misPremios($userId);
        $this->response ($data, REST_Controller :: HTTP_OK);
    }

    /**
     * Return the awards of the user started session.
     *
     * @return Response
    */
    public function change_status_prize_get($consecutive){
        $data = $this->Comprador_model->cambiarEstadoPremio($consecutive, '04');
        if($data){
            $res = array (
                'type' => 'success',
                'text' => 'Se ha cambiado el estado de su premio a proceso de entrega, este proceso comenzara una vez se reciba el formato diligenciado.',
                'title' => "Proceso de entrega",
            ); 
        }
        else{
            $res = array (
                'type' => 'error',
                'text' => 'Ocurrio un error al cambiar el estado de su solicitud, porfavor descarge nuevamente el formulario, gracias.',
                'title' => "Error",
            ); 
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    }
    
     /**
     * Return the bumpers for the consigments and the changes to the balance.
     *
     * @return Response
    */
    public function bumpers_get(){
        $data = $this->Premio_model->topes();
        $this->response ($data, REST_Controller :: HTTP_OK);
    }

     /**
     * update the state a product.
     *
     * @return Response
    */
    public function change_state_product_put(){
        $state = $this->put('state');
        $idCatalogue = $this->put('idCatalogue');

        $this->Premio_model->changeStateProduct($state, $idCatalogue);
        $this->response ([], REST_Controller :: HTTP_OK);
    } 

     /**
     * add a product.
     *
     * @return Response
    */
    public function add_product_post(){
        $data = json_decode($this->post('data'));

        $res = array(
            'title' => '¡Error!',
            'content' => 'Ocurrio un error a la hora de registrar, intente nuevamente.',
            'type' => 'red'
        );

       if($this->upFile($_FILES['imagen'])){
            try{
                $imagen = $_FILES['imagen']['name'];
                $result = $this->Premio_model->addProduct($data, $imagen);
        
                if($result){
                    $res = array(
                        'title' => '¡Información!',
                        'content' => 'El producto se ha registrado exitosamente.',
                        'type' => 'green'
                    );
                }
            }
            catch(Exeption $e){
                $res = array(
                    'title' => '¡Error!',
                    'content' => 'Ocurrio un error a la hora de registrar, intente nuevamente.',
                    'type' => 'red'
                );
            }
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    } 

    /**
     * update a product.
     *
     * @return Response
    */
    public function update_product_post(){
        $data = json_decode($this->post('data'));
        $imagen = '';

        $res = array(
            'title' => '¡Error!',
            'content' => 'Ocurrio un error a la hora de actualizar, intente nuevamente.',
            'type' => 'red'
        );

        if($_FILES){
            if($this->upFile($_FILES['imagen'])){
                $imagen = $_FILES['imagen']['name'];
            }
        }

        try{
            $result = $this->Premio_model->updateProduct($data, $imagen);
            if($result){
                $res = array(
                    'title' => '¡Información!',
                    'content' => 'El producto se ha actualizado exitosamente.',
                    'type' => 'green'
                );
            }
        }
        catch(Exeption $e){
            $res = array(
                'title' => '¡Error!',
                'content' => 'Ocurrio un error a la hora de actualizar, intente nuevamente.',
                'type' => 'red'
            );
        }
        $this->response ($res, REST_Controller :: HTTP_OK);
    } 

    public function upFile($file){
        if(isset($file)){
            $imagen_tipo = $file['type'];
            $directorio_final = FILE_PATH.$file['name'];
            if(move_uploaded_file($file['tmp_name'], $directorio_final)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
