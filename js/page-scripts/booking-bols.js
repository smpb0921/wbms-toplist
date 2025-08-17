var tabBKBOL = '#bookingbols-menutabpane';

$(document)
	.off('click', tabBKBOL + ' #bookingbols-downloadbtn:not(".disabled")')
	.on('click', tabBKBOL + ' #bookingbols-downloadbtn:not(".disabled")', function () {
		var shipper = $(tabBKBOL + ' .bookingbols-shipper').val();
		var trackingnumber = $(tabBKBOL + ' .bookingbols-trackingnumber').val();
		var billreference = $(tabBKBOL + ' .bookingbols-billreference').val();
		var insurancereference = $(tabBKBOL + ' .bookingbols-insurancereference').val();
		var bolnumber = $(tabBKBOL + ' .bookingbols-bolnumber').val();
		var datefrom = $(tabBKBOL + ' .bookingbols-datefrom').val();
		var dateto = $(tabBKBOL + ' .bookingbols-dateto').val();
		var button = $(this);

		button.addClass('disabled').removeClass('active');

		var w = window.open(
			'printouts/excel/reports.waybill-costing-summary.php?shipper=' +
				shipper +
				'&trackingnumber=' +
				trackingnumber +
				'&bolnumber=' +
				bolnumber +
				'&billreference=' +
				billreference +
				'&insurancereference=' +
				insurancereference +
				'&datefrom=' +
				datefrom +
				'&dateto=' +
				dateto
		);

		w.onbeforeunload = function () {
			button.removeClass('disabled').addClass('active');
		};
	});

$(document)
	.off('change', tabBKBOL + ' .bookingbols-bookingnumber')
	.on('change', tabBKBOL + ' .bookingbols-bookingnumber', function () {
		let bookingnumber = $(this).val();
		$(tabBKBOL + ' #bookingbols-table')
			.flexOptions({
				url: `loadables/ajax/reports.booking-waybills.php?bookingnumber=${bookingnumber}`,
				sortname: 'txn_waybill.waybill_number',
				sortorder: 'asc',
			})
			.flexReload();
	});

$(document)
	.off('click', tabBKBOL + ' .downloadbookingbolreport:not(".disabled")')
	.on('click', tabBKBOL + ' .downloadbookingbolreport:not(".disabled")', function () {
		let bookingnumber = $(tabBKBOL + ' .bookingbols-bookingnumber').val();
		let btn = $(this);
		if (bookingnumber !== null) {
			btn.addClass('disabled');
			let w = window.open(`printouts/excel/reports.booking-waybill.php?bookingnumber=${bookingnumber}`);

			w.onbeforeunload = function () {
				btn.removeClass('disabled');
			};
		} else {
			say('Please select booking number');
			btn.removeClass('disabled');
		}
	});
