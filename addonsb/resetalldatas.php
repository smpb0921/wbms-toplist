<?php 
$srv = $_SERVER['HTTP_HOST'];
if(!($srv=="localhost:8017" || $srv=="127.0.0.1:8017")){
   header("Location: $srv/cbl-wbms");
   exit;
}
include_once('conn.php');
$sql = "show tables;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$conn->query("set foreign_key_checks=0, sql_safe_updates=0;"); 
    while($row = $result->fetch_array()) {
        if($row[0]=="company_information"){
			 continue;
		} 
		if($row[0]=="transaction_type"){ 
			 $conn->query('update transaction_type set next_number_series=1;'); 
			 continue;
		}
		if($row[0]=="user") {
			 $conn->query("delete from user where id>1");
			 continue;
		} 
        if($row[0]=="company_information"){
			 continue;
		} 
        if($row[0]=="status"){
			 continue;
		} 
        if($row[0]=="movement_type"){
			 continue;
		} 
        if($row[0]=="movement_type_source"){
			 continue;
		} 
        if($row[0]=="no_update_status"){
			 continue;
		} 
        if($row[0]=="mod_sms_maintenance_trans"){
			 continue;
		} 
        if($row[0]=="mod_sms_und_reasons"){
			 continue;
		} 
        if($row[0]=="destination_route"){
			 continue;
		} 
        if($row[0]=="freight_computation"){
			 continue;
		} 
        if($row[0]=="origin_destination_port"){
			 continue;
		} 
        if($row[0]=="district_city_zipcode"){
			 continue;
		}  
		$conn->query("alter table `$row[0]` change column `id` `id` int(11) not null auto_increment;");
		$conn->query("truncate $row[0];");
		echo "Truncated $row[0]<br/>";
    }
	$conn->query("set foreign_key_checks=1, sql_safe_updates=1;");
	echo "script success";
} else {
    echo "0 results";
}
$conn->close();
?>