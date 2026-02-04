<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refWBM = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		BOL Movement
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper waybillmovement-content'>

                        <div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                    <div class='button-group-btn active' title='New' id='waybillmovement-trans-newbtn' data-toggle='modal' href='#newwaybillmovementmodal'>
                                        <img src="../resources/img/add.png">
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='waybillmovement-inputfields'>
                                				<input type='hidden' id='pgtxnwaybillmovement-id'>
                                				<div class='col-lg-2'>
                                                    <div class="form-horizontal">
                                                        <div class="form-group">
                                                        	<div class='col-md-12'>
                                                                <label class='control-label'>BOL Movement No.</label>
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
                                                    <div class='button-group-btn fluid searchbtn active' data-toggle='modal' href='#waybillmovement-searchmodallookup'>
                                                        <!--<i class='fa fa-search fa-lg fa-space'></i>-->
                                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-lg-10">

                                                	<div class='col-md-12 no-padding margin-bottom-xs margin-top-sm'>
							                            <div class="panel-group classictheme margin-bottom-xs" id="waybillmovement-panelheader-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybillmovement-panelheader">
							                                                Header
							                                        </div>
							                                        <div id="waybillmovement-panelheader" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            		<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="header-errordiv"></div>
									                                                </div>
									                                            </div>
							                                                    <div class="form-horizontal">
							                                                        <div class="col-md-6">
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Movement Type</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-movementtype'>
							                                                                </div>
							                                                            </div>
																						<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Shipment Type</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-shipmenttype'>
							                                                                </div>
							                                                            </div>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Location</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-location'>
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Remarks</label>
							                                                                <div class="col-lg-9">
							                                                                    <textarea class='form-control waybillmovement-remarks' rows='4'></textarea>
							                                                                </div>
							                                                            </div>
							                                                            
							                                                         

							                                                            
							                                                        </div>
							                                                        <div class='col-md-6'>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Document Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-documentdate datepicker'>
							                                                                </div>
							                                                            </div>
							                                                        	<div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-createddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Created by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-createdby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Updated Date</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-updateddate alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>
							                                                            <div class="form-group">
							                                                                <label class='control-label col-lg-3'>Updated by</label>
							                                                                <div class="col-lg-9">
							                                                                    <input type='text' class='form-control waybillmovement-updatedby alwaysdisabled' disabled="true">
							                                                                </div>
							                                                            </div>

							                                                            

							                                                            
							                                                        </div>
							                                                        
							                                                    </div>
							                                            </div>
							                                        </div>
							                                    </div>
							                            </div>

							                            <div class="panel-group classictheme margin-bottom-xs" id="waybillmovement-waybillpaneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybillmovement-waybillpaneldetails">
							                                                BOL Details
							                                        </div>
							                                        <div id="waybillmovement-waybillpaneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="waybilldetail-errordiv"></div>
									                                                </div>
									                                                <div class='col-md-12'>

											                                        	<div class='table-sm'>
											                                        		<table id='waybillmovement-waybilltbl'>
																								<tbody></tbody>
																							</table>
																		            		<!--<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='waybillmovement-waybilltbl' style='width:100%'>
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
									                                        
									                                     
							                                               
							                                                        
							                                                        
							                                            </div>
							                                        </div>
							                                    </div>


							                            </div>   
							                            <div class="panel-group classictheme margin-bottom-xs" id="waybillmovement-packagepaneldetails-wrapper" role="tablist" aria-multiselectable="true">

							                                    <div class="panel panel-default">
							                                        <div class="panel-heading" role="tab" data-toggle="collapse" href="#waybillmovement-packagepaneldetails">
							                                                Package Details
							                                        </div>
							                                        <div id="waybillmovement-packagepaneldetails" class="panel-collapse collapse in" role="tabpanel">
							                                            <div class="panel-body">
							                                            	<div class='row'>
								                                                	<div class="col-md-12">
									                                                    <div class="packagedetail-errordiv"></div>
									                                                </div>

									                                                <div class='col-md-12'>
									                                        
											                                        	<div class='table-sm'>
																		            		<table id='waybillmovement-packagecodetbl'>
																								<tbody></tbody>

																							</table>
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


