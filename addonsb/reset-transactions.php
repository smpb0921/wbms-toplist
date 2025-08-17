<?php
include_once('conn.php');
$sql = "show tables;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$conn->query("set foreign_key_checks=0;");  
    while($row = $result->fetch_array()) { 
		$conn->query("ALTER TABLE `$row[0]` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
		if($row[0]=="company_information"){
			 continue;
		}
		if($row[0]=="item"){
			 continue;
		}
		if($row[0]=="location"){
			 continue;
		}
		if($row[0]=="item_plu"){
			 continue;
		}
		if($row[0]=="zone"){
			 continue;
		}
		if($row[0]=="client"){
			 continue;
		}
		if($row[0]=="client_address"){
			 continue;
		}  
		if($row[0]=="warehouse"){
			 continue;
		}
		if($row[0]=="unit_of_measure"){
			 continue;
		}
		if($row[0]=="category"){
			 continue;
		}
		if($row[0]=="item_tax_type"){
			 continue;
		}
		if($row[0]=="item_type"){
			 continue;
		}
		if($row[0]=="printer"){
			 continue;
		}
		if($row[0]=="supplier"){
			 continue;
		}
		if($row[0]=="transaction_type"){
			 $conn->query('set sql_safe_updates=0;');
			 $conn->query('update transaction_type set next_number_series=1;');
			 $conn->query('set sql_safe_updates=1;');
			 continue;
		}
		if($row[0]=="user"){
			 continue;
		}
		if($row[0]=="reason"){
			 continue;
		} 
		if($row[0]=="reason_type"){
			 continue;
		}
		if($row[0]=="terms"){
			 continue;
		}
		if($row[0]=="user_group"){
			 continue;
		}
		if($row[0]=="user_rights"){
			 continue;
		}  
		$conn->query("alter table `$row[0]` change column `id` `id` int(11) not null auto_increment;");
		$conn->query("truncate $row[0];");
		echo "Truncated $row[0]<br/>";
    }
		try{
		  $conn->query("set foreign_key_checks=1;");
		}
		catch (Exception $ex){
			echo $ex->getMessage();
		}
		finally{
			$conn->close(); 
		}
	
	echo "<br><br>====Script Success=====<br><br>";
} else {
    echo "Error";
}


