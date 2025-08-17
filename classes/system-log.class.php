<?php
//require_once('../config/db-function.php');
require_once('table.class.php');
class system_log extends table{
	var $id; //primary key should always be the first variable in every entity class
	var $module;
	var $description;
	var $query;
	var $user_source;
	var $date_source;

	public function logAddedInfo($someclass,$dataarray,$module,$description,$userid,$now){
		$info = array();
		$cols = $someclass->getColumns();
		$x = 0;
		foreach ($cols as $key => $value) {
			//if($x!=0){
				array_push($info, $key.'='.$dataarray[$x]);
			//}
			$x++;
		}
		$info = implode(' | ', $info);

		$this->insert(array('',$module,$description,$info,$userid,$now));
	}

	public function logEditedInfo($someclass,$id,$dataarray,$module,$description,$userid,$now){
		$olddata = array();
		$tablecolumns = get_class_vars(get_class($someclass));
		$pk = array_keys($tablecolumns)[0];
		$rs = query("select * from ".get_class($someclass)." where ".$pk."='".$id."'");
		$data = fetchArray($rs);
		foreach($data as $key => $value) {
			array_push($olddata, $key.'='.$value);
		}
		$olddata = implode(' | ', $olddata);

		$newdata = array();
		$cols = $someclass->getColumns();
		$x = 0;
		foreach ($cols as $key => $value) {
			if($x!=0){
				array_push($newdata, $key.'='.$dataarray[$x]);
			}
			$x++;
		}
		$newdata = implode(' | ', $newdata);

		$this->insert(array('',$module,$description,'OLD VALUES: '.$olddata.' ==> NEW VALUES: '.$newdata,$userid,$now));
	}

	public function logInfo($module,$description,$data,$userid,$now){
		$this->insert(array('',$module,$description,$data,$userid,$now));
	}	
}

?>