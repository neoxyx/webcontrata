<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require APPPATH . 'libraries/Exception.php';
require APPPATH . 'libraries/PHPMailer.php';
require APPPATH . 'libraries/SMTP.php';
require APPPATH . 'libraries/REST_Controller.php';

class EmailBalance extends REST_Controller {
/**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        $sale = $this->post();
        $data["sale"] = $sale["return"];        
        $this->load->model('Ventas_model');
        $htmlTicket = $this->load->view('templates/tiquete', $data, true);  
        $this->load->library('pdfgenerator');
		$rutaGuardado = "./assets/tiquetes/";
        $filename = 'TICKET_'.$sale["return"][0]["numeroFactura"].'.pdf';
		$output = $this->pdfgenerator->generate($htmlTicket, $filename, FALSE, 'A4', 'portrait', $sale["return"][0]["cedulaCliente"]);		
        file_put_contents( $rutaGuardado.$filename, $output);
        
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = $sale["return"][0]["emailCliente"];
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        
		$subject = 'Confirmación compra LottiRed.Net';
		$name = array('nombre' => $sale["return"][0]["nombreCliente"]);
        $bodyHtml = $this->load->view('templates/plantilla_confirm_compra.php', $name, TRUE);  
        
        $mail = new PHPMailer(true);
        try {
            // Specify the SMTP settings.
            $mail->isSMTP();
            $mail->setFrom($sender, $senderName);
            $mail->Username   = $usernameSmtp;
            $mail->Password   = $passwordSmtp;
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->CharSet    = 'utf-8';
            $mail->IsHTML(true);
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';
            $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

            // Specify the message recipients.
            $mail->addAddress($recipient);
            // You can also add CC, BCC, and additional To recipients here.

            // Specify the content of the message.
            $mail->isHTML(true);
            $mail->Subject    = $subject;
			$mail->Body       = $bodyHtml;
			$mail->AddStringAttachment($output, 'ticket.pdf', 'base64', 'application/pdf');			
            $mail->Send();	            
            $this->response('Envío correcto', REST_Controller::HTTP_OK);
        } catch (phpmailerException $e) {
            $this->response('An error occurred. {$e->errorMessage()}', REST_Controller::HTTP_OK);      
        } catch (Exception $e) {
            $this->response('Email not sent. {$e->ErrorInfo}', REST_Controller::HTTP_OK);           
        }
              
    }
}