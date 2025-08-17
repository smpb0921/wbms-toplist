<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Booking Summary
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
                                <select class='form-control form-input bookingsummary-format select2' style='width:100%'>
                                    <option value='1'>Format 1</option>
                                    <option value='2'>Format 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Booking Type</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input bookingsummary-bookingtype bookingtypedropdownselect' style='width:100%'>
                                   
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Status</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input bookingsummary-status select2' style='width:100%'>
                                    <option value=''>All</option>
                                    <option value='LOGGED'>Logged</option>
                                    <option value='POSTED'>Posted</option>
                                    <option value='APPROVED'>Approved</option>
                                    <option value='PICKED UP'>Picked Up</option>
                                    <option value='REJECTED'>Rejected</option>
                                    <option value='VOID'>Void</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Origin</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect bookingsummary-origin'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Destination</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect bookingsummary-destination'></select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Shipper</label>
                            <div class="col-lg-8">
                                <select class='form-control shipperdropdownselect bookingsummary-shipper'></select>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label class='control-label col-lg-4'>Consignee</label>
                            <div class="col-lg-8">
                                <select class='form-control consigneedropdownselect bookingsummary-consignee'></select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class='control-label col-lg-4'>City (Pickup)</label>
                            <div class="col-lg-8">
                                 <select class='form-control form-input bookingsummary-city bookingcitydropdownselect'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Region/Province (Pickup)</label>
                            <div class="col-lg-8">
                                 <select class='form-control form-input bookingsummary-region bookingregiondropdownselect'></select>
                            </div>
                        </div>
                        
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Service</label>
                            <div class="col-lg-8">
                                <select class='form-control servicesdropdownselect bookingsummary-service'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Mode of Transport</label>
                            <div class="col-lg-8">
                                <select class='form-control modeoftransportdropdownselect bookingsummary-modeoftransport'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Handling Instruction</label>
                            <div class="col-lg-8">
                                <select class='form-control handlinginstructiondropdownselect bookingsummary-handlinginstruction'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pay Mode</label>
                            <div class="col-lg-8">
                                <select class='form-control paymodedropdownselect bookingsummary-paymode'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control bookingsummary-pickupdatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Pickup Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control bookingsummary-pickupdateto datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control bookingsummary-createddatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Created Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control bookingsummary-createddateto datepicker'>
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
                            <!--<div class='button-group-btn active' id='bookingsummary-trans-generatebtn'>
                                <img src='../resources/img/download-pdf2.png'>&nbsp;&nbsp;Generate Report
                            </div>-->
                            <div class='button-group-btn active' id='bookingsummary-trans-downloadbtn'>
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

        var tabBKRPT = "#bookingreport-menutabpane";
        $(tabBKRPT+" .select2").select2();
        var datetoday = new Date();
        $(tabBKRPT+' .datepicker').datepicker();

        $(tabBKRPT+" .bookingtypedropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/booking-type.php",
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


        $(tabBKRPT+" .origindestinationdropdownselect").select2({
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

        $(tabBKRPT+" .modeoftransportdropdownselect").select2({
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

        $(tabBKRPT+" .servicesdropdownselect").select2({
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

        $(tabBKRPT+" .handlinginstructiondropdownselect").select2({
                ajax: {
                    url: "loadables/dropdown/handling-instruction.php",
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

        $(tabBKRPT+" .paymodedropdownselect").select2({
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


        $(tabBKRPT+" .shipperdropdownselect").select2({
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

        /*$(tabBKRPT+" .consigneedropdownselect").select2({
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
        });*/

        $(tabBKRPT+" .bookingcitydropdownselect").select2({
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

        $(tabBKRPT+" .bookingregiondropdownselect").select2({
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
        

     });
</script>