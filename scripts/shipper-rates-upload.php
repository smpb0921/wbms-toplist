<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/shipper-rate.class.php");
    include("../classes/shipper-rate-freight-charge.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['uploadshipperratesmodal-file'])){

       function checkFreightComputation($comp){
            $validfreightcomp = array("ACTUAL WEIGHT","DEFAULT","CBM","VOLUMETRIC","COLLECTION FEE","AD VALOREM","NO. OF PACKAGE");
            $flag = false;
            $checkarray = in_array($comp, $validfreightcomp);

            if($checkarray==1){
                $flag = true;
            }

            return $flag;
       }
       function getShipperID($accountname){
            $accountname = strtoupper($accountname);
            $id = '';
            $rs = query("select * from shipper where upper(account_name)='$accountname'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
       }

       function getPouchSizeID($description){
            $description = strtoupper($description);
            $id = '';
            $rs = query("select * from pouch_size where upper(description)='$description'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
       }

       function getPortID($code){
            $code = strtoupper($code);
            $id = '';
            $rs = query("select * from origin_destination_port where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
       }

       function getServiceID($code){
            $code = strtoupper($code);
            $id = '';
            $rs = query("select * from services where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
       }

       function getModeOfTransportID($code){
            $code = strtoupper($code);
            $id = '';
            $rs = query("select * from mode_of_transport where upper(code)='$code'");
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

    	$file = $_FILES['uploadshipperratesmodal-file'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
        //$ftype = $file['type'];
    	$ftype = strrchr($filename, '.');

        $filetype = $_POST['uploadshipperratesmodal-type'];


    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

            if($filetype=='PARCEL'){

    			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
    			@$excelObj = $excelReader->load($tmp);



                $worksheet = $excelObj->getActiveSheet();
                $lastRow = $worksheet->getHighestRow();
                $lastCol = $worksheet->getHighestColumn();

                $headerColumns = array(
                                        'SHIPPER ACCOUNT NAME', //A
                                        'ORIGIN CODE', //B
                                        'DESTINATION CODE', //C
                                        'MODE OF TRANSPORT CODE',
                                        'SERVICES CODE', //E
                                        'FREIGHT COMPUTATION', //F
                                        'COLLECTION PERCENTAGE', //F
                                        'FREIGHT CHARGE COMPUTATION', //F
                                        'INSURANCE RATE COMPUTATION', //F
                                        'EXCESS AMOUNT OF', //F
                                        'RUSH FLAG', //F
                                        'PULLOUT FLAG', //F
                                        'FIXED RATE FLAG', //F
                                        'FIXED RATE AMOUNT', //F
                                        'ODA', //F
                                        'VALUATION PERCENTAGE', //F
                                        'FREIGHT RATE (FOR AD VALOREM)', //F
                                        'INSURANCE RATE', //F
                                        'FUEL RATE', //F
                                        'BUNKER RATE', //F
                                        'MINIMUM RATE', //F
                                        'RETURN DOCUMENT FEE', //F
                                        'WAYBILL FEE', //F
                                        'SECURITY FEE', //F
                                        'DOC STAMP FEE', //F
                                        'FREIGHT CHARGE - FROM', //F
                                        'FREIGHT CHARGE - TO', //F
                                        'FREIGHT CHARGE AMOUNT', //F
                                        'EXCESS WEIGHT CHARGE' //F
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

                    $rowswitherror = array();
                    $rowsuccessful = array();
                    $rowsupdated = array();

                    $shiprateclass = new shipper_rate();
                    $shipratefcclass = new shipper_rate_freight_charge();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $shipperaccountname = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $shipperID = getShipperID($shipperaccountname);
                        $origin = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $originID = getPortID($origin);
                        $destination = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $destinationID = getPortID($destination);
                        $modeoftransport = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $modeoftransportID = getModeOfTransportID($modeoftransport);
                        $service = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $serviceID = getServiceID($service);
                        $freightcomputation = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $checkfreightcomp = checkFreightComputation($freightcomputation);


                        $collectionpercentage = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $freightchargecomputation = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                        $insuranceratecomputation = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                        $excessamount = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                        $rushflag = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                        $pulloutflag = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                        $fixedrateflag = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                        $fixedrateamount = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                        $oda = convertToText(strtoupper($worksheet->getCell('O'.$i)->getValue()));
                        $valuation = convertToText(strtoupper($worksheet->getCell('P'.$i)->getValue()));
                        $freightrate = convertToText(strtoupper($worksheet->getCell('Q'.$i)->getValue()));
                        $insurancerate = convertToText(strtoupper($worksheet->getCell('R'.$i)->getValue()));
                        $fuelrate = convertToText(strtoupper($worksheet->getCell('S'.$i)->getValue()));
                        $bunkerrate = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));
                        $minimumrate = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
                        $returndocumentfee = convertToText(strtoupper($worksheet->getCell('V'.$i)->getValue()));
                        $waybillfee = convertToText(strtoupper($worksheet->getCell('W'.$i)->getValue()));
                        $securityfee = convertToText(strtoupper($worksheet->getCell('X'.$i)->getValue()));
                        $docstampfee = convertToText(strtoupper($worksheet->getCell('Y'.$i)->getValue()));
                        $freightchargefrom = convertToText(strtoupper($worksheet->getCell('Z'.$i)->getValue()));
                        $freightchargeto = convertToText(strtoupper($worksheet->getCell('AA'.$i)->getValue()));
                        $freightcharge = convertToText(strtoupper($worksheet->getCell('AB'.$i)->getValue()));
                        $excessweightcharge = convertToText(strtoupper($worksheet->getCell('AC'.$i)->getValue()));


                        $excessamount = str_replace(',', '', $excessamount);
                        $fixedrateamount = str_replace(',', '', $fixedrateamount);
                        $oda = str_replace(',', '', $oda);
                        $valuation = str_replace(',', '', $valuation);
                        $freightrate = str_replace(',', '', $freightrate);
                        $insurancerate = str_replace(',', '', $insurancerate);
                        $fuelrate = str_replace(',', '', $fuelrate);
                        $bunkerrate = str_replace(',', '', $bunkerrate);
                        $minimumrate = str_replace(',', '', $minimumrate);
                        $returndocumentfee = str_replace(',', '', $returndocumentfee);
                        $waybillfee = str_replace(',', '', $waybillfee);
                        $securityfee = str_replace(',', '', $securityfee);
                        $docstampfee = str_replace(',', '', $docstampfee);
                        $freightchargefrom = str_replace(',', '', $freightchargefrom);
                        $freightchargeto = str_replace(',', '', $freightchargeto);
                        $freightcharge = str_replace(',', '', $freightcharge);
                        $excessweightcharge = str_replace(',', '', $excessweightcharge);
                        




                        //$collectionpercentage = $collectionpercentage>0?$collectionpercentage:0;
                        //$freightcomputation = trim($freightcomputation)==''?'NULL':$freightcomputation;

                        $rowinsertflag = false;

                        $lineerrorremarks = array();
                        

                        if($shipperID!=''&&$originID!=''&&$destinationID!=''&&$modeoftransportID!=''&&$serviceID!=''&&($rushflag=='YES'||$rushflag=='NO')&&($pulloutflag=='YES'||$pulloutflag=='NO')&&($fixedrateflag=='YES'||$fixedrateflag=='NO')){

                            if($fixedrateflag=='YES'){
                                if($fixedrateamount>0){
                                    $rowinsertflag = true;
                                }
                                else{
                                     array_push($lineerrorremarks, "Fixed Rate Amount should be greater than zero");
                                }

                            }
                            else{
                                if($checkfreightcomp==true){
                                    if($freightcomputation=='ACTUAL WEIGHT'||$freightcomputation=='CBM'||$freightcomputation=='VOLUMETRIC'||$freightcomputation=='DEFAULT'){

                                        if($freightchargecomputation==1||$freightchargecomputation==2){

                                            if($insuranceratecomputation==1||$insuranceratecomputation==2){

                                                if($insuranceratecomputation==2){
                                                    if($excessamount>0){
                                                        $rowinsertflag = true;
                                                    }
                                                    else{
                                                         array_push($lineerrorremarks, "Please provide excess amount of");
                                                    }
                                                }
                                                else{
                                                    $rowinsertflag = true;
                                                }
                                            }
                                            else{
                                                 array_push($lineerrorremarks, "Invalid Insurance Rate Computation");
                                            }

                                        }
                                        else{
                                            array_push($lineerrorremarks, "Invalid Freight Charge Computation");
                                        }

                                    }
                                    else if($freightcomputation=='COLLECTION FEE'){

                                        if($collectionpercentage>0){

                                            if($insuranceratecomputation==1||$insuranceratecomputation==2){

                                                if($insuranceratecomputation==2){
                                                    if($excessamount>0){
                                                        $rowinsertflag = true;
                                                    }
                                                    else{
                                                         array_push($lineerrorremarks, "Please provide excess amount of");
                                                    }
                                                }
                                                else{
                                                    $rowinsertflag = true;
                                                }
                                            }
                                            else{
                                                 array_push($lineerrorremarks, "Invalid Insurance Rate Computation");
                                            }
                                        }
                                        else{
                                            array_push($lineerrorremarks, "Please provide collection percentage");
                                        }

                                    }
                                    else{
                                            if($insuranceratecomputation==1||$insuranceratecomputation==2){

                                                if($insuranceratecomputation==2){
                                                    if($excessamount>0){
                                                        $rowinsertflag = true;
                                                    }
                                                    else{
                                                         array_push($lineerrorremarks, "Please provide excess amount of");
                                                    }
                                                }
                                                else{
                                                    $rowinsertflag = true;
                                                }
                                            }
                                            else{
                                                 array_push($lineerrorremarks, "Invalid Insurance Rate Computation");
                                            }
                                    }

                                }
                                else{
                                    array_push($lineerrorremarks, "Invalid Freight Computation");
                                }
                            }

                        }
                        else{
                                if($shipperID==''){
                                    array_push($lineerrorremarks, "Invalid Shipper: $shipperaccountname");
                                }
                                if($originID==''){
                                    array_push($lineerrorremarks, "Invalid Origin: $origin");
                                }
                                if($destinationID==''){
                                    array_push($lineerrorremarks, "Invalid Destination: $destination");
                                }
                                if($modeoftransportID==''){
                                    array_push($lineerrorremarks, "Invalid Mode of Transport: $modeoftransport");
                                }
                                if($serviceID==''){
                                    array_push($lineerrorremarks, "Invalid Service: $service");
                                }
                                if($rushflag!='YES'&&$rushflag!='NO'){
                                    array_push($lineerrorremarks, "Invalid Rush Flag");
                                }
                                if($pulloutflag!='YES'&&$pulloutflag!='NO'){
                                    array_push($lineerrorremarks, "Invalid Pullout Flag");
                                }
                                if($fixedrateflag!='YES'&&$fixedrateflag!='NO'){
                                    array_push($lineerrorremarks, "Invalid Fixed Rate Flag");
                                }
                                
                        }

                        
                        if($rowinsertflag==true){
                            

                            $rushflag = $rushflag=='YES'?1:0;
                            $pulloutflag = $pulloutflag=='YES'?1:0;
                            $fixedrateflag = $fixedrateflag=='YES'?1:0;
                            $exists = false;
                            $now = date('Y-m-d H:i:s');
                            $userid = USERID;
                            $shipperrateID = '';

                            $checkifexistrs = query("select * from shipper_rate 
                                                   where shipper_id='$shipperID' and 
                                                         origin_id='$originID' and 
                                                         destination_id='$destinationID' and 
                                                         mode_of_transport_id='$modeoftransportID' and 
                                                         rush_flag='$rushflag' and 
                                                         pull_out_flag='$pulloutflag' and 
                                                         waybill_type='$filetype' and 
                                                         services_id='$serviceID'");
                            if(getNumRows($checkifexistrs)>0){
                                $exists = true;
                                while($obj=fetch($checkifexistrs)){
                                    $shipperrateID = $obj->id;
                                }
                                array_push($rowsupdated, "Row $i");
                            }
                            else{
                                array_push($rowsuccessful, "Row $i");
                            }

                            if($fixedrateflag==1){

                                $freightcomputation = 'NULL';
                                $freightchargecomputation = 'NULL';
                                $insuranceratecomputation = 'NULL';


                                if($exists==true){
                                    $systemlog->logEditedInfo($shiprateclass,$shipperrateID,array($shipperrateID,$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,'NULL',$fixedrateflag,0,0,0,0,0,0,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$filetype,'NOCHANGE',$fixedrateamount,0,0,$serviceID,0,0,0,0,'NULL',0,'NULL',0,'NULL',0),'SHIPPER RATE','Edited Shipper Rate Info - Upload',$userid,$now);/// log should be before update is made
                                    $shiprateclass->update($shipperrateID,array($shipperID,'NULL',$originID,$destinationID,$modeoftransportID,'NULL',$fixedrateflag,0,0,0,0,0,0,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$filetype,'NULL',$fixedrateamount,0,0,$serviceID,0,0,0,0,'NULL',0,'NULL',0,'NULL',0));
                                }
                                else{
                                    $shiprateclass->insert(array('',$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,'NULL',$fixedrateflag,0,0,0,0,0,0,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$filetype,'NULL',$fixedrateamount,0,0,$serviceID,0,0,0,0,'NULL',0,'NULL',0,'NULL',0));
                                    $id = $shiprateclass->getInsertId();
                                    $systemlog->logAddedInfo($shiprateclass,array($id,$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,'NULL',$fixedrateflag,0,0,0,0,0,0,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$filetype,'NULL',$fixedrateamount,0,0,$serviceID,0,0,0,0,'NULL',0,'NULL',0,'NULL',0),'SHIPPER RATE','New Shipper Rate Uploaded',$userid,$now);
                                }


                            }
                            else{
                                $fixedrateamount = 0;

                                if($freightcomputation!='ACTUAL WEIGHT'&&$freightcomputation!='CBM'&&$freightcomputation!='VOLUMETRIC'&&$freightcomputation!='DEFAULT'){
                                    $freightchargecomputation = 'NULL';
                                }

                                if($freightcomputation!='COLLECTION FEE'){
                                    $collectionpercentage = 0;
                                }

                                if($insuranceratecomputation==1){
                                    $excessamount = 0;
                                }

                                if($exists==true){
                                    $systemlog->logEditedInfo($shiprateclass,$shipperrateID,array($shipperrateID,$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$filetype,'NULL',0,0,$oda,$serviceID,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,'NULL',0,$insuranceratecomputation,$excessamount),'SHIPPER RATE','Edited Shipper Rate Info - Upload',$userid,$now);/// log should be before update is made
                                    $shiprateclass->update($shipperrateID,array($shipperID,'NULL',$originID,$destinationID,$modeoftransportID,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$filetype,'NULL',0,0,$oda,$serviceID,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,'NULL',0,$insuranceratecomputation,$excessamount));
                                }
                                else{
                                    $shiprateclass->insert(array('',$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$filetype,'NULL',0,0,$oda,$serviceID,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,'NULL',0,$insuranceratecomputation,$excessamount));
                                    $shipperrateID = $shiprateclass->getInsertId();
                                    $systemlog->logAddedInfo($shiprateclass,array($shipperrateID,$shipperID,'NULL',$originID,$destinationID,$modeoftransportID,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$filetype,'NULL',0,0,$oda,$serviceID,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,'NULL',0,$insuranceratecomputation,$excessamount),'SHIPPER RATE','New Shipper Rate Uploaded',$userid,$now);
                                }
                            }


                            if($freightchargefrom>=0&&$freightchargeto>0&&$freightcharge>=0&&$excessweightcharge>=0&&($freightcomputation=='ACTUAL WEIGHT'||$freightcomputation=='CBM'||$freightcomputation=='VOLUMETRIC'||$freightcomputation=='DEFAULT')){  

                                $checkiffreightchargeexist = query("select * from shipper_rate_freight_charge 
                                                                    where from_kg='$freightchargefrom' and to_kg='$freightchargeto'");

                                if(getNumRows($checkiffreightchargeexist)>0){

                                    while($obj=fetch($checkiffreightchargeexist)){
                                        $freightchargeID = $obj->id;
                                    }
                        
                                    $systemlog->logEditedInfo($shipratefcclass,$freightchargeID,array($freightchargeID,$shipperrateID,$freightchargefrom,$freightchargeto,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid),'SHIPPER RATE - FREIGHT CHARGE','Edited Shipper Rate - Freight Charge (Upload)',$userid,$now);/// log should be before update is made
                                     $shipratefcclass->update($freightchargeID,array('NOCHANGE',$freightchargefrom,$freightchargeto,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid));
                                }
                                else{

                                    $shipratefcclass->insert(array('',$shipperrateID,$freightchargefrom,$freightchargeto,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'));
                                    $freightchargeID = $shipratefcclass->getInsertId();
                                    $systemlog->logAddedInfo($shipratefcclass,array($freightchargeID,$shipperrateID,$freightchargefrom,$freightchargeto,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'),'SHIPPER RATE - FREIGHT CHARGE','New Shipper Rate - Freight Charge (Upload)',$userid,$now);

                                }


                            }


                            if($freightcomputation!='ACTUAL WEIGHT'&&$freightcomputation!='CBM'&&$freightcomputation!='VOLUMETRIC'&&$freightcomputation!='DEFAULT'){
                                    query("delete from shipper_rate_freight_charge where shipper_rate_id='$shipperrateID'");
                            }
                        }
                        else{
                            $lineerrorremarks = implode('  |  ', $lineerrorremarks);
                            array_push($rowswitherror, "Line $i: ".$lineerrorremarks);
                        }

                       


                    }
                    

                    

                    echo "SHIPPER RATES: <br><br>";

                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS INSERTED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowsuccessful);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowsuccessful[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";


                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowsupdated);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowsupdated[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";


                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS WITH ERROR</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowswitherror);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowswitherror[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";
                    /*$txnwithrowerror = array();
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
                     echo   "</tbody></table><br>";*/


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/districtcityzip-transaction-template.xlsx'>here</a> to download file template<br>";
                        
                }

            }
            else if($filetype=='DOCUMENT'){



                @$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
                @$excelObj = $excelReader->load($tmp);



                $worksheet = $excelObj->getActiveSheet();
                $lastRow = $worksheet->getHighestRow();
                $lastCol = $worksheet->getHighestColumn();

                $headerColumns = array(
                                        'SHIPPER ACCOUNT NAME', //A
                                        'ORIGIN CODE', //B
                                        'DESTINATION CODE', //C
                                        'POUCH SIZE',
                                        'EXPRESS TRANSACTION TYPE', //E
                                        'RUSH FLAG', //F
                                        'AD VALOREM FLAG', //F
                                        'ODA', //F
                                        'VALUATION PERCENTAGE', //F
                                        'FREIGHT RATE', //F
                                        'RETURN DOCUMENT FEE', //F
                                        'WAYBILL FEE', //F
                                        'SECURITY FEE', //F
                                        'DOC STAMP FEE'
                                       );
                $checkHeaderInfo = true;
                $col = "A";
                for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){

                            $checkHeaderInfo = false;
                        }
                        $col++;
                }

                if($checkHeaderInfo==true){

                    $rowswitherror = array();
                    $rowsuccessful = array();
                    $rowsupdated = array();

                    $shiprateclass = new shipper_rate();
                    $shipratefcclass = new shipper_rate_freight_charge();
                    $systemlog = new system_log();

                    for($i=2;$i<=$lastRow;$i++){
                        $shipperaccountname = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                        $shipperID = getShipperID($shipperaccountname);
                        $origin = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                        $originID = getPortID($origin);
                        $destination = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                        $destinationID = getPortID($destination);
                        $pouchsize = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                        $pouchsizeID = getPouchSizeID($pouchsize);
                        $expresstransactiontype = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                        $rushflag = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                        $advaloremflag = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                        $oda = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                        $valuation = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                        $freightrate = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                        $returndocumentfee = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                        $waybillfee = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                        $securityfee = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                        $docstampfee = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));

                        $oda = str_replace(',', '', $oda);
                        $valuation = str_replace(',', '', $valuation);
                        $freightrate = str_replace(',', '', $freightrate);
                        $returndocumentfee = str_replace(',', '', $returndocumentfee);
                        $waybillfee = str_replace(',', '', $waybillfee);
                        $securityfee = str_replace(',', '', $securityfee);
                        $docstampfee = str_replace(',', '', $docstampfee);



                        //$collectionpercentage = $collectionpercentage>0?$collectionpercentage:0;
                        //$freightcomputation = trim($freightcomputation)==''?'NULL':$freightcomputation;

                        $rowinsertflag = false;

                        $lineerrorremarks = array();
                        

                        if($shipperID!=''&&$originID!=''&&$destinationID!=''&&$pouchsizeID!=''&&($rushflag=='YES'||$rushflag=='NO')&&($advaloremflag=='YES'||$advaloremflag=='NO')&&($expresstransactiontype=='DOCUMENT'||$expresstransactiontype=='NON-DOCUMENT')&&$freightrate>0){

                            $rowinsertflag = true;

                           

                        }
                        else{
                                if($shipperID==''){
                                    array_push($lineerrorremarks, "Invalid Shipper: $shipperaccountname");
                                }
                                if($originID==''){
                                    array_push($lineerrorremarks, "Invalid Origin: $origin");
                                }
                                if($destinationID==''){
                                    array_push($lineerrorremarks, "Invalid Destination: $destination");
                                }
                                if($pouchsizeID==''){
                                    array_push($lineerrorremarks, "Invalid Pouch Size: $pouchsize");
                                }
                                if($expresstransactiontype!='DOCUMENT'&&$expresstransactiontype!='NON-DOCUMENT'){
                                    array_push($lineerrorremarks, "Invalid Express Transaction Type. Accepted Values: DOCUMENT or NON-DOCUMENT");
                                }
                                if($rushflag!='YES'&&$rushflag!='NO'){
                                    array_push($lineerrorremarks, "Invalid Rush Flag. Accepted Values: YES or NO");
                                }
                                if($advaloremflag!='YES'&&$advaloremflag!='NO'){
                                    array_push($lineerrorremarks, "Invalid Pullout Flag. Accepted Values: YES or NO");
                                }
                                if($freightrate<=0||trim($freightrate)==''){
                                    array_push($lineerrorremarks, "Invalid Freight Rate");
                                }
                                
                        }

                        
                        if($rowinsertflag==true){
                            

                            $rushflag = $rushflag=='YES'?1:0;
                            $advaloremflag = $advaloremflag=='YES'?1:0;
                            $exists = false;
                            $now = date('Y-m-d H:i:s');
                            $userid = USERID;
                            $shipperrateID = '';

                            $checkifexistrs = query("select * from shipper_rate 
                                                   where shipper_id='$shipperID' and 
                                                         origin_id='$originID' and 
                                                         destination_id='$destinationID' and 
                                                         pouch_size_id='$pouchsizeID' and 
                                                         rush_flag='$rushflag' and
                                                         waybill_type='$filetype' and 
                                                         express_transaction_type='$expresstransactiontype'");
                            if(getNumRows($checkifexistrs)>0){
                                $exists = true;
                                while($obj=fetch($checkifexistrs)){
                                    $shipperrateID = $obj->id;
                                }
                                array_push($rowsupdated, "Row $i");
                            }
                            else{
                                array_push($rowsuccessful, "Row $i");
                            }

                           

                           
                                

                            if($exists==true){
                                    $systemlog->logEditedInfo($shiprateclass,$shipperrateID,array($shipperrateID,$shipperID,'NULL',$originID,$destinationID,'NULL','NULL',0,$valuation,$freightrate,0,0,0,0,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,0,$filetype,$pouchsizeID,0,0,$oda,'NULL',$returndocumentfee,$waybillfee,$securityfee,$docstampfee,'NULL',0,$expresstransactiontype,$advaloremflag,'NULL',0),'SHIPPER RATE','Edited Shipper Rate Info - Upload',$userid,$now);/// log should be before update is made
                                    $shiprateclass->update($shipperrateID,array($shipperID,'NULL',$originID,$destinationID,'NULL','NULL',0,$valuation,$freightrate,0,0,0,0,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,0,$filetype,$pouchsizeID,0,0,$oda,'NULL',$returndocumentfee,$waybillfee,$securityfee,$docstampfee,'NULL',0,$expresstransactiontype,$advaloremflag,'NULL',0));
                            }
                            else{

                                   $shiprateclass->insert(array('',$shipperID,'NULL',$originID,$destinationID,'NULL','NULL',0,$valuation,$freightrate,0,0,0,0,$now,$userid,'NULL','NULL',$rushflag,0,$filetype,$pouchsizeID,0,0,$oda,'NULL',$returndocumentfee,$waybillfee,$securityfee,$docstampfee,'NULL',0,$expresstransactiontype,$advaloremflag,'NULL',0));
                                    $shipperrateID = $shiprateclass->getInsertId();
                                    $systemlog->logAddedInfo($shiprateclass,array($shipperrateID,$shipperID,'NULL',$originID,$destinationID,'NULL','NULL',0,$valuation,$freightrate,0,0,0,0,$now,$userid,'NULL','NULL',$rushflag,0,$filetype,$pouchsizeID,0,0,$oda,'NULL',$returndocumentfee,$waybillfee,$securityfee,$docstampfee,'NULL',0,$expresstransactiontype,$advaloremflag,'NULL',0),'SHIPPER RATE','New Shipper Rate Uploaded',$userid,$now);
                            }


                          



                        }
                        else{
                            $lineerrorremarks = implode('  |  ', $lineerrorremarks);
                            array_push($rowswitherror, "Line $i: ".$lineerrorremarks);
                        }

                       


                    }
                    

                    

                    echo "SHIPPER RATES: <br><br>";

                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS INSERTED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowsuccessful);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowsuccessful[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";


                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowsupdated);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowsupdated[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";


                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>ROWS WITH ERROR</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($rowswitherror);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$rowswitherror[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                    echo       "</tbody>
                           </table><br>";


                                    
                }
                else{
                    echo "Unable to upload file. <b>Invalid Header Format</b>.<br><br>";
                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/districtcityzip-transaction-template.xlsx'>here</a> to download file template<br>";
                        
                }
            }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>