<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");



	$rs = query("select * from txn_waybill_status_history 
		         where remarks like '%SOURCE WAYBILL MOVEMENT%' or 
		               remarks like '%POSTED SOURCE MANIFEST%' or 
		               remarks like '%Manifest status update%'");

	while($obj=fetch($rs)){
		$rowid = $obj->id;
		$remarks = trim(strtoupper($obj->remarks));
		$sourcenum = '';
		$newremarks = '';
		$createddate = $obj->created_date;


		if(strpos($remarks, 'POSTED SOURCE MANIFEST:')!== false){
			$sourcenum = str_replace('POSTED SOURCE MANIFEST:','', $remarks);
			$sourcenum = trim($sourcenum);

			$newremarks = getInfo("txn_manifest","remarks","where manifest_number='$sourcenum'");
			$newremarks = trim($newremarks);
			$newremarks = escapeString($newremarks);

			$locid = getInfo("txn_load_plan","location_id","where manifest_number='$sourcenum'");
			query("update txn_waybill_status_history 
				   set remarks='$newremarks',
				       location_id='$locid',
				       source='$sourcenum',
				       source_type='MANIFEST'
				   where id='$rowid'");

			
			
		}
		else if(strpos($remarks, 'SOURCE WAYBILL MOVEMENT:')!== false){
			$sourcenum = str_replace('SOURCE WAYBILL MOVEMENT:','', $remarks);
			$sourcenum = trim($sourcenum);

			$newremarks = getInfo("txn_waybill_movement","remarks","where waybill_movement_number='$sourcenum'");
			$newremarks = trim($newremarks);
			$newremarks = escapeString($newremarks);

			$locid = getInfo("txn_waybill_movement","location_id","where waybill_movement_number='$sourcenum'");
			query("update txn_waybill_status_history 
				   set remarks='$newremarks',
				       location_id='$locid',
				       source='$sourcenum',
				       source_type='WAYBILL MOVEMENT'
				   where id='$rowid'");

			
			
		}
		else if(strpos($remarks, 'MANIFEST STATUS UPDATE:')!==false){
			$sourcenum = str_replace('MANIFEST STATUS UPDATE:','', $remarks);
			$sourcenum = trim($sourcenum);

			$systemlogid = '';
			$rs1 = query("select * from system_log where query like '%$sourcenum%' and date_source='$createddate'");
			while($obj1=fetch($rs1)){
				$systemlogid = $obj1->id;
				$newremarks = $obj1->query;
				$newremarks = trim($newremarks);
				$newremarks = escapeString($newremarks);
			}
			
			$newremarks = substr($newremarks, strpos($newremarks, 'Remarks:')+8);
			$newremarks = trim($newremarks);

			$locid = getInfo("txn_load_plan","location_id","where manifest_number='$sourcenum'");
			query("update txn_waybill_status_history 
				   set remarks='$newremarks',
				       location_id='$locid',
				       source='$sourcenum',
				       source_type='MANIFEST'
				   where id='$rowid'");

			query("update system_log set description='Manifest Status Update: $obj->status_code' where id='$systemlogid'");

			
			
		}

		echo "$rowid <br>";
		query("insert into corrected_remarks(waybill_history_id,reference) values('$rowid','$sourcenum')");



	}
?>