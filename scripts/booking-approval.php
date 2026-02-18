 <?php
    include('../config/connection.php');
    include('../config/checklogin.php');
    include('../config/checkurlaccess.php');
    include('../config/functions.php');
    include("../classes/system-log.class.php");
    include("../classes/email.class.php");
    include("../classes/booking-status-history.class.php");

    if(isset($_POST['approveRejectBooking'])){
    	if($_POST['approveRejectBooking']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){

    			$action = escapeString(strtoupper($_POST['action']));
    			$bookingnumber = escapeString($_POST['bookingnumber']);
    			$remarks = escapeString($_POST['remarks']);
    			$bookingid = '';
    			$now = date('Y-m-d H:i:s');
    			$userid = USERID;
    			$status = '';
    			$bookingstathistory = new txn_booking_status_history();

    			$checkbookingnumrs = query("select * from txn_booking where booking_number='$bookingnumber'");

    			if(getNumRows($checkbookingnumrs)==1){
    				while($obj=fetch($checkbookingnumrs)){
    					$bookingid = $obj->id;
    					$status = $obj->status;
    				}

    				if($status=='POSTED'){
	    				$rs = query("insert into txn_booking_approval_rejection_history(
	    																						action_taken,
	    																						booking_id,
	    																						remarks,
	    																						created_date,
	    																						created_by
	    																			       )
						    						                                 values(	
						    						                                 			'$action',
						    						                                 			'$bookingid',
						    						                                 			'$remarks',
						    						                                 			'$now',
						    						                                 			'$userid'
						    						                             	       )");


	    				if($rs){
	    					$approvalrejectionID = mysql_insert_id();
	    					$rs = query("insert into txn_booking_history                   (
	    																						approval_transaction_history_id,
	    																						booking_number,
	    																						status,
	    																						origin_id,
	    																						destination_id,
	    																						approved_by,
	    																						approved_date,
	    																						rejected_by,
	    																						rejected_date,
	    																						rejected_reason,
	    																						pickup_date,
	    																						actual_pickup_date,
	    																						pickup_by,
	    																						remarks,
	    																						created_date,
	    																						created_by,
	    																						updated_date,
	    																						updated_by,
	    																						shipper_id,
	    																						shipper_account_number,
	    																						shipper_name,
	    																						shipper_tel_number,
	    																						shipper_company_name,
	    																						shipper_street_address,
	    																						shipper_district,
	    																						shipper_city,
	    																						shipper_state_province,
	    																						shipper_zip_code,
	    																						shipper_country,
	    																						shipper_pickup_street_address,
	    																						shipper_pickup_district,
	    																						shipper_pickup_city,
	    																						shipper_pickup_state_province,
	    																						shipper_pickup_zip_code,
	    																						shipper_pickup_country,
	    																						consignee_id,
	    																						consignee_account_number,
	    																						consignee_name,
	    																						consignee_tel_number,
	    																						consignee_company_name,
	    																						consignee_street_address,
	    																						consignee_district,
	    																						consignee_city,
	    																						consignee_state_province,
	    																						consignee_zip_code,
	    																						consignee_country,
	    																						package_number_of_packages,
	    																						unit_of_measure,
	    																						package_declared_value,
	    																						package_actual_weight,
	    																						package_cbm,
	    																						package_vw,
	    																						package_service,
	    																						package_handling_instruction,
	    																						package_mode_of_transport,
	    																						package_pay_mode,
	    																						package_amount,
	    																						shipment_description,
	    																						posted_date,
	    																						posted_by,
	    																						package_document,
	    																						location_id,
	    																						accompanying_document_description,
	    																						handling_instruction_description,
	    																						vehicle_type_id,
	    																						vehicle_type_type,
	    																						driver,
	    																						helper,
	    																						driver_contact_number,
	    																						bill_to,
	    																						plate_number,
	    																						time_ready,
	    																						booking_type_id,
																								trucking_details



	    																			       )
						    						                         select '$approvalrejectionID',
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
						    						                         	    txn_booking.unit_of_measure,
						    						                         	    txn_booking.package_declared_value,
						    						                         	    txn_booking.package_actual_weight,
						    						                         	    txn_booking.package_cbm,
						    						                         	    txn_booking.package_vw,
						    						                         	    txn_booking.package_service,
						    						                         	    txn_booking.package_handling_instruction,
						    						                         	    txn_booking.package_mode_of_transport,
						    						                         	    txn_booking.package_pay_mode,
						    						                         	    txn_booking.package_amount,
						    						                         	    txn_booking.shipment_description,
						    						                         	    txn_booking.posted_date,
						    						                         	    txn_booking.posted_by,
						    						                         	    txn_booking.package_document,
						    						                         	    txn_booking.location_id,
						    						                         	    group_concat(distinct accompanying_documents.description separator ', ') as document,
																					group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
																					txn_booking.vehicle_type_id,
																					vehicle_type.type,
																					txn_booking.driver,
																					txn_booking.helper,
																					txn_booking.driver_contact_number,
																					txn_booking.bill_to,
																					txn_booking.plate_number,
																					txn_booking.time_ready,
																					txn_booking.booking_type_id,
																					txn_booking.trucking_details
						    						                         from txn_booking 
						    						                         left join vehicle_type on vehicle_type.id=txn_booking.vehicle_type_id
						    						                         left join txn_booking_document on txn_booking_document.booking_number=txn_booking.booking_number
				         													 left join txn_booking_handling_instruction on txn_booking_handling_instruction.booking_number=txn_booking.booking_number
  																			left join accompanying_documents on accompanying_documents.id=txn_booking_document.accompanying_document_id
 																			left join handling_instruction on handling_instruction.id=txn_booking_handling_instruction.handling_instruction_id
						    						                        where txn_booking.booking_number='$bookingnumber'
						    						                        group by txn_booking.booking_number");

	    					if($rs){
	    						$systemlog = new system_log();
			    				if($action=='APPROVE'){
			    					$rs = query("update txn_booking set status='APPROVED', approved_by='$userid', approved_date='$now', rejected_date=NULL, rejected_by=NULL, rejected_reason=NULL where booking_number='$bookingnumber'");

			    					$systemlog->logInfo('BOOKING APPROVAL','Approved Booking Transaction',"Booking Number: ".$bookingnumber." | Remarks: $remarks",$userid,$now);

			    					$supervisor = getNameOfUser(USERID);
			    					$bookingstathistory->insert(array('',$bookingnumber,'APPROVED','NULL','NULL',$remarks,$supervisor,'NULL','NULL','NULL','NULL','NULL',$now,$userid,0));


			    				}
			    				else if($action=='REJECT'){
			    					
			    					/** EMAIL NOTIFICATION **/
			    					// Send rejection email using the new function
									$emailResult = sendBookingRejectionEmail($bookingnumber, $remarks);
									
									// Log the email result for debugging
									if($emailResult['success']) {
										// Email sent successfully
										error_log("Booking rejection email sent successfully for: " . $bookingnumber);
									} else {
										// Email failed to send, log the error
										error_log("Failed to send booking rejection email: " . $emailResult['message']);
									}
									/***************************/

									$rs = query("update txn_booking set status='REJECTED', supervisor_notified=NULL, driver_notified=NULL, rejected_date='$now', rejected_by='$userid', rejected_reason='$remarks', posted_date=NULL, posted_by=NULL where booking_number='$bookingnumber'");



			    					$systemlog->logInfo('BOOKING APPROVAL','Rejected Booking Transaction',"Booking Number: ".$bookingnumber." | Remarks: $remarks",$userid,$now);

			    					$supervisor = getNameOfUser(USERID);
			    					$bookingstathistory->insert(array('',$bookingnumber,'REJECTED','NULL','NULL',$remarks,$supervisor,'NULL','NULL','NULL','NULL','NULL',$now,$userid,0));

			    					//query("update mod_sms_booking_notifications set flag=0 where booking_number='$bookingnumber' and flag=1");

			    				}
			    				else{
			    					echo "unable to update status";
			    				}

			    				
								

			    				echo "success";
			    			}
	    				}
	    			}
	    			else{
	    				echo "bookingnotposted";
	    			}

    			}
    			else{
    				echo "invalidbookingnumber";
    			}


    	}
    }



    if(isset($_POST['getBookingDetails'])){
    	if($_POST['getBookingDetails']=='kjho#hikh@Oidpo%$n85hoddpm!lqplkohi'){

    		$id = escapeString($_POST['id']);
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
				                txn_booking.unit_of_measure,
				                txn_booking.package_service,
				                txn_booking.package_mode_of_transport,
				                txn_booking.package_handling_instruction,
				                txn_booking.package_pay_mode,
				                txn_booking.package_amount,
				                txn_booking.shipment_description,
				                txn_booking.posted_by,
				                txn_booking.posted_date,
				                txn_booking.package_document,
				                txn_booking.vehicle_type_id,
				                txn_booking.driver,
				                txn_booking.helper,
				                txn_booking.time_ready,
				                txn_booking.driver_contact_number,
				                txn_booking.bill_to,
								txn_booking.trucking_details,
				                txn_booking.plate_number,
				                vehicle_type.description as vehicletype,
				                vehicle_type.type as vehicletypetype,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
				                group_concat(distinct accompanying_documents.description separator ', ') as document,
				                mode_of_transport.description as modeoftransport,
				                group_concat(distinct handling_instruction.description separator ', ') as handlinginstruction,
				                location.description as location,
				                booking_type.description as bookingtype
				         from txn_booking
				         left join vehicle_type on vehicle_type.id=txn_booking.vehicle_type_id
				         left join txn_booking_document on txn_booking_document.booking_number=txn_booking.booking_number
				         left join txn_booking_handling_instruction on txn_booking_handling_instruction.booking_number=txn_booking.booking_number
				         left join origin_destination_port as origintbl on origintbl.id=txn_booking.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking.destination_id 
				         left join services on services.id=txn_booking.package_service
				         left join location on location.id=txn_booking.location_id
				         left join accompanying_documents on accompanying_documents.id=txn_booking_document.accompanying_document_id
				         left join mode_of_transport on mode_of_transport.id=txn_booking.package_mode_of_transport
				         left join handling_instruction on handling_instruction.id=txn_booking_handling_instruction.handling_instruction_id
				         left join booking_type on booking_type.id=txn_booking.booking_type_id
				         where txn_booking.id = '$id'
				         group by txn_booking.booking_number");

			$shipperaddress = array();
			$consigneeaddress = array();
			$pickupaddress = array();
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$timeready = dateFormat($obj->time_ready, "m/d/Y h:i:s A");
					$createddate = dateFormat($obj->created_date, "m/d/Y H:i:s");
					$createdby = getNameOfUser($obj->created_by);
					$updateddate = dateFormat($obj->updated_date, "m/d/Y H:i:s");
					$updatedby = getNameOfUser($obj->updated_by);
					$posteddate = dateFormat($obj->posted_date, "m/d/Y H:i:s");
					$postedby = getNameOfUser($obj->posted_by);
					$approveddate = dateFormat($obj->approved_date, "m/d/Y H:i:s");
					$approvedby = getNameOfUser($obj->approved_by);
					$rejecteddate = dateFormat($obj->rejected_date, "m/d/Y H:i:s");
					$rejectedby = getNameOfUser($obj->rejected_by);
					$pickupdate = dateFormat($obj->pickup_date, "m/d/Y");
					$actualpickupdate = dateFormat($obj->actual_pickup_date, "m/d/Y H:i:s");

					$zip = '';
					$bgy = '';
					if(trim($obj->shipper_street_address)!=''){
						array_push($shipperaddress,trim($obj->shipper_street_address,','));
					}
					if(trim($obj->shipper_district)!=''){
						$bgy = "<br>".$obj->shipper_district.", ";
					}
					if(trim($obj->shipper_city)!=''){
						array_push($shipperaddress,$bgy.trim($obj->shipper_city,','));
					}
					if(trim($obj->shipper_zip_code)!=''){
						$zip = "<br>".$obj->shipper_zip_code." ";
					}
					if(trim($obj->shipper_state_province)!=''){
						array_push($shipperaddress,$zip.trim($obj->shipper_state_province,','));
					}
					if(trim($obj->shipper_country)!=''){
						array_push($shipperaddress,"".trim($obj->shipper_country,','));
					}
					$shipperaddress = implode(', ', $shipperaddress);

					$zip = '';
					$bgy = '';
					if(trim($obj->shipper_pickup_street_address)!=''){
						array_push($pickupaddress,trim($obj->shipper_pickup_street_address,','));
					}
					if(trim($obj->shipper_pickup_district)!=''){
						$bgy = "<br>".$obj->shipper_pickup_district.", ";
					}
					if(trim($obj->shipper_pickup_city)!=''){
						array_push($pickupaddress,$bgy.trim($obj->shipper_pickup_city,','));
					}
					if(trim($obj->shipper_pickup_zip_code)!=''){
						$zip = "<br>".$obj->shipper_pickup_zip_code." ";
					}
					if(trim($obj->shipper_pickup_state_province)!=''){
						array_push($pickupaddress,$zip.trim($obj->shipper_pickup_state_province,','));
					}
					if(trim($obj->shipper_pickup_country)!=''){
						array_push($pickupaddress,"".trim($obj->shipper_pickup_country,','));
					}
					$pickupaddress = implode(', ', $pickupaddress);


					$zip = '';
					$bgy = '';
					if(trim($obj->consignee_street_address)!=''){
						array_push($consigneeaddress,trim($obj->consignee_street_address,','));
					}
					if(trim($obj->consignee_district)!=''){
						$bgy = "<br>".$obj->consignee_district.", ";
					}
					if(trim($obj->consignee_city)!=''){
						array_push($consigneeaddress,$bgy.trim($obj->consignee_city,','));
					}
					if(trim($obj->consignee_zip_code)!=''){
						$zip = "<br>".$obj->consignee_zip_code." ";
					}
					if(trim($obj->consignee_state_province)!=''){
						array_push($consigneeaddress,$zip.trim($obj->consignee_state_province,','));
					}
					if(trim($obj->consignee_country)!=''){
						array_push($consigneeaddress,"".trim($obj->consignee_country,','));
					}
					$consigneeaddress = implode(', ', $consigneeaddress);




					

					$dataarray = array(
									   "response"=>'success',
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "remarks"=>utfEncode($obj->remarks),
									   "pickupdate"=>utfEncode($pickupdate),
									   "shipperid"=>utfEncode($obj->shipper_id),
									   "shipperaccountnumber"=>utfEncode($obj->shipper_account_number),
									   "shipperaccountname"=>utfEncode($obj->shipper_name),
									   "shippertel"=>utfEncode($obj->shipper_tel_number),
									   "shippercompanyname"=>utfEncode($obj->shipper_company_name),
									   "shipperstreet"=>utfEncode($obj->shipper_street_address),
									   "shipperdistrict"=>utfEncode($obj->shipper_district),
									   "shippercity"=>utfEncode($obj->shipper_city),
									   "shipperprovince"=>utfEncode($obj->shipper_state_province),
									   "shipperzipcode"=>utfEncode($obj->shipper_zip_code),
									   "shippercountry"=>utfEncode($obj->shipper_country),
									   "shipperaddress"=>utfEncode($shipperaddress),
									   "pickupstreet"=>utfEncode($obj->shipper_pickup_street_address),
									   "pickupdistrict"=>utfEncode($obj->shipper_pickup_district),
									   "pickupcity"=>utfEncode($obj->shipper_pickup_city),
									   "pickupprovince"=>utfEncode($obj->shipper_pickup_state_province),
									   "pickupzipcode"=>utfEncode($obj->shipper_pickup_zip_code),
									   "pickupcountry"=>utfEncode($obj->shipper_pickup_country),
									   "pickupaddress"=>utfEncode($pickupaddress),
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
									   "consigneeaddress"=>utfEncode($consigneeaddress),
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
									   "actualpickupdate"=>$obj->actual_pickup_date,
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
									   "uom"=>utfEncode($obj->unit_of_measure),
									   "documentid"=>utfEncode($obj->package_document),
									   "document"=>utfEncode($obj->document),
									   "location"=>utfEncode($obj->location),
									   "vehicletype"=>utfEncode($obj->vehicletype),
									   "driverfor"=>utfEncode($obj->vehicletypetype),
									   "platenumber"=>utfEncode($obj->plate_number),
									   "driver"=>utfEncode($obj->driver),
									   "helper"=>utfEncode($obj->helper),
									   "drivercontactnumber"=>utfEncode($obj->driver_contact_number),
									   "billto"=>utfEncode($obj->bill_to),
									   "timeready"=>utfEncode($timeready),
									   "bookingtype"=>utfEncode($obj->bookingtype),
									   "truckingdetails"=>utfEncode($obj->trucking_details)


									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
    	}
    }


    if(isset($_POST['getBookingHistoryDetails'])){
    	if($_POST['getBookingHistoryDetails']=='kjho#hikh@Oidpo%$n85hoddpm!lqplkohi'){

    		$id = escapeString($_POST['id']);
			$rs = query("select txn_booking_history.id, 
				                txn_booking_history.booking_number,
				                txn_booking_history.status,
				                txn_booking_history.origin_id,
				                txn_booking_history.destination_id,
				                txn_booking_history.approved_by,
				                txn_booking_history.approved_date,
				                txn_booking_history.rejected_by,
				                txn_booking_history.rejected_date,
				                txn_booking_history.rejected_reason,
				                txn_booking_history.pickup_date,
				                txn_booking_history.actual_pickup_date,
				                txn_booking_history.pickup_by,
				                txn_booking_history.remarks,
				                txn_booking_history.created_date,
				                txn_booking_history.created_by,
				                txn_booking_history.updated_date,
				                txn_booking_history.updated_by,
				                txn_booking_history.shipper_id,
				                txn_booking_history.shipper_account_number,
				                txn_booking_history.shipper_name,
				                txn_booking_history.shipper_tel_number,
				                txn_booking_history.shipper_company_name,
				                txn_booking_history.shipper_street_address,
				                txn_booking_history.shipper_district,
				                txn_booking_history.shipper_city,
				                txn_booking_history.shipper_state_province,
				                txn_booking_history.shipper_zip_code,
				                txn_booking_history.shipper_country,
				                txn_booking_history.shipper_pickup_street_address,
				                txn_booking_history.shipper_pickup_district,
				                txn_booking_history.shipper_pickup_city,
				                txn_booking_history.shipper_pickup_state_province,
				                txn_booking_history.shipper_pickup_zip_code,
				                txn_booking_history.shipper_pickup_country,
				                txn_booking_history.consignee_id,
				                txn_booking_history.consignee_account_number,
				                txn_booking_history.consignee_name,
				                txn_booking_history.consignee_tel_number,
				                txn_booking_history.consignee_company_name,
				                txn_booking_history.consignee_street_address,
				                txn_booking_history.consignee_district,
				                txn_booking_history.consignee_city,
				                txn_booking_history.consignee_state_province,
				                txn_booking_history.consignee_zip_code,
				                txn_booking_history.consignee_country,
				                txn_booking_history.package_number_of_packages,
				                txn_booking_history.unit_of_measure,
				                txn_booking_history.package_declared_value,
				                txn_booking_history.package_actual_weight,
				                txn_booking_history.package_cbm,
				                txn_booking_history.package_vw,
				                txn_booking_history.package_service,
				                txn_booking_history.package_mode_of_transport,
				                txn_booking_history.package_handling_instruction,
				                txn_booking_history.package_pay_mode,
				                txn_booking_history.package_amount,
				                txn_booking_history.shipment_description,
				                txn_booking_history.posted_by,
				                txn_booking_history.posted_date,
				                txn_booking_history.package_document,
				                txn_booking_history.vehicle_type_id,
				                txn_booking_history.vehicle_type_type,
				                txn_booking_history.driver,
				                txn_booking_history.helper,
				                txn_booking_history.driver_contact_number,
				                txn_booking_history.bill_to,
				                txn_booking_history.time_ready,
				                txn_booking_history.plate_number,
				                vehicle_type.description as vehicletype,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
								txn_booking_history.trucking_details,
				                txn_booking_history.accompanying_document_description as document,
				                mode_of_transport.description as modeoftransport,
				                txn_booking_history.handling_instruction_description as handlinginstruction,
				                location.description as location,
				                booking_type.description as bookingtype
				         from txn_booking_history
				         left join vehicle_type on vehicle_type.id=txn_booking_history.vehicle_type_id
				         left join origin_destination_port as origintbl on origintbl.id=txn_booking_history.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_booking_history.destination_id 
				         left join services on services.id=txn_booking_history.package_service
				         left join location on location.id=txn_booking_history.location_id
				         left join accompanying_documents on accompanying_documents.id=txn_booking_history.package_document
				         left join mode_of_transport on mode_of_transport.id=txn_booking_history.package_mode_of_transport
				         left join handling_instruction on handling_instruction.id=txn_booking_history.package_handling_instruction
				         left join booking_type on booking_type.id=txn_booking_history.booking_type_id
				         where txn_booking_history.approval_transaction_history_id = '$id'");

			$shipperaddress = array();
			$consigneeaddress = array();
			$pickupaddress = array();
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$timeready = dateFormat($obj->time_ready, "m/d/Y h:i:s A");
					$createddate = dateFormat($obj->created_date, "m/d/Y H:i:s");
					$createdby = getNameOfUser($obj->created_by);
					$updateddate = dateFormat($obj->updated_date, "m/d/Y H:i:s");
					$updatedby = getNameOfUser($obj->updated_by);
					$posteddate = dateFormat($obj->posted_date, "m/d/Y H:i:s");
					$postedby = getNameOfUser($obj->posted_by);
					$approveddate = dateFormat($obj->approved_date, "m/d/Y H:i:s");
					$approvedby = getNameOfUser($obj->approved_by);
					$rejecteddate = dateFormat($obj->rejected_date, "m/d/Y H:i:s");
					$rejectedby = getNameOfUser($obj->rejected_by);
					$pickupdate = dateFormat($obj->pickup_date, "m/d/Y");
					$actualpickupdate = dateFormat($obj->actual_pickup_date, "m/d/Y H:i:s");

					$zip = '';
					$bgy = '';
					if(trim($obj->shipper_street_address)!=''){
						array_push($shipperaddress,trim($obj->shipper_street_address,','));
					}
					if(trim($obj->shipper_district)!=''){
						$bgy = "<br>".$obj->shipper_district.", ";
					}
					if(trim($obj->shipper_city)!=''){
						array_push($shipperaddress,$bgy.trim($obj->shipper_city,','));
					}
					if(trim($obj->shipper_zip_code)!=''){
						$zip = "<br>".$obj->shipper_zip_code." ";
					}
					if(trim($obj->shipper_state_province)!=''){
						array_push($shipperaddress,$zip.trim($obj->shipper_state_province,','));
					}
					if(trim($obj->shipper_country)!=''){
						array_push($shipperaddress,"".trim($obj->shipper_country,','));
					}
					$shipperaddress = implode(', ', $shipperaddress);

					$zip = '';
					$bgy = '';
					if(trim($obj->shipper_pickup_street_address)!=''){
						array_push($pickupaddress,trim($obj->shipper_pickup_street_address,','));
					}
					if(trim($obj->shipper_pickup_district)!=''){
						$bgy = "<br>".$obj->shipper_pickup_district.", ";
					}
					if(trim($obj->shipper_pickup_city)!=''){
						array_push($pickupaddress,$bgy.trim($obj->shipper_pickup_city,','));
					}
					if(trim($obj->shipper_pickup_zip_code)!=''){
						$zip = "<br>".$obj->shipper_pickup_zip_code." ";
					}
					if(trim($obj->shipper_pickup_state_province)!=''){
						array_push($pickupaddress,$zip.trim($obj->shipper_pickup_state_province,','));
					}
					if(trim($obj->shipper_pickup_country)!=''){
						array_push($pickupaddress,"".trim($obj->shipper_pickup_country,','));
					}
					$pickupaddress = implode(', ', $pickupaddress);


					$zip = '';
					$bgy = '';
					if(trim($obj->consignee_street_address)!=''){
						array_push($consigneeaddress,trim($obj->consignee_street_address,','));
					}
					if(trim($obj->consignee_district)!=''){
						$bgy = "<br>".$obj->consignee_district.", ";
					}
					if(trim($obj->consignee_city)!=''){
						array_push($consigneeaddress,$bgy.trim($obj->consignee_city,','));
					}
					if(trim($obj->consignee_zip_code)!=''){
						$zip = "<br>".$obj->consignee_zip_code." ";
					}
					if(trim($obj->consignee_state_province)!=''){
						array_push($consigneeaddress,$zip.trim($obj->consignee_state_province,','));
					}
					if(trim($obj->consignee_country)!=''){
						array_push($consigneeaddress,"".trim($obj->consignee_country,','));
					}
					$consigneeaddress = implode(', ', $consigneeaddress);




					

					$dataarray = array(
									   "response"=>'success',
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "remarks"=>utfEncode($obj->remarks),
									   "pickupdate"=>utfEncode($pickupdate),
									   "shipperid"=>utfEncode($obj->shipper_id),
									   "shipperaccountnumber"=>utfEncode($obj->shipper_account_number),
									   "shipperaccountname"=>utfEncode($obj->shipper_name),
									   "shippertel"=>utfEncode($obj->shipper_tel_number),
									   "shippercompanyname"=>utfEncode($obj->shipper_company_name),
									   "shipperstreet"=>utfEncode($obj->shipper_street_address),
									   "shipperdistrict"=>utfEncode($obj->shipper_district),
									   "shippercity"=>utfEncode($obj->shipper_city),
									   "shipperprovince"=>utfEncode($obj->shipper_state_province),
									   "shipperzipcode"=>utfEncode($obj->shipper_zip_code),
									   "shippercountry"=>utfEncode($obj->shipper_country),
									   "shipperaddress"=>utfEncode($shipperaddress),
									   "pickupstreet"=>utfEncode($obj->shipper_pickup_street_address),
									   "pickupdistrict"=>utfEncode($obj->shipper_pickup_district),
									   "pickupcity"=>utfEncode($obj->shipper_pickup_city),
									   "pickupprovince"=>utfEncode($obj->shipper_pickup_state_province),
									   "pickupzipcode"=>utfEncode($obj->shipper_pickup_zip_code),
									   "pickupcountry"=>utfEncode($obj->shipper_pickup_country),
									   "pickupaddress"=>utfEncode($pickupaddress),
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
									   "consigneeaddress"=>utfEncode($consigneeaddress),
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
									   "actualpickupdate"=>utfEncode($obj->actual_pickup_date),
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
									   "uom"=>utfEncode($obj->unit_of_measure),
									   "documentid"=>utfEncode($obj->package_document),
									   "document"=>utfEncode($obj->document),
									   "location"=>utfEncode($obj->location),
									   "vehicletype"=>utfEncode($obj->vehicletype),
									   "driverfor"=>utfEncode($obj->vehicle_type_type),
									   "platenumber"=>utfEncode($obj->plate_number),
									   "driver"=>utfEncode($obj->driver),
									   "helper"=>utfEncode($obj->helper),
									   "drivercontactnumber"=>utfEncode($obj->driver_contact_number),
									   "billto"=>utfEncode($obj->bill_to),
									   "timeready"=>utfEncode($timeready),
									   "bookingtype"=>utfEncode($obj->bookingtype),
									   "truckingdetails"=>utfEncode($obj->trucking_details)
									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
    	}
    }
?>
