<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refLDP = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Load Plan
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper loadplan-content'>

                        <div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                    <div class='button-group-btn active' title='New' id='loadplan-trans-newbtn' data-toggle='modal' href='#newloadplanmodal'>
                                        <img src="../resources/img/add.png">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='loadplan-inputfields'>
                                				<input type='hidden' id='pgtxnloadplan-id'>
                                				<div class='col-lg-2'>
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                        	<div class='col-md-12'>
                                                                <label class='control-label'>Load Plan No.</label>
                                                                <input type='text' class='form-input form-control transactionnumber'>
                                                            </div>
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="firstprevnextlastbtn">
                                                        <div class="btn-group btn-group-justified btn-group-sm margin-bottom-xs stock-item-refbuttons" role="group" aria-label="...">
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default stock-item-firstbtn" data-info='first'>
                                                                    <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='second'>
                                                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='third'>
                                                                    <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                            <div class="btn-group" role="group">
                                                                <button type="button" class="btn btn-default" data-info='fourth'>
                                                                    <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='transaction-status-div margin-bottom-xs statusdiv'><br></div>
                                                    <div class='button-group-btn fluid searchbtn active' data-toggle='modal' href='#loadplan-searchmodallookup'>
                                                        <!--<i class='fa fa-search fa-lg fa-space'></i>-->
                                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-lg-10">

                                                	<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
							                            <div class="panel-group classictheme margin-bottom-xs" id="loadplan-panelheader-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#loadplan-panelheader">
							                                                Header
							                                        </div>
							                                        <div id="loadplan-panelheader" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            		<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="header-errordiv"></div>
									                                                </div>
									                                            </div>
							                                                    <div class="form-horizontal">
							                                                        <div class="col-md-6">
							                                                        	
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Location</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-location'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                            	<label class='control-label col-lg-3'>Carrier</label>
							                                                            	<div class="col-lg-9">
							                                                            		<input type='text' class='form-control loadplan-carrier'>
							                                                            	</div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Origin</label>
							                                                                <div class="col-lg-9">
							                                                                   <input type='text' class='form-control loadplan-origin'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Destination</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-destination'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                            		<label class='control-label col-lg-3'>Mode of Transport</label>
							                                                            		<div class="col-lg-9">
							                                                            			<input type='text' class='form-control loadplan-modeoftransport'>
							                                                            		</div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                            		<label class='control-label col-lg-3'>Vehicle Type</label>
							                                                            		<div class="col-lg-9">
							                                                            			<input type='text' class='form-control loadplan-vehicletype'>
							                                                            		</div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                            		<label class='control-label col-lg-3'>Agent</label>
							                                                            		<div class="col-lg-9">
							                                                            			<input type='text' class='form-control loadplan-agent'>
							                                                            		</div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Remarks</label>
							                                                                <div class="col-lg-9">
							                                                                    <textarea class='form-control loadplan-remarks' rows='4'></textarea>
							                                                                </div>
							                                                            </div>
							                                                            
							                                                         

							                                                            
							                                                        </div>
							                                                        <div class='col-md-6'>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Manifest No.</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-manifestnumber'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>MAWB No./BL No.</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-mawbbl'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-documentdate'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>ETD</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-etd'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>ETA</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-eta'>
							                                                                </div>
							                                                            </div>
							                                                            
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-createddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-createdby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Updated Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-updateddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Updated by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-updatedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Status Update Remarks</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control loadplan-statusupdateremarks'>
							                                                                </div>
							                                                            </div>

							                                                            

							                                                            
							                                                        </div>
							                                                        
							                                                    </div>
							                                            </div>
							                                        </div>
							                                    </div>
							                            </div>

							                            <div class="panel-group classictheme margin-bottom-xs" id="loadplan-waybillpaneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#loadplan-waybillpaneldetails">
							                                                BOL Details
							                                        </div>
							                                        <div id="loadplan-waybillpaneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="waybilldetail-errordiv"></div>
									                                                </div>
									                                                <div class='col-md-12'>

											                                        	<div class='table-sm'>
											                                        		<table id='loadplan-waybilltbl'>
																								<tbody></tbody>
																							</table>
																		            		<!--<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='loadplan-waybilltbl' style='width:100%'>
																		            			<thead>
																		            				<tr>
																		            					<th class='column-nosort column-checkbox text-center'></th>
																		            					<th>WAYBILL NO.</th>
																		            					<th>ORIGIN</th>
																		            					<th>DESTINATION</th>
																		            					<th>DESTINATION ROUTE</th>
																		            				</tr>
																		            			</thead>
																		            			<tbody>

																		            			</tbody>
																		            		</table>-->
																		            	</div>

											                                        </div>
									                                        </div>
									                                        
									                                     
							                                               	<br>
							                                                <div class='form-horizontal'>
							                                                		<div class='col-md-offset-8 col-md-4'>
							                                                			<div class="form-group">
							                                                                <label class='control-label col-lg-5'>Total No. of Package</label>
							                                                                <div class="col-lg-7">
							                                                                    <input type='text' class='text-right form-control loadplan-totalnumofpackage'>
							                                                                </div>
							                                                            </div>
							                                                			<div class="form-group">
							                                                                <label class='control-label col-lg-5'>Total Actual Weight (kg)</label>
							                                                                <div class="col-lg-7">
							                                                                    <input type='text' class='text-right form-control loadplan-totalactualweight'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-5'>Total CBM</label>
							                                                                <div class="col-lg-7">
							                                                                    <input type='text' class='text-right form-control loadplan-totalcbm'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-5'>Total Vol. Weight</label>
							                                                                <div class="col-lg-7">
							                                                                    <input type='text' class='text-right form-control loadplan-totalvolweight'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-5'>Total No. of BOL</label>
							                                                                <div class="col-lg-7">
							                                                                    <input type='text' class='text-right form-control loadplan-totalnumofwaybill'>
							                                                                </div>
							                                                            </div>
							                                                        </div>
							                                                </div>   
							                                                        
							                                            </div>
							                                        </div>
							                                    </div>


							                            </div>   
							                                          
							                        </div>

							                        <div class='col-md-12 no-padding margin-top-xs savecancelbuttonwrapper'>
							                            
							                        </div>

                                                </div>
                              
                        </div>


                        

                        

                    
                </div>
                <!-- CONTENT-END -->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="newloadplanmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    New Load Plan
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		Please provide the following information.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<div class='modal-errordiv'></div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Location</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input newloadplanmodal-location locationdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Carrier</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input newloadplanmodal-carrier carrierdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Origin</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input newloadplanmodal-origin origindestinationdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Destination</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input newloadplanmodal-destination origindestinationdropdownselect' style='width:100%' multiple></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Mode of Transport</label>
            					<div class="col-lg-9">
            						<select class='form-control newloadplanmodal-modeoftransport modeoftransportdropdownselect'></select>
            					</div>
            				</div>

            				<div class="form-group">
            					<label class='control-label col-lg-3'>Vehicle Type</label>
            					<div class="col-lg-9">
            						<select class='form-control newloadplanmodal-vehicletype vehicletypedropdownselect'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Agent</label>
            					<div class="col-lg-9">
            						<select class='form-control newloadplanmodal-agent agentdropdownselect'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Remarks</label>
            					<div class="col-lg-9">
            						<textarea class='form-control newloadplanmodal-remarks' rows='4'></textarea>
            					</div>
            				</div>
            				<div class="form-group hidden">
            					<label class='control-label col-lg-3'>Manifest No.</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control newloadplanmodal-manifestnumber'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Date</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control newloadplanmodal-documentdate datepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>ETD</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control newloadplanmodal-etd datetimepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>ETA</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control newloadplanmodal-eta datetimepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>MAWB No./BL No.</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control newloadplanmodal-mawbbl'>
            					</div>
            				</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='newloadplanmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="editloadplanmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Edit Load Plan
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		Please provide the following information. Waybill(s) with different origin / destination / mode of transport will be deleted.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<div class='modal-errordiv'></div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Location</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input editloadplanmodal-location locationdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Carrier</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input editloadplanmodal-carrier carrierdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Origin</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input editloadplanmodal-origin origindestinationdropdownselect' style='width:100%'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Destination</label>
            					<div class="col-lg-9">
            						<select class='form-control form-input editloadplanmodal-destination origindestinationdropdownselect' style='width:100%' multiple></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Mode of Transport</label>
            					<div class="col-lg-9">
            						<select class='form-control editloadplanmodal-modeoftransport modeoftransportdropdownselect'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Vehicle Type</label>
            					<div class="col-lg-9">
            						<select class='form-control editloadplanmodal-vehicletype vehicletypedropdownselect'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Agent</label>
            					<div class="col-lg-9">
            						<select class='form-control editloadplanmodal-agent agentdropdownselect'></select>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Remarks</label>
            					<div class="col-lg-9">
            						<textarea class='form-control editloadplanmodal-remarks' rows='4'></textarea>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Manifest No.</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control editloadplanmodal-manifestnumber'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>Date</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control editloadplanmodal-documentdate datepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>ETD</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control editloadplanmodal-etd datetimepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>ETA</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control editloadplanmodal-eta datetimepicker'>
            					</div>
            				</div>
            				<div class="form-group">
            					<label class='control-label col-lg-3'>MAWB No./BL No.</label>
            					<div class="col-lg-9">
            						<input type='text' class='form-control editloadplanmodal-mawbbl'>
            					</div>
            				</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='editloadplanmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="ldpaddwaybillnumbermodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add BOL
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
           		<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				
            				<div class="form-group">
            					<label class='control-label'>BOL No.</label>
            					<input type='text' class='form-control ldpaddwaybillnumbermodal-waybillnumber'>
            				</div>
            				<div class="form-group">
	            					<div class='button-group-btn fluid active' id='ldpaddwaybillnumbermodal-addbtn'>
                                        <img src="../resources/img/add.png">&nbsp;&nbsp;Add Waybill
                                    </div>
                            </div>
            		</div>
            		<br>
            	</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="voidloadplantransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Void Load Plan Transaction
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		To void transaction, please provide a reason.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='voidloadplantransactionmodal-id'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Load Plan No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control voidloadplantransactionmodal-txnnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control voidloadplantransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='voidloadplantransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="unpostloadplantransactionmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Unpost Load Plan Transaction
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<div class='col-md-3'>
            		To unpost transaction, please provide a reason.
            	</div>
            	<div class='col-md-9'>
            		<div class='form-horizontal'>
            				<input type='hidden' id='unpostloadplantransactionmodal-id'>
            				<div class='modal-errordiv'></div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Load Plan No.</label>
	            				<div class='col-md-9'>
	            					<input type='text' class='form-input form-control unpostloadplantransactionmodal-txnnumber' disabled="true">
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control unpostloadplantransactionmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='unpostloadplantransactionmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="ldpsearchwaybilltransactionmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    BOL Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<table id='ldpsearchwaybilltransactiontbl'>
            		<tbody></tbody> 
            	</table>
            	
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="loadplan-searchmodallookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Search for Load Plan
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
	            	<div class="form-horizontal">
	            		<div class='col-md-6'>
	            			<div class="form-group">
			            			<label class='control-label col-md-3'>Status</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-status loadplanstatusdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Location</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-location locationdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Origin</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-origin origindestinationdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Destination</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-destination origindestinationdropdownselect' multiple>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Mode</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-mode modeoftransportdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Agent</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-agent agentdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Carrier</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input loadplansearch-carrier carrierdropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		
		            		
			            </div>
		            	<div class='col-md-6'>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Vehicle Type</label>
			            			<div class="col-md-8">
			            				 <select class='form-control form-input loadplansearch-vehicletype vehicletypedropdownselect'>
			            				 	
			            				 </select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Manifest No.</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control loadplansearch-manifestnumber'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>MAWBL/BL</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control loadplansearch-mawbl'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>BOL Number</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control loadplansearch-waybillnumber'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Document Date From</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control loadplansearch-docdatefrom datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Dcoument Date To</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control loadplansearch-docdateto datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<div class="col-md-offset-4 col-md-8">
			            				<div class='button-group-btn fluid active' id='loadplansearch-searchbtn'>
	                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                                    </div>
			            			</div>
			            	</div>
		            	</div>			
	            	</div>
            		
            		<div class='col-md-12'>
            			<br>
		            	<table id='loadplansearch-table'>
							<tbody></tbody>
						</table>
						<br>
					</div>
           	</div>
        </div>
    </div>
