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
            $str = trim($str);
            $str = escapeString($str);
            //$str = utf8_encode($str);
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
            else if($uploadtype=='WAYBILLUPLOAD'){
                if($usercostingupdate==1||USERID==1){

                    @$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
                    @$excelObj = $excelReader->load($tmp);

                    $worksheet = $excelObj->getActiveSheet();
                    $lastRow = $worksheet->getHighestRow();
                    $lastCol = $worksheet->getHighestColumn();

                    $headerColumns = array(
                                            'LINE', //A
                                            'WAYBILL NUMBER', //B
                                            'MAWBL',
                                            'STATUS',
                                            'BILLING REFERENCE',
                                            'DOCUMENT DATE',
                                            'DESTINATION',
                                            'SHIPPER ACCOUNT NAME',
                                            'CONSIGNEE ACCOUNT NAME',
                                            'CONSIGNEE TEL',
                                            'CONSIGNEE COMPANY NAME',
                                            'CONSIGNEE STREET ADDRESS',
                                            'CONSIGNEE DISTRICT',
                                            'CONSIGNEE CITY',
                                            'CONSIGNEE REGION/PROVINCE',
                                            'SHIPMENT DESCRIPTION',
                                            'CHARGEABLE WEIGHT',
                                            'VALUATION',
                                            'TOTAL AMOUNT',
                                            'POUCH SIZE',
											'BOOKING NUMBER',
											'BOL TYPE',
											'3PL',
											'DECLARED VALUE',
											'ACTUAL WEIGHT',
											'CONTACT NUMBER'
                                           );
                    $checkHeaderInfo = true;
                    $col = "A";
                    for($i=0;$i<count($headerColumns);$i++){
                            
                            if(strtoupper(trim($worksheet->getCell($col.'1')->getValue()))!=strtoupper(trim($headerColumns[$i]))){

                                $checkHeaderInfo = false;
                                echo strtoupper(trim($headerColumns[$i]))."->".strtoupper(trim($worksheet->getCell($col.'1')->getValue()))."<br>";
                            }
                            $col++;
                    }

                    if($checkHeaderInfo==true){
                        $txnwithrowerror = array();
                        $txnwithouterror = array();
                        $txnrowexist = array();

                        $checkvalues = true;
                        $lineerrors = array();
                        $uploadedtxn = array();
                        $skippedtxn = array();
                        $witherrortxn = array();
                        $noupdatetxn = array();

                        
                        $systemlog = new system_log();


                        for($i=2;$i<=$lastRow;$i++){
                            //$billitemnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                            $waybillnumber = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                            $mawbl = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                            $status = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                            $billingreference = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                            $docdate = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue())); //date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($worksheet->getCell('F'.$i)->getValue()));
                            $origin = 'METRO MANILA';
                            $destinationroute = 'NA';
                            $destination = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                            $shipperaccountname = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                            $consigneeaccountname = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                            $consigneetel = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                            $consigneecompanyname = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                            $consigneestreet = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                            $consigneedistrict = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                            $coonsigneecity = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                            $consigneeregion = convertToText(strtoupper($worksheet->getCell('O'.$i)->getValue()));
                            $shipmentdesc = convertToText(strtoupper($worksheet->getCell('P'.$i)->getValue()));
                            $chargeableweight = convertToText(strtoupper($worksheet->getCell('Q'.$i)->getValue()));
                            $valuation = convertToText(strtoupper($worksheet->getCell('R'.$i)->getValue()));
                            $totalamount = convertToText(strtoupper($worksheet->getCell('S'.$i)->getValue()));
                            $pouchsize = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));

							$bookingnumber = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
							$boltype = convertToText(strtoupper($worksheet->getCell('V'.$i)->getValue()));
							$threepl = convertToText(strtoupper($worksheet->getCell('W'.$i)->getValue()));
							$declaredvalue = convertToText(strtoupper($worksheet->getCell('X'.$i)->getValue()));
							$actualweight = convertToText(strtoupper($worksheet->getCell('Y'.$i)->getValue()));
							$contactnumber = convertToText(strtoupper($worksheet->getCell('Z'.$i)->getValue()));

                            //$cellpickupdate = $worksheet->getCell('C'.$i);
                            //$pickupdate =  date('Y-m-d', strtotime($pickupdate));
                            //$receiveddate = $receiveddate=='1970-01-01'?'':$receiveddate;


                            /*$checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Waybill Number: ".$waybillnumber));
                            }*/

                            $checktxnrs = query("select * from origin_destination_port where description='$destination'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Destination: ".$destination));
                            }


                            $checktxnrs = query("select * from shipper where account_name='$shipperaccountname'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Shipper: ".$shipperaccountname));
                            }

                            $checktxnrs = query("select * from consignee where account_name='$consigneeaccountname'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Consignee: ".$consigneeaccountname));
                            }

                            $checktxnrs = query("select * from pouch_size where description='$pouchsize'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Pouch Size: ".$pouchsize));
                            }


							$checktxnrs = query("select * from txn_booking where booking_number='$bookingnumber' and status!='VOID' and status!='LOGGED'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Booking Number: ".$bookingnumber." (Must not be Logged and Cancelled)"));
                            }

						   if($boltype!='DOCUMENT'&&$boltype!='PARCEL'){
								$checkvalues = false;
								array_push($lineerrors, array($i,"Invalid BOL Type: ".$boltype." (Must be PARCEL or DOCUMENT)"));
						   }

						   $checktxnrs = query("select * from third_party_logistic where code='$threepl'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid 3PL: ".$bookingnumber." (Valid 3pl Codes: CBL, AIR21, LBC, FEDEX)"));
                            }


                            /*if($pickupdate=='1970-01-01'){
                                 $checkvalues = false;
                                 array_push($lineerrors, array($i,"Invalid Pickup Date: ".$worksheet->getCell('C'.$i)->getValue()));
                            }*/

                            




                        }


                        if($checkvalues==true){

                           
                            $systemlog = new system_log();
                                                           
                            for($i=2;$i<=$lastRow;$i++){
                                $waybillnumber = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                $mawbl = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                $status = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                $billingreference = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                                $docdate = $worksheet->getCell('F'.$i);
                                $origin = 'METRO MANILA';
                                $destinationroute = 'NA';
                                $destination = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                                $shipperaccountname = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                                $consigneeaccountname = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                                $consigneetel = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                                $consigneecompanyname = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                                $consigneestreet = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                                $consigneedistrict = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                                $coonsigneecity = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                                $consigneeregion = convertToText(strtoupper($worksheet->getCell('O'.$i)->getValue()));
                                $shipmentdesc = convertToText(strtoupper($worksheet->getCell('P'.$i)->getValue()));
                                $chargeableweight = convertToText(strtoupper($worksheet->getCell('Q'.$i)->getValue()));
                                $valuation = convertToText(strtoupper($worksheet->getCell('R'.$i)->getValue()));
                                $totalamount = convertToText(strtoupper($worksheet->getCell('S'.$i)->getValue()));
                                $pouchsize = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));

								$bookingnumber = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
								$boltype = convertToText(strtoupper($worksheet->getCell('V'.$i)->getValue()));
								$threepl = convertToText(strtoupper($worksheet->getCell('W'.$i)->getValue()));
								$declaredvalue = convertToText(strtoupper($worksheet->getCell('X'.$i)->getValue()));
								$actualweight = convertToText(strtoupper($worksheet->getCell('Y'.$i)->getValue()));
								$contactnumber = convertToText(strtoupper($worksheet->getCell('Z'.$i)->getValue()));

                               
                                $now = date('Y-m-d H:i:s');
                                $userid = USERID;
                                
                                if(PHPExcel_Shared_Date::isDateTime($docdate)) {
                                    $docdate = date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP($docdate)); 
                                }
                               else{
                                    $docdate =  date('Y-m-d', strtotime($docdate));
                                }
                                

                                $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                $checktxnnumber = getNumRows($checktxnrs);

                               



                                if($checktxnnumber==0)
                                {
                                    $checkrs = query("select * from origin_destination_port where description='$origin'"); 
                                    while($obj=fetch($checkrs)){
                                        $originid = $obj->id;
                                    } 

                                    $checkrs = query("select * from origin_destination_port where description='$destination'"); 
                                    while($obj=fetch($checkrs)){
                                        $destinationid = $obj->id;
                                    } 

                                    $checkrs = query("select * from destination_route where description='$destinationroute'"); 
                                    while($obj=fetch($checkrs)){
                                        $destinationrouteid = $obj->id;
                                    } 

                                    $checkrs = query("select * from destination_route where description='$destinationroute'"); 
                                    while($obj=fetch($checkrs)){
                                        $destinationrouteid = $obj->id;
                                    } 

                                    $checkrs = query("select * from pouch_size where description='$pouchsize'"); 
                                    while($obj=fetch($checkrs)){
                                        $pouchsizeid = $obj->id;
                                    } 

									$checkrs = query("select * from third_party_logistic where code='$threepl'"); 
                                    while($obj=fetch($checkrs)){
                                        $threeplid = $obj->id;
                                    } 

									$pickupdate = 'NULL';
									$checkrs = query("select * from txn_booking where booking_number='$bookingnumber'"); 
                                    while($obj=fetch($checkrs)){
                                        $pickupdate = trim($obj->actual_pickup_date)!=''?"'$obj->actual_pickup_date'":'NULL';
                                    }

                                    $checkrs = query("select * from shipper where account_name='$shipperaccountname'");
                                    while($obj=fetch($checkrs)){
                                         $shipperid = $obj->id;
                                         $shipperaccountnumber = $obj->account_number;
                                         //$shippertel = $obj->id;
                                         $shippercompanyname = $obj->company_name;
                                         $shipperstreet = $obj->company_street_address;
                                         $shipperdistrict = $obj->company_district;
                                         $shippercity = $obj->company_city;
                                         $shipperregion = $obj->company_state_province;
                                         $shipperzip = $obj->company_zip_code;
                                         $shippercountry = $obj->company_country;
                                         $podinstruction = $obj->pod_instruction;

                                    }

                                    $checkrs = query("select * from consignee where account_name='$consigneeaccountname'");
                                    while($obj=fetch($checkrs)){
                                         $consigneeid = $obj->id;
                                         $consigneeaccountnumber = $obj->account_number;
                                         $consigneecompanyname = $obj->company_name;
                                         $consigneestreet = $obj->company_street_address;
                                         $consigneedistrict = $obj->company_district;
                                         $consigneecity = $obj->company_city;
                                         $consigneeregion = $obj->company_state_province;
                                         $consigneezip = $obj->company_zip_code;
                                         $consigneecountry = $obj->company_country;
										 $consigneeidnumber = trim($obj->id_number)!=''?"'$obj->id_number'":'NULL';
                                    }

                                    query("insert into txn_waybill(
                                                                    id,
                                                                    waybill_number,
                                                                    mawbl_bl,
                                                                    status,
                                                                    bill_reference,
                                                                    document_date,
                                                                    origin_id,
                                                                    destination_id,
                                                                    destination_route_id,
                                                                    shipper_id,
																	shipper_account_number,
                                                                    shipper_account_name,
                                                                    shipper_tel_number,
                                                                    shipper_company_name,
                                                                    shipper_street_address,
                                                                    shipper_district,
                                                                    shipper_city,
                                                                    shipper_state_province,
                                                                    shipper_zip_code,
                                                                    shipper_country,
                                                                    consignee_id,
																	consignee_account_number,
                                                                    consignee_account_name,
                                                                    consignee_tel_number,
                                                                    consignee_company_name,
                                                                    consignee_street_address,
                                                                    consignee_district,
                                                                    consignee_city,
                                                                    consignee_state_province,
                                                                    consignee_zip_code,
                                                                    consignee_country,
                                                                    shipment_description,
                                                                    package_chargeable_weight,
                                                                    package_valuation,
                                                                    total_amount,
                                                                    package_mode_of_transport,
                                                                    third_party_logistic_id,
                                                                    parcel_type_id,
                                                                    created_date,
                                                                    created_by,
                                                                    updated_date,
                                                                    updated_by,
                                                                    pouch_size_id,
																	booking_number,
																	waybill_type,
																	package_declared_value,
																	package_actual_weight,
																	pickup_date,
																	package_number_of_packages
                                                                )
                                                            values(
                                                                     '',
                                                                     '$waybillnumber',
                                                                     '$mawbl',
                                                                     '$status',
                                                                     '$billingreference',
                                                                     '$docdate',
                                                                     '$originid',
                                                                     '$destinationid',
                                                                     '$destinationrouteid',
                                                                     '$shipperid',
																	 '$shipperaccountnumber',
                                                                     '$shipperaccountname',
                                                                     '$contactnumber',
                                                                     '$shippercompanyname',
                                                                     '$shipperstreet',
                                                                     '$shipperdistrict',
                                                                     '$shippercity',
                                                                     '$shipperregion',
                                                                     '$shipperzip',
                                                                     '$shippercountry',
                                                                     '$consigneeid',
																	 '$consigneeaccountnumber',
                                                                     '$consigneeaccountname',
                                                                     '$consigneetel',
                                                                     '$consigneecompanyname',
                                                                     '$consigneestreet',
                                                                     '$consigneedistrict',
                                                                     '$consigneecity',
                                                                     '$consigneeregion',
                                                                     '$consigneezip',
                                                                     '$consigneecountry',
                                                                     '$shipmentdesc',
                                                                     '$chargeableweight',
                                                                     '$valuation',
                                                                     '$totalamount',
                                                                     NULL,
                                                                     '$threeplid',
                                                                     NULL,
                                                                     '$now',
                                                                     '$userid',
                                                                     NULL,
                                                                     NULL,
                                                                     $pouchsizeid,
																	 '$bookingnumber',
																	 '$boltype',
																	 '$declaredvalue',
																	 '$actualweight',
																	 $pickupdate,
																	 1
                                                                   )

                                                                    ");

                                    //$systemlog->logInfo('WAYBILL',"Waybill Costing Update (Upload)","Waybill Number: ".$waybillnumber." | Bill Item No.: $billitemnumber  | Billing Reference: $billingreference | Freight Cost: $freightcost",$userid,$now);

                                    array_push($uploadedtxn, array($i,$waybillnumber));



                                        
                                        

                                       
                                    

                                }
                                else{
                                    if($checktxnnumber>0){
                                        array_push($skippedtxn, array($i,"Waybill Number already exists: ".$waybillnumber));
                                    }
                                }

                                




                            }



                            
                            echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='6'>UPLOADED TRANSACTIONS: W/O ERROR</th>
                                    </tr>
                                    <tr>
                                        <th>LINE</th>
                                        <th>ROW NUMBER</th>
                                        <th>BOL NUMBER</th
                                    </tr>
                                </thead>
                                <tbody>";
                                    $line = 1;
                                    for($x=0;$x<count($uploadedtxn);$x++){
                                        echo "<tr>
                                                   <td>$line</td>
                                                   <td>".utf8_encode($uploadedtxn[$x][0])."</td>
                                                   <td>".utf8_encode($uploadedtxn[$x][1])."</td>
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
                        echo "Unable to upload file. <b>Invalid Header Format for BOL Upload</b>.<br><br>";
                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-upload-template.xlsx'>here</a> to download file template<br>";
                            
                    }

                }
                else{
                    echo "Unable to upload <b>waybill</b>. No user permission.";
                }
            }
            else if($uploadtype=='CHARGESUPLOAD'){

                if($userstatusupdate==1||USERID==1){

        			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
        			@$excelObj = $excelReader->load($tmp);



                    $worksheet = $excelObj->getActiveSheet();
                    $lastRow = $worksheet->getHighestRow();
                    $lastCol = $worksheet->getHighestColumn();

                    $headerColumns = array(
                                            'BOL TRACKING NUMBER', //A
                                            'CHARGEABLE WEIGHT', //B
                                            'FREIGHT CHARGES', //C
                                            'ODA CHARGES',
                                            'VALUATION',
                                            'AGENT FEE',
                                            'ADDRESS CORRECTION',
                                            'BACKLOAD',
                                            'BREAK BULK',
                                            'CRATING',
                                            'DEMMURAGE',
                                            'DROP CHARGE',
                                            'EXPORT DECLARATION',
                                            'FOUL TRIP',
                                            'FUMIGATION',
                                            'WAREHOUSE HANDLING IN/OUT',
                                            'MANPOWER',
                                            'PICK AND PACK',
                                            'PULL OUT CHARGES',
                                            'REPACKING',
                                            'RESIDENTIAL DELIVERY',
                                            'SAMEDAY',
                                            'SPECIAL HANDLING',
                                            'TRUCK RENTAL',
                                            'ISLAND DELIVERY FEE',
                                            'TEMPORARY SURCHARGE'
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
                            $chargeableweight = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                            $freightcharges = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                            $odacharges = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                            $valuation = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                            $agentfee = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                            $addresscorrection = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                            $backload = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                            $breakbulk = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                            $crating = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                            $demmurage = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                            $dropcharge = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                            $exportdeclaration = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                            $foultrip = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                            $fumigation = convertToText(strtoupper($worksheet->getCell('O'.$i)->getValue()));
                            $warehouseinout = convertToText(strtoupper($worksheet->getCell('P'.$i)->getValue()));
                            $manpower = convertToText(strtoupper($worksheet->getCell('Q'.$i)->getValue()));
                            $pickandpack = convertToText(strtoupper($worksheet->getCell('R'.$i)->getValue()));
                            $pulloutcharges = convertToText(strtoupper($worksheet->getCell('S'.$i)->getValue()));
                            $repacking = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));
                            $residentialdelivery = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
                            $sameday = convertToText(strtoupper($worksheet->getCell('V'.$i)->getValue()));
                            $specialhandling = convertToText(strtoupper($worksheet->getCell('W'.$i)->getValue()));
                            $truckrental = convertToText(strtoupper($worksheet->getCell('X'.$i)->getValue()));
                            $islanddeliveryfee = convertToText(strtoupper($worksheet->getCell('Y'.$i)->getValue()));
                            $temporarysurcharge = convertToText(strtoupper($worksheet->getCell('Z'.$i)->getValue()));


                        

                            
                            

                            $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Waybill Number: ".$waybillnumber));
                            }
                            /*else{
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
                            }*/


                          

                            




                        }


                        if($checkvalues==true){

                            
                            $systemlog = new system_log();
                            $now = date('Y-m-d H:i:s');
                            $userid = USERID;
                                                           
                            for($i=2;$i<=$lastRow;$i++){
                                $waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
                                $chargeableweight = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                                $freightcharges = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                                $odacharges = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                                $valuation = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                                $agentfee = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                                $addresscorrection = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                                $backload = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                                $breakbulk = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));
                                $crating = convertToText(strtoupper($worksheet->getCell('J'.$i)->getValue()));
                                $demmurage = convertToText(strtoupper($worksheet->getCell('K'.$i)->getValue()));
                                $dropcharge = convertToText(strtoupper($worksheet->getCell('L'.$i)->getValue()));
                                $exportdeclaration = convertToText(strtoupper($worksheet->getCell('M'.$i)->getValue()));
                                $foultrip = convertToText(strtoupper($worksheet->getCell('N'.$i)->getValue()));
                                $fumigation = convertToText(strtoupper($worksheet->getCell('O'.$i)->getValue()));
                                $warehouseinout = convertToText(strtoupper($worksheet->getCell('P'.$i)->getValue()));
                                $manpower = convertToText(strtoupper($worksheet->getCell('Q'.$i)->getValue()));
                                $pickandpack = convertToText(strtoupper($worksheet->getCell('R'.$i)->getValue()));
                                $pulloutcharges = convertToText(strtoupper($worksheet->getCell('S'.$i)->getValue()));
                                $repacking = convertToText(strtoupper($worksheet->getCell('T'.$i)->getValue()));
                                $residentialdelivery = convertToText(strtoupper($worksheet->getCell('U'.$i)->getValue()));
                                $sameday = convertToText(strtoupper($worksheet->getCell('V'.$i)->getValue()));
                                $specialhandling = convertToText(strtoupper($worksheet->getCell('W'.$i)->getValue()));
                                $truckrental = convertToText(strtoupper($worksheet->getCell('X'.$i)->getValue()));
                                $islanddeliveryfee = convertToText(strtoupper($worksheet->getCell('Y'.$i)->getValue()));
                                $temporarysurcharge = convertToText(strtoupper($worksheet->getCell('Z'.$i)->getValue()));
                                

                                $chargeableweight = $chargeableweight>0?$chargeableweight:0;
                                $freightcharges = $freightcharges>0?$freightcharges:0;
                                $odacharges = $odacharges>0?$odacharges:0;
                                $valuation = $valuation>0?$valuation:0;
                                $agentfee = $agentfee>0?$agentfee:0;
                                $addresscorrection = $addresscorrection>0?$addresscorrection:0;
                                $backload = $backload>0?$backload:0;
                                $breakbulk = $breakbulk>0?$breakbulk:0;
                                $crating = $crating>0?$crating:0;
                                $demmurage = $demmurage>0?$demmurage:0;
                                $dropcharge = $dropcharge>0?$dropcharge:0;
                                $exportdeclaration = $exportdeclaration>0?$exportdeclaration:0;
                                $foultrip = $foultrip>0?$foultrip:0;
                                $fumigation = $fumigation>0?$fumigation:0;
                                $warehouseinout = $warehouseinout>0?$warehouseinout:0;
                                $manpower = $manpower>0?$manpower:0;
                                $pickandpack = $pickandpack>0?$pickandpack:0;
                                $pulloutcharges = $pulloutcharges>0?$pulloutcharges:0;
                                $repacking = $repacking>0?$repacking:0;
                                $residentialdelivery = $residentialdelivery>0?$residentialdelivery:0;
                                $sameday = $sameday>0?$sameday:0;
                                $specialhandling = $specialhandling>0?$specialhandling:0;
                                $truckrental = $truckrental>0?$truckrental:0;
                                $islanddeliveryfee = $islanddeliveryfee>0?$islanddeliveryfee:0;
                                $temporarysurcharge = $temporarysurcharge>0?$temporarysurcharge:0;


                                $zeroratedflag = 0;
                                $rs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                                while($obj=fetch($rs)){
                                    $zeroratedflag = $obj->zero_rated_flag;
                                }


                    
                                        
                                            query("  update txn_waybill 
                                                     set package_chargeable_weight='$chargeableweight', 
                                                         package_freight='$freightcharges',
                                                         oda_charges='$odacharges',
                                                         package_valuation='$valuation',
                                                         updated_date='$now', 
                                                         updated_by='$userid'
                                                      where waybill_number='$waybillnumber'
                                            ");

                                        
                                            query("delete from txn_waybill_other_charges where waybill_number='$waybillnumber'");

                                            $otherchargesarray = array();
                                            $totalothercharges = 0;
                                            if($agentfee>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Agent Fee','$agentfee','$now',$zeroratedflag)");
                                                $totalothercharges+=$agentfee;
                                            }
                                            if($addresscorrection>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Address Correction','$addresscorrection','$now',$zeroratedflag)");
                                                $totalothercharges+=$addresscorrection;
                                            }
                                            if($backload>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Backload','$backload','$now',$zeroratedflag)");
                                                $totalothercharges+=$backload;
                                            }
                                            if($breakbulk>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Break Bulk','$breakbulk','$now',$zeroratedflag)");
                                                $totalothercharges+=$breakbulk;
                                            }
                                            if($crating>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Crating','$crating','$now',$zeroratedflag)");
                                                $totalothercharges+=$crating;
                                            }
                                            if($demmurage>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Demmurage','$demmurage','$now',$zeroratedflag)");
                                                $totalothercharges+=$demmurage;
                                            }
                                            if($dropcharge>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Drop Charge','$dropcharge','$now',$zeroratedflag)");
                                                $totalothercharges+=$dropcharge;
                                            }
                                            if($exportdeclaration>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Export Declaration','$exportdeclaration','$now',$zeroratedflag)");
                                                $totalothercharges+=$exportdeclaration;
                                            }
                                            if($foultrip>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Foul Trip','$foultrip','$now',$zeroratedflag)");
                                                $totalothercharges+=$foultrip;
                                            }
                                            if($fumigation>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Fumigation','$fumigation','$now',$zeroratedflag)");
                                                $totalothercharges+=$fumigation;
                                            }
                                            if($warehouseinout>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Warehouse Handling In/Out','$warehouseinout','$now',$zeroratedflag)");
                                                $totalothercharges+=$warehouseinout;
                                            }
                                            if($manpower>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Manpower','$manpower','$now',$zeroratedflag)");
                                                $totalothercharges+=$manpower;
                                            }
                                            if($pickandpack>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Pick and Pack','$pickandpack','$now',$zeroratedflag)");
                                                $totalothercharges+=$pickandpack;
                                            }
                                            if($pulloutcharges>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Pull Out Charges','$pulloutcharges','$now',$zeroratedflag)");
                                                $totalothercharges+=$pulloutcharges;
                                            }
                                            if($repacking>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Repacking','$repacking','$now',$zeroratedflag)");
                                                $totalothercharges+=$repacking;
                                            }
                                            if($residentialdelivery>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Residential Delivery','$residentialdelivery','$now',$zeroratedflag)");
                                                $totalothercharges+=$residentialdelivery;
                                            }
                                            if($sameday>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Sameday','$sameday','$now',$zeroratedflag)");
                                                $totalothercharges+=$sameday;
                                            }
                                            if($specialhandling>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Special Handling','$specialhandling','$now',$zeroratedflag)");
                                                $totalothercharges+=$specialhandling;
                                            }
                                            if($truckrental>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Truck Rental','$truckrental','$now',$zeroratedflag)");
                                                $totalothercharges+=$truckrental;
                                            }
                                            if($islanddeliveryfee>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Island Delivery Fee','$islanddeliveryfee','$now',$zeroratedflag)");
                                                $totalothercharges+=$islanddeliveryfee;
                                            }
                                            if($temporarysurcharge>0){
                                                array_push($otherchargesarray,"('','$waybillnumber','Temporary Surcharge','$temporarysurcharge','$now',$zeroratedflag)");
                                                $totalothercharges+=$temporarysurcharge;
                                            }

                                            if(count($otherchargesarray)>0){
                                                query("insert into txn_waybill_other_charges values ".implode(', ',$otherchargesarray));
                                            }


                                            query("  update txn_waybill 
                                                     set package_chargeable_weight='$chargeableweight', 
                                                         package_freight='$freightcharges',
                                                         oda_charges='$odacharges',
                                                         package_valuation='$valuation',
                                                         total_other_charges_vatable='$totalothercharges',
                                                         total_regular_charges=round(package_freight+oda_charges+package_valuation,2),
                                                         subtotal = round(total_other_charges_vatable+total_regular_charges,2),
                                                         vat=if(zero_rated_flag!=1,round((total_other_charges_vatable+total_regular_charges)*.12,2),0),
                                                         total_amount=round(total_regular_charges+total_other_charges_vatable+total_other_charges_non_vatable+vat,2),
                                                         updated_date='$now', 
                                                         updated_by='$userid'
                                                      where waybill_number='$waybillnumber'
                                            ");

                                            $updatedetails = "Waybill Number: $waybillnumber | Chargeable Weight: $chargeableweight | Freight Charges: $freightcharges | ODA Charges: $odacharges | Valuation: $valuation | Agent Fee: $agentfee | Address Correction: $addresscorrection | Backload: $backload | Break Bulk: $breakbulk | Crating: $crating | Demmurage: $demmurage | Drop Charge: $dropcharge | Export Declaration: $exportdeclaration | Foul Trip: $foultrip | Fumigation: $fumigation | Warehouse Handling In/Out: $warehouseinout | Manpower: $manpower | Pick and Pack: $pickandpack | Pull Out Charges: $pulloutcharges | Repacking: $repacking | Residential Delivery: $residentialdelivery | Sameday: $sameday | Special Handling: $specialhandling | Truck Rental: $truckrental | Island Delivery Fee: $islanddeliveryfee | Temporary Surcharge: $temporarysurcharge | Total Other Charges: $totalothercharges | Zero Rated Flag: $zeroratedflag";

                                            $systemlog->logInfo('WAYBILL',"Waybill Charges Updated:",$updatedetails,$userid,$now);

                                            array_push($updatedtxn, array($i,$waybillnumber,$updatedetails));
                                        
                                    

                                
                                




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
                                                   <td>".utf8_encode($updatedtxn[$x][2])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

                          
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
                        echo "Unable to upload file. <b>Invalid Header Format for Charges Update</b>.<br><br>";
                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-charges-upload-template.xlsx'>here</a> to download file template<br>";
                            
                    }

                }
                else{
                    echo "Unable to upload <b>status update</b>. No user permission.";
                }

            }
			else if($uploadtype=='PACKAGEDIMENSIONUPLOAD'){

                if($userstatusupdate==1||USERID==1){

        			@$excelReader = PHPExcel_IOFactory::createReaderForFile($tmp);
        			@$excelObj = $excelReader->load($tmp);



                    $worksheet = $excelObj->getActiveSheet();
                    $lastRow = $worksheet->getHighestRow();
                    $lastCol = $worksheet->getHighestColumn();

                    $headerColumns = array(
                                            'WAYBILL NUMBER', //A
                                            'QUANTITY', //B
                                            'UOM', //C
                                            'LENGTH',
                                            'WIDTH',
                                            'HEIGHT',
                                            'ACTUAL WEIGHT',
                                            'VOL WEIGHT',
                                            'CBM'
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
                            $qty = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
                            $uomcode = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
                            $length = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
                            $width = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
                            $height = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
                            $actualweight = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
                            $volweight = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
                            $cbm = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));

                            $checktxnrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid Waybill Number: ".$waybillnumber));
                            }

							$checktxnrs = query("select * from unit_of_measure where code='$uomcode'");
                            if(getNumRows($checktxnrs)!=1){
                                $checkvalues = false;
                                array_push($lineerrors, array($i,"Invalid UOM Code: ".$uomcode." (Please refer to registered unit of measure codes in Maintenance)"));
                            }



                        }


                        if($checkvalues==true){

                            
                            $systemlog = new system_log();
                            $now = date('Y-m-d H:i:s');
                            $userid = USERID;

							$temp = [];
                                                           
                            for($i=2;$i<=$lastRow;$i++){
											$waybillnumber = convertToText(strtoupper($worksheet->getCell('A'.$i)->getValue()));
											$qty = convertToText(strtoupper($worksheet->getCell('B'.$i)->getValue()));
											$uomcode = convertToText(strtoupper($worksheet->getCell('C'.$i)->getValue()));
											$length = convertToText(strtoupper($worksheet->getCell('D'.$i)->getValue()));
											$width = convertToText(strtoupper($worksheet->getCell('E'.$i)->getValue()));
											$height = convertToText(strtoupper($worksheet->getCell('F'.$i)->getValue()));
											$actualweight = convertToText(strtoupper($worksheet->getCell('G'.$i)->getValue()));
											$volweight = convertToText(strtoupper($worksheet->getCell('H'.$i)->getValue()));
											$cbm = convertToText(strtoupper($worksheet->getCell('I'.$i)->getValue()));

											$qty = $qty>0?$qty:0;
											$length = $length>0?$length:0;
											$width = $width>0?$width:0;
											$height = $height>0?$height:0;
											$actualweight = $actualweight>0?$actualweight:0;
											$volweight = $volweight>0?$volweight:0;
											$cbm = $cbm>0?$cbm:0;
											

											
											$rs = query("select * from unit_of_measure where code='$uomcode'");
											while($obj=fetch($rs)){
												$uomid = $obj->id;
											}
											


                    
                                        
                                            if($qty>0){

												if(!in_array($waybillnumber,$temp)){
													array_push($temp,$waybillnumber);
													query("delete from txn_waybill_package_dimension where waybill_number='$waybillnumber'");
												}

												query("
														insert into txn_waybill_package_dimension(
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
																						values (
																									'$waybillnumber',
																									'$length',
																									'$width',
																									'$height',
																									'$qty',
																									'$volweight',
																									'$cbm',
																									'$uomcode',
																									'$actualweight'


																						)
													");
												
												$updatedetails = "Waybill Number: $waybillnumber | Quantity: $qty | UOM: $uomcode | Length: $length | Width: $width | Height: $height | Actual Weight: $actualweight | Vol Weight: $volweight | CBM: $cbm";
                                            	$systemlog->logInfo('WAYBILL',"Waybill Package Dimensions Updated:",$updatedetails,$userid,$now);

												array_push($updatedtxn, array($i,$waybillnumber,$updatedetails));
											}
											else{
												$updatedetails = "Quantity Less Than Zero -> Waybill Number: $waybillnumber | Quantity: $qty | UOM: $uomcode | Length: $length | Width: $width | Height: $height | Actual Weight: $actualweight | Vol Weight: $volweight | CBM: $cbm";
												array_push($skippedtxn, array($i,$waybillnumber,$updatedetails));
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
                                        <th>BOL NUMBER</th>
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
                                                   <td>".utf8_encode($updatedtxn[$x][2])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

							echo "<table border='1px' cellspacing='0px'>
                                <thead>
                                    <tr>    
                                        <th colspan='4'>SKIPPED DIMENSIONS</th>
                                    </tr>
                                    <tr>
                                        <th>Line</th>
                                        <th>Row No.</th>
                                        <th>BOL NUMBER</th>
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
                                                   <td>".utf8_encode($skippedtxn[$x][2])."</td>
                                              </tr>";
                                        $line++;
                                    }
                            echo   "</tbody></table><br><br>";

                          
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
                        echo "Unable to upload file. <b>Invalid Header Format for Charges Update</b>.<br><br>";
                        echo "Click <a class='pointer downloadwaybillfiletemplate' href='../file-templates/waybill-charges-upload-template.xlsx'>here</a> to download file template<br>";
                            
                    }

                }
                else{
                    echo "Unable to upload <b>status update</b>. No user permission.";
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