<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Transaction Type
	</div>
</div>
<div class="container-fluid">
    <div class="pageContent noborder">

    				<table id='transactiontypetable'>
			    			
			    				<tbody>
			    					
			    				</tbody>
			    	</table>



	

	</div>
</div>

<div class="modal fade" id="edittransactiontypemodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Transaction Type
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							<span class='title'>Transaction type</span><br>
							To edit an transaction type, please update the following information.
						</div>
					</div>
					<div class="col-md-8">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="col-md-12">
									<div class="errordiv"></div>
								</div>
							</div>
							<input type='hidden' class='transactiontypeid'>

							<div class="form-group">
								<label class='control-label col-md-4'>Code*</label>
								<div class="col-md-8">
									<input type='text' name='code' class='form-input form-control code'>
								</div>
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Description*</label>
								<div class="col-md-8">
									<input type='text' name='description' class='form-input form-control desc'>
								</div>
							</div>	
							<div class="form-group">
								<label class='control-label col-md-4'>Prefix</label>
								<div class="col-md-8">
									<input type='text' name='prefix' class='form-input form-control prefix'>
								</div>
								
							</div>
							<div class="form-group">
								<label class='control-label col-md-4'>Next Number Series*</label>
								<div class="col-md-8">
									<input type='number' name='numberseries' class='form-control numseries'>
								</div>
								
							</div>				
						</form>
						
						
					</div>
			</div>
			<div class="modal-footer">
								<div class="text-center">
									<button class='btn mybtn btn-blue2' id='transactiontypeedit-savebtn'>Save</button>
									<button class='btn mybtn btn-blue2 modal-cancelbtn' id='transactiontypeedit-cancelbtn'>Cancel</button>
								</div>
			</div>
		</div>
	</div>  
</div>

<script type="text/javascript">
	//sliceTable(rowperpage,'#transactiontypetable','#transactiontypepagination');
	userAccess();
	$('.modal-dialog').draggable();
	$("#transactiontypetable").flexigrid({
				url: 'loadables/ajax/system.transaction-type.php',
				dataType: 'json',
				colModel : [
						{display: 'Actions', name : 'action', width : 100, sortable : false, align: 'center'},
						{display: 'System ID', name : 'id', width : 100, sortable : true, align: 'left'},
						{display: 'Code', name : 'code', width : 150, sortable : true, align: 'left'},
						{display: 'Description', name : 'description', width : 350, sortable : true, align: 'left'},
						{display: 'Prefix', name : 'prefix', width : 100, sortable : true, align: 'left'},
						{display: 'Next Number Series', name : 'next_number_series', width : 150, sortable : true, align: 'left'},
						{display: 'Updated By', name : 'updated_by', width : 200, sortable : true, align: 'left'},
						{display: 'Updated Date', name : 'updated_date', width : 150, sortable : false, align: 'left'}
				],
				buttons : [
						//{name: 'Add', bclass: 'add', onpress : addUserGroup},
						{separator: true},
						//{name: 'Delete', bclass: 'delete', onpress : deleteUserGroup}
				],
				searchitems : [
						{display: 'Code', name : 'code', isdefault: true},
						{display: 'Description', name : 'description'},
						{display: 'Prefix', name : 'prefix'},
						{display: 'Updated by', name : 'updated_by'}
				],
				sortname: "id",
				sortorder: "asc",
				usepager: true,
				title: "",
				useRp: true,
				rp: 15, //rows per page
				showTableToggleBtn: false,
				resizable: false,
				height: 500,
				singleSelect: false
	});
</script>


	



