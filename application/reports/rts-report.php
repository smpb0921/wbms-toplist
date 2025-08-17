<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        RTS Shipment Transmittal - OUTBOUND
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
                            <label class='control-label col-lg-4'>Shipper*</label>
                            <div class="col-lg-8">
                                <select class='form-input form-control rtsreport-shipper shipperdropdownselect'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Date From Returned*</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control rtsreport-datefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Date To Returned*</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control rtsreport-dateto datepicker'>
                            </div>
                        </div>
                    </div>
                <div class='col-md-12 no-padding margin-top-xs'>
                    <div class='padded-with-border-engraved button-bottom'>
                        <div class='button-group'>
                            <div class='button-group-btn active' id='rtsreport-trans-downloadbtn'>
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

        var tabRTS = "#rtsreport-menutabpane";
        $(tabRTS+" .select2").select2();
        var datetoday = new Date();
        $(tabRTS+' .datepicker').datepicker();

        $(tabRTS+" .shipperdropdownselect").select2({
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
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
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