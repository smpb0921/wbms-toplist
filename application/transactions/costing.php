<?php
     include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
    $refCOSTNG = isset($_GET['reference'])?escapeString($_GET['reference']):'';
?>
<div class='header-page' >
	<div class='header-page-inner'>
		Costing
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent">
        <div class="panel panel-primary mypanel">
            <div class="panel-body">
                <!-- CONTENT -->
                <div class='transaction-wrapper costing-content'>

                		<div class='col-md-12 no-padding margin-bottom-sm topbuttonsdiv'>
                            <div class='padded-with-border-engraved topbuttonswrapper'>
                                <div class='button-group'>
                                	<div class='button-group-btn active' title='costing Items' id='costing-trans-addbtn' href='#addcostingmodal' data-toggle='modal'><img src='../resources/img/add.png'></div>
                                    

                                </div>
                            </div>
                        </div>
                        
                        	<fieldset>
                        		<legend>Search Filters</legend>
                        	
	                        	<div class="form-horizontal">
	                        		<div class='col-md-6'>
	                        			<!--<div class="form-group">
	                        				<label class='control-label col-md-3'>Warehouse</label>
	                        				<div class="col-md-9">
	                        					<select class='form-control form-input costing-warehouse warehousedropdownselect costingfilterslct'>
	                        						
	                        					</select>
	                        				</div>
	                        			</div>-->
	                        			
	                        			<div class="form-group">
	                        				<label class='control-label col-md-3'>Type</label>
	                        				<div class="col-md-9">
	                        					<select class='form-control form-input costing-typeofaccount typeofaccountdropdownselect costingfilterslct'>
	                        						
	                        					</select>
	                        				</div>
	                        			</div>
	                        			
	                        			<div class="form-group">
	                        				<label class='control-label col-md-3'>Account</label>
	                        				<div class="col-md-9">
	                        					<select class='form-control form-input costing-chartofaccounts chartofaccountdropdownselect costingfilterslct'>
	                        						
	                        					</select>
	                        				</div>
	                        			</div>
										<div class="form-group">
	                        				<label class='control-label col-md-3'>Payee</label>
	                        				<div class="col-md-9">
	                        					<select class='form-control form-input costing-payee payeedropdownselect costingfilterslct'>
	                        						
	                        					</select>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-3'>Reference</label>
	                        				<div class="col-md-9">
	                        					<input type='text' class='form-control costing-reference costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-3'>PRF Number</label>
	                        				<div class="col-md-9">
	                        					<input type='text' class='form-control costing-prfnumber costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-3'>BOL Number(s)</label>
	                        				<div class="col-md-9">
	                        					<input type='text' class='form-control costing-waybillnumber costingfilterfld'>
	                        				</div>
	                        			</div>

	                        			
	                        			

	                        			
	                        			
	                        			
	                        		</div>
	                        		<div class='col-md-6'>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-4'>Costing Date From</label>
	                        				<div class="col-md-8">
	                        					<input type='text' class='form-control costing-datefrom datepicker costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-4'>Costing Date To</label>
	                        				<div class="col-md-8">
	                        					<input type='text' class='form-control costing-dateto datepicker costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-4'>Created Date From</label>
	                        				<div class="col-md-8">
	                        					<input type='text' class='form-control costing-createddatefrom datepicker costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<label class='control-label col-md-4'>Created Date To</label>
	                        				<div class="col-md-8">
	                        					<input type='text' class='form-control costing-createddateto datepicker costingfilterfld'>
	                        				</div>
	                        			</div>
	                        			<div class="form-group">
	                        				<div class="col-md-offset-4 col-md-5">
	                        					<div class='button-group-btn fluid active' id='costing-searchbtn'>
	                        						<img src="../resources/img/search.png">&nbsp;&nbsp;Search
	                        					</div>
	                        				</div>
	                        				<div class="col-md-3">
	                        					<div class='button-group-btn fluid active' id='costing-clearfilterbtn'>
	                        						<img src="../resources/img/clear.png">&nbsp;&nbsp;Clear
	                        					</div>
	                        				</div>
	                        			</div>
	                        		</div>			
	                        	</div>
                        	</fieldset>
                        

                        <div class='row'>
                        	<div class='col-md-12'>

                        		<div class='table-sm'>
                        			<table id='costing-detailstbl'>
                        				<tbody></tbody>
                        			</table>
                        		</div>

                        	</div>
                        </div>



                       


                        

                        

                    
                </div>
                <!-- CONTENT-END -->
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addcostingmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<div class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>

							<div class="form-group">
								<label class='control-label col-md-3'>Date*</label>
								<div class="col-md-3">
									<input type='text' class='form-control addeditcostingmodal-date costinginputfld datepicker' value="<?php echo date('m/d/Y'); ?>">

								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Type of Account*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-typeofaccount typeofaccountdropdownselect costinginputslct'></select>
								</div>
								
							</div>	
							<div class="form-group">
								<label class='control-label col-lg-3'>Account*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-chartofaccounts chartofaccountdropdownselect costinginputslct'></select>
								</div>
								
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Type</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-accountproducttype costinginputfld' disabled>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Payee*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-payee payeedropdownselect costinginputslct'></select>
								</div>
							</div>	
							<div class="form-group">
								<label class='control-label col-lg-3'>Address</label>
								<div class='col-lg-8'>
									<textarea class='form-input form-control addeditcostingmodal-payeeaddress costinginputfld' rows='4' disabled></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Gross Amount*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-amount costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vatable Amount*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-vatableamount costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vat Flag</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-vatflag costinginputslct'>
										<option value='1'>Yes</option>
										<option value='0'>No</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vat*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-vat costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Reference</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-reference costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>PRF Number</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-prfnumber costinginputfld'>
								</div>
							</div>

						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn addeditcostingmodal-savebtn' id='addcostingmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editcostingmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<div class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<input type="hidden" id="addeditcostingmodal-ctsngID">

							<div class="form-group">
								<label class='control-label col-md-3'>Date*</label>
								<div class="col-md-3">
									<input type='text' class='form-control addeditcostingmodal-date costinginputfld datepicker' value="<?php echo date('m/d/Y'); ?>">

								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Type of Account*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-typeofaccount costinginputslct'></select>
								</div>
								
							</div>	
							<div class="form-group">
								<label class='control-label col-lg-3'>Account*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-chartofaccounts costinginputslct'></select>
								</div>
								
							</div>	
							<div class="form-group">
								<label class='control-label col-lg-3'>Type</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-accountproducttype costinginputfld' disabled>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Payee*</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-payee payeedropdownselect costinginputslct'></select>
								</div>
							</div>	
							<div class="form-group">
								<label class='control-label col-lg-3'>Address</label>
								<div class='col-lg-8'>
									<textarea class='form-input form-control addeditcostingmodal-payeeaddress costinginputfld' rows='4' disabled></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Gross Amount*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-amount costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vatable Amount*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-vatableamount costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vat Flag</label>
								<div class='col-lg-8'>
									<select class='form-input form-control addeditcostingmodal-vatflag costinginputslct'>
										<option value='1'>Yes</option>
										<option value='0'>No</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Vat*</label>
								<div class='col-lg-8'>
									<input type='number' class='form-input form-control addeditcostingmodal-vat costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>Reference</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-reference costinginputfld'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-lg-3'>PRF Number</label>
								<div class='col-lg-8'>
									<input type='text' class='form-input form-control addeditcostingmodal-prfnumber costinginputfld'>
								</div>
							</div>

						</div>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn addeditcostingmodal-savebtn' id='editcostingmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>



