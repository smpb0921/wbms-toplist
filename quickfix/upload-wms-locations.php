<?php
	ini_set('max_execution_time', 30000);
	include("../config/connection.php");
	include("../config/functions.php");


	$tmpfile = "excel/imds-location.csv";
	$handle = fopen($tmpfile, 'r');
	$numberofrows = count(file($tmpfile));
	$strvalues =array();
	$rowdata = fgetcsv($handle);
	$now = date('Y-m-d H:i:s');

	for($i=1;$i<$numberofrows;$i++){


		$rowdata = fgetcsv($handle);
		$code = trim($rowdata[0])==''?'NULL':trim(strtoupper($rowdata[0]));
		$desc = trim($rowdata[1])==''?'NULL':trim(strtoupper($rowdata[1]));
		$whseid = 24;


		if($code!='NULL'&&$desc!='NULL'){

			$checkloc = query("select * from location where loc_code='$code'");

			if(mysql_num_rows($checkloc)>0){
				echo "Already exists: $code <br>";
			}
			else{
				query("insert into location(
												loc_code,
												loc_description,
												loc_pallet_slot,
												loc_pallet_cs,
												loc_capacity,
												warehouse_id,
												for_all_items,
												weight_capacity,
												cbm_capacity,
												zone_id,
												it_location_flag
											)
										values(
												  '$code',
												  '$desc',
												  '1',
												  '1000',
												  '1000000000',
												  '$whseid',
												  '1',
												  '1000000000',
												  2,
												  '23',
												  0
										       )
					  ");
			}

		}
		else{
			echo "No code/description: Code = $code; Description = $desc <br>";
		}

	}






?>