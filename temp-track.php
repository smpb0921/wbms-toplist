<?php


try{
        $wyb = isset($_GET['wyb']) ? $_GET['wyb'] : "";
        
        $creds = [
			"user" => "cbl-freight1",
			"password" => "#CBL-FREIGHT.COM4Tr4ck1nGModul3!",
			"tracking_number" => $wyb
		];
        
        $ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_URL, "http://58.71.87.132:8017/src/scripts/track.php?wyb={$wyb}");
        curl_setopt($ch, CURLOPT_URL, "http://58.71.87.130:8018/wbms/webservice/public/api/waybill?{$wyb}");
        curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        Curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $creds);
        
        $result = curl_exec($ch);
        
        if($err = curl_error($ch)){
            echo $err."-----<br/>";
        }
        else{
            $result = $result!=''?json_decode($result):$result; 
            print_r($result);
        }
        
        
        curl_close($ch);


}
catch (Exception $e){
	print_r(
		json_encode(
						["Sucess"=>false, "message"=>$e->getMessage()]
					)
	);
}

?>