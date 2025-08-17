<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Costing Summary
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
                            <label class='control-label col-lg-4'>Shipper</label>
                            <div class="col-lg-8">
                                <select class='form-control costingsummary-shipper shipperdropdownselect'>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Tracking No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-trackingnumber'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>BL No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-bolnumber'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Bill Reference</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-billreference'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Insurance Reference</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-insurancereference'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date From</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-datefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date To</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control costingsummary-dateto datepicker'>
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
                            <div class='button-group-btn active' id='costingsummary-trans-downloadbtn'>
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

        var tabWBCOST = "#costingreport-menutabpane";
        $(tabWBCOST+" .select2").select2();
        var datetoday = new Date();
        $(tabWBCOST+' .datepicker').datepicker();


        $(tabWBCOST+" .shipperdropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/shipper.php",
                dataType: 'json',
                delay: 100,
                data: function (params) {
                    return {
                                q: params.term // search term
                            };
                },
                processResults: function (data) {
                    return {
                                results: data
                            };
                },
                cache: true
            },
            minimumInputLength: 0,
            width: '100%'
        });

       

     });
</script>