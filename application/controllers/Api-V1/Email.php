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

class Email extends REST_Controller {
      
    /**
     * Insert Data from this method.
     *
     * @return Response
    */
    public function index_post()
    {
        $cart = $this->post('data');
        $sale = $this->post('sale');
        $data["details"] = $cart["details"];
        $data["sale"] = $sale["return"]; 
        //Datos configuración email
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = 'jhon.valdes@sunbeltfactory.com';//$sale["return"][0]["emailCliente"];
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        //
        $this->load->model('Ventas_model');
        for($i=0; $i<count($sale["return"]); $i++) {
            $args = array(
                'CODIGO_BARRAS' => $sale["return"][$i]["codigoBarras"],
                'FECHA_JUEGA' => $sale["return"][$i]["fechaJuega"]);
            $this->Ventas_model->updateDetailsSale($cart["details"][$i]["ID"],$args
            );
            if(intval($sale["return"][$i]["fracciones"])==1){
                $sale=array(
                    'email' => $sale["return"][$i]["emailCliente"],
                    'invoice' => $sale["return"][$i]["numeroFactura"],                    
                    'name' => $cart['name'],
                    'lottery' => $sale["return"][$i]["loteria"],
                    'lotteryname' => $sale["return"][$i]["nombreLoteria"],
                    'serie' => $sale["return"][$i]["serie"],
                    'number' => $sale["return"][$i]["numero"],
                    'draw' => $sale["return"][$i]["sorteo"],
                    'fractions' => intval($sale["return"][$i]["fracciones"]),
                    'fractionsticket' => $sale["return"][$i]["numeroFraccionesBilleteLoteria"],
                    'barcode' => $sale["return"][$i]["codigoBarras"],
                    'datesale' => $sale["return"][$i]["fechaHoraVenta"],
                    'dateplay' => $sale["return"][$i]["fechaJuega"],
                    'letters' => $sale["return"][$i]["numeroEnLetras"],
                    'vrfraction' => $sale["return"][$i]["valorFraccion"],
                    'jackpot' => $sale["return"][$i]["valorPremioMayor"],
                    'total' => $sale["return"][$i]["totalVenta"]
                );
            }
            $this->generateTicketFraction($sale);
        }  
        
        $htmlTicket = $this->load->view('templates/tiquete', $data, true);  
        $this->load->library('pdfgenerator');
		$rutaGuardado = "./assets/tiquetes/";
        $filename = 'TICKET_'.$cart["reference"].'.pdf';
		$output = $this->pdfgenerator->generate($htmlTicket, $filename, FALSE, 'A4', 'portrait', $sale["return"][0]["cedulaCliente"]);		
        file_put_contents( $rutaGuardado.$filename, $output);            
        
		$subject = 'Confirmación compra LottiRed.Net';
		$name = array('nombre' => $cart['name']);
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
            $this->response('Email not sent. {$mail->ErrorInfo}', REST_Controller::HTTP_OK);           
        }
              
    }

    public function generateTicketFraction($sale)
    {
        //Datos configuración email
        $sender = 'servicio@lottired.com';
        $senderName = 'Lottired';
        $recipient = 'jhon.valdes@sunbeltfactory.com';//$sale['email'];
        $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
        $passwordSmtp = 'Temporal2019.';
        $configurationSet = 'ConfigSet';
        $host = 'smtp.gmail.com';
        $port = 25;
        $data['sale'] = $sale;

        $htmlTicket = $this->load->view('templates/tiquete_fraccionado', $data, true);  
        $this->load->library('pdfgenerator');
		$rutaGuardado = "./assets/tiquetes/";
        $filename = 'TICKET_'.$sale['invoice'].'.pdf';
		$output = $this->pdfgenerator->generate($htmlTicket, $filename, FALSE, 'A4', 'portrait', $sale["return"][0]["cedulaCliente"]);		
        file_put_contents( $rutaGuardado.$filename, $output);            
        
		$subject = 'Confirmación compra LottiRed.Net';
		$name = array('nombre' => $sale['name']);
        $bodyHtml = $this->load->view('templates/plantilla_confirm_compra.php', $name, TRUE);  
        
        $mail = new PHPMailer(true);        
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
    }
    
}