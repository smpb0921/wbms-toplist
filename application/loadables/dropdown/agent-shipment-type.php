<?php 
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");

	$search         = isset($_GET['q'])               ? strip_tags(trim($_GET['q'])) : ''; 
	$search         = escapeString($search);
	$shipmentTypeId = isset($_GET['shipment_type_id']) ? escapeString(trim($_GET['shipment_type_id'])) : '';

	// Build optional shipment type filter
	$shipmentTypeCondition = '';
	if ($shipmentTypeId !== '' && strtoupper($shipmentTypeId) !== 'NULL') {
		$shipmentTypeCondition = " AND shipment_type_id = '$shipmentTypeId'";
	}

	if ($search != '') {
		$query = query("SELECT id, code, company_name
		                FROM   agent
		                WHERE  (code LIKE '%$search%' OR company_name LIKE '%$search%')
		                       $shipmentTypeCondition
		                ORDER  BY company_name ASC
		                LIMIT  40");
	} else {
		$query = query("SELECT id, code, company_name
		                FROM   agent
		                WHERE  1=1
		                       $shipmentTypeCondition
		                ORDER  BY company_name ASC
		                LIMIT  50");
	}

	$rscount = getNumRows($query);
	if ($rscount > 0) {
		$data[] = array('id' => 'NULL', 'text' => '-');
		while ($obj = fetch($query)) {
			$data[] = array('id' => $obj->id, 'text' => utfEncode($obj->company_name));
		}
	} else {
		$data[] = array('id' => '', 'text' => 'No Results Found');
	}

	echo json_encode($data);
?>