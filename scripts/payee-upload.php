<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/payee.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadpayeemodal-file'])){

        function convertToText($str){
            $str = trim($str);
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadpayeemodal-file'];
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
                                        'PAYEE NAME', //A
                                        'ADDRESS', //B
                                        'TIN' //C
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

                    $prclass = new payee();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $payeename = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $address = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $tin = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));

                        // Fixed SQL query - added closing quote after $remarks
                        $checkifexistrs = query("select * from payee 
                                                where payee_name='$payeename' and
                                                      address='$address' and
                                                      tin='$tin'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, PayeeName=$payeename, Address=$address, Tin=$tin");

                            $systemlog->logEditedInfo($prclass,$systemID,array($payeename,$address,$tin,$userid,$now,'NULL','NULL'),'PAYEE','Edited Payee Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($payeename,$address,$tin,$userid,$now,'NULL','NULL'));
                        }
                        // Changed validation - removed $remarks!='' requirement
                        else if($payeename!=''&&$address!=''&&$tin!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                            $prclass->insert(array('',$payeename,$address,$tin,$userid,$now,'NULL','NULL'));
                            $systemID = $prclass->getInsertId();

                            $systemlog->logAddedInfo($prclass,array($systemID,$payeename,$address,$tin,$userid,$now,'NULL','NULL'),'PAYEE','New Payee Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, PayeeName=$payeename, Address=$address, Tin=$tin");
                        }
                        else if($payeename!=''||$address!=''||$tin!=''){//INCOMPLETE DATA - ERROR
                            if($payeename==''){
                                array_push($rowerror, "Code is required");
                            }
                            if($address==''){
                                array_push($rowerror, "Company Name is required");
                            }
                            if($tin==''){
                                array_push($rowerror, "Company Street Address is required");
                            }
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: PayeeName=$payeename, Address=$address, Tin=$tin");
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
                    echo "Click <a class='pointer' href='../file-templates/payee-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>