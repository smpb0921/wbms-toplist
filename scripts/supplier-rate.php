<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/supplier-rate.class.php");
    include("../classes/supplier-rate-freight-charge.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['supplierRateSaveEdit'])){
		if($_POST['supplierRateSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$tpl = escapeString($_POST['tpl']);
			$zone = escapeString($_POST['zone']);
			$shipmenttype = escapeString($_POST['shipmenttype']);
			$shipmentmode = escapeString($_POST['shipmentmode']);
			$origin = escapeString($_POST['origin']);
			$destination = 'NULL';//escapeString($_POST['destination']);
			$modeoftransport = escapeString($_POST['modeoftransport']);
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
			$prclass = new supplier_rate();
			$systemlog = new system_log();

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
			/*}

			if($wbtype=='PARCEL'){
				$pouchsizecondition = '';
			}*/
			

			if($source=='edit'){
					$id = escapeString($_POST['id']);
					$query = "select supplier_rate.id 
					          from supplier_rate 
					          where origin_id='$origin' and 
					                zone_id='$zone' and 
					                third_party_logistic_id='$tpl' and
									shipment_type_id='$shipmenttype' and
									shipment_mode_id='$shipmentmode' and
									mode_of_transport_id='$modeoftransport' and
					                id!='$id' and 
					                waybill_type='$wbtype' $pouchsizecondition";
					//$pouchsizecondition					
			}
			else{
					$query = "select supplier_rate.id
					          from supplier_rate 
					          where origin_id='$origin' and 
					                zone_id='$zone' and 
					                third_party_logistic_id='$tpl' and
									shipment_type_id='$shipmenttype' and
									shipment_mode_id='$shipmentmode' and
									mode_of_transport_id='$modeoftransport' and
					                waybill_type='$wbtype' $pouchsizecondition";
					//$pouchsizecondition
			}
			


			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
						$prclass->insert(array('',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode));
						$id = $prclass->getInsertId();
						$systemlog->logAddedInfo($prclass,array($id,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode),'SUPPLIER RATE','New Supplier Rate Added',$userid,$now);

						echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($prclass,$id,array('',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode),'SUPPLIER RATE','Edited Supplier Rate Info',$userid,$now);/// log should be before update is made
						$prclass->update($id,array($origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl,$shipmenttype,$shipmentmode));


						
						echo "success";

						if($fixedrateflag==1){
							query("delete from supplier_rate_freight_charge where supplier_rate_id='$id'");
						}

				}


			}
			else{
				echo "rateexists";
			}
		}
			
	}


	if(isset($_POST['supplierRateGetInfo'])){
		if($_POST['supplierRateGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select supplier_rate.id,
									   supplier_rate.origin_id, 
									   supplier_rate.destination_id,
									   supplier_rate.mode_of_transport_id,
									   supplier_rate.freight_computation,
									   supplier_rate.fixed_rate_flag,
									   supplier_rate.valuation,
									   supplier_rate.freight_rate,
									   supplier_rate.insurance_rate,
									   supplier_rate.fuel_rate,
									   supplier_rate.bunker_rate,
									   supplier_rate.rush_flag,
									   supplier_rate.pull_out_flag,
									   supplier_rate.minimum_rate,
									   supplier_rate.fixed_rate_amount,
									   supplier_rate.pull_out_fee,
									   supplier_rate.oda_rate,
									   supplier_rate.waybill_type,
									   supplier_rate.pouch_size_id,
									   supplier_rate.services_id,
									   origin.description as origin_desc,
									   destination.description as destination_desc,
									   mode_of_transport.description as modeoftransport,
									   pouch_size.description as pouch_size_desc,
									   services.description as servicesdesc,
									   supplier_rate.third_party_logistic_id,
									   third_party_logistic.description as thirdpartylogistic,
									   supplier_rate.zone_id,
									   zone.description as zone,
									   supplier_rate.shipment_type_id,
									   shipment_type.code as shipmenttype,
									   supplier_rate.shipment_mode_id,
									   shipment_mode.code as shipmentmode
								from supplier_rate
								left join origin_destination_port as origin on origin.id=supplier_rate.origin_id
								left join origin_destination_port as destination on destination.id=supplier_rate.destination_id
								left join mode_of_transport on mode_of_transport.id=supplier_rate.mode_of_transport_id
								left join pouch_size on pouch_size.id=supplier_rate.pouch_size_id
								left join services on services.id=supplier_rate.services_id
								left join zone on zone.id=supplier_rate.zone_id
								left join third_party_logistic on third_party_logistic.id=supplier_rate.third_party_logistic_id
								left join shipment_type on shipment_type.id=supplier_rate.shipment_type_id
								left join shipment_mode on shipment_mode.id=supplier_rate.shipment_mode_id
								where supplier_rate.id='$id'
				 	    ");

			if(getNumRows($rs)==1){
				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>$obj->id,
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

	        	$rs = query("delete from supplier_rate where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['deletesupplierRateFreightCharge'])){
        if($_POST['deletesupplierRateFreightCharge']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){

        	$checkaccess = userAccess(USERID,'.deletesupplierratebtn');

			if($checkaccess==false){

	        	@$data = $_POST['data'];
		        $itemsiterate = count($data);

		        for($i=0;$i<$itemsiterate;$i++){
		        	$id = mysql_real_escape_string($data[$i]);

		        	$rs = query("delete from supplier_rate_freight_charge where id='$id'");
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

    if(isset($_POST['AddEditsupplierRateFreightCharge'])){
		if($_POST['AddEditsupplierRateFreightCharge']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = 'add';
			$supplierrateid = escapeString($_POST['supplierrateid']);
			$fromkg = escapeString($_POST['fromkg']);
			$fromkg = round($fromkg,4);
			$tokg = escapeString($_POST['tokg']);
			$tokg = round($tokg,4);
			$freightcharge = escapeString($_POST['freightcharge']);
			$excessweightcharge = escapeString($_POST['excessweightcharge']);

			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$pubratefcclass = new supplier_rate_freight_charge();
			$systemlog = new system_log();
			

			$source = escapeString($_POST['source']);

			//echo $supplierrateid.'<--';

			$checkaccess = userAccess(USERID,'.editsupplierratebtn');

			if($checkaccess==false){
			
				if($source=='add'){

					$pubratefcclass->insert(array('',$supplierrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'));
					$id = $pubratefcclass->getInsertId();
					$systemlog->logAddedInfo($pubratefcclass,array($id,$supplierrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'),'SUPPLIER RATE - FREIGHT CHARGE','New Supplier Rate - Freight Charge',$userid,$now);

					echo "success";
				}
				else if($source=='edit'){
						$freightchargeID = escapeString($_POST['freightchargeID']);
		
						$systemlog->logEditedInfo($pubratefcclass,$freightchargeID,array($freightchargeID,$supplierrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid),'SUPPLIER RATE - FREIGHT CHARGE','Edited Supplier Rate - Freight Charge',$userid,$now);/// log should be before update is made
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

