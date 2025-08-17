<?php
include "conn - Copy.php";

        $qry = "Select 
                psd.picking_number,
                itm_reference,
                itm_name,
                csit(picked_qty,plu_qty), 
                concat(first_name, ' ',last_name ), 
                date_format(timestamp, '%b %d, %Y %H:%i:%s' ),
                concat('PCK',psd.id)
                from picking_so_details psd 
            left join user u on u.id=user_id
            Left join item_plu ip on ip.item_id=psd.item_id and ip.plu_uom=1 
            Left join item i on i.id=psd.item_id  
            order by psd.timestamp  desc limit 5";
 
        $result = $conn->query($qry);
        $to_enc = array();
        if ($result->num_rows > 0) { 
                while($row = $result->fetch_array()) {
                    array_push($to_enc,array(
                        "PickingNumber"=>$row[0],
                        "ItemCode"=>$row[1],
                        "ItemDescription"=>$row[2],
                        "Qty"=>$row[3],
                        "Picker"=>$row[4],
                        "TimeStamp"=>$row[5],
                        "PickingID"=>$row[6]
                    )); 
                } 
        } else {
            echo "Error";
        }

        $qry = "Select 
                psd.issuance_number,
                itm_reference,
                itm_name,
                csit(item_qty,plu_qty), 
                concat(first_name, ' ',last_name ), 
                date_format(timestamp, '%b %d, %Y %H:%i:%s' ),
                concat('ISD',psd.id)
                from issuance_dispatch psd 
            left join user u on u.id=user_id
            Left join item_plu ip on ip.item_id=psd.item_id and ip.plu_uom=1 
            Left join item i on i.id=psd.item_id  
            order by psd.timestamp  desc limit 5";

        $result = $conn->query($qry); 
        if ($result->num_rows > 0) { 
                while($row = $result->fetch_array()) {
                    array_push($to_enc,array(
                        "PickingNumber"=>$row[0],
                        "ItemCode"=>$row[1],
                        "ItemDescription"=>$row[2],
                        "Qty"=>$row[3],
                        "Picker"=>$row[4],
                        "TimeStamp"=>$row[5],
                        "PickingID"=>$row[6]
                    )); 
                } 
        } else {
            echo "Error";
        }
        
        
        $qry = "Select 
                psd.rcv_number,
                itm_reference,
                itm_name,
                csit(quantity,plu_qty), 
                concat(first_name, ' ',last_name ), 
                date_format(putaway_scan_datetime, '%b %d, %Y %H:%i:%s' ),
                concat('PWY',psd.id)
                from putaway_staging psd 
            left join user u on u.id=user_id
            Left join item_plu ip on ip.item_id=psd.item_id and ip.plu_uom=1 
            Left join item i on i.id=psd.item_id  
            order by psd.putaway_scan_datetime  desc limit 5";

        $result = $conn->query($qry); 
        if ($result->num_rows > 0) { 
                while($row = $result->fetch_array()) {
                    array_push($to_enc,array(
                        "PickingNumber"=>$row[0],
                        "ItemCode"=>$row[1],
                        "ItemDescription"=>$row[2],
                        "Qty"=>$row[3],
                        "Picker"=>$row[4],
                        "TimeStamp"=>$row[5],
                        "PickingID"=>$row[6]
                    )); 
                } 
        } else {
            echo "Error";
        }

        $qry = "Select 
                psd.receipt_number,
                itm_reference,
                itm_name,
                csit(received_qty,plu_qty), 
                concat(first_name, ' ',last_name ), 
                date_format(timestamp, '%b %d, %Y %H:%i:%s' ),
                concat('RCV',psd.id)
                from stock_receipt_details psd 
            left join user u on u.id=user_id
            Left join item_plu ip on ip.item_id=psd.item_id and ip.plu_uom=1 
            Left join item i on i.id=psd.item_id  
            order by psd.timestamp  desc limit 5";

        $result = $conn->query($qry); 
        if ($result->num_rows > 0) { 
                while($row = $result->fetch_array()) {
                    array_push($to_enc,array(
                        "PickingNumber"=>$row[0],
                        "ItemCode"=>$row[1],
                        "ItemDescription"=>$row[2],
                        "Qty"=>$row[3],
                        "Picker"=>$row[4],
                        "TimeStamp"=>$row[5],
                        "PickingID"=>$row[6]
                    )); 
                } 
        } else {
            echo "Error";
        } 
        
        echo json_encode($to_enc); 