<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Courier Delivery Transmittal
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
                            <label class='control-label col-lg-4'>Courier</label>
                            <div class="col-lg-8">
                                <select class='form-control couriertransmittal-courier courierdropdownselect'>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Destination</label>
                            <div class="col-lg-8">
                                <select class='form-control couriertransmittal-destination destinationdropdownselect'>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Transmittal Date From</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control couriertransmittal-datefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Transmittal Date To</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control couriertransmittal-dateto datepicker'>
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
                            <div class='button-group-btn active' id='couriertransmittal-trans-downloadbtn'>
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

        var tabCOURIERTRANSMITTAL = "#courierdeliverytransmittal-menutabpane";
        $(tabCOURIERTRANSMITTAL+" .select2").select2();
        var datetoday = new Date();
        $(tabCOURIERTRANSMITTAL+' .datepicker').datepicker();

        $(tabCOURIERTRANSMITTAL+" .courierdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/3pl.php",
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

        $(tabCOURIERTRANSMITTAL+" .destinationdropdownselect").select2({
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