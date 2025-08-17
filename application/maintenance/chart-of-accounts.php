<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Chart of Accounts
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='chartofaccountstable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addchartofaccountsmodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>

							<div class="form-group">
								<label class='control-label'>Code*</label>
								<input type='text' class='form-input form-control code'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Description*</label>
								<input type='text' class='form-input form-control description'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Type of Account*</label>
								<select class='form-input form-control typeofaccount expensetypedropdownselect'></select>
							</div>
							<div class="form-group">
								<label class='control-label'>Type*</label>
								<select class='form-input form-control producttype select2'>
									<option value=''></option>
									<option value='GOODS'>Goods</option>
									<option value='SERVICES'>Services</option>
								</select>
							</div>	
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn chartofaccountsmodal-savebtn' id='addchartofaccountsmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editchartofaccountsmodal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-12">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="errordiv"></div>
							</div>
							<input type='hidden' class='chartofaccountsmodalid'>
							<div class="form-group">
								<label class='control-label'>Code*</label>
								<input type='text' class='form-input form-control code'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Description*</label>
								<input type='text' class='form-input form-control description'>
								
							</div>
							<div class="form-group">
								<label class='control-label'>Type of Account*</label>
								<select class='form-input form-control typeofaccount expensetypedropdownselect'></select>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Type*</label>
								<select class='form-input form-control producttype select2'>
									<option value=''></option>
									<option value='GOODS'>Goods</option>
									<option value='SERVICES'>Services</option>
								</select>
							</div>			
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn chartofaccountsmodal-savebtn' id='editchartofaccountsmodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){
		var tabChartofAcct = '#chartofaccounts-menutabpane';
		$(tabChartofAcct+' .modal-dialog').draggable();
		$(tabChartofAcct+' .select2').select2({width:'100%'});

		$(tabChartofAcct+" .expensetypedropdownselect").select2({
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
		                width:'100%'
		});

		

		$("#chartofaccountstable").flexigrid({
				url: 'loadables/ajax/maintenance.chart-of-accounts.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'chart_of_accounts.id', width : 100, sortable : true, align: 'left'},
						{display: 'Code', name : 'chart_of_accounts.code', width : 250, sortable : true, align: 'left'},
						{display: 'Description', name : 'chart_of_accounts.description', width : 250, sortable : true, align: 'left'},
						{display: 'Type of Account', name : 'expensetype', width : 250, sortable : true, align: 'left'},
						{display: 'Type', name : 'producttype', width : 180, sortable : true, align: 'left'},
						{display: 'Created by', name : 'createdby', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'chart_of_accounts.created_date', width : 200, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updatedby', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'chart_of_accounts.updated_date', width : 200, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addchartofaccountsbtn', onpress : addchartofaccounts},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletechartofaccountsbtn', onpress : deletechartofaccounts}
				],
				searchitems : [
						{display: 'Code', name : 'chart_of_accounts.code', isdefault: true},
						{display: 'Description', name : 'chart_of_accounts.description'},
				],
				sortname: "chart_of_accounts.description",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				//width: 800,
				height: 500,
				singleSelect: false
		});

		function addchartofaccounts(){
				$('#addchartofaccountsmodal').modal('show');
		}

		function deletechartofaccounts(){

		
			if(parseInt($('#chartofaccountstable .trSelected').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$('#chartofaccountstable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/chart-of-accounts.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#chartofaccountstable').flexOptions({
											url:'loadables/ajax/maintenance.chart-of-accounts.php',
											sortname: "description",
											sortorder: "asc"
									}).flexReload(); 
								}
								else{
									alert(response);
								}

							});
					},
					cancel:function(){

					}
				});
			}
			else{
				say("Please select row(s) to delete");
			}

				//$("#mytable").flexAddData(eval(array));
				//$('#mytable').flexOptions({url:'staff.php?name=user%200'}).flexReload(); 
				
		}
		
		userAccess();
			

		
	});
	
</script>