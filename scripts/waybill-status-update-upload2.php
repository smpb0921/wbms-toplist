<?php


	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////
    include("../classes/waybill.class.php");
    include("../classes/waybill-status-history.class.php");
    include("../resources/PHPExcel-1.8/Classes/PHPExcel.php");
    //include("../resources/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

    if(isset($_FILES['uploadwaybillstatusupdatemodal-file'])){

         $uploadtype = isset($_POST['uploadtype'])?escapeString(strtoupper(trim($_POST['uploadtype']))):'';


        function convertToText($str){
            $str = escapeString($str);
            //$str = trim($str,'0');
            return $str;
        }

    	$file = $_FILES['uploadwaybillstatusupdatemodal-file'];
    	$tmp = $file['tmp_name'];
    	$filename = $file['name'];
        //$ftype = $file['type'];
    	$ftype = strrchr($filename, '.');

        $userstatusupdate = hasAccess(USERID,'#waybill-trans-updatestatusbtn');
        $usercostingupdate = hasAccess(USERID,'#waybill-trans-editwaybillcostingbtn');

    	

    	if($ftype=='.xls'||$ftype=='.csv'||$ftype=='.xlsx'){

            if($uploadtype=='STATUSUPDATE'){

                if($userstatusupdate==1||USERID==1){

        			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
        			@$excelObj = $excelReader->load($tmp);



                    $worksheet = $excelObj->getActiveSheet();
                    $lastRow = $worksheet->getHighestRow();
                    $lastCol = $worksheet->getHighestColumn();

                    $headerColumns = array(
                                            'TRACKING NUMBER', //A
                                            'STATUS', //B
                                            'DATE RECEIVED', //C
                                            'RECEIVED BY',
                                            'REMARKS'
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

                        $checkvalues = true;
                        $lineerrors = array();
                        $updatedtxn = array();
                        $skippedtxn = array();

                        
                        $systemlog = new system_log();


                        for($i=2;$i<=$lastRow;$i++){
                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                            $status = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                            $receiveddate = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                            $receivedby = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                            $remarks = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));

                            $cellreceiveddate = $worksheet->getCell('C'.$i);
                            //$receiveddate =  date('Y-m-d', strtotime($receiveddate));
                            //$receiveddate = $receiveddate=='1970-01-01'?'':$receiveddate;

                            if(PHPExcel_Shared_Date::isDateTime($cellreceiveddate)) {
                                $receiveddate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($receiveddate)); 
                            }
                            else{
                                $receiveddate =  date('Y-m-d', strtotime($receiveddate));
                            }

                            $receiveddate = $status!='DELIVERED'?'NULL':$receiveddate;
                            $receivedby = $status!='DELIVERED'?'NULL':$receivedby;

                            
                            

                            $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Waybill Number: ".$waybillnumber));
                            }
                            else{
                                $checktxncurrentstatus = 'LOGGED';
                                while($obj=fetch($checktxnrs)){
                                    $checktxncurrentstatus = trim(strtoupper($obj->status));
                                }

                                if($checktxncurrentstatus=='LOGGED'||$checktxncurrentstatus==''){
                                     array_push($lineerrors, array($i,"Transaction Not Posted: ".$waybillnumber));
                                }
                            }

                            



                            $checktxnstatusrs = query("select * from status where description='$status'");
                            if(getNumRows($checktxnstatusrs)==0){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Status: ".$status));
                            }


                            if($receiveddate=='1970-01-01'&&$status=='DELIVERED'){
                                 $checkvalues = false;
                                 array_push($lineerrors, array($i,"Invalid Received Date: ".$worksheet->getCell('C'.$i)->getValue()));
                            }

                            




                        }


                        if($checkvalues==true||1==1){

                            $waybillstathistory = new txn_waybill_status_history();
                            $systemlog = new system_log();
                                                           
                            for($i=2;$i<=$lastRow;$i++){
                                $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                $status = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                $receiveddate = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                $receivedby = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                $remarks = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));

                                $cellreceiveddate = $worksheet->getCell('C'.$i);
                                //$receiveddate =  date('Y-m-d', strtotime($receiveddate));
                                $now = date('Y-m-d H:i:s');
                                $userid = USERID;
                                //$receiveddate = $receiveddate=='1970-01-01'?'':$receiveddate;

                                if(PHPExcel_Shared_Date::isDateTime($cellreceiveddate)) {
                                    $receiveddate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($receiveddate)); 
                                }
                                else{
                                    $receiveddate =  date('Y-m-d', strtotime($receiveddate));
                                }

                                $receiveddate = $status!='DELIVERED'?'NULL':$receiveddate;
                                $receivedby = $status!='DELIVERED'?'NULL':$receivedby;
                                

                                $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                $checktxnstatusrs = query("select * from status where description='$status'");

                                $checktxncurrentstatus = 'LOGGED';
                                $checktxncurrentremarks = '';
                                while($obj=fetch($checktxnrs)){
                                    $checktxncurrentstatus = trim(strtoupper($obj->status));
                                    $checktxncurrentremarks = trim(strtoupper($obj->last_status_update_remarks));
                                }
                                $checktxnnumber = getNumRows($checktxnrs);
                                $checktxnstatus = getNumRows($checktxnstatusrs);

                                $checkifstatusupdateallowedrs = query("select * from txn_waybill
                                                                       where waybill_number='$waybillnumber' and 
                                                                             status not in (select status from no_update_status)");
                                $checkupdatestatusflag = getNumRows($checkifstatusupdateallowedrs);


                                if($checktxncurrentstatus!='LOGGED'&&
                                   $checktxncurrentstatus!=''&&
                                   $checktxnnumber==1&&
                                   $checktxnstatus>0&&
                                   ($receiveddate=='NULL'||$receiveddate!='1970-01-01')&&
                                   $checkupdatestatusflag>0)
                                {

                                        $rcvcols = ", received_by=NULL, received_date=NULL";
                                        if($status=='DELIVERED'){
                                            $rcvcols = ", received_by='$receivedby', received_date='$receiveddate'";
                                        }
                                        
                                        $rs = query("update txn_waybill 
                                                     set status='$status', 
                                                         updated_date='$now', 
                                                         updated_by='$userid', 
                                                         last_status_update_remarks='$remarks' 
                                                         $rcvcols 
                                                      where waybill_number='$waybillnumber'");

                                        if($rs){
                                            $waybillstathistory->insert(array('',$waybillnumber,$status,$status,$remarks,$now,$userid,'NULL','NULL','NULL',$receivedby,$receiveddate,'NULL'));
                                            $systemlog->logInfo('WAYBILL',"Waybill Status Update (Upload): $status","Waybill Number: ".$waybillnumber." | Status: $status  |  Remarks: $remarks",$userid,$now);

                                            array_push($updatedtxn, array($i,$waybillnumber,$checktxncurrentstatus,$status));
                                        }
                                        else{
                                            echo mysql_error()."<br>";
                                        }
                                    

                                }
                                else{
                                    if(($checktxncurrentstatus=='LOGGED'||$checktxncurrentstatus=='')&&$checktxnnumber==1){
                                        array_push($skippedtxn, array($i,"Transaction Not Posted: ".$waybillnumber));
                                    }
                                    if($checktxnnumber<1){
                                        array_push($skippedtxn, array($i,"Invalid Waybill Number: ".$waybillnumber));
                                    }
                                    if($checktxnstatus<1){
                                        array_push($skippedtxn, array($i,"Invalid Status: ".$status));
                                    }
                                    if($receiveddate=='1970-01-01'&&$status=='DELIVERED'){
                                        array_push($skippedtxn, array($i,"Invalid Received Date: ".$worksheet->getCell('C'.$i)->getValue()));
                                    }
                                    if($checkupdatestatusflag==0&&$checktxnnumber>0){
                                        array_push($skippedtxn, array($i,"Already Updated to Final Status: $checktxncurrentstatus-$waybillnumber"));
                                    }

                                }

                                




                            }



                            
                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='4'>UPDATED TRANSACTIONS</th>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <th>Row No.</th>
                                        <th>TRACKING NUMBER</th>
                                        <th>DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($updatedtxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($updatedtxn[$x][0])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][1])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][2])."->".utf8_encode($updatedtxn[$x][3])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='3'>SKIPPED TRANSACTIONS</th>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <th>Row No.</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($skippedtxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($skippedtxn[$x][0])."</td>
                                                   <td>".utf8_encode($skippedtxn[$x][1])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br>";
                        }
                        else{
                            echo "Unable to upload. Please correct the following error(s):<br><br>";
                            
                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='3'>LINE ERRORS</th>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <th>Row No.</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($lineerrors);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($lineerrors[$x][0])."</td>
                                                   <td>".utf8_encode($lineerrors[$x][1])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br>";
                        }

                            


                                        
                    }
                    else{
                        echo "Unable to upload file. <b>Invalid Header Format for Status Update</b>.<br><br>";
                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-update-status-template.xlsx'>here</a> to download file template<br>";
                            
                    }

                }
                else{
                    echo "Unable to upload <b>status update</b>. No user permission.";
                }

            }
            else if($uploadtype=='COSTINGUPDATE'){
                if($usercostingupdate==1||USERID==1){

                    @$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
                    @$excelObj = $excelReader->load($tmp);

                    $worksheet = $excelObj->getActiveSheet();
                    $lastRow = $worksheet->getHighestRow();
                    $lastCol = $worksheet->getHighestColumn();

                    $headerColumns = array(
                                            'BILL ITEM NO', //A
                                            'TRACKING NO', //B
                                            'BILLING REFERENCE',
                                            'FREIGHT COST'
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

                        $checkvalues = true;
                        $lineerrors = array();
                        $updatedtxn = array();
                        $skippedtxn = array();
                        $witherrortxn = array();
                        $noupdatetxn = array();

                        
                        $systemlog = new system_log();


                        for($i=2;$i<=$lastRow;$i++){
                            $billitemnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                            $billingreference = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                            $freightcost = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));

                            //$cellpickupdate = $worksheet->getCell('C'.$i);
                            //$pickupdate =  date('Y-m-d', strtotime($pickupdate));
                            //$receiveddate = $receiveddate=='1970-01-01'?'':$receiveddate;


                            $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Waybill Number: ".$waybillnumber));
                            }


                            /*if($pickupdate=='1970-01-01'){
                                 $checkvalues = false;
                                 array_push($lineerrors, array($i,"Invalid Pickup Date: ".$worksheet->getCell('C'.$i)->getValue()));
                            }*/

                            




                        }


                        if($checkvalues==true||1==1){

                           
                            $systemlog = new system_log();
                                                           
                            for($i=2;$i<=$lastRow;$i++){
                                $billitemnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                $waybillnumber = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                //$pickupdate = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                $billingreference = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                $freightcost = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                $freightcost = str_replace(',', '', $freightcost);

                                //$cellpickupdate = $worksheet->getCell('C'.$i);
                                //$pickupdate =  date('Y-m-d', strtotime($cellpickupdate));
                                $now = date('Y-m-d H:i:s');
                                $userid = USERID;
                                
                                /*if(PHPExcel_Shared_Date::isDateTime($cellpickupdate)) {
                                    $pickupdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($pickupdate)); 
                                }
                               else{
                                    $pickupdate =  date('Y-m-d', strtotime($pickupdate));
                                }*/
                                

                                $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                              
                                $checktxnnumber = getNumRows($checktxnrs);

                                //echo $cellpickupdate."-".$pickupdate."<br>";



                                if($checktxnnumber==1)
                                {
                                        $updatestr = array();


                                        if(trim($billingreference)!=''){
                                            array_push($updatestr, "bill_reference='$billingreference'");
                                        }

                                        if(trim($billitemnumber)!=''){
                                            array_push($updatestr, "bill_item_number='$billitemnumber'");
                                        }

                                        if(trim($freightcost)>=0){
                                            array_push($updatestr, "freight_cost='$freightcost'");
                                        }

                                        /*if($pickupdate!='1970-01-01'){
                                            array_push($updatestr, "pickup_date='$pickupdate'");
                                        }*/

                                        if(count($updatestr)>0){
                                            $updatecolumns = implode(', ', $updatestr);

                                            $rs = query("update txn_waybill 
                                                         set $updatecolumns
                                                         where waybill_number='$waybillnumber'");

                                             if($rs){
                                            
                                                $systemlog->logInfo('WAYBILL',"Waybill Costing Update (Upload)","Waybill Number: ".$waybillnumber." | Bill Item No.: $billitemnumber  | Billing Reference: $billingreference | Freight Cost: $freightcost",$userid,$now);

                                                //if($pickupdate!='1970-01-01'){
                                                    array_push($updatedtxn, array($i,$waybillnumber,$billitemnumber,$billingreference,$freightcost));
                                                /*}
                                                else{
                                                    array_push($witherrortxn, array($i,$waybillnumber,$pickupdate,$billitemnumber,$billingreference,$freightcost,"INVALID PICKUP DATE"));
                                                }*/
                                            }
                                            else{
                                                echo mysql_error()."<br>";
                                            }
                                        }
                                        else{
                                            array_push($noupdatetxn, array($i,$waybillnumber,$billitemnumber,$billingreference,$freightcost));
                                        }



                                        
                                        

                                       
                                    

                                }
                                else{
                                    if($checktxnnumber!=1){
                                        array_push($skippedtxn, array($i,"Invalid Waybill Number: ".$waybillnumber));
                                    }
                                }

                                




                            }



                            
                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='6'>UPDATED TRANSACTIONS: W/O ERROR</th>
                                    </tr>
                                    <tr>
                                        <th>LINE</th>
                                        <th>ROW NUMBER</th>
                                        <th>TRACKING NUMBER</th>
                                        <th>BILL ITEM NUMBER</th>
                                        <th>BILL REFERENCE</th>
                                        <th>FREIGHT COST</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($updatedtxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($updatedtxn[$x][0])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][1])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][2])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][3])."</td>
                                                   <td>".utf8_encode($updatedtxn[$x][4])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='7'>UPDATED TRANSACTIONS: W/ ERROR</th>
                                    </tr>
                                    <tr>
                                        <th>LINE</th>
                                        <th>ROW NUMBER</th>
                                        <th>TRACKING NUMBER</th>
                                        <th>BILL ITEM NUMBER</th>
                                        <th>BILL REFERENCE</th>
                                        <th>FREIGHT COST</th>
                                        <th>ERROR DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($witherrortxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($witherrortxn[$x][0])."</td>
                                                   <td>".utf8_encode($witherrortxn[$x][1])."</td>
                                                   <td>".utf8_encode($witherrortxn[$x][2])."</td>
                                                   <td>".utf8_encode($witherrortxn[$x][3])."</td>
                                                   <td>".utf8_encode($witherrortxn[$x][4])."</td>
                                                   <td>".utf8_encode($witherrortxn[$x][5])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='3'>INVALID TRANSACTIONS</th>
                                    </tr>
                                    <tr>
                                        <th>LINE</th>
                                        <th>ROW NUMBER</th>
                                        <th>DETAILS</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($skippedtxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($skippedtxn[$x][0])."</td>
                                                   <td>".utf8_encode($skippedtxn[$x][1])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br>";

                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='6'>SKIPPED TRANSACTIONS: NO VALID UPDATES</th>
                                    </tr>
                                    <tr>
                                        <th>LINE</th>
                                        <th>ROW NUMBER</th>
                                        <th>TRACKING NUMBER</th>
                                        <th>BILL ITEM NUMBER</th>
                                        <th>BILL REFERENCE</th>
                                        <th>FREIGHT COST</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($noupdatetxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($noupdatetxn[$x][0])."</td>
                                                   <td>".utf8_encode($noupdatetxn[$x][1])."</td>
                                                   <td>".utf8_encode($noupdatetxn[$x][2])."</td>
                                                   <td>".utf8_encode($noupdatetxn[$x][3])."</td>
                                                   <td>".utf8_encode($noupdatetxn[$x][4])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br>";



                        }
                        else{
                            echo "Unable to upload. Please correct the following error(s):<br><br>";
                            
                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='3'>LINE ERRORS</th>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <th>Row No.</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($lineerrors);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($lineerrors[$x][0])."</td>
                                                   <td>".utf8_encode($lineerrors[$x][1])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br>";
                        }

                            


                                        
                    }
                    else{
                        echo "Unable to upload file. <b>Invalid Header Format for Costing Update</b>.<br><br>";
                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-update-costing-template.xlsx'>here</a> to download file template<br>";
                            
                    }

                }
                else{
                    echo "Unable to upload <b>costing update</b>. No user permission.";
                }
            }
            else{
                echo "Invalid Upload Type: $uploadtype";
            }


    	}
    	else{
    		echo "Invalid File Type: $ftype<br><br>
                  <b>Valid File Types</b>: .xlsx, .xls, .csv";
    	}


				
				

		
	}


?>