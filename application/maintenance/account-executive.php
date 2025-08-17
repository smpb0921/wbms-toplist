<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Account Executive
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='accountexecutivetable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addaccountexecutivemodal">
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
								<label class='control-label'>Name*</label>
								<input type='text' class='form-input form-control name'>
							</div>
							<div class="form-group">
								<label class='control-label'>Email Address*</label>
								<input type='text' class='form-input form-control email'>
							</div>	
							<div class="form-group">
								<label class='control-label'>Mobile*</label>
								<input type='text' class='form-input form-control mobile'>
							</div>	
							<div class="form-group">
								<label class='control-label'>Username*</label>
								<input type='text' class='form-input form-control username'>
							</div>
							<div class="form-group">
								<label class='control-label'>Password*</label>
								<div class="input-group">
									<input type='text' class='form-control form-input password'>
									<span class="input-group-addon inputgroupbtn">
										<i class="fa fa-refresh generaterandompasswordbtn" title='Generate Password'></i>
									</span>
								</div>
							</div>
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn accountexecutivemodal-savebtn' id='addaccountexecutivemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editaccountexecutivemodal">
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
							<input type='hidden' class='accountexecutivemodalid'>
							<div class="form-group">
								<label class='control-label'>Code*</label>
								<input type='text' class='form-input form-control code'>
							</div>	
							<div class="form-group">
								<label class='control-label'>Name*</label>
								<input type='text' class='form-input form-control name'>
							</div>
							<div class="form-group">
								<label class='control-label'>Email Address*</label>
								<input type='text' class='form-input form-control email'>
							</div>	
							<div class="form-group">
								<label class='control-label'>Mobile*</label>
								<input type='text' class='form-input form-control mobile'>
							</div>	
							<div class="form-group">
								<label class='control-label'>Username*</label>
								<input type='text' class='form-input form-control username'>
							</div>
							<div class="form-group">
								<label class='control-label'>Password*</label>
								<div class="input-group">
									<input type='text' class='form-control form-input password'>
									<span class="input-group-addon inputgroupbtn">
										<i class="fa fa-refresh generaterandompasswordbtn" title='Generate Password'></i>
									</span>
								</div>
							</div>				
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn accountexecutivemodal-savebtn' id='editaccountexecutivemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		$('.modal-dialog').draggable();

		

		$("#accountexecutivetable").flexigrid({
				url: 'loadables/ajax/maintenance.account-executive.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 80, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 80, sortable : true, align: 'left'},
						{display: 'Code', name : 'code', width : 120, sortable : true, align: 'left'},
						{display: 'Name', name : 'name', width : 250, sortable : true, align: 'left'},
						{display: 'Email', name : 'email_address', width : 200, sortable : true, align: 'left'},
						{display: 'Username', name : 'username', width : 200, sortable : true, align: 'left'},
						{display: 'Created by', name : 'created_by', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'created_date', width : 125, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 125, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addaccountexecutivebtn', onpress : addAccountExecutive},
						{separator: true},
						{name: 'Delete', bclass: 'delete deleteaccountexecutivebtn', onpress : deleteAccountExecutive}
				],
				searchitems : [
						{display: 'Code', name : 'code', isdefault: true},
						{display: 'Name', name : 'name'},
						{display: 'Username', name : 'username'},
						{display: 'Email', name : 'email_address'}
				],
				sortname: "name",
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

		function addAccountExecutive(){
				$('#addaccountexecutivemodal').modal('show');
		}

		function deleteAccountExecutive(){

		
			if(parseInt($('#accountexecutivetable .trSelected').length)>0){
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
							$('#accountexecutivetable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/account-executive.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#accountexecutivetable').flexOptions({
											url:'loadables/ajax/maintenance.account-executive.php',
											sortname: "name",
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