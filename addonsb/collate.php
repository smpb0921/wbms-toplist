<?php
include_once('conn.php');
$sql = "show tables;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$conn->query("set foreign_key_checks=0;");  
		while($row = $result->fetch_array()) { 
		$conn->query("ALTER TABLE `$row[0]` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
		$d = $conn->query("show table status from wbms_cbl where name= '$row[0]'");
		echo  "Collated $row[0] into ".$d->fetch_array()[14]."<br>";
    }
    $s = date('m d, Y H:i:s ');
	echo "<br><br>==== Script Success Ended at:  $s =====<br><br>";
} else {
    echo "Error";
}