<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/origin-destination-port.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['OriginDestinationPortSaveEdit'])){
		if($_POST['OriginDestinationPortSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$code = escapeString(strtoupper(trim($_POST['code'])));
			$desc = escapeString($_POST['desc']);
			$country = escapeString($_POST['country']);
			$zone = escapeString($_POST['zone']);
			$leadtime = trim($_POST['leadtime'])>0?escapeString($_POST['leadtime']):'NULL';
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$portclass = new origin_destination_port();
			$systemlog = new system_log();

			if($source=='edit'){
				$id = escapeString($_POST['id']);
				$query = "select * from origin_destination_port where code='$code' and id!='$id'";
			}
			else{
				$query = "select * from origin_destination_port where code='$code'";
			}
			$rs = query($query);

			if(getNumRows($rs)==0){
			
				if($source=='add'){
					$portclass->insert(array('',$code,$desc,$userid,$now,'NULL','NULL',$country,$zone,$leadtime));
					$id = $portclass->getInsertId();
					$systemlog->logAddedInfo($portclass,array($id,$code,$desc,$userid,$now,'NULL','NULL',$country,$zone,$leadtime),'ORIGIN/DESTINATION PORT','New Origin/Destination Port Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
						$systemlog->logEditedInfo($portclass,$id,array('',$code,$desc,'NOCHANGE','NOCHANGE',$userid,$now,$country,$zone,$leadtime),'ORIGIN/DESTINATION PORT','Edited Origin/Destination Port Info',$userid,$now);/// log should be before update is made
						$portclass->update($id,array($code,$desc,'NOCHANGE','NOCHANGE',$userid,$now,$country,$zone,$leadtime));
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
	        
	        	/*$flag = checkifallowdelete($dbname,'origin_destination_port','id',$id);

	            if($flag==0){*/

		        	$rs = query("delete from origin_destination_port where id='$id'");
	            	
		        	 
		        /*}
		        else{
		        	echo "unabletodelete";
		        }*/

	        }
	        echo "success";
	       
       	}
    }



?>

