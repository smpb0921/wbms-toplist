<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/waybill-booklet-issuance.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['WaybillBookletIssuanceSaveEdit'])){
		if($_POST['WaybillBookletIssuanceSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$issuancedate = dateString($_POST['issuancedate']);
			$validitydate = dateString($_POST['validitydate']);
			$issuedto = escapeString($_POST['issuedto']);
			$location = isset($_POST['location'])&&trim($_POST['location'])!=''?strtoupper(escapeString($_POST['location'])):'NULL';
			$shipper = isset($_POST['shipper'])&&trim($_POST['shipper'])!=''?strtoupper(escapeString($_POST['shipper'])):'NULL';
			$courier = isset($_POST['courier'])&&trim($_POST['courier'])!=''?strtoupper(escapeString($_POST['courier'])):'NULL';
			$courierflag = escapeString($_POST['courierflag']);
			$startseries = escapeString($_POST['startseries']);
			$endseries = escapeString($_POST['endseries']);
			$remarks = escapeString($_POST['remarks']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$wbiclass = new waybill_booklet_issuance();
			$systemlog = new system_log();


			

			if(validateDate($issuancedate)==1&&$issuancedate!='1970-01-01'){
				if(validateDate($validitydate)==1&&$validitydate!='1970-01-01'){
					if(strtotime($validitydate)>=strtotime($issuancedate)){

						
			
						if($source=='add'){
							$wbiclass->insert(array('',$issuancedate,$validitydate,$issuedto,$location,$startseries,$endseries,$remarks,$userid,$now,'NULL','NULL',$courierflag,$shipper,$courier));
							$id = $wbiclass->getInsertId();
							$systemlog->logAddedInfo($wbiclass,array($id,$issuancedate,$validitydate,$issuedto,$location,$startseries,$endseries,$remarks,$userid,$now,'NULL','NULL',$courierflag,$shipper,$courier),'WAYBILL BOOKLET ISSUANCE','New Waybill Booklet Added',$userid,$now);
							echo "success";
						}
						else if($source=='edit'){
								$id = escapeString($_POST['id']);
							
								$systemlog->logEditedInfo($wbiclass,$id,array('',$issuancedate,$validitydate,$issuedto,$location,$startseries,$endseries,$remarks,'NOCHANGE','NOCHANGE',$userid,$now,$courierflag,$shipper,$courier),'WAYBILL BOOKLET ISSUANCE','Edited Waybill Booklet Info',$userid,$now);/// log should be before update is made
								$wbiclass->update($id,array($issuancedate,$validitydate,$issuedto,$location,$startseries,$endseries,$remarks,'NOCHANGE','NOCHANGE',$userid,$now,$courierflag,$shipper,$courier));
								echo "success";

						}

					}
					else{
						echo "invalidvaliditydate";
					}
				}
				else{
					echo "invalidvaliditydate";
				}
			}
			else{
				echo "invalidissuancedate";
			}
		}
			
	}

	if(isset($_POST['WaybillBookletIssuanceGetInfo'])){
		if($_POST['WaybillBookletIssuanceGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("   select waybill_booklet_issuance.id,
								   waybill_booklet_issuance.issuance_date, 
								   waybill_booklet_issuance.validity_date,
								   waybill_booklet_issuance.issued_to,
								   waybill_booklet_issuance.location_id,
								   location.description as location,
								   location.code as location_code,
								   waybill_booklet_issuance.booklet_start_series,
								   waybill_booklet_issuance.booklet_end_series,
								   waybill_booklet_issuance.remarks,
								   waybill_booklet_issuance.courier_flag,
								   waybill_booklet_issuance.shipper_id,
								   shipper.account_name as shipper,
								   waybill_booklet_issuance.courier,
								   waybill_booklet_issuance.created_date,
								   concat(cuser.first_name,' ',cuser.last_name) as created_by,
								   waybill_booklet_issuance.updated_date,
								   concat(uuser.first_name,' ',uuser.last_name) as updated_by
					from waybill_booklet_issuance
					left join location on location.id=waybill_booklet_issuance.location_id
					left join user as cuser on cuser.id=waybill_booklet_issuance.created_by
					left join user as uuser on uuser.id=waybill_booklet_issuance.updated_by
					left join shipper on shipper.id=waybill_booklet_issuance.shipper_id
					where waybill_booklet_issuance.id='$id'");
			if(getNumRows($rs)==1){
				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>$obj->id,
										   "issuancedate"=>dateFormat($obj->issuance_date,'m/d/Y'),
										   "validitydate"=>dateFormat($obj->validity_date,'m/d/Y'),
										   "issuedto"=>utfEncode($obj->issued_to),
										   "courierflag"=>utfEncode($obj->courier_flag),
										   "locationid"=>utfEncode($obj->location_id),
										   "location"=>utfEncode($obj->location),
										   "shipperid"=>utfEncode($obj->shipper_id),
										   "shipper"=>utfEncode($obj->shipper),
										   "courier"=>utfEncode($obj->courier),
										   "startseries"=>utfEncode($obj->booklet_start_series),
										   "endseries"=>utfEncode($obj->booklet_end_series),
										   "remarks"=>utfEncode($obj->remarks),
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




	if(isset($_POST['deleteSelectedRows'])){
        if($_POST['deleteSelectedRows']=='skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs'){
        	@$data = $_POST['data'];
	        $itemsiterate = count($data);

	        for($i=0;$i<$itemsiterate;$i++){
	        	$id = mysql_real_escape_string($data[$i]);

	        	$rs = query("delete from waybill_booklet_issuance where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

