<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/vehicle.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['vehicleSaveEdit'])){
		if($_POST['vehicleSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
				$status = escapeString($_POST['status']);
				$source = escapeString($_POST['source']);
				$platenumber = escapeString(trim(strtoupper($_POST['platenumber'])));
				$model = escapeString(trim($_POST['model']));
				$year = escapeString(trim($_POST['year']));
				$vehicletype = escapeString(strtoupper(trim($_POST['vehicletype'])));
				$userid = USERID;
				$now = date('Y-m-d H:i:s');
				$vehicleclass = new vehicle();
				$systemlog = new system_log();

				if($source=='edit'){
					$id = escapeString($_POST['id']);
					$query = "select * from vehicle where plate_number='$platenumber' and id!='$id'";
				}
				else{
					$query = "select * from vehicle where plate_number='$platenumber'";
				}
				$rs = query($query);

				if(getNumRows($rs)==0){
			
					if($source=='add'){
						$vehicleclass->insert(array('',$vehicletype,$platenumber,$model,$year,$userid,$now,'NULL','NULL',$status));
						$id = $vehicleclass->getInsertId();
						$systemlog->logAddedInfo($vehicleclass,array($id,$vehicletype,$platenumber,$model,$year,$userid,$now,'NULL','NULL',$status),'VEHICLE','New Vehicle Added',$userid,$now);
						echo "success";
					}
					else if($source=='edit'){
							$id = escapeString($_POST['id']);
						
							$systemlog->logEditedInfo($vehicleclass,$id,array($id,$vehicletype,$platenumber,$model,$year,'NOCHANGE','NOCHANGE',$userid,$now,$status),'VEHICLE','Edited Vehicle Info',$userid,$now);/// log should be before update is made
							$vehicleclass->update($id,array($vehicletype,$platenumber,$model,$year,'NOCHANGE','NOCHANGE',$userid,$now,$status));
							echo "success";

					}
				}
				else{
					echo "codeexists";
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

	        	$rs = query("delete from vehicle where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

