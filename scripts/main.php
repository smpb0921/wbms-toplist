<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['checkDashboardAccess'])){
		if($_POST['checkDashboardAccess']=='sd$oihBO$h#OiB@s09#j!@IO#09aujj$Oi03n'){
			$userid = USERID;

			
			$rs = query("select * from user_rights where user_id='$userid' and menu_id='#dash'");
			if(getNumRows($rs)==0){
				echo 'true';
			}
			
		}
	}



	if(isset($_POST['fieldeditprocessing'])){
		if($_POST['fieldeditprocessing']=='sdfkjh$FI#@#NDOPs09#j!@IO#09aujj$Oi03n'){
				$code = isset($_POST['code'])?escapeString($_POST['code']):'';
				$table = isset($_POST['table'])?escapeString($_POST['table']):'';
				$column = isset($_POST['column'])?escapeString($_POST['column']):'';
				$txncolumn = isset($_POST['txncolumn'])?escapeString($_POST['txncolumn']):'';
				$txnnumber = isset($_POST['txnnumber'])?escapeString($_POST['txnnumber']):'';
				$type = isset($_POST['type'])?escapeString($_POST['type']):'';
				$id = isset($_POST['id'])?escapeString($_POST['id']):'';

				$oldvalue = '';

				$fieldeditwbcbmaccess = hasAccess(USERID,'#'.$id);
				if($fieldeditwbcbmaccess==1||USERID==1){
					
					$checktable = query("select * 
				                     from information_schema.TABLES
								     WHERE (TABLE_SCHEMA = '$dbname') AND 
								           (TABLE_NAME = '$table')");

					if(getNumRows($checktable)>0){
						$checktargetcolumn = query("select * 
												    from information_schema.COLUMNS 
												    where TABLE_SCHEMA = '$dbname' and 
												          TABLE_NAME = '$table' and
														  COLUMN_NAME = '$column'");
						if(getNumRows($checktargetcolumn)>0){

								$checktxncolumn = query("select * 
													    from information_schema.COLUMNS 
													    where TABLE_SCHEMA = '$dbname' and 
													          TABLE_NAME = '$table' and
															  COLUMN_NAME = '$txncolumn'");
								if(getNumRows($checktxncolumn)>0){

									$checktxnnumber = query("select * from $table where $txncolumn='$txnnumber'");
									if(getNumRows($checktxnnumber)>0){

										while($obj=fetch($checktxnnumber)){
											$oldvalue = $obj->$column;
										}


										$dataarray = array(
									   				       "response"=>"success",
									   				       "oldvalue"=>$oldvalue
									                 );

									}
									else{
										$dataarray = array(
									   				       "response"=>"invalidtxnnumber"
									                 );
									}

								}		
								else{

									$dataarray = array(
									   				   "response"=>"txncolumnnotexist"
									           );
								}

						}		
						else{
							$dataarray = array(
									   				"response"=>"columnnotexist"
									           );
						}

					}
					else{
						$dataarray = array(
									   		"response"=>"tablenotexist"
									   );
					}
				}
				else{
					$dataarray = array(
									   "response"=>"nopermission"
									   );
				}

				print_r(json_encode($dataarray));

				



		}
	}

	if(isset($_POST['saveFieldEditValue'])){
		if($_POST['saveFieldEditValue']=='sdfkjh$FI#@#NDOPs09#j!@IO#09aujj$Oi03n'){
				$code = isset($_POST['code'])?escapeString($_POST['code']):'';
				$table = isset($_POST['table'])?escapeString($_POST['table']):'';
				$column = isset($_POST['column'])?escapeString($_POST['column']):'';
				$txncolumn = isset($_POST['txncolumn'])?escapeString($_POST['txncolumn']):'';
				$txnnumber = isset($_POST['txnnumber'])?escapeString($_POST['txnnumber']):'';
				$type = isset($_POST['type'])?escapeString($_POST['type']):'';
				$id = isset($_POST['id'])?escapeString($_POST['id']):'';

				$newvalue = isset($_POST['newvalue'])?escapeString($_POST['newvalue']):'';
				$oldvalue = '';

				$fieldeditwbcbmaccess = hasAccess(USERID,'#'.$id);
				if($fieldeditwbcbmaccess==1){

					if(trim($newvalue)!=''){
					
						$checktable = query("select * 
					                     from information_schema.TABLES
									     WHERE (TABLE_SCHEMA = '$dbname') AND 
									           (TABLE_NAME = '$table')");

						if(getNumRows($checktable)>0){
							$checktargetcolumn = query("select * 
													    from information_schema.COLUMNS 
													    where TABLE_SCHEMA = '$dbname' and 
													          TABLE_NAME = '$table' and
															  COLUMN_NAME = '$column'");
							if(getNumRows($checktargetcolumn)>0){

									$checktxncolumn = query("select * 
														    from information_schema.COLUMNS 
														    where TABLE_SCHEMA = '$dbname' and 
														          TABLE_NAME = '$table' and
																  COLUMN_NAME = '$txncolumn'");
									if(getNumRows($checktxncolumn)>0){

										$checktxnnumber = query("select * from $table where $txncolumn='$txnnumber'");
										if(getNumRows($checktxnnumber)>0){

											while($obj=fetch($checktxnnumber)){
												$oldvalue = $obj->$column;
											}

											$checkdatatype = false;
											if(strtoupper($type)=='NUMERIC'){
												$newvalue = str_replace(',', '', $newvalue);
												if(is_numeric($newvalue)){
													$newvalue = round($newvalue,5);
													$checkdatatype = true;
												}
											}

											if($checkdatatype==true){

												$userid = USERID;
												$now = date("Y-m-d H:i:s");

												$rs = query("update $table set $column='$newvalue' where $txncolumn='$txnnumber'");
												if($rs){
													$systemlog = new system_log();		
													$systemlog->logInfo('FIELD EDIT','Edited - '.$code,"Transaction Number: ".$txnnumber." | Old Value: $oldvalue | New Value: $newvalue",$userid,$now);

													$dataarray = array(
										   				       		"response"=>"success"
										                         );
												}
												else{
													$dataarray = array(
										   				       		"response"=>"unsuccessful"
										                 );
												}
												

											}
											else{
												$dataarray = array(
										   				       		"response"=>"notnumeric"
										                 );
											}

											

										}
										else{
											$dataarray = array(
										   				       "response"=>"invalidtxnnumber"
										                 );
										}

									}		
									else{

										$dataarray = array(
										   				   "response"=>"txncolumnnotexist"
										           );
									}

							}		
							else{
								$dataarray = array(
										   				"response"=>"columnnotexist"
										           );
							}

						}
						else{
							$dataarray = array(
										   		"response"=>"tablenotexist"
										   );
						}
					}
					else{
						$dataarray = array(
									   "response"=>"newvaluenotprovided"
									   );
					}
				}
				else{
					$dataarray = array(
									   "response"=>"nopermission"
									   );
				}

				print_r(json_encode($dataarray));

				



		}
	}
?>