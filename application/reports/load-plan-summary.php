<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Load Plan Summary
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
                            <label class='control-label col-lg-4'>Status</label>
                            <div class="col-lg-8">
                                <select class='form-control form-input loadplansummary-status loadplanstatusdropdownselect' style='width:100%'>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Origin</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect loadplansummary-origin'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Destination</label>
                            <div class="col-lg-8">
                                <select class='form-control origindestinationdropdownselect loadplansummary-destination'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Mode of Transport</label>
                            <div class="col-lg-8">
                                <select class='form-control modeoftransportdropdownselect loadplansummary-modeoftransport'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Vehicle Type</label>
                            <div class="col-lg-8">
                                <select class='form-control vehicletypedropdownselect loadplansummary-vehicletype'></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Agent</label>
                            <div class="col-lg-8">
                                <select class='form-control agentdropdownselect loadplansummary-agent'></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Manifest No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-manifestnumber'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>MAWB No./BL No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-mawbblnumber'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Waybill No.</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-waybill'>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class='col-md-6'>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Location</label>
                            <div class="col-lg-8">
                                <select class='form-control locationdropdownselect loadplansummary-location'></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Carrier</label>
                            <div class="col-lg-8">
                                <select class='form-control carrierdropdownselect loadplansummary-carrier'></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>Document Date from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-docdatefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Document Date to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-docdateto datepicker'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>ETD from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-etdfrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>ETD to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-etdto datepicker'>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class='control-label col-lg-4'>ETA from</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-etafrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>ETA to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-input form-control loadplansummary-etato datepicker'>
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
                            <!--<div class='button-group-btn active' id='loadplansummary-trans-generatebtn'>
                                <img src='../resources/img/download-pdf2.png'>&nbsp;&nbsp;Generate Report
                            </div>-->
                            <div class='button-group-btn active' id='loadplansummary-trans-downloadbtn'>
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

        var tabLDPRPT = "#loadplanreport-menutabpane";
        $(tabLDPRPT+" .select2").select2();
        var datetoday = new Date();
        $(tabLDPRPT+' .datepicker').datepicker();


        $(tabLDPRPT+" .origindestinationdropdownselect").select2({
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

        $(tabLDPRPT+" .modeoftransportdropdownselect").select2({
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



        $(tabLDPRPT+" .loadplanstatusdropdownselect").select2({
                ajax: {
                        url: "loadables/dropdown/load-plan-status.report.php",
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

        $(tabLDPRPT+" .modeoftransportdropdownselect").select2({
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
        $(tabLDPRPT+" .vehicletypedropdownselect").select2({
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

        $(tabLDPRPT+" .agentdropdownselect").select2({
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

        $(tabLDPRPT+" .locationdropdownselect").select2({
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
        $(tabLDPRPT+" .carrierdropdownselect").select2({
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
        

     });
</script>