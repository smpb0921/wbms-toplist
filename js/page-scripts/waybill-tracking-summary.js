var waybilltrackingreportpane = "#waybilltrackingreport-menutabpane";

$(document).off('click',waybilltrackingreportpane+' #waybilltracking-trans-downloadbtn:not(".disabled")').on('click',waybilltrackingreportpane+' #waybilltracking-trans-downloadbtn:not(".disabled")',function(){

	var waybillnumber = $(waybilltrackingreportpane+' .waybilltracking-waybillnumber').val();
	var	shipper = $(waybilltrackingreportpane+' .waybilltracking-shipper').val();
	var	datefrom = $(waybilltrackingreportpane+' .waybilltracking-datefrom').val();
	var	dateto = $(waybilltrackingreportpane+' .waybilltracking-dateto').val();
	var	button = $(this);

		button.addClass('disabled').removeClass('active');
		

		var w = window.open("printouts/excel/reports.waybill-tracking-summary.php?waybillnumber="+waybillnumber+"&shipper="+shipper+"&datefrom="+datefrom+"&dateto="+dateto);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }		
		

		
		

});

