let tabPPMM = '#ppmmreport-menutabpane';

$(document)
	.off('click', tabPPMM + ' #ppmmreport-trans-downloadbtn:not(".disabled")')
	.on('click', tabPPMM + ' #ppmmreport-trans-downloadbtn:not(".disabled")', function () {
		let datefrom = $(tabPPMM + ' .ppmmreport-datefrom').val();
		let dateto = $(tabPPMM + ' .ppmmreport-dateto').val();
		let driver = $(tabPPMM + ' .ppmmreport-driver').val();
		let btn = $(this);
		btn.addClass('disabled');

		//if (driver == '' || (driver == null) | (driver == 'null') || driver == 'NULL') {
		//	say('Please select driver.');
		//	btn.removeClass('disabled');
		//} else {
		let w = window.open(`printouts/excel/reports.ppmm.php?driver=${driver}&datefrom=${datefrom}&dateto=${dateto}`);

		w.onbeforeunload = function () {
			btn.removeClass('disabled');
		};
		//}
	});
