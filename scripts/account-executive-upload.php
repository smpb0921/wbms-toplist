<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/account-executive.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadaccountexecutivemodal-file'])){
        

        function getShipmentTypeID($code){
            $id = '';
            $rs = query("select * from shipment_type where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                        $id=$obj->id;
                }
            }
            return $id;
        }

        function getShipmentModeID($code){
            $id = '';
            $rs = query("select * from shipment_mode where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                        $id=$obj->id;
                }
            }
            return $id;
        }

        function getModeofTransportID($code){
            $id = '';
            $rs = query("select * from mode_of_transport where upper(description)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                        $id=$obj->id;
                }
            }
            return $id;
        }

       function getPortID($code){
            $id = '';
            $rs = query("select * from origin_destination_port where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getZoneID($code){
            $id = '';
            $rs = query("select * from zone where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getPouchSizeID($code){
            $id = '';
            $rs = query("select * from pouch_size where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

         function getTplID($code){
            $id = '';
            $rs = query("select * from third_party_logistic where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }


        function convertToText($str){
            $str = trim($str);
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadaccountexecutivemodal-file'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
        //$ftype = $file['type'];
    	$ftype = strrchr($filename, '.');

    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

    			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
    			@$excelObj = $excelReader->load($tmp);



                $worksheet = $excelObj->getActiveSheet();
                $lastRow = $worksheet->getHighestRow();
                $lastCol = $worksheet->getHighestColumn();

                $headerColumns = array(
                                        'CODE', //A
                                        'NAME', //B
                                        'EMAIL ADDRESS', //C
                                        'MOBILE NUMBER', //D
                                        'USERNAME', //E
                                        'PASSWORD' //F
                                       );
                $checkHeaderInfo = true;
                $col = "A";
                for($i=0;$i<count($headerColumns);$i++){
                        //echo strtoupper(trim($worksheet->getCell($col.'1')->getValue()))."   ==   ".strtoupper(trim($headerColumns[$i]))."<br>";
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){

                            $checkHeaderInfo = false;
                        }
                        $col++;
                }

                if($checkHeaderInfo==true){
                    $txnwithrowerror = array();
                    $txnwithouterror = array();
                    $txnrowexist = array();
                    $rowerror = array();
                    $transactedIDs = array();

                    $prclass = new account_executive();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $code = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $name = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $emailaddress = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $mobilenumber = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $username = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $password = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));

                        $checkifexistrs = query("select * from account_executive 
                                                where code='$code' and
                                                    name='$name' and
                                                    email_address='$emailaddress' and
                                                    mobile_number='$mobilenumber' and 
                                                    username='$username' and
                                                    password='$password'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, Name=$name, EmailAddress=$emailaddress, MobileNumber=$mobilenumber, Username=$username, Password=$password");

                            $systemlog->logEditedInfo($prclass,$systemID,array('',$code,$name,$emailaddress,$mobilenumber,$username,$password,$userid,$now,'NULL','NULL'),'ACCOUNT EXECUTIVE','Edited Account Executive Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($code,$name,$emailaddress,$mobilenumber,$username,$password,$userid,$now,'NULL','NULL'));
                        }
                        else if($code!=''&&$name!=''&&$emailaddress!=''&&$mobilenumber!=''&&$username!=''&&$password!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                            $prclass->insert(array('',$code,$name,$emailaddress,$mobilenumber,$username,$password,$userid,$now,'NULL','NULL'));
                            $systemID = $prclass->getInsertId();
                            
                            $systemlog->logAddedInfo($prclass,array($systemID,$code,$name,$emailaddress,$mobilenumber,$username,$password,$userid,$now,'NULL','NULL'),'ACCOUNT EXECUTIVE','New Account Executive Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, Name=$name, EmailAddress=$emailaddress, MobileNumber=$mobilenumber, Username=$username, Password=$password");
                        }
                        else if($code!=''||$name!=''||$emailaddress!=''||$mobilenumber!=''||$username!=''||$password!=''){//INCOMPLETE DATA - ERROR
                            if($code==''){
                                array_push($rowerror, "Code is required");
                            }
                            if($name==''){
                                array_push($rowerror, "Name is required");
                            }
                            if($emailaddress==''){
                                array_push($rowerror, "Email Address is required");
                            }
                            if($mobilenumber==''){
                                array_push($rowerror, "Mobile Number is required");
                            }
                            if($username==''){
                                array_push($rowerror, "Username is required");
                            }
                            if($password==''){
                                array_push($rowerror, "Password is required");
                            }
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: code=$code, name=$name, emailaddress='$emailaddress', mobilenumber=$mobilenumber, username=$username, password=$password");
                            $rowerror = array();
                        }
                    }



                    echo "<table border='1px' cellspacing='0px' style='background-color:#ffdede'>
                            <thead >
                                <tr>    
                                    <th colspan='2'>WITH ERROR (NOT UPLOADED)</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnwithrowerror);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnwithrowerror[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                    echo   "</tbody></table><br><br>";

                    echo "<table border='1px' cellspacing='0px'>
                            <thead>
                                <tr>    
                                    <th colspan='2'>ADDED</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnwithouterror);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnwithouterror[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                     echo   "</tbody></table><br>";

                     echo "<table border='1px' cellspacing='0px'>
                            <thead>
                                <tr>    
                                    <th colspan='2'>UPDATED</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>";
                                $line = 1;
                                for($x=0;$x<count($txnrowexist);$x++){
                                    echo "<tr>
                                               <td>$line</td>
                                               <td>".utf8_encode($txnrowexist[$x])."</td>
                                          </tr>";
                                    $line++;
                                }
                     echo   "</tbody></table><br>";

                     


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer' href='../file-templates/published-rate-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>