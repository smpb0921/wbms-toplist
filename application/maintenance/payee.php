<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Payee
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    	<table id='payeetable'>
			<tbody></tbody>

		</table>


	</div>
</div>


<div class="modal fade" id="addpayeemodal">
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
								<label class='control-label'>Payee Name*</label>
								<input type='text' class='form-input form-control payeename'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Address*</label>
								<textarea class='form-input form-control payeeaddress' rows='4'></textarea>
								
							</div>	
                            <div class="form-group">
								<label class='control-label'>Tin*</label>
								<input type='text' class='form-input form-control payeetin'>
								
							</div>	
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn payeemodal-savebtn' id='addpayeemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editpayeemodal">
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
							<input type='hidden' class='payeemodalid'>
							<div class="form-group">
								<label class='control-label'>Payee Name*</label>
								<input type='text' class='form-input form-control payeename'>
								
							</div>	
							<div class="form-group">
								<label class='control-label'>Address*</label>
								<textarea class='form-input form-control payeeaddress' rows='4'></textarea>
								
							</div>	
                            <div class="form-group">
								<label class='control-label'>Tin*</label>
								<input type='text' class='form-input form-control payeetin'>
								
							</div>				
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn payeemodal-savebtn' id='editpayeemodal-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		$('.modal-dialog').draggable();

		

		$("#payeetable").flexigrid({
				url: 'loadables/ajax/maintenance.payee.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'payee.id', width : 100, sortable : true, align: 'left'},
						{display: 'Name', name : 'payee.payee_name', width : 250, sortable : true, align: 'left'},
						{display: 'Address', name : 'payee.address', width : 250, sortable : true, align: 'left'},
                        {display: 'TIN', name : 'payee.tin', width : 250, sortable : true, align: 'left'},
						{display: 'Created by', name : 'createdby', width : 200, sortable : true, align: 'left'},
						{display: 'Created Date', name : 'payee.created_date', width : 200, sortable : true, align: 'left'},
						{display: 'Updated by', name : 'updatedby', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'payee.updated_date', width : 200, sortable : true, align: 'left'}
				],
				buttons : [
						{name: 'Add', bclass: 'add addpayeebtn', onpress : addpayee},
						{separator: true},
						{name: 'Delete', bclass: 'delete deletepayeebtn', onpress : deletepayee}
				],
				searchitems : [
						{display: 'Name', name : 'payee.payee_name', isdefault: true},
						{display: 'Tin', name : 'payee.tin'},
                        {display: 'Address', name : 'payee.address'},
				],
				sortname: "payee.payee_name",
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

		function addpayee(){
				$('#addpayeemodal').modal('show');
		}

		function deletepayee(){

		
			if(parseInt($('#payeetable .trSelected').length)>0){
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
							$('#payeetable .trSelected').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/payee.php',{deleteSelectedRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#payeetable').flexOptions({
											url:'loadables/ajax/maintenance.payee.php',
											sortname: "payee.payee_name",
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