</div>


<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabLDP = '#loadplan-menutabpane';
			var inputfieldsLDP = ".loadplan-inputfields";
			

			//$(tabLDP+' .modal-dialog').draggable();
			$(inputfieldsLDP+' input,'+inputfieldsLDP+' textarea,'+inputfieldsLDP+' select').attr('disabled','disabled');
        	$(inputfieldsLDP+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabLDP+" .select2").select2();
        	var datetoday = new Date();
        	$(tabLDP+' .datepicker').datepicker();
        	$(tabLDP+' .datetimepicker').datetimepicker();

       
       		var refLDP = <?php echo json_encode($refLDP); ?>;
	        if(refLDP!=''){
	            getLoadPlanInformation(refLDP);
	            currentloadplanTxn = refLDP;
	        }

	        $(tabLDP+" .locationdropdownselect").select2({
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

	    	$(tabLDP+" .loadplanstatusdropdownselect").select2({
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

	    	$(tabLDP+" .origindestinationdropdownselect").select2({
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
	    	$(tabLDP+" .modeoftransportdropdownselect").select2({
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
	    	$(tabLDP+" .carrierdropdownselect").select2({
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

	        $(tabLDP+" .vehicletypedropdownselect").select2({
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

	        $(tabLDP+" .agentdropdownselect").select2({
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

	        function ldpAddWaybillNumber(){
	    		$(tabLDP+' #ldpaddwaybillnumbermodal').modal('show');
	    	}

	    	function ldpDeleteWaybillNumber(){
	    		wbnumbersid = [];
	    		ldpnumber = $(tabLDP+' #pgtxnloadplan-id').attr('pgtxnloadplan-number');

	    		$(tabLDP+' .ldpwaybillcheckbox:checked').each(function(){
	    			wbnumbersid.push($(this).attr('rowid'));
	    		});

	    		if(wbnumbersid.length>0){
		    		$.post(server+'load-plan.php',{deleteWaybillNumber:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',wbnumbersid:wbnumbersid},function(data){

		    			if(data.trim()=='success'){
		    				$(tabLDP+' #loadplan-waybilltbl').flexOptions({
												url:'loadables/ajax/transactions.load-plan-waybill.php?reference='+ldpnumber,
												sortname: 'waybill_number',
												sortorder: "asc"
							}).flexReload();

							getWaybillTotalWeightLDP(ldpnumber);

						
		    			}
		    			else{
		    				alert(data);
		    			}



		    		});
		    	}
	    	}


	    	function ldpSearchForWaybill(){
	    		$(tabLDP+' #ldpsearchwaybilltransactionmodal').modal('show');
	    	}



			$(tabLDP+' #loadplan-waybilltbl').flexigrid({
				url: 'loadables/ajax/transactions.load-plan-waybill.php?reference='+refLDP,
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'Date of Waybill', name : 'document_date', width : 150, sortable : true, align: 'left'},
						{display: 'Requested Delivery', name : 'delivery_date', width : 130, sortable : true, align: 'left'},
						{display: 'BOL No.', name : 'waybill_number', width : 130, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'No. of Package', name : 'package_number_of_packages', width : 150, sortable : true, align: 'left'},
						{display: 'Actual Weight', name : 'package_actual_weight', width : 180, sortable : true, align: 'left'},
						{display: 'Declared Value', name : 'declared_value', width : 150, sortable : true, align: 'left'},
						{display: 'CBM', name : 'package_cbm', width : 180, sortable : true, align: 'left'},
						{display: 'VMW', name : 'package_vw', width : 180, sortable : true, align: 'left'},
						{display: 'Shipper', name : 'shipper_account_name', width : 180, sortable : true, align: 'left'},
						{display: 'Consignee', name : 'consignee_account_name', width : 180, sortable : true, align: 'left'},
						{display: 'Mode of Transport', name : 'modeoftransport', width : 180, sortable : true, align: 'left'}

				],
				buttons : [
						{name: 'Add', bclass: 'add ldpaddwaybillbtn hidden', onpress : ldpAddWaybillNumber},
						{separator: true},
						{name: 'Delete', bclass: 'delete ldpdeletewaybillbtn hidden', onpress : ldpDeleteWaybillNumber},
						{separator: true},
						{name: 'Search for BOL', bclass: 'search ldpwaybilllookupbtn hidden', onpress : ldpSearchForWaybill}
				],
				searchitems : [
						{display: 'BOL No.', name : 'waybill_number', isdefault: true},
						{display: 'Destination', name : 'destination'}
				],
				sortname: "waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 250,
				singleSelect: false,
				disableSelect: true
			});

			$(tabLDP+' #ldpsearchwaybilltransactiontbl').flexigrid({
				url: 'loadables/ajax/transactions.load-plan-waybill-lookup.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'BOL No.', name : 'waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 130, sortable : true, align: 'left'},
						{display: 'Date', name : 'document_date', width : 130, sortable : true, align: 'left'},
						{display: 'Shipper', name : 'shipper_account_name', width : 200, sortable : true, align: 'left'},
						{display: 'Consignee', name : 'consignee_account_name', width : 200, sortable : true, align: 'left'},
						{display: 'No. of Package', name : 'package_number_of_packages', width : 150, sortable : true, align: 'right'},

						{display: 'Actual Weight', name : 'package_actual_weight', width : 180, sortable : true, align: 'right'},
						{display: 'CBM', name : 'cbm', width : 180, sortable : true, align: 'right'},
						{display: 'Vol. Weight', name : 'volumetric_weight', width : 180, sortable : true, align: 'right'}

				],
				buttons : [
						{name: 'Add', bclass: 'add ldpwaybilllookup-addbtn'}
				],
				searchitems : [
						{display: 'BOL No.', name : 'waybill_number', isdefault: true},
						{display: 'Destination', name : 'destination'}

				],
				sortname: "waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 450,
				singleSelect: false,
				disableSelect: true
			});

			$(tabLDP+" #loadplansearch-table").flexigrid({
				url: 'loadables/ajax/transactions.load-plan-search.php',
				dataType: 'json',
				colModel : [
						{display: 'Load Plan No.', name : 'txn_load_plan.load_plan_number', width : 120, sortable : true, align: 'left'},
						{display: 'Status', name : 'txn_load_plan.status', width : 100, sortable : true, align: 'left'},
						{display: 'Manifest No.', name : 'txn_load_plan.manifest_number', width : 120, sortable : true, align: 'left'},
						{display: 'Location', name : 'location', width : 100, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origintbl.description', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destinationfiltered', width : 200, sortable : true, align: 'left'},
						{display: 'Carrier', name : 'carrier.description', width : 200, sortable : true, align: 'left'},
						{display: 'Mode', name : 'mode_of_transport.description', width : 120, sortable : true, align: 'left'},
						{display: 'Agent', name : 'agent.company_name', width : 200, sortable : true, align: 'left'},
						{display: 'Vehicle Type', name : 'vehicle_type.description', width : 200, sortable : true, align: 'left'},
						{display: 'Document Date', name : 'txn_load_plan.document_date', width : 100, sortable : true, align: 'left'},
						{display: 'MAWBL No./BL No.', name : 'txn_load_plan.mawbl_bl', width : 130, sortable : true, align: 'left'},
						{display: 'Created by', name : 'createdby', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'txn_load_plan.created_date', width : 150, sortable : true, align: 'left'},
						{display: 'System ID', name : 'txn_load_plan.id', width : 80, sortable : true, align: 'left', hide:true}
				],
				
				searchitems : [
						{display: 'Load Plan No.', name : 'txn_load_plan.load_plan_number', isdefault: true},
						{display: 'Manifest No.', name : 'txn_load_plan.manifest_number'},
						{display: 'Status', name : 'txn_load_plan.status'},
						{display: 'Location', name : 'location'},
						{display: 'Origin', name : 'origintbl.description'},
						//{display: 'Destination', name : 'destination'},
						{display: 'Mode', name : 'mode_of_transport.description'},
						{display: 'Agent', name : 'agent.company_name'}
				],
				sortname: "txn_load_plan.load_plan_number",
				sortorder: "asc",
				usepager: true,
				title: "Search Results", 
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 400,
				singleSelect: false,
				disableSelect: true
			});







        
			
    		userAccess();


			

	});
	



</script>