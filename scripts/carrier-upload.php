<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/carrier.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadcarriermodal-file'])){

        function convertToText($str){
            $str = trim($str);
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadcarriermodal-file'];
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
                                        'DESCRIPTION' //B
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

                    $prclass = new carrier();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $code = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $description = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));

                        // Fixed SQL query - added closing quote after $remarks
                        $checkifexistrs = query("select * from carrier 
                                                where code='$code' and
                                                      description='$description'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, Description=$description");

                            $systemlog->logEditedInfo($prclass,$systemID,array($code,$description,$userid,$now,'NULL','NULL'),'CARRIER','Edited Carrier Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($code,$description,$userid,$now,'NULL','NULL'));
                        }
                        // Changed validation - removed $remarks!='' requirement
                        else if($code!=''&&$description!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                            $prclass->insert(array('',$code,$description,$userid,$now,'NULL','NULL'));
                            $systemID = $prclass->getInsertId();

                            $systemlog->logAddedInfo($prclass,array($systemID,$code,$description,$userid,$now,'NULL','NULL'),'CARRIER','New Carrier Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, Code=$code, Description=$description");
                        }
                        else if($code!=''||$description!=''){//INCOMPLETE DATA - ERROR
                            if($code==''){
                                array_push($rowerror, "Code is required");
                            }
                            if($description==''){
                                array_push($rowerror, "Description is required");
                            }
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: Code=$code, Description=$description");
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
                    echo "Click <a class='pointer' href='../file-templates/carrier-template.xlsx'>here</a> to download file template<br>";
                }
    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}
	}
?>