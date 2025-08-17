<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/unit-of-measure.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['unitofmeasureSaveEdit'])){
		if($_POST['unitofmeasureSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$unitofmeasureclass = new unit_of_measure();
			$systemlog = new system_log();


			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from unit_of_measure where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from unit_of_measure where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$unitofmeasureclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL'));
					$id = $unitofmeasureclass->getInsertId();
					$systemlog->logAddedInfo($unitofmeasureclass,array($id,$code,$desc,$userid,$now,'NULL','NULL'),'Vehicle Type','New Vehicle Type Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($unitofmeasureclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now),'Vehicle Type','Edited Vehicle Type Info',$userid,$now);/// log should be before update is made
						$unitofmeasureclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now));
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

	        	$rs = query("delete from unit_of_measure where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }



?>

