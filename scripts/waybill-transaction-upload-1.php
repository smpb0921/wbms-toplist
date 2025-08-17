<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
     include("../classes/waybill.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['waybilluploadtransactionfile'])){

        function recomputeVWCBM($waybillnumber){

            $vw = 0;
            $cbm = 0;
            $prevvw = 0;
            $prevcbm = 0;
            $numofpackage = 0;
            $actualweight = 0;
            $prevnumofpackage = 0;
            $prevactualweight = 0;

            $origin = '';
            $destination = '';
            $modeoftransport = '';
            $actualweight = 0;
            $declaredvalue = 0;
            $numberofpackage = 0;
            $zeroratedflag = 0;

            $totalcharges = 0;

            $rs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
            while($obj=fetch($rs)){
                $prevcbm = $obj->package_cbm;
                $prevvw = $obj->package_vw;
                $origin = $obj->origin_id;
                $destination = $obj->destination_id;
                $modeoftransport = $obj->package_mode_of_transport;
                //$actualweight = $obj->package_actual_weight;
                $declaredvalue = $obj->package_declared_value;
                //$numberofpackage = $obj->package_number_of_packages;
                $zeroratedflag = $obj->zero_rated_flag;
                $prevnumofpackage = $obj->package_number_of_packages;
                $prevactualweight = $obj->package_actual_weight;

                /***** comment to self get previous qty for charges later on ***/
            }

            $rs = query("select sum(volumetric_weight) as vw, sum(cbm) as cbm, sum(quantity) as numofpackage, sum(actual_weight) as actualweight from txn_waybill_package_dimension where waybill_number='$waybillnumber'");
            while($obj=fetch($rs)){
                $vw = round($obj->vw,4);
                $cbm = round($obj->cbm,4);
                $numofpackage = $obj->numofpackage;
                $actualweight = round($obj->actualweight,4);
            }

            /*if($vw>$cbm){
                $vwcbm = $vw;
            }
            else{
                $vwcbm = $cbm;
            }*/

            query("update txn_waybill set package_cbm='$cbm', package_vw='$vw', package_number_of_packages='$numofpackage', package_actual_weight='$actualweight' where waybill_number='$waybillnumber'");

            $systemlog = new system_log();
            $userid = USERID;
            $now = date('Y-m-d H:i:s');
            $systemlog->logInfo('WAYBILL',"Updated VW/CBM/NUM OF PACKAGES/ACTUAL WEIGHT (Upload)","Waybill Number: ".$waybillnumber."  |  VW = $prevvw->$vw  |  CBM = $prevcbm->$cbm  |  NUM OF PACKAGE = $prevnumofpackage->$numofpackage  |  ACTUAL WEIGHT = $prevactualweight->$actualweight",$userid,$now);

            $othercharges = 0;
            $rs = query("select sum(amount) as othercharges from txn_waybill_other_charges where waybill_number='$waybillnumber'");
            while($obj=fetch($rs)){
                $othercharges = $obj->othercharges;
            }

            $autocomputearray = computeWaybill($origin,$destination,$modeoftransport,$vw,$cbm,$actualweight,$declaredvalue,$numberofpackage,$zeroratedflag);

            $freightcompdesc = $autocomputearray['freightcomputation'];
            $valuation = $autocomputearray['valuation'];
            $freightcharges = $autocomputearray['freightrate'];
            $insurancecharges = $autocomputearray['insurancerate'];
            $fuelcharges = $autocomputearray['fuelrate'];
            $bunkercharges = $autocomputearray['bunkerrate'];
            $minimumcharges = $autocomputearray['minimumrate'];
            $chargeableweight = $autocomputearray['chargeableweight'];
            $vat = $autocomputearray['vat'];

            $totalcharges = $valuation+$freightcharges+$insurancecharges+$fuelcharges+$bunkercharges;
            $totalcharges = $totalcharges+$othercharges;

            if($totalcharges<$minimumcharges){
                $totalcharges = $minimumcharges;
            }


            $totalamount = $totalcharges+$vat;

            query("update txn_waybill set package_chargeable_weight='$chargeableweight',
                                          package_freight_computation='$freightcompdesc',
                                          package_valuation='$valuation',
                                          package_freight='$freightcharges',
                                          package_insurance_rate='$insurancecharges',
                                          package_fuel_rate='$fuelcharges',
                                          package_bunker_rate='$bunkercharges',
                                          package_minimum_rate='$minimumcharges',
                                          subtotal='$totalcharges',
                                          vat='$vat',
                                          total_amount='$totalamount'
                                where waybill_number='$waybillnumber'");


            echo "Recomputing VW/CBM/NUM OF PACKAGES/ACTUAL WEIGHT for $waybillnumber...<br>";
            echo "VW from $prevvw to $vw | CBM from $prevcbm to $cbm | NO. OF PACKAGE from $prevnumofpackage to $numofpackage | ACTUAL WEIGHT from $prevactualweight to $actualweight... <br>";
            echo "Recomputing Waybill for $waybillnumber...<br>";






        }

        function recomputeWaybill($waybillnumber){
            $minimumcharges = 0;
            $subtotal = 0;
            $prevsubtotal = 0;
            $prevtotalamount = 0;
            $othercharges = 0;
            $vat = 0;
            $totalamount = 0;
            $rs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
            while($obj=fetch($rs)){
                $subtotal = $obj->subtotal;
                $vat = $obj->vat;
                $minimumcharges = $obj->package_minimum_rate;
                $prevsubtotal = $obj->subtotal;
                $prevtotalamount = $obj->total_amount;
            }

            $rs = query("select sum(amount) as othercharges from txn_waybill_other_charges where waybill_number='$waybillnumber'");
            while($obj=fetch($rs)){
                $othercharges = $obj->othercharges;
            }

            $subtotal = $subtotal+$othercharges;

            if($subtotal<$minimumcharges){
                $subtotal = $minimumcharges;
            }

            $totalamount = $subtotal + $vat;


                            
            query("update txn_waybill set subtotal='$subtotal', total_amount='$totalamount' where waybill_number='$waybillnumber'");


            echo "<b>Waybill No.</b>->$waybillnumber:    Subtotal = $prevsubtotal->$subtotal,   VAT = $vat,   Total Amount = $prevtotalamount->$totalamount<br>";

            $systemlog = new system_log();
            $userid = USERID;
            $now = date('Y-m-d H:i:s');
            $systemlog->logInfo('WAYBILL',"Recompute Charges (Upload)","Waybill Number: ".$waybillnumber."  |  Subtotal = $prevsubtotal->$subtotal  |  VAT = $vat  |  Total Amount = $prevtotalamount->$totalamount",$userid,$now);

            



        }

        function computeWaybill($origin,$destination,$modeoftransport,$vw,$cbm,$actualweight,$declaredvalue,$numberofpackage,$zeroratedflag){
          
            $response = array();
            /*$vwcbm = $vw;

            if($actualweight>$vwcbm){
                $chargeableweight = $actualweight;
            }
            else{
                $chargeableweight = $vwcbm;
            }*/

            $ratefrom = '';
            $freightcomputation = '';
            $valuation = 0;
            $freightrate = 0;
            $insurancerate = 0;
            $fuelrate = 0;
            $bunkerrate = 0;
            $minimumrate = 0;
            $multiplier = 0;
            $totalrate = 0;

            

            $vat = getInfo("company_information","value_added_tax_percentage","where id=1");
            $vat = $vat>=0?$vat:0;


            if($origin!=''&&$origin!='NULL'&&$destination!=''&&$destination!='NULL'&&$modeoftransport!=''&&$modeoftransport!='NULL'){
                
                $checkshipperraters = query("select * from shipper_rate where origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' limit 1");
                if(getNumRows($checkshipperraters)==1){// has shipper rate
                    while($obj=fetch($checkshipperraters)){
                        $freightcomputation = $obj->freight_computation;
                        $minimumrate = $obj->minimum_rate;

                        $rtvaluation = $obj->valuation>=0?$obj->valuation:0;
                        $rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
                        $rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
                        $rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
                        $rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

                        if(strtoupper($freightcomputation)=='AD VALOREM'){
                            $multiplier = $declaredvalue;
                            $freightrate = $multiplier*($rtfreightrate/100);
                        }
                        else if(strtoupper($freightcomputation)=='NO. OF PACKAGE'){
                            $multiplier = $numberofpackage;
                            $freightrate = $multiplier*$rtfreightrate;
                        }
                        else if(strtoupper($freightcomputation)=='CBM'){
                            $multiplier = $cbm;
                            $freightrate = $multiplier*$rtfreightrate;
                        }
                        else if(strtoupper($freightcomputation)=='VOLUMETRIC'){
                            $multiplier = $vw;
                            $freightrate = $multiplier*$rtfreightrate;
                        }
                        else if(strtoupper($freightcomputation)=='DEFAULT'){

                            if($cbm>$vw){
                                $multiplier = $cbm;
                            }
                            else if($vw>$cbm){
                                $multiplier = $vw;
                            }
                            else{
                                $multiplier = $cbm;
                            }
                            $freightrate = $multiplier*$rtfreightrate;
                        }
                        else{
                            $multiplier = 0;
                            $freightrate = $multiplier*$rtfreightrate;
                        }

                        $valuation = $multiplier*($rtvaluation/100);
                        $insurancerate = $multiplier*$rtinsurancerate;
                        $fuelrate = $multiplier*$rtfuelrate;
                        $bunkerrate = $multiplier*$rtbunkerrate;
                        $chargeableweight = $multiplier;

                        $totalrate = $valuation+$freightrate+$insurancerate+$fuelrate+$bunkerrate;

                    }
                    $ratefrom = 'SHIPPER';
                }
                else{//no shipper rate, check published rate instead

                    $checkpublishedraters = query("select * from published_rate where origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' limit 1");
                    if(getNumRows($checkpublishedraters)==1){
                        while($obj=fetch($checkpublishedraters)){
                            $freightcomputation = $obj->freight_computation;
                            $minimumrate = $obj->minimum_rate;

                            $rtvaluation = $obj->valuation>=0?$obj->valuation:0;
                            $rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
                            $rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
                            $rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
                            $rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

                            if(strtoupper($freightcomputation)=='AD VALOREM'){
                                $multiplier = $declaredvalue;
                                $freightrate = $multiplier*($rtfreightrate/100);
                            }
                            else if(strtoupper($freightcomputation)=='NO. OF PACKAGE'){
                                $multiplier = $numberofpackage;
                                $freightrate = $multiplier*$rtfreightrate;
                            }
                            else if(strtoupper($freightcomputation)=='CBM'){
                                $multiplier = $cbm;
                                $freightrate = $multiplier*$rtfreightrate;
                            }
                            else if(strtoupper($freightcomputation)=='VOLUMETRIC'){
                                $multiplier = $vw;
                                $freightrate = $multiplier*$rtfreightrate;
                            }
                            else if(strtoupper($freightcomputation)=='ACTUAL WEIGHT'){
                                $multiplier = $actualweight;
                                $freightrate = $multiplier*$rtfreightrate;
                            }
                            else if(strtoupper($freightcomputation)=='DEFAULT'){

                                if($cbm>$vw){
                                    $multiplier = $cbm;
                                }
                                else if($vw>$cbm){
                                    $multiplier = $vw;
                                }
                                else{
                                    $multiplier = $cbm;
                                }
                                $freightrate = $multiplier*$rtfreightrate;
                            }
                            else{
                                $multiplier = 0;
                                $freightrate = $multiplier*$rtfreightrate;
                            }

                            $valuation = $multiplier*($rtvaluation/100);
                            $insurancerate = $multiplier*$rtinsurancerate;
                            $fuelrate = $multiplier*$rtfuelrate;
                            $bunkerrate = $multiplier*$rtbunkerrate;
                            $chargeableweight = $multiplier;

                            $totalrate = $valuation+$freightrate+$insurancerate+$fuelrate+$bunkerrate;

                        }
                        $ratefrom = 'PUBLISHED';
                    }
                    else{
                        $chargeableweight = 0;
                    }

                }



            }
            else{
                $chargeableweight = 0;
            }


            $totalamount = 0;
            if($zeroratedflag==1){
                $vat = 0;
            }
            else{
                $vat = $totalrate*$vat;
            }

            $totalamount = $totalrate+$vat;



            $response = array(
                                       "freightcomputation"=>$freightcomputation,
                                       "valuation"=>round($valuation,4),
                                       "freightrate"=>round($freightrate,4),
                                       "insurancerate"=>round($insurancerate,4),
                                       "fuelrate"=>round($fuelrate,4),
                                       "bunkerrate"=>round($bunkerrate,4),
                                       "minimumrate"=>round($minimumrate,4),
                                       "chargeableweight"=>round($chargeableweight,4),
                                       "ratefrom"=>$ratefrom,
                                       "multiplier"=>round($multiplier,4),
                                       "totalrate"=>round($totalrate,4),
                                       "totalamount"=>round($totalamount,4),
                                       "vat"=>round($vat,4),
                                       "response"=>'success'
                            );

            return $response;
        }






        function getOriginDestinationID($code){
            $id = '';
            $rs = query("select * from origin_destination_port where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getDestinationRouteID($code){
            $id = '';
            $rs = query("select * from destination_route where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getShipperID($shippernum){
            $shipperID = '';
            $rs = query("select * from shipper where upper(account_number)='$shippernum'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $shipperID=$obj->id;
                }
            }
            return $shipperID;
        }

        function getConsigneeID($accountnum){
            $id = '';
            $rs = query("select * from consignee where upper(account_number)='$accountnum'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getServiceID($code){
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
            $id = '';
            $rs = query("select * from mode_of_transport where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getDocumentID($code){
            $id = '';
            $rs = query("select * from accompanying_documents where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getDeliveryInstructionID($code){
            $id = '';
            $rs = query("select * from delivery_instruction where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }


        function getTransportChargesID($code){
            $id = '';
            $rs = query("select * from transport_charges where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getPayModeDescription($code){
            $desc = '';
            $rs = query("select * from pay_mode where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $desc=$obj->description;
                }
            }
            return $desc;
        }

        function checkifpaymodeexist($code){
            $code = strtoupper($code);
            $flag = 0;
            $rs = query("select * from pay_mode where upper(code)='$code'");
            if(getNumRows($rs)==1){
                $flag = 1;
            }
            return $flag;
        }

        function checkiffreightcomputationexist($code){
            $code = strtoupper($code);
            $flag = 0;
            $rs = query("select * from freight_computation where upper(code)='$code'");
            if(getNumRows($rs)==1){
                $flag = 1;
            }
            return $flag;
        }

        function getCarrierID($code){
            $id = '';
            $rs = query("select * from carrier where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }


        function getFreightCompDescription($code){
            $desc = '';
            $rs = query("select * from freight_computation where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $desc=$obj->description;
                }
            }
            return $desc;
        }



        function getOtherChargesID($code){
            $id = '';
            $rs = query("select * from other_charges where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getOtherChargesDesc($code){
            $desc = '';
            $rs = query("select * from other_charges where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $desc=$obj->description;
                }
            }
            return $desc;
        }

        function getHandlingInstructionID($code){
            $id = '';
            $rs = query("select * from handling_instruction where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $id=$obj->id;
                }
            }
            return $id;
        }

        function getHandlingInstructionDesc($code){
            $desc = '';
            $rs = query("select * from handling_instruction where upper(code)='$code'");
            if(getNumRows($rs)==1){
                while($obj=fetch($rs)){
                    $desc=$obj->description;
                }
            }
            return $desc;
        }



        function convertToText($str){
            $str = trim(mysql_real_escape_string($str));
            return $str;

        }

    	$file = $_FILES['waybilluploadtransactionfile'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
    	$ftype = substr($filename, strpos($filename, '.'));
    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

    			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
    			@$excelObj = $excelReader->load($tmp);



                $sheetcount = $excelObj->getSheetCount();
                $sheets = $excelObj->getSheetNames();
                for($i=0;$i<count($sheets);$i++){
                    $sheets[$i] = strtoupper(trim($sheets[$i]));
                }

                $checkrequiredsheets = true;
                if(!in_array('INFO', $sheets)){
                    $checkrequiredsheets = false;
                }
                if(!in_array('OTHERCHARGES', $sheets)){
                    $checkrequiredsheets = false;
                }
                if(!in_array('PACKAGEDIMENSIONS', $sheets)){
                    $checkrequiredsheets = false;
                }
                if(!in_array('PACKAGECODES', $sheets)){
                    $checkrequiredsheets = false;
                }
                if(!in_array('HANDLINGINSTRUCTIONS', $sheets)){
                    $checkrequiredsheets = false;
                }


                if($checkrequiredsheets==true){
                    $checkheaderformat = true;
                    $checkheaderremarks = array();

                    /************  CHECK HEADER FOR EACH SHEET ***********************/
                    //INFO
                    $infosheetindex = array_search('INFO', $sheets);
                    $worksheet = $excelObj->getSheet($infosheetindex);
                    $headerColumns = array(
                                    
                                    'WAYBILL NO.', //A
                                    'ORIGIN CODE', //B
                                    'DESTINATION CODE', //C
                                    'DESTINATION ROUTE', //D
                                    'PICKUP DATE', //E
                                    'ON HOLD', //F
                                    'ON HOLD REMARKS', //G
                                    'REMARKS', //H
                                    'MANIFEST NO.', //I
                                    'INVOICE NO.', //J
                                    'DOCUMENT DATE', //K
                                    'SHIPPER NO.', //L
                                    'SHIPPER TEL', //M
                                    'SHIPPER STREET', //N
                                    'SHIPPER DISTRICT', //O
                                    'SHIPPER CITY', //P
                                    'SHIPPER REGION', //Q
                                    'SHIPPER ZIP', //R
                                    'SHIPPER COUNTRY', //S
                                    'CONSIGNEE NO.', //T
                                    'CONSIGNEE TEL', //U
                                    'CONSIGNEE STREET', //V
                                    'CONSIGNEE DISTRICT', //W
                                    'CONSIGNEE CITY', //X
                                    'CONSIGNEE REGION', //Y
                                    'CONSIGNEE ZIP', //Z
                                    'CONSIGNEE COUNTRY', //AA
                                    'PICKUP STREET', //AB
                                    'PICKUP DISTRICT', //AC
                                    'PICKUP CITY', //AD
                                    'PICKUP REGION', //AE
                                    'PICKUP ZIP', //AF
                                    'PICKUP COUNTRY', //AG
                                    'SHIPMENT DESCRIPTION', //AH
                                    'NUM OF PACKAGES', //AI
                                    'DECLARED VALUE', //AJ
                                    'ACTUAL WEIGHT', //AK
                                    'VW', //AL
                                    'CBM', //AM
                                    'SERVICE', //AN
                                    'MODE OF TRANSPORT', //AO
                                    'DOCUMENT', //AP
                                    'DELIVERY INSTRUCTION', //AQ
                                    'TRANSPORT CHARGES', //AR
                                    'PAYMODE CODE', //AS
                                    'CARRIER', //AT
                                    'SHIPPER REP NAME', //AU
                                    'FREIGHT COMPUTATION', //AV
                                    'CHARGEABLE WEIGHT', //AW
                                    'VALUATION', //AX
                                    'FREIGHT CHARGES', //AY
                                    'INSURANCE CHARGES', //AZ
                                    'FUEL CHARGES', //BA
                                    'BUNKER CHARGES', //BB
                                    'MINIMUM CHARGES', //BC
                                    'ZERO RATED', //BD
                                    'VAT', //BE
                                    'TOTAL AMOUNT',//BF
                                    'AMOUNT FOR COLLECTION', //BG
                                    'DELIVERY DATE',
                                    'POD INSTRUCTION'

                                );

                    $checkHeaderInfo = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderInfo = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderInfo=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header for INFO Sheet');
                    }
                    //OTHERCHARGES
                    $otherchargessheetindex = array_search('OTHERCHARGES', $sheets);
                    $worksheet = $excelObj->getSheet($otherchargessheetindex);
                    $headerColumns = array(
                                    
                                    'WAYBILL NO.', //A
                                    'OTHER CHARGES CODE', //B
                                    'AMOUNT'
                        
                                );

                    $checkHeaderOtherCharges = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderOtherCharges = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderOtherCharges=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header for OTHERCHARGES Sheet');
                    }
                    //PACKAGEDIMENSIONS
                    $dimensionsheetindex = array_search('PACKAGEDIMENSIONS', $sheets);
                    $worksheet = $excelObj->getSheet($dimensionsheetindex);
                    $headerColumns = array(
                                    
                                    'WAYBILL NO.', //A
                                    'LENGTH', //B
                                    'WIDTH',
                                    'HEIGHT',
                                    'ACTUAL WEIGHT',
                                    'QUANTITY',
                                    'UOM CODE'
                        
                                );

                    $checkHeaderDimension = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderDimension = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderDimension=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header for PACKAGEDIMENSIONS Sheet');
                    }
                    //PACKAGECODES
                    $packagecodesheetindex = array_search('PACKAGECODES', $sheets);
                    $worksheet = $excelObj->getSheet($packagecodesheetindex);
                    $headerColumns = array(
                                    
                                    'WAYBILL NO.', //A
                                    'PACKAGE CODE'
                        
                                );

                    $checkHeaderPackageCode = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderPackageCode = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderPackageCode=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header for PACKAGECODES Sheet');
                    }
                    //HANDLINGINSTRUCTIONS
                    $handlinginstructionsheetindex = array_search('HANDLINGINSTRUCTIONS', $sheets);
                    $worksheet = $excelObj->getSheet($handlinginstructionsheetindex);
                    $headerColumns = array(
                                    
                                    'WAYBILL NO.', //A
                                    'HANDLING INSTRUCTION CODE'
                        
                                );

                    $checkHeaderHandlingInstruction = 'TRUE';
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                        if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                            $checkHeaderHandlingInstruction = 'FALSE';
                        }
                        $col++;
                    }

                    if($checkHeaderHandlingInstruction=='FALSE'){
                        $checkheaderformat = false;
                        array_push($checkheaderremarks, 'Invalid header for HANDLINGINSTRUCTIONS Sheet');
                    }


                    /*************** CHECK HEADER FOR EACH SHEET - END ****************/



                    if($checkheaderformat==true){






                                /***** INFO SHEET ******/

                                $infosheetindex = array_search('INFO', $sheets);
                    			//$worksheet = $excelObj->getActiveSheet();
                    			$worksheet = $excelObj->getSheet($infosheetindex);
                    			$lastRow = $worksheet->getHighestRow();
                    			$lastCol = $worksheet->getHighestColumn();

                    			$headerColumns = array(
                                                
                                                'WAYBILL NO.', //A
                                                'ORIGIN CODE', //B
                                                'DESTINATION CODE', //C
                                                'DESTINATION ROUTE', //D
                                                'PICKUP DATE', //E
                                                'ON HOLD', //F
                                                'ON HOLD REMARKS', //G
                                                'REMARKS', //H
                                                'MANIFEST NO.', //I
                                                'INVOICE NO.', //J
                                                'DOCUMENT DATE', //K
                                                'SHIPPER NO.', //L
                                                'SHIPPER TEL', //M
                                                'SHIPPER STREET', //N
                                                'SHIPPER DISTRICT', //O
                                                'SHIPPER CITY', //P
                                                'SHIPPER REGION', //Q
                                                'SHIPPER ZIP', //R
                                                'SHIPPER COUNTRY', //S
                                                'CONSIGNEE NO.', //T
                                                'CONSIGNEE TEL', //U
                                                'CONSIGNEE STREET', //V
                                                'CONSIGNEE DISTRICT', //W
                                                'CONSIGNEE CITY', //X
                                                'CONSIGNEE REGION', //Y
                                                'CONSIGNEE ZIP', //Z
                                                'CONSIGNEE COUNTRY', //AA
                                                'PICKUP STREET', //AB
                                                'PICKUP DISTRICT', //AC
                                                'PICKUP CITY', //AD
                                                'PICKUP REGION', //AE
                                                'PICKUP ZIP', //AF
                                                'PICKUP COUNTRY', //AG
                                                'SHIPMENT DESCRIPTION', //AH
                                                'NUM OF PACKAGES', //AI
                                                'DECLARED VALUE', //AJ
                                                'ACTUAL WEIGHT', //AK
                                                'VW', //AL
                                                'CBM', //AM
                                                'SERVICE', //AN
                                                'MODE OF TRANSPORT', //AO
                                                'DOCUMENT', //AP
                                                'DELIVERY INSTRUCTION', //AQ
                                                'TRANSPORT CHARGES', //AR
                                                'PAYMODE CODE', //AS
                                                'CARRIER', //AT
                                                'SHIPPER REP NAME', //AU
                                                'FREIGHT COMPUTATION', //AV
                                                'CHARGEABLE WEIGHT', //AW
                                                'VALUATION', //AX
                                                'FREIGHT CHARGES', //AY
                                                'INSURANCE CHARGES', //AZ
                                                'FUEL CHARGES', //BA
                                                'BUNKER CHARGES', //BB
                                                'MINIMUM CHARGES', //BC
                                                'ZERO RATED', //BD
                                                'VAT', //BE
                                                'TOTAL AMOUNT',//BF
                                                'AMOUNT FOR COLLECTION', //BG
                                                'DELIVERY DATE',
                                                'POD INSTRUCTION'

                                            );

                    			$checkHeader = 'TRUE';
                    			$col = "A";
                    			for($i=0;$i<count($headerColumns);$i++){
                    				if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                    					$checkHeader= 'FALSE';
                    				}
                    				$col++;
                    			}

                    			if($checkHeader=='TRUE'){

                                    /** ROW CHECK **/
                                    $txnwithrowerror = array();
                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $origin = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $originID = getOriginDestinationID($origin);
                                            $destination = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                            $destinationID = getOriginDestinationID($destination);
                                            $destinationroute = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                            $destinationrouteID = getDestinationRouteID($destinationroute);

                                            $cellpickupdate = $worksheet->getCell('E'.$i);
                                            $pickupdate = convertToText($worksheet->getCell('E'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($cellpickupdate)) {
                                                 $pickupdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($pickupdate)); 
                                            }
                                            $pickupdate = $pickupdate=='1970-01-01'?'':$pickupdate;
                                            

                                            $onholdflag = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                                            $onholdflag = $onholdflag=='YES'?1:0;
                                            $onholdremarks = convertToText($worksheet->getCell('G'.$i)->getValue());

                                            $remarks = convertToText($worksheet->getCell('H'.$i)->getValue());

                                            $manifest = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                                            $invoice = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));

                                            $celldocdate = $worksheet->getCell('K'.$i);
                                            $docdate = convertToText($worksheet->getCell('K'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($cellpickupdate)) {
                                                 $docdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($docdate)); 
                                            }
                                            $docdate = $docdate=='1970-01-01'?'':$docdate;

                                            $shippernum = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                                            $shipperID = getShipperID($shippernum);
                                            $shippertel = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                                            $shipperaccountname = '';
                                            $shippercompanyname = '';

                                            $consigneenum = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));
                                            $consigneeID = getConsigneeID($consigneenum);
                                            $consigneetel = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
                                            $consigneeaccountname = '';
                                            $consigneecompanyname = '';


                                            $shipmentdescription = convertToText(strtoupper($worksheet->getCell('AH'.$i)->getValue()));

                                            $numofpackage = convertToText(strtoupper($worksheet->getCell('AI'.$i)->getValue()));
                                            $declaredvalue = convertToText(strtoupper($worksheet->getCell('AJ'.$i)->getValue()));
                                            $actualweight = convertToText(strtoupper($worksheet->getCell('AK'.$i)->getValue()));
                                            $vw = convertToText(strtoupper($worksheet->getCell('AL'.$i)->getValue()));

                                            $cbm = convertToText(strtoupper($worksheet->getCell('AM'.$i)->getValue()));


                                            $servicecode = convertToText(strtoupper($worksheet->getCell('AN'.$i)->getValue()));
                                            $serviceID = getServiceID($servicecode);
                                            $modeoftransportcode = convertToText(strtoupper($worksheet->getCell('AO'.$i)->getValue()));
                                            $modeoftransportID = getModeOfTransportID($modeoftransportcode);
                                            $documentcode = convertToText(strtoupper($worksheet->getCell('AP'.$i)->getValue()));
                                            $documentID = getDocumentID($documentcode);
                                            $deliveryinstructioncode = convertToText(strtoupper($worksheet->getCell('AQ'.$i)->getValue()));
                                            $deliveryinstructionID = getDeliveryInstructionID($deliveryinstructioncode);
                                            $transportchargescode = convertToText(strtoupper($worksheet->getCell('AR'.$i)->getValue()));
                                            $transportchargesID = getTransportChargesID($transportchargescode);
                                            $paymodecode = convertToText(strtoupper($worksheet->getCell('AS'.$i)->getValue()));
                                            $paymodeflag = checkifpaymodeexist($paymodecode);

                                            $carriercode = convertToText(strtoupper($worksheet->getCell('AT'.$i)->getValue()));
                                            $carrierID = getCarrierID($carriercode);
                                            $shipperrep = convertToText(strtoupper($worksheet->getCell('AU'.$i)->getValue()));

                                            $freightcomputationcode = convertToText(strtoupper($worksheet->getCell('AV'.$i)->getValue()));
                                            $freightcompflag = checkiffreightcomputationexist($freightcomputationcode);
                                            $chargeableweight = convertToText(strtoupper($worksheet->getCell('AW'.$i)->getValue()));
                                            $valuation = convertToText(strtoupper($worksheet->getCell('AX'.$i)->getValue()));
                                            $freightcharges = convertToText(strtoupper($worksheet->getCell('AY'.$i)->getValue()));
                                            $insurancecharges = convertToText(strtoupper($worksheet->getCell('AZ'.$i)->getValue()));
                                            $fuelcharges = convertToText(strtoupper($worksheet->getCell('BA'.$i)->getValue()));
                                            $bunkercharges = convertToText(strtoupper($worksheet->getCell('BB'.$i)->getValue()));
                                            $minimumcharges = convertToText(strtoupper($worksheet->getCell('BC'.$i)->getValue()));

                                            $zeroratedflag = convertToText(strtoupper($worksheet->getCell('BD'.$i)->getValue()));
                                            $zeroratedflag = $zeroratedflag=='YES'?1:0;
                                            $vat = convertToText(strtoupper($worksheet->getCell('BE'.$i)->getValue()));
                                            $vat = $zeroratedflag==1?0:$vat;
                                            $totalamount = convertToText(strtoupper($worksheet->getCell('BF'.$i)->getValue()));
                                            $amountforcollection = convertToText(strtoupper($worksheet->getCell('BG'.$i)->getValue()));

                                            $celldeliverydate = $worksheet->getCell('BH'.$i);
                                            $deliverydate = convertToText($worksheet->getCell('BH'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($celldeliverydate)) {
                                                 $deliverydate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($deliverydate)); 
                                            }

                                            $podinstruction = convertToText(strtoupper($worksheet->getCell('BI'.$i)->getValue()));
                                                
                                            $validwaybill = false;
                                            $checkifvalidwaybillrs = query("select * from waybill_booklet_issuance 
                                            where '$waybillnumber'>=booklet_start_series and
                                                  '$waybillnumber'<=booklet_end_series and
                                                  '$docdate'<=date(validity_date) and
                                                  '$docdate'>=date(issuance_date)");
                                            if(getNumRows($checkifvalidwaybillrs)>0){
                                                $validwaybill = true;
                                            }
                                          


                                            /****** AUTO COMPUTE ******/
                                            $autocomputearray = computeWaybill($originID,$destinationID,$modeoftransportID,$vw,$cbm,$actualweight,$declaredvalue,$numofpackage,$zeroratedflag);
                                            $freightcompdesc = '';
                                            $valuation = 0;
                                            $freightcharges = 0;
                                            $insurancecharges = 0;
                                            $fuelcharges = 0;
                                            $bunkercharges = 0;
                                            $minimumcharges = 0;
                                            $chargeableweight = 0;
                                            $vat = 0;

                                            $freightcompdesc = $autocomputearray['freightcomputation'];
                                            $valuation = $autocomputearray['valuation'];
                                            $freightcharges = $autocomputearray['freightrate'];
                                            $insurancecharges = $autocomputearray['insurancerate'];
                                            $fuelcharges = $autocomputearray['fuelrate'];
                                            $bunkercharges = $autocomputearray['bunkerrate'];
                                            $minimumcharges = $autocomputearray['minimumrate'];
                                            $chargeableweight = $autocomputearray['chargeableweight'];
                                            $vat = $autocomputearray['vat'];
                                            $totalamount = $autocomputearray['totalamount'];


                                            /**** AUTO COMPUTE END ****/
                                           
                                            /*if($waybillnumber!=''&&$originID!=''&&$destinationID!=''&&$destinationrouteID!=''&&$shipperID!=''&&$consigneeID!=''&&$numofpackage>0&&$actualweight>0&&$declaredvalue>0&&$vwcbm>0&&$serviceID!=''&&$modeoftransportID!=''&&$documentID!=''&&$deliveryinstructionID!=''&&$transportchargesID!=''&&$paymodeflag==1&&$carrierID!=''&&$freightcompflag==1&&$chargeableweight>0&&$valuation>=0&&$freightcharges>=0&&$insurancecharges>=0&&$fuelcharges>=0&&$bunkercharges>=0&&$minimumcharges>=0&&$vat>=0&&$totalamount>=0){*/

                                            if($validwaybill==true&&$waybillnumber!=''&&$originID!=''&&$destinationID!=''&&$destinationrouteID!=''&&$shipperID!=''&&$consigneeID!=''&&$numofpackage>0&&$actualweight>0&&$declaredvalue>0&&$vw>0&&$cbm>0&&$serviceID!=''&&$modeoftransportID!=''&&$documentID!=''&&$deliveryinstructionID!=''&&$transportchargesID!=''&&$paymodeflag==1&&$carrierID!=''&&$amountforcollection>=0&&$deliverydate!=''){
                                                    //echo 'abc'."<br>";
                                            }
                                            else{

                                                                    if(!in_array($waybillnumber, $txnwithrowerror)){
                                                                        array_push($txnwithrowerror, $waybillnumber);
                                                                    }
                                            }
                                           
                                    }
                                    /** ROW CHECK - END **/

                                    $txninsert = array();

                                    $transactedexistingtxn = array();
                                    $successfultransactions = array();
                                    $failedtransactions = array();
                                    $transactedtransactions = array();

                                    $failedupdatetxn = array();
                                    $successfulupdatetxn = array();
                                    $successfulheaderupdatetxn = array();
                                    $failedupdatetxnremarks = array();

                                    $failedinserttxn = array();
                                    $failedinserttxnremarks = array();

                                    $successfulinserttxn = array();
                                    $successheaderinsert = array();

                                    $now = date('Y-m-d H:i:s');
                                    $userid = USERID;

                                    $waybillclass = new txn_waybill();
                                    $systemlog = new system_log();


                                    for($i=2;$i<=$lastRow;$i++){
                                            $subtotal = 0;
                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $origin = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $originID = getOriginDestinationID($origin);
                                            $destination = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                            $destinationID = getOriginDestinationID($destination);
                                            $destinationroute = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                            $destinationrouteID = getDestinationRouteID($destinationroute);

                                            $cellpickupdate = $worksheet->getCell('E'.$i);
                                            $pickupdate = convertToText($worksheet->getCell('E'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($cellpickupdate)) {
                                                 $pickupdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($pickupdate)); 
                                            }
                                            $pickupdate = $pickupdate=='1970-01-01'?'':$pickupdate;

                                            



                                            $onholdflag = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                                            $onholdflag = $onholdflag=='YES'?1:0;
                                            $onholdremarks = convertToText($worksheet->getCell('G'.$i)->getValue());

                                            $remarks = convertToText($worksheet->getCell('H'.$i)->getValue());

                                            $manifest = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                                            $invoice = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));

                                           



                                            $celldocdate = $worksheet->getCell('K'.$i);
                                            $docdate = convertToText($worksheet->getCell('K'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($cellpickupdate)) {
                                                 $docdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($docdate)); 
                                            }
                                            $docdate = $docdate=='1970-01-01'?'':$docdate;

                                            

                                            $shippernum = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                                            $shipperID = getShipperID($shippernum);
                                            $shippertel = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                                            $shipperaccountname = '';
                                            $shippercompanyname = '';
                                            $shipperstreet = '';
                                            $shipperdistrict = '';
                                            $shippercity = '';
                                            $shipperregion = '';
                                            $shipperzip = '';
                                            $shippercountry = '';
                                            $rs = query("select * from shipper where id='$shipperID'");
                                            while($obj=fetch($rs)){
                                                $shipperaccountname = $obj->account_name;
                                                $shippercompanyname = $obj->company_name;
                                                $shipperstreet = $obj->company_street_address;
                                                $shipperdistrict = $obj->company_district;
                                                $shippercity = $obj->company_city;
                                                $shipperregion = $obj->company_state_province;
                                                $shipperzip = $obj->company_zip_code;
                                                $shippercountry = $obj->company_country;
                                            }

                                            $consigneenum = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));
                                            $consigneeID = getConsigneeID($consigneenum);
                                            $consigneetel = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
                                            $consigneeaccountname = '';
                                            $consigneecompanyname = '';
                                            $consigneestreet = '';
                                            $consigneedistrict = '';
                                            $consigneecity = '';
                                            $consigneeregion = '';
                                            $consigneezip = '';
                                            $consigneecountry = '';
                                            $rs = query("select * from consignee where id='$consigneeID'");
                                            while($obj=fetch($rs)){
                                                $consigneeaccountname = $obj->account_name;
                                                $consigneecompanyname = $obj->company_name;
                                                $consigneestreet = $obj->company_street_address;
                                                $consigneedistrict = $obj->company_district;
                                                $consigneecity = $obj->company_city;
                                                $consigneeregion = $obj->company_state_province;
                                                $consigneezip = $obj->company_zip_code;
                                                $consigneecountry = $obj->company_country;
                                            }



                                            $pickupstreet = convertToText(strtoupper($worksheet->getCell('AB'.$i)->getValue()));;
                                            $pickupdistrict = convertToText(strtoupper($worksheet->getCell('AC'.$i)->getValue()));;
                                            $pickupcity = convertToText(strtoupper($worksheet->getCell('AD'.$i)->getValue()));;
                                            $pickupregion = convertToText(strtoupper($worksheet->getCell('AE'.$i)->getValue()));;
                                            $pickupzip = convertToText(strtoupper($worksheet->getCell('AF'.$i)->getValue()));;
                                            $pickupcountry = convertToText(strtoupper($worksheet->getCell('AG'.$i)->getValue()));;


                                            $shipmentdescription = convertToText(strtoupper($worksheet->getCell('AH'.$i)->getValue()));

                                            $numofpackage = convertToText(strtoupper($worksheet->getCell('AI'.$i)->getValue()));
                                            $declaredvalue = convertToText(strtoupper($worksheet->getCell('AJ'.$i)->getValue()));
                                            $actualweight = convertToText(strtoupper($worksheet->getCell('AK'.$i)->getValue()));
                                            $vw = convertToText(strtoupper($worksheet->getCell('AL'.$i)->getValue()));

                                            $cbm = convertToText(strtoupper($worksheet->getCell('AM'.$i)->getValue()));
                                            $servicecode = convertToText(strtoupper($worksheet->getCell('AN'.$i)->getValue()));
                                            $serviceID = getServiceID($servicecode);
                                            $modeoftransportcode = convertToText(strtoupper($worksheet->getCell('AO'.$i)->getValue()));
                                            $modeoftransportID = getModeOfTransportID($modeoftransportcode);
                                            $documentcode = convertToText(strtoupper($worksheet->getCell('AP'.$i)->getValue()));
                                            $documentID = getDocumentID($documentcode);
                                            $deliveryinstructioncode = convertToText(strtoupper($worksheet->getCell('AQ'.$i)->getValue()));
                                            $deliveryinstructionID = getDeliveryInstructionID($deliveryinstructioncode);
                                            $transportchargescode = convertToText(strtoupper($worksheet->getCell('AR'.$i)->getValue()));
                                            $transportchargesID = getTransportChargesID($transportchargescode);
                                            $paymodecode = convertToText(strtoupper($worksheet->getCell('AS'.$i)->getValue()));
                                            $paymodeflag = checkifpaymodeexist($paymodecode);
                                            $paymodedesc = getPayModeDescription($paymodecode);

                                            $carriercode = convertToText(strtoupper($worksheet->getCell('AT'.$i)->getValue()));
                                            $carrierID = getCarrierID($carriercode);
                                            $shipperrep = convertToText(strtoupper($worksheet->getCell('AU'.$i)->getValue()));

                                            $freightcomputationcode = convertToText(strtoupper($worksheet->getCell('AV'.$i)->getValue()));
                                            $freightcompflag = checkiffreightcomputationexist($freightcomputationcode);
                                            $freightcompdesc = getFreightCompDescription($freightcomputationcode);
                                            $chargeableweight = convertToText(strtoupper($worksheet->getCell('AW'.$i)->getValue()));
                                            $valuation = convertToText(strtoupper($worksheet->getCell('AX'.$i)->getValue()));
                                            $freightcharges = convertToText(strtoupper($worksheet->getCell('AY'.$i)->getValue()));
                                            $insurancecharges = convertToText(strtoupper($worksheet->getCell('AZ'.$i)->getValue()));
                                            $fuelcharges = convertToText(strtoupper($worksheet->getCell('BA'.$i)->getValue()));
                                            $bunkercharges = convertToText(strtoupper($worksheet->getCell('BB'.$i)->getValue()));
                                            $minimumcharges = convertToText(strtoupper($worksheet->getCell('BC'.$i)->getValue()));



                                            $zeroratedflag = convertToText(strtoupper($worksheet->getCell('BD'.$i)->getValue()));
                                            $zeroratedflag = $zeroratedflag=='YES'?1:0;
                                            $vat = convertToText(strtoupper($worksheet->getCell('BE'.$i)->getValue()));
                                            $vat = $zeroratedflag==1?0:$vat;
                                            $totalamount = convertToText(strtoupper($worksheet->getCell('BF'.$i)->getValue()));
                                            $amountforcollection = convertToText(strtoupper($worksheet->getCell('BG'.$i)->getValue()));

                                            $celldeliverydate = $worksheet->getCell('BH'.$i);
                                            $deliverydate = convertToText($worksheet->getCell('BH'.$i)->getValue());
                                            if(PHPExcel_Shared_Date::isDateTime($celldeliverydate)) {
                                                 $deliverydate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($deliverydate)); 
                                            }
                                            $deliverydate = $deliverydate=='1970-01-01'?'':$deliverydate;

                                            $podinstruction = convertToText(strtoupper($worksheet->getCell('BI'.$i)->getValue()));


                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }


                                            /****** AUTO COMPUTE ******/
                                            $autocomputearray = computeWaybill($originID,$destinationID,$modeoftransportID,$vw,$cbm,$actualweight,$declaredvalue,$numofpackage,$zeroratedflag);
                                            $freightcompdesc = '';
                                            $valuation = 0;
                                            $freightcharges = 0;
                                            $insurancecharges = 0;
                                            $fuelcharges = 0;
                                            $bunkercharges = 0;
                                            $minimumcharges = 0;
                                            $chargeableweight = 0;
                                            $vat = 0;

                                            $freightcompdesc = $autocomputearray['freightcomputation'];
                                            $valuation = $autocomputearray['valuation'];
                                            $freightcharges = $autocomputearray['freightrate'];
                                            $insurancecharges = $autocomputearray['insurancerate'];
                                            $fuelcharges = $autocomputearray['fuelrate'];
                                            $bunkercharges = $autocomputearray['bunkerrate'];
                                            $minimumcharges = $autocomputearray['minimumrate'];
                                            $chargeableweight = $autocomputearray['chargeableweight'];
                                            $vat = $autocomputearray['vat'];
                                            $totalamount = $autocomputearray['totalamount'];

                                            /**** AUTO COMPUTE END ****/


                                            $subtotal = $valuation+$freightcharges+$insurancecharges+$fuelcharges+$bunkercharges;


                                            if($waybillindb==false){

                                                $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                                if(getNumRows($checktxnrs)==0){

                                                            if(!in_array($waybillnumber, $txnwithrowerror)){

                                                                if(!in_array($waybillnumber, $txninsert)){
                                                                        array_push($txninsert, $waybillnumber);
                                                                }

                                                               

                                                                if($subtotal<$minimumcharges){
                                                                    $subtotal = $minimumcharges;
                                                                }

                                                                $rs = $waybillclass->insert(
                                                                                            array(    '',
                                                                                                      $waybillnumber,
                                                                                                      'LOGGED',
                                                                                                      'NULL',
                                                                                                      $originID,
                                                                                                      $destinationID,
                                                                                                      $destinationrouteID,
                                                                                                      $pickupdate,
                                                                                                      $onholdflag,
                                                                                                      $onholdremarks,
                                                                                                      $remarks,
                                                                                                      $now,
                                                                                                      $userid,
                                                                                                      'NULL',
                                                                                                      'NULL',
                                                                                                      $docdate,
                                                                                                      'NULL',
                                                                                                      'NULL',
                                                                                                      $shipperID,
                                                                                                      $shippernum,
                                                                                                      $shipperaccountname,
                                                                                                      $shippertel,
                                                                                                      $shippercompanyname,
                                                                                                      $shipperstreet,
                                                                                                      $shipperdistrict,
                                                                                                      $shippercity,
                                                                                                      $shipperregion,
                                                                                                      $shipperzip,
                                                                                                      $shippercountry,
                                                                                                      $consigneeID,
                                                                                                      $consigneenum,
                                                                                                      $consigneeaccountname,
                                                                                                      $consigneetel,
                                                                                                      $consigneecompanyname,
                                                                                                      $consigneestreet,
                                                                                                      $consigneedistrict,
                                                                                                      $consigneecity,
                                                                                                      $consigneeregion,
                                                                                                      $consigneezip,
                                                                                                      $consigneecountry,
                                                                                                      $pickupstreet,
                                                                                                      $pickupdistrict,
                                                                                                      $pickupcity,
                                                                                                      $pickupregion,
                                                                                                      $pickupzip,
                                                                                                      $pickupcountry,
                                                                                                      $shipmentdescription,
                                                                                                      $numofpackage,
                                                                                                      $declaredvalue,
                                                                                                      $actualweight,
                                                                                                      $cbm,
                                                                                                      $chargeableweight,
                                                                                                      $freightcharges,
                                                                                                      $valuation,
                                                                                                      $serviceID,
                                                                                                      $modeoftransportID,
                                                                                                      $documentID,
                                                                                                      $deliveryinstructionID,
                                                                                                      'NULL',
                                                                                                      $transportchargesID,
                                                                                                      $paymodedesc,
                                                                                                      $vat,
                                                                                                      $zeroratedflag,
                                                                                                      $totalamount,
                                                                                                      $carrierID,
                                                                                                      $shipperrep,
                                                                                                      $freightcompdesc,
                                                                                                      $insurancecharges,
                                                                                                      $fuelcharges,
                                                                                                      $bunkercharges,
                                                                                                      $minimumcharges,
                                                                                                      $subtotal,
                                                                                                      $deliverydate,
                                                                                                      $vw,
                                                                                                      'NULL',
                                                                                                      $podinstruction,
                                                                                                      $amountforcollection


                                                                                                     
                                                                                                 )
                                                                                          );
                                                                

                    
                                                               
                                                                if($rs){


                                                                    $wbID = $waybillclass->getInsertId();
                                                                    query("update txn_waybill set uploaded_from='$filename' where id='$wbID'");

                                                                    $systemlog->logAddedInfo($waybillclass,array($wbID,
                                                                                                                  $waybillnumber,
                                                                                                                  'LOGGED',
                                                                                                                  'NULL',
                                                                                                                  $originID,
                                                                                                                  $destinationID,
                                                                                                                  $destinationrouteID,
                                                                                                                  $pickupdate,
                                                                                                                  $onholdflag,
                                                                                                                  $onholdremarks,
                                                                                                                  $remarks,
                                                                                                                  $now,
                                                                                                                  $userid,
                                                                                                                  'NULL',
                                                                                                                  'NULL',
                                                                                                                  $docdate,
                                                                                                                  'NULL',
                                                                                                                  'NULL',
                                                                                                                  $shipperID,
                                                                                                                  $shippernum,
                                                                                                                  $shipperaccountname,
                                                                                                                  $shippertel,
                                                                                                                  $shippercompanyname,
                                                                                                                  $shipperstreet,
                                                                                                                  $shipperdistrict,
                                                                                                                  $shippercity,
                                                                                                                  $shipperregion,
                                                                                                                  $shipperzip,
                                                                                                                  $shippercountry,
                                                                                                                  $consigneeID,
                                                                                                                  $consigneenum,
                                                                                                                  $consigneeaccountname,
                                                                                                                  $consigneetel,
                                                                                                                  $consigneecompanyname,
                                                                                                                  $consigneestreet,
                                                                                                                  $consigneedistrict,
                                                                                                                  $consigneecity,
                                                                                                                  $consigneeregion,
                                                                                                                  $consigneezip,
                                                                                                                  $consigneecountry,
                                                                                                                  $pickupstreet,
                                                                                                                  $pickupdistrict,
                                                                                                                  $pickupcity,
                                                                                                                  $pickupregion,
                                                                                                                  $pickupzip,
                                                                                                                  $pickupcountry,
                                                                                                                  $shipmentdescription,
                                                                                                                  $numofpackage,
                                                                                                                  $declaredvalue,
                                                                                                                  $actualweight,
                                                                                                                  $cbm,
                                                                                                                  $chargeableweight,
                                                                                                                  $freightcharges,
                                                                                                                  $valuation,
                                                                                                                  $serviceID,
                                                                                                                  $modeoftransportID,
                                                                                                                  $documentID,
                                                                                                                  $deliveryinstructionID,
                                                                                                                  'NULL',
                                                                                                                  $transportchargesID,
                                                                                                                  $paymodedesc,
                                                                                                                  $vat,
                                                                                                                  $zeroratedflag,
                                                                                                                  $totalamount,
                                                                                                                  $carrierID,
                                                                                                                  $shipperrep,
                                                                                                                  $freightcompdesc,
                                                                                                                  $insurancecharges,
                                                                                                                  $fuelcharges,
                                                                                                                  $bunkercharges,
                                                                                                                  $minimumcharges,
                                                                                                                  $subtotal,
                                                                                                                  $deliverydate,
                                                                                                                  $vw,
                                                                                                                  'NULL',
                                                                                                                  $podinstruction,
                                                                                                                  $amountforcollection
                                                                             ),'WAYBILL','New Waybill Transaction Added (Upload)',$userid,$now);

                                                                    if(!in_array($waybillnumber, $successheaderinsert)){
                                                                        array_push($successheaderinsert, $waybillnumber);
                                                                    }
                                                                }

                                                            }
                                                            else{

                                                                    
                                                                    if($validwaybill==true&&$waybillnumber!=''&&$originID!=''&&$destinationID!=''&&$destinationrouteID!=''&&$shipperID!=''&&$consigneeID!=''&&$numofpackage>0&&$actualweight>0&&$declaredvalue>0&&$vw>0&&$cbm>0&&$serviceID!=''&&$modeoftransportID!=''&&$documentID!=''&&$deliveryinstructionID!=''&&$transportchargesID!=''&&$paymodeflag==1&&$carrierID!=''&&$deliverydate!=''){

                                                                        //echo "sdkfjsljflksdjf<br>";
                                                                    }
                                                                    else{

                                                                        $errorremarks = array("Waybill Number->$waybillnumber");
                                                                        if($waybillnumber==''){
                                                                            array_push($errorremarks, "No waybill number provided");
                                                                        }
                                                                        if($validwaybill==false){
                                                                            array_push($errorremarks, "Invalid Waybill Number: $waybillnumber");
                                                                        }
                                                                        if($originID==''){
                                                                            array_push($errorremarks, "Invalid Origin: $origin");
                                                                        }
                                                                        if($destinationID==''){
                                                                            array_push($errorremarks, "Invalid Destination: $destination");
                                                                        }
                                                                        if($destinationrouteID==''){
                                                                            array_push($errorremarks, "Invalid Destination Route: $destinationroute");
                                                                        }
                                                                        if($shipperID==''){
                                                                            array_push($errorremarks, "Invalid Shipper: $shippernum");
                                                                        }
                                                                        if($consigneeID==''){
                                                                            array_push($errorremarks, "Invalid Consignee: $consigneenum");
                                                                        }
                                                                        if(!$numofpackage>0){
                                                                            array_push($errorremarks, "Invalid No. of Package (x>0): $numofpackage");
                                                                        }
                                                                        if(!$actualweight>0){
                                                                            array_push($errorremarks, "Invalid Actual Weight (x>0): $actualweight");
                                                                        }
                                                                        if(!$declaredvalue>0){
                                                                            array_push($errorremarks, "Invalid Declared Value (x>0): $declaredvalue");
                                                                        }
                                                                        if(!$vw>0){
                                                                            array_push($errorremarks, "Invalid vw (x>0): $vw");
                                                                        }
                                                                        if(!$cbm>0){
                                                                            array_push($errorremarks, "Invalid cbm (x>0): $cbm");
                                                                        }
                                                                        if($serviceID==''){
                                                                            array_push($errorremarks, "Invalid Service: $servicecode");
                                                                        }
                                                                        if($modeoftransportID==''){
                                                                            array_push($errorremarks, "Invalid Mode of Transport: $modeoftransportcode");
                                                                        }
                                                                        if($documentID==''){
                                                                            array_push($errorremarks, "Invalid Document: $documentcode");
                                                                        }
                                                                        if($deliveryinstructionID==''){
                                                                            array_push($errorremarks, "Invalid Delivery Instruction: $deliveryinstructioncode");
                                                                        }
                                                                        if($transportchargesID==''){
                                                                            array_push($errorremarks, "Invalid Transport Charges: $transportchargescode");
                                                                        }
                                                                        if(!$paymodeflag==1){
                                                                            array_push($errorremarks, "Invalid Paymode: $paymodecode");
                                                                        }
                                                                        if($carrierID==''){
                                                                            array_push($errorremarks, "Invalid Carrier: $carriercode");
                                                                        }
                                                                        if(!$freightcompflag==1){
                                                                            array_push($errorremarks, "Invalid Freight Computation: $freightcomputationcode");
                                                                        }
                                                                        if(!$chargeableweight>0){
                                                                            array_push($errorremarks, "Invalid Chargeable Weight (x>0): $chargeableweight");
                                                                        }
                                                                        if(!$valuation>=0){
                                                                            array_push($errorremarks, "Invalid Valuation (x>=0): $valuation");
                                                                        }
                                                                        if(!$freightcharges>=0){
                                                                            array_push($errorremarks, "Invalid Freight Charges (x>=0): $freightcharges");
                                                                        }
                                                                        if(!$insurancecharges>=0){
                                                                            array_push($errorremarks, "Invalid Insurance Charges (x>=0): $insurancecharges");
                                                                        }
                                                                        if(!$fuelcharges>=0){
                                                                            array_push($errorremarks, "Invalid Fuel Charges (x>=0): $fuelcharges");
                                                                        }
                                                                        if(!$bunkercharges>=0){
                                                                            array_push($errorremarks, "Invalid Bunker Charges (x>=0): $bunkercharges");
                                                                        }
                                                                        if(!$minimumcharges>=0){
                                                                            array_push($errorremarks, "Invalid Minimum Charges (x>=0): $minimumcharges");
                                                                        }
                                                                        if(!$vat>=0){
                                                                            array_push($errorremarks, "Invalid VAT (x>=0): $vat");
                                                                        }
                                                                        if(!$totalamount>=0){
                                                                            array_push($errorremarks, "Invalid Total Amount (x>=0): $totalamount");
                                                                        }
                                                                        if(!$amountforcollection>=0){
                                                                            array_push($errorremarks, "Invalid Amount for Collection (x>=0): $amountforcollection");
                                                                        }

                                                                        if($deliverydate==''){
                                                                            array_push($errorremarks, "Invalid Delivery Date: $deliverydate");
                                                                        }





                                                                        $errorremarks = implode('  |  ', $errorremarks);
                                                                        array_push($failedinserttxnremarks, "Line $i: ".$errorremarks);

                                                                        if(!in_array($waybillnumber, $failedinserttxn)){
                                                                            array_push($failedinserttxn, $waybillnumber);
                                                                        }

                                                                    }
                                                            }


                                                }

                                            }
                                            else if(!in_array($waybillnumber, $txnwithrowerror)){



                                                    if($waybillnumber!=''&&$originID!=''&&$destinationID!=''&&$destinationrouteID!=''&&$shipperID!=''&&$consigneeID!=''&&$numofpackage>0&&$actualweight>0&&$declaredvalue>0&&$vw>0&&$cbm>0&&$serviceID!=''&&$modeoftransportID!=''&&$documentID!=''&&$deliveryinstructionID!=''&&$transportchargesID!=''&&$paymodeflag==1&&$carrierID!=''&&$deliverydate!=''){


                                                                    if(!in_array($waybillnumber, $transactedexistingtxn)){
                                                                        array_push($transactedexistingtxn, $waybillnumber);
                                                                    }

                                                                    if(!in_array($waybillnumber, $successfulheaderupdatetxn)){
                                                                        array_push($successfulheaderupdatetxn, $waybillnumber);
                                                                       
                                                                    }

                                                                    $systemlog->logEditedInfo($waybillclass,$waybillID,array(
                                                                                                                      $waybillID,
                                                                                                                      $waybillnumber,
                                                                                                                      'NOCHANGE',
                                                                                                                      'NOCHANGE',
                                                                                                                      $originID,
                                                                                                                      $destinationID,
                                                                                                                      $destinationrouteID,
                                                                                                                      $pickupdate,
                                                                                                                      $onholdflag,
                                                                                                                      $onholdremarks,
                                                                                                                      $remarks,
                                                                                                                      $now,
                                                                                                                      $userid,
                                                                                                                      'NULL',
                                                                                                                      'NULL',
                                                                                                                      $docdate,
                                                                                                                      'NULL',
                                                                                                                      'NULL',
                                                                                                                      $shipperID,
                                                                                                                      $shippernum,
                                                                                                                      $shipperaccountname,
                                                                                                                      $shippertel,
                                                                                                                      $shippercompanyname,
                                                                                                                      $shipperstreet,
                                                                                                                      $shipperdistrict,
                                                                                                                      $shippercity,
                                                                                                                      $shipperregion,
                                                                                                                      $shipperzip,
                                                                                                                      $shippercountry,
                                                                                                                      $consigneeID,
                                                                                                                      $consigneenum,
                                                                                                                      $consigneeaccountname,
                                                                                                                      $consigneetel,
                                                                                                                      $consigneecompanyname,
                                                                                                                      $consigneestreet,
                                                                                                                      $consigneedistrict,
                                                                                                                      $consigneecity,
                                                                                                                      $consigneeregion,
                                                                                                                      $consigneezip,
                                                                                                                      $consigneecountry,
                                                                                                                      $pickupstreet,
                                                                                                                      $pickupdistrict,
                                                                                                                      $pickupcity,
                                                                                                                      $pickupregion,
                                                                                                                      $pickupzip,
                                                                                                                      $pickupcountry,
                                                                                                                      $shipmentdescription,
                                                                                                                      $numofpackage,
                                                                                                                      $declaredvalue,
                                                                                                                      $actualweight,
                                                                                                                      $cbm,
                                                                                                                      $chargeableweight,
                                                                                                                      $freightcharges,
                                                                                                                      $valuation,
                                                                                                                      $serviceID,
                                                                                                                      $modeoftransportID,
                                                                                                                      $documentID,
                                                                                                                      $deliveryinstructionID,
                                                                                                                      'NULL',
                                                                                                                      $transportchargesID,
                                                                                                                      $paymodedesc,
                                                                                                                      $vat,
                                                                                                                      $zeroratedflag,
                                                                                                                      $totalamount,
                                                                                                                      $carrierID,
                                                                                                                      $shipperrep,
                                                                                                                      $freightcompdesc,
                                                                                                                      $insurancecharges,
                                                                                                                      $fuelcharges,
                                                                                                                      $bunkercharges,
                                                                                                                      $minimumcharges,
                                                                                                                      $subtotal,
                                                                                                                      $deliverydate,
                                                                                                                      $vw,
                                                                                                                      'NULL',
                                                                                                                      $podinstruction,
                                                                                                                      $amountforcollection
                                                                             ),'WAYBILL','Edited Waybill Transaction (Upload)',$userid,$now);/// log should be before update is made
                                                                    

                                                                    $waybillclass->update($waybillID,array($waybillnumber,
                                                                                                              'NOCHANGE',
                                                                                                              'NOCHANGE',
                                                                                                              $originID,
                                                                                                              $destinationID,
                                                                                                              $destinationrouteID,
                                                                                                              $pickupdate,
                                                                                                              $onholdflag,
                                                                                                              $onholdremarks,
                                                                                                              $remarks,
                                                                                                              $now,
                                                                                                              $userid,
                                                                                                              'NULL',
                                                                                                              'NULL',
                                                                                                              $docdate,
                                                                                                              'NULL',
                                                                                                              'NULL',
                                                                                                              $shipperID,
                                                                                                              $shippernum,
                                                                                                              $shipperaccountname,
                                                                                                              $shippertel,
                                                                                                              $shippercompanyname,
                                                                                                              $shipperstreet,
                                                                                                              $shipperdistrict,
                                                                                                              $shippercity,
                                                                                                              $shipperregion,
                                                                                                              $shipperzip,
                                                                                                              $shippercountry,
                                                                                                              $consigneeID,
                                                                                                              $consigneenum,
                                                                                                              $consigneeaccountname,
                                                                                                              $consigneetel,
                                                                                                              $consigneecompanyname,
                                                                                                              $consigneestreet,
                                                                                                              $consigneedistrict,
                                                                                                              $consigneecity,
                                                                                                              $consigneeregion,
                                                                                                              $consigneezip,
                                                                                                              $consigneecountry,
                                                                                                              $pickupstreet,
                                                                                                              $pickupdistrict,
                                                                                                              $pickupcity,
                                                                                                              $pickupregion,
                                                                                                              $pickupzip,
                                                                                                              $pickupcountry,
                                                                                                              $shipmentdescription,
                                                                                                              $numofpackage,
                                                                                                              $declaredvalue,
                                                                                                              $actualweight,
                                                                                                              $cbm,
                                                                                                              $chargeableweight,
                                                                                                                  $freightcharges,
                                                                                                                  $valuation,
                                                                                                                  $serviceID,
                                                                                                                  $modeoftransportID,
                                                                                                                  $documentID,
                                                                                                                  $deliveryinstructionID,
                                                                                                                  'NULL',
                                                                                                                  $transportchargesID,
                                                                                                                  $paymodedesc,
                                                                                                                  $vat,
                                                                                                                  $zeroratedflag,
                                                                                                                  $totalamount,
                                                                                                                  $carrierID,
                                                                                                                  $shipperrep,
                                                                                                                  $freightcompdesc,
                                                                                                                  $insurancecharges,
                                                                                                                  $fuelcharges,
                                                                                                                  $bunkercharges,
                                                                                                                  $minimumcharges,
                                                                                                                  $subtotal,
                                                                                                                  $deliverydate,
                                                                                                                  $vw,
                                                                                                                  'NULL',
                                                                                                                  $podinstruction,
                                                                                                                  $amountforcollection
                                                                                            ));
                                                    }
                                                                
                                                   
                                            }
                                            else{

                                                                        $errorremarks = array("Waybill Number->$waybillnumber");
                                                                        if($waybillnumber==''){
                                                                            array_push($errorremarks, "No waybill number provided");
                                                                        }
                                                                        if($originID==''){
                                                                            array_push($errorremarks, "Invalid Origin: $origin");
                                                                        }
                                                                        if($destinationID==''){
                                                                            array_push($errorremarks, "Invalid Destination: $destination");
                                                                        }
                                                                        if($destinationrouteID==''){
                                                                            array_push($errorremarks, "Invalid Destination Route: $destinationroute");
                                                                        }
                                                                        if($shipperID==''){
                                                                            array_push($errorremarks, "Invalid Shipper: $shippernum");
                                                                        }
                                                                        if($consigneeID==''){
                                                                            array_push($errorremarks, "Invalid Consignee: $consigneenum");
                                                                        }
                                                                        if(!$numofpackage>0){
                                                                            array_push($errorremarks, "Invalid No. of Package (x>0): $numofpackage");
                                                                        }
                                                                        if(!$actualweight>0){
                                                                            array_push($errorremarks, "Invalid Actual Weight (x>0): $actualweight");
                                                                        }
                                                                        if(!$declaredvalue>0){
                                                                            array_push($errorremarks, "Invalid Declared Value (x>0): $declaredvalue");
                                                                        }
                                                                        if(!$vw>0){
                                                                            array_push($errorremarks, "Invalid vw (x>0): $vw");
                                                                        }
                                                                        if(!$cbm>0){
                                                                            array_push($errorremarks, "Invalid cbm (x>0): $cbm");
                                                                        }
                                                                        if($serviceID==''){
                                                                            array_push($errorremarks, "Invalid Service: $servicecode");
                                                                        }
                                                                        if($modeoftransportID==''){
                                                                            array_push($errorremarks, "Invalid Mode of Transport: $modeoftransportcode");
                                                                        }
                                                                        if($documentID==''){
                                                                            array_push($errorremarks, "Invalid Document: $documentcode");
                                                                        }
                                                                        if($deliveryinstructionID==''){
                                                                            array_push($errorremarks, "Invalid Delivery Instruction: $deliveryinstructioncode");
                                                                        }
                                                                        if($transportchargesID==''){
                                                                            array_push($errorremarks, "Invalid Transport Charges: $transportchargescode");
                                                                        }
                                                                        if(!$paymodeflag==1){
                                                                            array_push($errorremarks, "Invalid Paymode: $paymodecode");
                                                                        }
                                                                        if($carrierID==''){
                                                                            array_push($errorremarks, "Invalid Carrier: $carriercode");
                                                                        }
                                                                        if(!$freightcompflag==1){
                                                                            array_push($errorremarks, "Invalid Freight Computation: $freightcomputationcode");
                                                                        }
                                                                        if(!$chargeableweight>0){
                                                                            array_push($errorremarks, "Invalid Chargeable Weight (x>0): $chargeableweight");
                                                                        }
                                                                        if(!$valuation>=0){
                                                                            array_push($errorremarks, "Invalid Valuation (x>=0): $valuation");
                                                                        }
                                                                        if(!$freightcharges>=0){
                                                                            array_push($errorremarks, "Invalid Freight Charges (x>=0): $freightcharges");
                                                                        }
                                                                        if(!$insurancecharges>=0){
                                                                            array_push($errorremarks, "Invalid Insurance Charges (x>=0): $insurancecharges");
                                                                        }
                                                                        if(!$fuelcharges>=0){
                                                                            array_push($errorremarks, "Invalid Fuel Charges (x>=0): $fuelcharges");
                                                                        }
                                                                        if(!$bunkercharges>=0){
                                                                            array_push($errorremarks, "Invalid Bunker Charges (x>=0): $bunkercharges");
                                                                        }
                                                                        if(!$minimumcharges>=0){
                                                                            array_push($errorremarks, "Invalid Minimum Charges (x>=0): $minimumcharges");
                                                                        }
                                                                        if(!$vat>=0){
                                                                            array_push($errorremarks, "Invalid VAT (x>=0): $vat");
                                                                        }
                                                                        if(!$totalamount>=0){
                                                                            array_push($errorremarks, "Invalid Total Amount (x>=0): $totalamount");
                                                                        }
                                                                        if(!$amountforcollection>=0){
                                                                            array_push($errorremarks, "Invalid Amount for Collection (x>=0): $amountforcollection");
                                                                        }

                                                                        if($deliverydate==''){
                                                                            array_push($errorremarks, "Invalid Delivery Date: $deliverydate");
                                                                        }

                                                                        $errorremarks = implode('  |  ', $errorremarks);

                                                                        array_push($failedupdatetxnremarks, "Line $i: ".$errorremarks);

                                                                        if(!in_array($waybillnumber, $failedupdatetxn)){
                                                                            array_push($failedupdatetxn, $waybillnumber);
                                                                        }

                                            }
                                           


                                          
                                    }

                                    $insertsuccess = array_diff($txninsert, $failedinserttxn);
                                    $updatesuccess = array_diff($successfulheaderupdatetxn, $failedupdatetxn);

                                    echo "UPLOAD: <br><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>UPLOADED WAYBILL(S)</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($insertsuccess);$x++){
                                                    echo "<tr><td>$line</td><td>".$insertsuccess[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";


                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>WAYBILL NUMBER(S) NOT UPLOADED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($failedinserttxn);$x++){
                                                    echo "<tr><td>$line</td><td>".$failedinserttxn[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>FAILED UPLOAD REMARKS</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($failedinserttxnremarks);$x++){
                                                    echo "<tr><td>$line</td><td>".$failedinserttxnremarks[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br><br><br>";

                                    echo "UPDATE: <br><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>UPDATED WAYBILL NUMBER(S)</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($transactedexistingtxn);$x++){
                                                    echo "<tr><td>$line</td><td>".$transactedexistingtxn[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";


                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>WAYBILL NUMBER(S) NOT UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($failedupdatetxn);$x++){
                                                    echo "<tr><td>$line</td><td>".$failedupdatetxn[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>FAILED UPDATE REMARKS</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($failedupdatetxnremarks);$x++){
                                                    echo "<tr><td>$line</td><td>".$failedupdatetxnremarks[$x]."</td></tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                    			}
                    			else{
                    				echo "Unable to upload file. <b>INFO SHEET: Invalid header format.</b><br><br>";

                    				echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                    				
                    			}
                                /***** INFO SHEET - END *****/





                                /***** OTHER CHARGES SHEET ****/
                                $otherchargessheetindex = array_search('OTHERCHARGES', $sheets);
                                //$worksheet = $excelObj->getActiveSheet();
                                $worksheet = $excelObj->getSheet($otherchargessheetindex);
                                $lastRow = $worksheet->getHighestRow();
                                $lastCol = $worksheet->getHighestColumn();

                                $headerColumns = array(
                                                
                                                'WAYBILL NO.', //A
                                                'OTHER CHARGES CODE', //B
                                                'AMOUNT'
                                    
                                            );

                                $checkHeader = 'TRUE';
                                $col = "A";
                                for($i=0;$i<count($headerColumns);$i++){
                                    if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                                        $checkHeader= 'FALSE';
                                    }
                                    $col++;
                                }

                                if($checkHeader=='TRUE'){

                                    $txnwithrowerror1 = array();

                                    $transactedtxn = array();

                                    $newtxn = array();

                                    $updatetxn = array();

                                    $deletedpreviouscharges = array();

                                    $insertedcharges = array();
                                    $updatedcharges = array();


                                    $notinsertedcharges = array();
                                    $notupdatedcharges = array();

                                    
                                    $errorremarksarray = array();

                                    $needrecomputation = array();

                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $otherchargescode = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $otherchargesID = getOtherChargesID($otherchargescode);
                                            $otherchargesDesc = getOtherChargesDesc($otherchargescode);
                                            $amount = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }


                                           if($waybillindb==true&&$otherchargesID!=''&&$amount>0){

                                           }
                                           else{
                                               if(!in_array($waybillnumber, $txnwithrowerror1)){
                                                        array_push($txnwithrowerror1, $waybillnumber);
                                               }
                                            }

                                    }

                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $otherchargescode = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $otherchargesID = getOtherChargesID($otherchargescode);
                                            $otherchargesDesc = getOtherChargesDesc($otherchargescode);
                                            $amount = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }
                                           





                                           if($waybillindb==true&&!in_array($waybillnumber, $txnwithrowerror1)&&$otherchargesID!=''&&$amount>0){

                                                $checkifnewwaybillrs = query("select * from txn_waybill_other_charges where waybill_number='$waybillnumber'");

                                                if(getNumRows($checkifnewwaybillrs)==0){
                                                    if(!in_array($waybillnumber, $newtxn)){
                                                        array_push($newtxn, $waybillnumber);
                                                    }
                                                }


                                                if(in_array($waybillnumber, $newtxn)){


                                                        query("insert into txn_waybill_other_charges(
                                                                                                        waybill_number,
                                                                                                        description,
                                                                                                        amount
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$otherchargesDesc',
                                                                                                        '$amount'
                                                                                                    )");

                                                        $rowinsertcharges = array($waybillnumber,$otherchargesDesc,$amount);
                                                        //if(!in_array($rowinsertcharges, $insertedcharges)){
                                                            array_push($insertedcharges, $rowinsertcharges);
                                                        //}

                                                        if(!in_array($waybillnumber, $needrecomputation)){

                                                            array_push($needrecomputation, $waybillnumber);
                                                        }
                                                }
                                                else{

                                                        if(!in_array($waybillnumber, $deletedpreviouscharges)){
                                                                query("delete from txn_waybill_other_charges where waybill_number='$waybillnumber'");
                                                                array_push($deletedpreviouscharges, $waybillnumber);
                                                        }

                                                        query("insert into txn_waybill_other_charges(
                                                                                                        waybill_number,
                                                                                                        description,
                                                                                                        amount
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$otherchargesDesc',
                                                                                                        '$amount'
                                                                                                    )");

                                                        $rowupdatecharges = array($waybillnumber,$otherchargesDesc,$amount);
                                                        //if(!in_array($rowupdatecharges, $updatedcharges)){
                                                            array_push($updatedcharges, $rowupdatecharges);
                                                        //}

                                                        if(!in_array($waybillnumber, $needrecomputation)){
                                                            array_push($needrecomputation, $waybillnumber);
                                                        }


                                                }





                                           }
                                           else{
                                                if($waybillnumber!=''||$otherchargescode!=''||$amount!=''){
                                                        $errorremarks = array("Waybill No.->$waybillnumber");
                                                        if($waybillID==''){
                                                            array_push($errorremarks, "Invalid Waybill No.: $waybillnumber");
                                                        }
                                                        if($waybillindb==false&&$waybillnumber!=''){
                                                            array_push($errorremarks, "Waybill not in DB: $waybillnumber");
                                                        }
                                                        if($otherchargesID==''){
                                                            array_push($errorremarks, "Invalid Other Charges: $otherchargescode");
                                                        }
                                                        if(!$amount>0){
                                                            array_push($errorremarks, "Invalid Amount: $amount");
                                                        }

                                                        $errorremarks = implode('  |  ', $errorremarks);
                                                    
                                                        
                                                        array_push($errorremarksarray, "Line $i: ".$errorremarks);
                                                }
                                                    

                                           }

                                    }
                                    

                                    echo "_________________________________________________________________________<br><br>";

                                    echo "OTHER CHARGES: <br><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='4'>OTHER CHARGES INSERTED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Description</th>
                                                                <th>Amount</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($insertedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$insertedcharges[$x][0]."</td>
                                                               <td>".$insertedcharges[$x][1]."</td>
                                                               <td>".$insertedcharges[$x][2]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='4'>OTHER CHARGES UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Description</th>
                                                                <th>Amount</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($updatedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$updatedcharges[$x][0]."</td>
                                                               <td>".$updatedcharges[$x][1]."</td>
                                                               <td>".$updatedcharges[$x][2]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>OTHER CHARGES DISREGARDED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($errorremarksarray);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$errorremarksarray[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";



                                    echo "<br><b>Recomputing waybill charges....</b><br>";

                                    for($i=0;$i<count($needrecomputation);$i++){
                                        echo "Recomputing ".$needrecomputation[$i]."....<br>";
                                        recomputeWaybill($needrecomputation[$i]);
                                    }

                                    


                                }
                                else{
                                    echo "Unable to upload file. <b>OTHERCHARGES SHEET: Invalid header format.</b><br><br>";

                                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                                }

                                /**** OTHER CHARGES - END *****/



                                /*****PACKAGEDIMENSIONS SHEET ****/
                                $packagedimensionsheetindex = array_search('PACKAGEDIMENSIONS', $sheets);
                                //$worksheet = $excelObj->getActiveSheet();
                                $worksheet = $excelObj->getSheet($packagedimensionsheetindex);
                                $lastRow = $worksheet->getHighestRow();
                                $lastCol = $worksheet->getHighestColumn();

                                $headerColumns = array(
                                                
                                                'WAYBILL NO.', //A
                                                'LENGTH', //B
                                                'WIDTH',
                                                'HEIGHT',
                                                'ACTUAL WEIGHT',
                                                'QUANTITY',
                                                'UOM CODE'
                                    
                                            );

                                $checkHeader = 'TRUE';
                                $col = "A";
                                for($i=0;$i<count($headerColumns);$i++){
                                    if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                                        $checkHeader= 'FALSE';
                                    }
                                    $col++;
                                }

                                if($checkHeader=='TRUE'){

                                    $txnwithrowerror1 = array();

                                    $transactedtxn = array();

                                    $newtxn = array();

                                    $updatetxn = array();

                                    $deletedprevious = array();

                                    $insertedcharges = array();
                                    $updatedcharges = array();


                                    $notinsertedcharges = array();
                                    $notupdatedcharges = array();

                                    
                                    $errorremarksarray = array();

                                    $needrecomputation = array();

                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $length = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $width = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                            $height = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                            $actualweight = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                                            $qty = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                                            $uom = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }


                                           if($waybillindb==true&&$length>0&&$height>0&&$height>0&&$qty>0&&$actualweight>0&&$uom!=''){

                                           }
                                           else{
                                               if(!in_array($waybillnumber, $txnwithrowerror1)){
                                                        array_push($txnwithrowerror1, $waybillnumber);
                                               }
                                            }

                                    }

                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $length = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $width = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                            $height = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                            $actualweight = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                                            $qty = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                                            $uom = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                                            $vw = 0;
                                            $cbm = 0;

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }
                                           





                                           if($waybillindb==true&&!in_array($waybillnumber, $txnwithrowerror1)&&$length>0&&$width>0&&$height>0&&$qty>0&&$actualweight>0&&$uom!=''){

                                                $checkifnewwaybillrs = query("select * from txn_waybill_package_dimension where waybill_number='$waybillnumber'");

                                                if(getNumRows($checkifnewwaybillrs)==0){
                                                    if(!in_array($waybillnumber, $newtxn)){
                                                        array_push($newtxn, $waybillnumber);
                                                    }
                                                }

                                                $vw = $length*$width*$height;
                                                $vw = $vw/6000;
                                                $vw = $vw*$qty;
                                                $vw = round($vw,4);
                                                $cbm = $length*$width*$height;
                                                $cbm = $cbm/1000000;
                                                $cbm = $cbm*$qty;
                                                $cbm = round($cbm,4);
                                                $actualweight = round($actualweight,4);


                                                if(in_array($waybillnumber, $newtxn)){




                                                        query("insert into txn_waybill_package_dimension(
                                                                                                        waybill_number,
                                                                                                        length,
                                                                                                        width,
                                                                                                        height,
                                                                                                        quantity,
                                                                                                        volumetric_weight,
                                                                                                        cbm,
                                                                                                        uom,
                                                                                                        actual_weight
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$length',
                                                                                                        '$width',
                                                                                                        '$height',
                                                                                                        '$qty',
                                                                                                        '$vw',
                                                                                                        '$cbm',
                                                                                                        '$uom',
                                                                                                        '$actualweight'
                                                                                                    )");

                                                        $rowinsertcharges = array($waybillnumber,$length,$width,$height,$vw,$cbm,$actualweight,$qty,$uom);
                                                        //if(!in_array($rowinsertcharges, $insertedcharges)){
                                                            array_push($insertedcharges, $rowinsertcharges);
                                                        //}

                                                        if(!in_array($waybillnumber, $needrecomputation)){

                                                            array_push($needrecomputation, $waybillnumber);
                                                        }
                                                }
                                                else{

                                                        if(!in_array($waybillnumber, $deletedprevious)){
                                                                query("delete from txn_waybill_package_dimension where waybill_number='$waybillnumber'");
                                                                array_push($deletedprevious, $waybillnumber);
                                                        }

                                                         query("insert into txn_waybill_package_dimension(
                                                                                                        waybill_number,
                                                                                                        length,
                                                                                                        width,
                                                                                                        height,
                                                                                                        quantity,
                                                                                                        volumetric_weight,
                                                                                                        cbm,
                                                                                                        uom,
                                                                                                        actual_weight
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$length',
                                                                                                        '$width',
                                                                                                        '$height',
                                                                                                        '$qty',
                                                                                                        '$vw',
                                                                                                        '$cbm',
                                                                                                        '$uom',
                                                                                                        '$actualweight'
                                                                                                    )");

                                                        $rowupdatecharges = array($waybillnumber,$length,$width,$height,$vw,$cbm,$actualweight,$qty,$uom);
                                                        //if(!in_array($rowupdatecharges, $updatedcharges)){
                                                            array_push($updatedcharges, $rowupdatecharges);
                                                        //}

                                                        if(!in_array($waybillnumber, $needrecomputation)){
                                                            array_push($needrecomputation, $waybillnumber);
                                                        }


                                                }





                                           }
                                           else{
                                                if(!$waybillindb==true||!$length>0||!$height>0||!$height>0||!$qty>0||!$actualweight>0||$uom==''){

                                           
                                                        $errorremarks = array("Waybill No.->$waybillnumber");
                                                        if($waybillID==''){
                                                            array_push($errorremarks, "Invalid Waybill No.: $waybillnumber");
                                                        }
                                                        if($waybillindb==false&&$waybillnumber!=''){
                                                            array_push($errorremarks, "Waybill not in DB: $waybillnumber");
                                                        }
                                                        if(!$length>0){
                                                            array_push($errorremarks, "Invalid Length: $length");
                                                        }
                                                        if(!$width>0){
                                                            array_push($errorremarks, "Invalid Width: $width");
                                                        }
                                                        if(!$height>0){
                                                            array_push($errorremarks, "Invalid Height: $height");
                                                        }
                                                        if(!$actualweight>0){
                                                            array_push($errorremarks, "Invalid Actual Weight: $actualweight");
                                                        }
                                                        if($uom==''){
                                                            array_push($errorremarks, "Invalid Uom: $uom");
                                                        }

                                                        $errorremarks = implode('  |  ', $errorremarks);
                                                    
                                                        
                                                        array_push($errorremarksarray, "Line $i: ".$errorremarks);
                                                }
                                                    

                                           }

                                    }
                                    

                                    echo "_________________________________________________________________________<br><br>";

                                    echo "PACKAGE DIMENSIONS: <br><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>PACKAGE DIMENSION INSERTED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Length</th>
                                                                <th>Width</th>
                                                                <th>Height</th>
                                                                <th>VW</th>
                                                                <th>CBM</th>
                                                                <th>Actual Weight</th>
                                                                <th>Quantity</th>
                                                                <th>UOM</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($insertedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$insertedcharges[$x][0]."</td>
                                                               <td>".$insertedcharges[$x][1]."</td>
                                                               <td>".$insertedcharges[$x][2]."</td>
                                                               <td>".$insertedcharges[$x][3]."</td>
                                                               <td>".$insertedcharges[$x][4]."</td>
                                                               <td>".$insertedcharges[$x][5]."</td>
                                                               <td>".$insertedcharges[$x][6]."</td>
                                                               <td>".$insertedcharges[$x][7]."</td>
                                                               <td>".$insertedcharges[$x][8]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='10'>PACKAGE DIMENSION UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Length</th>
                                                                <th>Width</th>
                                                                <th>Height</th>
                                                                <th>VW</th>
                                                                <th>CBM</th>
                                                                <th>Actual Weight</th>
                                                                <th>Quantity</th>
                                                                <th>UOM</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($updatedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$updatedcharges[$x][0]."</td>
                                                               <td>".$updatedcharges[$x][1]."</td>
                                                               <td>".$updatedcharges[$x][2]."</td>
                                                               <td>".$updatedcharges[$x][3]."</td>
                                                               <td>".$updatedcharges[$x][4]."</td>
                                                               <td>".$updatedcharges[$x][5]."</td>
                                                               <td>".$updatedcharges[$x][6]."</td>
                                                               <td>".$updatedcharges[$x][7]."</td>
                                                               <td>".$updatedcharges[$x][8]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>PACKAGE DIMENSION DISREGARDED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($errorremarksarray);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$errorremarksarray[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";



                                    

                                    for($i=0;$i<count($needrecomputation);$i++){
                                        echo "Recomputing ".$needrecomputation[$i]."....<br>";
                                        recomputeVWCBM($needrecomputation[$i]);
                                    }

                                    


                                }
                                else{
                                    echo "Unable to upload file. <b>PACKAGEDIMENSIONS SHEET: Invalid header format.</b><br><br>";

                                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                                }

                                /**** PACKAGEDIMENSIONS - END *****/





                                /***** HANDLING INTRUCTION SHEET ****/
                                $handlinginstructionsheetindex = array_search('HANDLINGINSTRUCTIONS', $sheets);
                                //$worksheet = $excelObj->getActiveSheet();
                                $worksheet = $excelObj->getSheet($handlinginstructionsheetindex);
                                $lastRow = $worksheet->getHighestRow();
                                $lastCol = $worksheet->getHighestColumn();

                                $headerColumns = array(
                                                
                                                'WAYBILL NO.', //A
                                                'HANDLING INSTRUCTION CODE'
                                    
                                            );

                                $checkHeader = 'TRUE';
                                $col = "A";
                                for($i=0;$i<count($headerColumns);$i++){
                                    if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){
                                        $checkHeader= 'FALSE';
                                    }
                                    $col++;
                                }

                                if($checkHeader=='TRUE'){

                                    $txnwithrowerror1 = array();

                                    $transactedtxn = array();

                                    $newtxn = array();

                                    $updatetxn = array();

                                    $deletedpreviouscharges = array();

                                    $insertedcharges = array();
                                    $updatedcharges = array();


                                    $notinsertedcharges = array();
                                    $notupdatedcharges = array();

                                    
                                    $errorremarksarray = array();

                                    

                                    /*for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $handlinginstructioncode = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $handlinginstructionID = getHandlingInstructionID($handlinginstructioncode);
                                            $handlinginstructionDesc = getHandlingInstructionDesc($handlinginstructioncode);

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }


                                           if($waybillindb==true&&$handlinginstructionID!=''){

                                           }
                                           else{
                                               if(!in_array($waybillnumber, $txnwithrowerror1)){
                                                        array_push($txnwithrowerror1, $waybillnumber);
                                               }
                                            }

                                    }*/
                                    $now = date('Y-m-d H:i:s');
                                    for($i=2;$i<=$lastRow;$i++){

                                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                            $handlinginstructioncode = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                            $handlinginstructionID = getHandlingInstructionID($handlinginstructioncode);
                                            $handlinginstructionDesc = getHandlingInstructionDesc($handlinginstructioncode);

                                            $waybillindb = false;
                                            $waybillID = '';
                                            $checkwaybillindbrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                            while($obj=fetch($checkwaybillindbrs)){
                                                $waybillindb = true;
                                                $waybillID = $obj->id;
                                            }
                                           


                                           


                                           if($waybillindb==true&&!in_array($waybillnumber, $txnwithrowerror1)&&$handlinginstructionID!=''){

                                                $checkifnewwaybillrs = query("select * from txn_waybill_handling_instruction where waybill_number='$waybillnumber'");

                                                if(getNumRows($checkifnewwaybillrs)==0){
                                                    if(!in_array($waybillnumber, $newtxn)){
                                                        array_push($newtxn, $waybillnumber);
                                                    }
                                                }


                                                if(in_array($waybillnumber, $newtxn)){


                                                        query("insert into txn_waybill_handling_instruction(
                                                                                                        waybill_number,
                                                                                                        handling_instruction_id,
                                                                                                        created_date
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$handlinginstructionID',
                                                                                                        '$now'
                                                                                                    )");
                                                        

                                                        $rowinsertcharges = array($waybillnumber,$handlinginstructionDesc);
                                                        //if(!in_array($rowinsertcharges, $insertedcharges)){
                                                            array_push($insertedcharges, $rowinsertcharges);
                                                        //}

                                                       
                                                }
                                                else{

                                                        if(!in_array($waybillnumber, $deletedpreviouscharges)){
                                                                query("delete from txn_waybill_handling_instruction where waybill_number='$waybillnumber'");
                                                                array_push($deletedpreviouscharges, $waybillnumber);

                                                        }

                                                        query("insert into txn_waybill_handling_instruction(
                                                                                                        waybill_number,
                                                                                                        handling_instruction_id,
                                                                                                        created_date
                                                                                                    )
                                                                                              values(
                                                                                                        '$waybillnumber',
                                                                                                        '$handlinginstructionID',
                                                                                                        '$now'
                                                                                                    )");

                                                        $rowupdatecharges = array($waybillnumber,$handlinginstructionDesc);
                                                        //if(!in_array($rowupdatecharges, $updatedcharges)){
                                                            array_push($updatedcharges, $rowupdatecharges);
                                                        //}

                                                       

                                                }





                                           }
                                           else{
                                                if($waybillnumber!=''||$handlinginstructionID!=''){
                                                        $errorremarks = array("Waybill No.->$waybillnumber");
                                                        if($waybillID==''){
                                                            array_push($errorremarks, "Invalid Waybill No.: $waybillnumber");
                                                        }
                                                        if($waybillindb==false&&$waybillnumber!=''){
                                                            array_push($errorremarks, "Waybill not in DB: $waybillnumber");
                                                        }
                                                        if($handlinginstructionID==''){
                                                            array_push($errorremarks, "Invalid Handling Instruction Code: $handlinginstructioncode");
                                                        }
                                                       

                                                        $errorremarks = implode('  |  ', $errorremarks);
                                                    
                                                        
                                                        array_push($errorremarksarray, "Line $i: ".$errorremarks);
                                                }
                                                    

                                           }

                                    }
                                    

                                    echo "_________________________________________________________________________<br><br>";

                                    echo "HANDLING INSTRUCTIONS: <br><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='4'>HANDLING INSTRUCTION INSERTED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Description</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($insertedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$insertedcharges[$x][0]."</td>
                                                               <td>".$insertedcharges[$x][1]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='4'>HANDLING INSTRUCTION UPDATED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Waybill No.</th>
                                                                <th>Description</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($updatedcharges);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$updatedcharges[$x][0]."</td>
                                                               <td>".$updatedcharges[$x][1]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";

                                    echo "<table border='1px' cellspacing='0px'>
                                                <thead>
                                                        <tr>    
                                                                <th colspan='2'>HANDLING INSTRUCTION DISREGARDED</th>
                                                        </tr>
                                                        <tr>    
                                                                <th>Line</th>
                                                                <th>Remarks</th>
                                                        </tr>
                                                </thead>
                                                <tbody>";
                                                $line = 1;
                                                for($x=0;$x<count($errorremarksarray);$x++){
                                                    echo "<tr>
                                                               <td>$line</td>
                                                               <td>".$errorremarksarray[$x]."</td>
                                                          </tr>";
                                                    $line++;
                                                }
                                    echo       "</tbody>
                                         </table><br>";



                                    
                                    


                                }
                                else{
                                    echo "Unable to upload file. <b>HANDLINGINSTRUCTIONS SHEET: Invalid header format.</b><br><br>";

                                    echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                                }

                                /**** HANDLING INSTRUCTION SHEET - END *****/







                    }
                    else{

                            echo "Unable to upload file.<br><br>";

                            echo "<table border='1px' cellspacing=0>
                                            <tr><td><b>REASON</b></td></tr>";
                                            for($o=0;$o<count($checkheaderremarks);$o++){
                                                echo "<tr><td>".$checkheaderremarks[$o]."</td></tr>";
                                            }
                            echo "</table><br><br>";

                            echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                    }


                }
                else{
                        echo "Unable to upload file. <br><br>

                        File should contain the following sheets: <b>info, othercharges, packagedimensions, packagecodes, handlinginstructions</b><br><br>";

                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-transaction-template.xlsx'>here</a> to download file template<br>";
                }


         



    			

    	}
    	else{
    		echo "Invalid File Type.<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>