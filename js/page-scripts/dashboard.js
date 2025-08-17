var dashpane = "#dashtabpane";

$(document).off('click',dashpane+' #warehouseutilization-loadbtn').on('click',dashpane+' #warehouseutilization-loadbtn',function(e){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		whse = $(modal+' .warehouseutilization-selectwhse').val(),
		button = $(this);
		button.attr('disabled',true);

	if(whse=='NULL'||whse==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a warehouse.</div></div>");
		button.removeAttr('disabled');
	}
	else {
		$(dashpane+' #dashboard-warehouseutilizationtbl tbody').load("Loadables/dashboard/warehouse-utilization-tblrows.php?whse="+whse);
		$("#dashboard-warehouseutilizationtbl").dataTable().fnDestroy();
		 reinitializeDataTable("#dashboard-warehouseutilizationtbl",3);
		$(modal).modal('hide');
		button.removeAttr('disabled');
	}
	
});

$(document).off('change',dashpane+' .inventory-selectwhse').on('change',dashpane+' .inventory-selectwhse',function(){
	var whse = $(this).val();
	$.post("../config/post-functions.php",{getPhysicalInventoryInAWarehouse:'Fkjoo#09#j!@IO#09aujj$Oi03n',whse:whse},function(data){
		$(dashpane+' .inventory-selectitem').html(data.trim()).trigger('change');
	});
});

$(document).off('click',dashpane+' #dash-inventory-loadbtn').on('click',dashpane+' #dash-inventory-loadbtn',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		whse = $(modal+' .inventory-selectwhse').val(),
		item = $(modal+' .inventory-selectitem').val(),
		button = $(this);
		button.attr('disabled',true);

	if(whse=='NULL'||whse==''){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a warehouse.</div></div>");
		button.removeAttr('disabled');
	}
	else {
		$(dashpane+' #dashboard-inventorytbl tbody').load("Loadables/dashboard/inventory-tblrows.php?whse="+whse+"&item="+item);
		    $("#dashboard-inventorytbl").dataTable().fnDestroy();
			reinitializeDataTable("#dashboard-inventorytbl",3);
			$(modal).modal('hide');
			button.removeAttr('disabled');
	}
});


$(document).off('dblclick',dashpane+' .dashboard-inventorytbl-row').on('dblclick',dashpane+' .dashboard-inventorytbl-row',function(){
	var whse = $(this).attr('whse'),
		item = $(this).attr('item'),
		uom = $(this).attr('uom');
	$('#dash-inventory-iteminventoryinfomodal').modal('show');
	$(dashpane+' #dash-inventory-iteminfotbl tbody').load("Loadables/dashboard/inventory-itemlocinfo.php?whse="+whse+"&item="+item+'&uom='+uom);
});




$(document).off('click',dashpane+' #dash-warehouseutl-downloadbtn:not(".disabled")').on('click',dashpane+' #dash-warehouseutl-downloadbtn:not(".disabled")',function(){
	
	var whse = $(dashpane+' .whseselectionfield').val(),
	    date = $(dashpane+' .dateselectionfield').val(),
		button = $(this);
		button.addClass('disabled');

	
		window.open("Printouts/excel/dashboard-warehouse-utilization-summary.php?warehouseid="+whse+"&asofdate="+date);
		button.removeClass('disabled');
	
});