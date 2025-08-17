


var transmittalreportpane = "#transmittalreport-menutabpane";

$(document).off('click',transmittalreportpane+' #transmittalsummary-trans-downloadbtn:not(".disabled")').on('click',transmittalreportpane+' #transmittalsummary-trans-downloadbtn:not(".disabled")',function(){

	var format = $(transmittalreportpane+' .transmittalsummary-format').val();
	var	billingnumber = $(transmittalreportpane+' .transmittalsummary-billingnumber').val();
	var	button = $(this);

		button.addClass('disabled');


	if(billingnumber.trim()==''){
		button.removeClass('disabled');
		say("Please provide billing number");
	}
	else{
		var w = window.open("printouts/excel/reports.transmittal-summary.php?format="+format+"&billingnumber="+billingnumber);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled');
						   }	
	}
		

			
		

		
		

});

