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
class Users extends CI_Controller {

    public function getBirthDay() 
    {
       $this->load->model('Comprador_model');
       $res = $this->Comprador_model->findAll();
       $nowDay = date('d');
       $nowMonth = date('m');
       foreach($res as $row){
           $birthDay = $row->FECHANACIMIENTO;
           $dateInt = strtotime($birthDay);
           $month = date("m", $dateInt);
           $day = date("d", $dateInt);           
           if($day == $nowDay && $month == $nowMonth){
            $sender = 'servicio@lottired.com';
            $senderName = 'Lottired';
            $recipient = $row->EMAIL;
            $usernameSmtp = 'juan.gil.sunbelt@gmail.com';
            $passwordSmtp = 'Temporal2019.';
            $configurationSet = 'ConfigSet';
            $host = 'smtp.gmail.com';
            $port = 25;
            
            $subject = 'Beneficio por tu cumpleaños de LottiRed.Net';
            $name = array('nombre' => $row->NOMBRES.' '.$row->APELLIDOS);
            $bodyHtml = $this->load->view('templates/plantilla_cumpleanios.php', $name, TRUE);  
            
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
                $send = $mail->Send();
            } catch (phpmailerException $e) {
                echo $e->error;
            } catch (Exception $e) {
                echo $e->error;
            }
           }
       }
    }	
}
