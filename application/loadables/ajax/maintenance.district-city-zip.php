<?php
	include('../../../config/connection.php');
	include('../../../config/functions.php');




	$page = 1;	// The current page
	$sortname = '';	// Sort column
	$sortorder = '';	// Sort order
	$qtype = '';	// Search column
	$query = '';	// Search string

	// Get posted data
	if (isset($_POST['page'])) {
		$page = mysql_real_escape_string($_POST['page']);
	}
	if (isset($_POST['sortname'])) {
		$sortname = mysql_real_escape_string($_POST['sortname']);
	}
	if (isset($_POST['sortorder'])) {
		$sortorder = mysql_real_escape_string($_POST['sortorder']);
	}
	if (isset($_POST['qtype'])) {
		$qtype = mysql_real_escape_string($_POST['qtype']);
	}
	if (isset($_POST['query'])) {
		$query = mysql_real_escape_string($_POST['query']);
	}
	if (isset($_POST['rp'])) {
		$rp = mysql_real_escape_string($_POST['rp']);
	}


	$customqry =   "select district_city_zipcode.id,
						   district_city_zipcode.district_barangay, 
						   district_city_zipcode.city,
						   district_city_zipcode.zip_code,
						   district_city_zipcode.origin_destination_port_id,
						   district_city_zipcode.oda_flag,
						   district_city_zipcode.lead_time,
						   case 
						   	when oda_flag=1 then 'YES'
						   	when oda_flag=0 then 'NO'
						   	else 'UNDEFINED'
						   end as odaflag,
						   district_city_zipcode.oda_rate,
						   origin_destination_port.description as region_province,
						   district_city_zipcode.created_date,
						   concat(cuser.first_name,' ',cuser.last_name) as created_by,
						   district_city_zipcode.updated_date,
						   concat(uuser.first_name,' ',uuser.last_name) as updated_by
					from district_city_zipcode
					left join origin_destination_port on origin_destination_port.id=district_city_zipcode.origin_destination_port_id
					left join user as cuser on cuser.id=district_city_zipcode.created_by
					left join user as uuser on uuser.id=district_city_zipcode.updated_by";

	// Setup sort and search SQL using posted data
	$sortSql = "order by $sortname $sortorder";
	$searchSql = ($qtype != '' && $query != '') ? "where $qtype like '%$query%'" : '';


	// Get total count of records
	$sql = "select * from ( $customqry ) as tbl $searchSql";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$total = mysql_num_rows($result);

	// Setup paging SQL
	$pageStart = ($page-1)*$rp;
	$limitSql = "limit $pageStart, $rp";

	// Return JSON data
	$data = array();
	$data['page'] = $page;
	$data['total'] = $total;
	$data['rows'] = array();
	$sql = "select *
			from (
			        $customqry
				  ) as tbl
			$searchSql
			$sortSql
			$limitSql";

		

			
	$results = mysql_query($sql);
	$line = 1;
	while ($obj = mysql_fetch_object($results)) {
		$id = $obj->id;
		$district = utfEncode($obj->district_barangay);
		$city = utfEncode($obj->city);
		$zip = utfEncode($obj->zip_code);
		$regionid = $obj->origin_destination_port_id;
		$region = utfEncode($obj->region_province);
		$odaflag = utfEncode($obj->oda_flag);
		$odaflagdesc = utfEncode($obj->odaflag);
		$odarate = utfEncode($obj->oda_rate);
		$createdby = utfEncode($obj->created_by);
		$createddate = $obj->created_date;
		$updatedby = utfEncode($obj->updated_by);
		$updateddate = $obj->updated_date;
		$leadtime = $obj->lead_time;
		$id = $obj->id;
		$editbtn = (userAccess(USERID,'.editdistrictcityzipbtn')==false)?"<img src='../resources/flexigrid/images/edit.png' rowid='$id' district='$district' city='$city' zip='$zip' regionid='$regionid' region='$region' odaflag='$odaflag' leadtime='$leadtime' odarate='$odarate' title='Edit' class='editdistrictcityzipbtn pointer' data-toggle='modal' href='#editdistrictcityzipmodal' height='20px'>":'';

		$data['rows'][] = array(
									'id' => $id,
									'cell' => array(
													 $editbtn,
													 $id, 
													 $district,
													 $city,
													 $zip,
													 $region,
													 $leadtime,
													 $odaflagdesc,
													 $odarate,
													
													 $createdby,
													 $createddate,
													 $updatedby,
													 $updateddate

													),
									'rowAttr'=>array(
													   'rowid'=>$id
													)
								);
		$line++;
	}
	echo json_encode($data);
?>