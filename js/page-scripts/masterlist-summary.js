var masterlistreportpane = "#masterlistreport-menutabpane";

$(document).off('click',masterlistreportpane+' #masterlistsummary-trans-downloadbtn:not(".disabled")').on('click',masterlistreportpane+' #masterlistsummary-trans-downloadbtn:not(".disabled")',function(){

	var type = $(masterlistreportpane+' .masterlistsummary-type').val(),
		button = $(this);

		button.addClass('disabled').removeClass('active');


		var w = window.open("printouts/excel/reports.masterlist-summary.php?type="+type);
							

		w.onbeforeunload = function(){
								button.removeClass('disabled').addClass('active');
						   }				
		

		
		

});

