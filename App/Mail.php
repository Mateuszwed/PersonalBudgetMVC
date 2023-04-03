<?php
namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mail {

     public static function send($to, $subject, $text, $html) {
        $mail = new PHPMailer(true);
        try {
            //$mail->SMTPDebug = 0;                  
            $mail->isSMTP();       
            $mail->Host       = 'smtp.gmail.com';         
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = '';                
            $mail->Password   = '';                              
            $mail->SMTPSecure = 'ssl';       
            $mail->Port       = 465;
            $mail->setFrom('', '');
            $mail->addAddress($to);     
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;
            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}