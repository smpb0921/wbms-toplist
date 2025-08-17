<?php
		include('../config/connection.php');
		include('email.class.php');

		$emailClass->sendNotification(array("sbonite21@gmail.com","wbms@tpincorporated.com"),'Kaye','Test Email','Sample text here','Sample text here',array("sbonite@tpincorporated.com"),array());


?>