<?php
include_once("conn - Copy.php");

if(isset($_POST['receive'])){
    $rcvd = $_POST['receivedDate'];
    $rcvd = date('Y-m-d H:i:s',strtotime($rcvd));
    $rcvb = ucwords(strtolower($_POST['receivedBy']));
    $rcvt = $_POST['dayterm'];
    $rcvi = $_POST['billing_num'];
    
    $qry =  $conn1->prepare("UPDATE txn_billing set received_date = ?, received_by = ?, payment_due_date = DATE_ADD(?, INTERVAL ? DAY) where billing_number = ? ");
            $qry->bind_param("sssss",$rcvd,$rcvb,$rcvd,$rcvt,$rcvi);
            $good = $qry->execute();
    if($good) {

        print_r(json_encode(array(
            "Success" => true,
            "Message" => "Billing for {$rcvi} has been received."
        )));

    }
    else {

        print_r(json_encode(array(
            "Success" => true,
            "Message" => "Error Occured, please try again. ".mysqli_error($conn1).$qry->error
        )));
    }

}

if(isset($_POST['datenow'])){
    print_r(date('m/d/Y'));
}