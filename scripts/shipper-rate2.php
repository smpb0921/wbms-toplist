<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/shipper-rate2.class.php");
    include("../classes/shipper-rate-freight-charge2.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['shipperRateSaveEdit'])){
		if($_POST['shipperRateSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$shipper = escapeString($_POST['shipper']);
			$source = escapeString($_POST['source']);
			$tpl = escapeString($_POST['tpl']);
			$zone = escapeString($_POST['zone']);
			$shipmenttype = escapeString($_POST['shipmenttype']);
			$shipmentmode = escapeString($_POST['shipmentmode']);
			$origin = escapeString($_POST['origin']);
			$destination = 'NULL';//escapeString($_POST['destination']);
			$modeoftransport = 'NULL';//escapeString($_POST['modeoftransport']);
			$services = 'NULL';//escapeString($_POST['services']);
			$freightcomputation = 'NULL';//escapeString($_POST['freightcomputation']);
			$fixedrateflag = escapeString($_POST['fixedrateflag']);
			$fixedrateflag = $fixedrateflag=='true'?1:0;
			$valuation = (trim($_POST['valuation'])>=0)?escapeString($_POST['valuation']):0;
			$freightrate = (trim($_POST['freightrate'])>=0)?escapeString($_POST['freightrate']):0;
			$insurancerate = (trim($_POST['insurancerate'])>=0)?escapeString($_POST['insurancerate']):0;
			$fuelrate = (trim($_POST['fuelrate'])>=0)?escapeString($_POST['fuelrate']):0;
			$bunkerrate = (trim($_POST['bunkerrate'])>=0)?escapeString($_POST['bunkerrate']):0;
			$minimumrate = (trim($_POST['minimumrate'])>=0)?escapeString($_POST['minimumrate']):0;
			$rushflag = 'NULL';//escapeString($_POST['rushflag']);
			$rushflag = 'NULL';//$rushflag=='true'?1:0;
			$pulloutflag = 'NULL';//escapeString($_POST['pulloutflag']);
			$pulloutflag = 'NULL';//$pulloutflag=='true'?1:0;
			$wbtype = escapeString($_POST['wbtype']);
			$pouchsize = escapeString($_POST['pouchsize']);
			$pouchsize = $pouchsize>0?$pouchsize:'NULL';
			$fixedrateamount = 0;//escapeString($_POST['fixedrateamount']);
			$pulloutfee = 0;//escapeString($_POST['pulloutfee']);
			$odarate = 0;//escapeString($_POST['odarate']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$prclass = new shipper_rate();
			$systemlog = new system_log();

			$checkshipperrs = query("select * from shipper where id='$shipper'");

			if(getNumRows($checkshipperrs)==1){

				if($fixedrateflag==0){
					$freightrate = 0;
				}

				/*if($wbtype=='DOCUMENT'){
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
				}*/

				/*if($pouchsize==''||$pouchsize=='NULL'){
					$pouchsizecondition = " and pouch_size_id is null";
				}
				else{*/
					$pouchsizecondition = " and pouch_size_id='$pouchsize'";
				//}

				/*if($wbtype=='PARCEL'){
					$pouchsizecondition = '';
				}*/
				

				if($source=='edit'){
						$id = escapeString($_POST['id']);
						$query = "select shipper_rate.id 
						          from shipper_rate 
						          where origin_id='$origin' and 
						                zone_id='$zone' and 
						                third_party_logistic_id='$tpl' and
										shipment_type_id='$shipmenttype' and
										shipment_mode_id='$shipmentmode' and
						                id!='$id' and 
						                shipper_id='$shipper' and
						                waybill_type='$wbtype' $pouchsizecondition";
						//$pouchsizecondition					
				}
				else{
						$query = "select shipper_rate.id
						          from shipper_rate 
						          where origin_id='$origin' and 
						                zone_id='$zone' and 
						                third_party_logistic_id='$tpl' and
						                shipper_id='$shipper' and
										shipment_type_id='$shipmenttype' and
										shipment_mode_id='$shipmentmode' and
						                waybill_type='$wbtype' $pouchsizecondition";
						//$pouchsizecondition
				}
				


				$rs = query($query);

				if(getNumRows($rs)==0){
				
					if($source=='add'){
							$prclass->insert(array('',$shipper,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode));
							$id = $prclass->getInsertId();
							$systemlog->logAddedInfo($prclass,array($id,$shipper,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode),'shipper RATE','New shipper Rate Added',$userid,$now);

							echo "success";
					}
					else if($source=='edit'){
							$id = escapeString($_POST['id']);
						
							$systemlog->logEditedInfo($prclass,$id,array($id,$shipper,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode),'SHIPPER RATE','Edited Shipper Rate Info',$userid,$now);/// log should be before update is made
							$prclass->update($id,array($shipper,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode));


							
							echo "success";

							if($fixedrateflag==1){
								query("delete from shipper_rate_freight_charge where shipper_rate_id='$id'");
							}

					}


				}
				else{
					echo "rateexists";
				}
			}
			else{
				echo "invalidshipper";
			}
		}
			
	}


	if(isset($_POST['shipperRateGetInfo'])){
		if($_POST['shipperRateGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select shipper_rate.id,
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
									   shipper_rate.rush_flag,
									   shipper_rate.pull_out_flag,
									   shipper_rate.minimum_rate,
									   shipper_rate.fixed_rate_amount,
									   shipper_rate.pull_out_fee,
									   shipper_rate.oda_rate,
									   shipper_rate.shipper_id,
									   shipper_rate.waybill_type,
									   shipper_rate.pouch_size_id,
									   shipper_rate.services_id,
									   origin.description as origin_desc,
									   destination.description as destination_desc,
									   mode_of_transport.description as modeoftransport,
									   pouch_size.description as pouch_size_desc,
									   services.description as servicesdesc,
									   shipper_rate.third_party_logistic_id,
									   third_party_logistic.description as thirdpartylogistic,
									   shipper_rate.zone_id,
									   zone.description as zone,
									   shipper.account_name as shippername,
									   shipper_rate.shipment_type_id,
									   shipment_type.code as shipmenttype,
									   shipnment_mode.code as shipmentmode
								from shipper_rate
								left join shipper on shipper.id=shipper_rate.shipper_id
								left join origin_destination_port as origin on origin.id=shipper_rate.origin_id
								left join origin_destination_port as destination on destination.id=shipper_rate.destination_id
								left join mode_of_transport on mode_of_transport.id=shipper_rate.mode_of_transport_id
								left join pouch_size on pouch_size.id=shipper_rate.pouch_size_id
								left join services on services.id=shipper_rate.services_id
								left join zone on zone.id=shipper_rate.zone_id
								left join third_party_logistic on third_party_logistic.id=shipper_rate.third_party_logistic_id
								left join shipment_type on shipment_type.id=shipper_rate.shipment_type_id
								left join shipment_mode on shipment_mode.id=shipper_rate.shipment_mode_id
								where shipper_rate.id='$id'
				 	    ");

			if(getNumRows($rs)==1){
				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>$obj->id,
										   "shipper"=>utfEncode($obj->shippername),
										   "shipperid"=>$obj->shipper_id,
										   "origin"=>utfEncode($obj->origin_desc),
										   "originid"=>$obj->origin_id,
										   "destination"=>utfEncode($obj->destination_desc),
										   "destinationid"=>$obj->destination_id,
										   "modeoftransport"=>utfEncode($obj->modeoftransport),
										   "modeoftransportid"=>$obj->mode_of_transport_id,
										   "services"=>utfEncode($obj->servicesdesc),
										   "servicesid"=>$obj->services_id,
										   "freightcomputation"=>$obj->freight_computation,
										   "fixedrateflag"=>$obj->fixed_rate_flag,
										   "rushflag"=>$obj->rush_flag,
										   "pulloutflag"=>$obj->pull_out_flag,
										   "valuation"=>$obj->valuation,
										   "freightrate"=>$obj->freight_rate,
										   "insurancerate"=>$obj->insurance_rate,
										   "fuelrate"=>$obj->fuel_rate,
										   "bunkerrate"=>$obj->bunker_rate,
										   "minimumrate"=>$obj->minimum_rate,
										   "pouchsizeid"=>$obj->pouch_size_id,
										   "pouchsize"=>utfEncode($obj->pouch_size_desc),
										   "zoneid"=>$obj->zone_id,
										   "zone"=>utfEncode($obj->zone),
										   "tplid"=>$obj->third_party_logistic_id,
										   "tpl"=>utfEncode($obj->thirdpartylogistic),
										   "waybilltype"=>$obj->waybill_type,
										   "fixedrateamount"=>$obj->fixed_rate_amount,
										   "pulloutfee"=>$obj->pull_out_fee,
										   "odarate"=>$obj->oda_rate,
										   "shipmenttypeid"=>$obj->shipment_type_id,
										   "shipmenttype"=>utfEncode($obj->shipmenttype),
										   "shipmentmodeid"=>$obj->shipment_mode_id,
										   "shipmentmode"=>utfEncode($obj->shipmentmode),
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

	/*if(isset($_POST['deleteselected'])){
		$idarray = $_POST['id'];
		$tmp = array();
		foreach ($idarray as $id) {
			$delete = escapeString($id);
			array_push($tmp, $delete);
		}
		$deleteids = "(".implode(',', $tmp).")";
		$ugclass = new user_group();
		$ugclass->deleteMultiple($deleteids);
		echo "success";

	}*/
	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);

	        	$rs = query("delete from shipper_rate where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['deleteshipperRateFreightCharge'])){
        if($_POST['deleteshipperRateFreightCharge']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){

        	$checkaccess = userAccess(USERID,'.deleteshipperratebtn');

			if($checkaccess==false){

	        	@$data = $_POST['data'];
		        $itemsiterate = count($data);

		        for($i=0;$i<$itemsiterate;$i++){
		        	$id = mysql_real_escape_string($data[$i]);

		        	$rs = query("delete from shipper_rate_freight_charge where id='$id'");
		        	if(!$rs){
		        		echo "ID: $id -".mysql_error()."\n";
		        	}

		        }

		        echo "success";
		    }
		    else{
		    	echo "noaccess";
		    }
       	}
    }

    if(isset($_POST['AddEditshipperRateFreightCharge'])){
		if($_POST['AddEditshipperRateFreightCharge']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = 'add';
			$shipperrateid = escapeString($_POST['shipperrateid']);
			$fromkg = escapeString($_POST['fromkg']);
			$fromkg = round($fromkg,4);
			$tokg = escapeString($_POST['tokg']);
			$tokg = round($tokg,4);
			$freightcharge = escapeString($_POST['freightcharge']);
			$excessweightcharge = escapeString($_POST['excessweightcharge']);

			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$pubratefcclass = new shipper_rate_freight_charge();
			$systemlog = new system_log();
			

			$source = escapeString($_POST['source']);

			//echo $shipperrateid.'<--';

			$checkaccess = userAccess(USERID,'.editshipperratebtn');

			if($checkaccess==false){
			
				if($source=='add'){

					$pubratefcclass->insert(array('',$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'));
					$id = $pubratefcclass->getInsertId();
					$systemlog->logAddedInfo($pubratefcclass,array($id,$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'),'SHIPPER RATE - FREIGHT CHARGE','New shipper Rate - Freight Charge',$userid,$now);

					echo "success";
				}
				else if($source=='edit'){
						$freightchargeID = escapeString($_POST['freightchargeID']);
		
						$systemlog->logEditedInfo($pubratefcclass,$freightchargeID,array($freightchargeID,$shipperrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid),'SHIPPER RATE - FREIGHT CHARGE','Edited Shipper Rate - Freight Charge',$userid,$now);/// log should be before update is made
						$pubratefcclass->update($freightchargeID,array('NOCHANGE',$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid));
						echo "success";

				}
			}
			else{
				echo "noaccess";
			}
			
		}
			
	}



?>

