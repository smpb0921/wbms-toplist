<?php
    include('../../config/connection.php');
    include("../../config/checkurlaccess.php");
	include("../../config/checklogin-page.php");
    include("../../config/functions.php");
?>

<div class='header-page' >
	<div class='header-page-inner'>
		Department
		<div class="button-group">
			<button class="btn btn-sm btn-blue2 mybtn border-right" id='adddepartmentbtn' data-toggle='modal' href='#adddepartmentmodal'>
				<i class='fa fa-plus fa-lg fa-space'></i>Add
			</button>
			<button class="btn btn-sm btn-blue2 mybtn border-right" id='deletedepartmentbtn'>
				<i class='fa fa-remove fa-lg fa-space'></i>Delete
			</button>
		</div>
	</div>

</div>
<div class="container-fluid">
    <div class="pageContent">
    	<div class="panel panel-primary mypanel">
    			<div class="panel-body">
	    			<table class='table table-condensed table-hover pointer table-striped table-bordered text-nowrap mytable gray-template table-font-sm no-side-borders' id='departmenttable'>
	    				<thead>
	    					<tr>
	    						<th class='column-nosort column-checkbox text-center'></th>
	    						<th class='column-nosort column-width-sm'>ACTIONS</th>
	    						<th>DEPARTMENT NAME</th>
	    					</tr>
	    				</thead>
	    				<tbody>
	    					<?php
													$hidebtn = (userAccess(USERID,'.editdepartmentbtn')==false)?'':'hidden';
													$query="select * from department";
													$rs = query($query);
													$rscount = getNumRows($rs);
													while($obj=fetch($rs)){
														$id = $obj->id;
														$dept = $obj->department_name;					
														echo "	<tr class='tablerow'>
																	<td class='text-center'>
																		<input type='checkbox' class='itemcheckbox' rowid='$id'>
																	</td>
																	<td class='action text-center'>
																		<img id='$id' 
																			 dept='$dept'
																			 class='nopadding editdepartmentbtn $hidebtn' 
																			 src='../resources/img/File_List.png' 
																			 title='Edit Warehouse' 
																			 data-toggle='modal' 
																			 href='#editdepartmentmodal'>
																	</td>
																	<td>$dept</td>
																</tr>
														";
													}
					?>
	    				</tbody>
	    			</table>
	    			
    			</div>
    	</div>

	</div>
</div>


<div class="modal fade" id="adddepartmentmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					New Department
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							To add a department, please provide department name.
						</div>
					</div>
					<div class="col-md-8">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="col-md-12">
									<div class="errordiv"></div>
								</div>
							</div>

							<div class="form-group">
								<label class='control-label col-md-4'>Department Name</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control name'>
								</div>
							</div>			
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn department-modal-savebtn' id='departmentadd-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='departmentadd-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>

<div class="modal fade" id="editdepartmentmodal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<div class='page-title'>
					Edit Department
					<button class="close" data-dismiss="modal">&times;</button>
				</div>
			</div>
			<div class="modal-body">
					<div class="col-md-4">
						<div class='modal-body-info'>
							To edit a department, please update department name.
						</div>
					</div>
					<div class="col-md-8">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="col-md-12">
									<div class="errordiv"></div>
								</div>
							</div>
							<input type='hidden' class='departmentid'>
							<div class="form-group">
								<label class='control-label col-md-4'>Department Name</label>
								<div class="col-md-8">
									<input type='text' class='form-input form-control name'>
								</div>
							</div>			
						</form>
					</div>
			</div>
			<div class="modal-footer">
				<div class="text-center">
					<button class='btn btn-blue2 mybtn department-modal-savebtn' id='departmentedit-savebtn'>Save</button>
					<button class='btn btn-blue2 mybtn modal-cancelbtn' id='departmentedit-cancelbtn'>Cancel</button>
				</div>
			</div>
		</div>
	</div>  
</div>


<script type="text/javascript">
	$(document).ready(function(){

		$('.modal-dialog').draggable();

		
		
			userAccess();
			////////////////// DATATABLES //////////////////////////////
			$('#departmenttable').DataTable({

		 			aaSorting: [[ 2, "desc" ]], //initially, table is sorted by second column desc
				    columnDefs: [
				      				 {
								        targets: "column-nosort", //class of columns you dont want to be sortable
								        orderable: false,
								        //visible: false,
		               					//searchable: true

								     }
								 ],
					pagingType: "full" //numbers, simple, simple_numbers, full, full_numbers
			});


		
	});
	
</script>