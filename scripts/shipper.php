<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/shipper.class.php");
    include("../classes/shipper-contact.class.php");
    include("../classes/shipper-rate.class.php");
    include("../classes/shipper-rate-handling-instruction.class.php");
    include("../classes/shipper-rate-freight-charge.class.php");
    include("../classes/shipper-pickup-address.class.php");
    include("../classes/system-log.class.php");////////

     

	if(isset($_POST['shipperSaveEdit'])){
		if($_POST['shipperSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$creditlimitaccess = hasAccess(USERID,'.creditlimitaccess');
			$source = escapeString($_POST['source']);
			$accountnumber = escapeString(strtoupper($_POST['accountnumber']));
			$accountname = escapeString(ucwords(strtolower($_POST['accountname'])));
			$companyname = escapeString(ucwords(strtolower($_POST['companyname'])));
			$billingincharge = escapeString($_POST['billingincharge']);
			$billingincharge = trim($billingincharge)==''?'NULL':$billingincharge;
			$accountexecutive = escapeString($_POST['accountexecutive']);
			$accountexecutive = trim($accountexecutive)==''?'NULL':$accountexecutive;
			$nonpodflag = escapeString($_POST['nonpodflag']);
			$vatflag = escapeString($_POST['vatflag']);
			$shipperstatus = escapeString($_POST['shipperstatus']);
			$paymode = escapeString($_POST['paymode']);
			$businessstyle = escapeString($_POST['businessstyle']);
			$creditlimit = escapeString(str_replace(',', '', trim($_POST['creditlimit'])));
			$creditterm = escapeString($_POST['creditterm']);
			$podinstruction = escapeString($_POST['podinstruction']);
			$tin = escapeString($_POST['tin']);
			$billingcutoff = escapeString($_POST['billingcutoff']);
			$lineofbusiness = escapeString($_POST['lineofbusiness']);
			$collectioncontactperson = escapeString($_POST['collectioncontactperson']);
			$collectionday = escapeString($_POST['collectionday']);
			$collectionlocation = escapeString($_POST['collectionlocation']);
			$companystreet = trim($_POST['companystreet'])==''?'NULL':escapeString($_POST['companystreet']);
			$companydistrict = trim($_POST['companydistrict'])==''?'NULL':escapeString($_POST['companydistrict']);
			$companycity = trim($_POST['companycity'])==''?'NULL':escapeString($_POST['companycity']);
			$companyprovince = trim($_POST['companyprovince'])==''?'NULL':escapeString($_POST['companyprovince']);
			$companyzipcode = trim($_POST['companyzipcode'])==''?'NULL':escapeString($_POST['companyzipcode']);
			$companycountry = trim($_POST['companycountry'])==''?'NULL':escapeString($_POST['companycountry']);
			$billingstreet = trim($_POST['billingstreet'])==''?'NULL':escapeString($_POST['billingstreet']);
			$billingdistrict = trim($_POST['billingdistrict'])==''?'NULL':escapeString($_POST['billingdistrict']);
			$billingcity = trim($_POST['billingcity'])==''?'NULL':escapeString($_POST['billingcity']);
			$billingprovince = trim($_POST['billingprovince'])==''?'NULL':escapeString($_POST['billingprovince']);
			$billingzipcode = trim($_POST['billingzipcode'])==''?'NULL':escapeString($_POST['billingzipcode']);
			$billingcountry = trim($_POST['billingcountry'])==''?'NULL':escapeString($_POST['billingcountry']);


			if($creditlimitaccess!=1){
				if($source=='edit'){
					$creditlimit = 'NOCHANGE';
				}
				else{
					$creditlimit = 0;
				}
			}


			$returndocumentfee = (is_numeric($_POST['returndocumentfee'])==1)?escapeString(round($_POST['returndocumentfee'],4)):0;
			$waybillfee = (is_numeric($_POST['waybillfee'])==1)?escapeString(round($_POST['waybillfee'],4)):0;
			$securityfee = (is_numeric($_POST['securityfee'])==1)?escapeString(round($_POST['securityfee'],4)):0;
			$docstampfee = (is_numeric($_POST['docstampfee'])==1)?escapeString(round($_POST['docstampfee'],4)):0;


			/*$pickupstreet = escapeString($_POST['pickupstreet']);
			$pickupdistrict = escapeString($_POST['pickupdistrict']);
			$pickupcity = escapeString($_POST['pickupcity']);
			$pickupprovince = escapeString($_POST['pickupprovince']);
			$pickupzipcode = escapeString($_POST['pickupzipcode']);
			$pickupcountry = escapeString($_POST['pickupcountry']);*/





		

			@$contact = $_POST['contact'];
			@$email = $_POST['email'];
			@$phonenumber= $_POST['phonenumber'];
			@$mobilenumber = $_POST['mobilenumber'];
			@$defaultflag = $_POST['defaultflag'];
			@$sendsmsflag = $_POST['sendsmsflag'];
			@$sendemailflag = $_POST['sendemailflag'];
			$contactiterate = count($contact);

			@$fixedrate = $_POST['fixedrate'];
			@$origin = $_POST['origin'];
			@$destination= $_POST['destination'];
			@$modeoftransport = $_POST['modeoftransport'];
			@$freightcomputation = $_POST['freightcomputation'];
			@$valuation = $_POST['valuation'];
			@$freightrate = $_POST['freightrate'];
			@$insurancerate = $_POST['insurancerate'];
			@$fuelrate = $_POST['fuelrate'];
			@$bunkerrate = $_POST['bunkerrate'];
			@$minimumrate = $_POST['minimumrate'];
			$rateiterate = count($origin);

			@$pickupstreet = $_POST['pickupstreet'];
			@$pickupdistrict = $_POST['pickupdistrict'];
			@$pickupcity = $_POST['pickupcity'];
			@$pickupprovince = $_POST['pickupprovince'];
			@$pickupzipcode = $_POST['pickupzipcode'];
			@$pickupcountry = $_POST['pickupcountry'];
			@$defaultpickupaddressflag = $_POST['defaultpickupaddressflag'];
			$pickupaddressiterate = count($pickupstreet);

			
			$wboutstandingbalance = 0;

			$qry = "select * from shipper where upper(account_number)='$accountnumber'";
			if($source=='edit'){
				$rowid = escapeString($_POST['id']);
				$qry = "select * from shipper where upper(account_number)='$accountnumber' and id!='$rowid'";

				$wboutstandingbalance = getShipperOutstandingBalance($rowid);
				$wboutstandingbalance = str_replace(',', '', $wboutstandingbalance);

			} 

			if($source=='add'||($creditlimit>=$wboutstandingbalance)){
				
				$rs = query($qry);
				if(getNumRows($rs)==0){
					$userid = USERID;
					$now = date('Y-m-d H:i:s');
					$shipperclass = new shipper();
					$shippercontactclass = new shipper_contact();
					$shipperrateclass = new shipper_rate();
					$shipperpickupaddressclass = new shipper_pickup_address();
					$systemlog = new system_log();

					if($source=='add'){
						$accountnumber = getTransactionNumber(6);
						$shipperclass->insert(array('',0,$accountnumber,$accountname,$companyname,$companystreet,$companydistrict,$companycity,$companyprovince,$companyzipcode,$companycountry,$billingstreet,$billingdistrict,$billingcity,$billingprovince,$billingzipcode,$billingcountry,$billingincharge,$accountexecutive,$nonpodflag,$vatflag,$shipperstatus,$userid,$now,'NULL','NULL',$paymode,$businessstyle,$creditlimit,$creditterm,$tin,$lineofbusiness,$collectioncontactperson,$billingcutoff,$collectionday,$collectionlocation,$podinstruction,$returndocumentfee,$waybillfee,$securityfee,$docstampfee));
						$id = $shipperclass->getInsertId();
						$systemlog->logAddedInfo($shipperclass,array('',0,$accountnumber,$accountname,$companyname,$companystreet,$companydistrict,$companycity,$companyprovince,$companyzipcode,$companycountry,$billingstreet,$billingdistrict,$billingcity,$billingprovince,$billingzipcode,$billingcountry,$billingincharge,$accountexecutive,$nonpodflag,$vatflag,$shipperstatus,$userid,$now,'NULL','NULL',$paymode,$businessstyle,$creditlimit,$creditterm,$tin,$lineofbusiness,$collectioncontactperson,$billingcutoff,$collectionday,$collectionlocation,$podinstruction,$returndocumentfee,$waybillfee,$securityfee,$docstampfee),'SHIPPER','New Shipper Added',$userid,$now);
					}
					else if($source=='edit'){
						$id = escapeString($_POST['id']);
						$inactiveflag = escapeString($_POST['inactiveflag']);
						$systemlog->logEditedInfo($shipperclass,$id,array($id,$inactiveflag,$accountnumber,$accountname,$companyname,$companystreet,$companydistrict,$companycity,$companyprovince,$companyzipcode,$companycountry,$billingstreet,$billingdistrict,$billingcity,$billingprovince,$billingzipcode,$billingcountry,$billingincharge,$accountexecutive,$nonpodflag,$vatflag,$shipperstatus,'','',$userid,$now,$paymode,$businessstyle,$creditlimit,$creditterm,$tin,$lineofbusiness,$collectioncontactperson,$billingcutoff,$collectionday,$collectionlocation,$podinstruction,$returndocumentfee,$waybillfee,$securityfee,$docstampfee),'SHIPPER','Edited Shipper Info',$userid,$now);/// log should be before update is made
						$shipperclass->update($id,array($inactiveflag,$accountnumber,$accountname,$companyname,$companystreet,$companydistrict,$companycity,$companyprovince,$companyzipcode,$companycountry,$billingstreet,$billingdistrict,$billingcity,$billingprovince,$billingzipcode,$billingcountry,$billingincharge,$accountexecutive,$nonpodflag,$vatflag,$shipperstatus,'NOCHANGE','NOCHANGE',$userid,$now,$paymode,$businessstyle,$creditlimit,$creditterm,$tin,$lineofbusiness,$collectioncontactperson,$billingcutoff,$collectionday,$collectionlocation,$podinstruction,$returndocumentfee,$waybillfee,$securityfee,$docstampfee));
					}

					$contactdata = array();
					for($i=0;$i<$contactiterate;$i++){
						$temp = array();
						array_push($temp,
									NULL,
								    $id,//agentID
								    escapeString($contact[$i]),
									escapeString($phonenumber[$i]),
									escapeString($email[$i]),
									escapeString($mobilenumber[$i]),
									$now,
									escapeString($defaultflag[$i]),
									escapeString($sendsmsflag[$i]),
									escapeString($sendemailflag[$i])
								    );
						array_push($contactdata, $temp);
					}
					$shippercontactclass->deleteWhere("where shipper_id=".$id);
					if($contactiterate>0){
						$shippercontactclass->insertMultiple($contactdata);
					}


					/*$ratedata = array();
					for($i=0;$i<$rateiterate;$i++){
						$temp = array();
						array_push($temp,
									NULL,
								    $id,//agentID
								    'NULL',
									escapeString($origin[$i]),
									escapeString($destination[$i]),
									escapeString($modeoftransport[$i]),
									escapeString($freightcomputation[$i]),
									escapeString($fixedrate[$i]),
									escapeString($valuation[$i]),
									escapeString($freightrate[$i]),
									escapeString($insurancerate[$i]),
									escapeString($fuelrate[$i]),
									escapeString($bunkerrate[$i]),
									escapeString($minimumrate[$i])
								    );
						array_push($ratedata, $temp);
					}
					$shipperrateclass->deleteWhere("where shipper_id=".$id);
					if($rateiterate>0){
						$shipperrateclass->insertMultiple($ratedata);
					}*/


					$pickupaddressdata = array();
					for($i=0;$i<$pickupaddressiterate;$i++){
						$temp = array();
						array_push($temp,
									NULL,
								    $id,//agentID
								    escapeString($defaultpickupaddressflag[$i]),
									escapeString($pickupstreet[$i]),
									escapeString($pickupdistrict[$i]),
									escapeString($pickupcity[$i]),
									escapeString($pickupprovince[$i]),
									escapeString($pickupzipcode[$i]),
									escapeString($pickupcountry[$i])
								    );
						array_push($pickupaddressdata, $temp);
					}
					$shipperpickupaddressclass->deleteWhere("where shipper_id=".$id);
					if($pickupaddressiterate>0){
						$shipperpickupaddressclass->insertMultiple($pickupaddressdata);
					}




					echo "success";
				}
				else{
					echo "codeexist";
				}
			}
			else{
				echo "invalidcreditlimit";
			}

		}
	}

	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = escapeString($data[$i]);

	        	$rs = query("delete from shipper where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['ShipperGetInfo'])){
		if($_POST['ShipperGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);
			$creditlimitaccess = hasAccess(USERID,'.creditlimitaccess');
			$viewcreditinfoaccess = hasAccess(USERID,'.viewcreditinfoaccess');


			       $rs = query("select shipper.id,
			       		 	           shipper.inactive_flag,
							    	   shipper.account_number,
							    	   shipper.account_name,
							           shipper.company_name,
							           shipper.billing_in_charge as billing_in_charge_id,
							           shipper.account_executive as account_executive_id,
							           shipper.non_pod_flag,
							           shipper.vat_flag,
							           shipper.status,
							           shipper.company_street_address,
							           shipper.company_district,
							           shipper.company_city,
							           shipper.company_state_province,
							           shipper.company_zip_code,
							           shipper.company_country,
							           shipper.billing_street_address,
							           shipper.billing_district,
							           shipper.billing_city,
							           shipper.billing_state_province,
							           shipper.billing_zip_code,
							           shipper.billing_country,
							           shipper.pickup_street_address,
							           shipper.pickup_district,
							           shipper.pickup_city,
							           shipper.pickup_state_province,
							           shipper.pickup_zip_code,
							           shipper.pickup_country,
							           shipper.pay_mode_id,
							           shipper.business_style,
							           shipper.credit_limit,
							           shipper.credit_term,
							           shipper.pod_instruction,
							           shipper.tin,
							           shipper.line_of_business,
							           shipper.collection_contact_person,
							           shipper.billing_cut_off,
							           shipper.collection_day,
							           shipper.collection_location,
							           shipper.return_document_fee,
							           shipper.waybill_fee,
							           shipper.security_fee,
							           shipper.doc_stamp_fee,
									   concat(billinguser.first_name,' ',billinguser.last_name) as billing_in_charge,
									   account_executive.name as account_executive,
									   pay_mode.description as paymode
							    from shipper
								left join user as billinguser on billinguser.id=shipper.billing_in_charge
								left join account_executive on account_executive.id=shipper.account_executive
								left join pay_mode on pay_mode.id=shipper.pay_mode_id
								where shipper.id='$id'
				 	    ");

			if(getNumRows($rs)==1){

				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>utfEncode($obj->id),
										   "accountnumber"=>utfEncode($obj->account_number),
										   "accountname"=>utfEncode($obj->account_name),
										   "companyname"=>utfEncode($obj->company_name),
										   "billinginchargeid"=>utfEncode($obj->billing_in_charge_id),
										   "billingincharge"=>utfEncode($obj->billing_in_charge),
										   "accountexecutiveid"=>utfEncode($obj->account_executive_id),
										   "accountexecutive"=>utfEncode($obj->account_executive),
										   "nonpodflag"=>utfEncode($obj->non_pod_flag),
										   "vatflag"=>utfEncode($obj->vat_flag),
										   "shipperstatus"=>utfEncode($obj->status),
										   "companystreet"=>utfEncode($obj->company_street_address),
										   "companydistrict"=>utfEncode($obj->company_district),
										   "companycity"=>utfEncode($obj->company_city),
										   "companyprovince"=>utfEncode($obj->company_state_province),
										   "companyzipcode"=>utfEncode($obj->company_zip_code),
										   "companycountry"=>utfEncode($obj->company_country),
										   "billingstreet"=>utfEncode($obj->billing_street_address),
										   "billingdistrict"=>utfEncode($obj->billing_district),
										   "billingcity"=>utfEncode($obj->billing_city),
										   "billingprovince"=>utfEncode($obj->billing_state_province),
										   "billingzipcode"=>utfEncode($obj->billing_zip_code),
										   "billingcountry"=>utfEncode($obj->billing_country),
										   "pickupstreet"=>utfEncode($obj->pickup_street_address),
										   "pickupdistrict"=>utfEncode($obj->pickup_district),
										   "pickupcity"=>utfEncode($obj->pickup_city),
										   "pickupprovince"=>utfEncode($obj->pickup_state_province),
										   "pickupzipcode"=>utfEncode($obj->pickup_zip_code),
										   "pickupcountry"=>utfEncode($obj->pickup_country),
										   "inactiveflag"=>utfEncode($obj->inactive_flag),
										   "paymodeid"=>utfEncode($obj->pay_mode_id),
										   "paymode"=>utfEncode($obj->paymode),
										   "businessstyle"=>utfEncode($obj->business_style),
										   "creditlimit"=>utfEncode($obj->credit_limit),
										   "creditterm"=>utfEncode($obj->credit_term),
										   "podinstruction"=>utfEncode($obj->pod_instruction),
										   "tin"=>utfEncode($obj->tin),
										   "lineofbusiness"=>utfEncode($obj->line_of_business),
										   "billingcutoff"=>utfEncode($obj->billing_cut_off),
										   "collectioncontactperson"=>utfEncode($obj->collection_contact_person),
										   "collectionday"=>utfEncode($obj->collection_day),
										   "collectionlocation"=>utfEncode($obj->collection_location),
										   "returndocumentfee"=>utfEncode($obj->return_document_fee),
										   "waybillfee"=>utfEncode($obj->waybill_fee),
										   "securityfee"=>utfEncode($obj->security_fee),
										   "docstampfee"=>utfEncode($obj->doc_stamp_fee),
										   "creditlimitaccess"=>$creditlimitaccess,
										   "viewcreditinfoaccess"=>$viewcreditinfoaccess,
										   "response"=>'success'

										  
										   );
				}
				print_r(json_encode($dataarray));
				
			}
			else{
				$dataarray = array(
										  
										   "response"=>'invalidID'

										  
								  );
				
				print_r(json_encode($dataarray));
			}




		


		}
	}

	if(isset($_POST['ShipperContactGetInfo'])){
		if($_POST['ShipperContactGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select * from shipper_contact where shipper_id='$id'");
				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "contact"=>utfEncode($obj->contact_name),
										   "phone"=>utfEncode($obj->phone_number),
										   "email"=>utfEncode($obj->email_address),
										   "mobile"=>utfEncode($obj->mobile_number),
										   "defaultflag"=>utfEncode($obj->default_flag),
										   "sendsmsflag"=>utfEncode($obj->send_sms_flag),
										   "sendemailflag"=>utfEncode($obj->send_email_flag),
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}

	if(isset($_POST['ShipperRateGetInfo'])){
		if($_POST['ShipperRateGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select shipper_rate.id,
				                    shipper_rate.shipper_id,
				                    shipper_rate.shipper_rate_code,
				                    shipper_rate.origin_id,
				                    shipper_rate.destination_id,
				                    shipper_rate.mode_of_transport_id,
				                    shipper_rate.freight_computation,
				                    shipper_rate.fixed_rate_flag,
				                    shipper_rate.valuation,
				                    shipper_rate.freight_rate,
				                    shipper_rate.insurance_rate,
				                    shipper_rate.fuel_rate,
				                    shipper_rate.bunker_rate,
				                    shipper_rate.minimum_rate,
				                    origintbl.description as origin,
				                    origintbl.description as destination,
				                    mode_of_transport.description as mode_of_transport
					         from shipper_rate
					         left join origin_destination_port as origintbl on origintbl.id=shipper_rate.origin_id
					         left join origin_destination_port as destinationtbl on destinationtbl.id=shipper_rate.destination_id
					         left join mode_of_transport on mode_of_transport.id=shipper_rate.mode_of_transport_id
					         where shipper_rate.shipper_id='$id'");

				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "fixedrateflag"=>utfEncode($obj->fixed_rate_flag),
										   "originid"=>utfEncode($obj->origin_id),
										   "origin"=>utfEncode($obj->origin),
										   "destinationid"=>utfEncode($obj->destination_id),
										   "destination"=>utfEncode($obj->destination),
										   "modeoftransportid"=>utfEncode($obj->mode_of_transport_id),
										   "modeoftransport"=>utfEncode($obj->mode_of_transport),
										   "freightcomputation"=>utfEncode($obj->freight_computation),
										   "fixedrateflag"=>utfEncode($obj->fixed_rate_flag),
										   "valuation"=>utfEncode($obj->valuation),
										   "freightrate"=>utfEncode($obj->freight_rate),
										   "insurancerate"=>utfEncode($obj->insurance_rate),
										   "fuelrate"=>utfEncode($obj->fuel_rate),
										   "bunkerrate"=>utfEncode($obj->bunker_rate),
										   "minimumrate"=>utfEncode($obj->minimum_rate),
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}



	if(isset($_POST['ShipperPickupAddressGetInfo'])){
		if($_POST['ShipperPickupAddressGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$id = escapeString($_POST['id']);
				$rs = query("select shipper_pickup_address.id,
									shipper_pickup_address.shipper_id,
									shipper_pickup_address.default_flag,
									shipper_pickup_address.pickup_street_address,
									shipper_pickup_address.pickup_district,
									shipper_pickup_address.pickup_city,
									shipper_pickup_address.pickup_state_province,
									shipper_pickup_address.pickup_zip_code,
									shipper_pickup_address.pickup_country
					         from shipper_pickup_address
					         where shipper_pickup_address.shipper_id='$id'");

				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "defaultpickupaddressflag"=>utfEncode($obj->default_flag),
										   "pickupstreet"=>utfEncode($obj->pickup_street_address),
										   "pickupdistrict"=>utfEncode($obj->pickup_district),
										   "pickupcity"=>utfEncode($obj->pickup_city),
										   "pickupprovince"=>utfEncode($obj->pickup_state_province),
										   "pickupzipcode"=>utfEncode($obj->pickup_zip_code),
										   "pickupcountry"=>utfEncode($obj->pickup_country),
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}

	/************************************** ATTACHMENTS SCRIPTS ****************************************/
	if(isset($_POST['submitShipperAttachments'])){
		if($_POST['submitShipperAttachments']=='f$bpom@soalns3o#2$I!Hk3so3!njsk'){

				$shipperid = escapeString($_POST['shipperid']);
				$haspermission = hasAccess(USERID,'.addshipperattachmentbtn');

				if($haspermission!=1){
					echo "invalidaccess";
				}
				else{
					echo "success";
				}

			

			

			
		}
	}



	if(isset($_POST['deleteFileAttachments'])){
		if($_POST['deleteFileAttachments']=='f$bpom@soalns3o#2$I!Hk3so3!njsk'){

					$shipperid = escapeString($_POST['shipperid']);
					@$filestobedeleted = $_POST['deletefiles'];
					$deletefilescount = count($filestobedeleted);
					$shipperaccount = getInfo('shipper','account_number',"where id='".$shipperid."'");
					$now = date('Y-m-d H:i:s');

			

					$haspermission = hasAccess(USERID,'.deleteshipperattachmentbtn');


					if($haspermission!=1){
						echo "noaccess";
					}
					else{	
							$systemlog = new system_log();
							$response = 0;
							for($i=0;$i<$deletefilescount;$i++){
								$filetodelete = escapeString($filestobedeleted[$i]);
								$query = "delete from shipper_attachments 
								          where shipper_id='$shipperid' and 
								                system_filename='$filetodelete'";
								$rs = query($query);
								if($rs){
									unlink("../attachments/".$filetodelete);
									$systemlog->logInfo('SHIPPER',"Delete Attachment","Shipper Account No.: $shipperaccount | Attachment: $filetodelete",$userid,$now);

								}
								else{
									$response = 1;
									echo "Error exists $filetodelete: ".mysql_error();
								}
								
							}
							if($response == 0){
								echo "success";
							}
					}
			


		}
	}


	if(isset($_FILES['file'])){
				$response = 'ok';
				$file = $_FILES['file'];
				$length = count($file['name']);

				$shipperid = escapeString($_POST['shipperid']);
				$now = date('Y-m-d H:i:s');
				$shipperaccount = getInfo('shipper','account_number',"where id='".$shipperid."'");

				$x = 1;
				$flag = true;
				$systemlog = new system_log();

				for($i=0;$i<$length;$i++){

					$tmp = $file['tmp_name'][$i];
					$originalfilename = $file['name'][$i];
					$filename = $file['name'][$i];
					$filedesc = escapeString($_POST['filedescription'][$i]);

					//check if filename exist
					$fn = substr($filename, 0, strpos($filename, '.'));
					$ftype = substr($filename, strpos($filename, '.'));
					if(file_exists('../attachments/'.$filename)==1){
						while($flag==true){
							if(file_exists('../attachments/'.$fn.'('.$x.')'.$ftype)==1){
								$x++;
							}
							else{
								$flag = false;
								$filename = $fn.'('.$x.')'.$ftype;
								$x=1;
							}
						}
					}
					//
					move_uploaded_file($_FILES['file']['tmp_name'][$i],'../attachments/'.$filename);
					$rs = query("insert into shipper_attachments(
																	  shipper_id,
																	  filename,
																	  system_filename,
																	  description,
																	  created_date,
																	  created_by
																	)
															values(
																	'$shipperid',
																	'$originalfilename',
																	'$filename',
																	'$filedesc',
																	'$now',
																	'".USERID."'
																   )");


					if(!$rs){
						echo mysql_error();
					}
					else{
						$systemlog->logInfo('SHIPPER',"Add Attachment","Shipper Account No.: $shipperaccount | Attachment: $filename",USERID,$now);
					}
				
				}
				

		
	}





	/********************** ADD SHIPPER RATE ***********************/
	if(isset($_POST['AddEditShipperRate'])){
		if($_POST['AddEditShipperRate']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$shipperid = escapeString($_POST['shipperid']);
			$origin = escapeString($_POST['origin']);
			$destination = escapeString($_POST['destination']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$services = escapeString($_POST['services']);
			$freightcomputation = escapeString($_POST['freightcomputation']);
			$fixedrateflag = escapeString($_POST['fixedrateflag']);
			$fixedrateflag = $fixedrateflag=='true'?1:0;
			$valuation = escapeString($_POST['valuation']);
			$freightrate = escapeString($_POST['freightrate']);
			$insurancerate = escapeString($_POST['insurancerate']);
			$fuelrate = escapeString($_POST['fuelrate']);
			$bunkerrate = escapeString($_POST['bunkerrate']);
			$minimumrate = escapeString($_POST['minimumrate']);
			$rushflag = escapeString($_POST['rushflag']);
			$rushflag = $rushflag=='true'?1:0;
			$pulloutflag = escapeString($_POST['pulloutflag']);
			$pulloutflag = $pulloutflag=='true'?1:0;
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$shiprateclass = new shipper_rate();
			$systemlog = new system_log();

			$type = escapeString($_POST['type']);
			$expresstransactiontype = 'NULL';
			$pouchsize = escapeString($_POST['pouchsize']);
			$pouchsize = $pouchsize>0?$pouchsize:'NULL';
			$fixedrateamount = escapeString($_POST['fixedrateamount']);
			$pulloutfee = escapeString($_POST['pulloutfee']);
			$odarate = escapeString($_POST['odarate']);

			$cbmcomputation = escapeString($_POST['cbmcomputation']);
			$freightchargecomputation = escapeString($_POST['freightchargecomputation']);
			$returndocumentfee = escapeString($_POST['returndocumentfee']);
			$waybillfee = escapeString($_POST['waybillfee']);
			$securityfee = escapeString($_POST['securityfee']);
			$docstampfee = escapeString($_POST['docstampfee']);
			$collectionpercentage = escapeString($_POST['collectionpercentage']);
			$advaloremflag = 0;


			$parceltype = 'NULL';//escapeString($_POST['parceltype']);


			//$checkhasexpressmotflag = true;

			if($type=='DOCUMENT'){
				/*$checkhasexpressmotflag = false;
				$checkhasexpressmotrs = query("select * from mode_of_transport where description='EXPRESS'");
				while($obj=fetch($checkhasexpressmotrs)){
					$checkhasexpressmotflag = true;
					$modeoftransport = $obj->id;
				}*/ 
				$parceltype = 'NULL';
				$modeoftransport = 'NULL';
				$services = 'NULL';
				$freightcomputation = 'NULL';
				$pulloutflag = 0;
				$pulloutfee = 0;
				$fixedrateflag = 0;
				$fixedrateamount = 0;
				$fuelrate = 0;
				$bunkerrate = 0;
				$minimumrate = 0;
				$insurancerate = 0;
				$excessamount = 0;

				$cbmcomputation = 'NULL';
				$insuranceratecomputation = 'NULL';
				$freightchargecomputation = 'NULL';
				$collectionpercentage = 0;

				$expresstransactiontype = escapeString($_POST['expresstransactiontype']);
				$advaloremflag = escapeString($_POST['advaloremflag']);
				$advaloremflag = $advaloremflag=='true'?1:0;
			}
			else{
				$insuranceratecomputation = escapeString($_POST['insuranceratecomputation']);
				if($insuranceratecomputation==2){
					$excessamount = escapeString($_POST['excessamount']);
					$excessamount = round($excessamount,4);
				}
				else{
					$excessamount = 0;
				}
			}

			

			if($pouchsize==''||$pouchsize=='NULL'){
				$pouchsizecondition = " and pouch_size_id is null";
			}
			else{
				$pouchsizecondition = " and pouch_size_id='$pouchsize'";
			}

			if($type=='PARCEL'){
				if($source=='edit'){
					$shipperrateid = escapeString($_POST['shipperrateid']);
					$query = "select * from shipper_rate where shipper_id='$shipperid' and origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' and id!='$shipperrateid' and rush_flag='$rushflag' and pull_out_flag='$pulloutflag' and waybill_type='$type' and services_id='$services'"; // and parcel_type_id='$parceltype'
					//and fixed_rate_flag='$fixedrateflag'
				}
				else{
					$query = "select * from shipper_rate where shipper_id='$shipperid' and origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' and rush_flag='$rushflag' and pull_out_flag='$pulloutflag' and waybill_type='$type' and services_id='$services'";//and parcel_type_id='$parceltype'
				}
			}
			else{
				if($source=='edit'){
					$id = escapeString($_POST['id']);
					$query = "select * from shipper_rate where origin_id='$origin' and destination_id='$destination' and id!='$id' and rush_flag='$rushflag' and waybill_type='$type' and express_transaction_type='$expresstransactiontype' $pouchsizecondition";			
				}
				else{
					$query = "select * from shipper_rate where origin_id='$origin' and destination_id='$destination' and rush_flag='$rushflag' and waybill_type='$type' and express_transaction_type='$expresstransactiontype' $pouchsizecondition";
				}
			}


			


			
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$shiprateclass->insert(array('',$shipperid,'NULL',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$type,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,$expresstransactiontype,$advaloremflag,$insuranceratecomputation,$excessamount,$parceltype,$cbmcomputation));
					$id = $shiprateclass->getInsertId();
					$systemlog->logAddedInfo($shiprateclass,array($id,$shipperid,'NULL',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$now,$userid,'NULL','NULL',$rushflag,$pulloutflag,$type,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,$expresstransactiontype,$advaloremflag,$insuranceratecomputation,$excessamount,$parceltype,$cbmcomputation),'SHIPPER RATE','New Shipper Rate Added',$userid,$now);

					
				}
				else if($source=='edit'){
						$id = escapeString($_POST['shipperrateid']);
						$systemlog->logEditedInfo($shiprateclass,$id,array('',$shipperid,'NULL',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$type,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,$expresstransactiontype,$advaloremflag,$insuranceratecomputation,$excessamount,$parceltype,$cbmcomputation),'SHIPPER RATE','Edited Shipper Rate Info',$userid,$now);/// log should be before update is made
						$shiprateclass->update($id,array($shipperid,'NULL',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$now,$userid,$rushflag,$pulloutflag,$type,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$returndocumentfee,$waybillfee,$securityfee,$docstampfee,$freightchargecomputation,$collectionpercentage,$expresstransactiontype,$advaloremflag,$insuranceratecomputation,$excessamount,$parceltype,$cbmcomputation));


						
						

				}

				if(strtoupper($freightcomputation)!='CBM'&&strtoupper($freightcomputation)!='ACTUAL WEIGHT'&&strtoupper($freightcomputation)!='VOLUMETRIC'&&strtoupper($freightcomputation)!='DEFAULT'){
					query("delete from shipper_rate_freight_charge where shipper_rate_id='$id'");
				}

				echo "success";
			}
			else{
				echo "rateexists";
			}
		}
			
	}


	if(isset($_POST['deleteShipperRates'])){
        if($_POST['deleteShipperRates']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = escapeString($data[$i]);

	        	$rs = query("delete from shipper_rate where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



    if(isset($_POST['getShipperRateDetails'])){
		if($_POST['getShipperRateDetails']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){


			$id = escapeString($_POST['id']);

			       $rs = query("select shipper_rate.id,
			       	                   shipper_rate.origin_id,
			       	                   shipper_rate.destination_id,
			       	                   shipper_rate.mode_of_transport_id,
			       	                   shipper_rate.freight_computation,
			       	                   shipper_rate.fixed_rate_flag,
			       	                   shipper_rate.valuation,
			       	                   shipper_rate.freight_rate,
			       	                   shipper_rate.insurance_rate,
			       	                   shipper_rate.fuel_rate,
			       	                   shipper_rate.bunker_rate,
			       	                   shipper_rate.minimum_rate,
			       	                   shipper_rate.pull_out_flag,
			       	                   shipper_rate.rush_flag,
			       	                   shipper_rate.ad_valorem_flag,
			       	                   shipper_rate.waybill_type,
			       	                   shipper_rate.pouch_size_id,
			       	                   pouch_size.description as pouchsize,
			       	                   shipper_rate.services_id,
			       	                   services.description as services,
			       	                   shipper_rate.fixed_rate_amount,
			       	                   shipper_rate.pull_out_fee,
			       	                   shipper_rate.oda_rate,
			       	                   shipper_rate.return_document_fee,
			       	                   shipper_rate.waybill_fee,
			       	                   shipper_rate.security_fee,
			       	                   shipper_rate.doc_stamp_fee,
			       	                   shipper_rate.excess_amount,
			       	                   shipper_rate.parcel_type_id,
			       	                   shipper_rate.freight_charge_computation,
			       	                   shipper_rate.insurance_rate_computation,
			       	                   insurance_rate_computation.description as insuranceratecomputation,
			       	                   freight_charge_computation.description as freightchargecomputation,
			       	                   shipper_rate.collection_fee_percentage,
			       	                   shipper_rate.express_transaction_type,
			       	                   origintbl.description as origin,
			       	                   destinationtbl.description as destination,
			       	                   mode_of_transport.description as modeoftransport,
			       	                   parcel_type.description as parceltype,
			       	                   shipper_rate.cbm_computation,
			       	                   cbm_computation.description as cbmcomputation
			       	            from shipper_rate
			       	            left join origin_destination_port as origintbl on origintbl.id=shipper_rate.origin_id
			       	            left join origin_destination_port as destinationtbl on destinationtbl.id=shipper_rate.destination_id
			       	            left join mode_of_transport on mode_of_transport.id=shipper_rate.mode_of_transport_id
			       	            left join freight_charge_computation on freight_charge_computation.id=shipper_rate.freight_charge_computation
			       	            left join insurance_rate_computation on insurance_rate_computation.id=shipper_rate.insurance_rate_computation
			       	            left join pouch_size on pouch_size.id=shipper_rate.pouch_size_id
			       	            left join services on services.id=shipper_rate.services_id
			       	            left join parcel_type on parcel_type.id=shipper_rate.parcel_type_id
			       	            left join cbm_computation on cbm_computation.id=shipper_rate.cbm_computation
								where shipper_rate.id='$id'
				 	    ");

			if(getNumRows($rs)==1){
				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>utfEncode($obj->id),
										   "originid"=>utfEncode($obj->origin_id),
										   "origin"=>utfEncode($obj->origin),
										   "destinationid"=>utfEncode($obj->destination_id),
										   "destination"=>utfEncode($obj->destination),
										   "modeoftransportid"=>utfEncode($obj->mode_of_transport_id),
										   "modeoftransport"=>utfEncode($obj->modeoftransport),
										   "freightcomputation"=>utfEncode($obj->freight_computation),
										   "fixedrateflag"=>utfEncode($obj->fixed_rate_flag),
										   "rushflag"=>utfEncode($obj->rush_flag),
										   "pulloutflag"=>utfEncode($obj->pull_out_flag),
										   "advaloremflag"=>utfEncode($obj->ad_valorem_flag),
										   "valuation"=>utfEncode($obj->valuation),
										   "freightrate"=>utfEncode($obj->freight_rate),
										   "insurancerate"=>utfEncode($obj->insurance_rate),
										   "fuelrate"=>utfEncode($obj->fuel_rate),
										   "bunkerrate"=>utfEncode($obj->bunker_rate),
										   "minimumrate"=>utfEncode($obj->minimum_rate),
										   "type"=>utfEncode($obj->waybill_type),
										   "pouchsizeid"=>utfEncode($obj->pouch_size_id),
										   "pouchsize"=>utfEncode($obj->pouchsize),
										   "servicesid"=>utfEncode($obj->services_id),
										   "services"=>utfEncode($obj->services),
										   "fixedrateamount"=>utfEncode($obj->fixed_rate_amount),
										   "pulloutfee"=>utfEncode($obj->pull_out_fee),
										   "odarate"=>utfEncode($obj->oda_rate),
										   "freightchargecomputationid"=>utfEncode($obj->freight_charge_computation),
										   "freightchargecomputation"=>utfEncode($obj->freightchargecomputation),
										   "cbmcomputationid"=>utfEncode($obj->cbm_computation),
										   "cbmcomputation"=>utfEncode($obj->cbmcomputation),
										   "returndocumentfee"=>utfEncode($obj->return_document_fee),
										   "waybillfee"=>utfEncode($obj->waybill_fee),
										   "securityfee"=>utfEncode($obj->security_fee),
										   "docstampfee"=>utfEncode($obj->doc_stamp_fee),
										   "collectionpercentage"=>utfEncode($obj->collection_fee_percentage),
										   "expresstransactiontype"=>utfEncode($obj->express_transaction_type),
										   "insuranceratecomputationid"=>utfEncode($obj->insurance_rate_computation),
										   "insuranceratecomputation"=>utfEncode($obj->insuranceratecomputation),
										   "excessamount"=>utfEncode($obj->excess_amount),
										   "parceltypeid"=>utfEncode($obj->parcel_type_id),
										   "parceltype"=>utfEncode($obj->parceltype),
										   "response"=>'success'

										  
										   );
				}
				print_r(json_encode($dataarray));
				
			}
			else{
				$dataarray = array(
										  
										   "response"=>'invalidshipperrateid'

										  
								  );
				
				print_r(json_encode($dataarray));
			}

		}
	}




	/***************** SHIPPER RATE HANDLING INSTRUCTION ***************/
	if(isset($_POST['AddEditShipperRateHandlingInstruction'])){
		if($_POST['AddEditShipperRateHandlingInstruction']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = 'add';
			$shipperrateid = escapeString($_POST['shipperrateid']);
			$handlinginstruction = escapeString($_POST['handlinginstruction']);
			$type = escapeString($_POST['type']);
			$percentage = escapeString($_POST['percentage']);
			$fixedcharge = escapeString($_POST['fixedcharge']);

			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$shipratehiclass = new shipper_rate_handling_instruction();
			$systemlog = new system_log();
			$shipperratehiID = '';

			$checkifexist = query("select * from shipper_rate_handling_instruction where shipper_rate_id='$shipperrateid' and handling_instruction_id='$handlinginstruction'");

			if(getNumRows($checkifexist)>0){
				$source = 'edit';
				while($obj=fetch($checkifexist)){
					$shipperratehiID = $obj->id;
				}

			}
			else{
			    $source = 'add';
			}

			//echo $shipperrateid.'<--';
			
				if($source=='add'){

					$shipratehiclass->insert(array('',$shipperrateid,$handlinginstruction,$type,$percentage,$fixedcharge,$now,$userid,'NULL','NULL'));
					$id = $shipratehiclass->getInsertId();
					$systemlog->logAddedInfo($shipratehiclass,array($id,$shipperrateid,$handlinginstruction,$type,$percentage,$fixedcharge,$now,$userid,'NULL','NULL'),'SHIPPER RATE - HANDLING INSTRUCTION','New Shipper Rate - Handling Instruction',$userid,$now);

					echo "success";
				}
				else if($source=='edit'){

				
					
						$systemlog->logEditedInfo($shipratehiclass,$shipperratehiID,array($shipperratehiID,$shipperrateid,$handlinginstruction,$type,$percentage,$fixedcharge,'NOCHANGE','NOCHANGE',$now,$userid),'SHIPPER RATE - HANDLING INSTRUCTION','Edited Shipper Rate - Handling Instruction',$userid,$now);/// log should be before update is made
						$shipratehiclass->update($shipperratehiID,array($shipperrateid,$handlinginstruction,$type,$percentage,$fixedcharge,'NOCHANGE','NOCHANGE',$now,$userid));

						
						
						echo "success";

				}
			
		}
			
	}



	



    /***************** SHIPPER RATE FREIGHT CHARGE ***************/
	if(isset($_POST['AddEditShipperRateFreightCharge'])){
		if($_POST['AddEditShipperRateFreightCharge']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = 'add';
			$shipperrateid = escapeString($_POST['shipperrateid']);
			$fromkg = escapeString($_POST['fromkg']);
			$tokg = escapeString($_POST['tokg']);
			$freightcharge = escapeString($_POST['freightcharge']);
			$excessweightcharge = escapeString($_POST['excessweightcharge']);

			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$shipratefcclass = new shipper_rate_freight_charge();
			$systemlog = new system_log();
			

			$source = escapeString($_POST['source']);

			//echo $shipperrateid.'<--';
			
				if($source=='add'){

					$shipratefcclass->insert(array('',$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'));
					$id = $shipratefcclass->getInsertId();
					$systemlog->logAddedInfo($shipratefcclass,array($id,$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'),'SHIPPER RATE - FREIGHT CHARGE','New Shipper Rate - Freight Charge',$userid,$now);

					echo "success";
				}
				else if($source=='edit'){
						$freightchargeID = escapeString($_POST['freightchargeID']);
		
						$systemlog->logEditedInfo($shipratefcclass,$freightchargeID,array($freightchargeID,$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid),'SHIPPER RATE - FREIGHT CHARGE','Edited Shipper Rate - Freight Charge',$userid,$now);/// log should be before update is made
						$shipratefcclass->update($freightchargeID,array('NOCHANGE',$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid));
						echo "success";

				}
			
		}
			
	}



	

    if(isset($_POST['deleteShipperRateHandlingInstruction'])){
        if($_POST['deleteShipperRateHandlingInstruction']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = escapeString($data[$i]);

	        	$rs = query("delete from shipper_rate_handling_instruction where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['deleteShipperRateFreightCharge'])){
        if($_POST['deleteShipperRateFreightCharge']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = escapeString($data[$i]);

	        	$rs = query("delete from shipper_rate_freight_charge where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
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



	if(isset($_POST['addShipperCSEmailAddresses'])){
		if($_POST['addShipperCSEmailAddresses']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$shipperid = isset($_POST['shipperid'])?escapeString($_POST['shipperid']):'';
			$email = isset($_POST['email'])?escapeString($_POST['email']):'';

			$checkshipperexistrs = query("select * from shipper where id='$shipperid'");

			if(trim($email)==''){
				$response = array(
										   "response"=>'noemailprovided'
									);
			}
			else{

				if(getNumRows($checkshipperexistrs)==1){

					$checkshipperemailexistrs = query("select * from shipper_cs_email_addresses where shipper_id='$shipperid' and email='$email'");


					if(getNumRows($checkshipperemailexistrs)>0){
						$response = array(
										   "response"=>'emailexists'
									);
					}
					else{
						$rs = query("insert into shipper_cs_email_addresses(shipper_id,email) values('$shipperid','$email')");

						if($rs){
							$response = array(
										   "response"=>'success'
									);
						}
						else{
							$response = array(
										   "response"=>mysql_error()
									);
						}
					}
				}
				else{
					$response = array(
										   "response"=>'invalidshipperid'
									);

				}
			}

			print_r(json_encode($response));

		}
	}

	if(isset($_POST['deleteSelectedCSEmailAddrRows'])){
        if($_POST['deleteSelectedCSEmailAddrRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = escapeString($data[$i]);

	        	$rs = query("delete from shipper_cs_email_addresses where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }


?>