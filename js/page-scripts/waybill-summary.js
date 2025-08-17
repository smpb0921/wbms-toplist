var waybillreportpane = '#waybillreport-menutabpane';

$(document)
	.off('click', waybillreportpane + ' #waybillsummary-trans-downloadbtn:not(".disabled")')
	.on('click', waybillreportpane + ' #waybillsummary-trans-downloadbtn:not(".disabled")', function () {
		var status = $(waybillreportpane + ' .waybillsummary-status').val(),
			billingstatus = $(waybillreportpane + ' .waybillsummary-billingstatus').val(),
			mawbl = $(waybillreportpane + ' .waybillsummary-mawbl').val(),
			origin = $(waybillreportpane + ' .waybillsummary-origin').val(),
			destination = $(waybillreportpane + ' .waybillsummary-destination').val(),
			destinationroute = $(waybillreportpane + ' .waybillsummary-destinationroute').val(),
			shipper = $(waybillreportpane + ' .waybillsummary-shipper').val(),
			consignee = $(waybillreportpane + ' .waybillsummary-consignee').val(),
			service = $(waybillreportpane + ' .waybillsummary-service').val(),
			modeoftransport = $(waybillreportpane + ' .waybillsummary-modeoftransport').val(),
			paymode = $(waybillreportpane + ' .waybillsummary-paymode').val(),
			docdatefrom = $(waybillreportpane + ' .waybillsummary-docdatefrom').val(),
			docdateto = $(waybillreportpane + ' .waybillsummary-docdateto').val(),
			deldatefrom = $(waybillreportpane + ' .waybillsummary-deldatefrom').val(),
			deldateto = $(waybillreportpane + ' .waybillsummary-deldateto').val(),
			pudatefrom = $(waybillreportpane + ' .waybillsummary-pudatefrom').val(),
			pudateto = $(waybillreportpane + ' .waybillsummary-pudateto').val(),
			format = $(waybillreportpane + ' .waybillsummary-format').val(),
			manifestflag = $(waybillreportpane + ' .waybillsummary-withmanifestflag').val(),
			bolstartseries = $(waybillreportpane + ' .waybillsummary-bolstartseries').val(),
			bolendseries = $(waybillreportpane + ' .waybillsummary-bolendseries').val(),
			bsstartseries = $(waybillreportpane + ' .waybillsummary-bsstartseries').val(),
			bsendseries = $(waybillreportpane + ' .waybillsummary-bsendseries').val(),
			bookingnumber = $(waybillreportpane + ' .waybillsummary-bookingnumber').val(),
			//itemname = $(waybillreportpane+' .issuancereport-itemname').val().replace(/\s/g,'%20'),
			button = $(this);

		if (bolendseries.trim() != '' && bolstartseries.trim() == '') {
			say('Please provide bol start series');
		} else if (bolstartseries.trim() != '' && bolendseries.trim() == '') {
			say('Please provide bol end series');
		} else if (bolstartseries.trim() != '' && checkInt(bolstartseries) != 1) {
			say('BOL Start series must be numeric');
		} else if (bolendseries.trim() != '' && checkInt(bolendseries) != 1) {
			say('BOL End series must be numeric');
		} else {
			button.addClass('disabled').removeClass('active');

			var w = window.open(
				encodeURI(
					`printouts/excel/reports.waybill-summary.php?bookingnumber=${bookingnumber}&manifestflag=${manifestflag}&status=${status}&origin=${origin}&destination=${destination}&destinationroute=${destinationroute}&shipper=${shipper}&consignee=${consignee}&service=${service}&modeoftransport=${modeoftransport}&paymode=${paymode}&docdatefrom=${docdatefrom}&docdateto=${docdateto}&deldatefrom=${deldatefrom}&deldateto=${deldateto}&pudatefrom=${pudatefrom}&pudateto=${pudateto}&format=${format}&mawbl=${mawbl}&bolstartseries=${bolstartseries}&bolendseries=${bolendseries}&billingstatus=${billingstatus}&bsstartseries=${bsstartseries}&bsendseries=${bsendseries}`
				)
			);

			w.onbeforeunload = function () {
				button.removeClass('disabled').addClass('active');
			};
		}
	});
