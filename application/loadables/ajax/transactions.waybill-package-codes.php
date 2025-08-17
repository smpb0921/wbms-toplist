<?php
	include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");


	if (!empty($_POST) ) {


		$waybillnumber = isset($_GET['waybill'])?mysql_real_escape_string($_GET['waybill']):'';

		$qry = "select * 
		        from 
					(	
							select txn_waybill_package_code.id,       
							       txn_waybill_package_code.code,
							       txn_waybill_package_code.created_date,
							       txn_waybill_package_code.created_by,
							       concat(first_name,' ',last_name) as createdby
							from txn_waybill_package_code
							left join user on user.id=txn_waybill_package_code.created_by
							where txn_waybill_package_code.waybill_number='$waybillnumber'
					) as tbl";

			
		function strUtfEncode($str){ 
			$str = utf8_encode($str);
			$str = str_replace("'", '&#39', $str);
			return $str;
		}

		


		
		
		function getData2($sql){
	    	global $connection ;//we use connection already opened
	        $query = mysql_query($sql) OR DIE ("Can't get Data from DB , check your SQL Query <br>".$sql );
	        $data = array();

	        $hidebtn = (userAccess(USERID,'.editclientbtn')==false)?'':'hidden';

			while($obj=mysql_fetch_object($query)){
				$id = $obj->id;
				$code = $obj->code;
				$createddate = dateFormat($obj->created_date,'m/d/Y h:i:s A');
				$createdby = utfEncode($obj->createdby);

				$arraydata =  array(
									"DT_RowClass"=>"wbpackagecoderow",
									'checkbox'=>"<input type='checkbox' class='wbpackagecodecheckbox' rowid='$id'>",
									'code'=>$code,
									'created_date'=>$createddate,
									'createdby'=>$createdby
								);
				array_push($data, $arraydata);
				

				
				
	        }

	        return $data;
	    	
	    }





	    /* Useful $_POST Variables coming from the plugin */
	    $draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	    $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	    $orderType = $_POST['order'][0]['dir']; // ASC or DESC
	    $start  = $_POST["start"];//Paging first record indicator.
	    $length = $_POST['length'];//Number of records that the table can display in the current draw
	    /* END of POST variables */

	    $columnSearch = array('item_code','item_name','long_description','supplier_name');


	    $recordsTotal = mysql_num_rows(mysql_query($qry));

	    /* SEARCH CASE : Filtered data */
	    if(!empty($_POST['search']['value'])){

	        /* WHERE Clause for searching */
	        for($i=0 ; $i<count($_POST['columns']);$i++){
	            $column = $_POST['columns'][$i]['data'];//we get the name of each column using its index from POST request
	            if($column=='code'){
	            	$where[]="$column like '%".mysql_real_escape_string(utf8_decode($_POST['search']['value']))."%'";
	            }

	        }


	        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
	        /* End WHERE */

	    		
	        $sql = sprintf("%s %s", $qry , $where);//Search query without limit clause (No pagination)

	        $recordsFiltered = mysql_num_rows(mysql_query($sql));//Count of search result

	        /* SQL Query for search with limit and orderBy clauses*/
	        $sql = sprintf("%s %s ORDER BY %s %s limit %d , %d ", $qry , $where ,$orderBy, $orderType ,$start,$length  );
	        $data = getData2($sql);
	    }
	    /* END SEARCH */
	    else {
	        $sql = sprintf("%s ORDER BY %s %s limit %d , %d ", $qry ,$orderBy,$orderType ,$start , $length);
	        $data = getData2($sql);

	        $recordsFiltered = $recordsTotal;
	    }

	    /* Response to client before JSON encoding */
	    $response = array(
	        "draw" => intval($draw),
	        "recordsTotal" => $recordsTotal,
	        "recordsFiltered" => $recordsFiltered,
	        "data" => $data
	    );

	    echo json_encode($response);

	} else {
	    echo "NO POST Query from DataTable";
	}


?>