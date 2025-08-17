let tabRTS = '#rtsreport-menutabpane';

$(document)
	.off('click', tabRTS + ' #rtsreport-trans-downloadbtn:not(".disabled")')
	.on('click', tabRTS + ' #rtsreport-trans-downloadbtn:not(".disabled")', function () {
		let datefrom = $(tabRTS + ' .rtsreport-datefrom').val();
		let dateto = $(tabRTS + ' .rtsreport-dateto').val();
		let shipper = $(tabRTS + ' .rtsreport-shipper').val();
		let btn = $(this);
		btn.addClass('disabled');

		if (shipper == '' || (shipper == null) | (shipper == 'null') || shipper == 'NULL') {
			say('Please select shipper.');
			btn.removeClass('disabled');
		} else {
			let w = window.open(`printouts/excel/reports.rts-shipment-transmittal.php?shipper=${shipper}&datefrom=${datefrom}&dateto=${dateto}`);

			w.onbeforeunload = function () {
				btn.removeClass('disabled');
			};
		}
	});
