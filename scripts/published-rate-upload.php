<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/published-rate.class.php");
    include("../classes/published-rate-freight-charge.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['uploadpublishedratemodal-file'])){

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

    	$file = $_FILES['uploadpublishedratemodal-file'];
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
                                        'SHIPMENT TYPE', //A
                                        'SHIPMENT MODE', //B
                                        'MODE OF TRANSPORT', //C
                                        '3PL', //D
                                        'TYPE', //E
                                        'ORIGIN CODE', //F
                                        'ZONE CODE',//G
                                        'POUCHSIZE CODE', //H
                                        'FIXED RATE FLAG', //I
                                        'FIXED RATE AMOUNT', //J
                                        'KG FROM', //K
                                        'KG TO', //L
                                        'FREIGHT CHARGE', //M
                                        'EXCESS FREIGHT CHARGE' //N
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

                    $prclass = new published_rate();
                    $prfcclass = new published_rate_freight_charge();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $shipmenttype = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $shipmenttypeID = getShipmentTypeID($shipmenttype);
                        $shipmentmode = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $shipmentmodeID = getShipmentModeID($shipmentmode);
                        $modeoftransport = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $modeoftransportID = getModeofTransportID($modeoftransport);
                        $tpl = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $tplID = getTplID($tpl);
                        $type = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $origincode = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $originID = getPortID($origincode);
                        $zonecode = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $zoneID = getZoneID($zonecode);
                        $pouchsize = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                        $pouchsizeID = getPouchSizeID($pouchsize);

                        $fixedflag = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                        $fixedflagbool = trim($fixedflag)=='YES'?1:0;
                        $fixedamount = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                        $fixedamount = $fixedflagbool==0?0:$fixedamount;
                        $fromkg = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                        $tokg = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                        $freightcharge = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                        $excessfreightcharge = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                        $excessfreightcharge = $excessfreightcharge>=0?$excessfreightcharge:0;


                        //echo $worksheet->getCell('G'.$i)->getValue()." $i <----<br>";
                        

                        if($shipmenttypeID!=''&&$shipmentmodeID!=''&&$modeoftransportID!=''&&$tplID!=''&&($type=='DOCUMENT'||$type=='PARCEL')&&$originID!=''&&$zoneID!=''&&$pouchsizeID!=''&&(
                                        ($fixedflag=='NO' &&(
                                                              (
                                                                ($fromkg<=$tokg&&$fromkg>0&&$tokg>0)||
                                                                ($fromkg>0&&$tokg==0)
                                                               )&&
                                                               $freightcharge>0
                                                            )
                                        )
                                        ||
                                        ($fixedamount>0&&$fixedflag=='YES')
                                       )
                        ){
                                
                                    //$pouchsizecondition='';
                                    //if($type=='DOCUMENT'){
                                        $pouchsizecondition = " and pouch_size_id='$pouchsizeID'";
                                    //}
                                    //else{
                                    //    $pouchsizeID = 'NULL';
                                    //}

                                    //if((($fromkg<=$tokg&&$fromkg>0&&$tokg>0)||($fromkg>0&&$tokg==0))&&$freightcharge>0){

                                            if($tokg==0){
                                                $tokg = $fromkg*10000;
                                            }
                               
                                            $checkifexistrs = query("select * from published_rate 
                                                                     where shipment_type_id='$shipmenttypeID' and
                                                                           shipment_mode_id='$shipmentmodeID' and
                                                                           mode_of_transport_id='$modeoftransportID' and
                                                                           third_party_logistic_id='$tplID' and 
                                                                           origin_id='$originID' and
                                                                           waybill_type='$type' and 
                                                                           zone_id='$zoneID' $pouchsizecondition");

                                            if(getNumRows($checkifexistrs)>0){//EXISTING RATE

                                                
                                                $userid = USERID;
                                                $now = date('Y-m-d H:i:s');

                                                while($obj=fetch($checkifexistrs)){
                                                    $systemID = $obj->id;
                                                }

                                                 array_push($txnrowexist, "<b>Details</b>: Line $i - SystemID=$systemID, ShipmentType=$shipmenttype, ShipmentMode=$shipmentmode, ModeofTransport=$modeoftransport, 3PL=$tpl, Type=$type, Origin=$origincode, Zone=$zonecode, PouchSize=$pouchsize, FixedRateFlag=$fixedflag, FixedAmount=$fixedamount, FromKg=$fromkg, ToKg=$tokg, FreightCharge=$freightcharge, ExcessCharge=$excessfreightcharge");

                                                 $systemlog->logEditedInfo($prclass,$systemID,array('',$originID,'NULL',$modeoftransportID,'NULL',$fixedflagbool,0,$fixedamount,0,0,0,0,$userid,$now,'NULL','NULL',0,0,$type,$pouchsizeID,0,0,0,'NULL',$zoneID,$tplID,$shipmenttypeID,$shipmentmodeID),'PUBLISHED RATE','Edited Published Rate Info (UPLOAD)',$userid,$now);
                                                 $prclass->update($systemID,array($originID,'NULL',$modeoftransportID,'NULL',$fixedflagbool,0,$fixedamount,0,0,0,0,$userid,$now,'NULL','NULL',0,0,$type,$pouchsizeID,0,0,0,'NULL',$zoneID,$tplID,$shipmenttypeID,$shipmentmodeID));


                                                 if($fixedflagbool==1){
                                                    query("delete from published_rate_freight_charge where published_rate_id='$systemID'");
                                                 }
                                                 else{
                                                    if(!in_array($systemID, $transactedIDs)){
                                                        array_push($transactedIDs, $systemID);
                                                        query("delete from published_rate_freight_charge where published_rate_id='$systemID'");
                                                    }


                                                    $userid = USERID;
                                                    $now = date('Y-m-d H:i:s');
                                                    $prfcclass->insert(array('',$systemID,$fromkg,$tokg,$freightcharge,$excessfreightcharge,$now,$userid,'NULL','NULL'));
                                                    $prfreightchargerowid = $prfcclass->getInsertId();
                                                    $systemlog->logAddedInfo($prfcclass,array($prfreightchargerowid,$systemID,$fromkg,$tokg,$freightcharge,$excessfreightcharge,$now,$userid,'NULL','NULL'),'PUBLISHED RATE - FREIGHT CHARGE','New Published Rate - Freight Charge (Upload)',$userid,$now);


                                                 }

                                                
                                            }
                                            else{//NEW RATE
                                                $userid = USERID;
                                                $now = date('Y-m-d H:i:s');
                                                $prclass->insert(array('',$originID,'NULL',$modeoftransportID,'NULL',$fixedflagbool,0,$fixedamount,0,0,0,0,$userid,$now,'NULL','NULL',0,0,$type,$pouchsizeID,0,0,0,'NULL',$zoneID,$tplID,$shipmenttypeID,$shipmentmodeID));
                                                $systemID = $prclass->getInsertId();
                                                $systemlog->logAddedInfo($prclass,array($systemID,$originID,'NULL',$modeoftransportID,'NULL',$fixedflagbool,0,$fixedamount,0,0,0,0,$userid,$now,'NULL','NULL',0,0,$type,$pouchsizeID,0,0,0,'NULL',$zoneID,$tplID,$shipmenttypeID,$shipmentmodeID),'PUBLISHED RATE','New Published Rate Added (UPLOAD)',$userid,$now);

                                                array_push($txnwithouterror, "<b>Details</b>: Line $i - SystemID=$systemID, ShipmentType=$shipmenttype, ShipmentMode=$shipmentmode, ModeofTransport=$modeoftransport, 3PL=$tpl, Type=$type, Origin=$origincode, Zone=$zonecode, PouchSize=$pouchsize, FixedRateFlag=$fixedflag, FixedAmount=$fixedamount, FromKg=$fromkg, ToKg=$tokg, FreightCharge=$freightcharge, ExcessCharge=$excessfreightcharge");
                                                
                                                array_push($transactedIDs, $systemID);

                                                if($fixedflagbool==0){
                                                    $userid = USERID;
                                                    $now = date('Y-m-d H:i:s');
                                                    $prfcclass->insert(array('',$systemID,$fromkg,$tokg,$freightcharge,$excessfreightcharge,$now,$userid,'NULL','NULL'));
                                                    $prfreightchargerowid = $prfcclass->getInsertId();
                                                    $systemlog->logAddedInfo($prfcclass,array($prfreightchargerowid,$systemID,$fromkg,$tokg,$freightcharge,$excessfreightcharge,$now,$userid,'NULL','NULL'),'PUBLISHED RATE - FREIGHT CHARGE','New Published Rate - Freight Charge (Upload)',$userid,$now);
                                                }
                                            }
                                    //}
                                    
                             
                                /*if($originID!=''){
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

                                        $systemlog->logEditedInfo($publishedrateclass,$systemID,array($systemID,$district,$city,$zipcode,$portcode,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate),'District/City/ZipCode','Edited District/City/ZipCode - Upload',$userid,$now);
                                        $publishedrateclass->update($systemID,array($district,$city,$zipcode,$portID,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate));
                                    }
                                    else{
                                        array_push($txnwithouterror, "Details: Line $i - District=$district, City=$city, Zip Code=$zipcode, Port Code=$portcode, ODA Flag=$odaflag, ODA Rate=$odarate");

                                        $userid = USERID;
                                        $now = date('Y-m-d H:i:s');


                                        $publishedrateclass->insert(array('',$district,$city,$zipcode,$portID,$userid,$now,'NULL','NULL',$odaflag,$odarate));
                                        $systemID = $publishedrateclass->getInsertId();
                                        $systemlog->logAddedInfo($publishedrateclass,array($systemID,$district,$city,$zipcode,$portcode,$userid,$now,'NULL','NULL',$odaflag,$odarate),'District/City/ZipCode','New District/City/ZipCode Added - Upload',$userid,$now);
                                    }

                                }
                                else{
                                    array_push($txnwithrowerror, "Error: Origin Code not in Database | Details: Line $i - 3PL=$tpl, Type=$type, Origin=$origincode, Zone=$zonecode, PouchSize=$pouchsize, FixedRateFlag=$fixedflag, FixedAmount=$fixedamount, FromKg=$fromkg, ToKg=$tokg, FreightCharge=$freightcharge, ExcessCharge=$excessfreightcharge");
                                }*/
                            
                        }
                        else if($shipmenttype!=''||$shipmentmode!=''||$modeoftransport!=''||$tpl!=''||$type!=''||$origincode!=''||$zonecode!=''||$fixedflag!=''||$fixedamount!=''||$pouchsize!=''||$fromkg!=''||$tokg!=''||$freightcharge!=''||$excessfreightcharge!=''){

                            if($shipmenttypeID==''){
                                array_push($rowerror, "Shipment Type not in Database");
                            }
                            if($shipmentmodeID==''){
                                array_push($rowerror, "Shipment Mode not in Database");
                            }
                            if($modeoftransportID==''){
                                array_push($rowerror, "Mode of Transport not in Database");
                            }
                            if($tplID==''){
                                array_push($rowerror, "3PL not in Database");
                            }
                            if($type!='DOCUMENT'&&$type!='PARCEL'){
                                array_push($rowerror, "Type must be 'PARCEL' or 'DOCUMENT'");
                            }
                            if($originID==''){
                                array_push($rowerror, "Origin not in Database");
                            }
                            if($zoneID==''){
                                array_push($rowerror, "Zone not in Database");
                            }
                            if($pouchsizeID==''){
                                array_push($rowerror, "Pouch Size not in Database");
                            }
                            if($fixedflag!='NO'&&$fixedflag!='YES'){
                                array_push($rowerror, "Fixed Rate Flag must have a value of YES or NO");
                            }
                            if($fixedflag=='YES'&&!$fixedamount>0){
                                array_push($rowerror, "Fixed Rate Amount must be greater than zero");
                            }
                            if(!($fixedflag=='NO'&&(
                                                   (
                                                     ($fromkg<=$tokg&&$fromkg>0&&$tokg>0)||
                                                     ($fromkg>0&&$tokg==0)
                                                   ) &&
                                                   $freightcharge>0
                                                 )
                            )){
                                array_push($rowerror, "If fixed rate flag = NO, fromkg and freight charge must be greater than zero(0). tokg must be greater than or equal to zero. If tokg is greater than zero, fromkg must be less than or equal to tokg.");
                            }


                            $rowtexterror = implode(", ", $rowerror);


                            array_push($txnwithrowerror, "<b>Line</b>: $i<br><b>Error</b>: $rowtexterror <br><b>Details</b>: ShipmentType=$shipmenttype, ShipmentMode=$shipmentmode, ModeofTransport='$modeoftransport', 3PL=$tpl, Type=$type, Origin=$origincode, Zone=$zonecode, PouchSize=$pouchsize, FixedRateFlag=$fixedflag, FixedAmount=$fixedamount, FromKg=$fromkg, ToKg=$tokg, FreightCharge=$freightcharge, ExcessCharge=$excessfreightcharge");

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