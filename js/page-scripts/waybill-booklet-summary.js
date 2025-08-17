


var waybillbookletreportpane = "#waybillbookletreport-menutabpane";

$(document).off('click',waybillbookletreportpane+' #waybillbookletsummary-trans-downloadbtn:not(".disabled")').on('click',waybillbookletreportpane+' #waybillbookletsummary-trans-downloadbtn:not(".disabled")',function(){

	var issuedto = $(waybillbookletreportpane+' .waybillbookletsummary-issuedto').val();
	var	datefrom = $(waybillbookletreportpane+' .waybillbookletsummary-datefrom').val();
	var	dateto = $(waybillbookletreportpane+' .waybillbookletsummary-dateto').val();
	var	button = $(this);

		button.addClass('disabled').removeClass('active');
		

		var w = window.open("printouts/excel/reports.waybill-booklet-summary.php?issuedto="+issuedto+"&datefrom="+datefrom+"&dateto="+dateto);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }		
		

		
		

});

