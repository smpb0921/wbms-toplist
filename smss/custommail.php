<?php  
require '../resources/PHPMailer/PHPMailerAutoload.php';  
include_once("../addonsb/conn - Copy.php");

 

$mail = new PHPMailer();  

try {  
    $mail->isSMTP();  
    $mail->Host = "sg2plcpnl0114.prod.sin2.secureserver.net";
    $mail->SMTPAuth = true; 
    $mail->Username =    "no-reply@tpincorporated.com"; 
    $mail->Password = "#reply.Dont-4wms"; 
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;
 
    $mail->setFrom("no-reply@tpincorporated.com", "WBMS Notifier");

    $mail->addAddress("phbhnlee@gmail.com", "Bhen Ogrimen");   

    $bodyContent = "Manifest Number <strong>MFT00070192</strong> status is now <strong><i>OUT FOR DELIVERY</i></strong>";

    $mail->isHTML(true);                                  
    $mail->Subject = "Custom Email";
    $mail->Body    = "
            
    <div style='height: 100%; width: 100%; text-align: center; margin: 0 auto; font-size: 16pt; font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif'>  
        <div style='box-shadow: 0 1px 1px 0 rgba(0,0,0,0.6); transition: 0.4s; width: 33%; margin: 0 auto;  background: white; text-align: center; padding: 8px;'> 
            <label>
                    Manifest Number <strong>MFT00070192</strong> status is now <strong><i>OUT FOR DELIVERY</i></strong>
            </label> 
        </div> 
    </div>
    
    "; 

    print_r($mail->send() ? 1 : 0);

} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}