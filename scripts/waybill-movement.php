<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/waybill-movement.class.php");
    include("../classes/waybill-movement-waybill.class.php");
    include("../classes/waybill-movement-package-code.class.php");
    include("../classes/waybill-status-history.class.php");
    include("../classes/system-log.class.php");////////
	
	if(isset($_POST['saveNewWaybillMovementTransaction'])){
		if($_POST['saveNewWaybillMovementTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$response = array();
			$movementtype = escapeString($_POST['movementtype']);
			$documentdate = dateString($_POST['documentdate']);
			$location = escapeString($_POST['location']);
			$remarks = escapeString($_POST['remarks']);
			$wbmnumber = getTransactionNumber(3);
			$now = date("Y-m-d H:i:s");
			$userid = USERID;

			$wbmclass = new txn_waybill_movement();
			$systemlog = new system_log();

			$locationcode = getInfo("location","code","where id='$location'");
			$movementtypedesc = getInfo("movement_type","description","where id='$movementtype'");

			$wbmclass->insert(array('',$wbmnumber,'LOGGED',$location,$remarks,$documentdate,$now,$userid,'NULL','NULL',$movementtype));
			$systemlog->logInfo('WAYBILL MOVEMENT',"New Waybill Movement","Waybill Movement No.: $wbmnumber | Movement Type: $movementtypedesc | Location: $locationcode | Remarks: $remarks | Document Date: $documentdate",$userid,$now);
			$response = array(
									   "response"=>'success',
									   "txnnumber"=>$wbmnumber
							 );
			print_r(json_encode($response));

		



		}
	}

	if(isset($_POST['getReference'])){
		if($_POST['getReference']=='FOio5ja3op2a2lK@3#4hh$93s'){
			$source = escapeString($_POST['source']);
			$id = escapeString($_POST['id']);
			

			$query = '';



			if($source=='first'){
				$query = "select * from txn_waybill_movement order by id asc limit 1";
			}
			else if($source=='second' && $id!=''){
				$query = "select * from txn_waybill_movement where id < $id order by id desc limit 1";
			}
			else if($source=='third' && $id!=''){
				$query = "select * from txn_waybill_movement where id > $id order by id asc limit 1";
			}
			else if($source=='fourth'){
				$query = "select * from txn_waybill_movement order by id desc limit 1";
			}
			else if($id==''){
				$query = "select * from txn_waybill_movement order by id asc limit 1";
			}
			if($query!=''){
				$rs = query($query);
				if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->waybill_movement_number;
				}
				else{
					$rs = query("select * from txn_waybill_movement where id='$id'");
					if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->waybill_movement_number;
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

	if(isset($_POST['postWaybillMovementTransaction'])){
		if($_POST['postWaybillMovementTransaction']=='oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){
				$id = escapeString($_POST['id']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$checktxnrs = query("select txn_waybill_movement.status, 
					                        txn_waybill_movement.created_by, 
					                        txn_waybill_movement.remarks,
					                        txn_waybill_movement.location_id,
					                        txn_waybill_movement.movement_type_id,
					                        movement_type.description as movementtype 
					                 from txn_waybill_movement 
					                 left join movement_type on movement_type.id=txn_waybill_movement.movement_type_id
					                 where waybill_movement_number='$txnnumber'");
				$status = '';
				$data = array();
				$createdby = 'none';
				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();

				$movementtype = '';
				$movementtypeid = '';
			

				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$status = $obj->status;
						$createdby = $obj->created_by;
						$movementtype = strtoupper($obj->movementtype);
						$movementtypeid = $obj->movement_type_id;
						$wbmlocid = $obj->location_id;
						$wbstatremarks = trim($obj->remarks);
					}

					if($status=='LOGGED'){

						$checkaddedwaybillrs = query("select * from txn_waybill_movement_waybill where waybill_movement_number='$txnnumber'");


						if(getNumRows($checkaddedwaybillrs)>0){

							$checkaddedwaybillrs = query("select txn_waybill_movement_waybill.waybill_number
						                              from txn_waybill_movement_waybill 
						                              left join txn_waybill on txn_waybill.waybill_number=txn_waybill_movement_waybill.waybill_number
						                              where txn_waybill_movement_waybill.waybill_movement_number='$txnnumber' and 
						                                    txn_waybill.status not in (select status from no_update_status) and 
						                                    txn_waybill.status in (select source_movement 
					                            								   from movement_type_source 
					                             									where movement_type_id='$movementtypeid')");
							//and txn_waybill.status not in (select status from no_update_status)
							//txn_waybill.status!='DELIVERED' and txn_waybill.status!='RETURN TO SHIPPER' and txn_waybill.status!='RTS'

							$userhasaccess = hasAccess(USERID,'#useraccessmanagewaybillmovement');
							if(USERID==$createdby||USERID==1||$userhasaccess==1){
								$rs = query("update txn_waybill_movement set status='POSTED', updated_date='$now', updated_by='$userid' where id='$id'");
								if($rs){

									

									$waybillstathistory = new txn_waybill_status_history();
									while($obj=fetch($checkaddedwaybillrs)){
										$wbnumber = $obj->waybill_number;
										//$wbstatremarks = $obj->remarks;

										query("update txn_waybill set status='$movementtype', last_status_update_remarks='$wbstatremarks' where waybill_number='$wbnumber'");

										//$wbstatremarks = trim($wbstatremarks)==''?'':' - '.$wbstatremarks; 
										$waybillstathistory->insert(array('',$wbnumber,$movementtype,$movementtype,$wbstatremarks,$now,$userid,$wbmlocid,$txnnumber,'WAYBILL MOVEMENT','NULL','NULL','NULL'));

										
										$systemlog->logInfo('WAYBILL','Updated Waybill Status',"Waybill Number: ".$wbnumber."; Status: $movementtype; Remarks: SOURCE WAYBILL MOVEMENT[$txnnumber] $wbstatremarks",$userid,$now);
										
									}

									$systemlog->logInfo('WAYBILL MOVEMENT','Posted Waybill Movement',"Waybill Movement No.: ".$txnnumber."; Status: $movementtype",$userid,$now);

									$data = array("response"=>'success');

									
									



								}
							}
							else{
								$data = array("response"=>'noaccess');
							}


						}
						else{
							$data = array("response"=>'nowaybilladded');
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



	if(isset($_POST['getWaybillMovementData'])){
		if($_POST['getWaybillMovementData']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

			$txnnumber = escapeString($_POST['txnnumber']);
			$rs = query("select txn_waybill_movement.id,
				                txn_waybill_movement.waybill_movement_number,
				                txn_waybill_movement.status,
				                txn_waybill_movement.location_id,
				                txn_waybill_movement.remarks,
				                txn_waybill_movement.document_date,
				                txn_waybill_movement.created_date,
				                txn_waybill_movement.updated_date,
				                txn_waybill_movement.created_by,
				                txn_waybill_movement.updated_by,
				                concat(cuser.first_name,' ',cuser.last_name) as createdby,
				                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
				                location.code as loccode,
				                location.description as locdesc,
				                movement_type.description as movementtypedesc,
				                movement_type.code as movementtypecode
				         from txn_waybill_movement
				         left join user as cuser on cuser.id=txn_waybill_movement.created_by
				         left join user as uuser on uuser.id=txn_waybill_movement.updated_by
				         left join location on location.id=txn_waybill_movement.location_id
				         left join movement_type on movement_type.id=txn_waybill_movement.movement_type_id
				         where txn_waybill_movement.waybill_movement_number = '$txnnumber'");
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
					$updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
					$documentdate = dateFormat($obj->document_date, "m/d/Y");

					$userhasaccess = hasAccess(USERID,'#useraccessmanagewaybillmovement');
					if(USERID==$obj->created_by||USERID==1||$userhasaccess==1){
						$loggedequalcreated='true';
					}
					else{
						$loggedequalcreated='false';
					}


					$dataarray = array(
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "wbmnumber"=>utfEncode($obj->waybill_movement_number),
									   "locationid"=>utfEncode($obj->location_id),
									   "movementtype"=>utfEncode($obj->movementtypecode).' - '.utfEncode($obj->movementtypedesc),
									   "location"=>utfEncode($obj->loccode).' - '.utfEncode($obj->locdesc),
									   "remarks"=>utfEncode($obj->remarks),
									   "documentdate"=>utfEncode($documentdate),
									   "createddate"=>utfEncode($createddate),
									   "updateddate"=>utfEncode($updateddate),
									   "createdby"=>utfEncode($obj->createdby),
									   "updatedby"=>utfEncode($obj->updatedby),
									   "hasaccess"=>utfEncode($loggedequalcreated)
									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
		}
	}



	if(isset($_POST['insertNewWaybillNumber'])){
		if($_POST['insertNewWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$wbmnumber = escapeString($_POST['wbmnumber']);
			$wbnumber = escapeString($_POST['wbnumber']);
			$packagecodes = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$movementtypeid = '';
			$wbstatus = '';
			$wbmstatus = '';

			$checkifvalidwbmrs = query("select * from txn_waybill_movement where waybill_movement_number='$wbmnumber'");

			if(getNumRows($checkifvalidwbmrs)==1){

				while($obj=fetch($checkifvalidwbmrs)){
					$movementtypeid = $obj->movement_type_id;
					$wbmstatus = $obj->status;
				}

				if($wbmstatus=='LOGGED'){

					$checkifvalidwbrs = query("select * from txn_waybill where waybill_number='$wbnumber'");
					if(getNumRows($checkifvalidwbrs)==1){

						while($obj=fetch($checkifvalidwbrs)){
							$wbstatus = strtoupper($obj->status);
						}

						$checkifundermovementrs = query("select * from movement_type_source where upper(source_movement)='$wbstatus' and movement_type_id='$movementtypeid' and source_movement!=''");

						

						if(getNumRows($checkifundermovementrs)==1){


							$checkifaddedrs = query("select * from txn_waybill_movement_waybill where waybill_movement_number='$wbmnumber' and waybill_number='$wbnumber'");

							

							if(getNumRows($checkifaddedrs)==0){

							
								$rs = query("insert into txn_waybill_movement_package_code(
																							waybill_movement_number,
																							waybill_number,
																							package_code,
																							created_date,
																							created_by
																						   )
											 select '$wbmnumber',
											         waybill_number,
											         code,
											         '$now',
											         '$userid'
											 from txn_waybill_package_code
											 where waybill_number='$wbnumber' and 
											       code not in (select package_code from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber')"); //*************//
								if($rs){

									$rs1 = query("select group_concat(package_code) as packagecodes from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber' and waybill_number='$wbnumber'");
									while($obj1=fetch($rs1)){
										$packagecodes = $obj1->packagecodes;
									}


									$wbmwbclass = new txn_waybill_movement_waybill();
									$systemlog = new system_log();
									$systemlog->logInfo('WAYBILL MOVEMENT',"Added Waybill","Waybill Movement No.: $wbmnumber | Waybill No.: $wbnumber | Added Package Code(s): $packagecodes",$userid,$now);
									$wbmwbclass->insert(array('',$wbmnumber,$wbnumber,$now,$userid));
									echo "success";
								}
								else{
									echo mysql_error();
								}

							}
							else{
								echo "alreadyadded";
							}

						}
						else{
							echo "invalidwaybill";
						}

					}
					else{
						echo "invalidwaybill";
					}

				}
				else{
					echo "txnalreadyposted";
				}

			}
			else{
				echo "invalidwaybillmovement";
			}

			



		}
	}

	if(isset($_POST['insertMultipleWaybillNumber'])){
		if($_POST['insertMultipleWaybillNumber']=='oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$'){

			$wbmnumber = escapeString($_POST['wbmnumber']);
			$wbnumbers = $_POST['wbnumber'];
			$wbcount = count($wbnumbers);
			$failedinsertwb = array();
			$successinsertwb = array();
			$packagecodes = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;
			$response = array();

			$movementtypeid = '';
			$wbstatus = '';
			$wbmstatus = '';
			$failedmsg = '';

			$checkifvalidwbmrs = query("select * from txn_waybill_movement where waybill_movement_number='$wbmnumber'");

			if(getNumRows($checkifvalidwbmrs)==1){

				while($obj=fetch($checkifvalidwbmrs)){
					$movementtypeid = $obj->movement_type_id;
					$wbmstatus = $obj->status;
				}

				if($wbmstatus=='LOGGED'){

					for ($i=0; $i < $wbcount ; $i++) { 
						$wbnumber = escapeString($wbnumbers[$i]);

						$checkifvalidwbrs = query("select * from txn_waybill where waybill_number='$wbnumber'");
						if(getNumRows($checkifvalidwbrs)==1){

							while($obj=fetch($checkifvalidwbrs)){
								$wbstatus = strtoupper($obj->status);
							}

							$checkifundermovementrs = query("select * from movement_type_source where upper(source_movement)='$wbstatus' and movement_type_id='$movementtypeid' and source_movement!=''");

							

							if(getNumRows($checkifundermovementrs)==1){


								$checkifaddedrs = query("select * from txn_waybill_movement_waybill where waybill_movement_number='$wbmnumber' and waybill_number='$wbnumber'");

								

								if(getNumRows($checkifaddedrs)==0){

								
									$rs = query("insert into txn_waybill_movement_package_code(
																								waybill_movement_number,
																								waybill_number,
																								package_code,
																								created_date,
																								created_by
																							   )
												 select '$wbmnumber',
												         waybill_number,
												         code,
												         '$now',
												         '$userid'
												 from txn_waybill_package_code
												 where waybill_number='$wbnumber' and 
												       code not in (select package_code from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber')"); //*************//
									if($rs){

										$rs1 = query("select group_concat(package_code) as packagecodes from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber' and waybill_number='$wbnumber'");
										while($obj1=fetch($rs1)){
											$packagecodes = $obj1->packagecodes;
										}


										$wbmwbclass = new txn_waybill_movement_waybill();
										$systemlog = new system_log();
										$systemlog->logInfo('WAYBILL MOVEMENT',"Added Waybill","Waybill Movement No.: $wbmnumber | Waybill No.: $wbnumber | Added Package Code(s): $packagecodes",$userid,$now);
										$wbmwbclass->insert(array('',$wbmnumber,$wbnumber,$now,$userid));
										//echo "success";
										array_push($successinsertwb, $wbnumber);


									}
									else{
										echo mysql_error();
									}

								}

							}
							else{
								array_push($failedinsertwb, $wbnumber);
							}

						}
						else{
							array_push($failedinsertwb, $wbnumber);
						}

						


					}

					if(count($failedinsertwb)>0){
						$failedmsg = "Unable to insert the following waybill(s):<br>".implode("<br>", $failedinsertwb);
					}
					$response = array(
									    	"response"=>'success',
									    	"failedmsg"=>$failedmsg
									  );

				}
				else{
					$response = array(
									    "response"=>'txnalreadyposted'
									  );
				}


					

			}
			else{
				$response = array(
							    "response"=>'invalidwaybillmovement'
							  );
			}

			
			print_r(json_encode($response));

			



		}
	}


	if(isset($_POST['deleteWaybillNumber'])){
		if($_POST['deleteWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$wbnumbersid = $_POST['wbnumbersid'];
			$wbnumbersid = implode("','", $wbnumbersid);
			$wbmnumber = '';
			$deletedwaybills = '';
			$deletedpackages = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$waybills = array();
			$getcorrespondingwbs = query("select * from txn_waybill_movement_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($getcorrespondingwbs)){
				array_push($waybills, $obj->waybill_number);
			}
			$waybills = implode("','", $waybills);


			
			$rs = query("select group_concat(waybill_number) as deletedwaybills, waybill_movement_number from txn_waybill_movement_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($rs)){
				$deletedwaybills = $obj->deletedwaybills;
				$wbmnumber = $obj->waybill_movement_number;
			}



			$rs1 = query("select group_concat(package_code) as deletedpackages from txn_waybill_movement_package_code where waybill_number in ('$waybills') and waybill_movement_number='$wbmnumber'");
			while($obj=fetch($rs1)){
				$deletedpackages = $obj->deletedpackages;
			}
			

			$rs = query("delete from txn_waybill_movement_waybill where id in ('$wbnumbersid')");
			if($rs){	
				query("delete from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber' and waybill_number in ('$waybills')");

				$systemlog = new system_log();
				$systemlog->logInfo('WAYBILL MOVEMENT',"Deleted Waybill","Waybill Movement No.: $wbmnumber | Deleted Waybill(s): $deletedwaybills  |  Deleted Package Code(s): $deletedpackages",$userid,$now);
				echo "success";

			}
			else{
				echo mysql_error();
			}

			

		}
	}


	if(isset($_POST['insertNewPackageCode'])){
		if($_POST['insertNewPackageCode']=='ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$wbmnumber = escapeString($_POST['wbmnumber']);
			$packagecode = escapeString(strtoupper($_POST['packagecode']));
			$wbnumber = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$movementtypeid = '';
			$wbstatus = '';



			$checkifvalidwbmrs = query("select * from txn_waybill_movement where waybill_movement_number='$wbmnumber'");

			if(getNumRows($checkifvalidwbmrs)==1){

				while($obj=fetch($checkifvalidwbmrs)){
					$movementtypeid = $obj->movement_type_id;
				}
		
				$checkifvalidcoders = query("select * from txn_waybill_package_code where code='$packagecode'");

				if(getNumRows($checkifvalidcoders)==1){
					while($obj=fetch($checkifvalidcoders)){
						$wbnumber = $obj->waybill_number;
					}

					$checkifvalidwbrs = query("select * from txn_waybill where waybill_number='$wbnumber'");
					if(getNumRows($checkifvalidwbrs)==1){

							while($obj=fetch($checkifvalidwbrs)){
								$wbstatus = strtoupper($obj->status);
							}

							$checkifundermovementrs = query("select * from movement_type_source where upper(source_movement)='$wbstatus' and movement_type_id='$movementtypeid' and source_movement!=''");

							

					

							if(getNumRows($checkifundermovementrs)==1){

									$checkifalreadyaddedrs = query("select * from txn_waybill_movement_package_code where package_code='$packagecode' and waybill_movement_number='$wbmnumber'");

									if(getNumRows($checkifalreadyaddedrs)==0){

										$systemlog = new system_log();

										$checkifwbaddedrs = query("select * from txn_waybill_movement_waybill where waybill_number='$wbnumber' and waybill_movement_number='$wbmnumber'");
										if(getNumRows($checkifwbaddedrs)==0){
											$wbmwbclass = new txn_waybill_movement_waybill();
											
											$systemlog->logInfo('WAYBILL MOVEMENT',"Added Waybill - Package Code Added","Waybill Movement No.: $wbmnumber | Waybill No.: $wbnumber | Package Code: $packagecode",$userid,$now);
											$wbmwbclass->insert(array('',$wbmnumber,$wbnumber,$now,$userid));
										}


										$wbmpckgclass = new txn_waybill_movement_package_code();
										$wbmpckgclass->insert(array('',$wbmnumber,$wbnumber,$packagecode,$now,$userid));
										$systemlog->logInfo('WAYBILL MOVEMENT',"Added Package Code","Waybill Movement No.: $wbmnumber | Waybill No.: $wbnumber | Package Code: $packagecode",$userid,$now);
										echo "success";





									}
									else{
										echo "alreadyadded";
									}

							}
							else{
								echo "invalidwaybill";
							}
					}
					else{
						echo "invalidwaybill";
					}

				}
				else{
					echo "invalidpackagecode";
				}



			}
			else{
				echo "invalidwaybillmovement";
			}




		}
	}


	if(isset($_POST['deletePackageCodes'])){
		if($_POST['deletePackageCodes']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$packagecodeids = $_POST['packagecodeids'];
			$packagecodeids = implode("','", $packagecodeids);
			$wbmnumber = escapeString($_POST['wbmnumber']);

			$deletedpackages = '';
			$deletedwaybills = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$waybills = array();

			/*$waybills = array();
			$getcorrespondingwbs = query("select * from txn_waybill_movement_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($getcorrespondingwbs)){
				array_push($waybills, $obj->waybill_number);
			}
			$waybills = implode("','", $waybills);*/


			
			$rs = query("select group_concat(package_code) as deletedpackages from txn_waybill_movement_package_code where id in ('$packagecodeids')");
			while($obj=fetch($rs)){
				$deletedpackages = $obj->deletedpackages;
			}

			

			$rs = query("delete from txn_waybill_movement_package_code where id in ('$packagecodeids')");
			if($rs){	
				
				$checkwaybillswithoutpackagers = query("select * from txn_waybill_movement_waybill where waybill_number not in (select waybill_number from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber') and waybill_movement_number='$wbmnumber'");

				while($obj = fetch($checkwaybillswithoutpackagers)){
					array_push($waybills, $obj->waybill_number);
				}

				if(count($waybills)>0){
					query("delete from txn_waybill_movement_waybill where waybill_number not in (select waybill_number from txn_waybill_movement_package_code where waybill_movement_number='$wbmnumber') and waybill_movement_number='$wbmnumber'");
					$deletedwaybills = implode(",", $waybills);

				}


				$systemlog = new system_log();
				$systemlog->logInfo('WAYBILL MOVEMENT',"Deleted Packages","Waybill Movement No.: $wbmnumber |  Deleted Package(s): $deletedpackages  |  Deleted Waybill(s): $deletedwaybills",$userid,$now);
				echo "success";

			}
			else{
				echo mysql_error();
			}

			

		}
	}

	if(isset($_POST['voidWaybillMovementTransaction'])){
		if($_POST['voidWaybillMovementTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$txnid = escapeString($_POST['txnid']);
				$txnnumber = escapeString($_POST['txnnumber']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();


			

				$checktxnrs = query("select * from txn_waybill_movement where id='$txnid' and waybill_movement_number='$txnnumber'");


				if(getNumRows($checktxnrs)==1){

						$rs = query("update txn_waybill_movement set status='VOID', updated_date='$now', updated_by='$userid' where id='$txnid'");
						if($rs){
							$systemlog->logInfo('WAYBILL MOVEMENT','Cancelled Waybill Movement Transaction',"BOL Number: ".$txnnumber,$userid,$now);
							echo "success";
						}
					
				}
				else{
					echo "invalidwaybillmovement";
				}

				
				
		}
	}



?>