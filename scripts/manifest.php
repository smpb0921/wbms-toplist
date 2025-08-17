<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/manifest.class.php");
    include("../classes/manifest-waybill.class.php");
    include("../classes/manifest-package-code.class.php");
    include("../classes/waybill-status-history.class.php");
    include("../classes/system-log.class.php");////////
	
	if(isset($_POST['saveNewManifestTransaction'])){
		if($_POST['saveNewManifestTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$response = array();
			$documentdate = dateString($_POST['documentdate']);
			$loadplannumber = escapeString($_POST['loadplannumber']);
			$truckername = escapeString($_POST['truckername']);
			$trucktype = escapeString($_POST['trucktype']);
			$platenumber = escapeString($_POST['platenumber']);
			$drivername = isset($_POST['drivername'])?escapeString($_POST['drivername']):'NULL';
			$contactnumber = escapeString($_POST['contactnumber']);
			$remarks = escapeString($_POST['remarks']);

			/*$getdriverrs = query("select concat(first_name,' ',last_name) as driver from personnel where id='$driverid'");
			while($obj=fetch($getdriverrs)){
				$drivername = $obj->driver;
			}*/

			$loadplanflag = escapeString($_POST['loadplanflag']);
			$location = escapeString($_POST['location']);
			$origin = escapeString($_POST['origin']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$agent = escapeString($_POST['agent']);
			$etd = escapeString($_POST['etd'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['etd'])));
			$eta = escapeString($_POST['eta'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['eta'])));
			$mawbl = escapeString($_POST['mawbl']);
			
			$now = date("Y-m-d H:i:s");
			$userid = USERID;

			$hasmanifest = false;
			$manifestnumber = '';

			$checkloadplanhasmanifestrs = query("select * from txn_manifest where load_plan_number='$loadplannumber' and status='LOGGED'");
			while($obj=fetch($checkloadplanhasmanifestrs)){
				$hasmanifest = true;
				$manifestnumber = $obj->manifest_number;
			}

			$ldpstatus = '';
			$checkloadplanstatusrs = query("select * from txn_load_plan where load_plan_number='$loadplannumber'");
			while($obj=fetch($checkloadplanstatusrs)){
				$ldpstatus = $obj->status;	
			}

			/*$loadplanmultiplemanifest = getInfo("company_information","load_plan_multiple_manifest","where id=1");
			if($loadplanmultiplemanifest==1){
				$tmpstat = ''
			}
			else{

			}*/
			//&&$ldpstatus!='DISPATCHED'
			if($ldpstatus!='POSTED'&&$loadplanflag==1){
				$response = array(
									   "response"=>'invalidloadplanstatus'
							     );
			}
			else if($hasmanifest==true&&$loadplanflag==1){
				$response = array(
									   "response"=>'loadplanhasmanifest',
									   "manifestnumber"=>$manifestnumber
							 );
			}
			else if($etd=='1970-01-01 08:00:00'&&$loadplanflag==0){
				$response = array(
									   "response"=>'invalidetd'
							 );
			}
			else if($eta=='1970-01-01 08:00:00'&&$loadplanflag==0){
				$response = array(
									   "response"=>'invalideta'
							 );
			}
			else if($documentdate=='1970-01-01'){
				$response = array(
									   "response"=>'invaliddocdate'
							 );
			}
			else{
				$mftclass = new txn_manifest();
				$systemlog = new system_log();
				$manifestnumber = getTransactionNumber(5);

				$mftclass->insert(array('',$manifestnumber,'LOGGED',$documentdate,$loadplannumber,$remarks,$now,$userid,'NULL','NULL','NULL',$truckername,$trucktype,$platenumber,$drivername,$contactnumber,$loadplanflag,$location,$origin,$modeoftransport,$agent,$mawbl,$eta,$etd));
				$systemlog->logInfo('MANIFEST',"New Manifest","Manifest No.: $manifestnumber | Load Plan Flag: $loadplanflag | Load Plan No.: $loadplannumber |  Document Date: $documentdate | Remarks: $remarks | Trucker Name: $truckername | Truck Type: $trucktype | Plate No.: $platenumber | Driver Name: $drivername | Contact Number: $contactnumber | Location: $location | Origin: $origin | Mode of Transport: $modeoftransport | Agent: $agent | Mawbl: $mawbl | ETD: $etd | ETA: $eta",$userid,$now);
				$response = array(
										   "response"=>'success',
										   "txnnumber"=>$manifestnumber
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
				$query = "select * from txn_manifest order by id asc limit 1";
			}
			else if($source=='second' && $id!=''){
				$query = "select * from txn_manifest where id < $id order by id desc limit 1";
			}
			else if($source=='third' && $id!=''){
				$query = "select * from txn_manifest where id > $id order by id asc limit 1";
			}
			else if($source=='fourth'){
				$query = "select * from txn_manifest order by id desc limit 1";
			}
			else if($id==''){
				$query = "select * from txn_manifest order by id asc limit 1";
			}
			
			if($query!=''){	
				$rs = query($query);
				if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->manifest_number;
				}
				else{
					$rs = query("select * from txn_manifest where id='$id'");
					if(getNumRows($rs)>0){
						$obj = fetch($rs);
						echo $obj->manifest_number;
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

	if(isset($_POST['getWaybillCount'])){
		if($_POST['getWaybillCount']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){
			$manifest = escapeString($_POST['manifest']);

			$loadplan = '';
			$loadplanflag = 0;
			$rs = query("select * from txn_manifest where manifest_number='$manifest'");
			while($obj=fetch($rs)){
				$loadplan = $obj->load_plan_number;
				$loadplanflag = $obj->load_plan_flag;
			}
			//$loadplan = getInfo("txn_manifest","load_plan_number","where manifest_number='$manifest'");

			if($loadplanflag==1){

				$waybillcountinldp = 0;
				$waybillcountinmft = 0;
				$waybillpackagecount = 0;
				$manifestpackagecount = '';

				$rs = query("select count(*) as waybillcount from txn_load_plan_waybill where load_plan_number='$loadplan'");
				while($obj=fetch($rs)){
					$waybillcountinldp = $obj->waybillcount;
				}

				$rs = query("select count(*) as waybillcount from txn_manifest_waybill where manifest_number='$manifest'");
				while($obj=fetch($rs)){
					$waybillcountinmft = $obj->waybillcount;
				}

				$response = $waybillcountinmft." / ".$waybillcountinldp;

				$checkflag = false;

				if($waybillcountinmft>0&&$waybillcountinldp>0&&$waybillcountinldp==$waybillcountinmft){
					$checkflag = true;
				}

				$userhasaccess = hasAccess(USERID,'#incompletewaybillposting');
				$userhasaccesspckg = hasAccess(USERID,'#incompletepackageposting');


				$rs = query("select count(*) as packagecount from txn_waybill_package_code where waybill_number in (select waybill_number from txn_load_plan_waybill where load_plan_number='$loadplan')");
				while($obj=fetch($rs)){
					$waybillpackagecount = $obj->packagecount;
				}

				$rs = query("select count(*) as packagecount from txn_manifest_waybill_package_code where manifest_number='$manifest'");
				while($obj=fetch($rs)){
					$manifestpackagecount = $obj->packagecount;
				}

				$checkpackageflag = false;
				if($waybillpackagecount==$manifestpackagecount){
					$checkpackageflag = true;
				}



				$dataarray = array(
										   "waybillcount"=>utfEncode($response),
										   "completewaybill"=>$checkflag,
										   "allowpostingincompletewaybill"=>$userhasaccess,
										   "completepackage"=>$checkpackageflag,
										   "allowpostingincompletepackage"=>$userhasaccesspckg,
										   "test"=>"$waybillpackagecount==$manifestpackagecount"
								  );
			}
			else{
				$userhasaccesspckg = hasAccess(USERID,'#incompletepackageposting');

				$rs = query("select count(*) as waybillcount from txn_manifest_waybill where manifest_number='$manifest'");
				while($obj=fetch($rs)){
					$waybillcountinmft = $obj->waybillcount;
				}
				$response = $waybillcountinmft;

				$rs = query("select count(*) as packagecount from txn_waybill_package_code where waybill_number in (select waybill_number from txn_manifest_waybill where manifest_number='$manifest')");
				while($obj=fetch($rs)){
					$waybillpackagecount = $obj->packagecount;
				}

				$rs = query("select count(*) as packagecount from txn_manifest_waybill_package_code where manifest_number='$manifest'");
				while($obj=fetch($rs)){
					$manifestpackagecount = $obj->packagecount;
				}

				$checkpackageflag = false;
				if($waybillpackagecount==$manifestpackagecount){
					$checkpackageflag = true;
				}

				$dataarray = array(
										   "waybillcount"=>utfEncode($response),
										   "completewaybill"=>true,
										   "allowpostingincompletewaybill"=>1,
										   "completepackage"=>$checkpackageflag,
										   "allowpostingincompletepackage"=>$userhasaccesspckg
								  );
			}
			print_r(json_encode($dataarray));

		}
	}

	if(isset($_POST['getLoadPlanDetails'])){
		if($_POST['getLoadPlanDetails']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$ldpnumber = escapeString($_POST['ldpnumber']);

			$query = "select txn_load_plan.carrier_id,
				                txn_load_plan.vehicle_type_id,
				                carrier.description as carrier,
				                vehicle_type.description as vehicletype
				         from txn_load_plan
				         left join vehicle_type on vehicle_type.id=txn_load_plan.vehicle_type_id
				         left join carrier on carrier.id=txn_load_plan.carrier_id
				         where txn_load_plan.load_plan_number='$ldpnumber'";

			$rs = query($query);

			if(getNumRows($rs)==1){

				while($obj = fetch($rs)){

					$dataarray = array(
									   "carrierid"=>utfEncode($obj->carrier_id),
									   "carrier"=>utfEncode($obj->carrier),
									   "vehicletypeid"=>utfEncode($obj->vehicle_type_id),
									   "vehicletype"=>utfEncode($obj->vehicletype),
									   "datenow"=>date('m/d/Y')
									  );

				}
				
			}
			else{
				$dataarray = array(
									   "carrierid"=>'',
									   "carrier"=>'',
									   "vehicletypeid"=>'',
									   "vehicletype"=>'',
									   "datenow"=>date('m/d/Y')
									  );
			}

			print_r(json_encode($dataarray));


		}
	}

	if(isset($_POST['getManifestData'])){
		if($_POST['getManifestData']=='F#@!3R3ksk#Op1NEi34smo1sonk&$'){

			$txnnumber = escapeString($_POST['txnnumber']);
			$loadplanflag = getInfo("txn_manifest","load_plan_flag","where manifest_number='$txnnumber'");



			if($loadplanflag==1){
				$rs = query("select txn_manifest.id,
					                txn_manifest.load_plan_number,
					                txn_manifest.status,
					                txn_manifest.manifest_number,
					                txn_manifest.document_date,
					                txn_manifest.created_date,
					                txn_manifest.updated_date,
					                txn_manifest.created_by,
					                txn_manifest.updated_by,
					                txn_manifest.remarks,
					                txn_manifest.last_status_update_remarks,
					                txn_manifest.trucker_name,
					                txn_manifest.truck_type,
					                txn_manifest.plate_number,
					                txn_manifest.driver_name,
					                txn_manifest.contact_number,
					                txn_load_plan.location_id,
					                txn_load_plan.carrier_id,
					                txn_load_plan.origin_id,
					                txn_load_plan.destination_id,
					                txn_load_plan.agent_id,
					                txn_load_plan.mawbl_bl,
					                txn_load_plan.eta,
					                txn_load_plan.etd,
					                concat(cuser.first_name,' ',cuser.last_name) as createdby,
					                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
					                location.code as loccode,
					                location.description as locdesc,
					                carrier.description as carrierdesc,
					                vehicle_type.description as vehicletype,
					                carrier.code as carriercode,
					                origintbl.description as origin,
					                group_concat(destinationtbl.description separator ', ') as destination,
					                mode_of_transport.description as modeoftransport,
					                agent.company_name as agent,
									agent_contact.mobile_number as agentcontact,
									agent.company_street_address,
									agent.company_district,
									agent.company_city,
									agent.company_state_province,
									agent.company_zip_code,
									agent.company_country
					         from txn_manifest
							 left join agent_contact on agent_contact.agent_id=txn_manifest.agent_id and default_flag=1
					         left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
					         left join user as cuser on cuser.id=txn_manifest.created_by
					         left join user as uuser on uuser.id=txn_manifest.updated_by
					         left join location on location.id=txn_load_plan.location_id
					         left join carrier on carrier.id=trucker_name
					         left join vehicle_type on vehicle_type.id=truck_type
	 						 left join origin_destination_port as origintbl on origintbl.id=txn_load_plan.origin_id 
	 						 left join txn_load_plan_destination on txn_load_plan_destination.load_plan_number=txn_load_plan.load_plan_number
					         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_load_plan_destination.origin_destination_port_id 
					         left join mode_of_transport on mode_of_transport.id=txn_load_plan.mode_of_transport_id
					         left join agent on agent.id=txn_load_plan.agent_id
					         where txn_manifest.manifest_number = '$txnnumber'
					         group by txn_load_plan.load_plan_number");
			}
			else{
				$rs = query("select txn_manifest.id,
					                ifnull(txn_manifest.load_plan_number,'N/A') as load_plan_number,
					                txn_manifest.status,
					                txn_manifest.manifest_number,
					                txn_manifest.document_date,
					                txn_manifest.created_date,
					                txn_manifest.updated_date,
					                txn_manifest.created_by,
					                txn_manifest.updated_by,
					                txn_manifest.remarks,
					                txn_manifest.last_status_update_remarks,
					                txn_manifest.trucker_name,
					                txn_manifest.truck_type,
					                txn_manifest.plate_number,
					                txn_manifest.driver_name,
					                txn_manifest.contact_number,
					                txn_manifest.mawbl as mawbl_bl,
					                txn_manifest.eta,
					                txn_manifest.etd,
					                concat(cuser.first_name,' ',cuser.last_name) as createdby,
					                concat(uuser.first_name,' ',uuser.last_name) as updatedby,
					                location.code as loccode,
					                location.description as locdesc,
					                carrier.description as carrierdesc,
					                vehicle_type.description as vehicletype,
					                carrier.code as carriercode,
					                origintbl.description as origin,
					                group_concat(distinct destinationtbl.description separator ', ') as destination,
					                mode_of_transport.description as modeoftransport,
					                agent.company_name as agent,
									agent_contact.mobile_number as agentcontact,
									agent.company_street_address,
									agent.company_district,
									agent.company_city,
									agent.company_state_province,
									agent.company_zip_code,
									agent.company_country
					         from txn_manifest
							 left join agent_contact on agent_contact.agent_id=txn_manifest.agent_id and default_flag=1
					         left join user as cuser on cuser.id=txn_manifest.created_by
					         left join user as uuser on uuser.id=txn_manifest.updated_by
					         left join location on location.id=txn_manifest.location_id
					         left join carrier on carrier.id=trucker_name
					         left join vehicle_type on vehicle_type.id=truck_type
	 						 left join origin_destination_port as origintbl on origintbl.id=txn_manifest.origin_id
					         left join mode_of_transport on mode_of_transport.id=txn_manifest.mode_of_transport_id
					         left join agent on agent.id=txn_manifest.agent_id
					         left join txn_manifest_waybill on txn_manifest_waybill.manifest_number=txn_manifest.manifest_number
					         left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
					         left join origin_destination_port as destinationtbl on destinationtbl.id=txn_waybill.destination_id
					         where txn_manifest.manifest_number = '$txnnumber'
					         group by txn_manifest.manifest_number");
			}
			if(getNumRows($rs)==1){
				while($obj = fetch($rs)){
					$agentaddressarr = [];
					$createddate = dateFormat($obj->created_date, "m/d/Y h:i:s A");
					$updateddate = dateFormat($obj->updated_date, "m/d/Y h:i:s A");
					$etd = dateFormat($obj->etd, "m/d/Y h:i:s A");
					$eta = dateFormat($obj->eta, "m/d/Y h:i:s A");
					$documentdate = dateFormat($obj->document_date, "m/d/Y");

					array_push($agentaddressarr,
													utfEncode($obj->company_street_address), 
													utfEncode($obj->company_district), 
													utfEncode($obj->company_city),
													utfEncode($obj->company_state_province), 
													utfEncode($obj->company_country).' '.utfEncode($obj->company_zip_code)
					);
					//print_r($agentaddressarr);

					$agentaddress = concatData($agentaddressarr,', ');

					$userhasaccess = hasAccess(USERID,'#useraccessmanagemanifest');
					if(USERID==$obj->created_by||USERID==1||$userhasaccess==1){
						$loggedequalcreated='true';
					}
					else{
						$loggedequalcreated='false';
					}


					$dataarray = array(
									   "id"=>utfEncode($obj->id),
									   "status"=>utfEncode($obj->status),
									   "loadplannumber"=>utfEncode($obj->load_plan_number),
									   "manifestnumber"=>utfEncode($obj->manifest_number),
									   "mawbbl"=>utfEncode($obj->mawbl_bl),
									   //"carrier"=>'['.$obj->carriercode.'] '.$obj->carrierdesc,
									   "location"=>'['.utfEncode($obj->loccode).'] '.utfEncode($obj->locdesc),
									   "agent"=>utfEncode($obj->agent),
									   "agentaddress"=>$agentaddress,
									   "agentcontact"=>utfEncode($obj->agentcontact),
									   "origin"=>utfEncode($obj->origin),
									   "destination"=>utfEncode($obj->destination),
									   "modeoftransport"=>utfEncode($obj->modeoftransport),
									   "remarks"=>utfEncode($obj->remarks),
									   "documentdate"=>$documentdate,
									   "eta"=>$eta,
									   "etd"=>$etd,
									   "statusupdateremarks"=>utfEncode($obj->last_status_update_remarks),
									   "createddate"=>$createddate,
									   "updateddate"=>$updateddate,
									   "createdby"=>utfEncode($obj->createdby),
									   "updatedby"=>utfEncode($obj->updatedby),
									   "truckername"=>utfEncode($obj->carrierdesc),
									   "trucktype"=>utfEncode($obj->vehicletype),
									   "platenumber"=>utfEncode($obj->plate_number),
									   "drivername"=>utfEncode($obj->driver_name),
									   "contactnumber"=>utfEncode($obj->contact_number),
									   "hasaccess"=>$loggedequalcreated
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

			$mftnumber = escapeString(strtoupper($_POST['mftnumber']));
			$wbnumber = escapeString(strtoupper($_POST['wbnumber']));
			$scantype = escapeString(strtoupper($_POST['scantype']));
			$pouchsize = escapeString(strtoupper($_POST['pouchsize']));
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$ldpnumber = '';
			$loadplanflag = 0;
			$mftoriginid = '';
			$mftmodeoftransportid = '';
			$mftmawbl = '';
			$wbpouchsize = 'N/A';
			$wbtype = '';

			$checkifvalidpouchsize = query("select * from pouch_size where id='$pouchsize'");

			if(getNumRows($checkifvalidpouchsize)==1){

				$checkifvalidmftrs = query("select * from txn_manifest where manifest_number='$mftnumber'");

				if(getNumRows($checkifvalidmftrs)==1){

					while($obj=fetch($checkifvalidmftrs)){
							$ldpnumber = $obj->load_plan_number;
							$loadplanflag = $obj->load_plan_flag;

							$mftoriginid = $obj->origin_id;
							$mftmodeoftransportid = $obj->mode_of_transport_id;
							$mftmawbl = $obj->mawbl;
					}

					if($scantype=='WB'){

						if($loadplanflag==1){
							/**** WITH LOAD PLAN NUMBER ***/
							$checkifvalidwbrs = query("select * from txn_load_plan_waybill where upper(waybill_number)=upper('$wbnumber') and load_plan_number='$ldpnumber'");
							
							if(getNumRows($checkifvalidwbrs)==1){



									$checkifaddedrs = query("select * from txn_manifest_waybill where manifest_number='$mftnumber' and waybill_number='$wbnumber'");

									if(getNumRows($checkifaddedrs)==0){

										$checkifinanothermanifestrs = query("select * 
											                                 from txn_manifest_waybill
										                                     left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
										                                     where waybill_number='$wbnumber' and txn_manifest.status!='VOID' and txn_manifest.status!='RETRIEVED'");

										//if(getNumRows($checkifinanothermanifestrs)==0){

											$insertpckgsrs = query("insert into txn_manifest_waybill_package_code(
																										manifest_number,
																										waybill_number,
																										package_code,
																										created_date,
																										created_by
																									   )
																 select '$mftnumber',
																         waybill_number,
																         code,
																         '$now',
																         '$userid'
																 from txn_waybill_package_code
																 where waybill_number='$wbnumber' and 
																       code not in (select package_code from txn_manifest_waybill_package_code where manifest_number='$mftnumber')");
											if($insertpckgsrs){
														$packagecodes = '';
														$rs1 = query("select group_concat(package_code) as packagecodes from txn_manifest_waybill_package_code where manifest_number='$mftnumber' and waybill_number='$wbnumber'");
														while($obj1=fetch($rs1)){
															$packagecodes = $obj1->packagecodes;
														}


														$mftwclass = new txn_manifest_waybill();
														$mftwclass->insert(array('',$mftnumber,$wbnumber,$now,$userid,$pouchsize));

														$systemlog = new system_log();
														$systemlog->logInfo('MANIFEST',"Added Waybill","Manifest No.: $mftnumber | Waybill No.: $wbnumber  |  Package(s): $packagecodes | Pouch Size: $pouchsize",$userid,$now);

														echo "success";
											}
											else{
												echo mysql_error();
											}



										/*}
										else{
											echo "hasactivemanifest";
										}*/
												
											

									}
									else{
										echo "alreadyadded";
									}

							
							}
							else{
								echo "invalidwaybill";
							}
							/**** WITH LOAD PLAN NUMBER - END ***/
						}
						else{
							/**** WITHOUT LOAD PLAN NUMBER ***/
							$checkifaddedrs = query("select * from txn_manifest_waybill where manifest_number='$mftnumber' and waybill_number='$wbnumber'");

							if(getNumRows($checkifaddedrs)==0){

								/*$checkifinanothermanifestrs = query("select * 
																	from txn_manifest_waybill
																	left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
																	where waybill_number='$wbnumber' and txn_manifest.status!='VOID' and txn_manifest.status!='RETRIEVED'");*/
								$checkifinanothermanifestrs = query("select * 
																	from txn_manifest_waybill
																	left join txn_manifest 
																	on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number 
																	where waybill_number='$wbnumber' and    
																	      txn_manifest.status='LOGGED' ");

								if(getNumRows($checkifinanothermanifestrs)==0){

									$checkifvalidwaybillrs = query("select * from txn_waybill 
																    where waybill_number='$wbnumber' and
																          origin_id='$mftoriginid' and
																          package_mode_of_transport='$mftmodeoftransportid'");

									if(getNumRows($checkifvalidwaybillrs)==1){

										while($obj4=fetch($checkifvalidwaybillrs)){
											$wbpouchsize = $obj4->pouch_size_id;
											$wbtype = $obj4->waybill_type;
											$wbstatus = $obj4->status;

										}

										$checkifhasfinalstatusrs = query("select * from no_update_status where status='$wbstatus'");

										if(getNumRows($checkifhasfinalstatusrs)>0){
											echo "hasfinalstatus@#&$wbstatus";
										}
										else{

											if($wbtype=='PARCEL'||$wbpouchsize==$pouchsize){


												$insertpckgsrs = query("insert into txn_manifest_waybill_package_code(
													manifest_number,
													waybill_number,
													package_code,
													created_date,
													created_by
													)
													select '$mftnumber',
													waybill_number,
													code,
													'$now',
													'$userid'
													from txn_waybill_package_code
													where waybill_number='$wbnumber' and 
													code not in (select package_code from txn_manifest_waybill_package_code where manifest_number='$mftnumber')");
												if($insertpckgsrs){
													$packagecodes = '';
													$rs1 = query("select group_concat(package_code) as packagecodes from txn_manifest_waybill_package_code where manifest_number='$mftnumber' and waybill_number='$wbnumber'");
													while($obj1=fetch($rs1)){
														$packagecodes = $obj1->packagecodes;
													}


													$mftwclass = new txn_manifest_waybill();
													$mftwclass->insert(array('',$mftnumber,$wbnumber,$now,$userid,$pouchsize));

													$systemlog = new system_log();
													$systemlog->logInfo('MANIFEST',"Added Waybill","Manifest No.: $mftnumber | Waybill No.: $wbnumber  |  Package(s): $packagecodes | Pouch Size: $pouchsize",$userid,$now);

													echo "success";
												}
												else{
													echo mysql_error();
												}

											}
											else{
												echo "pouchsizenotmatched";
											}
										}

									}
									else{
										echo "invalidwaybill";
									}



								}
								else{

									while($ob=fetch($checkifinanothermanifestrs)){
										$pendingmfttxn = $ob->manifest_number;
									}
									echo "hasactivemanifest@#&$pendingmfttxn";
								}



							}
							else{
								echo "alreadyadded";
							}
							/**** WITHOUT LOAD PLAN NUMBER - END ***/
						}

					}
					else{

						$checkifvalidwbrs = query("select * from txn_load_plan where upper(mawbl_bl)=upper('$wbnumber') and load_plan_number='$ldpnumber'");

						if(getNumRows($checkifvalidwbrs)==1){
							$mawbl = $wbnumber;
							$mawblrs = query("select * 
								              from txn_load_plan_waybill 
								              left join txn_load_plan on txn_load_plan.load_plan_number=txn_load_plan_waybill.load_plan_number
								              where txn_load_plan_waybill.load_plan_number='$ldpnumber' and 
								                    upper(mawbl_bl)=upper('$mawbl')");
							while($obj=fetch($mawblrs)){
								$wbnumber = $obj->waybill_number;

								$checkifaddedrs = query("select * from txn_manifest_waybill where manifest_number='$mftnumber' and waybill_number='$wbnumber'");

								if(getNumRows($checkifaddedrs)==0){

									$insertpckgsrs = query("insert into txn_manifest_waybill_package_code(
																								manifest_number,
																								waybill_number,
																								package_code,
																								created_date,
																								created_by
																							   )
														 select '$mftnumber',
														         waybill_number,
														         code,
														         '$now',
														         '$userid'
														 from txn_waybill_package_code
														 where waybill_number='$wbnumber' and 
														       code not in (select package_code from txn_manifest_waybill_package_code where manifest_number='$mftnumber')");
									if($insertpckgsrs){
												$packagecodes = '';
												$rs1 = query("select group_concat(package_code) as packagecodes from txn_manifest_waybill_package_code where manifest_number='$mftnumber' and waybill_number='$wbnumber'");
												while($obj1=fetch($rs1)){
													$packagecodes = $obj1->packagecodes;
												}


												$mftwclass = new txn_manifest_waybill();
												$mftwclass->insert(array('',$mftnumber,$wbnumber,$now,$userid,$pouchsize));

												$systemlog = new system_log();
												$systemlog->logInfo('MANIFEST',"Added Waybill","Manifest No.: $mftnumber | Waybill No.: $wbnumber  |  Package(s): $packagecodes | Pouch Size: $pouchsize",$userid,$now);

												
									}
									else{
										echo mysql_error();
									}
											
										

								}
								
							}
							echo "success";
						}
						else{
							echo "invalidwaybill";
						}
					}

				}
				else{
					echo "invalidmanifestnumber";
				}
			}
			else{
				echo "invalidpouchsize";
			}

			



		}
	}

	if(isset($_POST['deleteWaybillNumber'])){
		if($_POST['deleteWaybillNumber']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$wbnumbersid = $_POST['wbnumbersid'];
			$wbnumbersid = implode("','", $wbnumbersid);
			$mftnumber = '';
			$deletedwaybills = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$waybills = array();
			$getcorrespondingwbs = query("select * from txn_manifest_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($getcorrespondingwbs)){
				array_push($waybills, $obj->waybill_number);
			}
			$waybills = implode("','", $waybills);


			
			$rs = query("select group_concat(waybill_number) as deletedwaybills, manifest_number from txn_manifest_waybill where id in ('$wbnumbersid')");
			while($obj=fetch($rs)){
				$deletedwaybills = $obj->deletedwaybills;
				$mftnumber = $obj->manifest_number;
			}

			$deletedpackages = '';
			$rs1 = query("select group_concat(package_code) as deletedpackages from txn_manifest_waybill_package_code where waybill_number in ('$waybills') and manifest_number='$mftnumber'");
			while($obj=fetch($rs1)){
				$deletedpackages = $obj->deletedpackages;
			}

			$rs = query("delete from txn_manifest_waybill where id in ('$wbnumbersid')");
			if($rs){	

				query("delete from txn_manifest_waybill_package_code where manifest_number='$mftnumber' and waybill_number in ('$waybills')");

				$systemlog = new system_log();
				$systemlog->logInfo('MANIFEST',"Deleted Waybill(s)","Manifest No.: $mftnumber | Deleted Waybill(s): $deletedwaybills | Deleted Package(s): $deletedpackages",$userid,$now);
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
				$ldpnumber = '';
				$data = array();
				$createdby = 'none';
				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$motherbol = '';
				$ldpflag = 0;


				$checktxnrs = query("select txn_manifest.status,
				                            txn_manifest.created_by,
				                            txn_manifest.load_plan_number,
				                            txn_manifest.remarks, 
				                            txn_manifest.location_id,
				                            txn_manifest.load_plan_flag,
				                            txn_load_plan.mawbl_bl,
											txn_manifest.driver_name
					                 from txn_manifest 
					                 left join txn_load_plan on txn_load_plan.load_plan_number=txn_manifest.load_plan_number
					                 where txn_manifest.manifest_number='$txnnumber'");
			

				if(getNumRows($checktxnrs)==1){
					while($obj=fetch($checktxnrs)){
						$status = $obj->status;
						$createdby = $obj->created_by;
						$ldpnumber = $obj->load_plan_number;
						$mftremarks = $obj->remarks;
						$motherbol = $obj->mawbl_bl;
						$ldpflag = $obj->load_plan_flag;
						$mftlocid = $obj->location_id;
						$driver = $obj->driver_name;
					}

					if(trim($driver)!=''){
						if($ldpflag==1){
							$mftlocid = getInfo("txn_load_plan","location_id","where load_plan_number='$ldpnumber'");
						}

						if($status=='LOGGED'){

							$checkaddedwaybillrs = query("select * from txn_manifest_waybill where manifest_number='$txnnumber'");

							if(getNumRows($checkaddedwaybillrs)>0){

								$userhasaccess = hasAccess(USERID,'#useraccessmanagemanifest');
								if(USERID==$createdby||USERID==1||$userhasaccess==1){
									
									$waybillsincompletecodes = array();
									$packagecodecountcomplete = true;

									$checkpackagesrs = query("

																select txn_manifest_waybill.waybill_number,
																	numofpackage,
																	totalpackage 
																from txn_manifest_waybill
																left join (		
																			select txn_manifest_waybill_package_code.manifest_number,
																					txn_manifest_waybill_package_code.waybill_number,
																					count(package_code) as numofpackage
																			from txn_manifest_waybill_package_code
																			where txn_manifest_waybill_package_code.manifest_number='$txnnumber'
																			group by txn_manifest_waybill_package_code.waybill_number

																		) as numofpckgtbl
																on numofpckgtbl.manifest_number=txn_manifest_waybill.manifest_number and
																numofpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
																left join (
																			select waybill_number,
																					count(code) as totalpackage 
																			from txn_waybill_package_code 
																			group by waybill_number

																		) as totalpckgtbl
																on totalpckgtbl.waybill_number=txn_manifest_waybill.waybill_number
																where txn_manifest_waybill.manifest_number='$txnnumber'
															");

									while($obj2 = fetch($checkpackagesrs)){

										if($obj2->numofpackage!=$obj2->totalpackage){
											$packagecodecountcomplete = false;
											array_push($waybillsincompletecodes, $obj2->waybill_number);
										}
									}

									$incompletecodeswaybillstr = implode("<br>", $waybillsincompletecodes);

									if($packagecodecountcomplete==true){

											$rs = query("update txn_manifest set status='POSTED', updated_date='$now', updated_by='$userid' where id='$id'");

											if($rs){


												


												query("update txn_load_plan set manifest_number='$txnnumber', status='DISPATCHED' where load_plan_number='$ldpnumber'");
												$systemlog->logInfo('MANIFEST','Posted Manifest',"Manifest No.: ".$txnnumber,$userid,$now);
												$systemlog->logInfo('MANIFEST','Updated Manifest No. in Load Plan Transaction',"Load Plan No.: $ldpnumber  |  "."Manifest No.: ".$txnnumber,$userid,$now);

												/*$rs2 = query("select waybill_number
															from txn_manifest_waybill
															where manifest_number='$txnnumber'");
												while($obj2=fetch($rs2)){
													$lnwbnumber = $obj2->waybill_number;
													$systemlog->logInfo('MANIFEST','Mother BOL Tagging',"Waybill: $lnwbnumber | Mother Waybill: $motherbol | Load Plan No.: $ldpnumber | "."Manifest No.: ".$txnnumber,$userid,$now);
												}*/

												

												//if($loadplanmultiplemanifest==0){
													query("update txn_waybill 
														set status='DISPATCHED', 
															last_status_update_remarks='$mftremarks', 
															manifest_number='$txnnumber'
														where waybill_number in (select waybill_number 
																					from txn_manifest_waybill 
																					where manifest_number='$txnnumber')");

												
												//}
												/*else{
													$waybillcountinldp = 0;
													$waybillcountinmft = 0;

													$rs8 = query("select count(*) as waybillcount from txn_load_plan_waybill where load_plan_number='$ldpnumber'");
													while($obj8=fetch($rs8)){
														$waybillcountinldp = $obj8->waybillcount;
													}

													$rs8 = query("select count(*) as waybillcount 
																from txn_manifest_waybill 
																left join txn_manifest on txn_manifest.manifest_number=txn_manifest_waybill.manifest_number
																where txn_manifest.load_plan_number='$ldpnumber' and txn_manifest.status!='VOID'");
													while($obj8=fetch($rs8)){
														$waybillcountinmft = $obj8->waybillcount;
													}

													if($waybillcountinmft>0&&$waybillcountinldp>0&&$waybillcountinldp==$waybillcountinmft){
														query("update txn_waybill set status='DISPATCHED', manifest_number='$txnnumber' where waybill_number in (select waybill_number from txn_manifest_waybill where manifest_number='$txnnumber')");

														echo "updated";
													}
													else{
														echo "not updated";
													}
												}*/

												$waybillstathistory = new txn_waybill_status_history();
												$loopwaybillrs = query("select * from txn_waybill where waybill_number in (select waybill_number from txn_manifest_waybill where manifest_number='$txnnumber')");
												while($obj3=fetch($loopwaybillrs)){
													$waybillstathistory->insert(array('',$obj3->waybill_number,'DISPATCHED','DISPATCHED',$mftremarks,$now,$userid,$mftlocid,$txnnumber,'MANIFEST','NULL','NULL','NULL'));


												}

												$data = array("response"=>'success');

											}

									}
									else{
										$data = array("response"=>'incompletepackages',
													"details"=>$incompletecodeswaybillstr
													);
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
												
							"response"=>'nodriver'
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


	if(isset($_POST['voidTransaction'])){
		if($_POST['voidTransaction']=='dROi$nsFpo94dnels$4sRoi809srbmouS@1!'){
				$id = escapeString($_POST['txnid']);
				$txnnumber = escapeString($_POST['txnnumber']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
			

				$checktxnrs = query("select * from txn_manifest where id='$id' and manifest_number='$txnnumber'");


				if(getNumRows($checktxnrs)==1){

						$rs = query("update txn_manifest set status='VOID', updated_date='$now', updated_by='$userid', last_status_update_remarks='$remarks' where id='$id'");
						if($rs){
							$systemlog->logInfo('MANIFEST','Cancelled Manifest Transaction',"Manifest No.: ".$txnnumber." | Remarks: $remarks",$userid,$now);
							echo "success";
						}
					
				}
				else{
					echo "invalidtransaction";
				}

				
				
		}
	}

	if(isset($_POST['ManifestGetInfo'])){
		if($_POST['ManifestGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("select txn_manifest.id,
				                txn_manifest.document_date,
				                txn_manifest.remarks,
				                txn_manifest.load_plan_number,
				                txn_manifest.trucker_name,
				                txn_manifest.truck_type,
				                txn_manifest.plate_number,
				                txn_manifest.driver_name,
				                txn_manifest.contact_number,
				                txn_manifest.load_plan_flag,
				                txn_manifest.location_id,
				                txn_manifest.origin_id,
				                txn_manifest.agent_id,
				                txn_manifest.mode_of_transport_id,
				                txn_manifest.mawbl,
				                txn_manifest.eta,
				                txn_manifest.etd,
				                carrier.description as carrier,
				                vehicle_type.description as vehicletype,
				                origin_destination_port.description as origin,
				                mode_of_transport.description as modeoftransport,
				                agent.company_name as agent,
				                location.description as location
				         from txn_manifest
				         left join carrier on carrier.id=txn_manifest.trucker_name
				         left join vehicle_type on vehicle_type.id=txn_manifest.truck_type
				         left join origin_destination_port on origin_destination_port.id=txn_manifest.origin_id
				         left join mode_of_transport on mode_of_transport.id=txn_manifest.mode_of_transport_id
				         left join agent on agent.id=txn_manifest.agent_id
				         left join location on location.id=txn_manifest.location_id
				         where txn_manifest.id = '$id'
				 	    ");

			if(getNumRows($rs)==1){

				while($obj=fetch($rs)){
					$documentdate = dateFormat($obj->document_date, "m/d/Y");
					$dataarray = array(
										   "id"=>$obj->id,
										   "documentdate"=>$documentdate,
										   "loadplannumber"=>utfEncode($obj->load_plan_number),
										   "remarks"=>utfEncode($obj->remarks),
										   "truckername"=>utfEncode($obj->trucker_name),
										   "trucktype"=>utfEncode($obj->truck_type),
										   "platenumber"=>utfEncode($obj->plate_number),
										   "drivername"=>utfEncode($obj->driver_name),
										   "contactnumber"=>utfEncode($obj->contact_number),
										   "carrier"=>utfEncode($obj->carrier),
										   "vehicletype"=>utfEncode($obj->vehicletype),
										   "loadplanflag"=>utfEncode($obj->load_plan_flag),
										   "location"=>utfEncode($obj->location),
										   "locationid"=>utfEncode($obj->location_id),
										   "origin"=>utfEncode($obj->origin),
										   "originid"=>utfEncode($obj->origin_id),
										   "modeoftransport"=>utfEncode($obj->modeoftransport),
										   "modeoftransportid"=>utfEncode($obj->mode_of_transport_id),
										   "agent"=>utfEncode($obj->agent),
										   "agentid"=>utfEncode($obj->agent_id),
										   "mawbl"=>utfEncode($obj->mawbl),
										   "etd"=>utfEncode($obj->etd),
										   "eta"=>utfEncode($obj->eta),
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


	if(isset($_POST['saveEditManifestTransaction'])){
		if($_POST['saveEditManifestTransaction']=='oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@'){

			$response = array();
			$documentdate = dateString($_POST['documentdate']);
			$loadplannumber = escapeString($_POST['loadplannumber']);
			$remarks = escapeString($_POST['remarks']);
			$truckername = escapeString($_POST['truckername']);
			$trucktype = escapeString($_POST['trucktype']);
			$platenumber = escapeString($_POST['platenumber']);
			$drivername = escapeString($_POST['drivername']);
			$contactnumber = escapeString($_POST['contactnumber']);
			$loadplanflag = escapeString($_POST['loadplanflag']);
			$location = escapeString($_POST['location']);
			$origin = escapeString($_POST['origin']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
			$agent = escapeString($_POST['agent']);
			$etd = escapeString($_POST['etd'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['etd'])));
			$eta = escapeString($_POST['eta'])==''?'NULL':date('Y-m-d H:i:s', strtotime(escapeString($_POST['eta'])));
			$mawbl = escapeString($_POST['mawbl']);
			$id = escapeString($_POST['id']);

			$waybillnumbers = array();
			$packagecodes = array();


			
			$now = date("Y-m-d H:i:s");
			$userid = USERID;

			$hasmanifest = false;
			$manifestnumber = getInfo("txn_manifest","manifest_number","where id='$id'");

			$checkloadplanhasmanifestrs = query("select * from txn_manifest where load_plan_number='$loadplannumber' and status!='VOID' and id!='$id'");
			while($obj=fetch($checkloadplanhasmanifestrs)){
				$hasmanifest = true;
				$manifestnumber = $obj->manifest_number;
			}

			if($hasmanifest==true&&$loadplanflag==1){
				$response = array(
									   "response"=>'loadplanhasmanifest',
									   "manifestnumber"=>$manifestnumber
							 );
			}
			else if($etd=='1970-01-01 08:00:00'&&$loadplanflag==0){
				$response = array(
									   "response"=>'invalidetd'
							 );
			}
			else if($eta=='1970-01-01 08:00:00'&&$loadplanflag==0){
				$response = array(
									   "response"=>'invalideta'
							 );
			}
			else if($documentdate=='1970-01-01'){
				$response = array(
									   "response"=>'invaliddocdate'
							 );
			}
			else{

				if($loadplanflag==1){
					$mftclass = new txn_manifest();
					$systemlog = new system_log();

					$rs = query("select waybill_number
						         from txn_manifest_waybill 
						         where manifest_number='$manifestnumber' and 
						               waybill_number not in (
						               							select waybill_number 
						               							from txn_load_plan_waybill 
						               							where load_plan_number='$loadplannumber'
						               						  )");
					while($obj=fetch($rs)){
						array_push($waybillnumbers,$obj->waybill_number);
					}
					$waybillnumbers = implode(', ', $waybillnumbers);

					query("delete from txn_manifest_waybill 
						         where manifest_number='$manifestnumber' and 
						               waybill_number not in (
						               							select waybill_number 
						               							from txn_load_plan_waybill 
						               							where load_plan_number='$loadplannumber'
						               						  )");

					$systemlog->logEditedInfo($mftclass,$id,array($id,$manifestnumber,'NOCHANGE',$documentdate,$loadplannumber,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NOCHANGE',$truckername,$trucktype,$platenumber,$drivername,$contactnumber),'MANIFEST','Edited Manifest Header',$userid,$now);/// log should be before update is made
					$mftclass->update($id,array('NOCHANGE','NOCHANGE',$documentdate,$loadplannumber,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NOCHANGE',$truckername,$trucktype,$platenumber,$drivername,$contactnumber));

					/************ DELETE PACKAGE CODE - added 04/30/2018 ****************/
						$rs = query("select package_code 
						             from txn_manifest_waybill_package_code
						             where manifest_number='$manifestnumber' and 
						                   waybill_number not in (
						                   							select waybill_number 
						               							    from txn_load_plan_waybill 
						               							    where load_plan_number='$loadplannumber'

						                                          )");
						while($obj=fetch($rs)){
							array_push($packagecodes,$obj->package_code);
						}
						$packagecodes = implode(', ', $packagecodes);

						query("delete from txn_manifest_waybill_package_code 
						       where manifest_number='$manifestnumber' and 
						             waybill_number not in (
						               							select waybill_number 
						               							from txn_load_plan_waybill 
						               							where load_plan_number='$loadplannumber'
						               						 )");
					/******************* END - added 04/30/2018 ***********************/

					if(trim($waybillnumbers)!=''||trim($packagecodes)!=''){

						

						$systemlog->logInfo('MANIFEST',"Deleted Waybill(s) in Manifest","Manifest No.: $manifestnumber | Delete Waybill(s): $waybillnumbers; Deleted Package Code(s): $packagecodes",$userid,$now);
					}

					/*$mftclass->insert(array('',$manifestnumber,'LOGGED',$documentdate,$loadplannumber,$remarks,$now,$userid,'NULL','NULL','NULL'));
					$systemlog->logInfo('MANIFEST',"New Manifest","Manifest No.: $manifestnumber | Load Plan No.: $loadplannumber |  Document Date: $documentdate | Remarks: $remarks",$userid,$now);*/
					$response = array(
											   "response"=>'success',
											   "txnnumber"=>$manifestnumber
									 );
				}
				else{
					$mftclass = new txn_manifest();
					$systemlog = new system_log();

					$rs = query("select waybill_number
						         from txn_manifest_waybill 
						         where manifest_number='$manifestnumber'");
					while($obj=fetch($rs)){
						array_push($waybillnumbers,$obj->waybill_number);
					}
					$waybillnumbers = implode(', ', $waybillnumbers);

					query("delete txn_manifest_waybill 
						   from txn_manifest_waybill
						   left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
						   where (txn_waybill.origin_id!='$origin' or 
						         txn_waybill.package_mode_of_transport!='$modeoftransport') and 
						         txn_manifest_waybill.manifest_number='$manifestnumber'");

					$systemlog->logEditedInfo($mftclass,$id,array($id,$manifestnumber,'NOCHANGE',$documentdate,$loadplannumber,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NOCHANGE',$truckername,$trucktype,$platenumber,$drivername,$contactnumber,$loadplanflag,$location,$origin,$modeoftransport,$agent,$mawbl,$eta,$etd),'MANIFEST','Edited Manifest Header',$userid,$now);/// log should be before update is made
					$mftclass->update($id,array('NOCHANGE','NOCHANGE',$documentdate,$loadplannumber,$remarks,'NOCHANGE','NOCHANGE',$now,$userid,'NOCHANGE',$truckername,$trucktype,$platenumber,$drivername,$contactnumber,$loadplanflag,$location,$origin,$modeoftransport,$agent,$mawbl,$eta,$etd));

					/************ DELETE PACKAGE CODE - added 04/30/2018 ****************/
						$rs = query("select package_code 
						             from txn_manifest_waybill_package_code
						             where manifest_number='$manifestnumber'");
						while($obj=fetch($rs)){
							array_push($packagecodes,$obj->package_code);
						}
						$packagecodes = implode(', ', $packagecodes);

						query("delete txn_manifest_waybill_package_code
							   from txn_manifest_waybill_package_code 
							   left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill_package_code.waybill_number
						   where (txn_waybill.origin_id!='$origin' or 
						         txn_waybill.package_mode_of_transport!='$modeoftransport') and 
						         txn_manifest_waybill_package_code.manifest_number='$manifestnumber'");
					/******************* END - added 04/30/2018 ***********************/

					if(trim($waybillnumbers)!=''||trim($packagecodes)!=''){

						

						$systemlog->logInfo('MANIFEST',"Deleted Waybill(s) in Manifest","Manifest No.: $manifestnumber | Delete Waybill(s): $waybillnumbers; Deleted Package Code(s): $packagecodes",$userid,$now);
					}

					$response = array(
											   "response"=>'success',
											   "txnnumber"=>$manifestnumber
									 );
				}
			}


			print_r(json_encode($response));


			
		



		}
	}



	if(isset($_POST['deletePackageCodes'])){
		if($_POST['deletePackageCodes']=='dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$packagecodeids = $_POST['packagecodeids'];
			$packagecodeids = implode("','", $packagecodeids);
			$mftnumber = escapeString($_POST['mftnumber']);

			$deletedpackages = '';
			$deletedwaybills = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			$waybills = array();

			
			$rs = query("select group_concat(package_code) as deletedpackages from txn_manifest_waybill_package_code where id in ('$packagecodeids')");
			while($obj=fetch($rs)){
				$deletedpackages = $obj->deletedpackages;
			}

			

			$rs = query("delete from txn_manifest_waybill_package_code where id in ('$packagecodeids')");
			if($rs){	
				
				$checkwaybillswithoutpackagers = query("select * from txn_manifest_waybill where waybill_number not in (select waybill_number from txn_manifest_waybill_package_code where manifest_number='$mftnumber')  and manifest_number='$mftnumber'");

				while($obj = fetch($checkwaybillswithoutpackagers)){
					array_push($waybills, $obj->waybill_number);
				}

				if(count($waybills)>0){
					query("delete from txn_manifest_waybill where waybill_number not in (select waybill_number from txn_manifest_waybill_package_code where manifest_number='$mftnumber') and manifest_number='$mftnumber'");
					$deletedwaybills = implode(",", $waybills);

				}


				$systemlog = new system_log();
				$systemlog->logInfo('MANIFEST',"Deleted Packages","Manifest No.: $mftnumber |  Deleted Package(s): $deletedpackages  |  Deleted Waybill(s): $deletedwaybills",$userid,$now);
				echo "success";

			}
			else{
				echo mysql_error();
			}

			

		}
	}


	if(isset($_POST['insertNewPackageCode'])){
		if($_POST['insertNewPackageCode']=='ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$'){

			$mftnumber = escapeString($_POST['mftnumber']);
			$packagecode = escapeString(strtoupper($_POST['packagecode']));
			$wbnumber = '';
			$ldpnumber = '';
			$now = date('Y-m-d H:i:s');
			$userid = USERID;

			//$movementtypeid = '';
			$wbstatus = '';
			$loadplanflag = 0;



			$checkifvalidmftrs = query("select * from txn_manifest where manifest_number='$mftnumber'");

			if(getNumRows($checkifvalidmftrs)==1){

				while($obj=fetch($checkifvalidmftrs)){
					$ldpnumber = $obj->load_plan_number;
					$loadplanflag = $obj->load_plan_flag;
				}
		
				$checkifvalidcoders = query("select * from txn_waybill_package_code where code='$packagecode'");

				if(getNumRows($checkifvalidcoders)==1){

					while($obj=fetch($checkifvalidcoders)){
						$wbnumber = $obj->waybill_number;
					}

					$checkifvalidwbrs = query("select * from txn_load_plan_waybill where load_plan_number='$ldpnumber' and waybill_number='$wbnumber'");
					if(getNumRows($checkifvalidwbrs)==1||$loadplanflag==0){

							

									$checkifalreadyaddedrs = query("select * from txn_manifest_waybill_package_code where package_code='$packagecode' and manifest_number='$mftnumber'");

									if(getNumRows($checkifalreadyaddedrs)==0){

										$systemlog = new system_log();

										$checkifwbaddedrs = query("select * from txn_manifest_waybill where waybill_number='$wbnumber' and manifest_number='$mftnumber'");
										if(getNumRows($checkifwbaddedrs)==0){
											$mftwbclass = new txn_manifest_waybill();
								
											$systemlog->logInfo('MANIFEST',"Added Waybill - Package Code Added","Manifest No.: $mftnumber | Waybill No.: $wbnumber | Package Code: $packagecode",$userid,$now);
											$mftwbclass->insert(array('',$mftnumber,$wbnumber,$now,$userid));
										}


										$mftpckgclass = new txn_manifest_waybill_package_code();
										$mftpckgclass->insert(array('',$mftnumber,$wbnumber,$packagecode,$now,$userid));
										$systemlog->logInfo('MANIFEST',"Added Package Code","Manifest No.: $mftnumber | Waybill No.: $wbnumber | Package Code: $packagecode",$userid,$now);
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
					echo "invalidpackagecode";
				}



			}
			else{
				echo "invalidmanifest";
			}




		}
	}


	if(isset($_POST['updateManifestStatus'])){
		if($_POST['updateManifestStatus']=='kkjO#@siaah3h0$09odfj$owenxezpo92po@k@'){
				$manifestid = escapeString($_POST['manifestid']);
				$manifestnumber = escapeString($_POST['manifestnumber']);
				$status = escapeString($_POST['status']);
				$etd = date('Y-m-d H:i:s', strtotime(escapeString($_POST['etd'])));
				$eta = date('Y-m-d H:i:s', strtotime(escapeString($_POST['eta'])));
				$mawbbl = escapeString($_POST['mawbbl']);
				$remarks = escapeString($_POST['remarks']);

				$now = date('Y-m-d H:i:s');
				$userid = USERID;
				$systemlog = new system_log();
				$loadplannumber = '';

			

				$checktxnrs = query("select * from txn_manifest where id='$manifestid'");


				if(getNumRows($checktxnrs)==1){

					if((validateDateTime($etd)==1&&$etd!='1970-01-01 08:00:00')||$status!='TRANSFERRED'){
						if((validateDateTime($eta)==1&&$eta!='1970-01-01 08:00:00')||$status!='TRANSFERRED'){

							while($obj=fetch($checktxnrs)){
								$manifestnumber = $obj->manifest_number;
								$loadplannumber = $obj->load_plan_number;
							}

							$rs = query("update txn_manifest set status='$status', last_status_update_remarks='$remarks' where id='$manifestid'");
							if($rs){

								$systemlog = new system_log();
								$systemlog->logInfo('MANIFEST',"Manifest Status Update: $status","Manifest Number: ".$manifestnumber." | Mawbbl: $mawbbl | ETD: $etd |  ETA: $eta | Remarks: $remarks",$userid,$now);

								
								if($status=='TRANSFERRED'){
									query("update txn_load_plan set mawbl_bl='$mawbbl', etd='$etd', eta='$eta' where load_plan_number='$loadplannumber'");
								}

								$waybillstathistory = new txn_waybill_status_history();
								$loopwaybillrs = query("select txn_manifest_waybill.waybill_number 
									                    from txn_manifest_waybill
									                    left join txn_waybill on txn_waybill.waybill_number=txn_manifest_waybill.waybill_number
									                    where txn_manifest_waybill.manifest_number='$manifestnumber' and 
									                          txn_waybill.status not in (select status from no_update_status)");
								//txn_waybill.status!='DELIVERED' and txn_waybill.status!='RETURN TO SHIPPER' and txn_waybill.status!='RTS'

								//query("select waybill_number from txn_manifest_waybill where manifest_number='$manifestnumber'");
								while($obj3=fetch($loopwaybillrs)){
									$waybillstathistory->insert(array('',$obj3->waybill_number,$status,$status,$remarks,$now,$userid,'NULL',$manifestnumber,'MANIFEST','NULL','NULL','NULL'));
									
									query("update txn_waybill set status='$status', last_status_update_remarks='$remarks' where waybill_number='$obj3->waybill_number'");
								}


								echo "success";
							}

						}
						else{
							echo "invalideta";
						}
					}
					else{
						echo "invalidetd";
					}

				}
				else{
					echo "invalidmanifest";
				}

				
				
		}
	}

	if(isset($_POST['updateManifestWaybillRemarks'])){
		if($_POST['updateManifestWaybillRemarks']=='ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$'){
			$remarks = isset($_POST['remarks'])&&trim($_POST['remarks'])!=''?"'".escapeString($_POST['remarks'])."'":'NULL';
			$rowid = isset($_POST['rowid'])?trim($_POST['rowid']):'';

			query("update txn_manifest_waybill set remarks=$remarks where id='$rowid'");
			$dataarray = array(
								"response"=>'success',
								"updated_row"=>$rowid
							  );
			print_r(json_encode($dataarray));
		}
	}

?>