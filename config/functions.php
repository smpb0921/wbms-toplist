<?php
    include("db-function.php");
    function concatData($array,$concatwith){
        $temp = array();
        $len = count($array);
        $data = '';

        for($i=0;$i<$len;$i++){
            $wd = trim($array[$i]);
            if($wd!=''){
                array_push($temp, strtoupper($wd));
            }

        }
        $data = implode($concatwith, $temp);
        return $data;
    }

     function getShipperBilledBalance($shipperid){

        $amount = 0;

        $rs = query("select sum(
                                txn_billing_waybill.regular_charges+
                                txn_billing_waybill.other_charges_vatable+
                                txn_billing_waybill.other_charges_non_vatable+
                                txn_billing_waybill.vat
                                ) as amount
                     from txn_billing_waybill
                     left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                     where txn_billing_waybill.flag=1 and
                           txn_billing.shipper_id='$shipperid' and
                           txn_billing.status='POSTED' and
                           txn_billing.paid_flag=0
                  ");
         while($obj=fetch($rs)){
            $amount = $obj->amount;
        }

        return $amount;
    }


    function getShipperOutstandingBalance($shipperid){
        $waybillamount = 0;
        $paidwaybill = 0;
        $balance = 0;

        $rs = query("select sum(total_amount) as waybilltotal
                     from txn_waybill 
                     where shipper_id='$shipperid' and
                           txn_waybill.status!='VOID' and
                           txn_waybill.status!='LOGGED'
                   ");
        while($obj=fetch($rs)){
            $waybillamount = $obj->waybilltotal;
        }

        $rs = query("select sum(
                                txn_billing_waybill.regular_charges+
                                txn_billing_waybill.other_charges_vatable+
                                txn_billing_waybill.other_charges_non_vatable+
                                txn_billing_waybill.vat
                                ) as paidwaybillamount
                     from txn_billing_waybill
                     left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                     where txn_billing_waybill.flag=1 and
                           txn_billing.shipper_id='$shipperid' and
                           txn_billing.status='POSTED' and
                           txn_billing.paid_flag=1
                  ");
         while($obj=fetch($rs)){
            $paidwaybill = $obj->paidwaybillamount;
        }

        $balance = $waybillamount-$paidwaybill;

        return $balance;
    }


    function updateWaybillStatusBasedOnHistory($waybill){

        $rs = query("select * from txn_waybill_status_history where waybill_number='$waybill' order by created_date desc limit 1");

        if(getNumRows($rs)>0){
            
        }

    }

    function usedAsForeignKey($dbname,$tablename,$columnname,$val){
        $flag = 1;
        $rs = query("
                            call checkIfUsedAsForeignKey('".$dbname."','".$tablename."','".$columnname."',".$val.");


                    ");
        while($obj=fetch($rs)){
            $flag = $obj->flagresult;
        }
       
        //echo $flag;
        return $flag;
    }

    function getEmailAddressesTO($module,$locid,$agentid){
        $response = array();

        $condition = '';
        if($module=='MFLOAD'){
            $condition = " and (location_id='$locid' or agent_id='$agentid') ";
        }

        
        $rs = query("select * from email_address where module='$module' and type='TO' $condition");
        if(getNumRows($rs)==0){
             $rs = query("select * from email_address where module='$module' $condition limit 1");
        }
        while($obj=fetch($rs)){
            array_push($response, $obj->email);
        }
        return $response;
    }

    function getEmailAddressesCC($module,$locid,$agentid){
        $response = array();

        $condition = '';
        if($module=='MFLOAD'){
            $condition = " and (location_id='$locid' or agent_id='$agentid') ";
        }


        $rs = query("select * from email_address where module='$module' and type='CC' $condition");
        while($obj=fetch($rs)){
            array_push($response, $obj->email);
        }
        return $response;
    }

    function getLoadPlanDestination($ldpnumber){
        $destination = '';
        $rs = query("select group_concat(origin_destination_port.description order by origin_destination_port.description asc separator ', ') as destination
                     from txn_load_plan_destination 
                     left join origin_destination_port on origin_destination_port.id=txn_load_plan_destination.origin_destination_port_id
                     where txn_load_plan_destination.load_plan_number='$ldpnumber'
                     group by txn_load_plan_destination.load_plan_number 
                     ");
        while($obj=fetch($rs)){
            $destination = $obj->destination;
        }
        return $destination;
    }

    function Str4CSV($str){
        if(ContainsComma($str)){
            return "\"".$str."\"";
        }
        return $str;
    }

    function utfEncode($str){
        return utf8_encode($str);
    }

     function utfDecode($str){
        return utf8_decode($str);
    }

    function concatAddress($array){
        $temp = array();
        $len = count($array);
        $data = '';

        for($i=0;$i<$len;$i++){
            $wd = trim($array[$i]);
            if($wd!=''){
                array_push($temp, strtoupper($wd));
            }

        }
        $data = implode(', ', $temp);
        return $data;
    }

    


    function loadplanHasManifest($loadplannumber){
        $response = 1;

        $rs = query("select * from txn_manifest where load_plan_number='$loadplannumber' and status!='VOID'");

        if(getNumRows($rs)==0){
            $response = 0;
        }

        return $response;
    }

    
    function getUserOtherLocations($userid){
        $arr = array();
        $rs = query("select location_id, location.description as locname from user_location left join location on location.id=user_location.location_id where user_id='$userid'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->location_id.'%$&'.utfEncode($obj->locname));
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function getLoadPlanDestinations($ldpnumber){
        $arr = array();
        $rs = query("select origin_destination_port_id, 
                            origin_destination_port.description as destination 
                    from txn_load_plan_destination 
                    left join origin_destination_port on origin_destination_port.id=txn_load_plan_destination.origin_destination_port_id
                    where load_plan_number='$ldpnumber'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->origin_destination_port_id.'%$&'.utfEncode($obj->destination));
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function getWaybillHandlingInstructions($waybill){
        $arr = array();
        $rs = query("select handling_instruction_id, 
                            handling_instruction.description 
                     from txn_waybill_handling_instruction 
                     left join handling_instruction on handling_instruction.id=txn_waybill_handling_instruction.handling_instruction_id
                     where waybill_number='$waybill'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->handling_instruction_id.'%$&'.utfEncode($obj->description));
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function getBookingHandlingInstructions($txnnumber){
        $arr = array();
        $rs = query("select handling_instruction_id, 
                            handling_instruction.description 
                     from txn_booking_handling_instruction 
                     left join handling_instruction on handling_instruction.id=txn_booking_handling_instruction.handling_instruction_id
                     where booking_number='$txnnumber'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->handling_instruction_id.'%$&'.utfEncode($obj->description));
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function getBookingAccompanyingDocuments($txnnumber){
        $arr = array();
        $rs = query("select accompanying_document_id, 
                            accompanying_documents.description 
                     from txn_booking_document 
                     left join accompanying_documents on accompanying_documents.id=txn_booking_document.accompanying_document_id
                     where booking_number='$txnnumber'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->accompanying_document_id.'%$&'.utfEncode($obj->description));
        }
        $str = implode('#@$', $arr);
        return $str;
    }

    function getWaybillAccompanyingDocuments($txnnumber){
        $arr = array();
        $rs = query("select accompanying_document_id, 
                            accompanying_documents.description 
                     from txn_waybill_document 
                     left join accompanying_documents on accompanying_documents.id=txn_waybill_document.accompanying_document_id
                     where waybill_number='$txnnumber'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->accompanying_document_id.'%$&'.utfEncode($obj->description));
        }
        $str = implode('#@$', $arr);
        return $str;
    }
    
    function ContainsComma($str){
        foreach(str_split($str) as $s){
            if($s==","){
                return true;
            }
        }
        return false;
    }
   
    function getColor($colorcol){
        $color = getInfo('company_information',$colorcol,"where id=1");
        $color = str_replace('rgb', '', $color);
        $color = str_replace(')', '', $color);
        $color = str_replace('(', '', $color);
        $color = explode(',', $color);
        return $color;
    }

    function validateDate($date){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    function validateDateTime($date){
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') === $date;
    }

    function hasAccess($userid,$menuid){
        $response = 1;
        $rs = query("select * from user_rights where user_id='$userid' and menu_id='$menuid'");//if exist, user not allowed.
        if(getNumRows($rs)>0){
            $response = 0;
        }
        return $response;
    }

    

    function flcapital($str){
        $str = ucwords(strtolower($str));
        return $str;
    }
   

   

    function getNameOfUser($id){//GET NAME OF USER ID
        $fn='';
        $mn='';
        $ln='';
        $r = query("select * from user where id='$id' and active_flag=1");
        while($obj=fetch($r)){
            $fn=utfEncode($obj->first_name);
            $mn=utfEncode($obj->middle_name);
            $ln=utfEncode($obj->last_name);
        }
        return $fn.' '.$mn.' '.$ln;
    }

    function checkIfExist($tablename,$condition){
        $query = "select * from ".$tablename." ".$condition;
        $rs=query($query);
        $count = getNumRows($rs);
        if($count>=1){
            return true;
        }
        else{
           return false;
        }
    }


    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789@!";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    

    function userAccess($user,$menuid){
        $query = "select * from user_rights where user_id='$user' and menu_id='$menuid'";
        $rs = query($query);
        if(getNumRows($rs)>=1){
            return true;
        }
        else{
            return false;
        }
    }
    function getInfo($table,$column,$condition){
        $ret = '';
        $rs = query("select * from ".$table." ".$condition);
        while($obj=fetch($rs)){
            $ret = $obj->$column;
        }
        return $ret;
    }
    function trimString($str){
        $newstr =  str_replace("'", '', $str);
        $newstr =  str_replace('"', '', $newstr);
        return $newstr;
    }

    function dateFormat($date,$format){
        if($date=="0000-00-00 00:00:00"||$date==''||$date=='0000-00-00'){
            $date ='';
        }
        else{
           $date = date($format, strtotime($date));
        }
        return $date;
    }
   
     function formatDate($date){
        if($date=="0000-00-00 00:00:00"||$date==''||$date=='0000-00-00'){
            $date ='';
        }
        else{
           $date = date('M d, Y h:i:s A', strtotime($date));
        }
        return $date;
    }

    function convertWithDecimal($number,$decimalplaces){
        $temp = number_format($number,$decimalplaces,'.',',');

        if($number!=0){
                $num = number_format($number,$decimalplaces,'.',',');
                $num = trim($num,'0');
                if(substr($num, -1)=='.'){
                    $num = $num.'00';
                }
                else if(substr($num, -2,1)=='.'){
                    $num = $num.'0';
                }

                if(substr($num, 0,1)==='.'){
                    $num = '0'.$num;
                }
            
        }
        else{
            $num = number_format($number,$decimalplaces,'.',',');
        }

        if(strpos($temp, '.')===false){
            $num =  number_format($number,$decimalplaces,'.',',');
        }

        if($num==0){
            $num = 0;
        }
        return $num;
    }

    function convertWithDecimal2($number,$decimalplaces){
        $temp = number_format($number,$decimalplaces,'.','');

        if($number!=0){
                $num = number_format($number,$decimalplaces,'.','');
                $num = trim($num,'0');
                if(substr($num, -1)=='.'){
                    $num = $num.'00';
                }
                else if(substr($num, -2,1)=='.'){
                    $num = $num.'0';
                }

                if(substr($num, 0,1)==='.'){
                    $num = '0'.$num;
                }
            
        }
        else{
            $num = number_format($number,$decimalplaces,'.','');
        }

        if(strpos($temp, '.')===false){
            $num =  number_format($number,$decimalplaces,'.','');
        }

        if($num==0){
            $num = 0;
        }
        return $num;
    }
    
	

    function lineBreak($string, $maxwidth){
        $string = trim($string);
        if(strlen($string)<$maxwidth){
            $substr = $string;
        }
        else{
            $substr = preg_replace("/\s+?(\S+)?$/", '', substr($string, 0, $maxwidth));
        }
        return $substr;
        
    }


    function getNextTransactionNumber($transactiontypeId){
        $query = "select * from transaction_type where id='$transactiontypeId'";
        $rs = query($query);
        while($obj=fetch($rs)){
            $prefix = $obj->prefix;
            $nextnum = $obj->next_number_series;
        }
       
            if(trim($prefix)==''||$prefix==NULL){
                 $number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
            }
            else{
                $number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
            }
        
        $number = trim($number);
        return $number;
    }

    /*function getTransactionNumber($transactiontypeId){
        $query = "select * from transaction_type where id='$transactiontypeId'";
        $rs = query($query);
        while($obj=fetch($rs)){
            $prefix = $obj->prefix;
            $nextnum = $obj->next_number_series;
            //$month = $obj->month_flag;
        }
        $query = "update transaction_type set next_number_series = next_number_series + 1 where id='$transactiontypeId'";
        query($query);

        if($transactiontypeId==6||$transactiontypeId==7){
           $number = $prefix.date('Ymd').str_pad($nextnum, 3,'0',STR_PAD_LEFT);
        }
        else{
            if(trim($prefix)==''||$prefix==NULL){
                $number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
            }
            else{
                $number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
            }
        }
        
        return $number;
    }*/

    function getTransactionNumber($transactiontypeId){
        $flag = 1;
        $temp = 0;
        while($flag==1){
          $query = "select * from transaction_type where id='$transactiontypeId'";
          $rs = query($query);
          while($obj=fetch($rs)){
              $prefix = $obj->prefix;
              $nextnum = $obj->next_number_series;
          }
          //$number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
          if($transactiontypeId==6||$transactiontypeId==7){
               $number = $prefix.date('Ymd').str_pad($nextnum, 3,'0',STR_PAD_LEFT);
          }
          else{
               $number = $prefix.str_pad($nextnum, 8,'0',STR_PAD_LEFT);
          }
          $insertrs = mysql_query("insert into transaction_numbers_used(transaction_number) values('$number')");

          if($insertrs){
              $flag = 0;
              query("update transaction_type set next_number_series = next_number_series + 1 where id='$transactiontypeId'");
          }
          else{
              if($temp>1){
                  query("update transaction_type set next_number_series = next_number_series + 1 where id='$transactiontypeId'");
              }
          }
          $temp++;
        }



        return $number;
    }

    


  

    function getUserName($id){//GET NAME OF USER ID
        $fn='';
        $mn='';
        $ln='';
        $r = query("select * from user where id='$id'");
        while($obj=fetch($r)){
            $fn=utfEncode($obj->first_name);
            $mn=utfEncode($obj->middle_name);
            $ln=utfEncode($obj->last_name);
        }
        return $fn.' '.$mn.' '.$ln;
    }


    function getUserAssignedLocations($userid){
        $arr = array();
        $rs = query("select * from user_location where user_id='$userid'");
        while($obj=fetch($rs)){
            array_push($arr, $obj->location_id);
        }
        $str = implode("','", $arr);
        return "('".$str."')";
    }



    

    // function sendBookingRejectionEmail($booking_number, $rejection_reason = '') {

    //     require_once('../resources/PHPMailer/PHPMailerAutoload.php');

    //     $mail = new PHPMailer;

    //     try {

    //         $rs = query("SELECT 
    //                         txn_booking.booking_number,
    //                         txn_booking.shipper_company_name,
    //                         txn_booking.origin_id,
    //                         txn_booking.destination_id,
    //                         user.first_name,
    //                         user.last_name,
    //                         user.email_address as created_by_email
    //                     FROM txn_booking
    //                     LEFT JOIN user ON user.id = txn_booking.created_by
    //                     WHERE txn_booking.booking_number = '$booking_number'
    //                 ");

    //         if(getNumRows($rs) == 0){
    //             return array('success'=>false, 'message'=>'Booking not found');
    //         }

    //         $booking = fetch($rs);

    //         $mail->isSMTP();
    //         $mail->Host = "smtp.gmail.com";
    //         $mail->Port = 587;
    //         $mail->SMTPSecure = "tls";
    //         $mail->SMTPAuth = true;
    //         $mail->Username = "iamrussellpagatpatan@gmail.com";
    //         $mail->Password = "oezr frss ugvw oxdq";

    //         $mail->setFrom("tpincorporated@gmail.com", "WBMS Notifier");


    //         // Sending to the creator of booking transaction
    //         if(!empty($booking->created_by_email)){
    //             $mail->addAddress(
    //                 $booking->created_by_email,
    //                 $booking->first_name . ' ' . $booking->last_name
    //             );
    //         }

    //         // System configured TO emails
    //         $emailsTO = getEmailAddressesTO('BOOKING_REJECTION', '', '');
    //         foreach($emailsTO as $email){
    //             $mail->addAddress($email);
    //         }

    //         // CC emails
    //         $emailsCC = getEmailAddressesCC('BOOKING_REJECTION', '', '');
    //         foreach($emailsCC as $email){
    //             $mail->addCC($email);
    //         }

    //         /* ================= EMAIL ================= */

    //         $mail->isHTML(true);

    //         $mail->Subject = "Booking Rejected - " . $booking_number;

    //         $bodyContent = "
    //             <h3>Booking Rejected</h3>
    //             <p><strong>Booking Number:</strong> {$booking_number}</p>
    //             <p><strong>Shipper:</strong> {$booking->shipper_company_name}</p>
    //             <p><strong>Rejected Reason:</strong> {$rejection_reason}</p>
    //             <br>
    //             <p>Please review and resubmit the booking transaction.</p>
    //             <br>
    //             <small>This is a system generated email.</small>
    //         ";

    //         $mail->Body = $bodyContent;

    //         if($mail->send()){
    //             return array('success'=>true, 'message'=>'Email sent successfully');
    //         }else{
    //             return array('success'=>false, 'message'=>$mail->ErrorInfo);
    //         }

    //     } catch (Exception $e) {
    //         return array('success'=>false, 'message'=>$e->getMessage());
    //     }
    // }


    function sendBookingRejectionEmail($booking_number, $rejection_reason = '') {

        require_once('../resources/PHPMailer/PHPMailerAutoload.php');

        $mail = new PHPMailer;

        try {

            // Pull SMTP config from email_configuration table
            $configRs = query("SELECT * FROM email_configuration WHERE active_flag = 1 LIMIT 1");

            if(getNumRows($configRs) == 0){
                return array('success' => false, 'message' => 'No active email configuration found');
            }

            $config = fetch($configRs);

            // Get booking + creator details
            $rs = query("SELECT 
                            txn_booking.booking_number,
                            txn_booking.shipper_company_name,
                            txn_booking.origin_id,
                            txn_booking.destination_id,
                            user.first_name,
                            user.last_name,
                            user.email_address as created_by_email
                        FROM txn_booking
                        LEFT JOIN user ON user.id = txn_booking.created_by
                        WHERE txn_booking.booking_number = '$booking_number'
                    ");

            if(getNumRows($rs) == 0){
                return array('success' => false, 'message' => 'Booking not found');
            }

            $booking = fetch($rs);

            // SMTP setup from DB config
            $mail->isSMTP();
            $mail->Host       = $config->host;
            $mail->Port       = $config->port;
            $mail->SMTPSecure = $config->encryption;
            $mail->SMTPAuth   = true;
            $mail->Username   = $config->username;
            $mail->Password   = $config->password;

            // Sender from DB config
            $mail->setFrom($config->email_sent_from, $config->email_sender);

            // Send to booking creator
            if(!empty($booking->created_by_email)){
                $mail->addAddress(
                    $booking->created_by_email,
                    $booking->first_name . ' ' . $booking->last_name
                );
            }

            // Additional TO/CC emails (if configured)
            $emailsTO = getEmailAddressesTO('BOOKING_REJECTION', '', '');
            foreach($emailsTO as $email){
                $mail->addAddress($email);
            }

            $emailsCC = getEmailAddressesCC('BOOKING_REJECTION', '', '');
            foreach($emailsCC as $email){
                $mail->addCC($email);
            }

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Booking Rejected - " . $booking_number;
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <h3 style='color: #c0392b;'>Booking Transaction Rejected</h3>
                    <hr>
                    <p><strong>Booking Number:</strong> {$booking_number}</p>
                    <p><strong>Shipper:</strong> {$booking->shipper_company_name}</p>
                    <p><strong>Rejection Reason:</strong> {$rejection_reason}</p>
                    <br>
                    <p>Please review the rejection reason and resubmit the booking transaction.</p>
                    <br>
                    <small style='color: #888;'>This is a system generated email. Please do not reply.</small>
                </div>
            ";

            if($mail->send()){
                return array('success' => true, 'message' => 'Email sent successfully');
            } else {
                return array('success' => false, 'message' => $mail->ErrorInfo);
            }

        } catch (Exception $e) {
            return array('success' => false, 'message' => $e->getMessage());
        }
    }
   



?>