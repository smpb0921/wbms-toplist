<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Billing Summary
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
                            <label class='control-label col-lg-4'>Format</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input billingsummary-format select2' style='width:100%'>
                                    <option value='1'>BILLING STATEMENT SERIES FORMAT</option>
                                    <option value='2'>Format 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Status</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input billingsummary-status billingstatusdropdownselect' style='width:100%'>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Paid</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input billingsummary-paidflag' style='width:100%'>
                                    <option value=''></option>
                                    <option value='1'>Yes</option>
                                    <option value='0'>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Shipper</label>
                            <div class="col-lg-8">
                                <select class='form-control shipperdropdownselect billingsummary-shipper'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Account Executive</label>
                            <div class="col-lg-8">
                                <select class='form-control shipperdropdownselect billingsummary-accountexecutive accountexecutivedropdownselect'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-createddatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-createddateto datepicker'>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                    <div class='col-md-6'>
                        
                        <div class="form-group bssummwbnumwrapper hidden">
                            <label class='control-label col-lg-4'>Waybill No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-waybill'>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class='control-label col-lg-4'>Due Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-duedatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Due Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-duedateto datepicker'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Statement Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-docdatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Statement Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-docdateto datepicker'>
                            </div>
                        </div>

						<div class="form-group">
                            <label class='control-label col-lg-4'>BS Start Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-bsstartseries'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>BS End Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control billingsummary-bsendseries'>
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
                            <!--<div class='button-group-btn active' id='billingsummary-trans-generatebtn'>
                                <img src='../resources/img/download-pdf2.png'>&nbsp;&nbsp;Generate Report
                            </div>-->
                            <div class='button-group-btn active' id='billingsummary-trans-downloadbtn'>
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

        var tabBLSRPT = "#billingreport-menutabpane";
        $(tabBLSRPT+" .select2").select2();
        var datetoday = new Date();
        $(tabBLSRPT+' .datepicker').datepicker();

   


        $(tabBLSRPT+" .shipperdropdownselect").select2({
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

        $(tabBLSRPT+" .accountexecutivedropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/account-executive.php",
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


        $(tabBLSRPT+" .billingstatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/billing-status.report.php",
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