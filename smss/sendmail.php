<?php  
require '../resources/PHPMailer/PHPMailerAutoload.php';  
include_once("../addonsb/conn - Copy.php");

 

$mail = new PHPMailer();  

try {  
    $mail->isSMTP();  
    $mail->Host = "tpincorporated.com";
    $mail->Port = 465;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth = true; 
    $mail->Username = "no-reply@tpincorporated.com"; 
    $mail->Password = "#reply.Dont-4wms"; 
 
    $mail->setFrom("no-reply@tpincorporated.com", "WBMS Notifier");

    foreach (explode(",",$_POST['To']) as $key => $value) {
        $Address = explode("|",$value);
        $mail->addAddress($Address[0], count($Address)>1 ? $Address[1] : $Address[0]);  
    }
    
    if(isset($_POST['Cc'])) {
        foreach (explode(",",$_POST['Cc']) as $key => $value) {
            $Address = explode("|",$value);
            $mail->addCC($Address[0], count($Address)>1 ? $Address[1] : $Address[0]);  
        }
    }

    $bodyContent = $_POST["Body"];

    $mail->isHTML(true);                                  
    $mail->Subject = $_POST["Subject"];
    $mail->Body =  $bodyContent;

    print_r($mail->send() ? 1 : 0);

} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}