<div class="modal fade" id="viewcostingrcvmodal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Waybill Transactions
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
				<input type='hidden' class='form-input form-control viewcostingrcvmodal-costingID' disabled>
				<div class='col-lg-12'>
					<div class="form-horizontal">
						<div class="form-group">
							<label class='control-label col-lg-2'>Type of Account</label>
							<div class='col-lg-4'>
								<input type='text' class='form-input form-control viewcostingrcvmodal-typeofaccount' disabled>
							</div>

							<label class='control-label col-lg-1'>Date</label>
							<div class='col-lg-4'>
								<input type='text' class='form-input form-control viewcostingrcvmodal-date' disabled>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-2'>Account</label>
							<div class='col-lg-4'>
								<input type='text' class='form-input form-control viewcostingrcvmodal-chartofaccounts' disabled>
							</div>

							<label class='control-label col-lg-1'>Reference</label>
							<div class='col-lg-4'>
								<input type='text' class='form-input form-control viewcostingrcvmodal-reference' disabled>
							</div>
						</div>
						<div class="form-group">
							<label class='control-label col-lg-2'>Amount</label>
							<div class='col-lg-4'>
								<input type='text' class='form-input form-control viewcostingrcvmodal-amount' disabled>
							</div>
						</div>
						<br>
					</div>
				</div>

				<div class='viewcostingrcvmodal-addrcvwrapper'>

					<fieldset>
						
							<legend>Add Stock Receipt</legend>
							<div class='table-sm'>
								<table id='viewcostingrcvmodal-addrcvtbl'>
									<tbody></tbody>
								</table>
							</div>
					</fieldset>
				</div>

				<div class='viewcostingrcvmodal-existingrcvwrapper'>
					<fieldset>
						
							<legend>Added Transactions</legend>
							<div class='table-sm'>
								<table id='viewcostingrcvmodal-existingrcvtbl'>
									<tbody></tbody>
								</table>
							</div>
					</fieldset>
				</div>

			</div>
		</div>
	</div>