<div class="modal fade" id="newwaybillmovementmodal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    New BOL Movement
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
	            				<label class='control-label col-md-3'>Location*</label>
	            				<div class='col-md-9'>
	            					<select class='form-control newwaybillmovementmodal-location locationdropdownselect'></select>
	            				</div>
	            			</div>
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Movement Type*</label>
	            				<div class='col-md-9'>
	            					<select class='form-control newwaybillmovementmodal-movementtype movementtypedropdownselect'></select>
	            				</div>
	            			</div>
							<div class="form-group">
								<label class='control-label col-md-3'>Shipment Type*</label>
								<div class='col-md-9'>
									<select class='form-control newwaybillmovementmodal-shipmenttype addshiptypedropdownselect'></select>
								</div>
							</div>
            				<div class="form-group">
            					<label class='control-label col-md-3'>Document Date</label>
            					<div class="col-md-9">
            					<input type='text' class='form-control newwaybillmovementmodal-documentdate datepicker'>
            					</div>
            				</div>
	            			
	            			<div class="form-group">
	            				<label class='control-label col-md-3'>Remarks</label>
	            				<div class='col-md-9'>
	            					<textarea class='form-control newwaybillmovementmodal-remarks' rows='6'></textarea>
	            				</div>
	            			</div>
	            	</div>
            	</div>
           	</div>
           	<div class='modal-footer'>
           		<div class="text-center">
					<button class='btn btn-blue2 mybtn' id='newwaybillmovementmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="wbmaddwaybillnumbermodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add Bill of Lading
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
           		<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				
            				<div class="form-group">
            					<label class='control-label'>BOL No.</label>
            					<input type='text' class='form-control wbmaddwaybillnumbermodal-waybillnumber'>
            				</div>
            				<div class="form-group">
	            					<div class='button-group-btn fluid active' id='wbmaddwaybillnumbermodal-addbtn'>
                                        <img src="../resources/img/add.png">&nbsp;&nbsp;Add BOL
                                    </div>
                            </div>
            		</div>
            		<br>
            	</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="wbmaddpackagecodemodal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Add Package Code
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
           		<div class='modal-errordiv'></div>
            	<div class='col-md-12'>
            		<div class='form-horizontal'>
            				
            				<div class="form-group">
            					<label class='control-label'>Package Code</label>
            					<input type='text' class='form-control wbmaddpackagecodemodal-code'>
            				</div>
            				<div class="form-group">
	            					<div class='button-group-btn fluid active' id='wbmaddpackagecodemodal-addbtn'>
                                        <img src="../resources/img/add.png">&nbsp;&nbsp;Add Package
                                    </div>
                            </div>
            		</div>
            		<br>
            	</div>
           	</div>
        </div>
    </div>
</div>


<div class="modal fade" id="waybillmovement-searchmodallookup">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    Search for BOL Movement
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
	            	<div class="form-horizontal">
	            		<div class='col-md-6'>
	            			<div class="form-group">
			            			<label class='control-label col-md-3'>Status</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input waybillmovementsearch-status select2' style='width:100%'>
			            				 	<option value=''>-</option>
			            				    <option value='LOGGED'>LOGGED</option>
			            				    <option value='POSTED'>POSTED</option>
			            				    <option value='VOID'>VOID</option>
			            				 </select>
			            			</div>
		            		</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-3'>Movement Type</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input waybillmovementsearch-movementtype movementtypedropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-3'>Location</label>
			            			<div class="col-md-9">
			            				 <select class='form-control form-input waybillmovementsearch-location locationdropdownselect' style='width:100%'></select>
			            			</div>
		            		</div>
		            		
		            		
			            </div>
		            	<div class='col-md-6'>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>BOL Number</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillmovementsearch-waybillnumber'>
			            			</div>
			            	</div>
		            		<div class="form-group">
			            			<label class='control-label col-md-4'>Document Date From</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillmovementsearch-dodatefrom datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<label class='control-label col-md-4'>Dcoument Date To</label>
			            			<div class="col-md-8">
			            				<input type='text' class='form-control waybillmovementsearch-docdateto datepicker'>
			            			</div>
			            	</div>
			            	<div class="form-group">
			            			<div class="col-md-offset-4 col-md-8">
			            				<div class='button-group-btn fluid active' id='waybillmovementsearch-searchbtn'>
	                                        <img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                                    </div>
			            			</div>
			            	</div>
		            	</div>			
	            	</div>
            		
            		<div class='col-md-12'>
            			<br>
		            	<table id='waybillmovementsearch-table'>
							<tbody></tbody>
						</table>
						<br>
					</div>
           	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="waybillmovement-waybilllookupmodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class='page-title'>
                    BOL Lookup
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body">
            	<table id='waybillmovement-waybilllookupmodal-tbl'>
            		<tbody></tbody> 
            	</table>
            	
           	</div>
        </div>
    </div>
</div>



