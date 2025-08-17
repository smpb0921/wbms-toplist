<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/movement-type.class.php");
    include("../classes/movement-type-source.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['MovementTypeSaveEdit'])){
		if($_POST['MovementTypeSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$sourcemovement = escapeString($_POST['sourcemovement']);
			$sourcemovement = explode(',', $sourcemovement);
			$mtscount = count($sourcemovement);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$mtclass = new movement_type();
			$mtsclass = new movement_type_source();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from movement_type where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from movement_type where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$mtclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL'));
					$id = $mtclass->getInsertId();
					$systemlog->logAddedInfo($mtclass,array($id,$code,$desc,$userid,$now,'NULL','NULL'),'MOVEMENT TYPE','New Movement Type Added',$userid,$now);

					$mtsclass->deleteWhere("where movement_type_id=".$id);
					$sourcemvtarray = array();
					for($i=0;$i<$mtscount;$i++){
						$temp = array();
						array_push($temp,
									$id,
									$sourcemovement[$i]
								   );
						array_push($sourcemvtarray, $temp);

					}
					if($mtscount>0){
						$mtsclass->insertMultiple($sourcemvtarray);
					}


					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($mtclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now),'MOVEMENT TYPE','Edited Movement Type Info',$userid,$now);/// log should be before update is made
						$mtclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now));


						$mtsclass->deleteWhere("where movement_type_id=".$id);
						$sourcemvtarray = array();
						for($i=0;$i<$mtscount;$i++){
							$temp = array();
							array_push($temp,
										$id,
										$sourcemovement[$i]
									   );
							array_push($sourcemvtarray, $temp);

						}
						if($mtscount>0){
							$mtsclass->insertMultiple($sourcemvtarray);
						}
						echo "success";

				}
			}
			else{
				echo "codeexists";
			}
		}
			
	}


	if(isset($_POST['MovementTypeGetInfo'])){
		if($_POST['MovementTypeGetInfo']=='kjoI$H2oiaah3h0$09jDppo92po@k@'){
			$id = escapeString($_POST['id']);

			$rs = query("select * 
				 	     from (	
				 	     		select movement_type.id,
									   movement_type.code, 
									   movement_type.description,
									   movement_type.created_date,
									   concat(cuser.first_name,' ',cuser.last_name) as created_by,
									   movement_type.updated_date,
									   concat(uuser.first_name,' ',uuser.last_name) as updated_by,
									   group_concat(source_movement) as source_movement
								from movement_type
								left join movement_type_source on movement_type_source.movement_type_id=movement_type.id
								left join user as cuser on cuser.id=movement_type.created_by
								left join user as uuser on uuser.id=movement_type.updated_by
								group by movement_type.id
				 	     	  ) as tbl
					where tbl.id='$id'");

			if(getNumRows($rs)==1){
				while($obj=fetch($rs)){
					$dataarray = array(
										   "id"=>$obj->id,
										   "code"=>utfEncode($obj->code),
										   "description"=>utfEncode($obj->description),
										   "sourcemovement"=>utfEncode($obj->source_movement),
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

	        	$rs = query("delete from movement_type where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

