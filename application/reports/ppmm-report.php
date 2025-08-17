<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
    Process Performance Monitoring and Measurement
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
                        <!--<div class="form-group">
                            <label class='control-label col-lg-4'>Personnel Type</label>
                            <div class="col-lg-8">
                                <select class='form-input form-control ppmmreport-type'></select>
                            </div>
                        </div>-->
                        <!--<div class="form-group">
                            <label class='control-label col-lg-4'>Section*</label>
                            <div class="col-lg-8">
                                <select class='form-input form-control ppmmreport-port portdropdownselect'></select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Courier/Driver</label>
                            <div class="col-lg-8">
                                <select class='form-input form-control ppmmreport-driver driverdropdownselect'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Date From</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control ppmmreport-datefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Date To</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control ppmmreport-dateto datepicker'>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-md-12 no-padding margin-top-xs'>
                    <div class='padded-with-border-engraved button-bottom'>
                        <div class='button-group'>
                            <div class='button-group-btn active' id='ppmmreport-trans-downloadbtn'>
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

        var tabPPMM = "#ppmmreport-menutabpane";
        $(tabPPMM+" .select2").select2();
        var datetoday = new Date();
        $(tabPPMM+' .datepicker').datepicker();

        $(tabPPMM+" .driverdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/personnel.php?position=DRIVER&type=COURIER&hastype=0",
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

        $(tabPPMM+" .portdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/origin-destination-port.php",
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