<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabWBM = '#waybillmovement-menutabpane';
			var inputfieldsWBM = ".waybillmovement-inputfields";
			

			//$(tabWBM+' .modal-dialog').draggable();
			$(inputfieldsWBM+' input,'+inputfieldsWBM+' textarea,'+inputfieldsWBM+' select').attr('disabled','disabled');
        	$(inputfieldsWBM+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabWBM+" .select2").select2();
        	var datetoday = new Date();
        	$(tabWBM+' .datepicker').datepicker();
        	$(tabWBM+' .datetimepicker').datetimepicker();

       
       		var refWBM = <?php echo json_encode($refWBM); ?>;
	        if(refWBM!=''){
	            getWaybillMovementInformation(refWBM);
	            currentWaybillMovementTxn = refWBM;
	        }

	        $(tabWBM+" .locationdropdownselect").select2({
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

	    	$(tabWBM+" .movementtypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/movement-type.php",
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

			$(tabWBM+" .addshiptypedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/shipment-type.php",
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

	    	function wbmAddWaybillNumber(){
	    		$(tabWBM+' #wbmaddwaybillnumbermodal').modal('show');
	    	}


	    	function wbmDeleteWaybillNumber(){
	    		wbnumbersid = [];
	    		wbmnumber = $(tabWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');

	    		$(tabWBM+' .wbmwaybillrowcheckbox:checked').each(function(){
	    			wbnumbersid.push($(this).attr('rowid'));
	    		});
	    		if(wbnumbersid.length>0){
		    		$.post(server+'waybill-movement.php',{deleteWaybillNumber:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',wbnumbersid:wbnumbersid},function(data){

		    			if(data.trim()=='success'){
		    				$(tabWBM+' #waybillmovement-waybilltbl').flexOptions({
												url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+wbmnumber,
												sortname: 'waybill_number',
												sortorder: "asc"
							}).flexReload();

							$(tabWBM+' #waybillmovement-packagecodetbl').flexOptions({
															url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+wbmnumber,
															sortname: "waybill_number asc, package_code",
															sortorder: "asc"
							}).flexReload();
		    			}
		    			else{
		    				alert(data);
		    			}



		    		});
		    	}
	    	}


			$(tabWBM+' #waybillmovement-waybilltbl').flexigrid({
				url: 'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+refWBM,
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'BOL No.', name : 'waybill_number', width : 180, sortable : true, align: 'left'},
						{display: 'Packages', name : 'pckgs', width : 150, sortable : false, align: 'left'},
						{display: 'Origin', name : 'origin', width : 200, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 200, sortable : true, align: 'left'},
						{display: 'Destination Route', name : 'destinationroute', width : 200, sortable : true, align: 'left'}
						//,{display: 'Remarks', name : 'remarks', width : 250, sortable : false, align: 'left'}
						
				],
				buttons : [
						{separator: true},
						{name: 'Add', bclass: 'add wbmaddwaybillbtn hidden', onpress : wbmAddWaybillNumber},
						{name: 'Delete', bclass: 'delete wbmdeletewaybillbtn hidden', onpress : wbmDeleteWaybillNumber},
						{name: 'Search for BOL', bclass: 'search wbmwaybilllookupbtn hidden', onpress : ''}
				],
				searchitems : [
						{display: 'BOL No.', name : 'waybill_number', isdefault: true},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Destination Route', name : 'destinationroute'}
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
				singleSelect: true
			});


			function wbmAddPackageCode(){
	    		$(tabWBM+' #wbmaddpackagecodemodal').modal('show');
	    	}

	    	function wbmDeletePackageCode(){
	    		packagecodeids = [];
	    		wbmnumber = $(tabWBM+' #pgtxnwaybillmovement-id').attr('pgtxnwaybillmovement-number');

	    		$(tabWBM+' .wbmpackagecoderowcheckbox:checked').each(function(){
	    			packagecodeids.push($(this).attr('rowid'));
	    		});

	    		$.post(server+'waybill-movement.php',{deletePackageCodes:'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$',packagecodeids:packagecodeids,wbmnumber:wbmnumber},function(data){

	    			if(data.trim()=='success'){
	    				$(tabWBM+' #waybillmovement-waybilltbl').flexOptions({
											url:'loadables/ajax/transactions.waybill-movement-waybill.php?wbmnumber='+wbmnumber,
											sortname: 'waybill_number',
											sortorder: "asc"
						}).flexReload();

						$(tabWBM+' #waybillmovement-packagecodetbl').flexOptions({
														url:'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+wbmnumber,
														sortname: "waybill_number asc, package_code",
														sortorder: "asc"
						}).flexReload();
	    			}
	    			else{
	    				alert(data);
	    			}



	    		});
	    	}
			


			$(tabWBM+' #waybillmovement-packagecodetbl').flexigrid({
				url: 'loadables/ajax/transactions.waybill-movement-package-code.php?wbmnumber='+refWBM,
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'Package Code', name : 'package_code', width : 280, sortable : true, align: 'left'},
						{display: 'BOL No.', name : 'waybill_number', width : 200, sortable : true, align: 'left'}
				],
				buttons : [
						{separator: true},
						{name: 'Add', bclass: 'add wbmaddpackagebtn hidden', onpress : wbmAddPackageCode},
						{name: 'Delete', bclass: 'delete wbmdeletepackagebtn hidden', onpress : wbmDeletePackageCode}
				],
				searchitems : [
						{display: 'Code', name : 'package_code', isdefault: true},
						{display: 'BOL No.', name : 'waybill_number'}
				],
				sortname: "waybill_number, package_code",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 250,
				singleSelect: false
			});



			$(tabWBM+" #waybillmovementsearch-table").flexigrid({
				url: 'loadables/ajax/transactions.waybill-movement-search.php',
				dataType: 'json',
				colModel : [
						{display: 'BOL Movement No.', name : 'txn_waybill_movement.waybill_movement_number', width : 120, sortable : true, align: 'left'},
						{display: 'Status', name : 'txn_waybill_movement.status', width : 100, sortable : true, align: 'left'},
						{display: 'Movement Type', name : 'movement_type.description', width : 150, sortable : true, align: 'left'},
						{display: 'Location', name : 'location.description', width : 150, sortable : true, align: 'left'},
						{display: 'Waybill(s)', name : 'waybills', width : 300, sortable : false, align: 'left'},
						{display: 'Document Date', name : 'txn_waybill_movement.document_date', width : 150, sortable : true, align: 'left'},
						{display: 'Created by', name : 'user.first_name', width : 150, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'txn_waybill_movement.created_date', width : 150, sortable : true, align: 'left'},
						{display: 'System ID', name : 'txn_waybill_movement.id', width : 80, sortable : true, align: 'left'}
				],
				
				searchitems : [
						{display: 'BOL Movement No.', name : 'txn_waybill_movement.waybill_movement_number', isdefault: true},
						{display: 'Status', name : 'txn_waybill_movement.status'},
						{display: 'Movement Type', name : 'movement_type.description'},
						{display: 'Location', name : 'location.description'}
				],
				sortname: "txn_waybill_movement.waybill_movement_number",
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


			$(tabWBM+' #waybillmovement-waybilllookupmodal-tbl').flexigrid({
				url: 'loadables/ajax/transactions.waybill-movement-waybill-lookup.php',
				dataType: 'json',
				colModel : [
						{display: '', name : 'action', width : 40, sortable : false, align: 'center'},
						{display: 'BOL No.', name : 'waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'Date', name : 'document_date', width : 80, sortable : true, align: 'left'},
						{display: 'Origin', name : 'origin', width : 180, sortable : true, align: 'left'},
						{display: 'Destination', name : 'destination', width : 180, sortable : true, align: 'left'},
						{display: 'Shipper', name : 'shipper_account_name', width : 130, sortable : true, align: 'left'},
						{display: 'Consignee', name : 'consignee_account_name', width : 200, sortable : true, align: 'left'}
						

				],
				buttons : [
						{separator: true},
						{name: 'Add', bclass: 'add wbmwaybilllookup-addbtn'},
						{name: 'Select All', bclass: 'select1 wbmwaybilllookup-selectallbtn',onpress : wbmSelectAllWaybillNumber},
						{name: 'Deselect All', bclass: 'deselect1 wbmwaybilllookup-deselectallbtn',onpress : wbmDeselectAllWaybillNumber}
				],
				searchitems : [
						
						{display: 'BOL No.', name : 'waybill_number', isdefault: true},
						{display: 'Date', name : 'document_date'},
						{display: 'Origin', name : 'origin'},
						{display: 'Destination', name : 'destination'},
						{display: 'Shipper', name : 'shipper_account_name'},
						{display: 'Consignee', name : 'consignee_account_name'}

				],
				sortname: "waybill_number",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 50, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 550,
				singleSelect: false,
				disableSelect: true
			});

			function wbmSelectAllWaybillNumber(){
	    		$(tabWBM+' .wbmwaybilllookup-checkbox').prop('checked',true);
	    	}

	    	function wbmDeselectAllWaybillNumber(){
	    		$(tabWBM+' .wbmwaybilllookup-checkbox').prop('checked',false);
	    	}




        
			
    		userAccess();


			

	});
	



</script>