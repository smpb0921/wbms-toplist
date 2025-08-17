<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/district-city-zip.class.php");
    include("../classes/system-log.class.php");////////

	if(isset($_POST['districtcityzipSaveEdit'])){
		if($_POST['districtcityzipSaveEdit']=='j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@'){
			$source = escapeString($_POST['source']);
			$district = escapeString(trim($_POST['district']));
			$city = escapeString(trim($_POST['city']));
			$zip = escapeString(trim($_POST['zip']));
			$region = escapeString(trim($_POST['region']));
			$leadtime = isset($_POST['leadtime'])&&trim($_POST['leadtime'])!=''?escapeString(trim($_POST['leadtime'])):'NULL';
			$odaflag = escapeString(trim($_POST['odaflag']));
			$odarate = escapeString(trim($_POST['odarate']));
			$userid = USERID;
			$now = date('Y-m-d H:i:s');
			$districtcityzipclass = new district_city_zipcode();
			$systemlog = new system_log();


			
			
			
				if($source=='add'){
					$districtcityzipclass->insert(array('',$district,$city,$zip,$region,$userid,$now,'NULL','NULL',$odaflag,$odarate,$leadtime));
					$id = $districtcityzipclass->getInsertId();
					$systemlog->logAddedInfo($districtcityzipclass,array($id,$district,$city,$zip,$region,$userid,$now,'NULL','NULL',$odaflag,$odarate,$leadtime),'District/City/ZipCode','New District/City/ZipCode Added',$userid,$now);
					echo "success";
				}
				else if($source=='edit'){
						$id = escapeString($_POST['id']);
					
						$systemlog->logEditedInfo($districtcityzipclass,$id,array('',$district,$city,$zip,$region,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate,$leadtime),'District/City/ZipCode','Edited District/City/ZipCode Info',$userid,$now);/// log should be before update is made
						$districtcityzipclass->update($id,array($district,$city,$zip,$region,'NOCHANGE','NOCHANGE',$userid,$now,$odaflag,$odarate,$leadtime));
						echo "success";

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

	        	$rs = query("delete from district_city_zipcode where id='$id'");
	        	if(!$rs){
	        		echo "ID: $id -".mysql_error()."\n";
	        	}

	        }

	        echo "success";
       	}
    }


  



?>

