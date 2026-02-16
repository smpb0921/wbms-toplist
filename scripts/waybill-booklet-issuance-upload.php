<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/waybill-booklet-issuance.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");

    if(isset($_FILES['uploadwaybillbookletissuancemodal-file'])){

        function getLocationID($code){
            $id = '';
            $rs = query("select * from location where upper(description)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getShipperID($code){
            $id = '';
            $rs = query("select * from shipper where upper(company_name)='$code'");
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

    	$file = $_FILES['uploadwaybillbookletissuancemodal-file'];
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
                                        'ISSUANCE DATE', //A
                                        'VALIDITY DATE', //B
                                        'ISSUED TO', //C
                                        'LOCATION', //D
                                        'SHIPPER', //E
                                        'BOOKLET START SERIES', //F
                                        'BOOKLET END SERIES', //G
                                        'REMARKS', //H
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

                    $prclass = new waybill_booklet_issuance();
                    $systemlog = new system_log();

                    function excelDateToMysql($value){
                        if ($value == '') return '';
                        if (is_numeric($value)) {
                            return date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($value));
                        }
                        return date('Y-m-d', strtotime($value));
                    }


                    for($i=2;$i<=$lastRow;$i++){
                        // $issuancedate = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        // $validitydate = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $issuancedate  = excelDateToMysql($worksheet->getCell('A'.$i)->getValue());
                        $validitydate  = excelDateToMysql($worksheet->getCell('B'.$i)->getValue());
                        $issuedto = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $location = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $locationID = getLocationID($location);
                        $shipper = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $shipperID = getShipperID($shipper);
                        $bookletstartseries = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $bookletendseries = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $remarks = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));

                        // Fixed SQL query - added closing quote after $remarks
                        $checkifexistrs = query("select * from waybill_booklet_issuance 
                                                where issuance_date='$issuancedate' and
                                                      validity_date='$validitydate' and
                                                      issued_to='$issuedto' and
                                                      location_id='$locationID' and 
                                                      shipper_id='$shipperID' and
                                                      booklet_start_series='$bookletstartseries' and
                                                      booklet_end_series='$bookletendseries' and
                                                      remarks='$remarks'");

                        if(getNumRows($checkifexistrs)>0){//EXISTING RATE - UPDATE
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');

                            while($obj=fetch($checkifexistrs)){
                                $systemID = $obj->id;
                            }

                            array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, IssuanceDate=$issuancedate, ValidityDate=$validitydate, Issuedto=$issuedto, LocationID=$locationID, ShipperID='$shipperID' BookletStartSeries=$bookletstartseries, BookletEndSeries=$bookletendseries, Remarks=$remarks");

                            $systemlog->logEditedInfo($prclass,$systemID,array($issuancedate,$validitydate,$issuedto,$locationID,$bookletstartseries,$bookletendseries,$remarks,$userid,$now,'NULL','NULL',0,$shipperID,'NULL'),'WAYBILL BOOKLET ISSUANCE','Edited Waybill Booklet Issuance Info (UPLOAD)',$userid,$now);
                            $prclass->update($systemID,array($issuancedate,$validitydate,$issuedto,$locationID,$bookletstartseries,$bookletendseries,$remarks,$userid,$now,'NULL','NULL',0,$shipperID,'NULL'));
                        }
                        // Changed validation - removed $remarks!='' requirement
                        else if($issuancedate!=''&&$validitydate!=''&&$issuedto!=''&&$location!=''&&$bookletstartseries!=''&&$bookletendseries!=''){//NEW RECORD - INSERT
                            $userid = USERID;
                            $now = date('Y-m-d H:i:s');
                            
                            $prclass->insert(array('',$issuancedate,$validitydate,$issuedto,$locationID,$bookletstartseries,$bookletendseries,$remarks,$userid,$now,'NULL','NULL',0,$shipperID,'NULL'));
                            $systemID = $prclass->getInsertId();

                            $systemlog->logAddedInfo($prclass,array($systemID,$issuancedate,$validitydate,$issuedto,$locationID,$bookletstartseries,$bookletendseries,$remarks,$userid,$now,'NULL','NULL',0,$shipperID,'NULL'),'WAYBILL BOOKLET ISSUANCE','New Waybill Booklet Issuance Added (UPLOAD)',$userid,$now);
                            
                            array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, IssuanceDate=$issuancedate, ValidityDate=$validitydate, Issuedto=$issuedto, LocationID=$locationID, ShipperID='$shipperID', BookletStartSeries=$bookletstartseries, BookletEndSeries=$bookletendseries, Remarks=$remarks");
                        }
                        else if($issuancedate!=''||$validitydate!=''||$issuedto!=''||$location!=''||$shipper!=''||$bookletstartseries!=''||$bookletendseries!=''){//INCOMPLETE DATA - ERROR
                            if($issuancedate==''){
                                array_push($rowerror, "Issuance Date is required");
                            }
                            if($validitydate==''){
                                array_push($rowerror, "Validity Date is required");
                            }
                            if($issuedto==''){
                                array_push($rowerror, "Issued to is required");
                            }
                            if($locationID==''){
                                array_push($rowerror, "Location is required");
                            }
                            if($shipperID==''){
                                array_push($rowerror, "Shipper is required");
                            }
                            if($bookletstartseries==''){
                                array_push($rowerror, "Booklet Start Series is required");
                            }
                            if($bookletendseries==''){
                                array_push($rowerror, "Booklet End Series is required");
                            }
                            
                            $rowtexterror = implode(", ", $rowerror);
                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: IssuanceDate=$issuancedate, ValidityDate=$validitydate, Issuedto=$issuedto, LocationID=$locationID, ShipperID=$shipperID, BookletStartSeries=$bookletstartseries, BookletEndSeries=$bookletendseries, Remarks=$remarks");
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
                    echo "Click <a class='pointer' href='../file-templates/agent-template.xlsx'>here</a> to download file template<br>";
                        
                }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>