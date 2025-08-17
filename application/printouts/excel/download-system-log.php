<?php
    include("../../../config/connection.php");
    include("../../../config/checkurlaccess.php");
    include("../../../config/checklogin.php");
    include("../../../config/functions.php");


	$rs = query("select user_source,
						system_log.id as logid,
						system_log.description,
						query,
						user_source,
						date_source,
						first_name,
						middle_name,
						last_name
				from system_log, user 
				where user.id=user_source
				order by date_source desc"
		);
	
	$fillcolor = getInfo('company_information','primary_color',"where id=1");
	$rowitem = getInfo('company_information','secondary_color',"where id=1");


    $output = "<table>
                        <tr><th colspan='5'>SYSTEM LOG</th></tr>
                        <tr></tr>
                        <tr>    
                            <th class='text-left' style='background-color:$fillcolor; color:#fff;'>LINE</th>
                            <th class='text-left' style='background-color:$fillcolor; color:#fff;'>DATE</th>
                            <th class='text-left' style='background-color:$fillcolor; color:#fff;'>USER</th>
                            <th class='text-left' style='background-color:$fillcolor; color:#fff;'>DESCRIPTION</th>
                            <th class='text-left' style='background-color:$fillcolor; color:#fff;'>DETAILS</th>
                         </tr>
                            

                    ";


    $line = 1;
    while($obj=mysql_fetch_object($rs)){
		$name = array();
		$id = $obj->logid;
		$desc = $obj->description;	
		$query = $obj->query;
		$user = $obj->user_source;
		$fname = $obj->first_name;
		$mname=$obj->middle_name;
		$lname=$obj->last_name;
		array_push($name,$fname,$mname,$lname);
		$nameofuser = implode(' ', $name);
		$date = formatDate($obj->date_source);

		if($line%2==0){
           $rowbgcolor = "$rowitem";
        }
        else{
            $rowbgcolor = "#fff";
        }

		$output .=     "<tr class='itembalancerow'>
                            <td class='text-center' style='background-color:$rowbgcolor'>$line</td>
                            <td style='background-color:$rowbgcolor'>$date</td>
                            <td style='background-color:$rowbgcolor'>$nameofuser</td>
                            <td style='background-color:$rowbgcolor'>$desc</td>
                            <td style='background-color:$rowbgcolor; width:1000px'>$query</td>
                         </tr>  
                        ";
        $line++;
		
	}



    if(getNumRows($rs)==0){
        $output .= "<tr><td colspan='5' align='center'>No Results Found</td></tr>";
    }
    $output .="</table>";
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=system-log-as-of-".date('mdY').".xls");
    echo $output;


?>