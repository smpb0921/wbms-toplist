<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    //include("../classes/shipper-pickup-address.class.php");
    include("../classes/waybill.class.php");
    include("../classes/waybill-other-charges.class.php");
    include("../classes/waybill-package-dimension.class.php");
    include("../classes/waybill-status-history.class.php");
    include("../classes/waybill-status-history-deleted.class.php");
    include("../classes/waybill-handling-instruction.class.php");
    include("../classes/waybill-document.class.php");
    include("../classes/consignee.class.php");
    include("../classes/consignee-contact.class.php");
    include("../classes/waybill-print-history.class.php");
    include("../classes/system-log.class.php");////////
    include("../classes/waybill-billing-history.class.php");////////
	 
	if(isset($_POST['SaveWaybillTransaction'])){
		if($_POST['SaveWaybillTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){
			$parceltype = 'NULL';//escapeString($_POST['parceltype']);
			$waybillstatus = escapeString(strtoupper($_POST['waybillstatus']));
			$tpl = escapeString(strtoupper($_POST['tpl']));
			$source = escapeString($_POST['source']);
			$waybillnumber = escapeString(strtoupper(trim($_POST['waybillnumber'])));
			$bookingnumber = escapeString(strtoupper(trim($_POST['bookingnumber'])));
			$bookingnumber = $bookingnumber==''?'NULL':$bookingnumber;
			$origin = escapeString(strtoupper(trim($_POST['origin'])));
			$destination = escapeString($_POST['destination']);
			$destinationroute = escapeString($_POST['destinationroute']);
			$remarks = escapeString($_POST['remarks']);
			$mawbl = escapeString(strtoupper($_POST['mawbl']));
			$onholdflag = escapeString($_POST['onholdflag']);
			$onholdflag =strtoupper($onholdflag)=='TRUE'?1:0;
			$onholdremarks = escapeString($_POST['onholdremarks']);
			$pickupdate = trim($_POST['pickupdate'])==''?'NULL':dateString($_POST['pickupdate']);
			$documentdate = trim($_POST['documentdate'])==''?'NULL':dateString($_POST['documentdate']);
			$documentdate = $pickupdate;
			$deliverydate = trim($_POST['deliverydate'])==''?'NULL':dateString($_POST['deliverydate']);
			$shipperid = escapeString($_POST['shipperid']);
			$shipperaccountnumber = escapeString($_POST['shipperaccountnumber']);
			$shipperaccountname = str_replace('\\',"",escapeString($_POST['shipperaccountname']));
			$shipperaccountname = addslashes($shipperaccountname);
			$shippertel = escapeString($_POST['shippertel']);
			$shippercontact = escapeString($_POST['shippercontact'])==''?'NULL':escapeString($_POST['shippercontact']);
			$shippercompanyname = str_replace('\\',"",escapeString($_POST['shippercompanyname']));
			$shippercompanyname = addslashes($shippercompanyname);
			$shipperpodinstruction = escapeString($_POST['shipperpodinstruction']);
			$shipperstreet = escapeString($_POST['shipperstreet']);
			$shipperdistrict =  escapeString($_POST['shipperdistrict'])==''?'NULL':escapeString($_POST['shipperdistrict']);
			$shippercity = escapeString($_POST['shippercity'])==''?'NULL':escapeString($_POST['shippercity']);
			$shipperprovince = escapeString($_POST['shipperprovince'])==''?'NULL':escapeString($_POST['shipperprovince']);
			$shipperzipcode = escapeString($_POST['shipperzipcode'])==''?'NULL':escapeString($_POST['shipperzipcode']);
			$shippercountry = escapeString($_POST['shippercountry'])==''?'NULL':escapeString($_POST['shippercountry']);
			$pickupstreet = trim($_POST['pickupstreet'])==''||trim(strtoupper($_POST['pickupstreet']))=='NULL'?'NULL':escapeString($_POST['pickupstreet']);
			$pickupdistrict = trim($_POST['pickupdistrict'])==''||trim(strtoupper($_POST['pickupdistrict']))=='NULL'?'NULL':escapeString($_POST['pickupdistrict']);
			$pickupcity = trim($_POST['pickupcity'])==''||trim(strtoupper($_POST['pickupcity']))=='NULL'?'NULL':escapeString($_POST['pickupcity']);
			$pickupprovince = trim($_POST['pickupprovince'])==''||trim(strtoupper($_POST['pickupprovince']))=='NULL'?'NULL':escapeString($_POST['pickupprovince']);
			$pickupzipcode = trim($_POST['pickupzipcode'])==''||trim(strtoupper($_POST['pickupzipcode']))=='NULL'?'NULL':escapeString($_POST['pickupzipcode']);
			$pickupcountry = trim($_POST['pickupcountry'])==''||trim(strtoupper($_POST['pickupcountry']))=='NULL'?'NULL':escapeString($_POST['pickupcountry']);
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
			$actualweight = $actualweight==''?'NULL':$actualweight;
			$vwcbm = escapeString($_POST['vwcbm']);
			$vwcbm = $vwcbm==''?'NULL':$vwcbm;
			$vw = escapeString($_POST['vw']);
			$vw = $vw==''?'NULL':$vw;
			$chargeableweight = escapeString($_POST['chargeableweight']);
			$freight = escapeString($_POST['freight']);
			$valuation = escapeString($_POST['valuation']);
			$vat = escapeString($_POST['vat']);
			$totalamount = escapeString($_POST['totalamount']);
			$amountforcollection = escapeString($_POST['amountforcollection']);
			$amountforcollection = round($amountforcollection,4);
			$zeroratedflag = escapeString($_POST['zeroratedflag']);
			$zeroratedflag =strtoupper($zeroratedflag)=='TRUE'?1:0;
			$carrier = escapeString($_POST['carrier']);
			$carrier = trim($carrier)==''?'NULL':$carrier;
			$shipperrepname = escapeString($_POST['shipperrepname']);
			$services = escapeString($_POST['services']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$deliveryinstruction = escapeString($_POST['deliveryinstruction']);
			$accompanyingdocument = 'NULL';//escapeString($_POST['accompanyingdocument']);
			$transportcharges = escapeString($_POST['transportcharges']);
			$transportcharges = trim($transportcharges=='')?'NULL':$transportcharges;
			$paymode = escapeString($_POST['paymode']);
			$costcenter = escapeString($_POST['costcenter']);
			$shipmentdescription = escapeString($_POST['shipmentdescription']);
			$secondaryrecipient = $_POST['secondaryrecipient'];

			$waybilltype = escapeString($_POST['waybilltype']);
			$rushflag = escapeString($_POST['rushflag']);
			$pulloutflag = escapeString($_POST['pulloutflag']);
			$odaflag = escapeString($_POST['odaflag']);


			@$handlinginstructionarray = $_POST['handlinginstruction'];
			@$documentarray = $_POST['accompanyingdocument'];

			$returndocumentfee = escapeString(round($_POST['returndocumentfee'],4));
			$waybillfee = escapeString(round($_POST['waybillfee'],4));
			$securityfee = escapeString(round($_POST['securityfee'],4));
			$docstampfee = escapeString(round($_POST['docstampfee'],4));

			$freightcomputation = escapeString($_POST['freightcomputation']);
			$insurancerate = escapeString($_POST['insurancerate']);
			$fuelrate = escapeString($_POST['fuelrate']);
			$bunkerrate = escapeString($_POST['bunkerrate']);
			$minimumrate = escapeString($_POST['minimumrate']);
			$subtotal = escapeString($_POST['subtotal']);
			$reference = escapeString($_POST['reference']);

			$baseoda = escapeString($_POST['baseoda']);
			$shipperoda = escapeString($_POST['shipperoda']);
			$oda = escapeString($_POST['oda']);
			$pulloutfee = escapeString($_POST['pulloutfee']);
			$fixedrateamount = escapeString($_POST['fixedrateamount']);
			$totalhandlingcharges = escapeString($_POST['totalhandlingcharges']);

			$totalregularcharges = escapeString($_POST['totalregularcharges']);
			$totalotherchargesvatable = escapeString($_POST['totalotherchargesvatable']);
			$totalotherchargesnonvatable = escapeString($_POST['totalotherchargesnonvatable']);

			$freightcost = trim($_POST['freightcost'])>=0?escapeString($_POST['freightcost']):0;

			$agent = escapeString($_POST['agent'])==''?'NULL':escapeString($_POST['agent']);
			$brand = escapeString($_POST['brand'])==''?'NULL':escapeString($_POST['brand']);
			$costcentercode = escapeString($_POST['costcentercode'])==''?'NULL':escapeString($_POST['costcentercode']);
			$costcenter = escapeString($_POST['costcenter'])==''?'NULL':escapeString($_POST['costcenter']);
			$buyercode = escapeString($_POST['buyercode'])==''?'NULL':escapeString($_POST['buyercode']);
			$contractnumber = escapeString($_POST['contractnumber'])==''?'NULL':escapeString($_POST['contractnumber']);
			$customernumber = escapeString($_POST['customernumber'])==''?'NULL':escapeString($_POST['customernumber']);
			$project = escapeString($_POST['project'])==''?'NULL':escapeString($_POST['project']);
			$parkingslot = escapeString($_POST['parkingslot'])==''?'NULL':escapeString($_POST['parkingslot']);
			$blockunitdistrict = escapeString($_POST['blockunitdistrict'])==''?'NULL':escapeString($_POST['blockunitdistrict']);
			$lotfloor = escapeString($_POST['lotfloor'])==''?'NULL':escapeString($_POST['lotfloor']);


			$expresstransactiontype = 'NULL';
			$pouchsize = 'NULL';
			$pouchsize = escapeString($_POST['pouchsize']);
			$pouchsize = $pouchsize==''?'NULL':$pouchsize;

			//CHECK IF EXPRESS IN MAINTENANCE 
			$motexpressid = '';
			$srvexpressid = '';
			$rs = query("select * from mode_of_transport where description='EXPRESS'");
			while($obj=fetch($rs)){
				$motexpressid = $obj->id;
			}
			$rs = query("select * from services where description='EXPRESS'");
			while($obj=fetch($rs)){
				$srvexpressid = $obj->id;
			}
			//CHECK IF EXPRESS IN MAINTENANCE - END


			$maxweightallowed = 0;


			//if(($srvexpressid>0&&$motexpressid>0)||$waybilltype=='PARCEL'){
				if($waybilltype=='DOCUMENT'){
					//$services = 'NULL';
					$accompanyingdocument = 'NULL';
					//$deliveryinstruction = 'NULL';
					$paymode = 'NULL';
					//$costcenter = 'NULL';
					$amountforcollection = 0;

					$expresstransactiontype = escapeString($_POST['expresstransactiontype']);
					$expresstransactiontype = $expresstransactiontype==''?'NULL':$expresstransactiontype;
					//$pouchsize = escapeString($_POST['pouchsize']);
					//$pouchsize = $pouchsize==''?'NULL':$pouchsize;

					//$services = $srvexpressid;
					//$modeoftransport = $motexpressid;

					$maxweightallowed = getInfo("pouch_size","max_weight","where id='$pouchsize'");
					$maxweightallowed = $maxweightallowed>=0?$maxweightallowed:0;
				}

				@$otherchargesiterate = count($_POST['linedesc']);
				@$linedesc = $_POST['linedesc'];
				@$lineamount = $_POST['lineamount'];
				@$linevatflag = $_POST['linevatflag'];
				@$linepackagedimlength = $_POST['linepackagedimlength'];
				@$linepackagedimwidth = $_POST['linepackagedimwidth'];
				@$linepackagedimheight = $_POST['linepackagedimheight'];
				@$linepackagedimqty = $_POST['linepackagedimqty'];
				@$linepackagedimvw = $_POST['linepackagedimvw'];
				@$linepackagedimcbm = $_POST['linepackagedimcbm'];
				@$linepackagedimuom = $_POST['linepackagedimuom'];
				@$linepackagedimactualweight = $_POST['linepackagedimactualweight'];
				@$packagedimiterate = count($_POST['linepackagedimlength']);
				$response = array();

				$userid = USERID;
				$now = date('Y-m-d H:i:s');
				$waybillclass = new txn_waybill();
				$waybillotherchargesclass = new txn_waybill_other_charges();
				$waybillpackagedimclass = new txn_waybill_package_dimension();
				$systemlog = new system_log();

				$checkifvalidwaybillrs = query("select * from waybill_booklet_issuance 
					                            where '$waybillnumber'>=booklet_start_series and
					                                  '$waybillnumber'<=booklet_end_series and
					                                  '$documentdate'<=date(validity_date) and
					                                  '$documentdate'>=date(issuance_date)");
				//if(getNumRows($checkifvalidwaybillrs)>0){
					if($maxweightallowed>=$actualweight||$maxweightallowed==0){
						$checkshipperrs = query("select * from shipper where id='$shipperid'");
						if(getNumRows($checkshipperrs)==1){

							$checkconsigneers = query("select * from consignee where id='$consigneeid'");
							if(getNumRows($checkconsigneers)==1){

								if((validateDate($pickupdate)==1&&$pickupdate!='1970-01-01')||$pickupdate=='NULL'){
									if((validateDate($deliverydate)==1&&$deliverydate!='1970-01-01')||$deliverydate=='NULL'){
										if(validateDate($documentdate)==1&&$documentdate!='1970-01-01'){

											
													if(trim($waybillnumber)==''){
														$waybillnumber = getTransactionNumber(2);
													}	

													if(trim($reference)==''){
														$reference = $waybillnumber;
													}

													if($source=='add'){
														//$waybillnumber = getTransactionNumber(2);

														$checkwaybillnumberrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");

														if(getNumRows($checkwaybillnumberrs)>0){
															$response = array(
																			"response"=>'waybillexists'
																		 );
														}
														else{


															$waybillclass->insert(
																					array(	  '',
																							  $waybillnumber,
																							  $waybillstatus,
																							  $bookingnumber,
																							  $origin,
																							  $destination,
																							  $destinationroute,
																							  $pickupdate,
																							  $onholdflag,
																							  $onholdremarks,
																							  $remarks,
																							  $now,
																							  $userid,
																							  $now,
																							  $userid,
																							  $documentdate,
																							  'NULL',
																							  'NULL',
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
																							  $pickupstreet,
																							  $pickupdistrict,
																							  $pickupcity,
																							  $pickupprovince,
																							  $pickupzipcode,
																							  $pickupcountry,
																							  $shipmentdescription,
																							  $numberofpackage,
																							  $declaredvalue,
																							  $actualweight,
																							  $vwcbm,
																							  $chargeableweight,
																							  $freight,
																							  $valuation,
																							  $services,
																							  $modeoftransport,
																							  $accompanyingdocument,
																							  $deliveryinstruction,
																							  'NULL',
																							  $transportcharges,
																							  $paymode,
																							  $vat,
																							  $zeroratedflag,
																							  $totalamount,
																							  $carrier,
																							  $shipperrepname,
																							  $freightcomputation,
																							  $insurancerate,
																							  $fuelrate,
																							  $bunkerrate,
																							  $minimumrate,
																							  $subtotal,
																							  $deliverydate,
																							  $vw,
																							  $costcenter,
																							  $shipperpodinstruction,
																							  $amountforcollection,
																							  $waybilltype,
																							  $rushflag,
																							  $pulloutflag,
																							  $pouchsize,
																							  $fixedrateamount,
																							  $pulloutfee,
																							  $baseoda,
																							  $shipperoda,
																							  $oda,
																							  $totalhandlingcharges,
																							  $returndocumentfee,
																							  $waybillfee,
																							  $securityfee,
																							  $docstampfee,
																							  $totalregularcharges,
																							  $totalotherchargesvatable,
																							  $totalotherchargesnonvatable,
																							  $expresstransactiontype,
																							  $odaflag,
																							  $parceltype,
																							  $tpl,
																							  $reference,
																							  $secondaryrecipient,
																							  $shippercontact,
																							  $brand,
																							  $costcentercode,
																							  $buyercode,
																							  $contractnumber,
																							  $customernumber,
																							  $project,
																							  $parkingslot,
																							  $blockunitdistrict,
																							  $lotfloor,
																							  $mawbl,
																							  $agent
																							 
																						 )
																				  );
															$id = $waybillclass->getInsertId();
															$systemlog->logAddedInfo($waybillclass,array($id,
																							  $waybillnumber,
																							  $waybillstatus,
																							  $bookingnumber,
																							  $origin,
																							  $destination,
																							  $destinationroute,
																							  $pickupdate,
																							  $onholdflag,
																							  $onholdremarks,
																							  $remarks,
																							  $now,
																							  $userid,
																							  $now,
																							  $userid,
																							  $documentdate,
																							  'NULL',
																							  'NULL',
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
																							  $pickupstreet,
																							  $pickupdistrict,
																							  $pickupcity,
																							  $pickupprovince,
																							  $pickupzipcode,
																							  $pickupcountry,
																							  $shipmentdescription,
																							  $numberofpackage,
																							  $declaredvalue,
																							  $actualweight,
																							  $vwcbm,
																							  $chargeableweight,
																							  $freight,
																							  $valuation,
																							  $services,
																							  $modeoftransport,
																							  $accompanyingdocument,
																							  $deliveryinstruction,
																							  'NULL',
																							  $transportcharges,
																							  $paymode,
																							  $vat,
																							  $zeroratedflag,
																							  $totalamount,
																							  $carrier,
																							  $shipperrepname,
																							  $freightcomputation,
																							  $insurancerate,
																							  $fuelrate,
																							  $bunkerrate,
																							  $minimumrate,
																							  $subtotal,
																							  $deliverydate,
																							  $vw,
																							  $costcenter,
																							  $shipperpodinstruction,
																							  $amountforcollection,
																							  $waybilltype,
																							  $rushflag,
																							  $pulloutflag,
																							  $pouchsize,
																							  $fixedrateamount,
																							  $pulloutfee,
																							  $baseoda,
																							  $shipperoda,
																							  $oda,
																							  $totalhandlingcharges,
																							  $returndocumentfee,
																							  $waybillfee,
																							  $securityfee,
																							  $docstampfee,
																							  $totalregularcharges,
																							  $totalotherchargesvatable,
																							  $totalotherchargesnonvatable,
																							  $expresstransactiontype,
																							  $odaflag,
																							  $parceltype,
																							  $tpl,
																							  $reference,
																							  $secondaryrecipient,
																							  $shippercontact,
																							  $brand,
																							  $costcentercode,
																							  $buyercode,
																							  $contractnumber,
																							  $customernumber,
																							  $project,
																							  $parkingslot,
																							  $blockunitdistrict,
																							  $lotfloor,
																							  $mawbl,
																							  $agent
																						 ),'WAYBILL','New Waybill Transaction Added',$userid,$now);
															/** WAYBILL STAT HISTORY **/
															$waybillstathistory = new txn_waybill_status_history();
															$waybillstathistory->insert(array('',$waybillnumber,$waybillstatus,$waybillstatus,'NULL',$now,$userid,'NULL','NULL','NULL','NULL','NULL','NULL'));
															/**************************/

															$response = array(
																			"response"=>'success',
																			"txnnumber"=>$waybillnumber
																		 );
														}
													}
													else if($source=='edit'){
														$id = escapeString($_POST['id']);
														//$waybillnumber = '';

														$checkwaybillnumberrs = query("select * from txn_waybill where waybill_number='$waybillnumber' and id!='$id'");

														if(getNumRows($checkwaybillnumberrs)>0){
															$response = array(
																			"response"=>'waybillexists'
																		 );
														}
														else{

															$creditlimit = 0;
															$oldtotalamount = 0;


															$checkwaybillidrs = query("select   txn_waybill.status, 
																								txn_waybill.created_by, 
																								txn_waybill.shipper_id,
																								txn_waybill.total_amount,
																								shipper.credit_limit
																						from txn_waybill 
																						left join shipper on shipper.id=txn_waybill.shipper_id
																						where txn_waybill.id='$id'");
															if(getNumRows($checkwaybillidrs)==1){

																while($obj=fetch($checkwaybillidrs)){
																	$creditlimit = $obj->credit_limit>0?$obj->credit_limit:0;
																	$oldtotalamount = $obj->total_amount>0?$obj->total_amount:0;
																}

																

												            	$shipperbalance = getShipperOutstandingBalance($shipperid);
												            	$shipperbalance = $shipperbalance-$oldtotalamount;
																$creditlimitavailable = $creditlimit-$shipperbalance;
																$expshipperbalance = $shipperbalance+$totalamount;

																if(str_replace(',', '', $creditlimit)>=str_replace(',', '', $expshipperbalance)){


																		/*** WAYBILL UPDATE OK ***/
																		$systemlog->logEditedInfo($waybillclass,$id,array(
																									  $id,
																									  $waybillnumber,
																									  $waybillstatus,
																									  $bookingnumber,
																									  $origin,
																									  $destination,
																									  $destinationroute,
																									  $pickupdate,
																									  $onholdflag,
																									  $onholdremarks,
																									  $remarks,
																									  'NOCHANGE',
																									  'NOCHANGE',
																									  $now,
																									  $userid,
																									  $documentdate,
																									  'NULL',
																									  'NULL',
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
																									  $pickupstreet,
																									  $pickupdistrict,
																									  $pickupcity,
																									  $pickupprovince,
																									  $pickupzipcode,
																									  $pickupcountry,
																									  $shipmentdescription,
																									  $numberofpackage,
																									  $declaredvalue,
																									  $actualweight,
																									  $vwcbm,
																									  $chargeableweight,
																									  $freight,
																									  $valuation,
																									  $services,
																									  $modeoftransport,
																									  $accompanyingdocument,
																									  $deliveryinstruction,
																									  'NULL',
																									  $transportcharges,
																									  $paymode,
																									  $vat,
																									  $zeroratedflag,
																									  $totalamount,
																									  $carrier,
																									  $shipperrepname,
																									  $freightcomputation,
																									  $insurancerate,
																									  $fuelrate,
																									  $bunkerrate,
																									  $minimumrate,
																									  $subtotal,
																									  $deliverydate,
																									  $vw,
																									  $costcenter,
																									  $shipperpodinstruction,
																									  $amountforcollection,
																									  $waybilltype,
																									  $rushflag,
																									  $pulloutflag,
																									  $pouchsize,
																									  $fixedrateamount,
																									  $pulloutfee,
																									  $baseoda,
																									  $shipperoda,
																									  $oda,
																									  $totalhandlingcharges,
																									  $returndocumentfee,
																									  $waybillfee,
																									  $securityfee,
																									  $docstampfee,
																									  $totalregularcharges,
																									  $totalotherchargesvatable,
																									  $totalotherchargesnonvatable,
																									  $expresstransactiontype,
																									  $odaflag,
																									  $parceltype,
																									  $tpl,
																									  $reference,
																									  $secondaryrecipient,
																							  		  $shippercontact,
																									  $brand,
																									  $costcentercode,
																									  $buyercode,
																									  $contractnumber,
																									  $customernumber,
																									  $project,
																									  $parkingslot,
																									  $blockunitdistrict,
																									  $lotfloor,
																									  $mawbl,
																									  $agent
																								 ),'WAYBILL','Edited Waybill Transaction',$userid,$now);/// log should be before update is made
																		$waybillclass->update($id,array($waybillnumber,
																									  $waybillstatus,
																									  $bookingnumber,
																									  $origin,
																									  $destination,
																									  $destinationroute,
																									  $pickupdate,
																									  $onholdflag,
																									  $onholdremarks,
																									  $remarks,
																									  'NOCHANGE',
																									  'NOCHANGE',
																									  $now,
																									  $userid,
																									  $documentdate,
																									  'NULL',
																									  'NULL',
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
																									  $pickupstreet,
																									  $pickupdistrict,
																									  $pickupcity,
																									  $pickupprovince,
																									  $pickupzipcode,
																									  $pickupcountry,
																									  $shipmentdescription,
																									  $numberofpackage,
																									  $declaredvalue,
																									  $actualweight,
																									  $vwcbm,
																									  $chargeableweight,
																									  $freight,
																									  $valuation,
																									  $services,
																									  $modeoftransport,
																									  $accompanyingdocument,
																									  $deliveryinstruction,
																									  'NULL',
																									  $transportcharges,
																									  $paymode,
																									  $vat,
																									  $zeroratedflag,
																									  $totalamount,
																									  $carrier,
																									  $shipperrepname,
																									  $freightcomputation,
																									  $insurancerate,
																									  $fuelrate,
																									  $bunkerrate,
																									  $minimumrate,
																									  $subtotal,
																									  $deliverydate,
																									  $vw,
																									  $costcenter,
																									  $shipperpodinstruction,
																									  $amountforcollection,
																									  $waybilltype,
																									  $rushflag,
																									  $pulloutflag,
																									  $pouchsize,
																									  $fixedrateamount,
																									  $pulloutfee,
																									  $baseoda,
																									  $shipperoda,
																									  $oda,
																									  $totalhandlingcharges,
																									  $returndocumentfee,
																									  $waybillfee,
																									  $securityfee,
																									  $docstampfee,
																									  $totalregularcharges,
																									  $totalotherchargesvatable,
																									  $totalotherchargesnonvatable,
																									  $expresstransactiontype,
																									  $odaflag,
																									  $parceltype,
																									  $tpl,
																									  $reference,
																									  $secondaryrecipient,
																							  		  $shippercontact,
																									  $brand,
																									  $costcentercode,
																									  $buyercode,
																									  $contractnumber,
																									  $customernumber,
																									  $project,
																									  $parkingslot,
																									  $blockunitdistrict,
																									  $lotfloor,
																									  $mawbl,
																									  $agent
																								 ));

																		$response = array(
																							"response"=>'success',
																							"txnnumber"=>$waybillnumber
																						 );
																		/*** WAYBILL UPDATE OK - END ***/


																		/** PRINT COUNTER RESET **/
																		$wbprintcounter = getInfo("txn_waybill","print_counter","where waybill_number='$waybillnumber'");

																		if($wbprintcounter>0){
																			$systemlog->logInfo('WAYBILL','Print Counter Reset',"Waybill Number: ".$waybillnumber." | Remarks (System Generated): Waybill edited. Print counter has been reset.",$userid,$now);
																			query("update txn_waybill set print_counter=0, printed_by=NULL, printed_date=NULL where waybill_number='$waybillnumber'");
																		}

																		/** PRINT COUNTER RESET - END **/


																}
																else{
																		$creditlimitavailable = convertWithDecimal($creditlimitavailable,5);
																		$response = array(
																							"response"=>'exceedscreditlimit',
										 													"creditlimitavailable"=>$creditlimitavailable
																						 );
																}
															}
															else{
																$response = array(
																			"response"=>'invalidbookingid',
																			"detail"=>$id
																		 );
															}



															





														}


														
														
														
													}

													query("update txn_waybill set freight_cost='$freightcost' where id='$id'");


													$otherchargesdata = array();
													for($i=0;$i<$otherchargesiterate;$i++){
														$temp = array();
														$vatflag = trim(strtoupper($linevatflag[$i]))=='YES'?1:0;

														array_push($temp,
																	NULL,
																	$waybillnumber,
																    escapeString($linedesc[$i]),
																    escapeString(str_replace(',','',$lineamount[$i])),
																	$vatflag
																    );
														array_push($otherchargesdata, $temp);
													}
													$waybillotherchargesclass->deleteWhere("where waybill_number='".$waybillnumber."'");
													if($otherchargesiterate>0){
														$waybillotherchargesclass->insertMultiple($otherchargesdata);
													}


													$packagedimdata = array();
													for($i=0;$i<$packagedimiterate;$i++){
														$temp = array();
														array_push($temp,
																	NULL,
																	$waybillnumber,
																    escapeString(round($linepackagedimlength[$i],4)),
																	escapeString(round($linepackagedimwidth[$i],4)),
																	escapeString(round($linepackagedimheight[$i],4)),
																	escapeString(round($linepackagedimqty[$i],4)),
																	escapeString(round($linepackagedimvw[$i],4)),
																	escapeString(round($linepackagedimcbm[$i],4)),
																	escapeString($linepackagedimuom[$i]),
																	escapeString(round($linepackagedimactualweight[$i],4))
																    );
														array_push($packagedimdata, $temp);
													}
													$waybillpackagedimclass->deleteWhere("where waybill_number='".$waybillnumber."'");
													if($packagedimiterate>0){
														$waybillpackagedimclass->insertMultiple($packagedimdata);
													}



													/**** HANDLING INSTRUCTION ***/
													$handlinginstructionclass = new txn_waybill_handling_instruction();
													$handlinginstructionclass->deleteWhere("where waybill_number='".$waybillnumber."'");
													$hidata = array();
													
													if($_POST['handlinginstruction']!=null){
														for($i=0;$i<count($handlinginstructionarray);$i++){
															$handlingins = array();
															array_push($handlingins, '', $waybillnumber, $handlinginstructionarray[$i]);
															array_push($hidata, $handlingins);
														}
														if(count($handlinginstructionarray)>0){
															$handlinginstructionclass->insertMultiple($hidata);
														}
													}
													/**** HANDLING INSTRUCTION - END ***/

													/**** DOCUMENT ***/
													$documentclass = new txn_waybill_document();
													$documentclass->deleteWhere("where waybill_number='".$waybillnumber."'");
													$bkdocdata = array();

													if($_POST['accompanyingdocument']!=null){
														for($i=0;$i<count($documentarray);$i++){
															$doctemparray = array();
															array_push($doctemparray,$waybillnumber, $documentarray[$i]);
															array_push($bkdocdata, $doctemparray);
														}
														if(count($documentarray)>0){
															$documentclass->insertMultiple($bkdocdata);
														}
													}
													/**** DOCUMENT - END ***/
										}
										else{
											$response = array(
															"response"=>'invaliddocdate'
														 );
										}
									}
									else{
										$response = array(
															"response"=>'invaliddeliverydate'
														 );
									}
								}
								else{
									$response = array(
														"response"=>'invalidpickupdate'
													 );
								}


							}
							else{
								$response = array(
														"response"=>'invalidconsigneeid'
													 );
							}
						}
						else{
							$response = array(
														"response"=>'invalidshipperid'
											  );
						}
					}
					else{
						$response = array(
														"response"=>'exceedsmaxweight',
														"pouchsizemaxweight"=>$maxweightallowed
											  );
					}
				/*}
				else{
					$response = array(
													"response"=>'invalidwaybill'
										  );
				}*/
			/*}
			else if($motexpressid==''){
				$response = array(
												"response"=>'noexpressidformot'
									  );
			}
			else if($srvexpressid==''){
				$response = array(
												"response"=>'noexpressidforsrvc'
									  );
			}
			else{
				$response = array(
												"response"=>'noexpressidformotsrvc'
									  );
			}*/
			echo json_encode($response);



		}
	}

	if(isset($_POST['getReference'])){
		if($_POST['getReference']=='FOio5ja3op2a2lK@3#4hh$93s'){
			$source = escapeString($_POST['source']);
			$id = escapeString($_POST['id']);
			

			$query = '';



			if($source=='first'){
				$query = "select * from txn_waybill order by id asc limit 1";
			}
			else if($source=='second' && $id!=''){
				$query = "select * from txn_waybill where id < $id order by id desc limit 1";
			}
			else if($source=='third' && $id!=''){
				$query = "select * from txn_waybill where id > $id order by id asc limit 1";
			}
			else if($source=='fourth'){
				$query = "select * from txn_waybill order by id desc limit 1";
			}
			else if($id==''){
				$query = "select * from txn_waybill order by id asc limit 1";
			}
			if($query!=''){
				$rs = query($query);
				if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->waybill_number;
				}
				else{
					$rs = query("select * from txn_waybill where id='$id'");
					if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->waybill_number;
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



	if(isset($_POST['getWaybillData'])){
		if($_POST['getWaybillData']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

			$txnnumber = escapeString($_POST['txnnumber']);
			$rs = query("select txn_waybill.id, 
								txn_waybill.waybill_number,
								txn_waybill.status,
				                txn_waybill.booking_number,
				                txn_waybill.origin_id,
				                txn_waybill.destination_id,
				                txn_waybill.destination_route_id,
				                txn_waybill.pickup_date,
				                txn_waybill.on_hold_flag,
				                txn_waybill.on_hold_remarks,
				                txn_waybill.remarks,
				                txn_waybill.created_date,
				                txn_waybill.created_by,
				                txn_waybill.updated_date,
				                txn_waybill.updated_by,
				                txn_waybill.document_date,
				                txn_waybill.delivery_date,
				                txn_waybill.manifest_number,
				                txn_waybill.invoice_number,
				                txn_waybill.shipper_id,
				                txn_waybill.shipper_account_number,
				                txn_waybill.shipper_account_name,
				                txn_waybill.shipper_tel_number,
				                txn_waybill.shipper_company_name,
				                txn_waybill.shipper_pod_instruction,
				                txn_waybill.shipper_street_address,
				                txn_waybill.shipper_district,
				                txn_waybill.shipper_city,
				                txn_waybill.shipper_state_province,
				                txn_waybill.shipper_zip_code,
				                txn_waybill.shipper_country,
				                txn_waybill.consignee_id,
				                txn_waybill.consignee_account_number,
				                txn_waybill.consignee_account_name,
				                txn_waybill.consignee_tel_number,
				                txn_waybill.consignee_company_name,
				                txn_waybill.consignee_street_address,
				                txn_waybill.consignee_district,
				                txn_waybill.consignee_city,
				                txn_waybill.consignee_state_province,
				                txn_waybill.consignee_zip_code,
				                txn_waybill.consignee_country,
				                txn_waybill.pickup_street_address,
				                txn_waybill.pickup_district,
				                txn_waybill.pickup_city,
				                txn_waybill.pickup_state_province,
				                txn_waybill.pickup_zip_code,
				                txn_waybill.pickup_country,
				                txn_waybill.shipment_description,
				                txn_waybill.package_number_of_packages,
				                txn_waybill.package_declared_value,
				                txn_waybill.package_actual_weight,
				                txn_waybill.package_cbm,
				                txn_waybill.package_vw,
				                txn_waybill.package_chargeable_weight,
				                txn_waybill.package_freight,
				                txn_waybill.package_valuation,
				                txn_waybill.package_service,
				                txn_waybill.package_mode_of_transport,
				                txn_waybill.package_document,
				                txn_waybill.package_delivery_instruction,
				                txn_waybill.package_transport_charges,
				                txn_waybill.package_pay_mode,
				                txn_waybill.cost_center,
				                txn_waybill.vat,
				                txn_waybill.zero_rated_flag,
				                txn_waybill.total_amount,
				                txn_waybill.carrier_id,
				                txn_waybill.shipper_rep_name,
				                txn_waybill.package_freight_computation,
						   		txn_waybill.package_insurance_rate,
						    	txn_waybill.package_fuel_rate,
						    	txn_waybill.package_bunker_rate,
						    	txn_waybill.package_minimum_rate,
						    	txn_waybill.subtotal,
						    	txn_waybill.amount_for_collection,
						    	txn_waybill.last_status_update_remarks,
						    	txn_waybill.waybill_type,
						    	txn_waybill.pull_out_flag,
						    	txn_waybill.rush_flag,
						    	txn_waybill.billed_flag,
						    	txn_waybill.billing_reference,
						    	txn_waybill.pouch_size_id,
						    	txn_waybill.fixed_rate_amount,
						    	txn_waybill.pull_out_fee,
						    	txn_waybill.base_oda_charges,
						    	txn_waybill.shipper_oda_rate,
						    	txn_waybill.oda_charges,
						    	txn_waybill.total_handling_charges,
						    	txn_waybill.return_document_fee,
						    	txn_waybill.waybill_fee,
						    	txn_waybill.security_fee,
						    	txn_waybill.doc_stamp_fee,
						    	txn_waybill.oda_flag,
						    	txn_waybill.parcel_type_id,
						    	txn_waybill.third_party_logistic_id,
						    	txn_waybill.express_transaction_type,
						    	txn_waybill.total_regular_charges,
						    	txn_waybill.total_other_charges_vatable,
						    	txn_waybill.total_other_charges_non_vatable,
						    	txn_waybill.print_counter,
						    	txn_waybill.printed_by,
						    	txn_waybill.printed_date,
								txn_waybill.reference,
								txn_waybill.secondary_recipient,
								txn_waybill.mawbl_bl,
								txn_waybill.brand,
								txn_waybill.cost_center_code,
								txn_waybill.buyer_code,
								txn_waybill.contract_number,
								txn_waybill.customer_number,
								txn_waybill.project,
								txn_waybill.phase_parking_slot,
								txn_waybill.block_unit_district,
								txn_waybill.lot_floor,
								txn_waybill.shipper_contact_person,
								txn_waybill.freight_cost,
								txn_waybill.bill_reference,
								txn_waybill.agent_cost,
								txn_waybill.bill_item_number,
								txn_waybill.insurance_amount,
								txn_waybill.insurance_reference,
								txn_waybill.agent_id,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                services.description as servicedesc,
				                mode_of_transport.description as modeoftransport,
				                delivery_instruction.description as deliveryinstruction,
				                destination_route.description as destinationroute,
				                accompanying_documents.description as document,
				                transport_charges.description as transportcharges,
				                carrier.description as carrier,
				                pouch_size.description as pouchsize,
				                parcel_type.description as parceltype,
				                agent.company_name as agent,
				                mode_of_transport.divisor,
				                third_party_logistic.description as thirdpartylogistic,
				                concat(printeduser.first_name,' ',printeduser.last_name) as printedby,
				                case
				                	when txn_waybill.print_counter>0 then 'YES'
				                	else 'NO'
				                end as printedflag
				         from txn_waybill
				         left join origin_destination_port as origintbl on origintbl.id=txn_waybill.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id 
				         left join services on services.id=txn_waybill.package_service
				         left join mode_of_transport on mode_of_transport.id=txn_waybill.package_mode_of_transport
				         left join delivery_instruction on delivery_instruction.id=txn_waybill.package_delivery_instruction
				         left join destination_route on destination_route.id=txn_waybill.destination_route_id
				         left join accompanying_documents on accompanying_documents.id=txn_waybill.package_document
				         left join transport_charges on transport_charges.id=txn_waybill.package_transport_charges
				         left join carrier on carrier.id=txn_waybill.carrier_id
				         left join pouch_size on pouch_size.id=txn_waybill.pouch_size_id
				         left join parcel_type on parcel_type.id=txn_waybill.parcel_type_id
				         left join third_party_logistic on third_party_logistic.id=txn_waybill.third_party_logistic_id
				         left join user as printeduser on printeduser.id=txn_waybill.printed_by
				         left join agent on agent.id=txn_waybill.agent_id
				         where txn_waybill.waybill_number = '$txnnumber'");
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$reprintcount = 0;
					if($obj->print_counter>0){
						$reprintcount = $obj->print_counter-1;
					}
					$printeddate = dateFormat($obj->printed_date, "m/d/Y h:i:s A");
					$createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
					$createdby = getNameOfUser($obj->created_by);
					$updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
					$updatedby = getNameOfUser($obj->updated_by);
					$pickupdate = dateFormat($obj->pickup_date, "m/d/Y");
					$documentdate = dateFormat($obj->document_date, "m/d/Y");
					$deliverydate = dateFormat($obj->delivery_date, "m/d/Y");

					$grossincome = $obj->total_amount-($obj->agent_cost+$obj->freight_cost+$obj->insurance_amount);

					


					$checkifwaybillinloadplanrs = query("select * from txn_load_plan_waybill where waybill_number='$txnnumber'");
					
					if(getNumRows($checkifwaybillinloadplanrs)>0){
						$inloadplan = 'true';
					}
					else{
						$inloadplan = 'false';
					}


					$viewbillingaccess = hasAccess(USERID,'#viewbillingflag');

					$userhasaccess = hasAccess(USERID,'#useraccessmanagewaybill');
					if(USERID==$obj->created_by||USERID==1||$userhasaccess==1){
						$loggedequalcreated='true';
					}
					else{
						$loggedequalcreated='false';
					}

					if($userhasaccess==1||USERID==1){
						$hasadminrights='true';
					}
					else{
						$hasadminrights='false';
					}

					$editwaybilldimensionsaccess = hasAccess(USERID,'#waybill-trans-editdimensionsbtn');
					if($editwaybilldimensionsaccess==1){
						$editwaybilldimensionsflag='true';
					}
					else{
						$editwaybilldimensionsflag='false';
					}

					$viewwaybillchargesaccess = hasAccess(USERID,'#viewwaybillcharges');
					if($viewwaybillchargesaccess==1){
						$viewwaybillchargesflag='true';
					}
					else{
						$viewwaybillchargesflag='false';
					}

					$viewwaybillcostingaccess = hasAccess(USERID,'#viewwaybillcosting');
					if($viewwaybillcostingaccess==1){
						$viewwaybillcostingflag='true';
					}
					else{
						$viewwaybillcostingflag='false';
					}

					$userstatusupdate = hasAccess(USERID,'#waybill-trans-updatestatusbtn');
					if($userstatusupdate==1||USERID==1){
						$userstatusupdateflag='true';
					}
					else{
						$userstatusupdateflag='false';
					}

					$checkiffinalstatus = query("select * from no_update_status where status='$obj->status'");
					if(getNumRows($checkiffinalstatus)>0){
						$finalstatusflag = 'true';
					}
					else{
						$finalstatusflag = 'false';
					}

					$onholdflag = $obj->on_hold_flag==1?'true':'false';
					$zeroratedflag = $obj->zero_rated_flag==1?'true':'false';


					$editwaybillchargesaccess = hasAccess(USERID,'#waybill-trans-editwaybillchargesbtn');
					if($editwaybillchargesaccess==1||USERID==1){
						

						$checkinbillingrs = query("select * from txn_billing_waybill
		            							   left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
		            		                       where txn_billing_waybill.waybill_number='$txnnumber' and
		            		                             txn_billing.status!='VOID' and
		            		                             txn_billing_waybill.flag=1
		            							  ");
            			if(getNumRows($checkinbillingrs)>0){
            				$editchargesenabled='false';
            			}
            			else{
            				$editchargesenabled='true';
            			}
					}
					else{
						$editchargesenabled='false';
					}

					/******** FIELD EDIT ******/
					$fieldeditwbcbmaccess = hasAccess(USERID,'#fieldedit-wbcbm');
					if($fieldeditwbcbmaccess==1||USERID==1){
						$fieldeditwbcbmflag='true';
					}
					else{
						$fieldeditwbcbmflag='false';
					}
					/******** FIELD EDIT ******/

					$resetprintacess = hasAccess(USERID,'#waybill-trans-resetprintcounterbtn');


					$editwaybillcostingaccess = hasAccess(USERID,'#waybill-trans-editwaybillcostingbtn');
					if($editwaybillcostingaccess==1){
						$editwaybillcostingflag='true';
					}
					else{
						$editwaybillcostingflag='false';
					}

					$updatewaybillstatusccess = hasAccess(USERID,'#waybill-trans-updatestatusbtn');
					if($updatewaybillstatusccess==1){
						$updatewaybillstatusflag='true';
					}
					else{
						$updatewaybillstatusflag='false';
					}

					$dataarray = array(
									   "id"=>$obj->id,
									   "status"=>$obj->status,
									   "bookingnumber"=>$obj->booking_number,
									   "originid"=>$obj->origin_id,
									   "origin"=>utfEncode($obj->origin),
									   "destinationid"=>$obj->destination_id,
									   "destination"=>utfEncode($obj->destination),
									   "destinationrouteid"=>$obj->destination_route_id,
									   "destinationroute"=>utfEncode($obj->destinationroute),
									   "onholdflag"=>$onholdflag,
									   "onholdremarks"=>utfEncode($obj->on_hold_remarks),
									   "remarks"=>utfEncode($obj->remarks),
									   "pickupdate"=>$pickupdate,
									   "documentdate"=>$documentdate,
									   "deliverydate"=>$deliverydate,
									   "shipperid"=>$obj->shipper_id,
									   "shipperaccountnumber"=>utfEncode($obj->shipper_account_number),
									   "shipperaccountname"=>utfEncode($obj->shipper_account_name),
									   "shippertel"=>utfEncode($obj->shipper_tel_number),
									   "shippercompanyname"=>utfEncode($obj->shipper_company_name),
									   "shipperpodinstruction"=>utfEncode($obj->shipper_pod_instruction),
									   "shipperstreet"=>utfEncode($obj->shipper_street_address),
									   "shipperdistrict"=>utfEncode($obj->shipper_district),
									   "shippercity"=>utfEncode($obj->shipper_city),
									   "shipperprovince"=>utfEncode($obj->shipper_state_province),
									   "shipperzipcode"=>utfEncode($obj->shipper_zip_code),
									   "shippercountry"=>utfEncode($obj->shipper_country),
									   "pickupstreet"=>utfEncode($obj->pickup_street_address),
									   "pickupdistrict"=>utfEncode($obj->pickup_district),
									   "pickupcity"=>utfEncode($obj->pickup_city),
									   "pickupprovince"=>utfEncode($obj->pickup_state_province),
									   "pickupzipcode"=>utfEncode($obj->pickup_zip_code),
									   "pickupcountry"=>utfEncode($obj->pickup_country),
									   "consigneeid"=>utfEncode($obj->consignee_id),
									   "consigneeaccountnumber"=>utfEncode($obj->consignee_account_number),
									   "consigneeaccountname"=>utfEncode($obj->consignee_account_name),
									   "consigneetel"=>utfEncode($obj->consignee_tel_number),
									   "consigneecompanyname"=>utfEncode($obj->consignee_company_name),
									   "consigneestreet"=>utfEncode($obj->consignee_street_address),
									   "consigneedistrict"=>utfEncode($obj->consignee_district),
									   "consigneecity"=>utfEncode($obj->consignee_city),
									   "secondary_recipient"=>utfEncode($obj->secondary_recipient),
									   "consigneeprovince"=>utfEncode($obj->consignee_state_province),
									   "consigneezipcode"=>utfEncode($obj->consignee_zip_code),
									   "consigneecountry"=>utfEncode($obj->consignee_country),
									   "numberofpackage"=>$obj->package_number_of_packages,
									   "declaredvalue"=>$obj->package_declared_value,
									   "actualweight"=>$obj->package_actual_weight,
									   "vwcbm"=>$obj->package_cbm,
									   "vw"=>$obj->package_vw,
									   "freightcomputation"=>$obj->package_freight_computation,
									   "chargeableweight"=>$obj->package_chargeable_weight,
									   "freight"=>$obj->package_freight,
									   "valuation"=>$obj->package_valuation,
									   "insurancerate"=>$obj->package_insurance_rate,
									   "fuelrate"=>$obj->package_fuel_rate,
									   "bunkerrate"=>$obj->package_bunker_rate,
									   "minimumrate"=>$obj->package_minimum_rate,
									   "subtotal"=>$obj->subtotal,
									   "vat"=>$obj->vat,
									   "totalamount"=>$obj->total_amount,
									   "zeroratedflag"=>$zeroratedflag,
									   "shipmentdescription"=>utfEncode($obj->shipment_description),
									   "serviceid"=>utfEncode($obj->package_service),
									   "service"=>utfEncode($obj->servicedesc),
									   "modeoftransportid"=>$obj->package_mode_of_transport,
									   "modeoftransport"=>utfEncode($obj->modeoftransport),
									   "deliveryinstructionid"=>utfEncode($obj->package_delivery_instruction),
									   "deliveryinstruction"=>utfEncode($obj->deliveryinstruction),
									   "documentid"=>$obj->package_document,
									   "document"=>utfEncode($obj->document),
									   "transportchargesid"=>$obj->package_transport_charges,
									   "transportcharges"=>utfEncode($obj->transportcharges),
									   "carrierid"=>$obj->carrier_id,
									   "carrier"=>utfEncode($obj->carrier),
									   "paymode"=>$obj->package_pay_mode,
									   "costcenter"=>$obj->cost_center,
									   "amountforcollection"=>$obj->amount_for_collection,
									   "createddate"=>$createddate,
									   "createdby"=>utfEncode($createdby),
									   "updateddate"=>$updateddate,
									   "updatedby"=>utfEncode($updatedby),
									   "manifestnumber"=>utfEncode($obj->manifest_number),
									   "invoicenumber"=>utfEncode($obj->invoice_number),
									   "shipperrepname"=>utfEncode($obj->shipper_rep_name),
									   "hasaccess"=>$loggedequalcreated,
									   "hasadminrights"=>$hasadminrights,
									   "laststatupdateremarks"=>utfEncode($obj->last_status_update_remarks),
									   "waybillinloadplan"=>$inloadplan,
									   "usercanupdatestatus"=>$userstatusupdateflag,
									   "finalstatusflag"=>$finalstatusflag,
									   "waybilltype"=>utfEncode($obj->waybill_type),
									   "rushflag"=>$obj->rush_flag,
									   "pulloutflag"=>$obj->pull_out_flag,
									   "odaflag"=>$obj->oda_flag,
									   "viewbillingaccess"=>$viewbillingaccess,
									   "billedflag"=>$obj->billed_flag,
									   "billingreference"=>utfEncode($obj->billing_reference),
									   "pouchsizeid"=>utfEncode($obj->pouch_size_id),
									   "pouchsize"=>utfEncode($obj->pouchsize),
									   "fixedrateamount"=>utfEncode($obj->fixed_rate_amount),
									   "pulloutfee"=>utfEncode($obj->pull_out_fee),
									   "baseoda"=>utfEncode($obj->base_oda_charges),
									   "shipperoda"=>utfEncode($obj->shipper_oda_rate),
									   "oda"=>utfEncode($obj->oda_charges),
									   "totalhandlingcharges"=>utfEncode($obj->total_handling_charges),
									   "returndocumentfee"=>utfEncode($obj->return_document_fee),
									   "waybillfee"=>utfEncode($obj->waybill_fee),
									   "securityfee"=>utfEncode($obj->security_fee),
									   "docstampfee"=>utfEncode($obj->doc_stamp_fee),
									   "expresstransactiontype"=>utfEncode($obj->express_transaction_type),
									   "totalregularcharges"=>utfEncode($obj->total_regular_charges),
									   "totalotherchargesvatable"=>utfEncode($obj->total_other_charges_vatable),
									   "totalotherchargesnonvatable"=>utfEncode($obj->total_other_charges_non_vatable),
									   "editchargesenabled"=>$editchargesenabled,
									   "viewwaybillchargesflag"=>$viewwaybillchargesflag,
									   "fieldeditwbcbm"=>$fieldeditwbcbmflag,
									   "parceltypeid"=>utfEncode($obj->parcel_type_id),
									   "parceltype"=>utfEncode($obj->parceltype),
									   "thirdpartylogisticid"=>utfEncode($obj->third_party_logistic_id),
									   "thirdpartylogistic"=>utfEncode($obj->thirdpartylogistic),
									   "printeddate"=>$printeddate,
									   "printedby"=>utfEncode($obj->printedby),
									   "printcounter"=>$obj->print_counter,
									   "printedflag"=>utfEncode($obj->printedflag),
									   "reprintcount"=>$reprintcount,
									   "resetprintacess"=>$resetprintacess,
									   "divisor"=>utfEncode($obj->divisor),
									   "reference"=>utfEncode($obj->reference),
									   "mawbl"=>utfEncode($obj->mawbl_bl),
									   "shippercontact"=>utfEncode($obj->shipper_contact_person),
									   "brand"=>utfEncode($obj->brand),
									   "costcentercode"=>utfEncode($obj->cost_center_code),
									   "buyercode"=>utfEncode($obj->buyer_code),
									   "contractnumber"=>utfEncode($obj->contract_number),
									   "customernumber"=>utfEncode($obj->customer_number),
									   "project"=>utfEncode($obj->project),
									   "parkingslot"=>utfEncode($obj->phase_parking_slot),
									   "blockunitdistrict"=>utfEncode($obj->block_unit_district),
									   "lotfloor"=>utfEncode($obj->lot_floor),
									   "viewwaybillcostingflag"=>$viewwaybillcostingflag,
									   "editwaybillcostingflag"=>$editwaybillcostingflag,
									   "freightcost"=>utfEncode($obj->freight_cost),
									   "billreference"=>utfEncode($obj->bill_reference),
									   "agentcost"=>utfEncode($obj->agent_cost),
									   "billitemnumber"=>utfEncode($obj->bill_item_number),
									   "insuranceamount"=>utfEncode($obj->insurance_amount),
									   "insurancereference"=>utfEncode($obj->insurance_reference),
									   "grossincome"=>utfEncode($grossincome),
									   "editwaybilldimensionsflag"=>$editwaybilldimensionsflag,
									   "updatewaybillstatusflag"=>$updatewaybillstatusflag,
									   "agentid"=>utfEncode($obj->agent_id),
									   "agent"=>utfEncode($obj->agent)


									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
		}
	}


	if(isset($_POST['WaybillOtherChargesGetInfo'])){
		if($_POST['WaybillOtherChargesGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$txnnumber = escapeString($_POST['txnnumber']);
				$rs = query("select * from txn_waybill_other_charges where waybill_number='$txnnumber'");
				$tmp = array();
				while($obj=fetch($rs)){
					$vatflag = $obj->vatable_flag==1?'YES':'NO';
					$tmpinner = array(
										   "description"=>utfEncode($obj->description),
										   "amount"=>convertWithDecimal($obj->amount,2),
										   "vatflag"=>$vatflag,
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}

	if(isset($_POST['WaybillPackageDimensionsGetInfo'])){
		if($_POST['WaybillPackageDimensionsGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
				$txnnumber = escapeString($_POST['txnnumber']);
				$rs = query("select * from txn_waybill_package_dimension where waybill_number='$txnnumber'");
				$tmp = array();
				while($obj=fetch($rs)){
					$tmpinner = array(
										   "uom"=>$obj->uom,
										   "actualweight"=>$obj->actual_weight,
										   "length"=>$obj->length,
										   "width"=>$obj->width,
										   "height"=>$obj->height,
										   "qty"=>$obj->quantity,
										   "vw"=>$obj->volumetric_weight,
										   "cbm"=>$obj->cbm,
										   "response"=>'success'

										  
										   );
					array_push($tmp, $tmpinner);
				}
				echo json_encode($tmp);
		}
	}



	if(isset($_POST['voidWaybillTransaction'])){
		if($_POST['voidWaybillTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$waybillid = escapeString($_POST['waybillid']);
				$waybillnumber = escapeString($_POST['waybillnumber']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$waybillstathistory = new txn_waybill_status_history();


			

				$checktxnrs = query("select * from txn_waybill where id='$waybillid' and waybill_number='$waybillnumber'");


				if(getNumRows($checktxnrs)==1){

						$rs = query("update txn_waybill set status='VOID', updated_date='$now', updated_by='$userid', cancelled_reason='$remarks', cancelled_date='$now', cancelled_by='$userid', last_status_update_remarks='$remarks' where id='$waybillid'");
						if($rs){
							$waybillstathistory->insert(array('',$waybillnumber,'VOID','VOID',$remarks,$now,$userid,'NULL','NULL','NULL','NULL','NULL','NULL'));
							$systemlog->logInfo('WAYBILL','Cancelled Waybill Transaction',"Waybill Number: ".$waybillnumber." | Remarks: $remarks",$userid,$now);
							echo "success";
						}
					
				}
				else{
					echo "invalidwaybill";
				}

				
				
		}
	}




	if(isset($_POST['postWaybillTransaction'])){
		if($_POST['postWaybillTransaction']=='oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){
				$id = escapeString($_POST['id']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$checktxnrs = query("select txn_waybill.status, 
											txn_waybill.created_by, 
											txn_waybill.shipper_id,
											txn_waybill.total_amount,
											shipper.credit_limit
									from txn_waybill 
									left join shipper on shipper.id=txn_waybill.shipper_id
									where txn_waybill.waybill_number='$txnnumber'");
				$status = '';
				$data = array();
				$createdby = 'none';
				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$shipperid = '';
				$creditlimit = 0;
				$totalamount = 0;

			




				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$status = $obj->status;
						$createdby = $obj->created_by;
						$shipperid = $obj->shipper_id;
						$creditlimit = $obj->credit_limit>0?$obj->credit_limit:0;
						$totalamount = $obj->total_amount>0?$obj->total_amount:0;
					}

					if($status=='LOGGED'){

						$shipperbalance = getShipperOutstandingBalance($shipperid);
						$creditlimitavailable = $creditlimit-$shipperbalance;
						$expshipperbalance = $shipperbalance+$totalamount;

						if(str_replace(',', '', $creditlimit)>=str_replace(',', '', $expshipperbalance)){

							$userhasaccess = hasAccess(USERID,'#useraccessmanagewaybill');
							if(USERID==$createdby||USERID==1||$userhasaccess==1){
								$rs = query("update txn_waybill set status='POSTED', updated_date='$now', updated_by='$userid' where id='$id'");
								if($rs){
									$waybillstathistory = new txn_waybill_status_history();
									$waybillstathistory->insert(array('',$txnnumber,'POSTED','POSTED','',$now,$userid,'NULL','NULL','NULL','NULL','NULL','NULL'));
									$systemlog->logInfo('WAYBILL','Posted Waybill Transaction',"Waybill Number: ".$txnnumber,$userid,$now);
									$data = array("response"=>'success');
								}
							}
							else{
								$data = array("response"=>'noaccess');
							}
						}
						else{	
							$creditlimitavailable = convertWithDecimal($creditlimitavailable,5);
							$data = array(
											   
									 "response"=>'exceedscreditlimit',
									 "creditlimitavailable"=>$creditlimitavailable
								    );
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



	if(isset($_POST['waybillComputation'])){
		if($_POST['waybillComputation']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$waybilltype = escapeString(strtoupper($_POST['waybilltype']));
			$parceltype = 'NULL';//escapeString(strtoupper($_POST['parceltype']));
			$pouchsize = escapeString(strtoupper($_POST['pouchsize']));
			$expresstransactiontype = escapeString(strtoupper($_POST['expresstransactiontype']));
			$rushflag = escapeString(strtoupper($_POST['rushflag']));
			$pulloutflag = escapeString(strtoupper($_POST['pulloutflag']));
			$shipperid = escapeString(strtoupper($_POST['shipperid']));
			$consigneeid = escapeString(strtoupper($_POST['consigneeid']));

			$odaflag = escapeString(strtoupper($_POST['odaflag']));

			$tpl = escapeString(strtoupper($_POST['tpl']));
			$origin = escapeString(strtoupper($_POST['origin']));
			$destination = escapeString(strtoupper($_POST['destination']));
			$modeoftransport = escapeString(strtoupper($_POST['modeoftransport']));
			$services = escapeString(strtoupper($_POST['services']));
			$cbm = trim($_POST['cbm'])==''?0:escapeString($_POST['cbm']);
			$cbm = $cbm>0&&$cbm<1?1:$cbm;
			$vw = trim($_POST['vw'])==''?0:escapeString($_POST['vw']);
			$actualweight = trim($_POST['actualweight'])==''?0:escapeString($_POST['actualweight']);
			$declaredvalue = trim($_POST['declaredvalue'])==''?0:escapeString($_POST['declaredvalue']);
			$amountforcollection = trim($_POST['amountforcollection'])==''?0:escapeString($_POST['amountforcollection']);
			$numberofpackage = trim($_POST['numberofpackage'])==''?0:escapeString($_POST['numberofpackage']);
			$response = array();

			$handlinginstruction = $_POST['handlinginstruction'];
			$handlinginstruction = $handlinginstruction!=null?"(".implode(', ', $handlinginstruction).")":'';
			
			//print_r($handlinginstruction);
			$zone = '';
			$zonetype = '';

			$rs = query("select origin_destination_port.zone_id, zone.type from origin_destination_port
				         left join zone on zone.id=origin_destination_port.zone_id
				         where origin_destination_port.id='$destination'");
			while($obj=fetch($rs)){
				$zone = $obj->zone_id;
				$zonetype = $obj->type;
			}

			$decimalplaces = 2;
			

			$ratefrom = '';
			$returndocumentfee = 0;
			$waybillfee = 0;
			$securityfee = 0;
			$docstampfee = 0;
			$freightcomputation = '';
			$valuation = 0;
			$freightrate = 0;
			$insurancerate = 0;
			$fuelrate = 0;
			$bunkerrate = 0;
			$minimumrate = 0;
			$pulloutfee = 0;
			$fixedrateamount = 0;
			$multiplier = 0;
			$totalrate = 0;
			$baseoda = 0;
			$shipperoda = 0;
			$oda = 0;
			$totalhandlingcharges = 0;
			$vat = getInfo("company_information","value_added_tax_percentage","where id=1");
			$vat = $vat>=0?$vat:0;
			$totalshipperfixedcharges = 0;
			$chargeableweight = 0;
			$supplierfreightrate = 0;


			//$pouchsizecondition = $waybilltype=='DOCUMENT'?" and pouch_size_id='$pouchsize'":'';
			$pouchsizecondition =" and pouch_size_id='$pouchsize'";

			
			
				
				/*if($waybilltype=='PARCEL'&&$origin!=''&&$origin!='NULL'&&$destination!=''&&$destination!='NULL'&&$modeoftransport!=''&&$modeoftransport!='NULL'&&$services!=''&&$services!='NULL'){
					$checkshipperraters = query("select * from shipper_rate where origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' and services_id='$services' and rush_flag='$rushflag' and pull_out_flag='$pulloutflag' and shipper_id='$shipperid' and waybill_type='$waybilltype'  limit 1");//and parcel_type_id='$parceltype'
					if(getNumRows($checkshipperraters)==1){// has shipper rate
						while($obj=fetch($checkshipperraters)){
							$returndocumentfee = $obj->return_document_fee;
							$waybillfee = $obj->waybill_fee;
							$securityfee = $obj->security_fee;
							$docstampfee = $obj->doc_stamp_fee;
							$totalshipperfixedcharges = $returndocumentfee+$waybillfee+$securityfee+$docstampfee;

							$freightchargecomputation = $obj->freight_charge_computation;
							$insuranceratecomputation = $obj->insurance_rate_computation;
							$cbmcomputation = $obj->cbm_computation;
							$excessamount = $obj->excess_amount;


							$shipperrateid = $obj->id;
							$fixedrateflag = $obj->fixed_rate_flag;
							$freightcomputation = $obj->freight_computation;
							$minimumrate = $obj->minimum_rate;

							$rtcollectionpercentage = $obj->collection_fee_percentage>=0?$obj->collection_fee_percentage:0;
							$rtvaluation = $obj->valuation>=0?$obj->valuation:0;
							$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
							$rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
							$rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
							$rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

							$pulloutfee = $obj->pull_out_fee>=0?$obj->pull_out_fee:0;
							$fixedrateamount = $obj->fixed_rate_amount>=0?$obj->fixed_rate_amount:0;
							

							if($fixedrateflag==0){
								$shipperoda = $obj->oda_rate>=0?$obj->oda_rate:0;
								//$oda = $baseoda>0?($baseoda*(1+($shipperoda/100))):0;
								$oda = $shipperoda;
							}

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

								if($cbmcomputation==2){
									if($minimumrate>$freightrate){
										$freightrate = $minimumrate;
									}
								}
							}
							else if(strtoupper($freightcomputation)=='VOLUMETRIC'){
								$multiplier = $vw;
								$freightrate = $multiplier*$rtfreightrate;
							}
							else if(strtoupper($freightcomputation)=='ACTUAL WEIGHT'){
								$multiplier = $actualweight;
								$freightrate = $multiplier*$rtfreightrate;
							}
							else if(strtoupper($freightcomputation)=='COLLECTION FEE'){
								$multiplier = 0;
								$freightrate = $amountforcollection*($rtcollectionpercentage/100);
							}
							else if(strtoupper($freightcomputation)=='DEFAULT'){
								$mode = getInfo("mode_of_transport","description","where id='$modeoftransport'");
								if(strpos(strtoupper($mode), 'SEA') !== false){
									if($actualweight>$cbm){
										$multiplier = $actualweight;
									}
									else{
										$multiplier = $cbm;
									} 
								}
								else{
									if($actualweight>$vw){
										$multiplier = $actualweight;
									}
									else{
										$multiplier = $vw;
									}	
								}
								$freightrate = $multiplier*$rtfreightrate;
							}
							else{
								$chargeableweight = 0;
								$multiplier = $chargeableweight;
								$freightrate = $multiplier*$rtfreightrate;
							}

							$valuation = $declaredvalue*($rtvaluation/100);
							
							$fuelrate = $multiplier*$rtfuelrate;
							$bunkerrate = $multiplier*$rtbunkerrate;
							$chargeableweight = $multiplier;

							if($insuranceratecomputation==1){
								$insurancerate = $actualweight*$rtinsurancerate;
							}
							else if($insuranceratecomputation==2){
								$excess = $declaredvalue-$excessamount;
								$excess = $excess>=0?$excess:0;
								$insurancerate = $excess*$rtinsurancerate;
							}
							else{
								$insurancerate = 0;
							}


							if(strtoupper($freightcomputation)=='ACTUAL WEIGHT'||strtoupper($freightcomputation)=='VOLUMETRIC'||strtoupper($freightcomputation)=='CBM'||strtoupper($freightcomputation)=='DEFAULT'){

								$checkshipperratefreight = query("select * from shipper_rate_freight_charge where shipper_rate_id='$shipperrateid' and from_kg<=$multiplier and to_kg>=$multiplier order by to_kg asc limit 1");
								if(getNumRows($checkshipperratefreight)==1){
									while($srfobj=fetch($checkshipperratefreight)){
										

										if($freightchargecomputation==1){
											$freightrate = $srfobj->freight_charge*$multiplier;
										}
										else if($freightchargecomputation==2){
											$freightrate = $srfobj->freight_charge;
										}
									}
								}else{
									$checkshipperratefreight = query("select * from shipper_rate_freight_charge where shipper_rate_id='$shipperrateid' and from_kg<=$multiplier order by to_kg desc limit 1");
									while($srfobj=fetch($checkshipperratefreight)){
										$maxkg = $srfobj->to_kg;
										$excesskg = $multiplier-$maxkg;
										$excesskg = $excesskg>0?$excesskg:0;
										$excesscharge = $excesskg*$srfobj->excess_weight_charge;
										$excesscharge = round($excesscharge,$decimalplaces);
										

										if($freightchargecomputation==1){
											$freightrate = ($srfobj->freight_charge*$multiplier)+$excesscharge;
										}
										else if($freightchargecomputation==2){
											$freightrate = $srfobj->freight_charge+$excesscharge;
										}
									}
								}

							}

							if($handlinginstruction!=''&&$fixedrateflag==0){
								$gettotalhandlingcharges = query("select * from shipper_rate_handling_instruction where shipper_rate_id='$shipperrateid' and handling_instruction_id in $handlinginstruction");
								while($hiobj=fetch($gettotalhandlingcharges)){
									if($hiobj->percentage_flag==1){
										$percentage = ($hiobj->percentage/100)*$freightrate;
										$totalhandlingcharges = $totalhandlingcharges + round($percentage,$decimalplaces);
									}
									else{
										$totalhandlingcharges = $totalhandlingcharges + round($hiobj->fixed_charge,$decimalplaces);
									}
									
								}

							}

							$oda = $odaflag!=1?0:$oda;
							$shipperoda = $odaflag!=1?0:$shipperoda;
							$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

						}
						$ratefrom = 'SHIPPER';
					}
					else{//no shipper rate, check published rate instead

						$checkpublishedraters = query("select * from published_rate where origin_id='$origin' and destination_id='$destination' and mode_of_transport_id='$modeoftransport' and services_id='$services' and rush_flag='$rushflag' and pull_out_flag='$pulloutflag' and waybill_type='$waybilltype' limit 1");
						if(getNumRows($checkpublishedraters)==1){
							while($obj=fetch($checkpublishedraters)){

							
								$fixedrateflag = $obj->fixed_rate_flag;

								$freightcomputation = $obj->freight_computation;
								$minimumrate = $obj->minimum_rate;

								$rtvaluation = $obj->valuation>=0?$obj->valuation:0;
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
								$rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
								$rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
								$rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

								$pulloutfee = $obj->pull_out_fee>=0?$obj->pull_out_fee:0;
							    $fixedrateamount = $obj->fixed_rate_amount>=0?$obj->fixed_rate_amount:0;

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
								else if(strtoupper($freightcomputation)=='COLLECTION FEE'){
									$multiplier = $amountforcollection;
									$freightrate = 0;//$multiplier*($rtcollectionpercentage/100);
								}
								else if(strtoupper($freightcomputation)=='DEFAULT'){
									$mode = getInfo("mode_of_transport","description","where id='$modeoftransport'");
									if(strpos(strtoupper($mode), 'SEA') !== false){
										if($actualweight>$cbm){
											$multiplier = $actualweight;
										}
										else{
											$multiplier = $cbm;
										} 
									}
									else{
										if($actualweight>$vw){
											$multiplier = $actualweight;
										}
										else{
											$multiplier = $vw;
										}	
									}
									$freightrate = $multiplier*$rtfreightrate;
								}
								else{
									$chargeableweight = 0;
									$multiplier = $chargeableweight;
									$freightrate = $multiplier*$rtfreightrate;
								}

								$valuation = $declaredvalue*($rtvaluation/100);
								$insurancerate = $multiplier*$rtinsurancerate;
								$fuelrate = $multiplier*$rtfuelrate;
								$bunkerrate = $multiplier*$rtbunkerrate;
								$chargeableweight = $multiplier;

								//$totalrate = $valuation+$freightrate+$insurancerate+$fuelrate+$bunkerrate;
								$oda = $baseoda;
								$oda = $odaflag!=1?0:$oda;
								$shipperoda = $odaflag!=1?0:$shipperoda;
								$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

							}
							$ratefrom = 'PUBLISHED';
						}
						else{
							$chargeableweight = 0;
						}

					}
				}
				else if($waybilltype=='DOCUMENT'&&$origin!=''&&$origin!='NULL'&&$destination!=''&&$destination!='NULL'&&$pouchsize!=''&&$pouchsize!='NULL'){
					$checkshipperraters = query("select * from shipper_rate where origin_id='$origin' and destination_id='$destination' and rush_flag='$rushflag' and shipper_id='$shipperid' and waybill_type='$waybilltype' and pouch_size_id='$pouchsize' and express_transaction_type='$expresstransactiontype' limit 1");
					if(getNumRows($checkshipperraters)==1){// has shipper rate
						while($obj=fetch($checkshipperraters)){

							$returndocumentfee = $obj->return_document_fee;
							$waybillfee = $obj->waybill_fee;
							$securityfee = $obj->security_fee;
							$docstampfee = $obj->doc_stamp_fee;
							$totalshipperfixedcharges = $returndocumentfee+$waybillfee+$securityfee+$docstampfee;

							

							$shipperrateid = $obj->id;
							$fixedrateflag = $obj->fixed_rate_flag;
							$minimumrate = $obj->minimum_rate;

							$rtvaluation = $obj->valuation>=0?$obj->valuation:0;
							$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
							$rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
							$rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
							$rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

							$pulloutfee = $obj->pull_out_fee>=0?$obj->pull_out_fee:0;
							$fixedrateamount = $obj->fixed_rate_amount>=0?$obj->fixed_rate_amount:0;
							
							if($fixedrateflag==0){
								$shipperoda = $obj->oda_rate>=0?$obj->oda_rate:0;
								//$oda = $baseoda>0?($baseoda*(1+($shipperoda/100))):0;
								$oda = $shipperoda;
							}

							$advaloremflag = $obj->ad_valorem_flag;
							if($advaloremflag==1){
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate/100:0;
							}



							
							$multiplier = $declaredvalue;
							$freightrate = $multiplier*$rtfreightrate;
							$valuation = $declaredvalue*($rtvaluation/100);
							$insurancerate = $multiplier*$rtinsurancerate;
							$fuelrate = $multiplier*$rtfuelrate;
							$bunkerrate = $multiplier*$rtbunkerrate;
							$chargeableweight = $multiplier;

							


							$oda = $odaflag!=1?0:$oda;
							$shipperoda = $odaflag!=1?0:$shipperoda;
							$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

						}
						$ratefrom = 'SHIPPER';
					}
					else{//no shipper rate, check published rate instead

						$checkpublishedraters = query("select * from published_rate where origin_id='$origin' and destination_id='$destination' and rush_flag='$rushflag' and waybill_type='$waybilltype' and pouch_size_id='$pouchsize' and express_transaction_type='$expresstransactiontype' limit 1");
						if(getNumRows($checkpublishedraters)==1){
							while($obj=fetch($checkpublishedraters)){
								$fixedrateflag = $obj->fixed_rate_flag;
								$minimumrate = $obj->minimum_rate;

								$rtvaluation = $obj->valuation>=0?$obj->valuation:0;
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
								$rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
								$rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
								$rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

								$pulloutfee = $obj->pull_out_fee>=0?$obj->pull_out_fee:0;
								$fixedrateamount = $obj->fixed_rate_amount>=0?$obj->fixed_rate_amount:0;

								
								$multiplier = $declaredvalue;
								$freightrate = $multiplier*$rtfreightrate;
								
								$valuation = $declaredvalue*($rtvaluation/100);
								$insurancerate = $multiplier*$rtinsurancerate;
								$fuelrate = $multiplier*$rtfuelrate;
								$bunkerrate = $multiplier*$rtbunkerrate;
								$chargeableweight = $multiplier;

								//$totalrate = $valuation+$freightrate+$insurancerate+$fuelrate+$bunkerrate;
								$oda = $baseoda;
								$oda = $odaflag!=1?0:$oda;
								$shipperoda = $odaflag!=1?0:$shipperoda;
								$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

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
					$oda = $baseoda;

					$oda = $odaflag!=1?0:$oda;
					$shipperoda = $odaflag!=1?0:$shipperoda;
					$totalrate = $baseoda+$returndocumentfee+$waybillfee+$securityfee+$docstampfee;
				}*/




			if(($waybilltype=='PARCEL'||$waybilltype=='DOCUMENT')&&$origin!=''&&$origin!='NULL'&&$zone!=''&&$zone!='NULL'&&$tpl!=''&&$tpl!='NULL'&&$pouchsize!=''&&$pouchsize!='NULL'){


					$checkshipperraters = query("select * 
							                     from shipper_rate 
							                     where origin_id='$origin' and 
							                          zone_id='$zone' and 
							                          third_party_logistic_id='$tpl' and
							                          waybill_type='$waybilltype' and 
							                          shipper_id='$shipperid '$pouchsizecondition limit 1");
					if(getNumRows($checkshipperraters)==1){// has shipper rate
						
						
							while($obj=fetch($checkshipperraters)){
								$rateid = $obj->id;
								$multiplier = $actualweight;

								if($vw>$actualweight){
									$multiplier = $vw;
								}
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
								$fixedrateflag = $obj->fixed_rate_flag;

								if($zonetype=='DOMESTIC'){
									$multiplier = ceil($multiplier);
								}

								$chargeableweight = $multiplier;

								

								if($fixedrateflag==1){
									$freightrate = $rtfreightrate;
								}
								else{

									if($zonetype=='DOMESTIC'){
										$checkshipperratefreight = query("select * 
																			from shipper_rate_freight_charge 
										                                    where shipper_rate_id='$rateid' and 
										                                	      from_kg<=$multiplier 
										                                     order by to_kg desc limit 1");
										while($prfobj=fetch($checkshipperratefreight)){
											$maxkg = $prfobj->to_kg;
											$excesskg = $multiplier-$maxkg;
											$excesskg = $excesskg>0?$excesskg:0;
											$excesscharge = $excesskg*$prfobj->excess_weight_charge;
											$excesscharge = round($excesscharge,$decimalplaces);
											
											$freightrate = $prfobj->freight_charge+$excesscharge;
											
										}
									}
									else{
										$checkshipperratefreight = query("select * from shipper_rate_freight_charge 
										                                where shipper_rate_id='$rateid' and 
										                                	  from_kg<=$multiplier and
										                                	  to_kg>=$multiplier
										                                order by to_kg desc limit 1");
										while($prfobj=fetch($checkshipperratefreight)){
				
											$freightrate = $prfobj->freight_charge;
											
										}
									}
									
									
								}

							
								$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

							}
							$ratefrom = 'SHIPPER';
						
					}
					else{//no shipper rate, check published rate instead

						$checkpublishedraters = query("select * 
							                           from published_rate 
							                           where origin_id='$origin' and 
							                                 zone_id='$zone' and 
							                                 third_party_logistic_id='$tpl' and
							                                 waybill_type='$waybilltype' $pouchsizecondition limit 1");

						
						if(getNumRows($checkpublishedraters)==1){
							while($obj=fetch($checkpublishedraters)){
								$rateid = $obj->id;
								$multiplier = $actualweight;

								if($vw>$actualweight){
									$multiplier = $vw;
								}
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
								$fixedrateflag = $obj->fixed_rate_flag;

								if($zonetype=='DOMESTIC'){
									$multiplier = ceil($multiplier);
								}

								$chargeableweight = $multiplier;

								

								if($fixedrateflag==1){
									$freightrate = $rtfreightrate;
								}
								else{

									if($zonetype=='DOMESTIC'){
										$checkpublishedratefreight = query("select * from published_rate_freight_charge 
										                                where published_rate_id='$rateid' and 
										                                	  from_kg<=$multiplier 
										                                order by to_kg desc limit 1");
										while($prfobj=fetch($checkpublishedratefreight)){
											$maxkg = $prfobj->to_kg;
											$excesskg = $multiplier-$maxkg;
											$excesskg = $excesskg>0?$excesskg:0;
											$excesscharge = $excesskg*$prfobj->excess_weight_charge;
											$excesscharge = round($excesscharge,$decimalplaces);
											
											$freightrate = $prfobj->freight_charge+$excesscharge;
											
										}
									}
									else{
										$checkpublishedratefreight = query("select * from published_rate_freight_charge 
										                                where published_rate_id='$rateid' and 
										                                	  from_kg<=$multiplier and
										                                	  to_kg>=$multiplier
										                                order by to_kg desc limit 1");
										while($prfobj=fetch($checkpublishedratefreight)){
				
											$freightrate = $prfobj->freight_charge;
											
										}
									}
									
									
								}

							/*
							  	$freightcomputation = $obj->freight_computation;
								$minimumrate = $obj->minimum_rate;

								$rtvaluation = $obj->valuation>=0?$obj->valuation:0;
								$rtfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
								$rtinsurancerate = $obj->insurance_rate>=0?$obj->insurance_rate:0;
								$rtfuelrate = $obj->fuel_rate>=0?$obj->fuel_rate:0;
								$rtbunkerrate = $obj->bunker_rate>=0?$obj->bunker_rate:0;

								$pulloutfee = $obj->pull_out_fee>=0?$obj->pull_out_fee:0;
							    $fixedrateamount = $obj->fixed_rate_amount>=0?$obj->fixed_rate_amount:0;

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
								else if(strtoupper($freightcomputation)=='COLLECTION FEE'){
									$multiplier = $amountforcollection;
									$freightrate = 0;//$multiplier*($rtcollectionpercentage/100);
								}
								else if(strtoupper($freightcomputation)=='DEFAULT'){
									$mode = getInfo("mode_of_transport","description","where id='$modeoftransport'");
									if(strpos(strtoupper($mode), 'SEA') !== false){
										if($actualweight>$cbm){
											$multiplier = $actualweight;
										}
										else{
											$multiplier = $cbm;
										} 
									}
									else{
										if($actualweight>$vw){
											$multiplier = $actualweight;
										}
										else{
											$multiplier = $vw;
										}	
									}
									$freightrate = $multiplier*$rtfreightrate;
								}
								else{
									$chargeableweight = 0;
									$multiplier = $chargeableweight;
									$freightrate = $multiplier*$rtfreightrate;
								}

								$valuation = $declaredvalue*($rtvaluation/100);
								$insurancerate = $multiplier*$rtinsurancerate;
								$fuelrate = $multiplier*$rtfuelrate;
								$bunkerrate = $multiplier*$rtbunkerrate;
								$chargeableweight = $multiplier;

								//$totalrate = $valuation+$freightrate+$insurancerate+$fuelrate+$bunkerrate;
								$oda = $baseoda;
								$oda = $odaflag!=1?0:$oda;
								$shipperoda = $odaflag!=1?0:$shipperoda;

							*/
								$totalrate = round($valuation,$decimalplaces)+round($freightrate,$decimalplaces)+round($insurancerate,$decimalplaces)+round($fuelrate,$decimalplaces)+round($bunkerrate,$decimalplaces)+round($pulloutfee,$decimalplaces)+round($fixedrateamount,$decimalplaces)+round($totalhandlingcharges,$decimalplaces)+round($oda,$decimalplaces)+$totalshipperfixedcharges;

							}
							$ratefrom = 'PUBLISHED';
						}
						else{
							$chargeableweight = 0;
						}

					}
			}



			$checksupplierraters = query("select * 
							              from supplier_rate 
							              where origin_id='$origin' and 
							                    zone_id='$zone' and 
							                    third_party_logistic_id='$tpl' and
							                    waybill_type='$waybilltype' 
							                    $pouchsizecondition 
							              limit 1");

						
			if(getNumRows($checksupplierraters)==1){
				while($obj=fetch($checksupplierraters)){
					$supplierrateid = $obj->id;
					$suppliermultiplier = $actualweight;

					if($vw>$actualweight){
						$suppliermultiplier = $vw;
					}
					$rtsupplierfreightrate = $obj->freight_rate>=0?$obj->freight_rate:0;
					$supplierfixedrateflag = $obj->fixed_rate_flag;

					if($zonetype=='DOMESTIC'){
						$suppliermultiplier = ceil($suppliermultiplier);
					}

					$chargeableweight = $suppliermultiplier;

								

					if($fixedrateflag==1){
						$supplierfreightrate = $rtsupplierfreightrate;
					}
					else{

						if($zonetype=='DOMESTIC'){
							$checksupplierratefreight = query("select * 
								                               from supplier_rate_freight_charge 
										                       where supplier_rate_id='$supplierrateid' and 
										                             from_kg<=$suppliermultiplier 
										                       order by to_kg desc limit 1");
							while($prfobj=fetch($checksupplierratefreight)){
											$maxkg = $prfobj->to_kg;
											$excesskg = $suppliermultiplier-$maxkg;
											$excesskg = $excesskg>0?$excesskg:0;
											$excesscharge = $excesskg*$prfobj->excess_weight_charge;
											$excesscharge = round($excesscharge,$decimalplaces);
											
											$supplierfreightrate = $prfobj->freight_charge+$excesscharge;
											
							}
						}
						else{
							$checksupplierratefreight = query("select * 
															   from supplier_rate_freight_charge 
										                       where supplier_rate_id='$supplierrateid' and 
										                             from_kg<=$suppliermultiplier and
										                             to_kg>=$suppliermultiplier
										                       order by to_kg desc limit 1");
							while($prfobj=fetch($checksupplierratefreight)){
				
											$supplierfreightrate = $prfobj->freight_charge;
											
							}
						}
					}
									
									
						

							
								

				}
							
			}
			else{
				$chargeableweight = 0;
			}

				
				



			

			$response = array(
									   "returndocumentfee"=>round($returndocumentfee,$decimalplaces),
									   "waybillfee"=>round($waybillfee,$decimalplaces),
									   "securityfee"=>round($securityfee,$decimalplaces),
									   "docstampfee"=>round($docstampfee,$decimalplaces),
									   "freightcomputation"=>$freightcomputation,
									   "valuation"=>round($valuation,$decimalplaces),
									   "freightrate"=>round($freightrate,$decimalplaces),
									   "insurancerate"=>round($insurancerate,$decimalplaces),
									   "fuelrate"=>round($fuelrate,$decimalplaces),
									   "bunkerrate"=>round($bunkerrate,$decimalplaces),
									   "minimumrate"=>round($minimumrate,$decimalplaces),
									   "pulloutfee"=>round($pulloutfee,$decimalplaces),
									   "fixedrateamount"=>round($fixedrateamount,$decimalplaces),
									   "shipperoda"=>round($shipperoda,$decimalplaces),
									   "baseoda"=>round($baseoda,$decimalplaces),
									   "totalhandlingcharges"=>round($totalhandlingcharges,$decimalplaces),
									   "oda"=>round($oda,$decimalplaces),
									   "chargeableweight"=>$chargeableweight,
									   "ratefrom"=>$ratefrom,
									   "multiplier"=>$multiplier,
									   "totalrate"=>$totalrate,
									   "decimalplaces"=>$decimalplaces,
									   "supplierfreightrate"=>round($supplierfreightrate,$decimalplaces),
									   "vat"=>$vat,
									   "response"=>'success'
							);


			print_r(json_encode($response));


		}
	}

	/*if(isset($_POST['updateStatusWaybill'])){
		if($_POST['updateStatusWaybill']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$waybillid = escapeString($_POST['waybillid']);
				$status = escapeString($_POST['status']);
				$remarks = escapeString($_POST['remarks']);
				$receivedby = escapeString(trim($_POST['receivedby']));
				$receiveddate = escapeString(trim($_POST['receiveddate']));
				$receiveddate = datetimeString($receiveddate);

				$checkdate = dateString($_POST['receiveddate']);


				if(strtoupper($status)!='DEL'&&strtoupper($status)!='DELIVERED'){
					$receiveddate = 'NULL';
					$receivedby = 'NULL';
				}
				else{
					$dash = trim($remarks)==''?'':'  - ';
					$remarks = $remarks."$dash Received by: $receivedby, Received Date: $receiveddate";
				}

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$waybillstathistory = new txn_waybill_status_history();
				$waybillnumber = '';
				$statusdesc = '';


			
				if((validateDate($checkdate)==1&&$checkdate!='1970-01-01')||$status!='DEL'){
					$checktxnrs = query("select * from txn_waybill where id='$waybillid'");



					if(getNumRows($checktxnrs)==1){

							while($obj=fetch($checktxnrs)){
								$waybillnumber = $obj->waybill_number;
							}

							$checkstatusrs = query("select * from status where code='$status' and type='WAYBILL'");

							if(getNumRows($checkstatusrs)==1){
								while($obj=fetch($checkstatusrs)){
									$statusdesc = $obj->description;
								}

								$rs = query("update txn_waybill set status='$statusdesc', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' where id='$waybillid'");
								if($rs){
									$waybillstathistory->insert(array('',$waybillnumber,$status,$statusdesc,$remarks,$now,$userid,'NULL','NULL','NULL',$receivedby,$receiveddate));
									$systemlog = new system_log();
									$systemlog->logInfo('WAYBILL',"Waybill Status Update: $status","Waybill Number: ".$waybillnumber." | Status: $status - $statusdesc  |  Remarks: $remarks",$userid,$now);
									echo "success";
								}
							}
							else{
								echo "invalidstatus";
							}
					

					}
					else{
						echo "invalidwaybill";
					}
				}
				else{
					echo "invalidreceiveddate";
				}

				
				
		}
	}*/
	/*if(isset($_POST['updateStatusWaybill'])){
		if($_POST['updateStatusWaybill']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$waybillid = escapeString($_POST['waybillid']);
				$status = escapeString(strtoupper($_POST['status']));
				$remarks = escapeString($_POST['remarks']);

				$receivedby = escapeString(trim($_POST['receivedby']));
				$receiveddate = escapeString(trim($_POST['receiveddate']));
				$receiveddate = datetimeString($receiveddate);

				$checkdate = dateString($_POST['receiveddate']);


				if(strtoupper($status)!='DEL'&&strtoupper($status)!='DELIVERED'){
					$receiveddate = 'NULL';
					$receivedby = 'NULL';
				}
				else{
					$dash = trim($remarks)==''?'':'  - ';
					$remarks = $remarks."$dash Received by: $receivedby, Received Date: $receiveddate";
				}

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$waybillstathistory = new txn_waybill_status_history();
				$waybillnumber = '';
				$statusdesc = '';

				$mftnumber = '';
				$ldpnumber = '';




			

				$checktxnrs = query("select * from txn_waybill where id='$waybillid'");


				if((validateDate($checkdate)==1&&$checkdate!='1970-01-01')||$status!='DEL'){


				
					if(getNumRows($checktxnrs)==1){

						while($obj=fetch($checktxnrs)){
								$waybillnumber = $obj->waybill_number;
						}

						$checkifinloggedmanifest = query("select txn_manifest_waybill.waybill_number,
																 txn_manifest_waybill.manifest_number 
						                              from txn_manifest_waybill
						                              left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
						                              where txn_manifest.status='LOGGED' and 
						                                    txn_manifest_waybill.waybill_number='$waybillnumber'");
						if(getNumRows($checkifinloggedmanifest)>0&&($status=='DEL'||$status=='DELIVERED')){
							while($obj=fetch($checkifinloggedmanifest)){
								$mftnumber = $obj->manifest_number;
							}

							echo "inpendingmanifesttxn@$mftnumber@inpendingmanifesttxn";

						}
						else{

							$checkifinactiveloadplan = query("select txn_load_plan_waybill.waybill_number,
																     txn_load_plan_waybill.load_plan_number 
						                                      from txn_load_plan_waybill
						                                      left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number 
						                                      where txn_load_plan.status!='DISPATCHED' and
						                                            txn_load_plan.status!='VOID' and  
						                                            txn_load_plan_waybill.waybill_number='$waybillnumber'");


							if(getNumRows($checkifinactiveloadplan)>0&&($status=='DEL'||$status=='DELIVERED')){

								while($obj=fetch($checkifinactiveloadplan)){
									$ldpnumber = $obj->load_plan_number;
								}

								echo "inpendingloadplantxn@$ldpnumber@inpendingloadplantxn";
							}		
							else{
							
								//// OK 
								$checkstatusrs = query("select * from status where code='$status' and type='WAYBILL'");

								if(getNumRows($checkstatusrs)==1){
									while($obj=fetch($checkstatusrs)){
										$statusdesc = $obj->description;
									}

									$rs = query("update txn_waybill set status='$statusdesc', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' where id='$waybillid'");
									if($rs){
										$waybillstathistory->insert(array('',$waybillnumber,$status,$statusdesc,$remarks,$now,$userid,'NULL','NULL','NULL',$receivedby,$receiveddate));
										$systemlog = new system_log();
										$systemlog->logInfo('WAYBILL',"Waybill Status Update: $status","Waybill Number: ".$waybillnumber." | Status: $status - $statusdesc  |  Remarks: $remarks",$userid,$now);
										echo "success";
									}
								}
								else{
									echo "invalidstatus";
								}
								///// OK 

							}




						}
					

					}
					else{
						echo "invalidwaybill";
					}
				}
				else{
					echo "invalidreceiveddate";
				}

				
				
		}
	}*/

	if(isset($_POST['updateStatusWaybill'])){
		if($_POST['updateStatusWaybill']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$waybillid = escapeString($_POST['waybillid']);
				$status = escapeString(strtoupper($_POST['status']));
				$remarks = escapeString($_POST['remarks']);

				$personnel = escapeString($_POST['personnel'])==''?'NULL':escapeString($_POST['personnel']);
				$receivedby = escapeString(trim($_POST['receivedby']));
				$receiveddate = escapeString(trim($_POST['receiveddate']));
				$receiveddate = datetimeString($receiveddate);

				$checkdate = dateString($_POST['receiveddate']);


				if(strtoupper($status)!='DEL'&&strtoupper($status)!='DELIVERED'&&strtoupper($status)!='RTS'&&strtoupper($status)!='RETURN TO SHIPPER'){
					$receiveddate = 'NULL';
					$receivedby = 'NULL';
					$personnel = 'NULL';
				}
				else{
					$dash = trim($remarks)==''?'':'  - ';
					$remarks = $remarks."$dash Received by: $receivedby, Received Date: $receiveddate";
				}

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$waybillstathistory = new txn_waybill_status_history();
				$waybillnumber = '';
				$statusdesc = '';
				$currentwaybillstatus = '';

				$mftnumber = '';
				$ldpnumber = '';



				$userstatusupdate = hasAccess(USERID,'#waybill-trans-updatestatusbtn');
				if($userstatusupdate==1||USERID==1){
						
			
					$checkstatusrs = query("select * from status where code='$status' and type='WAYBILL'");

					if(getNumRows($checkstatusrs)==1){

						while($obj=fetch($checkstatusrs)){
							$statusdesc = $obj->description;
						}
						

						$checktxnrs = query("select * from txn_waybill where id='$waybillid'");

						if((validateDate($checkdate)==1&&$checkdate!='1970-01-01')||$status!='DEL'){

							if(getNumRows($checktxnrs)==1){

								while($obj=fetch($checktxnrs)){
										$waybillnumber = $obj->waybill_number;
										$currentwaybillstatus = $obj->status;
								}

								$checkifstatusupdateallowedrs = query("select * from txn_waybill
									                                   where id='$waybillid' and 
									                                         status not in (select status from no_update_status)");

								if(getNumRows($checkifstatusupdateallowedrs)>0){
								
									if(strtoupper($currentwaybillstatus)!=strtoupper($statusdesc)){

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

											echo "inpendingmanifesttxn@$mftnumber@$statusdesc@inpendingmanifesttxn";

										}
										else{

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

												echo "inpendingloadplantxn@$ldpnumber@$statusdesc@inpendingloadplantxn";
											}		
											else{
											
												/**** OK ****/
													$rcvcols = ", received_by=NULL, received_date=NULL";
													if($receiveddate!='NULL'||$receivedby!='NULL'){
														$rcvcols = ", received_by='$receivedby', received_date='$receiveddate'";
													}

													$rs = query("update txn_waybill set status='$statusdesc', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' $rcvcols where id='$waybillid'");
													if($rs){
														$waybillstathistory->insert(array('',$waybillnumber,$status,$statusdesc,$remarks,$now,$userid,'NULL','NULL','NULL',$receivedby,$receiveddate,$personnel));
														$systemlog = new system_log();
														$systemlog->logInfo('WAYBILL',"Waybill Status Update: $status","Waybill Number: ".$waybillnumber." | Status: $status - $statusdesc  |  Remarks: $remarks",$userid,$now);
														echo "success";
													}
												
												/**** OK *****/

											}




										}
									}
									else{
										echo "samestatus";
									}

								}
								else{
									echo "nostatusupdate";
								}
							

							}
							else{
								echo "invalidwaybill";
							}
						}
						else{
							echo "invalidreceiveddate";
						}
					}
					else{
						echo "invalidstatus";
					}

				}
				else{
					echo "usernoaccess";
				}

				
				
		}
	}


	if(isset($_POST['AddWaybillPackageCode'])){
		if($_POST['AddWaybillPackageCode']=='#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s'){

				$waybillnumber = escapeString($_POST['waybillnumber']);
				$code = escapeString(strtoupper($_POST['code']));
				$code = str_replace(' ', '', $code);
				$userid = USERID;
				$now = date('Y-m-d H:i:s');


				$checkcoders = query("select * from txn_waybill_package_code where code='$code'");

				if(getNumRows($checkcoders)>0){
					echo "codeexist";
				}
				else{
					$rs = query("insert into txn_waybill_package_code(code,waybill_number,created_by,created_date) values('$code','$waybillnumber','$userid','$now')");

					if($rs){
						$systemlog = new system_log();
						$systemlog->logInfo('WAYBILL',"Add Package Code","Waybill Number: $waybillnumber | Code: $code",$userid,$now);
						echo "success";
					}
					else{
						echo mysql_error();
					}
				}




		}
	}


	if(isset($_POST['deletePackageCodes'])){
		if($_POST['deletePackageCodes']=='#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s'){

			$rowid = $_POST['rowid'];
			$waybillnumber = escapeString($_POST['waybillnumber']);
			$rowid = implode("','", $rowid);
			$deletedpackages = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			
			$rs = query("select group_concat(code) as packagecodes from txn_waybill_package_code where id in ('$rowid')");

			while($obj=fetch($rs)){
				$deletedpackages = $obj->packagecodes;
			}
			

			$rs = query("delete from txn_waybill_package_code where id in ('$rowid')");
			if($rs){	
				$systemlog = new system_log();
				$systemlog->logInfo('WAYBILL',"Delete Package Code","Waybill Number: $waybillnumber | Deleted Packages: $deletedpackages",$userid,$now);
				echo "success";

			}
			else{
				echo mysql_error();
			}

			

		}
	}


	if(isset($_POST['getHandlingInstructions'])){
		if($_POST['getHandlingInstructions']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$txnnumber = escapeString($_POST['txnnumber']);
			$instructions = getWaybillHandlingInstructions($txnnumber);

			$dataarray = array(
								 "instructions"=>$instructions
							  );
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['getShipperPODInstruction'])){
		if($_POST['getShipperPODInstruction']=='#@1F4fdgrw$ghjt3K@3#4hh$9v7&3s'){
			$shipperid = escapeString($_POST['shipperid']);

			$instruction = '';
			$rs = query("select * from shipper where id='$shipperid'");
			while($obj=fetch($rs)){
				$instruction = $obj->pod_instruction;
			}

			$dataarray = array(
								 "instruction"=>$instruction
							  );
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['changeBillingFlagging'])){
        if($_POST['changeBillingFlagging']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
                $id = escapeString($_POST['txnid']);
                $txnnumber = escapeString($_POST['txnnumber']);
                $flag = escapeString($_POST['billingflag']);
                $reference = escapeString(trim(strtoupper($_POST['billingreference'])));
                $remarks = escapeString($_POST['remarks']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
                $waybillbillhistory = new txn_waybill_billing_history();
            

                $checktxnrs = query("select * from txn_waybill where id='$id' and waybill_number='$txnnumber'");


                if(getNumRows($checktxnrs)==1){

                	if(($flag==1&&trim($reference)!='')||$flag==0){

                		$ref = $flag==0?'NULL':"'".$reference."'";

                	

                        $rs = query("update txn_waybill 
                        	         set billed_flag='$flag', 
                        	             billing_reference=$ref
                        	         where id='$id'");
                        if($rs){

                        	query("update txn_billing_waybill 
                        		   left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
                        		   set txn_billing_waybill.flag=0 
                        		   where txn_billing_waybill.waybill_number='$txnnumber' and
                        		         txn_billing.status='POSTED' and
                        		         txn_billing_waybill.billing_number!='$reference'");

                        	$ref = $flag==0?'NULL':$reference;
                        	$waybillbillhistory->insert(array('',$flag,$txnnumber,$ref,'NULL',$remarks,$now,$userid));
                            $systemlog->logInfo('WAYBILL','Changed Billing Flag',"Waybill No.: ".$txnnumber." | Flag: $flag | Reference: $reference | Remarks: $remarks",$userid,$now);
                            echo "success";
                        }
                    }
                    else{
                    	echo "noreference";
                    }
                    
                }
                else{
                    echo "invalidtransaction";
                }

                
                
        }
    }

    if(isset($_POST['deleteWaybillStatusHistory'])){
        if($_POST['deleteWaybillStatusHistory']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){


        		$id = escapeString($_POST['rowid']);
                $remarks = escapeString($_POST['remarks']);

                $now = date('Y-m-d H:i:s');
                $userid = USERID;
                $systemlog = new system_log();
                $waybillnumber = '';

                $latestID = '';

                $checkpermission = userAccess(USERID,'.deletewaybillstatushistorybtn');

                if($checkpermission==false&&USERID==1){
	                if(trim($remarks)==''){
	                	echo "noreasonprovided";
	                }
	                else{
	                	$rs = query("select * from txn_waybill_status_history where id='$id'");
	                	if(getNumRows($rs)>0){
	              
		                		while($obj=fetch($rs)){
		                			$waybillnumber = $obj->waybill_number;
		                		}



		                		$checklatesthistory = query("select id 
		                		 	                          from txn_waybill_status_history 
		                		 	                          where waybill_number='$waybillnumber' 
		                		 	                          order by created_date desc 
		                		 	                          limit 1");
		                		while($obj=fetch($checklatesthistory)){
		                		 	$latestID = $obj->id;
		                		}

		                		if($latestID!=$id){
			                		/** OK **/
			                		$rs = query("insert into 
		                					     txn_waybill_status_history_deleted (	
		                					         										
																							row_id,
																							waybill_number,
																							status_code,
																							status_description,
																							remarks,
																							created_date,
																							created_by,
																							location_id,
																							source,
																							source_type,
																							received_by,
																							received_date,
																							reason,
																							deleted_by,
																							personnel_id
		                					                                        )
		                					                                  select        id,
		                					                                                waybill_number,
																							status_code,
																							status_description,
																							remarks,
																							created_date,
																							created_by,
																							location_id,
																							source,
																							source_type,
																							received_by,
																							received_date,
																							'$remarks',
																							'$userid',
																							personnel_id
																				 from txn_waybill_status_history
																				 where id='$id'

		                					        ");
			                		
		                			
		                			if($rs){
		                				$rs = query("delete from txn_waybill_status_history where id='$id'");

		                				if($rs){
		                						$systemlog->logInfo('WAYBILL STATUS HISTORY','Deleted Status History',"Waybill Number: ".$waybillnumber." | Reason: $remarks",$userid,$now);
		                						echo "success";
		                				}
		                			}
		                			/** OK **/
		                		}
		                		else{
		                			echo "lateststatus";
		                		}
	                	}
	                	else{
	                		echo "invalidrowid";
	                	}

	                }
	            }
	            else{
	            	echo "nopermission";
	            }
        }
    }



    if(isset($_POST['getExpressValforModeAndServices'])){
		if($_POST['getExpressValforModeAndServices']=='sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7'){

			$motexpressid = '';
			$motexpress = '';
			$srvexpressid = '';
			$srvexpress = '';


			$rs = query("select * from mode_of_transport where description='EXPRESS'");
			while($obj=fetch($rs)){
				$motexpressid = $obj->id;
				$motexpress = $obj->description;
			}

			$rs = query("select * from services where description='EXPRESS'");
			while($obj=fetch($rs)){
				$srvexpressid = $obj->id;
				$srvexpress = $obj->description;
			}

			$response = array(
									   "motexpressid"=>$motexpressid,
									   "motexpress"=>utfEncode($motexpress),
									   "srvexpressid"=>$srvexpressid,
									   "srvexpress"=>utfEncode($srvexpress)
								);
			print_r(json_encode($response));



		}
	}



	if(isset($_POST['ConfirmEditedCharges'])){
		if($_POST['ConfirmEditedCharges']=='D#@ihQnsRPFG%$po92po@k@'){
			$waybillnumber = escapeString($_POST['waybillnumber']);
			$checkpermission = hasAccess(USERID,'#waybill-trans-editwaybillchargesbtn');
			$totalamount = escapeString(round($_POST['totalamount'],2));
			$billing = '';

	
			$shipperid = '';
			$creditlimit = 0;
			$oldtotalamount = 0;

			$checktxnrs = query("select txn_waybill.status, 
											txn_waybill.created_by, 
											txn_waybill.shipper_id,
											txn_waybill.total_amount,
											shipper.credit_limit
									from txn_waybill 
									left join shipper on shipper.id=txn_waybill.shipper_id
									where txn_waybill.waybill_number='$waybillnumber'");
			while($obj=fetch($checktxnrs)){
				$shipperid = $obj->shipper_id;
				$creditlimit = $obj->credit_limit>0?$obj->credit_limit:0;
				$oldtotalamount = $obj->total_amount>0?$obj->total_amount:0;
			}


            if($checkpermission==1){

            	$shipperbalance = getShipperOutstandingBalance($shipperid);
            	$shipperbalance = $shipperbalance-$oldtotalamount;
				$creditlimitavailable = $creditlimit-$shipperbalance;
				$expshipperbalance = $shipperbalance+$totalamount;


				if(str_replace(',', '', $creditlimit)>=str_replace(',', '', $expshipperbalance)){
				

	            	$checkinbillingrs = query("select * from txn_billing_waybill
	            							   left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
	            		                       where txn_billing_waybill.waybill_number='$waybillnumber' and
	            		                             txn_billing.status!='VOID' and
	            		                             txn_billing_waybill.flag=1
	            							  ");
	            	if(getNumRows($checkinbillingrs)>0){
	            		while($obj=fetch($checkinbillingrs)){
	            			$billing = $obj->billing_number;
	            		}

	            		$response = array(
							"response"=>'inactivebilling',
							"txnnumber"=>$waybillnumber,
							"billingnumber"=>$billing
						);
	            	}
	            	else{
						$freightcomputation = 'NULL';
						$chargeableweight = 'NULL';
						$minimumrate = 0;
						
						$chargeableweight = escapeString(round($_POST['chargeableweight'],4));
						$chargeableweight = $chargeableweight>=0?$chargeableweight:'NULL';
						$returndocumentfee = escapeString(round($_POST['returndocumentfee'],2));
						$waybillfee = escapeString(round($_POST['waybillfee'],2));
						$securityfee = escapeString(round($_POST['securityfee'],2));
						$docstampfee = escapeString(round($_POST['docstampfee'],2));
						$insurancerate = escapeString(round($_POST['insurancerate'],2));
						$fuelrate = escapeString(round($_POST['fuelrate'],2));
						$freightcharge = escapeString(round($_POST['freight'],2));
						$bunkerrate = escapeString(round($_POST['bunkerrate'],2));
						$oda = escapeString(round($_POST['oda'],2));
						$valuation = escapeString(round($_POST['valuation'],2));
					    $zeroratedflag = escapeString($_POST['zeroratedflag']);
						$zeroratedflag =strtoupper($zeroratedflag)=='TRUE'?1:0;
						$totalhandlingcharges = escapeString(round($_POST['totalhandlingcharges'],2));
					  	
					    $fixedrateamount = escapeString($_POST['fixedrateamount']);
				       
				        $totalregularcharges = escapeString($_POST['totalregularcharges']);
					    $totalotherchargesvatable = escapeString($_POST['totalotherchargesvatable']);
					    $subtotal = escapeString($_POST['subtotal']);
					    $vat = escapeString(round($_POST['vat'],2));
						$totalotherchargesnonvatable = escapeString($_POST['totalotherchargesnonvatable']);
						$totalamount = escapeString(round($_POST['totalamount'],2));



						$odaflag = 0;
						if($oda>0){
							$odaflag = 1;
						}


						@$handlinginstructionarray = $_POST['handlinginstruction'];
						@$otherchargesiterate = count($_POST['linedesc']);
						@$linedesc = $_POST['linedesc'];
						@$lineamount = $_POST['lineamount'];
						@$linevatflag = $_POST['linevatflag'];

						$getOldValrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");

						if(getNumRows($getOldValrs)==1){

							while($obj=fetch($getOldValrs)){
									$waybilltype = $obj->waybill_type;
									$oldfreightcomputation = $obj->package_freight_computation;
									$oldminimumcharges = $obj->package_minimum_rate;
									$oldchargeableweight = $obj->package_chargeable_weight;
									$oldfreightcharge = $obj->package_freight;

									$oldreturndocumentfee = $obj->return_document_fee;
									$oldwaybillfee =  $obj->waybill_fee;
									$oldsecurityfee =  $obj->security_fee;
									$olddocstampfee =  $obj->doc_stamp_fee;
									$oldinsurancerate =  $obj->package_insurance_rate;
									$oldfuelrate =  $obj->package_fuel_rate;
									$oldbunkerrate =  $obj->package_bunker_rate;
									$oldoda =  $obj->oda_charges;
									$oldvaluation =  $obj->package_valuation;
								    $oldzeroratedflag =  $obj->zero_rated_flag;
									$oldtotalhandlingcharges =  $obj->total_handling_charges;
								  
								    $oldfixedrateamount =  $obj->fixed_rate_amount;
							       
							        $oldtotalregularcharges =  $obj->total_regular_charges;
								    $oldtotalotherchargesvatable =  $obj->total_other_charges_vatable;
								    $oldsubtotal =  $obj->subtotal;
								    $oldvat =  $obj->vat;
									$oldtotalotherchargesnonvatable =  $obj->total_other_charges_non_vatable;
									$oldtotalamount =  $obj->total_amount;
									$oldodaflag = $obj->oda_flag;
							}

							if($waybilltype=='DOCUMENT'){
								$insurancerate = 0;
								$totalhandlingcharges = 0;
								$fixedrateamount = 0;
							}

							$now = date('Y-m-d H:i:s');
							$userid = USERID;

							$rs = query("update txn_waybill
												set package_freight_computation=NULL,
												    package_minimum_rate=0,
												    package_freight = $freightcharge,
												    package_chargeable_weight=$chargeableweight,
												    return_document_fee='$returndocumentfee',
												    waybill_fee='$waybillfee',
												    security_fee='$securityfee',
												    doc_stamp_fee='$docstampfee',
												    package_insurance_rate='$insurancerate',
												    package_fuel_rate='$fuelrate',
												    package_bunker_rate='$bunkerrate',
												    oda_flag='$odaflag',
												    shipper_oda_rate='$oda',
												    oda_charges='$oda',
												    package_valuation='$valuation',
												    total_handling_charges='$totalhandlingcharges',
												    fixed_rate_amount='$fixedrateamount',
												    total_regular_charges='$totalregularcharges',
												    total_other_charges_vatable='$totalotherchargesvatable',
												    subtotal='$subtotal',
												    vat='$vat',
												    total_other_charges_non_vatable='$totalotherchargesnonvatable',
												    total_amount='$totalamount',
												    zero_rated_flag='$zeroratedflag'
												where waybill_number='$waybillnumber'
								              ");

							if($rs){
								$systemlog = new system_log();
								$systemlog->logInfo('WAYBILL','Edited Waybill Charges',"Waybill Number: ".$waybillnumber." - OLD VALUES > Freight Computation: $oldfreightcomputation | Chargeable Weight: $oldchargeableweight | Minimum Charges: $oldminimumcharges | Freight Charge: $oldfreightcharge | Return Document Fee: $oldreturndocumentfee | Waybill Fee: $oldwaybillfee | Security Fee: $oldsecurityfee | Doc Stamp Fee: $olddocstampfee | Insurance Charges: $oldinsurancerate | Fuel Charges: $oldfuelrate | Bunker Charges: $oldbunkerrate | ODA Flag: $oldodaflag | ODA Charges: $oldoda | Valuation: $oldvaluation | Handling Charges: $oldtotalhandlingcharges | Fixed Charges: $oldfixedrateamount | Total Regular Charges: $oldtotalregularcharges | Total Other Charges - Vatable: $oldtotalotherchargesvatable | Total Vatable Charges: $oldsubtotal | Total Other Charges - Non Vatable: $oldtotalotherchargesnonvatable | Vat: $oldvat | Total Amount: $oldtotalamount | Zero Rated Flag: $oldzeroratedflag; NEW VALUES > Freight Computation: $freightcomputation | Chargeable Weight: $chargeableweight | Minimum Charges: $minimumrate | Freight Charge: $freightcharge | Return Document Fee: $returndocumentfee | Waybill Fee: $waybillfee | Security Fee: $securityfee | Doc Stamp Fee: $docstampfee | Insurance Charges: $insurancerate | Fuel Charges: $fuelrate | Bunker Charges: $bunkerrate | ODA Flag: $odaflag | ODA Charges: $oda | Valuation: $valuation | Handling Charges: $totalhandlingcharges | Fixed Charges: $fixedrateamount | Total Regular Charges: $totalregularcharges | Total Other Charges - Vatable: $totalotherchargesvatable | Total Vatable Charges: $subtotal | Total Other Charges - Non Vatable: $totalotherchargesnonvatable | Vat: $vat | Total Amount: $totalamount | Zero Rated Flag: $zeroratedflag;",$userid,$now);

								/******* OTHER CHARGES *****/
								$waybillotherchargesclass = new txn_waybill_other_charges();
								$otherchargesdata = array();
								for($i=0;$i<$otherchargesiterate;$i++){
									$temp = array();
									$vatflag = trim(strtoupper($linevatflag[$i]))=='YES'?1:0;

									array_push($temp,
										NULL,
										$waybillnumber,
										escapeString($linedesc[$i]),
										escapeString(str_replace(',','',$lineamount[$i])),
										$vatflag
										);
									array_push($otherchargesdata, $temp);
								}
								$waybillotherchargesclass->deleteWhere("where waybill_number='".$waybillnumber."'");
								if($otherchargesiterate>0){
									$waybillotherchargesclass->insertMultiple($otherchargesdata);
								}
								/**** OTHER CHARGES - END ****/


								/**** HANDLING INSTRUCTION ***/
								$handlinginstructionclass = new txn_waybill_handling_instruction();
								$handlinginstructionclass->deleteWhere("where waybill_number='".$waybillnumber."'");
								$hidata = array();

								if($_POST['handlinginstruction']!=null){
									for($i=0;$i<count($handlinginstructionarray);$i++){
										$handlingins = array();
										array_push($handlingins, '', $waybillnumber, $handlinginstructionarray[$i]);
										array_push($hidata, $handlingins);
									}
									if(count($handlinginstructionarray)>0){
										$handlinginstructionclass->insertMultiple($hidata);
									}
								}
								/**** HANDLING INSTRUCTION - END ***/




								$response = array(
									"response"=>'success',
									"txnnumber"=>$waybillnumber
								);
							}
							echo mysql_error();

						}
						else{
							$response = array(
								"response"=>'invalidwaybill',
								"txnnumber"=>$waybillnumber
							);
						}
					}

				}
				else{	
					$creditlimitavailable = convertWithDecimal($creditlimitavailable,5);
					$response = array(
											   
									 "response"=>'exceedscreditlimit',
									 "creditlimitavailable"=>$creditlimitavailable,
									 "expshipperbalance"=>$expshipperbalance,
									 "creditlimit"=>floatval($creditlimit)
								   );
				}

			}
			else{
				$response = array(
						"response"=>'nopermission',
						"txnnumber"=>$waybillnumber
				);
			}

			echo json_encode($response);


			



		}
	}




	if(isset($_POST['checkifviewchargesallowed'])){
		if($_POST['checkifviewchargesallowed']=='KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$'){
			$viewwaybillchargesaccess = hasAccess(USERID,'#viewwaybillcharges');
			if($viewwaybillchargesaccess==1){
				$viewwaybillchargesflag='true';
			}
			else{
				$viewwaybillchargesflag='false';
			}

			$viewwaybillcostingaccess = hasAccess(USERID,'#viewwaybillcosting');
			if($viewwaybillcostingaccess==1){
				$viewwaybilcostingflag='true';
			}
			else{
				$viewwaybilcostingflag='false';
			}

			$response = array(
									   "viewwaybillcostingflag"=>$viewwaybilcostingflag,
									   "viewwaybillchargesflag"=>$viewwaybillchargesflag
								);
			print_r(json_encode($response));
		}
	}


	if(isset($_POST['increaseprintcounter'])){
		if($_POST['increaseprintcounter']=='KFHoEO#0HELKN#Opsy#lka$P#HlNlk!I#H$'){
			$waybillid = escapeString($_POST['waybillid']);
			$now = date('Y-m-d H:i:s');
			$userid = USERID;
			$printcounter = 0;
			$status = '';
			$wbnumber = '';
			$checkifprintedrs = query("select * from txn_waybill where id='$waybillid'");
			while($obj=fetch($checkifprintedrs)){
				$printcounter = $obj->print_counter;
				$status = $obj->status;
				$wbnumber = $obj->waybill_number;
			}

			if(getNumRows($checkifprintedrs)==1){
				if($status!='LOGGED'&&$status!='VOID'){

					if($printcounter>0){
						$rs = query("update txn_waybill set print_counter=print_counter+1,printed_by='$userid' where id='$waybillid'");
					}
					else{
						$rs = query("update txn_waybill 
							         set print_counter=print_counter+1, 
							             printed_by='$userid', 
							             printed_date='$now' 
							         where id='$waybillid'");
					}

					

					if($rs){
						$waybillprinthistory = new txn_waybill_print_history();
						$systemlog = new system_log();
						$systemlog->logInfo('WAYBILL','Printing',"Waybill Number: ".$wbnumber." | Print Counter: $printcounter",$userid,$now);
						$waybillprinthistory->insert(array('',$wbnumber,$printcounter,$userid,$now));
						echo "success";
					}
					else{
						echo "failed";
					}
				}
				else if($status=='LOGGED'){
					echo "loggedstatus";
				}
			}
			else{
				echo "invalidwaybill";
			}



		}
	}


	if(isset($_POST['resetPrintCounter'])){
		if($_POST['resetPrintCounter']=='sdfed#n2L1hfi$n#opi3opod30napri'){

			$remarks = escapeString($_POST['remarks']);
			$waybillnumber = escapeString($_POST['waybillnumber']);
			$resetaccess = hasAccess(USERID,'#waybill-trans-resetprintcounterbtn');

			if($resetaccess==1){
				$checkrs = query("select * from txn_waybill where waybill_number='$waybillnumber'");

				if(getNumRows($checkrs)==1){
					$systemlog = new system_log();
					$now = date('Y-m-d H:i:s');
					$userid = USERID;

					$rs = query("update txn_waybill set print_counter=NULL, printed_by=NULL, printed_date=NULL where waybill_number='$waybillnumber'");

					if($rs){
						$systemlog->logInfo('WAYBILL','Print Counter Reset',"Waybill Number: ".$waybillnumber." | Remarks: $remarks",$userid,$now);
						echo "success";
					}

					

				}
				else{
					echo "invalidwaybill";
				}
			}
			else{
				echo "noaccess";
			}

		}
	}


	if(isset($_POST['getAccompanyingDocuments'])){
		if($_POST['getAccompanyingDocuments']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$txnnumber = escapeString($_POST['txnnumber']);
			$descriptions = getWaybillAccompanyingDocuments($txnnumber);

			$dataarray = array(
								 "descriptions"=>$descriptions
							  );
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['saveWaybillCostingDetails'])){
		if($_POST['saveWaybillCostingDetails']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){
			$txnid = escapeString($_POST['txnid']);
			$txnnumber = escapeString($_POST['txnnumber']);
			$freightcost = (trim($_POST['freightcost'])==''||trim($_POST['freightcost'])<0)?'NULL':"'".escapeString($_POST['freightcost'])."'";
			$billreference = trim($_POST['billreference'])==''?'NULL':"'".escapeString($_POST['billreference'])."'";
			$agentcost = (trim($_POST['agentcost'])==''||trim($_POST['agentcost'])<0)?'NULL':"'".escapeString($_POST['agentcost'])."'";
			$billitemnumber = trim($_POST['billitemnumber'])==''?'NULL':"'".escapeString($_POST['billitemnumber'])."'";
			$insuranceamount = (trim($_POST['insuranceamount'])==''||trim($_POST['insuranceamount'])<0)?'NULL':"'".escapeString($_POST['insuranceamount'])."'";
			$insurancereference = trim($_POST['insurancereference'])==''?'NULL':"'".escapeString($_POST['insurancereference'])."'";
			$freightcharge = 0;
			$editcosting = hasAccess(USERID,'#waybill-trans-editwaybillcostingbtn');


			if($editcosting==1){
				$checktxnrs = query("select * from txn_waybill where id='$txnid'");
				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$freightcharge = $obj->package_freight;
					}
					$grossincome = $freightcharge-($freightcost+$agentcost+$insuranceamount);

					$rs = query("update txn_waybill 
							     set freight_cost=$freightcost,
							         bill_reference=$billreference,
							         agent_cost=$agentcost,
							         bill_item_number=$billitemnumber,
							         insurance_amount=$insuranceamount,
							         insurance_reference=$insurancereference
							     where id='$txnid'");

					$systemlog = new system_log();
					$now = date('Y-m-d H:i:s');
					$userid = USERID;

					if($rs){
						$systemlog->logInfo('WAYBILL','Edited Costing',"Waybill Number: ".$txnnumber." | ID: $txnid | Freight Cost: ".trim($freightcost,"'")." | Bill Reference: ".trim($billreference,"'")." | Agent Cost: ".trim($agentcost,"'")." | Bill Item No.: ".trim($billitemnumber,"'")." | Insurance Amount: ".trim($insuranceamount,"'")." | Insurance Reference: ".trim($insurancereference,"'")." | Gross Amount: $grossincome",$userid,$now);
						$dataarray = array(
											 "response"=>'success'
										  );
					}
					
				}		
				else{
					$dataarray = array(
								 "response"=>'invalidtransaction'
							  );
				}
				
			}
			else{
				$dataarray = array(
								 "response"=>'nopermission'
							  );
			}
			echo json_encode($dataarray);


		}
	}

	if(isset($_POST['checkIfUserCanAddNewConsignee'])){
		if($_POST['checkIfUserCanAddNewConsignee']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){

			$userhasaccess = hasAccess(USERID,'.addconsigneebtn');
			echo $userhasaccess;

		}
	}


	if(isset($_POST['AddNewConsignee'])){
		if($_POST['AddNewConsignee']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){

			$accountname = escapeString(strtoupper($_POST['accountname']));
			$companyname = escapeString(strtoupper($_POST['companyname']));
			$contact = trim($_POST['contact'])==''?'N/A':escapeString($_POST['contact']);
			$tel = trim($_POST['tel'])==''?'NULL':escapeString($_POST['tel']);
			$mobile = trim($_POST['mobile'])==''?'NULL':escapeString($_POST['mobile']);
			$street = trim($_POST['street'])==''?'NULL':escapeString($_POST['street']);
			$district = trim($_POST['district'])==''?'NULL':escapeString($_POST['district']);
			$city = trim($_POST['city'])==''?'NULL':escapeString($_POST['city']);
			$province = trim($_POST['region'])==''?'NULL':escapeString($_POST['region']);
			$zipcode = trim($_POST['zipcode'])==''?'NULL':escapeString($_POST['zipcode']);
			$country = trim($_POST['country'])==''?'NULL':escapeString($_POST['country']);

			$userhasaccess = hasAccess(USERID,'.addconsigneebtn');

			if($userhasaccess==1){

				$checkifexistrs = query("select * from consignee where account_name='$accountname'");

				if(getNumRows($checkifexistrs)>0){
					echo "consigneeexists";
				}
				else{
						$userid = USERID;
						$now = date('Y-m-d H:i:s');
						$consigneeclass = new consignee();
						$consigneecontactclass = new consignee_contact();
						$systemlog = new system_log();

						$accountnumber = getTransactionNumber(7);
						$consigneeclass->insert(array('',$accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,$userid,$now,'NULL','NULL',0));
						$id = $consigneeclass->getInsertId();
						$systemlog->logAddedInfo($consigneeclass,array($id,$accountnumber,$accountname,$companyname,$street,$district,$city,$province,$zipcode,$country,$userid,$now,'NULL','NULL',0),'CONSIGNEE','New Consignee Added (Waybill Module)',$userid,$now);


						if(trim($tel)!='NULL'||trim($mobile)!='NULL'){
							$consigneecontactclass->insert(array('',$id,$contact,$tel,'NULL',$mobile,$now,1,1,1));
						}
						echo "success@#$%&".$id;
				}
				
			}
			else{
				echo "noaccess";
			}

		}
	}



	if(isset($_POST['checkWaybillEnableEdit'])){
		if($_POST['checkWaybillEnableEdit']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

				$wbnumber = escapeString(strtoupper($_POST['wbnumber']));
				$manifest = '';

				$checkinactivemanifestrs = query("select txn_manifest.status,
				                                         txn_manifest.manifest_number 
					                              from txn_manifest_waybill 
					                              left join txn_manifest 
					                              on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
					                              where txn_manifest_waybill.waybill_number='$wbnumber' and 
					                                    txn_manifest.status='LOGGED'");
				if(getNumRows($checkinactivemanifestrs)>0){
					while($obj=fetch($checkinactivemanifestrs)){
						$manifest = $obj->manifest_number;
					}
					$dataarray = array(
								 		"response"=>'inactivemanifesttransaction',
								 		"manifest"=>$manifest
							  		  );
				}
				else{

					$checkinactivebillingrs = query("select txn_billing.billing_number
						                             from txn_billing_waybill
						                             left join txn_billing on txn_billing.billing_number=txn_billing_waybill.billing_number
						                             where txn_billing.status!='VOID' and 
						                             	   txn_billing_waybill.waybill_number='$wbnumber'");
					if(getNumRows($checkinactivebillingrs)>0){
						while($obj=fetch($checkinactivebillingrs)){
							$billing = $obj->billing_number;
						}

						$dataarray = array(
									 		"response"=>'inactivebillingtransaction',
									 		"billing"=>$billing
								  		  );
					}
					else{

						$dataarray = array(
									 "response"=>'success'
								  );
					}
				}
				echo json_encode($dataarray);

		}
	}

?>