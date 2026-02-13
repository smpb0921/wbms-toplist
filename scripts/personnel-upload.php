<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/personnel.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadpersonnelmodal-file'])){

        function getPositionID($code){
            $id = '';
            $rs = query("select * from personnel_position where upper(description)='$code'");
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

    	$file = $_FILES['uploadpersonnelmodal-file'];
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
                                        'FIRST NAME', //A
                                        'LAST NAME', //B
                                        'CONTACT NUMBER', //C
                                        'POSITION', //D
                                        'DRIVER FOR' //E
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

                    $prclass = new personnel();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $firstname = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $lastname = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $contactnumber = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $position = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $positionID = getPositionID($position);
                        $driverfor = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));

                        $checkifexistrs = query("select * from personnel 
                                                where first_name='$firstname' and
                                                    last_name='$lastname' and
                                                    position='$positionID' and
                                                    type='$driverfor' and
                                                    contact_number='$contactnumber'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, FirstName=$firstname, LastName=$lastname, ContactNumber=$contactnumber, Position=$position, DriverFor=$driverfor");

                            
                            $systemlog->logEditedInfo($prclass,$systemID,array($firstname,$lastname,$positionID,$driverfor,$contactnumber,$userid,$now,'NULL','NULL',1),'PERSONNEL','Edited Personnel Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($firstname,$lastname,$position,$driverfor,$contactnumber,$userid,$now,'NULL','NULL',1));
                        }
                        else if($firstname!=''&&$lastname!=''&&$positionID!=''&&$driverfor!=''&&$contactnumber!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                          
                            $prclass->insert(array('',$firstname,$lastname,$position,$driverfor,$contactnumber,$userid,$now,'NULL','NULL',1));
                            $systemID = $prclass->getInsertId();
                            
                            $systemlog->logAddedInfo($prclass,array($systemID,$firstname,$lastname,$positionID,$driverfor,$contactnumber,$userid,$now,'NULL','NULL',1),'Personnel','New Personnel Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, FirstName=$firstname, LastName=$lastname, Position=$position, DriverFor=$driverfor, ContactNumber=$contactnumber");
                        }
                        else if($firstname!=''||$lastname!=''||$position!=''||$driverfor!=''||$contactnumber!=''){//INCOMPLETE DATA - ERROR
                            if($firstname==''){
                                array_push($rowerror, "First Name is required");
                            }
                            if($lastname==''){
                                array_push($rowerror, "Last Name is required");
                            }
                            if($contactnumber==''){
                                array_push($rowerror, "Contact Number is required");
                            }
                            if($positionID==''){
                                array_push($rowerror, "Position is required");
                            }
                            if($driverfor==''){
                                array_push($rowerror, "Driver For is required");
                            }
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: FirstName=$firstname, LastName=$lastname, Position=$position, DriverFor=$driverfor, ContactNumber=$contactnumber");
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
                    echo "Click <a class='pointer' href='../file-templates/personnel-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>