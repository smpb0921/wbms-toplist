<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/district-city-zip.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['uploaddistrictcityzipmodal-file'])){

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


        function convertToText($str){
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploaddistrictcityzipmodal-file'];
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
                                        'DISTRICT/BARANGAY', //A
                                        'CITY/MAJOR AREA', //B
                                        'ZIP CODE', //C
                                        'PORT CODE',
                                        'ODA FLAG', //E
                                        'ODA RATE' //F
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

                    $districtcityzipclass = new district_city_zipcode();
                    $systemlog = new system_log();


                    for($i=2;$i<=$lastRow;$i++){
                        $district = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $city = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $zipcode = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $portcode = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $portID = getPortID($portcode);


                        $odaflag = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $odarate = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));

                        if($odaflag=='YES'){
                            $odaflag = 1;
                            if($odarate>=0){
                                $odarate = $odarate;
                            }
                            else{
                                $odarate = 0;
                            }
                        }
                        else{
                            $odaflag = 0;
                            $odarate = 0;
                        }

                        

                        if($district!=''&&$city!=''&&$zipcode!=''&&$portcode!=''){
                            if($portID!=''){
                                $checkifexistrs = query("select * from district_city_zipcode 
                                                         where district_barangay='$district' and 
                                                               city='$city' and
                                                               zip_code='$zipcode' and 
                                                               origin_destination_port_id='$portID'
                                                         limit 1");

                                if(getNumRows($checkifexistrs)>0){
                                    array_push($txnrowexist, "Details: Line $i - District=$district, City=$city, Zip Code=$zipcode, Port Code=$portcode, ODA Flag=$odaflag, ODA Rate=$odarate");

                                    while($obj=fetch($checkifexistrs)){
                                        $systemID = $obj->id;
                                    }

                                    $userid = USERID;
                                    $now = date('Y-m-d H:i:s');

                                    $systemlog->logEditedInfo($districtcityzipclass,$systemID,array($systemID,$district,$city,$zipcode,$portcode,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate),'District/City/ZipCode','Edited District/City/ZipCode - Upload',$userid,$now);
                                    $districtcityzipclass->update($systemID,array($district,$city,$zipcode,$portID,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate));
                                }
                                else{
                                    array_push($txnwithouterror, "Details: Line $i - District=$district, City=$city, Zip Code=$zipcode, Port Code=$portcode, ODA Flag=$odaflag, ODA Rate=$odarate");

                                    $userid = USERID;
                                    $now = date('Y-m-d H:i:s');


                                    $districtcityzipclass->insert(array('',$district,$city,$zipcode,$portID,$userid,$now,'NULL','NULL',$odaflag,$odarate));
                                    $systemID = $districtcityzipclass->getInsertId();
                                    $systemlog->logAddedInfo($districtcityzipclass,array($systemID,$district,$city,$zipcode,$portcode,$userid,$now,'NULL','NULL',$odaflag,$odarate),'District/City/ZipCode','New District/City/ZipCode Added - Upload',$userid,$now);
                                }

                            }
                            else{
                                array_push($txnwithrowerror, "Error: Port Code not in Database | Details: Line $i - District=$district, City=$city, Zip Code=$zipcode, Port Code=$portcode, ODA Flag=$odaflag, ODA Rate=$odarate");
                            }
                        }
                        else{
                            array_push($txnwithrowerror, "Error: Incomplete Details | Details: Line $i - District=$district, City=$city, Zip Code=$zipcode, Port Code=$portcode, ODA Flag=$odaflag, ODA Rate=$odarate");
                        }


                    }

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

                     echo "<table border='1px' cellspacing='0px'>
                            <thead>
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
                     echo   "</tbody></table><br>";


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/districtcityzip-transaction-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>