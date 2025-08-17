<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refBKA = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Booking Approval
        
    </div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

        <div class='tabpane-white margin-top-20 margin-bottom-10 tabpanesection'>
            <ul class="nav nav-tabs">
            <li role="presentation" class="active" data-pane='#pendingapprovals-pane' id='pendingapprovals-tab'><a href="#">Pending Approvals</a></li>
                <li role="presentation" data-pane='#approvalhistory-pane' id='approvalhistory-tab'><a href="#">Approval History</a></li>
            </ul>
            <div class='tab-panes'>
                <div class='pane active' id='pendingapprovals-pane'>
                        <table id='pendingapprovalstable'>
                            <tbody></tbody>

                        </table>
                </div>
                <div class='pane' id='approvalhistory-pane'>
                        <table id='approvalhistorytable'>
                            <tbody></tbody>

                        </table>
                </div>
            </div>
        </div>

        


    </div>
</div>

<div class="modal fade" id="viewbookinghistorymodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Booking History
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class='col-md-12' style='background-color:#fff'>
                    <div class='col-lg-3'>
                        <br>
                        <h3>Booking Details</h3>
                        <span class='viewbookinghistorymodal-bookingnumber' style="font-size: 20px; font-weight: bold; color: #649fbd;">
                        </span>
                        
                    </div>
                    <div class='col-lg-9'>
                        <br>
                        <div class='form-horizontal' style='background: #f5f5f5; padding: 15px'>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Location</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-location'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Booking Type</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-bookingtype'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Origin</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-origin'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Destination</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-destination'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Pickup Date</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-pickupdate'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Posted by</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-postedby'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Posted Date</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-posteddate'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Remarks</label> 
                                <div class='col-xs-9'>
                                    <span class='viewbookinghistorymodal-remarks'>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-12'><hr></div>

                    <div class='col-lg-7'>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Shipper Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account No.</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-accountnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account Name</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-accountname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Tel.</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-tel'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Name</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-companyname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Address</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-companyaddress'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Pickup Address</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipper-pickupaddress'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel hidden'>
                            <div class='panel-heading'>
                                Consignee Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account No.</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-consignee-accountnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account Name</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-consignee-accountname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Tel.</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-consignee-tel'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Name</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-consignee-companyname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Address</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-consignee-companyaddress'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Vehicle Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Vehicle Type</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-vehicletype'>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver For</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-driverfor'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Plate Number</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-platenumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-driver'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Helper</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-helper'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver Contact Number</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-drivercontactnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Time Ready</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-timeready'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Bill To</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-billto'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Trucking Details</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-truckingdetails'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        

                    </div>
                    <div class='col-lg-5'>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Package Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-4'>No. of Packages (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-numberofpackage'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Unit of Measure</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-uom'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Declared Value (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-declaredvalue'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Actual Weight (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-actualweight'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>CBM (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-vwcbm'>
                                                </span>
                                            </div>
                                        </div>
                                        <!--<div class="form-group">
                                            <label class='control-label col-md-4'>VW</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-vw'>
                                                </span>
                                            </div>
                                        </div>-->
                                        <div class="form-group hidden">
                                            <label class='control-label col-md-4'>Amount</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-amount'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Service</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-service'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Document</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-document'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Handling Instruction</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-handlinginstruction'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Mode of Transport</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-modeoftransport'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Pay Mode</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-paymode'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Other Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Shipment Description</label> 
                                            <div class='col-md-8'>
                                                <span class='viewbookinghistorymodal-shipmentdescription'>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <br>
                        

                    </div>

                </div>    
            </div>
            <div class="modal-footer">
                <br>
            </div>
        </div>
    </div>  
</div>

<div class="modal fade" id="reviewbookingmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Review Booking
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class='col-md-12' style='background-color:#fff'>
                    <div class='col-lg-3'>
                        <br>
                        <h3>Booking Details</h3>
                        <span class='reviewbookingmodal-bookingnumber' style="font-size: 20px; font-weight: bold; color: #649fbd;">
                        </span>
                        
                    </div>
                    <div class='col-lg-9'>
                        <br>
                        <div class='form-horizontal' style='background: #f5f5f5; padding: 15px'>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Location</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-location'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Booking Type</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-bookingtype'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Origin</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-origin'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Destination</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-destination'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Pickup Date</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-pickupdate'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Posted by</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-postedby'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Posted Date</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-posteddate'>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class='control-label col-xs-3'>Remarks</label> 
                                <div class='col-xs-9'>
                                    <span class='reviewbookingmodal-remarks'>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-12'><hr></div>

                    <div class='col-lg-7'>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Shipper Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account No.</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-accountnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account Name</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-accountname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Tel.</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-tel'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Name</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-companyname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Address</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-companyaddress'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Pickup Address</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipper-pickupaddress'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel hidden'>
                            <div class='panel-heading'>
                                Consignee Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account No.</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-consignee-accountnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Account Name</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-consignee-accountname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Tel.</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-consignee-tel'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Name</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-consignee-companyname'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Company Address</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-consignee-companyaddress'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Vehicle Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Vehicle Type</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-vehicletype'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver For</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-driverfor'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Plate Number</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-platenumber'>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-driver'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Helper</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-helper'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Driver Contact Number</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-drivercontactnumber'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Time Ready</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-timeready'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Bill To</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-billto'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-3'>Trucking Details</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-truckingdetails'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                    </div>
                    <div class='col-lg-5'>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Package Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-4'>No. of Packages (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-numberofpackage'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Unit of Measure</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-uom'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Declared Value (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-declaredvalue'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Actual Weight (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-actualweight'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>CBM (Estimated)</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-vwcbm'>
                                                </span>
                                            </div>
                                        </div>
                                        <!--<div class="form-group">
                                            <label class='control-label col-md-4'>VW</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-vw'>
                                                </span>
                                            </div>
                                        </div>-->
                                        <div class="form-group hidden">
                                            <label class='control-label col-md-4'>Amount</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-amount'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Service</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-service'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Document</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-document'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Handling Instruction</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-handlinginstruction'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Mode of Transport</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-modeoftransport'>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Pay Mode</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-paymode'>
                                                </span>
                                            </div>
                                        </div>
                                       
                                    </div>
                            </div>
                        </div>

                        <div class='panel viewpanel'>
                            <div class='panel-heading'>
                                Other Information
                            </div>
                            <div class="panel-body">
                                    <div class='form-horizontal'>

                                        <div class="form-group">
                                            <label class='control-label col-md-4'>Shipment Description</label> 
                                            <div class='col-md-8'>
                                                <span class='reviewbookingmodal-shipmentdescription'>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <br>
                        

                    </div>

                </div>    
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <div class='padded-with-border-engraved button-bottom'>
                                <div class='button-group'>
                                    <div class='button-group-btn active reviewbookingmodal-approverejectbtn' title='Approve' id='reviewbookingmodal-approvebtn'>
                                        <img src="../resources/img/checkmark.png">&nbsp;&nbsp;Approve
                                    </div>
                                    <div class='button-group-btn active reviewbookingmodal-approverejectbtn' title='Reject' id='reviewbookingmodal-rejectbtn'>
                                        <img src="../resources/img/cancel1.png">&nbsp;&nbsp;Reject
                                    </div>

                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>


<div class="modal fade" id="bookingapproval-confirmationmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    <span id='bookingapproval-confirmationmodal-action'></span>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
                <div class='col-md-12'>
                    <input type="hidden" id='bookingapproval-confirmationmodal-sourceaction'>
                    <input type="hidden" id='bookingapproval-confirmationmodal-bookingnumber'>
                    <div class='form-horizontal'>
                                <div class="form-group">
                                    <label class='control-label'>Remarks</label>
                                    <textarea class='form-control bookingapproval-confirmationmodal-remarks' rows='5'></textarea>
                                </div>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <div class="text-center">
                    <button class='btn btn-blue2 mybtn' id='bookingapproval-confirmationmodal-confirmbtn'>Confirm</button>
                    <button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
                </div>

            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){

        $('.modal-dialog').draggable();



        

        $("#pendingapprovalstable").flexigrid({
                url: 'loadables/ajax/transactions.booking-approval.pending-approvals.php',
                dataType: 'json',
                colModel : [
                        {display: '', name : 'actionbtn', width : 80, sortable : false, align: 'center'},
                        {display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left', hide: true},
                        {display: 'Booking No.', name : 'booking_number', width : 150, sortable : true, align: 'left'},
                        {display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
                        {display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
                        {display: 'Pickup Date', name : 'pickup_date', width : 130, sortable : true, align: 'left'},
                        {display: 'Location', name : 'location', width : 150, sortable : true, align: 'left'},
                        {display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
                        {display: 'Created Date', name : 'created_date', width : 130, sortable : true, align: 'left'},
                        {display: 'Posted by', name : 'posted_by', width : 200, sortable : true, align: 'left'},
                        {display: 'Posted Date', name : 'posted_date', width : 130, sortable : true, align: 'left'}
                ],
                searchitems : [
                        {display: 'Booking Number', name : 'booking_number', isdefault: true},
                        {display: 'Origin', name : 'origin'},
                        {display: 'Destination', name : 'destination'},
                        {display: 'Pickup Date', name : 'pickup_date'},
                        {display: 'Posted Date', name : 'posted_date'},
                        {display: 'Posted By', name : 'posted_by'}
                ],
                sortname: "posted_date",
                sortorder: "desc",
                usepager: true,
                title: "",
                useRp: true,
                rp: 15, //rows per page
                showTableToggleBtn: false,
                resizable: false,
                disableSelect: true,
                //width: 800,
                height: 500,
                singleSelect: false
        });

        $("#approvalhistorytable").flexigrid({
                url: 'loadables/ajax/transactions.booking-approval.approval-history.php',
                dataType: 'json',
                colModel : [
                        {display: '', name : 'actionbtn', width : 80, sortable : false, align: 'center'},
                        {display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left', hide: true},
                        {display: 'Booking No.', name : 'booking_number', width : 150, sortable : true, align: 'left'},
                        {display: 'Action', name : 'action', width : 150, sortable : false, align: 'center'},
                        {display: 'Remarks', name : 'remarks', width : 450, sortable : true, align: 'left'},
                        {display: 'Approved/Rejected by', name : 'created_by', width : 200, sortable : true, align: 'left'},
                        {display: 'Date', name : 'created_date', width : 130, sortable : true, align: 'left'}
                ],
                searchitems : [
                        {display: 'Booking Number', name : 'booking_number', isdefault: true},
                        {display: 'Remarks', name : 'remarks'},
                        {display: 'Approver', name : 'created_by'},
                        {display: 'Date', name : 'created_date'}
                ],
                buttons : [
                        {name: 'Download', bclass: 'download', onpress : downloadBookingApprovalHistory},
                        {separator: true}
                ],
                sortname: "created_date",
                sortorder: "desc",
                usepager: true,
                title: "",
                useRp: true,
                rp: 15, //rows per page
                showTableToggleBtn: false,
                resizable: false,
                disableSelect: true,
                //width: 800,
                height: 500,
                singleSelect: false
        });


        function downloadBookingApprovalHistory(){
            window.open("Printouts/excel/transactions.booking-approval-history.php");
        }
        
        userAccess();

        var refBKA = <?php echo json_encode($refBKA); ?>;


        setInterval(function(){
            $('#pendingapprovalstable').flexOptions({
                    url:'loadables/ajax/transactions.booking-approval.pending-approvals.php',
                    sortname: 'posted_date',
                    sortorder: "desc"
            }).flexReload(); 
            $('#approvalhistorytable').flexOptions({
                    url:'loadables/ajax/transactions.booking-approval.approval-history.php',
                    sortname: 'created_date',
                    sortorder: "desc"
            }).flexReload(); 
        },10000);

       
        
        
       
            

        
    });
    
</script>