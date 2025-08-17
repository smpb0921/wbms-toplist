 <?php
    include('../config/connection.php');
    include('../config/checklogin.php');
    include('../config/checkurlaccess.php');
    include('../config/functions.php');
    include("../classes/company-information.class.php");
    include("../classes/email-configuration.class.php");
    include("../classes/system-log.class.php");

    if(isset($_POST['emailConfiguration'])){
    	if($_POST['emailConfiguration']=='Bdm2Donoi#20slOPD90&$rpodk49d94po3'){
    		$host = escapeString($_POST['host']);
    		$port = escapeString($_POST['port']);
    		$encryption = escapeString($_POST['encryption']);
    		$uname = escapeString($_POST['username']);
    		$pword = escapeString($_POST['password']);
    		$sender = escapeString($_POST['sender']);
    		$emailfrom = escapeString($_POST['emailfrom']);
            $flag = escapeString($_POST['flag']);
            $now = date('Y-m-d H:i:s');

            $emailconf = new email_configuration();
            $systemlog = new System_log();

            $systemlog->logEditedInfo($emailconf,1,array('',$host,$uname,$pword,$port,$emailfrom,$sender,$encryption,$flag),'CONFIGURATION','Edited Email Configuration',$userid,$now);
            $emailconf->update(1,array($host,$uname,$pword,$port,$emailfrom,$sender,$encryption,$flag));
           

            echo "success";

    		/*$query = "update email_configuration set host='$host', username='$uname', password='$pword', port='$port', email_sent_from='$emailfrom', email_sender='$sender', encryption='$encryption' where id='1'";
    		$rs = query($query);
    		if($rs){
    			echo "success";
    		}*/

    	}
    }

    if(isset($_POST['companyInformation'])){
        if($_POST['companyInformation']=='Bdm#r@1podk49d94po3sfr$@1qdsdf'){
            $name = escapeString($_POST['name']);
            $tin = escapeString($_POST['tin']);
            $addr = escapeString($_POST['addr']);
            $city = escapeString($_POST['city']);
            $state = escapeString($_POST['state']);
            $postal = escapeString($_POST['postal']);
            $country = escapeString($_POST['country']);
            $primary = escapeString($_POST['primary']);
            $secondary = escapeString($_POST['secondary']);
            $now = date('Y-m-d H:i:s');

            $companyinfo = new company_information();
            $systemlog = new System_log();

            $systemlog->logEditedInfo($companyinfo,1,array('',$name,$addr,$city,$state,$country,$postal,$primary,$secondary,$tin),'CONFIGURATION','Edited Company Information',$userid,$now);
            $companyinfo->update(1,array($name,$addr,$city,$state,$country,$postal,$primary,$secondary,$tin));
           

            echo "success";

        }
    }

  






?>