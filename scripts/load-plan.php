<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/load-plan.class.php");
    include("../classes/load-plan-waybill.class.php");
    include("../classes/load-plan-destination.class.php");
    include("../classes/system-log.class.php");////////
	
	if(isset($_POST['saveNewLoadPlanTransaction'])){
		if($_POST['saveNewLoadPlanTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$response = array();
			$location = escapeString($_POST['location']);
			$origin = escapeString($_POST['origin']);
			//$destination = escapeString($_POST['destination']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$vehicletype = escapeString($_POST['vehicletype']);
			$agent = escapeString($_POST['agent']);
			$carrier = escapeString($_POST['carrier']);
			$manifestnumber = escapeString($_POST['manifestnumber']);
			$documentdate = dateString($_POST['documentdate']);
			$etd = escapeString($_POST['etd'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['etd'])));
			$eta = escapeString($_POST['eta'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['eta'])));
			$mawbbl = escapeString($_POST['mawbbl']);
			$remarks = escapeString($_POST['remarks']);

			@$destinationarray = $_POST['destination'];
			$destinationcondition = implode(',', $destinationarray);
			
			$now = date("Y-m-d H:i:s");
			$userid = USERID;


			if($documentdate=='1970-01-01'){
				$response = array(
									   "response"=>'invaliddocdate'
							 );
			}
			else if($etd=='1970-01-01 08:00:00'){
				$response = array(
									   "response"=>'invalidetd'
							 );
			}
			else if($eta=='1970-01-01 08:00:00'){
				$response = array(
									   "response"=>'invalideta'
							 );
			}
			else{
				$ldpclass = new txn_load_plan();
				$systemlog = new system_log();
				$loadplannumber = getTransactionNumber(4);

				$locationcode = getInfo("location","code","where id='$location'");
				$origindesc = getInfo("origin_destination_port","description","where id='$origin'");
				//$destinationdesc = getInfo("origin_destination_port","description","where id='$destination'");
				$modeoftransportdesc = getInfo("mode_of_transport","description","where id='$modeoftransport'");
				$agentdesc = getInfo("agent","company_name","where id='$agent'");
				$carriercode = getInfo("carrier","description","where id='$carrier'");
				$vehicletypedesc = getInfo("vehicle_type","description","where id='$vehicletype'");

				$destinationdesc = '';
				$rs = query("select group_concat(description) as destination from origin_destination_port where id in ($destinationcondition)");
				while($obj=fetch($rs)){
					$destinationdesc = $obj->destination;
				}

				$ldpclass->insert(array('',$loadplannumber,'LOGGED',$manifestnumber,$location,$carrier,$origin,'NULL',$modeoftransport,$agent,$mawbbl,$documentdate,$eta,$etd,$remarks,$now,$userid,'NULL','NULL','NULL',$vehicletype));
				$systemlog->logInfo('LOAD PLAN',"New Load Plan","Load Plan No.: $loadplannumber | Manifest No.: $manifestnumber | Location: $locationcode | Carrier: $carriercode | Origin: $origindesc | Destination: $destinationdesc | Mode of Transport: $modeoftransportdesc | Agent: $agentdesc | MAWBL/BL: $mawbbl | Document Date: $documentdate | ETD: $etd | ETA: $eta | Remarks: $remarks | Vehicle Type: $vehicletypedesc",$userid,$now);

				/**** DESTINATION ***/
				$ldpdestinationclass = new txn_load_plan_destination();
				
				$ldpdestinationclass->deleteWhere("where load_plan_number='".$loadplannumber."'");
				$ldpdestdata = array();
				
				if($_POST['destination']!=null){
					for($i=0;$i<count($destinationarray);$i++){
						$destinationtemparray = array();
						array_push($destinationtemparray, '', $loadplannumber, $destinationarray[$i],USERID);
						array_push($ldpdestdata, $destinationtemparray);
					}
					if(count($destinationarray)>0){
						$ldpdestinationclass->insertMultiple($ldpdestdata);
					}
				}
				/**** DESTINATION - END ***/
				$response = array(
										   "response"=>'success',
										   "txnnumber"=>$loadplannumber
								 );
			}



			

			
			print_r(json_encode($response));


			
		



		}
	}


	if(isset($_POST['getReference'])){
		if($_POST['getReference']=='FOio5ja3op2a2lK@3#4hh$93s'){
			$source = escapeString($_POST['source']);
			$id = escapeString($_POST['id']);
			

			$query = '';



			if($source=='first'){
				$query = "select * from txn_load_plan order by id asc limit 1";
			}
			else if($source=='second' && $id!=''){
				$query = "select * from txn_load_plan where id < $id order by id desc limit 1";
			}
			else if($source=='third' && $id!=''){
				$query = "select * from txn_load_plan where id > $id order by id asc limit 1";
			}
			else if($source=='fourth'){
				$query = "select * from txn_load_plan order by id desc limit 1";
			}
			else if($id==''){
				$query = "select * from txn_load_plan order by id asc limit 1";
			}
			if($query!=''){
				$rs = query($query);
				if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->load_plan_number;
				}
				else{
					$rs = query("select * from txn_load_plan where id='$id'");
					if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->load_plan_number;
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

	if(isset($_POST['getLoadPlanData'])){
		if($_POST['getLoadPlanData']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

			$txnnumber = escapeString($_POST['txnnumber']);
			$rs = query("select txn_load_plan.id,
				                txn_load_plan.load_plan_number,
				                txn_load_plan.status,
				                txn_load_plan.manifest_number,
				                txn_load_plan.location_id,
				                txn_load_plan.carrier_id,
				                txn_load_plan.origin_id,
				                txn_load_plan.destination_id,
				                txn_load_plan.mode_of_transport_id,
				                txn_load_plan.agent_id,
				                txn_load_plan.mawbl_bl,
				                txn_load_plan.remarks,
				                txn_load_plan.document_date,
				                txn_load_plan.eta,
				                txn_load_plan.etd,
				                txn_load_plan.created_date,
				                txn_load_plan.updated_date,
				                txn_load_plan.created_by,
				                txn_load_plan.updated_by,
				                txn_load_plan.last_status_update_remarks,
				                txn_load_plan.vehicle_type_id,
				                concat(cuser.first_name,' ',cuser.last_name) as createdby,
				                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
				                location.code as loccode,
				                location.description as locdesc,
				                carrier.description as carrierdesc,
				                carrier.code as carriercode,
				                origintbl.description as origin,
				                group_concat(destinationtbl.description separator ', ') as destination,
				                mode_of_transport.description as modeoftransport,
				                agent.company_name as agent,
				                vehicle_type.description as vehicletype
				         from txn_load_plan
				         left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
				         left join user as cuser on cuser.id=txn_load_plan.created_by
				         left join user as uuser on uuser.id=txn_load_plan.updated_by
				         left join location on location.id=txn_load_plan.location_id
				         left join carrier on carrier.id=txn_load_plan.carrier_id
 						 left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id 
				         left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				         left join agent on agent.id=txn_load_plan.agent_id
				         left join vehicle_type on vehicle_type.id=txn_load_plan.vehicle_type_id

				         where txn_load_plan.load_plan_number = '$txnnumber'
				         group by txn_load_plan.load_plan_number");
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){

					$createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
					$updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
					$etd = dateFormat($obj->etd, "m/d/Y h:i:s A");
					$eta = dateFormat($obj->eta, "m/d/Y h:i:s A");
					$documentdate = dateFormat($obj->document_date, "m/d/Y");

					$userhasaccess = hasAccess(USERID,'#useraccessmanageloadplan');
					if(USERID==$obj->created_by||USERID==1||$userhasaccess==1){
						$loggedequalcreated='true';
					}
					else{
						$loggedequalcreated='false';
					}

					$loadplanhasmanifest = loadplanHasManifest($txnnumber);


					$dataarray = array(
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "loadplannumber"=>utfEncode($obj->load_plan_number),
									   "manifestnumber"=>utfEncode($obj->manifest_number),
									   "mawbbl"=>utfEncode($obj->mawbl_bl),
									   "carrier"=>'['.utfEncode($obj->carriercode).'] '.utfEncode($obj->carrierdesc),
									   "location"=>'['.utfEncode($obj->loccode).'] '.utfEncode($obj->locdesc),
									   "agent"=>utfEncode($obj->agent),
									   "origin"=>utfEncode($obj->origin),
									   "originid"=>utfEncode($obj->origin_id),
									   "destination"=>utfEncode($obj->destination),
									   "destinationid"=>utfEncode($obj->destination_id),
									   "modeoftransport"=>utfEncode($obj->modeoftransport),
									   "modeoftransportid"=>utfEncode($obj->mode_of_transport_id),
									   "vehicletype"=>utfEncode($obj->vehicletype),
									   "vehicletypeid"=>utfEncode($obj->vehicle_type_id),
									   "remarks"=>utfEncode($obj->remarks),
									   "documentdate"=>$documentdate,
									   "eta"=>$eta,
									   "etd"=>$etd,
									   "statusupdateremarks"=>utfEncode($obj->last_status_update_remarks),
									   "createddate"=>$createddate,
									   "updateddate"=>$updateddate,
									   "createdby"=>utfEncode($obj->createdby),
									   "updatedby"=>utfEncode($obj->updatedby),
									   "hasaccess"=>$loggedequalcreated,
									   "hasmanifest"=>$loadplanhasmanifest
									   );
				}
				print_r(json_encode($dataarray));
			}
			else{
				echo "INVALID";
			}
		}
	}

	if(isset($_POST['insertMultipleWaybillNumber'])){
		if($_POST['insertMultipleWaybillNumber']=='oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$'){

			$ldpnumber = escapeString(strtoupper($_POST['ldpnumber']));
			$wbnumber = $_POST['wbnumber'];
			$waybillcount = count($wbnumber);
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$originid = '';
			$destinationid = '';
			$modeoftransportid = '';

			$invalidwaybills = array();
			$checkwaybills = true;

			$inanotherldp = array();
			$noldps = true;

			$addedwaybills = array();

			$ldpwclass = new txn_load_plan_waybill();
			$systemlog = new system_log();

			$validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");
			$checkifvalidldprs = query("select * from txn_load_plan where load_plan_number='$ldpnumber'");

			if(getNumRows($checkifvalidldprs)==1){
				while($obj=fetch($checkifvalidldprs)){
					$originid = $obj->origin_id;
					$destinationid = $obj->destination_id;
					$modeoftransportid = $obj->mode_of_transport_id;
				}


				
				for($i=0;$i<$waybillcount;$i++){
					$checkifvalidwbrs = query("select * 
						                       from txn_waybill 
						                       where waybill_number='".escapeString(strtoupper($wbnumber[$i]))."' and
						                             origin_id='$originid' and 
						                             destination_id in (select origin_destination_port_id 
						                                                from txn_load_plan_destination
						                                                where load_plan_number='$ldpnumber'
						                                                ) and 
						                             package_mode_of_transport='$modeoftransportid' and 
						                             txn_waybill.status='$validwaybillstatus'");

					if(getNumRows($checkifvalidwbrs)!=1){
						array_push($invalidwaybills, escapeString(strtoupper($wbnumber[$i])));
						$checkwaybills = false;
					}

					$checkifinanotherloadplanrs = query("select * 
																from txn_load_plan_waybill 
																left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
																where txn_load_plan_waybill.waybill_number='".escapeString(strtoupper($wbnumber[$i]))."' and 
																      txn_load_plan.status!='VOID' and 
																      (txn_load_plan.load_plan_number not in (select load_plan_number from txn_manifest where status!='VOID') or 
																       txn_load_plan.load_plan_number in (select load_plan_number from txn_manifest where status!='POSTED') or 
																       txn_load_plan_waybill.waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where txn_manifest.status!='VOID')
																      )");
					if(getNumRows($checkifinanotherloadplanrs)!=0){
						while($obj=fetch($checkifinanotherloadplanrs)){
							array_push($inanotherldp, escapeString(strtoupper($wbnumber[$i]))." (".$obj->load_plan_number.")");
						}
						
						$noldps = false;
					}


					$checkifaddedrs = query("select * from txn_load_plan_waybill where load_plan_number='$ldpnumber' and waybill_number='".escapeString(strtoupper($wbnumber[$i]))."'");
					if(getNumRows($checkifaddedrs)==0){
						array_push($addedwaybills, escapeString(strtoupper($wbnumber[$i])));
					}




				}

				$invalidwaybillstr = implode(', ', $invalidwaybills);
				$inanotherldpstr = implode(', ', $inanotherldp);
				$addedwaybillstr = implode(', ', $addedwaybills);



				if($checkwaybills==true&&$noldps==true&&count($addedwaybills)>0){

						for($i=0;$i<$waybillcount;$i++){
								$wb = escapeString(strtoupper($wbnumber[$i]));
								$checkifaddedrs = query("select * from txn_load_plan_waybill where load_plan_number='$ldpnumber' and waybill_number='$wb'");
								if(getNumRows($checkifaddedrs)==0){
										$ldpwclass->insert(array('',$ldpnumber,$wb,$now,$userid));
										
								}
						}
						$systemlog->logInfo('LOAD PLAN',"Added Waybill(s) - Multiple","Load Plan No.: $ldpnumber | Waybill No.: $addedwaybillstr ",$userid,$now);
						$response = array(
											"response"=>'success'
								        );
				}
				else if($checkwaybills==false||$noldps==false){
					$response = array(
										"response"=>'unabletoallselectedwaybills',
										"invaliddetails"=>$invalidwaybillstr,
										"inanotherldpdetails"=>$inanotherldpstr
							         );
				}
				/*else if($noldps==false){
					$response = array(
										"response"=>'inanotherloadplan',
										"details"=>$inanotherldpstr
							         );

				}*/
				else if(count($addedwaybills)<=0){
					$response = array(
											"response"=>'success'
								      );
				}
				



			}
			else{
				$response = array(
									"response"=>'invalidloadplannumber'
							     );
			}

			print_r(json_encode($response));



		}
	}



	if(isset($_POST['insertNewWaybillNumber'])){
		if($_POST['insertNewWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$ldpnumber = escapeString(strtoupper($_POST['ldpnumber']));
			$wbnumber = escapeString(strtoupper($_POST['wbnumber']));
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$originid = '';
			$destinationid = '';
			$modeoftransportid = '';

			$validwaybillstatus = getInfo("company_information","load_plan_waybill_status","where id=1");

			$checkifvalidldprs = query("select * from txn_load_plan where load_plan_number='$ldpnumber'");

			if(getNumRows($checkifvalidldprs)==1){

				while($obj=fetch($checkifvalidldprs)){
					$originid = $obj->origin_id;
					$destinationid = $obj->destination_id;
					$modeoftransportid = $obj->mode_of_transport_id;
				}

				$checkifvalidwbrs = query("select * 
					                       from txn_waybill 
					                       where waybill_number='$wbnumber' and 
					                             origin_id='$originid' and 
					                             destination_id in (
					                             					 select origin_destination_port_id 
						                                             from txn_load_plan_destination
						                                             where load_plan_number='$ldpnumber'
						                                            ) and 
					                             package_mode_of_transport='$modeoftransportid' and 
					                             txn_waybill.status='$validwaybillstatus'");
				if(getNumRows($checkifvalidwbrs)==1){



						$checkifaddedrs = query("select * from txn_load_plan_waybill where load_plan_number='$ldpnumber' and waybill_number='$wbnumber'");

						if(getNumRows($checkifaddedrs)==0){

							$checkifinanotherloadplanrs = query("select * 
																from txn_load_plan_waybill 
																left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
																where txn_load_plan_waybill.waybill_number='$wbnumber' and 
																      txn_load_plan.status!='VOID' and txn_load_plan.status!='DISPATCHED' and 
																      (txn_load_plan.load_plan_number not in (select load_plan_number from txn_manifest where status!='VOID') or 
																       txn_load_plan.load_plan_number in (select load_plan_number from txn_manifest where status!='POSTED') or 
																       txn_load_plan_waybill.waybill_number in (select waybill_number from txn_manifest_waybill left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number where txn_manifest.status!='VOID')
																      )");

								if(getNumRows($checkifinanotherloadplanrs)==0){


										$ldpwclass = new txn_load_plan_waybill();
										$systemlog = new system_log();
										$systemlog->logInfo('LOAD PLAN',"Added Waybill","Load Plan No.: $ldpnumber | Waybill No.: $wbnumber ",$userid,$now);
										$ldpwclass->insert(array('',$ldpnumber,$wbnumber,$now,$userid));
										echo "success";
									
								}
								else{
									echo "inanotherloadplan";
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
				echo "invalidloadplannumber";
			}

			



		}
	}


	if(isset($_POST['deleteWaybillNumber'])){
		if($_POST['deleteWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$wbnumbersid = $_POST['wbnumbersid'];
			$wbnumbersid = implode("','", $wbnumbersid);
			$ldpnumber = '';
			$deletedwaybills = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$waybills = array();
			$getcorrespondingwbs = query("select * from txn_load_plan_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($getcorrespondingwbs)){
				array_push($waybills, $obj->waybill_number);
			}
			$waybills = implode("','", $waybills);


			
			$rs = query("select group_concat(waybill_number) as deletedwaybills, load_plan_number from txn_load_plan_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($rs)){
				$deletedwaybills = $obj->deletedwaybills;
				$ldpnumber = $obj->load_plan_number;
			}


			$rs = query("delete from txn_load_plan_waybill where id in ('$wbnumbersid')");
			if($rs){	

				$systemlog = new system_log();
				$systemlog->logInfo('LOAD PLAN',"Deleted Waybill(s)","Load Plan No.: $ldpnumber | Deleted Waybill(s): $deletedwaybills",$userid,$now);
				echo "success";

			}
			else{
				echo mysql_error();
			}

			

		}
	}


	if(isset($_POST['postTransaction'])){
		if($_POST['postTransaction']=='oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk'){
				$id = escapeString($_POST['id']);
				$txnnumber = escapeString($_POST['txnnumber']);
				
				$status = '';
				$data = array();
				$createdby = 'none';
				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();


				$checktxnrs = query("select * from txn_load_plan where load_plan_number='$txnnumber'");
			

				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$status = $obj->status;
						$createdby = $obj->created_by;
					}

					if($status=='LOGGED'){

						$checkaddedwaybillrs = query("select * from txn_load_plan_waybill where load_plan_number='$txnnumber'");

						if(getNumRows($checkaddedwaybillrs)>0){

							$userhasaccess = hasAccess(USERID,'#useraccessmanageloadplan');
							if(USERID==$createdby||USERID==1||$userhasaccess==1){
								$rs = query("update txn_load_plan set status='POSTED', updated_date='$now', updated_by='$userid' where id='$id'");

								if($rs){

									/*$waybillstathistory = new txn_waybill_status_history();
									while($obj=fetch($checkaddedwaybillrs)){
										$wbnumber = $obj->waybill_number;
										$wbstatremarks = $obj->remarks;

										query("update txn_waybill set status='$movementtype', last_status_update_remarks='$wbstatremarks' where waybill_number='$wbnumber'");

										$wbstatremarks = trim($wbstatremarks)==''?'NULL':$wbstatremarks; 
										$waybillstathistory->insert(array('',$wbnumber,$movementtype,$movementtype,$wbstatremarks,$now,$userid));

										$systemlog->logInfo('WAYBILL MOVEMENT','Posted Waybill Movement',"Waybill Number: ".$txnnumber."; Status: $movementtype",$userid,$now);
										$systemlog->logInfo('WAYBILL','Updated Waybill Status',"Waybill Number: ".$wbnumber."; Status: $movementtype; Remarks: $wbstatremarks",$userid,$now);
										
									}*/

									$systemlog->logInfo('LOAD PLAN','Posted Load Plan',"Load Plan No.: ".$txnnumber,$userid,$now);
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



	if(isset($_POST['LoadPlanGetInfo'])){
		if($_POST['LoadPlanGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("select txn_load_plan.id,
				                txn_load_plan.load_plan_number,
				                txn_load_plan.status,
				                txn_load_plan.manifest_number,
				                txn_load_plan.location_id,
				                txn_load_plan.carrier_id,
				                txn_load_plan.origin_id,
				                txn_load_plan.destination_id,
				                txn_load_plan.agent_id,
				                txn_load_plan.mode_of_transport_id,
				                txn_load_plan.mawbl_bl,
				                txn_load_plan.remarks,
				                txn_load_plan.document_date,
				                txn_load_plan.eta,
				                txn_load_plan.etd,
				                txn_load_plan.vehicle_type_id,
				                location.code as loccode,
				                location.description as locdesc,
				                carrier.description as carrierdesc,
				                carrier.code as carriercode,
				                origintbl.description as origin,
				                destinationtbl.description as destination,
				                mode_of_transport.description as modeoftransport,
				                agent.company_name as agent,
				                vehicle_type.description as vehicletype
				         from txn_load_plan
				         left join location on location.id=txn_load_plan.location_id
				         left join carrier on carrier.id=txn_load_plan.carrier_id
 						 left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
				         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan.destination_id 
				         left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
				         left join agent on agent.id=txn_load_plan.agent_id
				         left join vehicle_type on vehicle_type.id=txn_load_plan.vehicle_type_id
				         where txn_load_plan.id = '$id'
				 	    ");

			if(getNumRows($rs)==1){

				while($obj=fetch($rs)){
					$etd = dateFormat($obj->etd, "m/d/Y H:i");
					$eta = dateFormat($obj->eta, "m/d/Y H:i");
					$documentdate = dateFormat($obj->document_date, "m/d/Y");
					$dataarray = array(
										   "id"=>utfEncode($obj->id),
										   "manifestnumber"=>utfEncode($obj->manifest_number),
										   "mawbbl"=>utfEncode($obj->mawbl_bl),
										   "documentdate"=>$documentdate,
										   "etd"=>$etd,
										   "eta"=>$eta,
										   "remarks"=>utfEncode($obj->remarks),
										   "location"=>utfEncode($obj->loccode).' - '.utfEncode($obj->locdesc),
										   "locationid"=>utfEncode($obj->location_id),
										   "carrier"=>utfEncode($obj->carriercode).' - '.utfEncode($obj->carrierdesc),
										   "carrierid"=>utfEncode($obj->carrier_id),
										   "origin"=>utfEncode($obj->origin),
										   "originid"=>utfEncode($obj->origin_id),
										   "destination"=>utfEncode($obj->destination),
										   "destinationid"=>utfEncode($obj->destination_id),
										   "modeoftransport"=>utfEncode($obj->modeoftransport),
										   "modeoftransportid"=>utfEncode($obj->mode_of_transport_id),
										   "vehicletype"=>utfEncode($obj->vehicletype),
										   "vehicletypeid"=>utfEncode($obj->vehicle_type_id),
										   "agent"=>utfEncode($obj->agent),
										   "agentid"=>utfEncode($obj->agent_id),
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


	if(isset($_POST['saveEditLoadPlanTransaction'])){
		if($_POST['saveEditLoadPlanTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$response = array();
			$loadplanID = escapeString($_POST['id']);
			$location = escapeString($_POST['location']);
			$origin = escapeString($_POST['origin']);
			//$destination = escapeString($_POST['destination']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$vehicletype = escapeString($_POST['vehicletype']);
			$agent = escapeString($_POST['agent']);
			$carrier = escapeString($_POST['carrier']);
			$manifestnumber = escapeString($_POST['manifestnumber']);
			$loadplannumber = escapeString($_POST['loadplannumber']);
			$documentdate = dateString($_POST['documentdate']);
			$etd = escapeString($_POST['etd'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['etd'])));
			$eta = escapeString($_POST['eta'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['eta'])));
			$mawbbl = escapeString($_POST['mawbbl']);
			$remarks = escapeString($_POST['remarks']);

			@$destinationarray = $_POST['destination'];
			$destinationcondition = implode(',', $destinationarray);
				
			
			$now = date("Y-m-d H:i:s");
			$userid = USERID;


			if($documentdate=='1970-01-01'){
				$response = array(
									   "response"=>'invaliddocdate'
							 );
			}
			else if($etd=='1970-01-01 08:00:00'){
				$response = array(
									   "response"=>'invalidetd'
							 );
			}
			else if($eta=='1970-01-01 08:00:00'){
				$response = array(
									   "response"=>'invalideta'
							 );
			}
			else{
				$ldpclass = new txn_load_plan();
				$systemlog = new system_log();

				$locationcode = getInfo("location","code","where id='$location'");
				$origindesc = getInfo("origin_destination_port","description","where id='$origin'");
				//$destinationdesc = getInfo("origin_destination_port","description","where id='$destination'");
				$modeoftransportdesc = getInfo("mode_of_transport","description","where id='$modeoftransport'");
				$agentdesc = getInfo("agent","company_name","where id='$agent'");
				$carriercode = getInfo("carrier","description","where id='$carrier'");
				$vehicletypedesc = getInfo("vehicle_type","description","where id='$vehicletype'");

				$destinationdesc = '';
				$rs = query("select group_concat(description) as destination from origin_destination_port where id in ($destinationcondition)");
				while($obj=fetch($rs)){
					$destinationdesc = $obj->destination;
				}

				$systemlog->logEditedInfo($ldpclass,$loadplanID,array($loadplanID,$loadplannumber,'NOCHANGE',$manifestnumber,$location,$carrier,$origin,$destinationdesc,$modeoftransport,$agent,$mawbbl,$documentdate,$eta,$etd,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NULL',$vehicletype),'LOAD PLAN','Edited Load Plan Header',$userid,$now);/// log should be before update is made
				$ldpclass->update($loadplanID,array($loadplannumber,'NOCHANGE',$manifestnumber,$location,$carrier,$origin,'NULL',$modeoftransport,$agent,$mawbbl,$documentdate,$eta,$etd,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NOCHANGE',$vehicletype));

				/**** DESTINATION ***/
				$ldpdestinationclass = new txn_load_plan_destination();
				
				$ldpdestinationclass->deleteWhere("where load_plan_number='".$loadplannumber."'");
				$ldpdestdata = array();
				
				if($_POST['destination']!=null){
					for($i=0;$i<count($destinationarray);$i++){
						$destinationtemparray = array();
						array_push($destinationtemparray, '', $loadplannumber, $destinationarray[$i],USERID);
						array_push($ldpdestdata, $destinationtemparray);
					}
					if(count($destinationarray)>0){
						$ldpdestinationclass->insertMultiple($ldpdestdata);
					}
				}
				/**** DESTINATION - END ***/

				
				

				query("delete txn_load_plan_waybill
					  from txn_load_plan_waybill 
					  left join txn_waybill on txn_waybill.waybill_number=txn_load_plan_waybill.waybill_number
					  where txn_load_plan_waybill.load_plan_number='$loadplannumber' and 
					        (
					          txn_waybill.origin_id!='$origin' or 
					          txn_waybill.destination_id not in ($destinationcondition) or 
					          txn_waybill.package_mode_of_transport!='$modeoftransport'
					        )");

				$response = array(
										   "response"=>'success',
										   "txnnumber"=>$loadplannumber
								 );
			}



			

			
			print_r(json_encode($response));


			
		



		}
	}


	if(isset($_POST['voidTransaction'])){
		if($_POST['voidTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$id = escapeString($_POST['txnid']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
			

				$checktxnrs = query("select * from txn_load_plan where id='$id' and load_plan_number='$txnnumber'");


				if(getNumRows($checktxnrs)==1){

						$rs = query("update txn_load_plan set status='VOID', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' where id='$id'");
						if($rs){
							$systemlog->logInfo('LOAD PLAN','Cancelled Load Plan Transaction',"Load Plan No.: ".$txnnumber." | Remarks: $remarks",$userid,$now);
							echo "success";
						}
					
				}
				else{
					echo "invalidtransaction";
				}

				
				
		}
	}

	if(isset($_POST['getDestinations'])){
		if($_POST['getDestinations']=='sdfed#n2L1hfi$n#opi3opod30napri'){
			$ldpnumber = escapeString($_POST['id']);
			$ldpdestination = getLoadPlanDestinations($ldpnumber);

			$dataarray = array(
								 "ldpdestination"=>$ldpdestination
							  );
			print_r(json_encode($dataarray));

		}
	}


	if(isset($_POST['getTotalActualWeight'])){
		if($_POST['getTotalActualWeight']=='oiu2OI9kldp39u2o0lfknzzzo92po@k@'){
			$txnnumber = escapeString($_POST['txnnumber']);
			$totalweight = 0;
			$totalcbm = 0;
			$totalvolweight = 0;
			$totalnumofpackage = 0;
			$totalnumofwaybill = 0;
			$rs = query("select sum(package_actual_weight) as totalweight,
			                    sum(package_number_of_packages) as numofpackage,
			                    count(*) as numofwaybill 
				         from txn_load_plan_waybill
				         left join txn_waybill on txn_waybill.waybill_number=txn_load_plan_waybill.waybill_number 
				         where txn_load_plan_waybill.load_plan_number='$txnnumber'");

			
			while($obj=fetch($rs)){
				$totalweight = $obj->totalweight;
				$totalnumofpackage = $obj->numofpackage;
			    $totalnumofwaybill = $obj->numofwaybill;
			}

			$totalweight = convertWithDecimal($totalweight,5);

			/*$rs = query("select sum(txn_waybill_package_dimension.volumetric_weight) as volweight,
			                    sum(txn_waybill_package_dimension.cbm) as cbm 
				         from txn_load_plan_waybill
				         left join txn_waybill_package_dimension on txn_waybill_package_dimension.waybill_number=txn_load_plan_waybill.waybill_number 
				         where txn_load_plan_waybill.load_plan_number='$txnnumber'");*/

			$rs = query("select sum(txn_waybill.package_vw) as volweight,
			                    sum(txn_waybill.package_cbm) as cbm 
				         from txn_load_plan_waybill
				         left join txn_waybill on txn_waybill.waybill_number=txn_load_plan_waybill.waybill_number 
				         where txn_load_plan_waybill.load_plan_number='$txnnumber'");

			while($obj=fetch($rs)){
				$totalcbm = $obj->cbm;
				$totalvolweight = $obj->volweight;
			}

			$totalcbm = convertWithDecimal($totalcbm,5);
			$totalvolweight = convertWithDecimal($totalvolweight,5);

			$response = array(
										   "response"=>'success',
										   "totalweight"=>$totalweight,
										   "totalcbm"=>$totalcbm,
										   "totalvolweight"=>$totalvolweight,
										   "totalnumofpackage"=>$totalnumofpackage,
										   "totalnumofwaybill"=>$totalnumofwaybill
								 );
			print_r(json_encode($response));




		}
	}



	if(isset($_POST['unpostTransaction'])){
		if($_POST['unpostTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$id = escapeString($_POST['txnid']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();

				$loadplanhasmanifest = loadplanHasManifest($txnnumber);
			
				if($loadplanhasmanifest==0){
					$checktxnrs = query("select * from txn_load_plan where id='$id' and load_plan_number='$txnnumber'");
					if(getNumRows($checktxnrs)==1){

							$rs = query("update txn_load_plan set status='LOGGED', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' where id='$id'");
							if($rs){
								$systemlog->logInfo('LOAD PLAN','Unpost Load Plan Transaction',"Load Plan No.: ".$txnnumber." | Remarks: $remarks",$userid,$now);
								echo "success";
							}
						
					}
					else{
						echo "invalidtransaction";
					}
				}
				else{
					echo "hasmanifest";
				}

				
				
		}
	}

?>