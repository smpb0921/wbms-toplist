<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/published-rate.class.php");
    include("../classes/published-rate-freight-charge.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['PublishedRateSaveEdit'])){
		if($_POST['PublishedRateSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$tpl = escapeString($_POST['tpl']);
			$zone = escapeString($_POST['zone']);
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
			$prclass = new published_rate();
			$systemlog = new system_log();

			if($fixedrateflag==0){
				$freightrate = 0;
			}



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
					$query = "select published_rate.id 
					          from published_rate 
					          where origin_id='$origin' and 
					                zone_id='$zone' and 
					                third_party_logistic_id='$tpl' and
					                id!='$id' and 
					                waybill_type='$wbtype' $pouchsizecondition";
					//$pouchsizecondition					
			}
			else{
					$query = "select published_rate.id
					          from published_rate 
					          where origin_id='$origin' and 
					                zone_id='$zone' and 
					                third_party_logistic_id='$tpl' and
					                waybill_type='$wbtype' $pouchsizecondition";
					//$pouchsizecondition
			}
			


			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
						$prclass->insert(array('',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl));
						$id = $prclass->getInsertId();
						$systemlog->logAddedInfo($prclass,array($id,$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,$userid,$now,'NULL','NULL',$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl),'PUBLISHED RATE','New Published Rate Added',$userid,$now);

						echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($prclass,$id,array('',$origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl),'PUBLISHED RATE','Edited Published Rate Info',$userid,$now);/// log should be before update is made
						$prclass->update($id,array($origin,$destination,$modeoftransport,$freightcomputation,$fixedrateflag,$valuation,$freightrate,$insurancerate,$fuelrate,$bunkerrate,$minimumrate,'NOCHANGE','NOCHANGE',$userid,$now,$rushflag,$pulloutflag,$wbtype,$pouchsize,$fixedrateamount,$pulloutfee,$odarate,$services,$zone,$tpl));


						
						echo "success";

						if($fixedrateflag==1){
							query("delete from published_rate_freight_charge where published_rate_id='$id'");
						}

				}


			}
			else{
				echo "rateexists";
			}
		}
			
	}


	if(isset($_POST['PublishedRateGetInfo'])){
		if($_POST['PublishedRateGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("       select published_rate.id,
									   published_rate.origin_id, 
									   published_rate.destination_id,
									   published_rate.mode_of_transport_id,
									   published_rate.freight_computation,
									   published_rate.fixed_rate_flag,
									   published_rate.valuation,
									   published_rate.freight_rate,
									   published_rate.insurance_rate,
									   published_rate.fuel_rate,
									   published_rate.bunker_rate,
									   published_rate.rush_flag,
									   published_rate.pull_out_flag,
									   published_rate.minimum_rate,
									   published_rate.fixed_rate_amount,
									   published_rate.pull_out_fee,
									   published_rate.oda_rate,
									   published_rate.waybill_type,
									   published_rate.pouch_size_id,
									   published_rate.services_id,
									   origin.description as origin_desc,
									   destination.description as destination_desc,
									   mode_of_transport.description as modeoftransport,
									   pouch_size.description as pouch_size_desc,
									   services.description as servicesdesc,
									   published_rate.third_party_logistic_id,
									   third_party_logistic.description as thirdpartylogistic,
									   published_rate.zone_id,
									   zone.description as zone
								from published_rate
								left join origin_destination_port as origin on origin.id=published_rate.origin_id
								left join origin_destination_port as destination on destination.id=published_rate.destination_id
								left join mode_of_transport on mode_of_transport.id=published_rate.mode_of_transport_id
								left join pouch_size on pouch_size.id=published_rate.pouch_size_id
								left join services on services.id=published_rate.services_id
								left join zone on zone.id=published_rate.zone_id
								left join third_party_logistic on third_party_logistic.id=published_rate.third_party_logistic_id
								where published_rate.id='$id'
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

	        	$rs = query("delete from published_rate where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }

    if(isset($_POST['deletePublishedRateFreightCharge'])){
        if($_POST['deletePublishedRateFreightCharge']=='$jhfoFIsmdlPE#9s3#7skoRboIh4!j3sio$*yhs'){

        	$checkaccess = userAccess(USERID,'.deletepublishedratebtn');

			if($checkaccess==false){

	        	@$data = $_POST['data'];
		        $itemsiterate = count($data);

		        for($i=0;$i<$itemsiterate;$i++){
		        	$id = mysql_real_escape_string($data[$i]);

		        	$rs = query("delete from published_rate_freight_charge where id='$id'");
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

    if(isset($_POST['AddEditPublishedRateFreightCharge'])){
		if($_POST['AddEditPublishedRateFreightCharge']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = 'add';
			$publishedrateid = escapeString($_POST['publishedrateid']);
			$fromkg = escapeString($_POST['fromkg']);
			$fromkg = round($fromkg,4);
			$tokg = escapeString($_POST['tokg']);
			$tokg = round($tokg,4);
			$freightcharge = escapeString($_POST['freightcharge']);
			$excessweightcharge = escapeString($_POST['excessweightcharge']);

			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$pubratefcclass = new published_rate_freight_charge();
			$systemlog = new system_log();
			

			$source = escapeString($_POST['source']);

			//echo $publishedrateid.'<--';

			$checkaccess = userAccess(USERID,'.editpublishedratebtn');

			if($checkaccess==false){
			
				if($source=='add'){

					$pubratefcclass->insert(array('',$publishedrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'));
					$id = $pubratefcclass->getInsertId();
					$systemlog->logAddedInfo($pubratefcclass,array($id,$publishedrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,$now,$userid,'NULL','NULL'),'PUBLISHED RATE - FREIGHT CHARGE','New Published Rate - Freight Charge',$userid,$now);

					echo "success";
				}
				else if($source=='edit'){
						$freightchargeID = escapeString($_POST['freightchargeID']);
		
						$systemlog->logEditedInfo($pubratefcclass,$freightchargeID,array($freightchargeID,$publishedrateid,$fromkg,$tokg,$freightcharge,$excessweightcharge,'NOCHANGE','NOCHANGE',$now,$userid),'PUBLISHED RATE - FREIGHT CHARGE','Edited Published Rate - Freight Charge',$userid,$now);/// log should be before update is made
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

