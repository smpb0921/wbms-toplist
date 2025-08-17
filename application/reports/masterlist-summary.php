<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Masterlist 
    </div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <br>
                <!-- CONTENT -->
                <div class="form-horizontal">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Type</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input masterlistsummary-type select2' style='width:100%'>
                                    <option value='PORT'>Origin/Destination Port</option>
                                    <option value='ROUTE'>Destination Route</option>
                                    <option value='SHIPPER'>Shipper</option>
                                    <option value='CONSIGNEE'>Consignee</option>
                                    <option value='ACCOUNT'>Account Executive</option>
                                    <option value='AGENT'>Agent</option>
                                    <option value='SERVICES'>Services</option>
                                    <option value='MODE'>Mode of Transport</option>
                                    <option value='HANDLING'>Handling Instruction</option>
                                    <option value='DOCUMENT'>Accompanying Documents</option>
                                    <option value='DELIVERY'>Delivery Instruction</option>
                                    <option value='CARRIER'>Carrier</option>
                                    <option value='LOCATION'>Location</option>
                                    <option value='MOVEMENT'>Movement Type</option>
                                    <option value='VEHICLE'>Vehicle Type</option>
                                    <option value='UOM'>Unit of Measure</option>
                                    <option value='PAYMODE'>Pay Mode</option>
                                    <option value='ADDRESS'>District/City/Zip</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class='col-md-12'>
                        <br>
                    </div>
                    
                </div>
                <div class='col-md-12 no-padding margin-top-xs'>
                    <div class='padded-with-border-engraved button-bottom'>
                        <div class='button-group'>
                            <div class='button-group-btn active' id='masterlistsummary-trans-downloadbtn'>
                                <img src='../resources/img/download-excel2.png'>&nbsp;&nbsp;Download Excel 
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CONTENT-END -->
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        var tabMSTRPT = "#masterlistreport-menutabpane";
        $(tabMSTRPT+" .select2").select2();
        var datetoday = new Date();
        $(tabMSTRPT+' .datepicker').datepicker();


        

     });
</script>