var billingreportpane = '#billingreport-menutabpane';

$(document)
	.off('click', billingreportpane + ' #billingsummary-trans-downloadbtn:not(".disabled")')
	.on('click', billingreportpane + ' #billingsummary-trans-downloadbtn:not(".disabled")', function () {
		var format = $(billingreportpane + ' .billingsummary-format').val(),
			waybill = $(billingreportpane + ' .billingsummary-waybill').val(),
			accountexecutive = $(billingreportpane + ' .billingsummary-accountexecutive').val(),
			status = $(billingreportpane + ' .billingsummary-status').val(),
			paidflag = $(billingreportpane + ' .billingsummary-paidflag').val(),
			shipper = $(billingreportpane + ' .billingsummary-shipper').val(),
			duedatefrom = $(billingreportpane + ' .billingsummary-duedatefrom').val(),
			duedateto = $(billingreportpane + ' .billingsummary-duedateto').val(),
			docdatefrom = $(billingreportpane + ' .billingsummary-docdatefrom').val(),
			docdateto = $(billingreportpane + ' .billingsummary-docdateto').val(),
			createddatefrom = $(billingreportpane + ' .billingsummary-createddatefrom').val(),
			createddateto = $(billingreportpane + ' .billingsummary-createddateto').val(),
			bsstartseries = $(billingreportpane + ' .billingsummary-bsstartseries').val(),
			bsendseries = $(billingreportpane + ' .billingsummary-bsendseries').val(),
			button = $(this);

		button.addClass('disabled').removeClass('active');

		var w = window.open(
			'printouts/excel/reports.billing-summary.php?accountexecutive=' +
				accountexecutive +
				'&status=' +
				status +
				'&shipper=' +
				shipper +
				'&waybill=' +
				waybill +
				'&docdatefrom=' +
				docdatefrom +
				'&docdateto=' +
				docdateto +
				'&duedatefrom=' +
				duedatefrom +
				'&duedateto=' +
				duedateto +
				'&createddatefrom=' +
				createddatefrom +
				'&createddateto=' +
				createddateto +
				'&format=' +
				format +
				'&paidflag=' +
				paidflag +
				'&bsstartseries=' +
				bsstartseries +
				'&bsendseries=' +
				bsendseries
		);

		w.onbeforeunload = function () {
			button.removeClass('disabled').addClass('active');
		};
	});

$(document)
	.off('change', billingreportpane + ' .billingsummary-format:not(".disabled")')
	.on('change', billingreportpane + ' .billingsummary-format:not(".disabled")', function () {
		var format = $(this).val();

		if (format == '1' || format == 1) {
			$(billingreportpane + ' .bssummwbnumwrapper').addClass('hidden');
		} else {
			$(billingreportpane + ' .bssummwbnumwrapper').removeClass('hidden');
		}
	});
