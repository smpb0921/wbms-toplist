<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/system-log.class.php");////////

    if(isset($_FILES['file'])){
        $now = date("Y-m-d H:i:s");
        $userid = USERID;
        $bookingNumber = isset($_POST['bookingNumber'])?trim($_POST['bookingNumber']):'';
        $file = $_FILES['file'];
		$length = count($file['name']);
			
        $rs = query("select * from txn_booking where booking_number='$bookingNumber'");
        if(getNumRows($rs)==1){

            
            
                $x = 1;
			    $flag = true;

                $tmp = $file['tmp_name'];
                $originalfilename = $file['name'];
                $filename = $file['name'];

                $fn = substr($filename, 0, strpos($filename, '.'));
				$ftype = substr($filename, strpos($filename, '.'));
                
                if(file_exists('../application/attachments/'.$filename)==1){
                    while($flag){
                        if(file_exists('../application/attachments/'.$fn."(".$x.")".$ftype)==1){
                            $x++;
                        }
                        else{
                            $x=1;
                            $flag = false;
                            $filename = $fn."(".$x.")".$ftype;
                        }
                    }
                }
              
                move_uploaded_file($_FILES['file']['tmp_name'],'../application/attachments/'.$filename);
                query("
                        insert into txn_booking_attachments(
                                                             booking_number,
                                                             file_name,
                                                             created_date,
                                                             created_by
                                                            )
                                                    values (
                                                            '$bookingNumber',
                                                            '$filename',
                                                            '$now',
                                                            '$userid'
                                                            )
                    
                ");

            


            $response = array(
                                "status"=>"ok",
                                "path"=>'../application/attachments/'.$filename
                            );
        }
        else{
            $response = array(
                                "status"=>"error",
                                "message"=>"invalidbooking"
                            );
        }
        print_r(json_encode($response));
    }

?>