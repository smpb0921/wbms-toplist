<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/shipper-pickup-address.class.php");
    include("../classes/booking.class.php");
    include("../classes/system-log.class.php");////////
    include("../classes/booking-document.class.php");
    include("../classes/booking-handling-instruction.class.php");
    include("../classes/booking-status-history.class.php");

	if(isset($_POST['deleteAttachment'])){
		if($_POST['deleteAttachment'] == 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){
			$bookingnumber = isset($_POST['txnnumber'])?escapeString($_POST['txnnumber']):'';
			$filename = isset($_POST['filename'])?escapeString($_POST['filename']):'';
			$attachmentid = isset($_POST['attachmentid'])?escapeString($_POST['attachmentid']):'';
			
			

			$rs = query("select * from txn_booking where booking_number='$bookingnumber'");
			if(getNumRows($rs)==1){
				if(file_exists("../application/attachments/".$filename)==1){
					unlink("../application/attachments/".$filename);
					$response = array("status"=>"ok");
				}
				else{
					$response = array(
										"status"=>"error",
										"message"=>"File Not Found"
									);
				}
				query("delete from txn_booking_attachments where id='$attachmentid' and booking_number='$bookingnumber'");
			}
			else{
				$response = array(
					"status"=>"error",
					"message"=>"Invalid Booking Number"
				);
			}
			
			print_r(json_encode($response));
		}
	}


	if(isset($_POST['checkduplicateshipper'])){
		if($_POST['checkduplicateshipper'] == 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){
			$pickupdate = date("Y-m-d",strtotime($_POST["pickupdate"]));
			$rs = query("SELECT shipper_name,booking_number from txn_booking where pickup_date = '{$pickupdate}' and shipper_id = {$_POST['shipperid']} order by booking_number");
			$booking = fetch($rs);
			$exists = getNumRows($rs);
			if($exists>0){
				print_r(json_encode(array(
					"DuplicateShipper" => true,
					"Shipper" => $booking->shipper_name,
					"DuplicateBookingNumber" => $booking->booking_number
				)));
			}
			else {
				print_r(json_encode(array(
					"DuplicateShipper" => false
				)));
			}

		}
	}

    if(isset($_POST['checkVehicleInformationAccess'])){
		if($_POST['checkVehicleInformationAccess']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$access = hasAccess(USERID,'#booking-trans-editvehicleinformationbtn');

			echo $access;

		}
	}


    if(isset($_POST['getShipperCreditInfo'])){
		if($_POST['getShipperCreditInfo']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$shipperid = isset($_POST['shipperid'])?escapeString($_POST['shipperid']):'';

			$accountname = '';
			$accountnumber = '';
			$status = '';
			$creditlimit = 0;
			$outstandingbalance = 0;
			$creditbalance = 0;
			$billedamount = 0;
			$unbilledamount = 0;


			$checkifvalidshipperrs = query("select * from shipper where id='$shipperid'");

			if(getNumRows($checkifvalidshipperrs)==1){

				while($obj=fetch($checkifvalidshipperrs)){
					$accountnumber = utfEncode($obj->account_number);
					$accountname = utfEncode($obj->account_name);
					$status = utfEncode($obj->status);
					$creditlimit = is_numeric($obj->credit_limit)==1?utfEncode($obj->credit_limit):0;
				}

				$outstandingbalance = getShipperOutstandingBalance($shipperid);
				$billedamount = getShipperBilledBalance($shipperid);
				$creditbalance = $creditlimit-$outstandingbalance;

				$unbilledamount = $outstandingbalance-$billedamount;

				$creditlimit = is_numeric($creditlimit)==1?convertWithDecimal($creditlimit,5):0;
				$outstandingbalance = is_numeric($outstandingbalance)==1?convertWithDecimal($outstandingbalance,5):0;
				$creditbalance = is_numeric($creditbalance)==1?convertWithDecimal($creditbalance,5):0;
				$billedamount = is_numeric($billedamount)==1?convertWithDecimal($billedamount,5):0;
				$unbilledamount = is_numeric($unbilledamount)==1?convertWithDecimal($unbilledamount,5):0;


				$response = array(
									   "accountnumber"=>$accountnumber,
									   "accountname"=>$accountname,
									   "status"=>$status,
									   "creditlimit"=>$creditlimit,
									   "outstandingbalance"=>$outstandingbalance,
									   "creditbalance"=>$creditbalance,
									   "billedamount"=>$billedamount,
									   "unbilledamount"=>$unbilledamount,
									   "response"=>'success'
								);
			}
			else{
				$response = array(
									   "response"=>'invalidshipperid'
								);
			}

			
			print_r(json_encode($response));



		}
	}


    if(isset($_POST['ShipperDefaultContactGetInfo'])){
		if($_POST['ShipperDefaultContactGetInfo']=='ojoiAndElspriaoi#@po92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from shipper_contact where shipper_id='$id' and default_flag=1 limit 1");

				if(!getNumRows($rs)>0){
					$rs = query("select * from shipper_contact where shipper_id='$id' limit 1");
				}	
				$data = array();
				if(getNumRows($rs)>0){
					while($obj=fetch($rs)){
						$data = array(
											   "contact"=>utfEncode($obj->contact_name),
											   "phone"=>utfEncode($obj->phone_number),
											   "email"=>utfEncode($obj->email_address),
											   "mobile"=>utfEncode($obj->mobile_number),
											   "defaultflag"=>$obj->default_flag,
											   "sendsmsflag"=>$obj->send_sms_flag,
											   "sendemailflag"=>$obj->send_email_flag,
											   "response"=>'success'

											  
									 );
					}

				}
				else{
					$data = array(
											   
											   "response"=>'nocontactinfo'

											  
								);
				}
				
				echo json_encode($data);
		}
	}


	if(isset($_POST['ShipperDefaultPickupAddressGetInfo'])){
		if($_POST['ShipperDefaultPickupAddressGetInfo']=='ooi3h$9apsojespriaoi#@po92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from shipper_pickup_address where shipper_id='$id' and default_flag=1 limit 1");
				
				if(!getNumRows($rs)>0){
					
					$rs = query("select * from shipper_pickup_address where shipper_id='$id' limit 1");
				}	
				$data = array();
				if(getNumRows($rs)>0){
					while($obj=fetch($rs)){
						$data = array(
											   "street"=>utfEncode($obj->pickup_street_address),
											   "district"=>utfEncode($obj->pickup_district),
											   "city"=>utfEncode($obj->pickup_city),
											   "province"=>utfEncode($obj->pickup_state_province),
											   "zipcode"=>utfEncode($obj->pickup_zip_code),
											   "country"=>utfEncode($obj->pickup_country),
											   "response"=>'success'

											  
									 );
					}

				}
				else{
					$data = array(
											   
											   "response"=>'nopickupaddressinfo'

											  
								);
				}
				
				echo json_encode($data);
		}
	}

	if(isset($_POST['AddNewPickupAddress'])){
		if($_POST['AddNewPickupAddress']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){

			$shipperid = escapeString($_POST['shipperid']);
			$street = escapeString($_POST['street']);
			$district = escapeString($_POST['district']);
			$city = escapeString($_POST['city']);
			$province = escapeString($_POST['province']);
			$zipcode = escapeString($_POST['zipcode']);
			$country = escapeString($_POST['country']);
			$userhasaccess = hasAccess(USERID,'#useraccessmanagebooking');

			if($userhasaccess==1){
				$checkshipperrs = query("select * from shipper where id='$shipperid'");
				if(getNumRows($checkshipperrs)==1){
						$userid = USERID;
						$now = date('Y-m-d H:i:s');
						$shipperpickupaddressclass = new shipper_pickup_address();
						$systemlog = new system_log();
					
						$shipperpickupaddressclass->insert(array('',$shipperid,0,$street,$district,$city,$province,$zipcode,$country));
						$id = $shipperpickupaddressclass->getInsertId();
						$systemlog->logAddedInfo($shipperpickupaddressclass,array($id,$shipperid,0,$street,$district,$city,$province,$zipcode,$country),'BOOKING','New Shipper Pickup Address Added',$userid,$now);
						echo "success";
				}	
				else{
					echo "invalidshipperid";
				}
			}
			else{
				echo "noaccess";
			}

		}
	}


	if(isset($_POST['ConsigneeDefaultContactGetInfo'])){
		if($_POST['ConsigneeDefaultContactGetInfo']=='oj94oifof#o@odlspriaoi#@po92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from consignee_contact where consignee_id='$id' and default_flag=1 limit 1");

				if(!getNumRows($rs)>0){
					$rs = query("select * from consignee_contact where consignee_id='$id' limit 1");
				}	
				$data = array();
				if(getNumRows($rs)>0){
					while($obj=fetch($rs)){
						$data = array(
											   "contact"=>utfEncode($obj->contact_name),
											   "phone"=>utfEncode($obj->phone_number),
											   "email"=>utfEncode($obj->email_address),
											   "mobile"=>utfEncode($obj->mobile_number),
											   "defaultflag"=>$obj->default_flag,
											   "sendsmsflag"=>$obj->send_sms_flag,
											   "sendemailflag"=>$obj->send_email_flag,
											   "response"=>'success'

											  
									 );
					}

				}
				else{
					$data = array(
											   
											   "response"=>'nocontactinfo'

											  
								);
				}
				
				echo json_encode($data);
		}
	}


	if(isset($_POST['SaveBookingTransaction'])){
		if($_POST['SaveBookingTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$shipmenttype = escapeString($_POST['shipmenttype']);
			$shipmentmode = escapeString($_POST['shipmentmode']);
			$bookingtype = escapeString($_POST['bookingtype']);
			$source = escapeString($_POST['source']);
			$origin = escapeString(strtoupper(trim($_POST['origin'])));
			$destination = escapeString($_POST['destination']);
			$remarks = escapeString($_POST['remarks']);
			$pickupdate = dateString($_POST['pickupdate']);
			$shipperid = escapeString($_POST['shipperid']);
			$shipperaccountnumber = escapeString($_POST['shipperaccountnumber']);
			$shipperaccountname = escapeString($_POST['shipperaccountname']);
			$shippertel = escapeString($_POST['shippertel']);
			$shippermobile = escapeString($_POST['shippermobile']);
			$shippercontact = escapeString($_POST['shippercontact']);
			$shippercompanyname = escapeString($_POST['shippercompanyname']);
			$shipperstreet = escapeString($_POST['shipperstreet']);
			$shipperdistrict = escapeString($_POST['shipperdistrict']);
			$shippercity = escapeString($_POST['shippercity']);
			$shipperprovince = escapeString($_POST['shipperprovince']);
			$shipperzipcode = escapeString($_POST['shipperzipcode']);
			$shippercountry = escapeString($_POST['shippercountry']);
			$pickupstreet = escapeString($_POST['pickupstreet']);
			$pickupdistrict = escapeString($_POST['pickupdistrict']);
			$pickupcity = escapeString($_POST['pickupcity']);
			$pickupprovince = escapeString($_POST['pickupprovince']);
			$pickupzipcode = escapeString($_POST['pickupzipcode']);
			$pickupcountry = escapeString($_POST['pickupcountry']);
			$consigneeid = escapeString($_POST['consigneeid']);
			$consigneeaccountnumber = escapeString($_POST['consigneeaccountnumber']);
			$consigneeaccountname = escapeString($_POST['consigneeaccountname']);
			$consigneetel = escapeString($_POST['consigneetel']);
			$consigneecompanyname = escapeString($_POST['consigneecompanyname']);
			$consigneestreet = escapeString($_POST['consigneestreet']);
			$consigneedistrict = escapeString($_POST['consigneedistrict']);
			$consigneecity = escapeString($_POST['consigneecity']);
			$consigneeprovince = escapeString($_POST['consigneeprovince']);
			$consigneezipcode = escapeString($_POST['consigneezipcode']);
			$consigneecountry = escapeString($_POST['consigneecountry']);
			$numberofpackage = escapeString($_POST['numberofpackage']);
			$declaredvalue = escapeString($_POST['declaredvalue']);
			$actualweight = escapeString($_POST['actualweight']);
			$vwcbm = escapeString($_POST['vwcbm']);
			$vw = escapeString($_POST['vw']);
			$uom = escapeString($_POST['uom']);
			$amount = escapeString($_POST['amount']);
			$services = escapeString($_POST['services']);
			$vehicletype = escapeString($_POST['vehicletype']);
			$vehicletype = $vehicletype>0?$vehicletype:'NULL';
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$platenumber = escapeString(strtoupper($_POST['platenumber']));
			$truckingdetails = isset($_POST['truckingdetails'])?escapeString($_POST['truckingdetails']):'NULL';
			$timeready = escapeString($_POST['timeready'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['timeready'])));
			
			$paymode = escapeString($_POST['paymode']);
			$shipmentdescription = escapeString($_POST['shipmentdescription']);

			$samedaypickupflag = escapeString($_POST['samedaypickupflag']);
			$samedaypickupflag = strtoupper($samedaypickupflag)=='TRUE'?1:0;
			$drivercontact = escapeString($_POST['drivercontact']);
			$billto = escapeString($_POST['billto']);
			$driver = escapeString($_POST['driver']);
			$helper = escapeString($_POST['helper']);

			$handlinginstruction = 'NULL';//escapeString($_POST['handlinginstruction']);
			$document = 'NULL';//escapeString($_POST['bkdocument']);
			@$documentarray = $_POST['bkdocument'];
			@$handlinginstructionarray = $_POST['handlinginstruction'];

			$response = array();

			$pickupdatenum = dateFormat($_POST['pickupdate'],'Ymd');
			$currentdatenum = date('Ymd');


			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$bookingclass = new txn_booking();
			$systemlog = new system_log();

			$checkpickupdate = strtotime($_POST['pickupdate']);
			$datenow = strtotime(date('Y-m-d'));

			
			
			/*if(floatval($checkpickupdate)<floatval($datenow)) {
			    $response = array(
									"response"=>'invalidpickupdate'
								  );
			}
			else{*/
				$location = 'NULL';
				$getuserlocationrs = query("select * from user where id='$userid'");
				while($obj=fetch($getuserlocationrs)){
					$location = $obj->location_id>0?$obj->location_id:'NULL';
				}

				$checkshipperrs = query("select * from shipper where id='$shipperid'");
				if(getNumRows($checkshipperrs)==1){

						$checkconsigneers = query("select * from consignee where id='$consigneeid'");
						if(getNumRows($checkconsigneers)!=1){
							$consigneeid = 'NULL';
							$consigneeaccountnumber = 'NULL';
							$consigneeaccountname = 'NULL';
							$consigneetel = 'NULL';
							$consigneecompanyname = 'NULL';
							$consigneestreet = 'NULL';
							$consigneedistrict = 'NULL';
							$consigneecity = 'NULL';
							$consigneeprovince = 'NULL';
							$consigneezipcode = 'NULL';
							$consigneecountry = 'NULL';
						}

						
						//if(validateDate($pickupdate)==1&&$pickupdate!='1970-01-01'&&$pickupdatenum>=$currentdatenum){

							if($source=='add'){
								$bookingnumber = getTransactionNumber(1);
								$bookingclass->insert(
														array(	  '',
																  $bookingnumber,
																  'LOGGED',
																  $origin,
																  $destination,
																  $pickupdate,
																  $remarks,
																  $now,
																  $userid,
																  $now,
																  $userid,
																  $shipperid,
																  $shipperaccountnumber,
																  $shipperaccountname,
																  $shippertel,
																  $shippercompanyname,
																  $shipperstreet,
																  $shipperdistrict,
																  $shippercity,
																  $shipperprovince,
																  $shipperzipcode,
																  $shippercountry,
																  $pickupstreet,
																  $pickupdistrict,
																  $pickupcity,
																  $pickupprovince,
																  $pickupzipcode,
																  $pickupcountry,
																  $consigneeid,
																  $consigneeaccountnumber,
																  $consigneeaccountname,
																  $consigneetel,
																  $consigneecompanyname,
																  $consigneestreet,
																  $consigneedistrict,
																  $consigneecity,
																  $consigneeprovince,
																  $consigneezipcode,
																  $consigneecountry,
																  $numberofpackage,
																  $declaredvalue,
																  $actualweight,
																  $vwcbm,
																  $services,
																  $modeoftransport,
																  $handlinginstruction,
																  $paymode,
																  $amount,
																  $shipmentdescription,
																  $vw,
																  $uom,
																  $shippermobile,
																  $shippercontact,
																  $document,
																  $location,
																  $samedaypickupflag,
																  $drivercontact,
																  $billto,
																  $driver,
																  $helper,
																  $vehicletype,
																  $platenumber,
																  $timeready,
																  $bookingtype,
																  $truckingdetails,
																  $shipmenttype,
																  $shipmentmode
															 )
													  );
								$id = $bookingclass->getInsertId();
								$systemlog->logAddedInfo($bookingclass,array($id,
																  $bookingnumber,
																  'LOGGED',
																  $origin,
																  $destination,
																  $pickupdate,
																  $remarks,
																  $now,
																  $userid,
																  $now,
																  $userid,
																  $shipperid,
																  $shipperaccountnumber,
																  $shipperaccountname,
																  $shippertel,
																  $shippercompanyname,
																  $shipperstreet,
																  $shipperdistrict,
																  $shippercity,
																  $shipperprovince,
																  $shipperzipcode,
																  $shippercountry,
																  $pickupstreet,
																  $pickupdistrict,
																  $pickupcity,
																  $pickupprovince,
																  $pickupzipcode,
																  $pickupcountry,
																  $consigneeid,
																  $consigneeaccountnumber,
																  $consigneeaccountname,
																  $consigneetel,
																  $consigneecompanyname,
																  $consigneestreet,
																  $consigneedistrict,
																  $consigneecity,
																  $consigneeprovince,
																  $consigneezipcode,
																  $consigneecountry,
																  $numberofpackage,
																  $declaredvalue,
																  $actualweight,
																  $vwcbm,
																  $services,
																  $modeoftransport,
																  $handlinginstruction,
																  $paymode,
																  $amount,
																  $shipmentdescription,
																  $vw,
																  $uom,
																  $shippermobile,
																  $shippercontact,
																  $document,
																  $location,
																  $samedaypickupflag,
																  $drivercontact,
																  $billto,
																  $driver,
																  $helper,
																  $vehicletype,
																  $platenumber,
																  $timeready,
																  $bookingtype,
																  $truckingdetails,
																  $shipmenttype,
																  $shipmentmode
															 ),'BOOKING','New Booking Transaction Added',$userid,$now);

								$response = array(
												"response"=>'success',
												"txnnumber"=>$bookingnumber
											 );
							}
							else if($source=='edit'){
								$id = escapeString($_POST['id']);
								$bookingnumber = '';

								$checkbookingidrs = query("select * from txn_booking where id='$id'");
								if(getNumRows($checkbookingidrs)==1){

									while($obj=fetch($checkbookingidrs)){
										$bookingnumber = $obj->booking_number;
										$bookingcreatedby = $obj->created_by;
									}

									$location = 'NULL';
									$getuserlocationrs = query("select * from user where id='$bookingcreatedby'");
									while($obj=fetch($getuserlocationrs)){
										$location = $obj->location_id>0?$obj->location_id:'NULL';
									}

									$systemlog->logEditedInfo($bookingclass,$id,array($id,$bookingnumber,
																  'LOGGED',
																  $origin,
																  $destination,
																  $pickupdate,
																  $remarks,
																  'NOCHANGE',
																  'NOCHANGE',
																  $now,
																  $userid,
																  $shipperid,
																  $shipperaccountnumber,
																  $shipperaccountname,
																  $shippertel,
																  $shippercompanyname,
																  $shipperstreet,
																  $shipperdistrict,
																  $shippercity,
																  $shipperprovince,
																  $shipperzipcode,
																  $shippercountry,
																  $pickupstreet,
																  $pickupdistrict,
																  $pickupcity,
																  $pickupprovince,
																  $pickupzipcode,
																  $pickupcountry,
																  $consigneeid,
																  $consigneeaccountnumber,
																  $consigneeaccountname,
																  $consigneetel,
																  $consigneecompanyname,
																  $consigneestreet,
																  $consigneedistrict,
																  $consigneecity,
																  $consigneeprovince,
																  $consigneezipcode,
																  $consigneecountry,
																  $numberofpackage,
																  $declaredvalue,
																  $actualweight,
																  $vwcbm,
																  $services,
																  $modeoftransport,
																  $handlinginstruction,
																  $paymode,
																  $amount,
																  $shipmentdescription,
																  $vw,
																  $uom,
																  $shippermobile,
																  $shippercontact,
																  $document,
																  $location,
																  $samedaypickupflag,
																  $drivercontact,
																  $billto,
																  $driver,
																  $helper,
																  $vehicletype,
																  $platenumber,
																  $timeready,
																  $bookingtype,
																  $truckingdetails,
																  $shipmenttype,
																  $shipmentmode
															 ),'BOOKING','Edited Booking Transaction',$userid,$now);/// log should be before update is made
									$bookingclass->update($id,array($bookingnumber,
																  'LOGGED',
																  $origin,
																  $destination,
																  $pickupdate,
																  $remarks,
																  'NOCHANGE',
																  'NOCHANGE',
																  $now,
																  $userid,
																  $shipperid,
																  $shipperaccountnumber,
																  $shipperaccountname,
																  $shippertel,
																  $shippercompanyname,
																  $shipperstreet,
																  $shipperdistrict,
																  $shippercity,
																  $shipperprovince,
																  $shipperzipcode,
																  $shippercountry,
																  $pickupstreet,
																  $pickupdistrict,
																  $pickupcity,
																  $pickupprovince,
																  $pickupzipcode,
																  $pickupcountry,
																  $consigneeid,
																  $consigneeaccountnumber,
																  $consigneeaccountname,
																  $consigneetel,
																  $consigneecompanyname,
																  $consigneestreet,
																  $consigneedistrict,
																  $consigneecity,
																  $consigneeprovince,
																  $consigneezipcode,
																  $consigneecountry,
																  $numberofpackage,
																  $declaredvalue,
																  $actualweight,
																  $vwcbm,
																  $services,
																  $modeoftransport,
																  $handlinginstruction,
																  $paymode,
																  $amount,
																  $shipmentdescription,
																  $vw,
																  $uom,
																  $shippermobile,
																  $shippercontact,
																  $document,
																  $location,
																  $samedaypickupflag,
																  $drivercontact,
																  $billto,
																  $driver,
																  $helper,
																  $vehicletype,
																  $platenumber,
																  $timeready,
																  $bookingtype,
																  $truckingdetails,
																  $shipmenttype,
																  $shipmentmode
															 ));

									$response = array(
														"response"=>'success',
														"txnnumber"=>$bookingnumber
													 );
								}
								else{
									$response = array(
												"response"=>'invalidbookingid',
												"detail"=>$id
											 );
								}
								
								
							}


							/**** DOCUMENT ***/
							$documentclass = new txn_booking_document();
							$documentclass->deleteWhere("where booking_number='".$bookingnumber."'");
							$bkdocdata = array();

							if($_POST['bkdocument']!=null){
								for($i=0;$i<count($documentarray);$i++){
									$doctemparray = array();
									array_push($doctemparray,$bookingnumber, $documentarray[$i]);
									array_push($bkdocdata, $doctemparray);
								}
								if(count($documentarray)>0){
									$documentclass->insertMultiple($bkdocdata);
								}
							}
							/**** DOCUMENT - END ***/

							/**** HANDLING INSTRUCTION ***/
							$handlinginstructionclass = new txn_booking_handling_instruction();
							$handlinginstructionclass->deleteWhere("where booking_number='".$bookingnumber."'");
							$hidata = array();
							
							if($_POST['handlinginstruction']!=null){
								for($i=0;$i<count($handlinginstructionarray);$i++){
									$handlingins = array();
									array_push($handlingins, $bookingnumber, $handlinginstructionarray[$i]);
									array_push($hidata, $handlingins);
								}
								if(count($handlinginstructionarray)>0){
									$handlinginstructionclass->insertMultiple($hidata);
								}
							}
							/**** HANDLING INSTRUCTION - END ***/



						/*}
						else{
							$response = array(
												"response"=>'invalidpickupdate'
											 );
						}*/


					/*}
					else{
						$response = array(
												"response"=>'invalidconsigneeid'
											 );
					}*/
				}
				else{
					$response = array(
												"response"=>'invalidshipperid'
											 );
				}
			//}
			    echo json_encode($response);



		}
	}





	if(isset($_POST['getReference'])){
		if($_POST['getReference']=='FOio5ja3op2a2lK@3#4hh$93s'){
			$source = escapeString($_POST['source']);
			$id = escapeString($_POST['id']);
			

			$query = '';



			if($source=='first'){
				$query = "select * from txn_booking order by id asc limit 1";
			}
			else if($source=='second' && $id!=''){
				$query = "select * from txn_booking where id < $id order by id desc limit 1";
			}
			else if($source=='third' && $id!=''){
				$query = "select * from txn_booking where id > $id order by id asc limit 1";
			}
			else if($source=='fourth'){
				$query = "select * from txn_booking order by id desc limit 1";
			}
			else if($id==''){
				$query = "select * from txn_booking order by id asc limit 1";
			}
			if($query!=''){
				$rs = query($query);
				if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->booking_number;
				}
				else{
					$rs = query("select * from txn_booking where id='$id'");
					if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->booking_number;
					}
					else{
						echo "N/A";
					}
				}
			}
			else{
				echo "N/A";
			}
		}
	}



	if(isset($_POST['getBookingData'])){
		if($_POST['getBookingData']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

			$txnnumber = escapeString($_POST['txnnumber']);
			$rs = query("select txn_booking.id, 
				                txn_booking.booking_number,
				                txn_booking.status,
				                txn_booking.origin_id,
				                txn_booking.destination_id,
				                txn_booking.approved_by,
				                txn_booking.approved_date,
				                txn_booking.rejected_by,
				                txn_booking.rejected_date,
				                txn_booking.rejected_reason,
				                txn_booking.pickup_date,
				                txn_booking.actual_pickup_date,
				                txn_booking.pickup_by,
				                txn_booking.remarks,
				                txn_booking.created_date,
				                txn_booking.created_by,
				                txn_booking.updated_date,
				                txn_booking.updated_by,
				                txn_booking.shipper_id,
				                txn_booking.shipper_account_number,
				                txn_booking.shipper_name,
				                txn_booking.shipper_tel_number,
				                txn_booking.shipper_mobile,
				                txn_booking.shipper_contact,
				                txn_booking.shipper_company_name,
				                txn_booking.shipper_street_address,
				                txn_booking.shipper_district,
				                txn_booking.shipper_city,
				                txn_booking.shipper_state_province,
				                txn_booking.shipper_zip_code,
				                txn_booking.shipper_country,
				                txn_booking.shipper_pickup_street_address,
				                txn_booking.shipper_pickup_district,
				                txn_booking.shipper_pickup_city,
				                txn_booking.shipper_pickup_state_province,
				                txn_booking.shipper_pickup_zip_code,
				                txn_booking.shipper_pickup_country,
				                txn_booking.consignee_id,
				                txn_booking.consignee_account_number,
				                txn_booking.consignee_name,
				                txn_booking.consignee_tel_number,
				                txn_booking.consignee_company_name,
				                txn_booking.consignee_street_address,
				                txn_booking.consignee_district,
				                txn_booking.consignee_city,
				                txn_booking.consignee_state_province,
				                txn_booking.consignee_zip_code,
				                txn_booking.consignee_country,
				                txn_booking.package_number_of_packages,
				                txn_booking.package_declared_value,
				                txn_booking.package_actual_weight,
				                txn_booking.package_cbm,
				                txn_booking.package_vw,
				                txn_booking.package_service,
				                txn_booking.package_mode_of_transport,
				                txn_booking.package_handling_instruction,
				                txn_booking.package_pay_mode,
				                txn_booking.package_amount,
				                txn_booking.shipment_description,
				                txn_booking.posted_by,
				                txn_booking.posted_date,
				                txn_booking.unit_of_measure,
				                txn_booking.package_document,
				                txn_booking.same_day_pickup_flag,
				                txn_booking.driver_contact_number,
				                txn_booking.bill_to,
				                txn_booking.driver,
				                txn_booking.helper,
				                txn_booking.time_ready,
				                txn_booking.vehicle_type_id,
				                txn_booking.plate_number,
				                case 
				                	when txn_booking.supervisor_notified=1 then 'YES'
				                	when txn_booking.supervisor_notified=0 then 'NO'
				                	else ''
				                end as supervisornotified,
				            	case 
				                	when txn_booking.driver_notified=1 then 'YES'
				                	when txn_booking.driver_notified=0 then 'NO'
				                	else ''
				                end as drivernotified,
				                vehicle_type.description as vehicletype,
				                vehicle_type.type as vehicletypetype,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
				                mode_of_transport.description as modeoftransport,
				                handling_instruction.description as handlinginstruction,
				                accompanying_documents.description as document,
				                txn_booking.booking_type_id,
				                booking_type.description as bookingtype,
								txn_booking.shipment_type_id,
								txn_booking.shipment_mode_id,
								shipment_type.code as shipmenttype,
								shipment_mode.code as shipmentmode,
								trucking_details
				         from txn_booking
				         left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id 
				         left join services on services.id=txn_booking.package_service
				         left join accompanying_documents on accompanying_documents.id=txn_booking.package_document
				         left join mode_of_transport on mode_of_transport.id=txn_booking.package_mode_of_transport
				         left join handling_instruction on handling_instruction.id=txn_booking.package_handling_instruction
				         left join vehicle_type on vehicle_type.id=txn_booking.vehicle_type_id
				         left join booking_type on booking_type.id=txn_booking.booking_type_id
						 left join shipment_type on shipment_type.id=txn_booking.shipment_type_id
						 left join shipment_mode on shipment_mode.id=txn_booking.shipment_mode_id
				         where txn_booking.booking_number = '$txnnumber'");
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
					$createdby = getNameOfUser($obj->created_by);
					$updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
					$updatedby = getNameOfUser($obj->updated_by);
					$posteddate = dateFormat($obj->posted_date, "m/d/Y h:i:s A");
					$postedby = getNameOfUser($obj->posted_by);
					$approveddate = dateFormat($obj->approved_date, "m/d/Y h:i:s A");
					$approvedby = getNameOfUser($obj->approved_by);
					$rejecteddate = dateFormat($obj->rejected_date, "m/d/Y h:i:s A");
					$rejectedby = getNameOfUser($obj->rejected_by);
					$pickupdate = dateFormat($obj->pickup_date, "m/d/Y");
					$actualpickupdate = dateFormat($obj->actual_pickup_date, "m/d/Y h:i:s A");
					$timeready = dateFormat($obj->time_ready, "m/d/Y h:i:s A");

					$vehicleinfoaccessaccess = hasAccess(USERID,'#booking-trans-editvehicleinformationbtn');
					$userhasaccess = hasAccess(USERID,'#useraccessmanagebooking');
					$addaccess = hasAccess(USERID,'#booking-trans-addbtn');
					$printaccess = hasAccess(USERID,'#booking-trans-printbtn');
					$editaccess = hasAccess(USERID,'#booking-trans-editbtn');
					$voidaccess = hasAccess(USERID,'#booking-trans-voidbtn');
					$postaccess = hasAccess(USERID,'#booking-trans-postbn');
					$updatestatusaccess = hasAccess(USERID,'#booking-trans-updatestatpickedbtn');
					$assigndriveraccess = hasAccess(USERID,'#booking-trans-editvehicleinformationbtn');
					$resetdriveraccess = hasAccess(USERID,'#booking-trans-resetvehicleinformationbtn');
					$viewstatushistoryaccess = hasAccess(USERID,'#booking-trans-viewstatushistorybtn');

					if($viewstatushistoryaccess==1){
						$userviewstatushistoryaccess='true';
					}
					else{
						$userviewstatushistoryaccess='false';
					}

					if($addaccess==1){
						$useraddaccess='true';
					}
					else{
						$useraddaccess='false';
					}

					if($printaccess==1){
						$userprintaccess='true';
					}
					else{
						$userprintaccess='false';
					}

					if(USERID==$obj->created_by||$editaccess==1||$vehicleinfoaccessaccess==1){
						$usereditaccess='true';
					}
					else{
						$usereditaccess='false';
					}

					if(USERID==$obj->created_by||$voidaccess==1){
						$uservoidaccess='true';
					}
					else{
						$uservoidaccess='false';
					}

					if(USERID==$obj->created_by||$postaccess==1){
						$userpostaccess='true';
					}
					else{
						$userpostaccess='false';
					}

					if($updatestatusaccess==1){
						$userupdatestatusaccess='true';
					}
					else{
						$userupdatestatusaccess='false';
					}

					if($assigndriveraccess==1){
						$userassigndriveraccess='true';
					}
					else{
						$userassigndriveraccess='false';
					}

					if($resetdriveraccess==1){
						$userresetdriveraccess='true';
					}
					else{
						$userresetdriveraccess='false';
					}
					

					$dataarray = array(
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "remarks"=>utfEncode($obj->remarks),
									   "pickupdate"=>utfEncode($pickupdate),
									   "shipperid"=>utfEncode($obj->shipper_id),
									   "shipperaccountnumber"=>utfEncode($obj->shipper_account_number),
									   "shipperaccountname"=>utfEncode($obj->shipper_name),
									   "shippertel"=>utfEncode($obj->shipper_tel_number),
									   "shippermobile"=>utfEncode($obj->shipper_mobile),
									   "shippercontact"=>utfEncode($obj->shipper_contact),
									   "shippercompanyname"=>utfEncode($obj->shipper_company_name),
									   "shipperstreet"=>utfEncode($obj->shipper_street_address),
									   "shipperdistrict"=>utfEncode($obj->shipper_district),
									   "shippercity"=>utfEncode($obj->shipper_city),
									   "shipperprovince"=>utfEncode($obj->shipper_state_province),
									   "shipperzipcode"=>utfEncode($obj->shipper_zip_code),
									   "shippercountry"=>utfEncode($obj->shipper_country),
									   "pickupstreet"=>utfEncode($obj->shipper_pickup_street_address),
									   "pickupdistrict"=>utfEncode($obj->shipper_pickup_district),
									   "pickupcity"=>utfEncode($obj->shipper_pickup_city),
									   "pickupprovince"=>utfEncode($obj->shipper_pickup_state_province),
									   "pickupzipcode"=>utfEncode($obj->shipper_pickup_zip_code),
									   "pickupcountry"=>utfEncode($obj->shipper_pickup_country),
									   "consigneeid"=>utfEncode($obj->consignee_id),
									   "consigneeaccountnumber"=>utfEncode($obj->consignee_account_number),
									   "consigneeaccountname"=>utfEncode($obj->consignee_name),
									   "consigneetel"=>utfEncode($obj->consignee_tel_number),
									   "consigneecompanyname"=>utfEncode($obj->consignee_company_name),
									   "consigneestreet"=>utfEncode($obj->consignee_street_address),
									   "consigneedistrict"=>utfEncode($obj->consignee_district),
									   "consigneecity"=>utfEncode($obj->consignee_city),
									   "consigneeprovince"=>utfEncode($obj->consignee_state_province),
									   "consigneezipcode"=>utfEncode($obj->consignee_zip_code),
									   "consigneecountry"=>utfEncode($obj->consignee_country),
									   "numberofpackage"=>utfEncode($obj->package_number_of_packages),
									   "declaredvalue"=>utfEncode($obj->package_declared_value),
									   "actualweight"=>utfEncode($obj->package_actual_weight),
									   "vwcbm"=>utfEncode($obj->package_cbm),
									   "vw"=>utfEncode($obj->package_vw),
									   "amount"=>utfEncode($obj->package_amount),
									   "shipmentdescription"=>utfEncode($obj->shipment_description),
									   "originid"=>utfEncode($obj->origin_id),
									   "origin"=>utfEncode($obj->origin),
									   "destinationid"=>utfEncode($obj->destination_id),
									   "destination"=>utfEncode($obj->destination),
									   "serviceid"=>utfEncode($obj->package_service),
									   "service"=>utfEncode($obj->servicedesc),
									   "modeoftransportid"=>utfEncode($obj->package_mode_of_transport),
									   "modeoftransport"=>utfEncode($obj->modeoftransport),
									   "handlinginstructionid"=>utfEncode($obj->package_handling_instruction),
									   "handlinginstruction"=>utfEncode($obj->handlinginstruction),
									   "paymode"=>utfEncode($obj->package_pay_mode),
									   "actualpickupdate"=>utfEncode($actualpickupdate),
									   "pickupby"=>utfEncode($obj->pickup_by),
									   "createddate"=>utfEncode($createddate),
									   "createdby"=>utfEncode($createdby),
									   "posteddate"=>utfEncode($posteddate),
									   "postedby"=>utfEncode($postedby),
									   "approveddate"=>utfEncode($approveddate),
									   "approvedby"=>utfEncode($approvedby),
									   "rejecteddate"=>utfEncode($rejecteddate),
									   "rejectedby"=>utfEncode($rejectedby),
									   "reason"=>utfEncode($obj->rejected_reason),
									   "addaccess"=>$useraddaccess,
									   "printaccess"=>$userprintaccess,
									   "editaccess"=>$usereditaccess,
									   "voidaccess"=>$uservoidaccess,
									   "postaccess"=>$userpostaccess,
									   "updatestatusaccess"=>$userupdatestatusaccess,
									   "uom"=>utfEncode($obj->unit_of_measure),
									   "documentid"=>utfEncode($obj->package_document),
									   "document"=>utfEncode($obj->document),
									   "samedaypickupflag"=>utfEncode($obj->same_day_pickup_flag),
									   "drivercontact"=>utfEncode($obj->driver_contact_number),
									   "billto"=>utfEncode($obj->bill_to),
									   "driver"=>utfEncode($obj->driver),
									   "helper"=>utfEncode($obj->helper),
									   "vehicletype"=>utfEncode($obj->vehicletype),
									   "vehicletypeid"=>utfEncode($obj->vehicle_type_id),
									   "vehicletypetype"=>utfEncode($obj->vehicletypetype),
									   "platenumber"=>utfEncode($obj->plate_number),
									   "timeready"=>utfEncode($timeready),
									   "assigndriverdetails"=>utfEncode($userassigndriveraccess),
									   "supervisornotified"=>utfEncode($obj->supervisornotified),
									   "drivernotified"=>utfEncode($obj->drivernotified),
									   "resetdriver"=>utfEncode($userresetdriveraccess),
									   "viewstatushistoryaccess"=>utfEncode($userviewstatushistoryaccess),
									   "bookingtypeid"=>$obj->booking_type_id,
									   "bookingtype"=>utfEncode($obj->bookingtype),
									   "truckingdetails"=>utfEncode($obj->trucking_details),
									   "shipmenttypeid"=>utfEncode($obj->shipment_type_id),
									   "shipmentmodeid"=>utfEncode($obj->shipment_mode_id),
									   "shipmenttype"=>utfEncode($obj->shipmenttype),
									   "shipmentmode"=>utfEncode($obj->shipmentmode)
									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
		}
	}





	if(isset($_POST['postBookingTransaction'])){
		if($_POST['postBookingTransaction']=='oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){
				$id = escapeString($_POST['id']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$checktxnrs = query("select status, created_by from txn_booking where booking_number='$txnnumber'");
				$status = '';
				$data = array();
				$createdby = 'none';
				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$bookingstathistory = new txn_booking_status_history();
				$supervisormobile = '';
				$drivermobile = '';
				$vehicletype = '';
				$vehicletypetype = '';
				$supervisor = '';
				$driver = '';

				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$status = $obj->status;
						$createdby = $obj->created_by;
					}

					if($status=='LOGGED'){
							$userhasaccess = hasAccess(USERID,'#useraccessmanagebooking');
							if(USERID==$createdby||USERID==1||$userhasaccess==1){

								$rs = query("select * from txn_booking where booking_number='$txnnumber'");
								while($obj=fetch($rs)){
									$vehicletype = $obj->vehicle_type_id;
									$drivermobile = $obj->driver_contact_number;
									$driver = $obj->driver;
								}

								$rs = query("select * from vehicle_type where id='$vehicletype'");
								while($obj=fetch($rs)){
									$vehicletypetype = $obj->type;
								}

								
								if(strtoupper($vehicletypetype)=='COURIER'){
									$qry = "select group_concat(first_name,' ',last_name separator ';') as supervisor, group_concat(mobile separator ';') as mobile from user where courier_supervisor_flag=1";
								}
								else{
									$qry = "select group_concat(first_name,' ',last_name separator ';') as supervisor, group_concat(mobile separator ';') as mobile from user where freight_supervisor_flag=1";
								}
								$rs = query($qry);
								while($obj=fetch($rs)){
									// $supervisormobile = $obj->mobile;
									// $supervisor = $obj->supervisor;//$obj->id;
									$supervisormobile = $obj->mobile ? $obj->mobile : '';
									$supervisor = $obj->supervisor ? $obj->supervisor : '';
								}
								




								$rs = query("update txn_booking 
									         set status='POSTED', 
									             posted_date='$now', 
									             posted_by='$userid', 
									             approved_date=NULL, 
									             approved_by=NULL, 
									             rejected_reason=NULL, 
									             rejected_date=NULL, 
									             rejected_by=NULL 
									         where id='$id'");
								if($rs){
									$systemlog->logInfo('BOOKING','Posted Booking Transaction',"Booking Number: ".$txnnumber,$userid,$now);
									$bookingstathistory->insert(array('',$txnnumber,'POSTED','','','',$supervisor,$supervisormobile,$driver,$drivermobile,'','',$now,$userid,0));

									query("delete from mod_sms_booking_notifications where booking_number='$txnnumber'");
									query("insert into mod_sms_booking_notifications(		
																						booking_number,
																						supervisor_mobile_number
																				    )
																			values  (
																						'$txnnumber',
																						'$supervisormobile'

																					)");
									$data = array("response"=>'success');
								}
							}
							else{
								$data = array("response"=>'noaccess');
							}
					}
					else{
						$data = array(	   
									 "response"=>'alreadyposted'
								    );
					}

				}
				else{
					$data = array(		   
									"response"=>'invalidtransaction'
								);
				}
				echo json_encode($data);
		}
	}



	if(isset($_POST['updateStatusPickedUp'])){
		if($_POST['updateStatusPickedUp']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$bookingid = escapeString($_POST['bookingid']);
				$actualpickupdate = date('Y-m-d H:i:s', strtotime(escapeString($_POST['actualpickupdate'])));
				$pickedupby = escapeString($_POST['pickedupby']);
				$remarks = escapeString($_POST['remarks']);
				$status = escapeString($_POST['status']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$bookingnumber = '';
				$bookingstathistory = new txn_booking_status_history();
				$driver = '';
				$drivercontact = '';

			

				$checktxnrs = query("select * from txn_booking where id='$bookingid'");


				if(getNumRows($checktxnrs)==1){

					if(validateDateTime($actualpickupdate)==1&&$actualpickupdate!='1970-01-01 08:00:00'){
						while($obj=fetch($checktxnrs)){
							$bookingnumber = $obj->booking_number;
							$driver = $obj->driver;
							$drivercontact = $obj->driver_contact_number;
						}
						if($status=='PICKED UP'){
							$rs = query("update txn_booking set status='$status', actual_pickup_date='$actualpickupdate', pickup_by='$pickedupby', pickup_remarks='$remarks', last_status_update='$now', last_status_update_remarks='$remarks' where id='$bookingid'");
						}
						else{
							$remarks2 = $remarks." - $pickedupby ($actualpickupdate)";
							$rs = query("update txn_booking set status='$status', actual_pickup_date=NULL, pickup_by=NULL, pickup_remarks='$remarks', last_status_update='$now', last_status_update_remarks='$remarks2' where id='$bookingid'");
						}

						//$rs = query("update txn_booking set status='PICKED UP', actual_pickup_date='$actualpickupdate', pickup_by='$pickedupby', pickup_remarks='$remarks' where id='$bookingid'");
						if($rs){
							$systemlog = new system_log();
							$systemlog->logInfo('BOOKING',"Booking Status Update: $status","Booking Number: ".$bookingnumber." | Actual Pickup Date: $actualpickupdate | Picked-up By: $pickedupby | Remarks: $remarks",$userid,$now);
							$bookingstathistory->insert(array('',$bookingnumber,$status,$actualpickupdate,$pickedupby,$remarks,'NULL','NULL',$driver,$drivercontact,'NULL','NULL',$now,$userid,1));
							echo "success";
						}
					}
					else{
						echo "invaliddate";
					}

				}
				else{
					echo "invalidbooking";
				}

				
				
		}
	}

	if(isset($_POST['updateBookingStatus'])){
		if($_POST['updateBookingStatus']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$bookingid = escapeString($_POST['bookingid']);
				$actualpickupdate = date('Y-m-d H:i:s', strtotime(escapeString($_POST['actualpickupdate'])));
				$pickedupby = escapeString($_POST['pickedupby']);
				$remarks = escapeString($_POST['remarks']);
				$status = escapeString($_POST['status']);
				$driver = trim($_POST['driver'])==''?'NULL':"'".escapeString($_POST['driver'])."'";
				$helper = trim($_POST['helper'])==''?'NULL':"'".escapeString($_POST['helper'])."'";
				$platenumber = trim($_POST['platenumber'])==''?'NULL':"'".escapeString($_POST['platenumber'])."'";

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$bookingnumber = '';
				$bookingstathistory = new txn_booking_status_history();
				

			

				$checktxnrs = query("select * from txn_booking where id='$bookingid'");


				if(getNumRows($checktxnrs)==1){

					if(validateDateTime($actualpickupdate)==1&&$actualpickupdate!='1970-01-01 08:00:00'){
						while($obj=fetch($checktxnrs)){
							$bookingnumber = $obj->booking_number;
							
						}
						if($status=='PICKED UP'){
							$rs = query("update txn_booking 
								         set status='$status', 
								             actual_pickup_date='$actualpickupdate', 
								             pickup_by='$pickedupby', 
								             driver_contact_number=NULL, 
								             driver=$driver, 
								             helper=$helper, 
								             plate_number=$platenumber, 
								             pickup_remarks='$remarks', 
								             last_status_update='$now', 
								             last_status_update_remarks='$remarks' 
								         where id='$bookingid'");
						}
						else{
							$remarks2 = $remarks." - $pickedupby ($actualpickupdate)";
							$rs = query("update txn_booking set status='$status', actual_pickup_date=NULL, pickup_by=NULL, pickup_remarks='$remarks', last_status_update='$now', last_status_update_remarks='$remarks2', driver_contact_number=NULL, driver=$driver, helper=$helper, plate_number=$platenumber where id='$bookingid'");
						}

						//$rs = query("update txn_booking set status='PICKED UP', actual_pickup_date='$actualpickupdate', pickup_by='$pickedupby', pickup_remarks='$remarks' where id='$bookingid'");
						if($rs){
							$systemlog = new system_log();
							$systemlog->logInfo('BOOKING',"Booking Status Update: $status","Booking Number: ".$bookingnumber." | Actual Pickup Date: $actualpickupdate | Picked-up By: $pickedupby | Remarks: $remarks | Driver: $driver | Helper: $helper | Plate Number: $platenumber",$userid,$now);
							$bookingstathistory->insert(array('',$bookingnumber,$status,$actualpickupdate,$pickedupby,$remarks,'NULL','NULL',trim($driver,"'"),'NULL','NULL','NULL',$now,$userid,1));
							echo "success";
						}
					}
					else{
						echo "invaliddate";
					}

				}
				else{
					echo "invalidbooking";
				}

				
				
		}
	}


	if(isset($_POST['voidBookingTransaction'])){
		if($_POST['voidBookingTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$bookingid = escapeString($_POST['bookingid']);
				$bookingnumber = escapeString($_POST['bookingnumber']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$bookingstathistory = new txn_booking_status_history();

			

				$checktxnrs = query("select * from txn_booking where id='$bookingid' and booking_number='$bookingnumber'");


				if(getNumRows($checktxnrs)==1){

						$rs = query("update txn_booking set status='VOID', cancelled_reason='$remarks', cancelled_date='$now', cancelled_by='$userid' where id='$bookingid'");
						if($rs){
							$systemlog = new system_log();
							$systemlog->logInfo('BOOKING','Cancelled Booking Transaction',"Booking Number: ".$bookingnumber." | Remarks: $remarks",$userid,$now);
							$bookingstathistory->insert(array('',$bookingnumber,'VOID','NULL','NULL',$remarks,'NULL','NULL','NULL','NULL','NULL','NULL',$now,$userid,0));
							echo "success";
						}
					
				}
				else{
					echo "invalidbooking";
				}

				
				
		}
	}



	if(isset($_POST['checkIfUserCanAddPickupAddress'])){
		if($_POST['checkIfUserCanAddPickupAddress']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){

			$userhasaccess = hasAccess(USERID,'#useraccessmanagebooking');
			echo $userhasaccess;

		}
	}

	if(isset($_POST['getHandlingInstructions'])){
		if($_POST['getHandlingInstructions']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$txnnumber = escapeString($_POST['txnnumber']);
			$instructions = getBookingHandlingInstructions($txnnumber);

			$dataarray = array(
								 "instructions"=>$instructions
							  );
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['getAccompanyingDocuments'])){
		if($_POST['getAccompanyingDocuments']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$txnnumber = escapeString($_POST['txnnumber']);
			$descriptions = getBookingAccompanyingDocuments($txnnumber);

			$dataarray = array(
								 "descriptions"=>$descriptions
							  );
			print_r(json_encode($dataarray));

		}
	}


	if(isset($_POST['confirmDriverDetails'])){
		if($_POST['confirmDriverDetails']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){
				$bookingid = escapeString($_POST['bookingid']);
				$platenumber = escapeString($_POST['platenumber']);
				$driver = escapeString($_POST['driver']);
				$helper = escapeString($_POST['helper']);
				$drivercontactnumber = escapeString($_POST['drivercontactnumber']);
				$timeready = escapeString($_POST['timeready'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['timeready'])));
				$status = '';
				$bookingnumber = '';

				
				if($timeready=='NULL'||$timeready=='1970-01-01 08:00:00'){
					$dataarray = array(
										 	"response"=>'invalidtimeready'
									 );

				}
				else{

					$assigndriveraccess = hasAccess(USERID,'#booking-trans-editvehicleinformationbtn');
					if($assigndriveraccess==1){

						$checktxnrs = query("select * from txn_booking where id='$bookingid'");

						if(getNumRows($checktxnrs)==1){

							while($obj=fetch($checktxnrs)){
								$status = $obj->status;
								$bookingnumber = $obj->booking_number;
							}

							if($status=='APPROVED'||$status=='MISSED PICKUP'||$status=='ACKNOWLEDGED'){
								$now = date('Y-m-d H:i:s');
								$userid = USERID;
								$systemlog = new system_log();
								$bookingstathistory = new txn_booking_status_history();

								$rs = query("update txn_booking 
									         set plate_number='$platenumber',
									             driver='$driver',
									             helper='$helper',
									             driver_contact_number='$drivercontactnumber',
									             time_ready='$timeready',
									             status='WAITING FOR RESPONSE',
									             driver_notified=NULL
									         where id='$bookingid'");

								if($rs){
									$systemlog->logInfo('BOOKING','Driver/Helper Assignment',"Booking Number: ".$bookingnumber." | Plate Number: $platenumber | Driver: $driver | Helper: $helper | Driver Contact: $drivercontactnumber | Time Ready: $timeready",$userid,$now);
									$bookingstathistory->insert(array('',$bookingnumber,'WAITING FOR RESPONSE','NULL','NULL','NULL','NULL','NULL',$driver,$drivercontactnumber,$userid,$timeready,$now,$userid,1));

									
									query("update mod_sms_booking_notifications 
										   set send_to_driver=1,
										   	   assigned_by='$userid',
										   	   driver='$driver',
										   	   driver_mobile_number='$drivercontactnumber'
										   where booking_number='$bookingnumber' and 
										         flag=1");

									$dataarray = array(
													 	"response"=>'success'
												 );


								}



							}
							else{
								$dataarray = array(
												 	"response"=>'invalidstatus'
											 );
							}


						}
						else{
								$dataarray = array(
												 	"response"=>'invalidbooking'
											 );
						}
							
					}
					else{
						$dataarray = array(
										 	"response"=>'invalidaccess'
									 );
					}	
				}
				print_r(json_encode($dataarray));
					





		}
	}



	if(isset($_POST['resetDriverDetails'])){
		if($_POST['resetDriverDetails']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$bookingnumber = escapeString($_POST['bookingnumber']);
			$remarks = escapeString($_POST['remarks']);
			$driver = '';
			$helper = '';
			$drivercontactnumber = '';
			$status = '';

			$access = hasAccess(USERID,'#booking-trans-resetvehicleinformationbtn');
			if($access==1){

					$checktxnrs = query("select * from txn_booking where booking_number='$bookingnumber'");

					if(getNumRows($checktxnrs)==1){
							while($obj=fetch($checktxnrs)){
								$status = $obj->status;
								$driver = $obj->driver;
								$helper = $obj->helper;
								$drivercontactnumber = $obj->driver_contact_number;
							}

							if($status=='ACKNOWLEDGED'||$status=='WAITING FOR RESPONSE'){
								$now = date('Y-m-d H:i:s');
								$userid = USERID;
								$systemlog = new system_log();
								$bookingstathistory = new txn_booking_status_history();

								$rs = query("update txn_booking 
									         set status='APPROVED',
									             driver_notified=NULL
									         where booking_number='$bookingnumber'");

								if($rs){
									$systemlog->logInfo('BOOKING','Driver/Helper Reset',"Booking Number: ".$bookingnumber." | Reason: $remarks | Driver: $driver | Helper: $helper | Driver Contact No.: $drivercontactnumber",$userid,$now);
									$bookingstathistory->insert(array('',$bookingnumber,'APPROVED','NULL','NULL',"RESET: Reason->$remarks",'NULL','NULL',$driver,$drivercontactnumber,'NULL','NULL',$now,$userid,1));

									$dataarray = array(
													 	"response"=>'success'
												 );

								}


							}
							else{
								$dataarray = array(
												 	"response"=>'invalidstatus'
											 );
							}

					}
					else{
							$dataarray = array(
												 	"response"=>'invalidbooking'
											 );
					}
							
						
			}
			else{
				$dataarray = array(
								 	"response"=>'invalidaccess'
							      );
			}
			print_r(json_encode($dataarray));

		}
	}





	if(isset($_FILES['bookingFileAttachments'])){
		
	}

    // Auto Set For Booking Type
	if (isset($_POST['getDefaultBookingType'])) {
		$name = strtoupper(trim($_POST['name']));
		
		$sql = "SELECT id, description as name FROM booking_type WHERE UPPER(description) = '$name' LIMIT 1";
		$result = query($sql);  
		
		if (getNumRows($result) > 0) {
			$row = fetch($result);
			echo json_encode([
				'response' => 'success',
				'id'       => $row->id,
				'name'     => $row->name
			]);
		} else {
			echo json_encode(['response' => 'notfound']);
		}
		exit;
	}


	





?>
