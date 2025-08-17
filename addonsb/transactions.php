<?php   

#Includes
    include_once("conn - Copy.php"); 
    include("../config/connection.php");
    include("../config/functions.php");
    include("../classes/manifest.class.php");
    include("../classes/manifest-waybill.class.php");
    include("../classes/manifest-package-code.class.php");
    include("../classes/waybill-status-history.class.php"); 
    include("../classes/waybill.class.php");
    include("../classes/waybill-other-charges.class.php");
    include("../classes/waybill-package-dimension.class.php"); 
    include("../classes/waybill-handling-instruction.class.php");
    include("../classes/system-log.class.php");
    include("../classes/shipper-pickup-address.class.php");
    include("../classes/booking.class.php");
#Includes END
 

if(isset($_POST['GetTrans'])){ 
    $qry = $conn1->prepare("SELECT trx.id,trx.module,trx.keyword,trx.description,mt.code,
                            trx.reasonflag,trx.postkey,trx.postvalue,trx.requiresnumber,
                            trx.shipper_email_subject,trx.shipper_email_body,trx.shipper_sms_message,
                            trx.consignee_email_subject,trx.shipper_email_body,trx.consignee_sms_message,
                            trx.send_status_flag
                           from mod_sms_maintenance_trans trx
                           left join movement_type mt on mt.id = trx.ReferenceMovementType");
    $qry->execute();
    $qry->bind_result($id,$mod,$key,$dsc,$typ,$rsn,$pky,$pvl,$rqn,$ShipperEmailSubject,$ShipperEmailBody,$ShipperSMSMessage,$ConsigneeEmailSubject,$ConsigneeEmailBody,$ConsigneeSMSMessage,$sendFlag);
    $arr = array();
    while($qry->fetch()){
        array_push($arr,
            array(
                "ID"=>$id,
                "Module"=>$mod,
                "KeyWord"=>$key,
                "Description"=>$dsc,
                "MovementType"=>$typ,
                "ReasonFlag"=>$rsn,
                "PostKey"=>$pky,
                "PostValue"=>$pvl,
                "RequiresNumber"=>$rqn,
                "ShipperEmailSubject"=>utf8_encode($ShipperEmailSubject),
                "ShipperEmailBody"=>utf8_encode($ShipperEmailBody),
                "ShipperSMSMessage"=>utf8_encode($ShipperSMSMessage),
                "ConsigneeEmailSubject"=>utf8_encode($ConsigneeEmailSubject),
                "ConsigneeEmailBody"=>utf8_encode($ConsigneeEmailBody),
                "ConsigneeSMSMessage"=>utf8_encode($ConsigneeSMSMessage),
                "SendFlag"=>$sendFlag
            )
        );
    }
    print_r(json_encode($arr));
}

if(isset($_POST['GetsBookingDriverDenial'])) {

    $arr = array();
    $qry = $conn1->prepare("SELECT * FROM 
                            (SELECT booking_number,TIMESTAMPDIFF(MINUTE,approved_date,NOW()) MinPassed from txn_booking
                            where status ='APPROVED') X
                            where MinPassed > 1");     
        $qry->execute();
        $qry->bind_result($bookingNo,$MinutesPassedAfterApproved);
        while($qry->fetch()){
            array_push($arr,$bookingNo);
        }
    $qry->close();
    print_r(json_encode($arr));

}

if(isset($_POST['GetsPostedBookings'])){
    
    $arr = array();
    $qry = $conn1->prepare("SELECT group_concat(id),group_concat(concat('- ',booking_number) separator '\r\n') bookings,supervisor_mobile_number,driver_mobile_number from mod_sms_booking_notifications
                            where sent_to_supervisor_flag = 0
                            and sent_to_driver_flag = 0
                            and driver_reply_flag = 0
                            and driver_rejection_sent_to_supervisor_flag = 0
                            group by supervisor_mobile_number");
        $qry->execute();
        $qry->bind_result($ID,$BookingNum,$SuperVisorNum,$DriverNum);
        while($qry->fetch()){
            array_push($arr,array(
                "IDs" => $ID,
                "BookingNumber" => utf8_encode($BookingNum),
                "SupervisorMobile" => str_replace(" ","",str_replace("-","",$SuperVisorNum)),
                "DriverMobile" => str_replace(" ","",str_replace("-","",$DriverNum))
            ));
        }
    $qry->close();
    print_r(json_encode($arr));

}

if(isset($_POST['SMSNotsSecondStep'])){

    $arr = array();
    $qry = $conn1->prepare("SELECT group_concat(msbn.id),
	group_concat(
		concat('- ',msbn.booking_number,
			   ' - ',upper(bkn.shipper_name),
			   ' - ', shipper_pickup_street_address,
			   ' - Time Ready: ', date_format(bkn.time_ready,'%m/%d/%Y %k:%S%p') ,
			   ' - ', bkn.remarks, 
			   ' - ', bkn.shipment_description, 
               ' - ', 'Created By: ', concat(u.first_name,' ',u.last_name)) separator '\r\n') 
            bookings,supervisor_mobile_number,driver_mobile_number from mod_sms_booking_notifications msbn
                            left join txn_booking bkn on bkn.booking_number = msbn.booking_number
                            left join user u on u.id = bkn.created_by
                            where sent_to_supervisor_flag = 1 
                            and send_to_driver = 1
                            and sent_to_driver_flag = 0 
                            and driver_reply_flag = 0 
                            and driver_rejection_sent_to_supervisor_flag = 0 
                            and bkn.status = 'WAITING FOR RESPONSE'
                            group by msbn.booking_number,driver_mobile_number"); 

        $qry->execute();
        $qry->bind_result($ID,$BookingNum,$SuperVisorNum,$DriverNum);
        while($qry->fetch()){
            array_push($arr,array(
                "IDs" => $ID,
                "BookingNumber" => utf8_encode($BookingNum),
                "SupervisorMobile" => str_replace(" ","",str_replace("-","",$SuperVisorNum)),
                "DriverMobile" => str_replace(" ","",str_replace("-","",$DriverNum))
            ));
        }
    $qry->close();
    print_r(json_encode($arr));
    
}
//str_replace("-","",$DriverNum)
//str_replace(" ","",str_replace("-","",$DriverNum))

if(isset($_POST['SMSNotsThirdStep'])){

    $arr = array();
    $qry = $conn1->prepare("SELECT group_concat(msbn.id),group_concat(concat('- ',msbn.booking_number,' Driver: ',STRTITLE(bkn.driver)) separator '\r\n') bookings,supervisor_mobile_number,driver_mobile_number,group_concat(bkn.id) bookingIds from mod_sms_booking_notifications msbn
                            left join txn_booking bkn on bkn.booking_number = msbn.booking_number
                            where sent_to_supervisor_flag = 1
                            and sent_to_driver_flag = 1
                            and driver_reply_flag = 0
                            and driver_rejection_sent_to_supervisor_flag = 0
                            and bkn.status = 'WAITING FOR RESPONSE'
                            and TIMESTAMPDIFF(MINUTE,sent_to_driver_timestamp,NOW()) > 30 
                            group by supervisor_mobile_number");
        $qry->execute();
        $qry->bind_result($ID,$BookingNum,$SuperVisorNum,$DriverNum,$bknIds);
        while($qry->fetch()){
            array_push($arr,array(
                "IDs" => $ID,
                "BookingNumber" => utf8_encode($BookingNum),
                "SupervisorMobile" => str_replace(" ","",str_replace("-","",$SuperVisorNum)),
                "DriverMobile" => str_replace(" ","",str_replace("-","",$DriverNum))
            ));
            foreach (explode(",",$bknIds) as $key => $value) {
                $booking = fetch(query("SELECT * from txn_booking where id = $value"));
                $msbnObj = fetch(query("SELECT * from mod_sms_booking_notifications where booking_number = '{$booking->booking_number}'"));
                query("UPDATE txn_booking set status ='APPROVED',driver_notified = 0, supervisor_notified = 0 where id = {$value}");
                query("INSERT into txn_booking_status_history(booking_number,status_description,date,contact,driver,driver_mobile_number,assigned_by,time_ready,created_date,remarks)
                VALUES('{$booking->booking_number}','APPROVED',now(),'{$msbnObj->driver}','{$msbnObj->driver}','{$msbnObj->driver_mobile_number}','{$msbnObj->assigned_by}','{$booking->time_ready}',now(),'DENIED BY DRIVER IN GIVEN TIME');");
            }
            foreach (explode(",",$ID) as $key => $value){ 
                query("UPDATE mod_sms_booking_notifications set send_to_driver = 0,sent_to_driver_flag = 0,driver_reply_flag = 0,driver_rejection_sent_to_supervisor_flag = 0 where id = {$value}");
            }
        }
    $qry->close();
    print_r(json_encode($arr));
    
}

if(isset($_POST['acknowledgeBooking'])){
    if($_POST['acknowledgeBooking'] == 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
        $bookingid = escapeString($_POST['bookingid']);
        $checktxnrs = query("SELECT * from txn_booking where id='$bookingid'");
        $ShipperSendables = array();
        $ConsigneeSendables = array();
        $remarks = escapeString($_POST['remarks']);
        $pickedupby = isset($_POST['sendername'])?escapeString($_POST['sendername']):$remarks; 
        $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
        $updatedBy =  ucwords($_POST["sendername"]);


        if(getNumRows($checktxnrs) > 0) {

            $booking = fetch($checktxnrs);

            if($booking->status == "WAITING FOR RESPONSE"){
                $msbn = query("SELECT * from mod_sms_booking_notifications where booking_number = '{$booking->booking_number}'");
                $msbnObj = fetch($msbn); 

                $supervisor = $msbnObj->supervisor_mobile_number;
                $qry = $conn1->prepare("UPDATE mod_sms_booking_notifications set driver_reply_flag = 1, driver_reply_timestamp = NOW() where booking_number = ?");

                    $qry->bind_param("s",$booking->booking_number);
                    if($qry->execute()) {
                        //, last_status_update_remarks = 'PICKED UP BY: {$updatedBy}', last_status_update = now()
                        query("UPDATE txn_booking set status = 'ACKNOWLEDGED', last_status_update_remarks = '{$remarks}', last_status_update = now() where booking_number = '{$booking->booking_number}'");
                        query("INSERT into txn_booking_status_history(booking_number,status_description,date,contact,driver,driver_mobile_number,assigned_by,time_ready,created_date,remarks)
                        VALUES('{$booking->booking_number}','ACKNOWLEDGED',now(),'{$msbnObj->driver}','{$updatedBy}','{$sender}','{$msbnObj->assigned_by}','{$booking->time_ready}',now(), 'ACKNOWLEDGED BY {$updatedBy}');");
                        $dorh = ucwords(strtolower($booking->driver));
                        print_r(json_encode(
                            array(
                                "Success" => true,
                                "Message"=>"Booking with Transaction Number {$booking->booking_number} has been Acknowledged by the Driver or Helper: {$dorh}.",
                                "Status"=>"Acknowledged;{$supervisor}",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    else {
                        print_r(json_encode(
                            array(
                                "Success" => false,
                                "Message"=>"Unable to Acknowledge the Booking with Transaction Number {$booking->booking_number}. Please try again.",
                                "Status"=>"N/A",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    $qry->close();
            }
            else {
                print_r(json_encode(
                    array(
                        "Success" => false,
                        "Message"=>"Booking with Transaction Number {$booking->booking_number} current status is not yet WAITING FOR RESPONSE. Unable to proceed with transaction.",
                        "Status"=>"N/A",
                        "Shippers" => $ShipperSendables,
                        "Consignees" => $ConsigneeSendables
                    )
                ));
            }
        }
        else {

            print_r(json_encode(
                array(
                    "Success" => false,
                    "Message"=>"Booking with Transaction Number {$booking->booking_number} does not exists.",
                    "Status"=>"N/A",
                    "Shippers" => $ShipperSendables,
                    "Consignees" => $ConsigneeSendables
                )
            ));

        }

    }
}


if(isset($_POST['rejectBooking'])){
    if($_POST['rejectBooking'] == 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
        $bookingid = escapeString($_POST['bookingid']);
        $checktxnrs = query("SELECT * from txn_booking where id='$bookingid'");
        $ShipperSendables = array();
        $ConsigneeSendables = array();
        $remarks = escapeString($_POST['remarks']);
        $pickedupby = isset($_POST['sendername'])?escapeString($_POST['sendername']):$remarks;
        $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
        $updatedBy =  ucwords($_POST["sendername"]);


        if(getNumRows($checktxnrs) > 0){

            $booking = fetch($checktxnrs);

            if($booking->status == "WAITING FOR RESPONSE"){
                $msbn = query("SELECT * from mod_sms_booking_notifications where booking_number = '{$booking->booking_number}'");
                $msbnObj = fetch($msbn);
                
    /*
    sent_to_supervisor_flag = 1 , send_to_driver = 1 , sent_to_driver_flag = 0  , driver_reply_flag = 0  , driver_rejection_sent_to_supervisor_flag = 0 

    */

                $supervisor = $msbnObj->supervisor_mobile_number;
                $qry = $conn1->prepare("UPDATE mod_sms_booking_notifications set sent_to_supervisor_flag = 1 , send_to_driver = 1 , sent_to_driver_flag = 0  , driver_reply_flag = 0  , driver_rejection_sent_to_supervisor_flag = 0   where booking_number = ?");
                    $qry->bind_param("s",$booking->booking_number);
                    if($qry->execute()) {
                        query("UPDATE txn_booking set status = 'APPROVED', last_status_update_remarks = '{$remarks}', last_status_update = now(),driver_notified = 0, supervisor_notified = 0 where booking_number = '{$booking->booking_number}'");         
                        query("INSERT into txn_booking_status_history(booking_number,status_description,date,contact,driver,driver_mobile_number,assigned_by,time_ready,created_date,remarks)
                        VALUES('{$booking->booking_number}','APPROVED',now(),'{$msbnObj->driver}','{$updatedBy}','{$sender}','{$msbnObj->assigned_by}','{$booking->time_ready}',now(),'DENIED BY {$updatedBy}');");
                        print_r(json_encode(
                            array(
                                "Success" => true,
                                "Message"=>"Booking with Transaction Number {$booking->booking_number} has been Rejected by the Driver or Helper: {$booking->driver}.",
                                "Status"=>"Rejected;{$supervisor}",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    else {
                        print_r(json_encode(
                            array(
                                "Success" => false,
                                "Message"=>"Unable to Acknowledge the Booking with Transaction Number {$booking->booking_number}. Please try again.",
                                "Status"=>"N/A",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    $qry->close();
            }
            else {
                print_r(json_encode(
                    array(
                        "Success" => false,
                        "Message"=>"Booking with Transaction Number {$booking->booking_number} current status is not yet WAITING FOR RESPONSE. Unable to proceed with transaction.",
                        "Status"=>"N/A",
                        "Shippers" => $ShipperSendables,
                        "Consignees" => $ConsigneeSendables
                    )
                ));
            }
        }
        else {

            print_r(json_encode(
                array(
                    "Success" => false,
                    "Message"=>"Booking with Transaction Number {$booking->booking_number} does not exists.",
                    "Status"=>"N/A",
                    "Shippers" => $ShipperSendables,
                    "Consignees" => $ConsigneeSendables
                )
            ));

        }
        
    }
}


if(isset($_POST['missedBooking'])){

    if($_POST['missedBooking']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
        $bookingid = escapeString($_POST['bookingid']);
        $checktxnrs = query("SELECT * from txn_booking where id='$bookingid'");
        $ShipperSendables = array();
        $ConsigneeSendables = array();
        $remarks = escapeString($_POST['remarks']);
        $pickedupby = isset($_POST['sendername'])?escapeString($_POST['sendername']):$remarks;
        $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
        $updatedBy =  ucwords($_POST["sendername"]);
        
        
        
        if(getNumRows($checktxnrs) > 0){

            $booking = fetch($checktxnrs);

            if($booking->status == "ACKNOWLEDGED"){
                $msbn = query("SELECT * from mod_sms_booking_notifications where booking_number = '{$booking->booking_number}'");
                $msbnObj = fetch($msbn); 

                $supervisor = $msbnObj->supervisor_mobile_number;
                $qry = $conn1->prepare("UPDATE mod_sms_booking_notifications set sent_to_supervisor_flag = 1 , send_to_driver = 1 , sent_to_driver_flag = 0  , driver_reply_flag = 0  , driver_rejection_sent_to_supervisor_flag = 0   where booking_number = ?");
                    $qry->bind_param("s",$booking->booking_number);
                    if($qry->execute()) {
                        query("UPDATE txn_booking set status = 'MISSED PICKUP', last_status_update_remarks = '{$remarks}', last_status_update = now() where booking_number = '{$booking->booking_number}'");
                        query("INSERT into txn_booking_status_history(booking_number,status_description,date,contact,driver,driver_mobile_number,assigned_by,time_ready,created_date,remarks)
                        VALUES('{$booking->booking_number}','MISSED PICKUP',now(),'{$msbnObj->driver}','{$updatedBy}','{$sender}','{$msbnObj->assigned_by}','{$booking->time_ready}',now(),'MISSED PICK UP BY {$updatedBy}');");
                        print_r(json_encode(
                            array(
                                "Success" => true,
                                "Message"=>"Booking with Transaction Number {$booking->booking_number} has been Rejected by the Driver or Helper: {$booking->driver}.",
                                "Status"=>"Rejected;{$supervisor}",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    else {
                        print_r(json_encode(
                            array(
                                "Success" => false,
                                "Message"=>"Unable to Acknowledge the Booking with Transaction Number {$booking->booking_number}. Please try again.",
                                "Status"=>"N/A",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                    $qry->close();
            }
            else {
                print_r(json_encode(
                    array(
                        "Success" => false,
                        "Message"=>"Booking with Transaction Number {$booking->booking_number} current status is not yet WAITING FOR RESPONSE. Unable to proceed with transaction.",
                        "Status"=>"N/A",
                        "Shippers" => $ShipperSendables,
                        "Consignees" => $ConsigneeSendables
                    )
                ));
            }
        }
        else {

            print_r(json_encode(
                array(
                    "Success" => false,
                    "Message"=>"Booking with Transaction Number {$booking->booking_number} does not exists.",
                    "Status"=>"N/A",
                    "Shippers" => $ShipperSendables,
                    "Consignees" => $ConsigneeSendables
                )
            ));

        }


    }

}


if(isset($_POST['updateStatusPickedUp'])){
    if($_POST['updateStatusPickedUp']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){ 
            $now = date('Y-m-d H:i:s');
            $bookingid = escapeString($_POST['bookingid']);
            $actualpickupdate = date('Y-m-d H:i:s', strtotime(isset($_POST['actualpickupdate'])?escapeString($_POST['actualpickupdate']):$now));
            $remarks = escapeString($_POST['remarks']);
            $pickedupby = isset($_POST['sendername'])?escapeString($_POST['sendername']):$remarks;
            $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
            $updatedBy =  ucwords($_POST["sendername"]);
            $userid = 1;
            $systemlog = new system_log();
            $bookingnumber = ''; 
            $ShipperSendables = array();
            $ConsigneeSendables = array();

            $checktxnrs = query("SELECT * from txn_booking where id='$bookingid'  ");
 
            if(getNumRows($checktxnrs)>=1) {

                if(validateDateTime($actualpickupdate)==1&&$actualpickupdate!='1970-01-01 08:00:00'){
                    while($obj=fetch($checktxnrs)){
                        $bookingnumber = $obj->booking_number;
                        $currentstat = $obj->status;
                        $timeready = $obj->time_ready;
                    }
                    
                    if ($currentstat != "PICKED UP") {
                            
                        if($currentstat == "ACKNOWLEDGED") {

                                            
                            $arrsender = explode(" ",$updatedBy);

                            $fetchedUser = @fetch(@query("SELECT ifnull(id,0) id from personnel where first_name = '{$arrsender[0]}' and last_name = '{$arrsender[1]}' and contact_number ='{$sender}'"))->id;
                                    
                            if(strlen($fetchedUser) <= 0 || $fetchedUser == null || $fetchedUser <= 0 ) {
                                $genUser = substr($sender,8);
                                $genUser = "{$arrsender[0]}.{$arrsender[1]}{$genUser}";
                                query("INSERT INTO user(first_name,middle_name,last_name,username,password) values('{$arrsender[0]}','{$arrsender[1]}','$sender','$genUser',md5('0987654321')) on duplicate key update first_name = values(first_name), last_name = values(last_name), middle_name = values(middle_name)");
                                $fetchedUser =  mysql_insert_id();
                            }
                            
                            $rs = query("UPDATE txn_booking set status='PICKED UP', actual_pickup_date='$actualpickupdate', updated_by = '$fetchedUser', pickup_by='$pickedupby', pickup_remarks='$remarks', last_status_update_remarks = 'PICKED UP BY: {$updatedBy}', last_status_update = now() where id='$bookingid' ");
                            if($rs){

                                $msbn = query("SELECT * from mod_sms_booking_notifications where booking_number = '{$bookingnumber}' ");
                                $msbnObj = fetch($msbn);
                                $driver = ucwords(strtolower($msbnObj->driver));
                                $supervisormobile = $msbnObj->supervisor_mobile_number;
                                $updatedBy = ucwords(strtolower($updatedBy));
                                $historyInserted = query("INSERT into txn_booking_status_history(booking_number,status_description,date,contact,driver,driver_mobile_number,assigned_by,time_ready,created_date,remarks)
                                   VALUES('{$bookingnumber}','PICKED UP',now(),'{$driver}','{$updatedBy}','{$sender}','{$msbnObj->assigned_by}','{$timeready}',now(),'{$remarks}');");
                                
                                if($historyInserted){

                                    $systemlog = new system_log();
                                    $systemlog->logInfo('BOOKING','SMS Booking Status Update: Picked Up',"Booking Number: ".$bookingnumber." | Actual Pickup Date: $actualpickupdate | Picked-up By: $pickedupby | Remarks: $remarks",$fetchedUser,$now); 
                                     
                                    print_r(json_encode(
                                        array(
                                            "Success" => true,
                                            "Message"=>"Booking with Transaction Number $bookingnumber status is now PICKED-UP.",
                                            "Status"=>"PICKED UP;{$supervisormobile}",
                                            "Shippers" => $ShipperSendables,
                                            "Consignees" => $ConsigneeSendables
                                        )
                                    ));

                                }
         
                            }
                        }
                        else { 
                            print_r(json_encode(
                                array(
                                    "Success" => false,
                                    "Message"=>"Booking with Transaction Number $bookingnumber status is not yet ACKNOWLEDGED.",
                                    "Status"=>"N/A",
                                    "Shippers" => $ShipperSendables,
                                    "Consignees" => $ConsigneeSendables
                                )
                            ));
                        }
                    }
                    else { 
                        print_r(json_encode(
                            array(
                                "Success" => false,
                                "Message"=>"Booking with Transaction Number $bookingnumber status is already PICKED-UP. System not updated.",
                                "Status"=>"N/A",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                }
                else { 
                    print_r(json_encode(
                        array(
                            "Success" => false,
                            "Message"=>"Invalid Pickup Date for Booking Transaction",
                            "Status"=>"N/A",
                            "Shippers" => $ShipperSendables,
                            "Consignees" => $ConsigneeSendables
                        )
                    )); 
                }

            }
            else { 
                print_r(json_encode(
                    array(
                        "Success" => false,
                        "Message"=>"Invalid Booking Transaction Number",
                        "Status"=>"N/A",
                        "Shippers" => $ShipperSendables,
                        "Consignees" => $ConsigneeSendables
                    )
                ));
            }

            
            
    }
}


if(isset($_POST['SyncDriverHelper'])){
    $json = array();
    $qry = $conn1->prepare("SELECT first_name,last_name,contact_number FROM personnel union all select first_name,last_name,mobile contact_number from user where freight_supervisor_flag = 1 or courier_supervisor_flag = 1");
        $qry->execute();
        $qry->bind_result($first,$last,$num);
        while($qry->fetch()){
            array_push($json, array(
                "FirstName" => $first,
                "LastName" => $last,
                "Number" => $num
            ));
        }
    print_r(json_encode($json));
}

if(isset($_POST['SMSNotsUpdatesFlag'])){
    $flag = $_POST['flag'];
    $flag_ts = $_POST['flagTS'];
    $val = $_POST['value'];
    $msbnId = $_POST['id'];
    $good = false;
    foreach (explode(",",$msbnId) as $key => $value) {
        if($flag == "sent_to_supervisor_flag") {
            query("UPDATE txn_booking set supervisor_notified = 1 where booking_number = (select booking_number from mod_sms_booking_notifications where id = $value limit 1)");
        }
        else if($flag == "sent_to_driver_flag") {
            query("UPDATE txn_booking set driver_notified = 1 where booking_number = (select booking_number from mod_sms_booking_notifications where id = $value limit 1)");
        }
        $qry = $conn1->prepare("UPDATE mod_sms_booking_notifications set {$flag}=?, {$flag_ts}=now() where id = ?");
            $qry->bind_param("ii",$val,$value);
            $good = $qry->execute();
        $qry->close();
    }

    if($good){

        print_r(json_encode(array(
            "Success" => true,
            "Message" => "$flag has been Updated!"
        )));

    }
    else {
        print_r(json_encode(array(
            "Success" => false,
            "Message" => "Error Occured, please try again. ".mysqli_error($conn1).$qry->error
        )));
    }

}


if(isset($_POST['UpdateTransMaintenance'])){
    if($_POST["UpdateTransMaintenance"] == "#UpdTrxKey=6623P^MOZCRM./MPOXXZQ"){  
        
        $ses = (strlen($_POST['ses'])>0 ? $_POST['ses'] : "");
        $seb = (strlen($_POST['seb'])>0 ? $_POST['seb'] : "");
        $ssm = (strlen($_POST['ssm'])>0 ? $_POST['ssm'] : "");
        $ces = (strlen($_POST['ces'])>0 ? $_POST['ces'] : "");
        $ceb = (strlen($_POST['ceb'])>0 ? $_POST['ceb'] : "");
        $csm = (strlen($_POST['csm'])>0 ? $_POST['csm'] : "");
        $trxId = $_POST['id'];
        $qry =  $conn1->prepare("UPDATE mod_sms_maintenance_trans set shipper_email_subject = ?, shipper_email_body = ? , shipper_sms_message = ?, consignee_email_subject = ?, consignee_email_body = ?, consignee_sms_message = ?
                                where id = ?");
                $qry->bind_param("ssssssi",$ses,$seb,$ssm,$ces,$ceb,$csm,$trxId);
                $good = $qry->execute();
                if($good){
                    print_r(json_encode(array(
                        "Success" => true,
                        "Message" => "Message Format has been Updated"
                    )));
                }
                else { 
                    print_r(json_encode(array(
                        "Success" => false,
                        "Message" => "Failed to Update Message Format, please try again."
                    )));
                }
        $qry->close();
    }
}

if(isset($_POST['ValidateTrans'])){
    $trans = strtolower($_POST['Trans']);
    $transNum = $_POST['TransNum'];
    $remarks = $_POST['Remarks'];
    $senderNum = $_POST['Phone'];
    $requiresReason = $_POST['ReasonFlag'];
    $trxId = 0; 
    if($requiresReason) {
        $rsnId = 0;
        $reason = trim(strtoupper($remarks));
        $prep = "SELECT id from status where Code=?";
        $qry = $conn1->prepare($prep); 
        $qry->bind_param("s",$reason);
        $qry->execute();
        $qry->bind_result($rsnId);
        $qry->fetch();
        $qry->close();
        if($rsnId==null || $rsnId==0) { 
            die("-1");
        }
    }

    $prep = "SELECT id,status from txn_{$trans} where {$trans}_number = ? or {$trans}_number = ?";
    $qry = $conn1->prepare($prep); 
    $qry->bind_param("si",$transNum,($trans == "manifest" ? str_replace("MFT","",$transNum) : intval($transNum)));
    $qry->execute();
    $qry->bind_result($trxId,$status);
    $qry->fetch();
    $qry->close();
 
    if($trxId == null) {
       $trxId=0; 
    }

    print_r($trxId); 
}

if(isset($_POST['updateManifestStatus'])){
    if($_POST['updateManifestStatus']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
            $manifestid = escapeString($_POST['manifestid']);
            $manifestnumber = escapeString($_POST['manifestnumber']);
            $status = escapeString($_POST['status']);
            $now = date('Y-m-d H:i:s');
            $etd = date('Y-m-d H:i:s', strtotime(escapeString(isset($_POST['etd'])?$_POST['etd']:$now)));
            $eta = date('Y-m-d H:i:s', strtotime(escapeString(isset($_POST['eta'])?$_POST['eta']:$now)));
            $mawbbl = '';
            $remarks = addslashes(escapeString($_POST['remarks']));
            $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
            $updatedBy = ucwords($_POST["sendername"]);

            $ShipperSendables = array();
            $ConsigneeSendables = array();

            $now = date('Y-m-d H:i:s');
            $userid = 1;
            $systemlog = new system_log();
            $loadplannumber = '';
            $smsreceivers =  $sender;
            $emailreceivers = '';
  
            $checktxnrs = query("SELECT mnf.id,mnf.manifest_number,mnf.load_plan_number,mnf.status,ifnull(concat(lower(u.email_address),'|',concat(u.first_name,' ',u.last_name)),'') user_email,
            group_concat(distinct if(cnc.send_email_flag=1,concat(cnc.email_address,'|',cnc.contact_name),'')) ConsigneeEmail,group_concat(distinct if(cnc.send_sms_flag=1,cnc.mobile_number,'')) ConsigneeMobile,
            group_concat(distinct if(spc.send_email_flag=1,concat(spc.email_address,'|',spc.contact_name),'')) ShipperEmail,group_concat(distinct if(spc.send_sms_flag=1,spc.mobile_number,'')) ShipperMobile
            
            from txn_manifest mnf
            left join txn_manifest_waybill mfw on mfw.manifest_number = mnf.manifest_number
            left join txn_waybill wyb on wyb.waybill_number = mfw.waybill_number
            left join consignee cns on cns.id = wyb.consignee_id
            left join consignee_contact cnc on cnc.consignee_id = cns.id
            left join shipper shp on shp.id = wyb.shipper_id 
            left join shipper_contact spc on spc.shipper_id = shp.id 
            left join user u on u.id = mnf.created_by
            where mnf.id='$manifestid'  group by mnf.manifest_number ");




            if(getNumRows($checktxnrs)>=1) {

                if((validateDateTime($etd)==1&&$etd!='1970-01-01 08:00:00')||$status!='TRANSFERRED') {
                    if((validateDateTime($eta)==1&&$eta!='1970-01-01 08:00:00')||$status!='TRANSFERRED') {
                        
                        $status = fetch(query("SELECT description from status where type='MANIFEST' and code='$status'"))->description;

                        while($obj=fetch($checktxnrs)) {
                            $manifestnumber = $obj->manifest_number;
                            $loadplannumber = $obj->load_plan_number;
                            $smsreceivers =  $sender.",".($status == 'OUT FOR DELIVERY' ? $obj->ConsigneeMobile.','.$obj->ShipperMobile : '');
                            $emailreceivers = ( $status == 'LOADED' ? $obj->user_email.','.'bogrimen@tpincorporated.com|Bhen Ogrimen' : '' );
                            $currentstat = $obj->status; 
                        }

                        $reqStat = '';
                        if($status == 'TRANSFERRED'){
                            $reqStat = 'POSTED';
                        }
                        else if($status == 'LOADED'){
                            $reqStat = 'TRANSFERRED'; 
                        }
                        else if($status == 'RETRIEVED'){ 
                            $reqStat = 'LOADED';
                        } 
                        else if($status == 'OUT FOR DELIVERY'){
                            $reqStat = 'RETRIEVED'; 
                        }
                        
                        if($currentstat == "POSTED" || $currentstat == "TRANSFERRED" || $currentstat == "LOADED" || $currentstat == "RETRIEVED" || $currentstat == "OUT FOR DELIVERY" ) {
                            
                            if(strtoupper($currentstat)!=strtoupper($status)) {
                                                    
                                $arrsender = explode(" ",$updatedBy);

                                $fetchedUser = @fetch(@query("SELECT ifnull(id,0) id from personnel where first_name = '{$arrsender[0]}' and last_name = '{$arrsender[0]}' and contact_number ='{$sender}'"))->id;
                                    
                                if(strlen($fetchedUser) <= 0 || $fetchedUser == null || $fetchedUser <= 0 ) {
                                    $genUser = substr($sender,8);
                                    $genUser = "{$arrsender[0]}.{$arrsender[1]}{$genUser}"; 
                                    query("INSERT INTO user(first_name,middle_name,last_name,username,password) values('{$arrsender[0]}','{$arrsender[1]}','$sender','$genUser',md5('0987654321')) on duplicate key update first_name = values(first_name), last_name = values(last_name), middle_name = values(middle_name)");
                                    $fetchedUser =  mysql_insert_id();
                                }

                                $rs = query("UPDATE txn_manifest set status='$status', last_status_update_remarks='$remarks', updated_date='$now', updated_by = $fetchedUser where id='$manifestid'"); 
                                if($rs){
                                    
                                    $qrys = query("SELECT
                                                   mnf.manifest_number,
                                                   group_concat(concat('- ',mfw.waybill_number) separator '\r') Waybills, 
                                                   trim(shp.company_name) ShipperCom,
                                                   cns.account_name ConsigneeAcc,
                                                   cns.company_name ConsigneeCom,
                                                   group_concat(distinct if(spc.send_email_flag=1,concat(spc.email_address,'|',spc.contact_name),'')) ShipperEmail,
                                                   group_concat(distinct if(spc.send_sms_flag=1,spc.mobile_number,'')) ShipperMobile
                                                   from txn_manifest mnf
                                                   left join txn_manifest_waybill mfw on mfw.manifest_number = mnf.manifest_number
                                                   left join txn_waybill wyb on wyb.waybill_number = mfw.waybill_number
                                                   left join consignee cns on cns.id = wyb.consignee_id
                                                   left join consignee_contact cnc on cnc.consignee_id = cns.id  
                                                   left join shipper shp on shp.id = wyb.shipper_id 
                                                   left join shipper_contact spc on spc.shipper_id = shp.id 
                                                   left join user u on u.id = mnf.created_by
                                                   where mnf.manifest_number = '{$manifestnumber}'
                                                   
                                                   group by cns.id,shp.id");

                                    while($sobj = fetch($qrys)){ 
                                        array_push($ShipperSendables,array(
                                            "Waybills" => $sobj->Waybills,
                                            "ConsigneeAccount" => utf8_encode($sobj->ConsigneeAcc),
                                            "ConsigneeCompany" => utf8_encode($sobj->ConsigneeCom),
                                            "ShipperEmail" => $sobj->ShipperEmail,
                                            "ShipperMobile" => $sobj->ShipperMobile,
                                            "ShipperCompany" => utf8_encode($sobj->ShipperCom)
                                            ));
                                    }
                                    

                                    $qryc = query("SELECT
                                                   mnf.manifest_number,
                                                   group_concat(concat('- ',mfw.waybill_number) separator '\r') Waybills,
                                                   trim(shp.company_name) ShipperCom,
                                                   cns.account_name ConsigneeAcc,
                                                   cns.company_name ConsigneeCom,
                                                   group_concat(distinct if(cnc.send_email_flag=1,concat(cnc.email_address,'|',cnc.contact_name),'')) ConsigneeEmail,
                                                   group_concat(distinct if(cnc.send_sms_flag=1,cnc.mobile_number,'')) ConsigneeMobile
                                                   
                                                   from txn_manifest mnf
                                                   left join txn_manifest_waybill mfw on mfw.manifest_number = mnf.manifest_number
                                                   left join txn_waybill wyb on wyb.waybill_number = mfw.waybill_number
                                                   left join consignee cns on cns.id = wyb.consignee_id
                                                   left join consignee_contact cnc on cnc.consignee_id = cns.id  
                                                   left join shipper shp on shp.id = wyb.shipper_id 
                                                   left join shipper_contact spc on spc.shipper_id = shp.id 
                                                   left join user u on u.id = mnf.created_by
                                                   where mnf.manifest_number = '{$manifestnumber}'
                                                   
                                                   group by cns.id,shp.id");
                                    while ($cobj = fetch($qryc)) {         
                                        array_push($ConsigneeSendables,array( 
                                            "Waybills" => $cobj->Waybills,
                                            "ConsigneeMobile" => $cobj->ConsigneeMobile,
                                            "ConsigneeEmail" => $cobj->ConsigneeEmail,
                                            "ShipperCompany" => utf8_encode($cobj->ShipperCom),
                                            "ConsigneeAccount" =>  utf8_encode($cobj->ConsigneeAcc),
                                            "ConsigneeCompany" =>  utf8_encode($cobj->ConsigneeCom)
                                            ));
                                    }


                                    $systemlog = new system_log();
                                    $systemlog->logInfo('MANIFEST',"SMS Manifest Status Update: $status","Manifest Number: ".$manifestnumber." | Mawbbl: $mawbbl | ETD: $etd |  ETA: $eta | Remarks: $remarks",$fetchedUser,$now);
            
                                    
                                    if($status=='TRANSFERRED'){
                                        query("UPDATE txn_load_plan set mawbl_bl='$mawbbl', etd='$etd', eta='$eta',updated_by=$fetchedUser where load_plan_number='$loadplannumber'");
                                    }
            
                                    $waybillstathistory = new txn_waybill_status_history();
                                    $loopwaybillrs = query("SELECT txn_manifest_waybill.waybill_number 
                                                            from txn_manifest_waybill
                                                            left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                                                            where txn_manifest_waybill.manifest_number='$manifestnumber' and 
                                                                txn_waybill.status not in (SELECT status from no_update_status)"); 
                                    while($obj3=fetch($loopwaybillrs)) {

                                        $waybillstathistory->insert(array('',$obj3->waybill_number,$status,$status,$remarks,$now,$fetchedUser,'NULL',$manifestnumber,'MANIFEST','NULL','NULL')); 
                                        //TO BE ADDED updated_by
                                        query("UPDATE txn_waybill set status='$status', last_status_update_remarks='$remarks', updated_date = '$now', updated_by = $fetchedUser where waybill_number='$obj3->waybill_number'");

                                    }
                                                
                                    print_r(json_encode(
                                        array(
                                            "Success" => true,
                                            "Message" => "Manifest with Transaction Number {$manifestnumber} status is now {$status}",
                                            "Status" => $status,
                                            "Shippers" => $ShipperSendables,
                                            "Consignees" => $ConsigneeSendables
                                        )
                                    ));

                                }
                            }
                            else { 
                                print_r(
                                    json_encode(
                                        array("Success"=>false,
                                            "Message"=>"Manifest with Transaction Number $manifestnumber current status is already {$status}. System not updated.",
                                            "Status"=>$status,
                                            "Shippers" => $ShipperSendables,
                                            "Consignees" => $ConsigneeSendables)));  
                            }
                        }
                        else {

                            print_r(
                                json_encode(
                                    array("Success"=>false,
                                        "Message"=>"Manifest with Transaction Number $manifestnumber current status is not yet POSTED. Cannot proceed with transaction update",
                                        "Status"=>$status,
                                        "Shippers" => $ShipperSendables,
                                        "Consignees" => $ConsigneeSendables)));  

                        }
                        
                    }
                    else {
                        
                        print_r(
                            json_encode(
                                array("Success"=>false,
                                      "Message"=>"Invalid Estimated Time of Arrival.",
                                      "Status"=>$status,
                                      "Shippers" => $ShipperSendables,
                                      "Consignees" => $ConsigneeSendables)));   
                    }
                }
                else {
                    print_r(
                        json_encode(
                            array("Success"=>false,
                                  "Message"=>"Invalid Estimated Time of Departure.",
                                  "Status"=>$status,
                                  "Shippers" => $ShipperSendables,
                                  "Consignees" => $ConsigneeSendables)));    
                }
            }
            else {
                
                print_r(
                    json_encode(
                        array("Success"=>false,
                              "Message"=>"Invalid Manifest Number.",
                              "Status"=>$status,
                              "Shippers" => $ShipperSendables,
                              "Consignees" => $ConsigneeSendables)));     

            }
    }
}
 
if(isset($_POST['updateStatusWaybill'])){
    if($_POST['updateStatusWaybill']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@') {
        $waybillid = escapeString($_POST['waybillid']);
        $status = escapeString($_POST['status']);
        $remarks = escapeString($_POST['remarks']);
        $sender = str_replace('+63','0',$_POST["sendermobilenum"]);
        $updatedBy =  ucwords($_POST["sendername"]);

        $now = date('Y-m-d H:i:s');
        $receivedby = escapeString(trim($_POST['remarks']));
        $receiveddate = escapeString(trim(isset($_POST['receiveddate']) ? $_POST['receiveddate'] : $now));
        $receiveddate = datetimeString($receiveddate);
        $checkdate = dateString($receiveddate);
        $userid = 1;
        $systemlog = new system_log();
        $waybillstathistory = new txn_waybill_status_history();
        $waybillnumber = '';
        $statusdesc = '';
        $ShipperSendables = array();
        $ConsigneeSendables = array();

        $searStat = $status == "UND" ? strtoupper($remarks) : $status;

        $checktxnrs = query("SELECT * from txn_waybill where id='$waybillid'");
        
			$checkstatusrs = query("SELECT * from status where code='$searStat' and type='WAYBILL'");

		if(getNumRows($checkstatusrs) > 0) {
			while($obj=fetch($checkstatusrs)) {
				$statusdesc = $obj->description;
            }
             
			$checktxnrs = query("SELECT wyb.id, wyb.waybill_number, wyb.status,ifnull(concat(lower(u.email_address),'|',concat(u.first_name,' ',u.last_name)),'') user_email,
            group_concat(distinct if(cnc.send_email_flag=1,concat(cnc.email_address,'|',cnc.contact_name),'')) ConsigneeEmail,group_concat(distinct if(cnc.send_sms_flag=1,cnc.mobile_number,'')) ConsigneeMobile,
            group_concat(distinct if(spc.send_email_flag=1,concat(spc.email_address,'|',spc.contact_name),'')) ShipperEmail,group_concat(distinct if(spc.send_sms_flag=1,spc.mobile_number,'')) ShipperMobile
            from txn_waybill wyb  
            left join consignee cns on cns.id = wyb.consignee_id
            left join consignee_contact cnc on cnc.consignee_id = cns.id
            left join shipper shp on shp.id = wyb.shipper_id 
            left join shipper_contact spc on spc.shipper_id = shp.id
            left join user u on u.id = wyb.created_by
            
            where wyb.id = '$waybillid' group by wyb.waybill_number");
			if((validateDate($checkdate)==1 && $checkdate!='1970-01-01') || $status!='DEL') {
				if(getNumRows($checktxnrs) > 0) {
					while($obj=fetch($checktxnrs)) {
						$waybillnumber = $obj->waybill_number;
                        $currentwaybillstatus = $obj->status;
                        $smsreceivers =  $sender.",".($status == 'DELIVERED' ? $obj->ConsigneeMobile.','.$obj->ShipperMobile : '');
                        $emailreceivers = 'bogrimen@tpincorporated.com'; //$obj->user_email;
					}
					$checkifstatusupdateallowedrs = query("SELECT * from txn_waybill
								                            where id='$waybillid' and 
								                            status not in (SELECT status from no_update_status)");
                             
                    if(getNumRows($checkifstatusupdateallowedrs) > 0) {
                                
                        if(strtoupper($currentwaybillstatus)!=strtoupper($statusdesc)) {
                            $checkifinloggedmanifest = query("SELECT txn_manifest_waybill.waybill_number,
                                                                txn_manifest_waybill.manifest_number 
                                                                from txn_manifest_waybill
                                                                left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
                                                                where txn_manifest.status='LOGGED' and 
                                                                txn_manifest_waybill.waybill_number='$waybillnumber'");
                            if(getNumRows($checkifinloggedmanifest) > 0) {
                                while($obj=fetch($checkifinloggedmanifest)){
                                                $mftnumber = $obj->manifest_number;
                                }
                                print_r(
                                    json_encode(
                                        array("Success"=>false,
                                            "Message"=>"Waybill {$waybillnumber} is in Pending Manifest with Transaction Number {$mftnumber}. Cannot proceed with Transaction Update.",
                                            "Status"=>"{$statusdesc}",
                                            "SMSReceipients"=>$sender,
                                            "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen"))); 
                            }
                            else {
                                $checkifinactiveloadplan = query("SELECT txn_load_plan_waybill.waybill_number,
                                                                                    txn_load_plan_waybill.load_plan_number 
                                                                            from txn_load_plan_waybill
                                                                            left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number 
                                                                            where txn_load_plan.status!='DISPATCHED' and
                                                                                    txn_load_plan.status!='VOID' and  
                                                                                    txn_load_plan_waybill.waybill_number='$waybillnumber'");
                                if(getNumRows($checkifinactiveloadplan)>0) {
                                    while($obj=fetch($checkifinactiveloadplan)){
                                        $ldpnumber = $obj->load_plan_number;
                                    }
                                    print_r(
                                        json_encode(
                                            array("Success"=>false,
                                                "Message"=>"Waybill {$waybillnumber} is in Active Load Plan with Transaction Number {$ldpnumber}. Cannot proceed with Transaction Update.",
                                                "Status"=>"{$statusdesc}",
                                                "SMSReceipients"=>$sender,
                                                "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));
                                }
                                else {
 

                                    $isUndStatus = fetch(query("SELECT if(search_results>0,1,0) isUndStatus from (SELECT count(id) search_results from mod_sms_und_reasons where code = '$searStat') xx "))->isUndStatus;
                                                                    
                                    if( ($currentwaybillstatus == "OUT FOR DELIVERY" && $statusdesc == "DELIVERED" ) || $isUndStatus ) {
                                                            
                                        /**** OK ****/
 
                                            $arrsender = explode(" ",$updatedBy);

                                            $fetchedUser = @fetch(@query("SELECT ifnull(id,0) id from personnel where first_name = '{$arrsender[0]}' and last_name = '{$arrsender[0]}' and contact_number ='{$sender}'"))->id;
                                    
                                            if(strlen($fetchedUser) <= 0 || $fetchedUser == null || $fetchedUser <= 0 ) {
                                                $genUser = substr($sender,8);
                                                $genUser = "{$arrsender[0]}.{$arrsender[1]}{$genUser}";
                                                query("INSERT INTO user(first_name,middle_name,last_name,username,password) values('{$arrsender[0]}','{$arrsender[1]}','$sender','$genUser',md5('0987654321')) on duplicate key update first_name = values(first_name), last_name = values(last_name), middle_name = values(middle_name)");
                                                $fetchedUser =  mysql_insert_id();
                                            }
               
                                            $qrys = query("SELECT 
                                                            group_concat(concat('- ',wyb.waybill_number) separator '\r') Waybills, 
                                                            trim(shp.company_name) ShipperCom, 
                                                            cns.account_name ConsigneeAcc,
                                                            cns.company_name ConsigneeCom,
                                                            group_concat(distinct if(spc.send_email_flag=1,concat(spc.email_address,'|',spc.contact_name),'')) ShipperEmail,
                                                            group_concat(distinct if(spc.send_sms_flag=1,spc.mobile_number,'')) ShipperMobile
                                                            from txn_waybill wyb 
                                                            left join consignee cns on cns.id = wyb.consignee_id
                                                            left join consignee_contact cnc on cnc.consignee_id = cns.id  
                                                            left join shipper shp on shp.id = wyb.shipper_id 
                                                            left join shipper_contact spc on spc.shipper_id = shp.id  
                                                            where wyb.waybill_number = '{$waybillnumber}'
                                                            
                                                            group by cns.id,shp.id");

                                            while($sobj = fetch($qrys)){ 
                                                array_push($ShipperSendables,array(
                                                    "Waybills" => $sobj->Waybills,
                                                    "ConsigneeAccount" => utf8_encode($sobj->ConsigneeAcc),
                                                    "ConsigneeCompany" => utf8_encode($sobj->ConsigneeCom),
                                                    "ShipperEmail" => $sobj->ShipperEmail,
                                                    "ShipperMobile" => $sobj->ShipperMobile,
                                                    "ShipperCompany" => utf8_encode($sobj->ShipperCom)
                                                    ));
                                            }

                                            $qryc = query("SELECT 
                                                            group_concat(concat('- ',wyb.waybill_number) separator '\r') Waybills,
                                                            trim(shp.company_name) ShipperCom,
                                                            cns.account_name ConsigneeAcc,
                                                            cns.company_name ConsigneeCom,
                                                            group_concat(distinct if(cnc.send_email_flag=1,concat(cnc.email_address,'|',cnc.contact_name),'')) ConsigneeEmail,
                                                            group_concat(distinct if(cnc.send_sms_flag=1,cnc.mobile_number,'')) ConsigneeMobile
                                                            
                                                            from txn_waybill wyb  
                                                            left join consignee cns on cns.id = wyb.consignee_id
                                                            left join consignee_contact cnc on cnc.consignee_id = cns.id  
                                                            left join shipper shp on shp.id = wyb.shipper_id 
                                                            left join shipper_contact spc on spc.shipper_id = shp.id  
                                                            where wyb.waybill_number = '{$waybillnumber}'
                                                            
                                                            group by cns.id,shp.id");
                                            while ($cobj = fetch($qryc)) {         
                                                array_push($ConsigneeSendables,array( 
                                                    "Waybills" => $cobj->Waybills,
                                                    "ConsigneeMobile" => $cobj->ConsigneeMobile,
                                                    "ConsigneeEmail" => $cobj->ConsigneeEmail,
                                                    "ShipperCompany" => utf8_encode($cobj->ShipperCom),
                                                    "ConsigneeAccount" =>  utf8_encode($cobj->ConsigneeAcc),
                                                    "ConsigneeCompany" =>  utf8_encode($cobj->ConsigneeCom)
                                                    ));
                                            }
  
                                    

                                            $rcvcols = ", received_by=NULL, received_date=NULL";
                                            if($receiveddate!='NULL'||$receivedby!='NULL'){
                                                $rcvcols = ", received_by='$receivedby', received_date='$receiveddate'";
                                            } 
                                            $rs = query("UPDATE txn_waybill set status='$statusdesc', updated_date='$now', updated_by = $fetchedUser, last_status_update_remarks='$remarks' $rcvcols where id='$waybillid'");
                                            if($rs){
                                                $waybillstathistory->insert(array('',$waybillnumber,$statusdesc,$statusdesc,$remarks,$now,$fetchedUser,'NULL','NULL','NULL',$receivedby,$receiveddate));
                                                $systemlog = new system_log();
                                                $systemlog->logInfo('WAYBILL',"SMS Waybill Status Update: $status","Waybill Number: ".$waybillnumber." | Status: $status - $statusdesc  |  Remarks: $remarks",$fetchedUser,$now);
                                               
                                                print_r(json_encode(
                                                    array(
                                                        "Success" => true,
                                                        "Message"=>"Waybill Number $waybillnumber status is now $statusdesc.",
                                                        "Status"=>"{$statusdesc}",
                                                        "Shippers" => $ShipperSendables,
                                                        "Consignees" => $ConsigneeSendables
                                                    )
                                                ));

                                            }
                                        
                                        /**** OK *****/
                                    }
                                    else {  
                                        print_r(json_encode(
                                            array(
                                                "Success" => false,
                                                "Message"=>"Waybill Number {$waybillnumber} status is not yet OUT FOR DELIVERY.",
                                                "Status"=>"{$statusdesc}",
                                                "Shippers" => $ShipperSendables,
                                                "Consignees" => $ConsigneeSendables
                                            )
                                        ));
                                    }
                                    
                                }
                                
                            }
                        }
                        else {         
                            print_r(json_encode(
                                array(
                                    "Success" => false,
                                    "Message"=>"Waybill Number {$waybillnumber} status is already $statusdesc.",
                                    "Status"=>"{$statusdesc}",
                                    "Shippers" => $ShipperSendables,
                                    "Consignees" => $ConsigneeSendables
                                )
                            ));
                        }
                    }
                    else {
                        print_r(json_encode(
                            array(
                                "Success" => false,
                                "Message"=>"Waybill already reached the final possible status.",
                                "Status"=>"{$statusdesc}",
                                "Shippers" => $ShipperSendables,
                                "Consignees" => $ConsigneeSendables
                            )
                        ));
                    }
                }
				else {
                    
                    print_r(json_encode(
                        array(
                            "Success" => false,
                            "Message"=>"Invalid Waybill Transaction Number.",
                            "Status"=>"{$statusdesc}",
                            "Shippers" => $ShipperSendables,
                            "Consignees" => $ConsigneeSendables
                        )
                    )); 
                                         
                }
            }
            else {
                print_r(json_encode(
                    array(
                        "Success" => false,
                        "Message"=>"Invalid Waybill Receive Date.",
                        "Status"=>"{$statusdesc}",
                        "Shippers" => $ShipperSendables,
                        "Consignees" => $ConsigneeSendables
                    )
                ));  
            }
        }
		else {
            print_r(json_encode(
                array(
                    "Success" => false,
                    "Message"=>"Invalid Status for Waybill Transaction.",
                    "Status"=>"{$statusdesc}",
                    "Shippers" => $ShipperSendables,
                    "Consignees" => $ConsigneeSendables
                )
            ));
        }
    }
} 
