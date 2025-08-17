<?php

//echo !extension_loaded('openssl')?"Not Available":"Available";
require '../resources/PHPMailer/PHPMailerAutoload.php';
//include('../config/db-function.php');

class Email{
			private $host;
			private $SMTPAuth;
			private $username;
			private $password;
			private $port;
			private $emailfrom;
			private $fromname;
			private $tlsencryption;

			function Email($host,$SMTPAuth,$username,$password,$port,$emailfrom,$fromname,$encryption,$activeflag){
				$this->host=$host;
				$this->SMTPAuth = $SMTPAuth;
				$this->username = $username;
				$this->password = $password;
				$this->port = $port;

				$this->emailfrom = $emailfrom;
				$this->fromname = $fromname;
				$this->tlsencryption=$encryption;
				$this->activeflag = $activeflag;
			}


			function sendNotification($emailto,$toname,$subject,$body,$altbody,$ccrequestor,$attachments){
				if($this->activeflag==1){
					$mail = new PHPMailer;

					$mail->SMTPDebug = 1;                               // Enable verbose debug output
					//$mail->SMTPKeepAlive = true;
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = $this->host;  // Specify main and backup SMTP servers
					$mail->SMTPAuth = $this->SMTPAuth;                               // Enable SMTP authentication
					$mail->Username = $this->username;                 // SMTP username
					$mail->Password = $this->password;                           // SMTP password
					$mail->SMTPSecure = $this->tlsencryption;                           // Enable TLS encryption, `ssl` also accepted
					$mail->Port = $this->port;                                    // TCP port to connect to

					$mail->From = $this->emailfrom;
					$mail->FromName = $this->fromname;
					//$mail->addAddress($emailto, $toname);     // Add a recipient
					//$mail->addAddress('ellen@example.com');               // Name is optional
					//$mail->addReplyTo('info@example.com', 'Information');

					for($i=0;$i<count($emailto);$i++){
						@$mail->addAddress($emailto[$i]);

					}

					for($i=0;$i<count($ccrequestor);$i++){
						@$mail->addCC($ccrequestor[$i]);
					}

					for($i=0;$i<count($attachments);$i++){
						@$mail->addAttachment($attachments[$i]);
					}
					

					//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
					//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
					$mail->isHTML(true);                                  // Set email format to HTML

					$mail->Subject = $subject;
					$mail->Body    = $body;
					$mail->AltBody = $altbody;

					if(!$mail->send()) {
						
					    //echo 'Message could not be sent.';
					    //echo '<br>Mailer Error: ' . $mail->ErrorInfo;
					    
					    //return $mail->ErrorInfo;
					} else {
					    echo 'Message has been sent';
					    return true;
					}
				}
			}
}



$query = "select * from email_configuration where id='1'";
$rs = mysql_query($query);
$SMTPAuth = true;
$host = '';
$port = '';
$uname='';
$pword='';
$from = '';
$sender = '';
$encryption = '';
$emailnotifyactive = '';
//echo mysql_error();
while($obj=mysql_fetch_object($rs)){
	$host = $obj->host;
	$uname = $obj->username;
	$pword = $obj->password;
	$port = $obj->port;
	$from = $obj->email_sent_from;
	$sender = $obj->email_sender;
	$emailnotifyactive = $obj->active_flag;
	//$encryption = $obj->encryption;
}


								
$emailClass = new Email($host,$SMTPAuth,$uname,$pword,$port,$from,$sender,$encryption,$emailnotifyactive);