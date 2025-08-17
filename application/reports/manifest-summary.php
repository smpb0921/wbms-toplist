<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Manifest Summary
    </div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <br>
                <!-- CONTENT -->
                <div class="form-horizontal">
                    <div class='col-md-12'>
                        <fieldset>
                            <legend>Manifest Details</legend>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Manifest Txn Status</label>
                                    <div class="col-lg-8">
                                        <select class='form-control form-input manifestsummary-mftstatus manifeststatusdropdownselect' style='width:100%'>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Mode of Transport</label>
                                    <div class="col-lg-8">
                                        <select class='form-control modeoftransportdropdownselect manifestsummary-mftmodeoftransport'></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Agent</label>
                                    <div class="col-lg-8">
                                        <select class='form-control agentdropdownselect manifestsummary-mftagent'></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Carrier</label>
                                    <div class="col-lg-8">
                                        <select class='form-control carrierdropdownselect manifestsummary-mftcarrier'></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Vehicle Type</label>
                                    <div class="col-lg-8">
                                        <select class='form-control vehicletypedropdownselect manifestsummary-mftvehicletype'></select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Document Date from</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-mftdocdatefrom datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Document Date to</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-mftdocdateto datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Created Date from</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-mftcreatedfrom datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Created Date to</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-mftcreatedto datepicker'>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    
                    <!--<div class='col-md-12'>
                        <fieldset>
                            <legend>Waybill Details</legend>
                            <div class="col-md-6">
                                

                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Status</label>
                                    <div class="col-lg-8">
                                        <select class='form-control form-input manifestsummary-wbstatus waybillstatusdropdownselect' style='width:100%'>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>MAWBL</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbmawbl'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Origin</label>
                                    <div class="col-lg-8">
                                        <select class='form-control origindestinationdropdownselect manifestsummary-wborigin'></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Destination</label>
                                    <div class="col-lg-8">
                                        <select class='form-control origindestinationdropdownselect manifestsummary-wbdestination'></select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Shipper</label>
                                    <div class="col-lg-8">
                                        <select class='form-control shipperdropdownselect manifestsummary-wbshipper'></select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Consignee</label>
                                    <div class="col-lg-8">
                                        <select class='form-control consigneedropdownselect manifestsummary-wbconsignee'></select>
                                    </div>
                                </div>
                                
                            </div>
                            <div class='col-md-6'>

                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Document Date from</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbdocdatefrom datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Document Date to</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbdocdateto datepicker'>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Pickup Date from</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbpudatefrom datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Pickup Date to</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbpudateto datepicker'>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Created Date from</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbcreateddatefrom datepicker'>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class='control-label col-lg-4'>Created Date to</label>
                                    <div class="col-lg-8">
                                        <input type='text' class='form-input form-control manifestsummary-wbcreateddateto datepicker'>
                                    </div>
                                </div>
                                
                            </div>
                        </fieldset>
                    </div>-->
                    <div class='col-md-12'>
                        <br>
                    </div>
                    
                </div>
                <div class='col-md-12 no-padding margin-top-xs'>
                    <div class='padded-with-border-engraved button-bottom'>
                        <div class='button-group'>
                            <!--<div class='button-group-btn active' id='manifestsummary-trans-generatebtn'>
                                <img src='../resources/img/download-pdf2.png'>&nbsp;&nbsp;Generate Report
                            </div>-->
                            <div class='button-group-btn active' id='manifestsummary-trans-downloadbtn'>
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

        var tabDSPMFT = "#manifestreport-menutabpane";
        $(tabDSPMFT+" .select2").select2();
        var datetoday = new Date();
        $(tabDSPMFT+' .datepicker').datepicker();




        $(tabDSPMFT+" .origindestinationdropdownselect").select2({
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

        $(tabDSPMFT+" .modeoftransportdropdownselect").select2({
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



        $(tabDSPMFT+" .manifeststatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/manifest-status.report.php",
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

        $(tabDSPMFT+" .manifestloadplandropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/manifest-load-plan-number.report.php",
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



        $(tabDSPMFT+" .agentdropdownselect").select2({
                    ajax: {
                            url: "loadables/dropdown/agent.php",
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

        $(tabDSPMFT+" .locationdropdownselect").select2({
                    ajax: {
                        url: "loadables/dropdown/user-assigned-locations.php",
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
        $(tabDSPMFT+" .carrierdropdownselect").select2({
                    ajax: {
                            url: "loadables/dropdown/carrier.php",
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


        $(tabDSPMFT+" .shipperdropdownselect").select2({
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

        $(tabDSPMFT+" .consigneedropdownselect").select2({
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

        $(tabDSPMFT+" .vehicletypedropdownselect").select2({
            ajax: {
                url: "loadables/dropdown/vehicle-type.php",
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