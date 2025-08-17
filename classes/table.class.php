<?php
require('database.class.php');
abstract class table extends database{

	public function insert($val){ //e.g $val = array('',$name,$user,$password) -> first parameter should be empty
		$columns = array();
		$cols = get_class_vars(get_class($this));
		foreach ($cols as $key => $value) {
			array_push($columns, $key);
		}
		$columnnames=implode(',', $columns);
		$values = array();
		foreach ($val as $k) {
			if(is_string($k)&&$k!='NULL'){
				$k = "'".mysql_real_escape_string($k)."'";
			}
			array_push($values, $k);
		}
		$data = implode(',', $values);
		$query = "insert into ".get_class($this)."(".$columnnames.") values(".$data.")";
		//echo utf8_encode($query)."<br>";
		$this->doQuery($query);

		
	}

	public function insertMultiple($multidarraydata){
		$columns = array();
		$cols = get_class_vars(get_class($this));
		foreach ($cols as $key => $value) {
			array_push($columns, $key);
		}
		$columnnames=implode(',', $columns);

		$values = array();
		for($i = 0; $i<count($multidarraydata); $i++){
			$temp = "";
			for($n = 0; $n<count($multidarraydata[$i]); $n++){
				
				if(is_string($multidarraydata[$i][$n])&&$multidarraydata[$i][$n]=='NULL'){
					$temp = $temp.", ".$multidarraydata[$i][$n];
				}
				else{
					$temp = $temp.", '".$multidarraydata[$i][$n]."'";
				}
			}
			$temp = trim($temp,",");
			$temp = "(".$temp.")";
			array_push($values, $temp);
		}
		$data = implode(',', $values);

		$query = "insert into ".get_class($this)."(".$columnnames.") values".$data;
		$this->doQuery($query);

	}



	public function delete($id){
		$vars = get_class_vars(get_class($this));
		foreach($vars as $key=>$val){
			$query = "delete from ".get_class($this)." where ".$key."='".$id."'";
			break;
		}
		$this->doQuery($query);
	}

	public function deleteAll(){
		$query = "delete from ".get_class($this);
		$this->doQuery($query);
	}

	public function deleteMultiple($ids){
		$vars = get_class_vars(get_class($this));
		foreach($vars as $key=>$val){
			$query = "delete from ".get_class($this)." where ".$key." in ".$ids;
			break;
		}
		$this->doQuery($query);

	}

	public function deleteWhere($condition){
		$query = "delete from ".get_class($this)." ".$condition;
		$this->doQuery($query);
	}



	public function select(){
		$query = "select * from ".get_class($this);
		$this->doQuery($query);

	}

	public function update($id,$values){//$values = array();
		$vars = get_class_vars(get_class($this));
		$setvalues = array();
		$i = 0;
		$flag = '';
		foreach($vars as $key=>$val){
			if($flag==''){
				$flag = $key;
			}
			else{
				if(strtoupper($values[$i])=='NULL'){
					array_push($setvalues, $key."=".$values[$i]);
				}
				else if(strtoupper($values[$i])=='NOCHANGE'){
				}
				else if(is_integer($values[$i])){
					array_push($setvalues, $key."=".$values[$i]);
				}
				else{
					array_push($setvalues, $key."='".$values[$i]."'");
				}
				$i++;
			}
		}
		$setvalues = implode(', ', $setvalues);
		$query = "update ".get_class($this)." set ".$setvalues." where ".$flag."='".$id."'";
		//echo $query;


		$this->doQuery($query);
	}

	public function getColumns(){
		$columns = get_class_vars(get_class($this));
		return $columns;
	}

	public function updateColumn($column,$value,$condition){
		$query = "update ".get_class($this)." set ".$column."='".$value."'";
		if(trim($condition," ")!=''||strtoupper($condition)!='NULL'){
			$query = "update ".get_class($this)." set ".$column."='".$value."' where ".$condition;
		}
		$this->doQuery($query);

	}


}



?>