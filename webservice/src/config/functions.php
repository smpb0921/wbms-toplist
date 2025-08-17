<?php
	function escapeString($str){
		$str = utf8_decode(trim($str));
		$str = mysql_real_escape_string($str);
		$str = stripcslashes($str);
		$str = str_replace("'", "\'", $str);
		
		return $str;
	}

	function dateFormat($date,$format){
        if($date=="0000-00-00 00:00:00"||$date==''||$date=='0000-00-00'){
            $date ='';
        }
        else{
           $date = date($format, strtotime($date));
        }
        return $date;
    }


    function insertApiLog($db,$userid){

    	

    	$ip = getClientIpAddr();
    	if($userid>0){
    		$sql = "insert into webapi_log(api_user_id,ip_address) values(:userid,:ip)";

    		
		    $stmt = $db->prepare($sql);
		    $stmt->bindParam(':userid', $userid);
		    $stmt->bindParam(':ip', $ip);
		    $stmt->execute();
    	}
    	else{
    		$sql = "insert into webapi_log(ip_address) values(:ip)";

    		
		    $stmt = $db->prepare($sql);
		    $stmt->bindParam(':ip', $ip);
		    $stmt->execute();
    	}
    }

    function getClientIpAddr() {
	    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	    foreach ($ip_keys as $key) {
	        if (array_key_exists($key, $_SERVER) === true) {
	            foreach (explode(',', $_SERVER[$key]) as $ip) {
	                // trim for safety measures
	                $ip = trim($ip);
	                // attempt to validate IP
	                if (validate_ip($ip)) {
	                    return $ip;
	                }
	            }
	        }
	    }
	    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
	}

	function validate_ip($ip)
	{
	    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
	        return false;
	    }
	    return true;
	}




?>