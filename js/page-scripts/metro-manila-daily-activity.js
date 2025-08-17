let tabMMDA = '#mmdareport-menutabpane';

$(document)
	.off('click', tabMMDA + ' #mmdareport-trans-downloadbtn:not(".disabled")')
	.on('click', tabMMDA + ' #mmdareport-trans-downloadbtn:not(".disabled")', function () {
		let docdate = $(tabMMDA + ' .mmdareport-date').val();
		let btn = $(this);
		btn.addClass('disabled');

		if (docdate == '') {
			say('Please provide transmittal date.');
			btn.removeClass('disabled');
		} else {
			let w = window.open(`printouts/excel/reports.metro-manila-daily-activity.php?docdate=${docdate}`);

			w.onbeforeunload = function () {
				btn.removeClass('disabled');
			};
		}
	});
