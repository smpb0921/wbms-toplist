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
                            trx.reasonflag,trx.postkey,trx.postvalue,trx.requiresnumber
                           from mod_sms_maintenance_trans trx
                           left join movement_type mt on mt.id = trx.ReferenceMovementType");
    $qry->execute();
    $qry->bind_result($id,$mod,$key,$dsc,$typ,$rsn,$pky,$pvl,$rqn);
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
            )
        );
    }
    print_r(json_encode($arr));
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
        $prep = "select id from status where Code=?";
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

    $prep = "select id,status from txn_{$trans} where {$trans}_number=?";
    $qry = $conn1->prepare($prep); 
    $qry->bind_param("s",$transNum);
    $qry->execute();
    $qry->bind_result($trxId,$status);
    $qry->fetch();
    $qry->close();
 
    if($trxId==null) {
       $trxId=0; 
    }
    else {

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
            $remarks = escapeString($_POST['remarks']);
            $sender = $_POST["sendermobilenum"];

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
            where mnf.id='$manifestid' and (mnf.status <> 'LOGGED' or mnf.status <> 'VOID') group by mnf.manifest_number ");




            if(getNumRows($checktxnrs)>=1) {

                if((validateDateTime($etd)==1&&$etd!='1970-01-01 08:00:00')||$status!='TRANSFERRED') {
                    if((validateDateTime($eta)==1&&$eta!='1970-01-01 08:00:00')||$status!='TRANSFERRED') {
                        
                        $status = fetch(query("select description from status where type='MANIFEST' and code='$status'"))->description;

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
                        
                        if($currentstat != "LOGGED" && $currentstat != "VOID" ) {
                            
                            if(strtoupper($currentstat)!=strtoupper($status)) {

                                $rs = query("update txn_manifest set status='$status', last_status_update_remarks='$remarks', updated_date='$now' where id='$manifestid'"); 
                                if($rs){
            
                                    $systemlog = new system_log();
                                    $systemlog->logInfo('MANIFEST',"SMS Manifest Status Update: $status","Manifest Number: ".$manifestnumber." | Mawbbl: $mawbbl | ETD: $etd |  ETA: $eta | Remarks: $remarks",$userid,$now);
            
                                    
                                    if($status=='TRANSFERRED'){
                                        query("update txn_load_plan set mawbl_bl='$mawbbl', etd='$etd', eta='$eta' where load_plan_number='$loadplannumber'");
                                    } 
            
                                    $waybillstathistory = new txn_waybill_status_history();
                                    $loopwaybillrs = query("select txn_manifest_waybill.waybill_number 
                                                            from txn_manifest_waybill
                                                            left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
                                                            where txn_manifest_waybill.manifest_number='$manifestnumber' and 
                                                                txn_waybill.status not in (select status from no_update_status)"); 
                                    while($obj3=fetch($loopwaybillrs)) { 
                                        $waybillstathistory->insert(array('',$obj3->waybill_number,$status,$status,$remarks,$now,$userid,'NULL',$manifestnumber,'MANIFEST','NULL','NULL'));
                                        
                                        query("update txn_waybill set status='$status', last_status_update_remarks='$remarks' where waybill_number='$obj3->waybill_number'");
                                    }
                                    print_r(
                                        json_encode(
                                            array("Success"=>true,
                                                "Message"=>"Manifest with Transaction Number {$manifestnumber} status is now $status",
                                                "Status"=>$status,
                                                "SMSReceipients"=>$smsreceivers,
                                                "EmailReceipients"=>$emailreceivers)));  
                                }
                            }
                            else { 
                                print_r(
                                    json_encode(
                                        array("Success"=>false,
                                            "Message"=>"Manifest with Transaction Number $manifestnumber status is already $status",
                                            "Status"=>$status,
                                            "SMSReceipients"=>$sender,
                                            "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));  
                            }
                        }
                        else {

                            print_r(
                                json_encode(
                                    array("Success"=>false,
                                        "Message"=>"Manifest with Transaction Number $manifestnumber status is not yet POSTED. Cannot proceed with transaction update",
                                        "Status"=>$status,
                                        "SMSReceipients"=>$sender,
                                        "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));  

                        }
                        
                    }
                    else {
                        
                        print_r(
                            json_encode(
                                array("Success"=>false,
                                      "Message"=>"Invalid Estimated Time of Arrival.",
                                      "Status"=>$status,
                                      "SMSReceipients"=>$sender,
                                      "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));   
                    }
                }
                else {
                    print_r(
                        json_encode(
                            array("Success"=>false,
                                  "Message"=>"Invalid Estimated Time of Departure.",
                                  "Status"=>$status,
                                  "SMSReceipients"=>$sender,
                                  "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));    
                }

            }
            else {
                
                print_r(
                    json_encode(
                        array("Success"=>false,
                              "Message"=>"Invalid Manifest Number.",
                              "Status"=>$status,
                              "SMSReceipients"=>$sender,
                              "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));     

            }
    }
}
 
if(isset($_POST['updateStatusWaybill'])){
    if($_POST['updateStatusWaybill']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
            $waybillid = escapeString($_POST['waybillid']);
            $status = escapeString($_POST['status']);
            $remarks = escapeString($_POST['remarks']);
            $sender = $_POST["sendermobilenum"];

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

            $searStat = $status == "UND" ? strtoupper($remarks) : $status;

            $checktxnrs = query("select * from txn_waybill where id='$waybillid'");



            if(getNumRows($checktxnrs)==1) {

                    while($obj=fetch($checktxnrs)){
                        $waybillnumber = $obj->waybill_number;
                    }

					$checkstatusrs = query("select * from status where code='$searStat' and type='WAYBILL'");

					if(getNumRows($checkstatusrs)==1){

						while($obj=fetch($checkstatusrs)){
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
                        
                        where wyb.id='$waybillid' and (wyb.status = 'OUT FOR DELIVERY') group by wyb.waybill_number");

						if((validateDate($checkdate)==1&&$checkdate!='1970-01-01')||$status!='DEL'){

							if(getNumRows($checktxnrs)==1){

								while($obj=fetch($checktxnrs)) {
										$waybillnumber = $obj->waybill_number;
                                        $currentwaybillstatus = $obj->status;
                                        $smsreceivers =  $sender.",".($status == 'DELIVERED' ? $obj->ConsigneeMobile.','.$obj->ShipperMobile : '');
                                        $emailreceivers = 'bogrimen@tpincorporated.com'; //$obj->user_email;
								}

								$checkifstatusupdateallowedrs = query("select * from txn_waybill
									                                   where id='$waybillid' and 
									                                         status not in (select status from no_update_status)");
                                
                                if($currentwaybillstatus == "OUT FOR DELIVERY") {
                                        
                                        
                                    if(getNumRows($checkifstatusupdateallowedrs)>0) {
                                    
                                        if(strtoupper($currentwaybillstatus)!=strtoupper($statusdesc)) {

                                            $checkifinloggedmanifest = query("select txn_manifest_waybill.waybill_number,
                                                                                    txn_manifest_waybill.manifest_number 
                                                                        from txn_manifest_waybill
                                                                        left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
                                                                        where txn_manifest.status='LOGGED' and 
                                                                                txn_manifest_waybill.waybill_number='$waybillnumber'");
                                            if(getNumRows($checkifinloggedmanifest)>0){//&&($status=='DEL'||$status=='DELIVERED')

                                                while($obj=fetch($checkifinloggedmanifest)){
                                                    $mftnumber = $obj->manifest_number;
                                                } 

                                                print_r(
                                                    json_encode(
                                                        array("Success"=>false,
                                                            "Message"=>"Waybill {$waybillnumber} is in Pending Manifest with Transaction Number {$mftnumber}.",
                                                            "Status"=>"{$statusdesc}",
                                                            "SMSReceipients"=>$sender,
                                                            "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen"))); 

                                            }
                                            else {

                                                $checkifinactiveloadplan = query("select txn_load_plan_waybill.waybill_number,
                                                                                        txn_load_plan_waybill.load_plan_number 
                                                                                from txn_load_plan_waybill
                                                                                left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number 
                                                                                where txn_load_plan.status!='DISPATCHED' and
                                                                                        txn_load_plan.status!='VOID' and  
                                                                                        txn_load_plan_waybill.waybill_number='$waybillnumber'");


                                                if(getNumRows($checkifinactiveloadplan)>0){//&&($status=='DEL'||$status=='DELIVERED')

                                                    while($obj=fetch($checkifinactiveloadplan)){
                                                        $ldpnumber = $obj->load_plan_number;
                                                    }

                                                    print_r(
                                                        json_encode(
                                                            array("Success"=>false,
                                                                "Message"=>"Waybill {$waybillnumber} is in Active Load Plan with Transaction Number {$ldpnumber}.",
                                                                "Status"=>"{$statusdesc}",
                                                                "SMSReceipients"=>$sender,
                                                                "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));  
                                                }		
                                                else {
                                                
                                                    /**** OK ****/
                                                        $rcvcols = ", received_by=NULL, received_date=NULL";
                                                        if($receiveddate!='NULL'||$receivedby!='NULL'){
                                                            $rcvcols = ", received_by='$receivedby', received_date='$receiveddate'";
                                                        } 
                                                        $rs = query("update txn_waybill set status='$statusdesc', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' $rcvcols where id='$waybillid'");
                                                        if($rs){
                                                            $waybillstathistory->insert(array('',$waybillnumber,$statusdesc,$statusdesc,$remarks,$now,$userid,'NULL','NULL','NULL',$receivedby,$receiveddate));
                                                            $systemlog = new system_log();
                                                            $systemlog->logInfo('WAYBILL',"SMS Waybill Status Update: $status","Waybill Number: ".$waybillnumber." | Status: $status - $statusdesc  |  Remarks: $remarks",$userid,$now);
                                                                
                                                            print_r(
                                                                json_encode(
                                                                    array("Success"=>true,
                                                                        "Message"=>"Waybill Number $waybillnumber status is now $statusdesc.",
                                                                        "Status"=>"{$statusdesc}",
                                                                        "SMSReceipients"=>$smsreceivers,
                                                                        "EmailReceipients"=>$emailreceivers)));   
                                                        }
                                                    
                                                    /**** OK *****/

                                                }
                                                
                                            }
                                        }
                                        else { 
                                            print_r(
                                                json_encode(
                                                    array("Success"=>false,
                                                        "Message"=>"Waybill Number {$waybillnumber} status is already $statusdesc.",
                                                        "Status"=>"{$statusdesc}",
                                                        "SMSReceipients"=>$sender,
                                                        "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));
                                        }

                                    }
                                    else{
                                        print_r("{\"Success\":false,\"Message\":\"Waybill already reached the final status.\",\"Status\":\"$statusdesc\",
                                            \"SMSReceipients\":\"{$sender}\",\"EmailReceipients\":\"phbhnlee@gmail.com|Bhen Ogrimen\"}");
                                    }

                                }
                                else {

                                    print_r(
                                        json_encode(
                                            array("Success"=>false,
                                                  "Message"=>"Waybill Number {$waybillnumber} status is not yet OUT FOR DELIVERY.",
                                                  "Status"=>"{$statusdesc}",
                                                  "SMSReceipients"=>$sender,
                                                  "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));
                                }

							 
							}
							else{
                                print_r("{\"Success\":false,\"Message\":\"Invalid Waybill Transaction Number.\",\"Status\":\"$statusdesc\",
                                    \"SMSReceipients\":\"{$sender}\",\"EmailReceipients\":\"phbhnlee@gmail.com|Bhen Ogrimen\"}");
							}
						}
						else{
                            print_r("{\"Success\":false,\"Message\":\"Invalid Waybill Receive Date.\",\"Status\":\"$statusdesc\",
                                \"SMSReceipients\":\"{$sender}\",\"EmailReceipients\":\"phbhnlee@gmail.com|Bhen Ogrimen\"}");
						}
					}
					else{
                        print_r("{\"Success\":false,\"Message\":\"Invalid Status for Waybill.\",\"Status\":\"$statusdesc\",
                            \"SMSReceipients\":\"{$sender}\",\"EmailReceipients\":\"phbhnlee@gmail.com|Bhen Ogrimen\"}");
					}

            }
            else {
                print_r("{\"Success\":false,\"Message\":\"Invalid Waybill Transaction Number.\",\"Status\":\"$statusdesc\",
                    \"SMSReceipients\":\"{$sender}\",\"EmailReceipients\":\"phbhnlee@gmail.com|Bhen Ogrimen\"}");
            }

            
            
    }
}

if(isset($_POST['updateStatusPickedUp'])){
    if($_POST['updateStatusPickedUp']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){ 
            $now = date('Y-m-d H:i:s');
            $bookingid = escapeString($_POST['bookingid']);
            $actualpickupdate = date('Y-m-d H:i:s', strtotime(isset($_POST['actualpickupdate'])?escapeString($_POST['actualpickupdate']):$now));
            $remarks = escapeString($_POST['remarks']);
            $pickedupby = isset($_POST['pickedupby'])?escapeString($_POST['pickedupby']):$remarks;
            $sender = $_POST["sendermobilenum"];
            $userid = 1;
            $systemlog = new system_log();
            $bookingnumber = '';
 
            $checktxnrs = query("select * from txn_booking where id='$bookingid'  ");
 
            if(getNumRows($checktxnrs)>=1) {

                if(validateDateTime($actualpickupdate)==1&&$actualpickupdate!='1970-01-01 08:00:00'){
                    while($obj=fetch($checktxnrs)){
                        $bookingnumber = $obj->booking_number;
                        $currentstat = $obj->status;
                    }
                    
                    if ($currentstat != "PICKED UP") {
                            
                        if($currentstat == "APPROVED") {

                            $rs = query("update txn_booking set status='PICKED UP', actual_pickup_date='$actualpickupdate', pickup_by='$pickedupby', pickup_remarks='$remarks' where id='$bookingid'");
                            if($rs){
                                $systemlog = new system_log();
                                $systemlog->logInfo('BOOKING','SMS Booking Status Update: Picked Up',"Booking Number: ".$bookingnumber." | Actual Pickup Date: $actualpickupdate | Picked-up By: $pickedupby | Remarks: $remarks",$userid,$now);
                         
                            print_r(
                                json_encode(
                                    array("Success"=>true,
                                          "Message"=>"Booking with Transaction Number $bookingnumber status is now PICKED UP",
                                          "Status"=>"PICKED UP",
                                          "SMSReceipients"=>$sender,
                                          "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen"))); 
         
                            }
                        }
                        else {
                            
                            print_r(
                                json_encode(
                                    array("Success"=>false,
                                        "Message"=>"Booking with Transaction Number $bookingnumber status is not yet APPROVED",
                                        "Status"=>"N/A",
                                        "SMSReceipients"=>$sender,
                                        "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen"))); 
                        }
                    }
                    else {

                        print_r(
                            json_encode(
                                array("Success"=>false,
                                      "Message"=>"Booking with Transaction Number $bookingnumber status is already $status",
                                      "Status"=>"N/A",
                                      "SMSReceipients"=>$sender,
                                      "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen"))); 
                    }
                }
                else {
                    
                    print_r(
                        json_encode(
                            array("Success"=>false,
                                  "Message"=>"Invalid Pickup Date for Booking Transaction",
                                  "Status"=>"N/A",
                                  "SMSReceipients"=>$sender,
                                  "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));  
                }

            }
            else {
                
                print_r(
                    json_encode(
                        array("Success"=>false,
                              "Message"=>"Invalid Booking Number",
                              "Status"=>"N/A",
                              "SMSReceipients"=>$sender,
                              "EmailReceipients"=>"phbhnlee@gmail.com|Bhen Ogrimen")));

            }

            
            
    }
}