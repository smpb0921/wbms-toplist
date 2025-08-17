<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['key'])){
        $key = $_POST['key'];
        if($key == "80c32cab84de086d89df60e8856651fd"){ 

            

            require '../resources/PHPMailer/PHPMailerAutoload.php';  
            include_once("../addonsb/conn - Copy.php"); 
            $sent = []; 

            $recipients = 0;

            $qry = $conn1->query("SELECT name,email,shipper,shipper_id,group_concat(concat(waybill_number,'{}',ifnull(last_status_update_remarks,''),'{}',status,'{}',ifnull(date_format(updated_date,'%b. %d, %Y %h:%i %p'),'N/A')) separator '|') waybills from (
                                        SELECT 
                                            tw.shipper_id,
                                            tw.shipper_account_name shipper,
                                            tw.waybill_number,
                                            tw.status,
                                            tw.updated_date,
                                            tw.last_status_update_remarks,
                                            sce.email email,
                                            CONCAT(u.first_name,
                                                    IF(LENGTH(u.last_name) > 4,
                                                        CONCAT(' ', u.last_name),
                                                        '')) name
                                        FROM
                                            txn_waybill tw
                                                LEFT JOIN
                                            shipper_cs_email_addresses sce ON sce.shipper_id = tw.shipper_id
                                                LEFT JOIN
                                            user u ON u.email_address = sce.email
                                        WHERE
                                        tw.status != 'DELIVERED' and sce.email is not null
                                    ) x 
                                    group by x.shipper_id, email");



            for($i = 0; $result = $qry->fetch_object(); $i++) {

                $recipients++;
                $mail = new PHPMailer();
                try {  
                    $mail->isSMTP();  
                    $mail->Host = "sg2plcpnl0114.prod.sin2.secureserver.net";
                    $mail->Port = 587;
                    $mail->SMTPSecure = "tls";
                    $mail->SMTPAuth = true; 
                    $mail->Username = "no-reply@tpincorporated.com"; 
                    $mail->Password = "#reply.Dont-4wms"; 
                
                    $mail->setFrom("no-reply@tpincorporated.com", "WBMS Notifier"); 

                    $mail->addAddress($result->email, ($result->name != null ? $result->name : $result->email));

                    $mail->isHTML(true);
                    $mail->Subject = "Undelivered Packages of {$result->shipper} as of ".date('M d, Y');
                    $waybills = [];
                    $body = "<div style='font-family: \"Trebuchet MS\", Arial, Helvetica, sans-serif;'>
                                The Following BOL/Tracking Numbers are undelivered of Shipper: <b>{$result->shipper}</b> as of ".date('M d, Y')."<br><br><br>
                                <table style='
                                    font-family: \"Trebuchet MS\", Arial, Helvetica, sans-serif;
                                    border-collapse: collapse;'>
                                    <tr>
                                        <th style='background-color:#EE1F25; color: white; padding-bottom: 12px; padding-top: 12px; padding: 8px; border: 1px solid #ddd;'>
                                            BOL / Tracking No.
                                        </th>
                                        <th style='background-color:#EE1F25; color: white; padding-bottom: 12px; padding-top: 12px; padding: 8px; border: 1px solid #ddd;'>
                                            Latest Status
                                        </th>
                                        <th style='background-color:#EE1F25; color: white; padding-bottom: 12px; padding-top: 12px; padding: 8px; border: 1px solid #ddd;'>
                                            Latest Status Update Remarks
                                        </th>
                                        <th style='background-color:#EE1F25; color: white; padding-bottom: 12px; padding-top: 12px; padding: 8px; border: 1px solid #ddd;'>
                                            Date & Time Updated
                                        </th>
                                    </tr>";
                    foreach (explode("|", $result->waybills) as $key => $value) {
                        $value = explode("{}",$value);
                        $wyb = $value[0];
                        $last_update_remarks = $value[1];
                        $status = $value[2];
                        $timestamp = $value[3];
                        array_push($waybills,
                            "<tr>
                                <td style='padding: 8px; border: 1px solid #ddd;'>{$wyb}</td>
                                <td style='padding: 8px; border: 1px solid #ddd;'>{$status}</td>
                                <td style='padding: 8px; border: 1px solid #ddd;'>{$last_update_remarks}</td>
                                <td style='padding: 8px; border: 1px solid #ddd;'>{$timestamp}</td>
                            </tr>");
                    }
                    $body.= implode("",$waybills);
                    $body.= "</table><br><br><br><b><center>This is a system generated email and for notification purpose only. Please do not reply.</center></b></div>";
                    $mail->Body = $body;

                    if($mail->send()){
                        array_push($sent, [
                            "Name" => $result->name,
                            "Email" => $result->email,
                            "TrackingNos" => explode("|",$result->waybills)
                        ]);
                    }

                } 
                catch (Exception $e) {
                    echo json_encode([
                        "Success" => false,
                        "Message" => $mail->ErrorInfo
                    ]);
                }

                $mail = null;
                
            }

            if($recipients == count($sent)){ 
                $msgs = count($sent);
                print_r(json_encode([
                    "Success" => true,
                    "Message" => "({$msgs}) Email Messages has been sent."
                ]));
            }
            else if(count($sent) > 0 && count($sent) < $recipients) {
                print_r(json_encode([
                    "Success" => false,
                    "Message" => "Some of the email did not sent successfuly."
                ]));
            }
            else if(count($sent) == 0){
                print_r(json_encode([
                    "Success" => false,
                    "Message" => "There are no email sent successfuly."
                ]));
            }
            else {
                print_r(json_encode([
                    "Success" => false,
                    "Message" => "There are no email sent successfuly."
                ]));
            }

        }
        else {
            print_r(json_encode([
                "Success" => false,
                "Message" => "Authorization Failed"
            ]));
        }
    }
    else {
        print_r(json_encode([
            "Success" => false,
            "Message" => "Authorization Failed"
        ]));
    }
}
else {
    print_r(json_encode([
        "Success" => false,
        "Message" => "Authorization Failed"
    ]));
} 