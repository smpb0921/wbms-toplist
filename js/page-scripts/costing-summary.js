


var waybillcostingreportpane = "#costingreport-menutabpane";

$(document).off('click',waybillcostingreportpane+' #costingsummary-trans-downloadbtn:not(".disabled")').on('click',waybillcostingreportpane+' #costingsummary-trans-downloadbtn:not(".disabled")',function(){

		var shipper = $(waybillcostingreportpane+' .costingsummary-shipper').val();
		var	trackingnumber = $(waybillcostingreportpane+' .costingsummary-trackingnumber').val();
		var	billreference = $(waybillcostingreportpane+' .costingsummary-billreference').val();
		var	insurancereference = $(waybillcostingreportpane+' .costingsummary-insurancereference').val();
		var	bolnumber = $(waybillcostingreportpane+' .costingsummary-bolnumber').val();
		var	datefrom = $(waybillcostingreportpane+' .costingsummary-datefrom').val();
		var	dateto = $(waybillcostingreportpane+' .costingsummary-dateto').val();
		var	button = $(this);

		button.addClass('disabled').removeClass('active');
		

		var w = window.open("printouts/excel/reports.waybill-costing-summary.php?shipper="+shipper+"&trackingnumber="+trackingnumber+"&bolnumber="+bolnumber+"&billreference="+billreference+"&insurancereference="+insurancereference+"&datefrom="+datefrom+"&dateto="+dateto);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }		
		

		
		

});

