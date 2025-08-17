let tabCDDT = '#cddtreport-menutabpane';

/*$(document)
	.off('change', tabCDDT + ' .cddtreport-date')
	.on('change', tabCDDT + ' .cddtreport-date', function () {
		let docdate = $(this).val();
		$(tabCDDT + ' #cddtreport-table')
			.flexOptions({
				url: `loadables/ajax/reports.metro-manila-daily-activity.php?docdate=${docdate}`,
				sortname: 'txn_manifest.driver_name',
				sortorder: 'asc',
			})
			.flexReload();
	});*/

$(document)
	.off('click', tabCDDT + ' #cddtreport-trans-downloadbtn:not(".disabled")')
	.on('click', tabCDDT + ' #cddtreport-trans-downloadbtn:not(".disabled")', function () {
		let docdate = $(tabCDDT + ' .cddtreport-date').val();
		let courier = $(tabCDDT + ' .cddtreport-courier').val();
		let btn = $(this);
		btn.addClass('disabled');

		if (courier == null || courier == '' || courier == 'NULL' || courier == 'null' || courier == undefined) {
			say('Please select assigned courier');
			btn.removeClass('disabled');
		} else if (docdate == '') {
			say('Please provide transmittal date.');
			btn.removeClass('disabled');
		} else {
			let w = window.open(`printouts/excel/reports.courier-daily-delivery-transmittal.php?docdate=${docdate}&driver=${courier}`);

			w.onbeforeunload = function () {
				btn.removeClass('disabled');
			};
		}
	});
