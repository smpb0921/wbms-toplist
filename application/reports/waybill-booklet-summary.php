<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
    include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
    <div class='header-page-inner'>
        Waybill Booklet
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
                            <label class='control-label col-lg-4'>Issued to</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control waybillbookletsummary-issuedto'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Issuance Date</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control waybillbookletsummary-datefrom datepicker'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class='control-label col-lg-4'>Validity Date</label>
                            <div class="col-lg-8">
                                <input type='text' class='form-control waybillbookletsummary-dateto datepicker'>
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
                            <div class='button-group-btn active' id='waybillbookletsummary-trans-downloadbtn'>
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

        var tabWBBKISS = "#waybillbookletreport-menutabpane";
        $(tabWBBKISS+" .select2").select2();
        var datetoday = new Date();
        $(tabWBBKISS+' .datepicker').datepicker();

       

     });
</script>