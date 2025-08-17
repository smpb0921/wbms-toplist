<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Bill of Lading
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
                                <select class='form-control form-input waybillsummary-format select2' style='width:100%'>
                                    <option value='1'>Format 1</option>
                                    <option value='2'>Format 2</option>
                                    <option value='3'>LBC Format</option>
                                    <option value='4'>BOL Number Series Checking Format</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class='control-label col-lg-4'>With Manifest</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input waybillsummary-withmanifestflag select2' style='width:100%'>
                                    <option value='NO'>NO</option>
                                    <option value='YES'>YES</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Status</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input waybillsummary-status waybillstatusdropdownselect' style='width:100%'>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Billing Status</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input waybillsummary-billingstatus select2' style='width:100%'>
                                    <option value=''></option>
                                    <option value='1'>Billed</option>
                                    <option value='0'>Not Billed</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>MAWBL</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-mawbl'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Origin</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect waybillsummary-origin'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Destination</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect waybillsummary-destination'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Destination Route</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input waybillsummary-destinationroute destinationroutedropdownselect' style='width:100%'></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Shipper</label>
                            <div class="col-lg-8">
                                <select class='form-control shipperdropdownselect waybillsummary-shipper'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Consignee</label>
                            <div class="col-lg-8">
                                <select class='form-control consigneedropdownselect waybillsummary-consignee'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Booking Number</label>
                            <div class="col-lg-8">
                                <select class='form-control bookingnumberdropdownselect waybillsummary-bookingnumber'></select>
                            </div>
                        </div>
                        
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Service</label>
                            <div class="col-lg-8">
                                <select class='form-control servicesdropdownselect waybillsummary-service'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Mode of Transport</label>
                            <div class="col-lg-8">
                                <select class='form-control modeoftransportdropdownselect waybillsummary-modeoftransport'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pay Mode</label>
                            <div class="col-lg-8">
                                <select class='form-control paymodedropdownselect waybillsummary-paymode'></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Document Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-docdatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Document Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-docdateto datepicker'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-pudatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-pudateto datepicker'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-deldatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-deldateto datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>MAWBL Start Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-bolstartseries'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>MAWBL End Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-bolendseries'>
                            </div>
                        </div>
						<div class="form-group">
                            <label class='control-label col-lg-4'>BS Start Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-bsstartseries'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>BS End Series</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control waybillsummary-bsendseries'>
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
                            <!--<div class='button-group-btn active' id='waybillsummary-trans-generatebtn'>
                                <img src='../resources/img/download-pdf2.png'>&nbsp;&nbsp;Generate Report
                            </div>-->
                            <div class='button-group-btn active' id='waybillsummary-trans-downloadbtn'>
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

        var tabWBRPT = "#waybillreport-menutabpane";
        $(tabWBRPT+" .select2").select2();
        var datetoday = new Date();
        $(tabWBRPT+' .datepicker').datepicker();


        $(tabWBRPT+" .origindestinationdropdownselect").select2({
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

        $(tabWBRPT+" .modeoftransportdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/mode-of-transport.php",
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


        $(tabWBRPT+" .bookingnumberdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/booking-number-used-in-waybill.php",
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

        $(tabWBRPT+" .servicesdropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/services.php",
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


        $(tabWBRPT+" .paymodedropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/pay-mode.php",
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


        $(tabWBRPT+" .shipperdropdownselect").select2({
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

        $(tabWBRPT+" .consigneedropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/consignee.php",
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

        $(tabWBRPT+" .bookingcitydropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/booking.city.php",
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

        $(tabWBRPT+" .bookingregiondropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/booking.region.php",
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

        $(tabWBRPT+" .destinationroutedropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/destination-route.php",
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

        $(tabWBRPT+" .waybillstatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/waybill-status.report.php",
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