</div>





<script type="text/javascript">
	/**** INITIALIZED *******/
	$(document).ready(function(){
			var tabCOSTNG = '#costing-menutabpane';
			var inputfieldsCOSTNG = ".costing-inputfields";
			

        	$(inputfieldsCOSTNG+' .transactionnumber').removeAttr('disabled').focus();
        	$(tabCOSTNG+" .select2").select2({
        		width:'100%'
        	});
        	var datetoday = new Date();
        	$(tabCOSTNG+' .datepicker').datepicker();
        	$(tabCOSTNG+' .datetimepicker').datetimepicker();

       		
       		
	    	$(tabCOSTNG+" .typeofaccountdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/expense-type.php",
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

	    	$(tabCOSTNG+" .chartofaccountdropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/chart-of-accounts.php",
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

			$(tabCOSTNG+" .payeedropdownselect").select2({
	            ajax: {
	                    url: "loadables/dropdown/payee.php",
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

	    	
	    	

	    



			$(tabCOSTNG+' #costing-detailstbl').flexigrid({
				url: 'loadables/ajax/transactions.costing-details.php',
				dataType: 'json',
				colModel : [
				        {display: "<input type='checkbox' class='flexigrid-checkallbox'>", name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'Action', name : 'action', width : 80, sortable : false, align: 'center'},
						{display: 'SystemID', name : 'costing.id', width : 80, hide:false, sortable : true, align: 'left'},
						{display: 'Date', name : 'costing.date', width : 80, sortable : true, align: 'left'},
						{display: 'Type of Account', name : 'expense_type.description', width : 120, sortable : true, align: 'left'},
						{display: 'Account', name : 'chart_of_accounts.description', width : 120, sortable : true, align: 'left'},
						{display: 'Payee Name', name : 'payee.payee_name', width : 150, sortable : true, align: 'left'},
						{display: 'Payee Address', name : 'costing.payee_address', width : 200, sortable : true, align: 'left',hide:true},
						{display: 'TIN', name : 'payee.tin', width : 120, sortable : true, align: 'left'},
						{display: 'Vat Flag', name : 'vatflag', width : 80, sortable : true, align: 'left'},
						{display: 'Amount', name : 'costing.amount', width : 120, sortable : true, align: 'right'},
						{display: 'Vatable Amount', name : 'costing.vatable_amount', width : 120, sortable : true, align: 'right'},
						{display: 'Vat Amount', name : 'costing.vat_amount', width : 120, sortable : true, align: 'right'},
						{display: 'Reference', name : 'costing.reference', width : 120, sortable : true, align: 'left'},
						{display: 'PRF Number', name : 'costing.prf_number', width : 120, sortable : true, align: 'left'},
						{display: 'No. of Transactions', name : 'waybillcount', width : 160, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'costing.created_date', width : 145, hide:false, sortable : true, align: 'left'},
						{display: 'Created By', name : 'createdby', width : 150, hide:false, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'costing.updated_date', width : 145, hide:false, sortable : true, align: 'left'},
						{display: 'Updated By', name : 'updatedby', width : 150, hide:false, sortable : true, align: 'left'}
						

				],
				buttons : [
						{separator: true},
						{name: 'Delete', bclass: 'delete deletecostingbtn', onpress : ''},
						{name: 'Download', bclass: 'download downloadcostinghistorybtn', onpress : ''}
						
				],
				searchitems : [
						{display: 'Type of Account', name : 'expense_type.description', isdefault: true},
						{display: 'Chart of Accounts', name : 'chart_of_accounts.description'},
						{display: 'Reference', name : 'costing.reference'}
						
				],
				sortname: "costing.id",
				sortorder: "desc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 10, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 300,
				singleSelect: false,
				disableSelect: true
			});


			$(tabCOSTNG+' #viewcostingrcvmodal-addrcvtbl').flexigrid({
				url: 'loadables/ajax/transactions.costing-add-rcvcosting-details.php',
				dataType: 'json',
				colModel : [
						{display: 'Action', name : 'action', width : 80, sortable : false, align: 'center'},
						{display: 'SystemID', name : 'txn_waybill.id', width : 80, hide:false, sortable : true, align: 'left'},
						{display: 'BOL Number', name : 'txn_waybill.waybill_number', width : 180, sortable : true, align: 'left'},
						{display: 'MAWBL', name : 'txn_waybill.mawbl_bl', width : 180, sortable : true, align: 'left'}

				],
				buttons : [
						{separator: true}

						
				],
				searchitems : [
						{display: 'BOL Number', name : 'txn_waybill.waybill_number', isdefault: true},
						{display: 'MAWBL', name : 'txn_waybill.mawbl_bl'}
				],
				sortname: "txn_waybill.waybill_number",
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

			$(tabCOSTNG+' #viewcostingrcvmodal-existingrcvtbl').flexigrid({
				url: 'loadables/ajax/transactions.costing-view-rcvcosting-details.php',
				dataType: 'json',
				colModel : [
						{display: "<input type='checkbox' class='flexigrid-checkallbox'>", name : 'checkbox', width : 50, sortable : false, align: 'center'},
						{display: 'SystemID', name : 'costing_waybill.id', width : 80, hide:true, sortable : true, align: 'left'},
						{display: 'Waybill Number', name : 'txn_waybill.waybill_number', width : 150, sortable : true, align: 'left'},
						{display: 'Actual Weight', name : 'actualweight', width : 180, sortable : true, align: 'right'},
						{display: 'Vol. Weight', name : 'volweight', width : 180, sortable : true, align: 'right'},
						{display: 'Distributed Amount', name : 'costing_waybill.amount', width : 180, sortable : true, align: 'right'},
						{display: 'Created Date', name : 'costing.created_date', width : 145, hide:false, sortable : true, align: 'left'},
						{display: 'Created By', name : 'createdby', width : 150, hide:false, sortable : true, align: 'left'}
						

				],
				buttons : [

						{separator: true},
						{name: 'Delete', bclass: 'delete deletecostingrcvbtn', onpress : ''},

						
				],
				searchitems : [
						{display: 'Waybill Number', name : 'txn_waybill.waybill_number', isdefault: true}
						
				],
				sortname: "txn_waybill.waybill_number",
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

			


			

			






			
			
    		userAccess();


			

	});
	



</script>