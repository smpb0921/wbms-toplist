<?php
	include("../config/connection.php");
    include("../config/checkurlaccess.php");
    include("../config/checklogin.php");
    include("../config/functions.php");
    include("../classes/stock-adjustment-header.class.php");
    include("../classes/stock-adjustment-details.class.php");
    include("../classes/system-log.class.php");////////
    include("../classes/stock-card.class.php");

    if(isset($_FILES['file'])){
				$response = 'ok';
				$file = $_FILES['file'];
				$length = count($file['name']);
				echo $length;

				$adjnumber = escapeString($_POST['adjnumber']);

				$x = 1;
				$flag = true;

				for($i=0;$i<$length;$i++){

					$tmp = $file['tmp_name'][$i];
					$originalfilename = $file['name'][$i];
					$filename = $file['name'][$i];
					$filedesc = escapeString($_POST['filedescription'][$i]);

					//check if filename exist
					$fn = substr($filename, 0, strpos($filename, '.'));
					$ftype = substr($filename, strpos($filename, '.'));
					if(file_exists('../attachments/'.$filename)==1){
						while($flag==true){
							if(file_exists('../attachments/'.$fn.'('.$x.')'.$ftype)==1){
								$x++;
							}
							else{
								$flag = false;
								$filename = $fn.'('.$x.')'.$ftype;
								$x=1;
							}
						}
					}
					//

					move_uploaded_file($_FILES['file']['tmp_name'][$i],'../attachments/'.$filename);
					$rs = query("insert into stock_adjustment_attachments(
																	  adjustment_number,
																	  system_file_name,
																	  file_description,
																	  file_name
																	)
															values(
																	'$adjnumber',
																	'$filename',
																	'$filedesc',
																	'$originalfilename'
																   )");
					if(!$rs){
						echo mysql_error();
					}
				
				}
				

		
	}

  
?>