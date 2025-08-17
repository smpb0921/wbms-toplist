var contentCOSTNG = '#costing-menutabpane';

/******************* SEARCH FILTER ***********************/

function costingclearfilteredtxn() {
	$(contentCOSTNG + ' .costingfilterfld').val('');
	$(contentCOSTNG + ' .costingfilterslct')
		.empty()
		.trigger('change');
	$(contentCOSTNG + ' .costingfilterslct2')
		.val('')
		.trigger('change');
	costingloadfilteredtxn();
}

function costingloadfilteredtxn() {
	var typeofaccount = $(contentCOSTNG + ' .costing-typeofaccount').val(),
		chartofaccounts = $(contentCOSTNG + ' .costing-chartofaccounts').val(),
		payee = $(contentCOSTNG + ' .costing-payee').val(),
		amount = $(contentCOSTNG + ' .costing-amount').val(),
		reference = encodeURIComponent($(contentCOSTNG + ' .costing-reference').val()),
		prfnumber = encodeURIComponent($(contentCOSTNG + ' .costing-prfnumber').val()),
		waybillnumber = $(contentCOSTNG + ' .costing-waybillnumber').val(),
		costingdatefrom = $(contentCOSTNG + ' .costing-datefrom').val(),
		costingdateto = $(contentCOSTNG + ' .costing-dateto').val(),
		createddatefrom = $(contentCOSTNG + ' .costing-createddatefrom').val(),
		createddateto = $(contentCOSTNG + ' .costing-createddateto').val();

	$(contentCOSTNG + ' #costing-detailstbl')
		.flexOptions({
			url:
				'loadables/ajax/transactions.costing-details.php?prfnumber=' +
				prfnumber +
				'&typeofaccount=' +
				typeofaccount +
				'&chartofaccounts=' +
				chartofaccounts +
				'&reference=' +
				reference +
				'&waybillnumber=' +
				waybillnumber +
				'&costingdatefrom=' +
				costingdatefrom +
				'&costingdateto=' +
				costingdateto +
				'&createddatefrom=' +
				createddatefrom +
				'&createddateto=' +
				createddateto +
				'&payee=' +
				payee,
			sortname: 'costing.id',
			sortorder: 'desc',
			newp: 1,
		})
		.flexReload();
}

$(document)
	.off('click', contentCOSTNG + ' #costing-searchbtn')
	.on('click', contentCOSTNG + ' #costing-searchbtn', function () {
		costingloadfilteredtxn();
	});

$(document)
	.off('keyup', contentCOSTNG + ' .costingfilterfld')
	.on('keyup', contentCOSTNG + ' .costingfilterfld', function (e) {
		var key = e.keycode || e.which;
		if (key == 13) {
			costingloadfilteredtxn();
		}
	});

$(document)
	.off('select2:select', contentCOSTNG + ' .costingfilterslct, ' + contentCOSTNG + ' .costingfilterslct2')
	.on('select2:select', contentCOSTNG + ' .costingfilterslct, ' + contentCOSTNG + ' .costingfilterslct2', function (e) {
		costingloadfilteredtxn();
	});

$(document)
	.off('click', contentCOSTNG + ' #costing-clearfilterbtn')
	.on('click', contentCOSTNG + ' #costing-clearfilterbtn', function () {
		costingclearfilteredtxn();
	});
/**************** SEARCH FILTER - END ***********************/

/******************** ADD EDIT COSTING MODAL *****************/
$(document)
	.off('click', contentCOSTNG + ' .addeditcostingmodal-savebtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .addeditcostingmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			type = $(modal + ' .addeditcostingmodal-typeofaccount').val(),
			payee = $(modal + ' .addeditcostingmodal-payee').val(),
			payeeaddress = $(modal + ' .addeditcostingmodal-payeeaddress').val(),
			particulars = $(modal + ' .addeditcostingmodal-chartofaccounts').val(),
			amount = $(modal + ' .addeditcostingmodal-amount').val(),
			vatflag = $(modal + ' .addeditcostingmodal-vatflag').val(),
			vatableamount = $(modal + ' .addeditcostingmodal-vatableamount').val(),
			vat = $(modal + ' .addeditcostingmodal-vat').val(),
			reference = $(modal + ' .addeditcostingmodal-reference').val(),
			prfnumber = $(modal + ' .addeditcostingmodal-prfnumber').val(),
			date = $(modal + ' .addeditcostingmodal-date').val(),
			id = '',
			newsort = 'costing.created_date',
			source = 'add',
			button = $(this);
		button.addClass('disabled').attr('disabled', 'disabled');

		if (modal == '#editcostingmodal') {
			id = $(modal + ' #addeditcostingmodal-ctsngID').val();
			source = 'edit';
			newsort = 'costing.updated_date';
		}

		if (date.trim() == '') {
			$(modal + ' .addeditcostingmodal-date').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide costing date.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (type == '' || type == null || type == 'null' || type == 'NULL' || type == undefined) {
			$(modal + ' .addeditcostingmodal-typeofaccount').select2('open');
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select type of account.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (particulars == '' || particulars == null || particulars == 'null' || particulars == 'NULL' || particulars == undefined) {
			$(modal + ' .addeditcostingmodal-chartofaccounts').select2('open');
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an account.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (payee == '' || payee == null || payee == 'null' || payee == 'NULL' || payee == undefined) {
			$(modal + ' .addeditcostingmodal-payee').select2('open');
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select payee</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (!(amount >= 0) || amount == '') {
			$(modal + ' .addeditcostingmodal-amount').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide amount.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (!(vatableamount >= 0) || vatableamount == '') {
			$(modal + ' .addeditcostingmodal-vatableamount').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide vatable amount.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if ((vat <= 0 || vat == '') && vatflag == 1) {
			$(modal + ' .addeditcostingmodal-vat').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide vat amount.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'costing.php',
				{
					costingSaveEdit: 'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',
					source: source,
					id: id,
					type: type,
					particulars: particulars,
					amount: amount,
					reference: reference,
					date: date,
					prfnumber: prfnumber,
					payee: payee,
					payeeaddress: payeeaddress,
					vatflag: vatflag,
					vatableamount: vatableamount,
					vat: vat,
				},
				function (data) {
					if (data.trim() == 'success') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							//costingclearfilteredtxn();
							$('#loading-img').addClass('hidden');
							$(modal).off('hidden.bs.modal');
							button.removeAttr('disabled').removeClass('disabled');

							$(modal + ' .costinginputfld').val('');
							$(modal + ' .costinginputslct')
								.empty()
								.trigger('change');

							$(contentCOSTNG + ' .costingfilterfld').val('');
							$(contentCOSTNG + ' .costingfilterslct')
								.empty()
								.trigger('change');
							$(contentCOSTNG + ' .costingfilterslct2')
								.val('')
								.trigger('change');
						});
					} else if (data.trim() == 'referenceexists') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .addeditcostingmodal-reference').focus();
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Already exists.</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
					} else if (data.trim() == 'invalidcostingdate') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .addeditcostingmodal-date').focus();
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide costing date</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
					} else if (data.trim() == 'invalidparticulars') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .addeditcostingmodal-chartofaccounts').selec2('open');
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select account</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
					} else if (data.trim() == 'noaddpermission') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Access denied. No permission to add.</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
						userAccess();
					} else if (data.trim() == 'noeditpermission') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Access denied. No permission to edit.</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
						userAccess();
					} else if (data.trim() == 'sourceundefined') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Source undefined. Please refresh page.</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
						userAccess();
					} else {
						$('#loading-img').addClass('hidden');
						alert(data);
						button.removeAttr('disabled').removeClass('disabled');
					}

					$(contentCOSTNG + ' #costing-detailstbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.costing-details.php',
							sortname: newsort,
							sortorder: 'desc',
						})
						.flexReload();
				}
			);
		}
	});

$(document)
	.off('click', contentCOSTNG + ' .editcostingbtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .editcostingbtn:not(".disabled")', function () {
		var modal = '#editcostingmodal';

		var id = $(this).attr('rowid');
		var button = $(this);
		button.addClass('disabled');

		$.post(server + 'costing.php', { getData: 'kFNEPslkd$HmndlUpdklSpR#1NEi34smo1sonk&$', id: id }, function (response) {
			if (isJson(response) == true) {
				data = $.parseJSON(response);
				if (data['response'] == 'INVALID') {
					$(modal + ' #addeditcostingmodal-ctsngID').val('');
					$(modal + ' .costinginputfld').val('');
					$(modal + ' .costinginputslct')
						.empty()
						.trigger('change');

					button.removeClass('disabled');
				} else if (data['response'] == 'success') {
					$(modal + ' #addeditcostingmodal-ctsngID').val(id);
					$(modal + ' .addeditcostingmodal-date').val(data['date']);
					$(modal + ' .addeditcostingmodal-amount').val(data['amount']);
					$(modal + ' .addeditcostingmodal-vatflag').val(data['isvatable']);
					$(modal + ' .addeditcostingmodal-vat').val(data['vat']);
					$(modal + ' .addeditcostingmodal-payeeaddress').val(data['payeeaddress']);
					$(modal + ' .addeditcostingmodal-accountproducttype').val(data['producttype']);
					$(modal + ' .addeditcostingmodal-vatableamount').val(data['vatableamount']);

					$(modal + ' .addeditcostingmodal-reference').val(data['reference']);
					$(modal + ' .addeditcostingmodal-prfnumber').val(data['prfnumber']);

					if (data['typeofaccount'] != null && data['typeofaccountid'] > 0) {
						$(modal + ' .addeditcostingmodal-typeofaccount')
							.empty()
							.append('<option selected value="' + data['typeofaccountid'] + '">' + data['typeofaccount'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .addeditcostingmodal-typeofaccount')
							.empty()
							.trigger('change');
					}

					if (data['account'] != null && data['accountid'] > 0) {
						$(modal + ' .addeditcostingmodal-chartofaccounts')
							.empty()
							.append('<option selected value="' + data['accountid'] + '">' + data['account'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .addeditcostingmodal-chartofaccounts')
							.empty()
							.trigger('change');
					}

					if (data['payeename'] != null && data['payeeid'] > 0) {
						$(modal + ' .addeditcostingmodal-payee')
							.empty()
							.append('<option selected value="' + data['payeeid'] + '">[' + data['payeetin'] + '] ' + data['payeename'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .addeditcostingmodal-payee')
							.empty()
							.trigger('change');
					}

					$(modal).modal('show');
					button.removeClass('disabled');
				}
			} else {
				console.log(response);
				say('Invalid JSON Response');
				button.removeClass('disabled');
			}
		});
	});

$(document)
	.off('click', contentCOSTNG + ' .viewcostingrcvbtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .viewcostingrcvbtn:not(".disabled")', function () {
		var modal = '#viewcostingrcvmodal';

		var btn = $(this);
		btn.addClass('disabled');

		var id = btn.attr('rowid');

		$.post(server + 'costing.php', { getData: 'kFNEPslkd$HmndlUpdklSpR#1NEi34smo1sonk&$', id: id }, function (response) {
			if (isJson(response) == true) {
				data = $.parseJSON(response);
				if (data['response'] == 'INVALID') {
					say('Invalid Costing ID. Please refresh.');
					btn.removeClass('disabled');
				} else if (data['response'] == 'success') {
					if (data['addrcvaccess'] == 1 || 1 == 1) {
						$(modal + ' .viewcostingrcvmodal-addrcvwrapper').removeClass('hidden');
					} else {
						$(modal + ' .viewcostingrcvmodal-addrcvwrapper').addClass('hidden');
					}

					$(modal + ' .viewcostingrcvmodal-costingID').val(id);
					$(modal + ' .viewcostingrcvmodal-date').val(data['date']);
					$(modal + ' .viewcostingrcvmodal-typeofaccount').val(data['typeofaccount']);
					$(modal + ' .viewcostingrcvmodal-chartofaccounts').val(data['account']);
					$(modal + ' .viewcostingrcvmodal-amount').val(data['amountdesc']);
					$(modal + ' .viewcostingrcvmodal-reference').val(data['reference']);
					$(modal + ' .viewcostingrcvmodal-prfnumber').val(data['prfnumber']);

					$(contentCOSTNG + ' #viewcostingrcvmodal-existingrcvtbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.costing-view-rcvcosting-details.php?txnid=' + id,
							sortname: 'txn_waybill.waybill_number',
							sortorder: 'asc',
						})
						.flexReload();

					$(contentCOSTNG + ' #viewcostingrcvmodal-addrcvtbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.costing-add-rcvcosting-details.php?txnid=' + id,
							sortname: 'txn_waybill.waybill_number',
							sortorder: 'asc',
						})
						.flexReload();

					$(modal).modal('show');
					$(document)
						.off('shown.bs.modal', modal)
						.on('shown.bs.modal', modal, function () {
							$(document).off('shown.bs.modal', modal);
							btn.removeClass('disabled');
						});
				}
			} else {
				console.log(response);
				say('Invalid JSON Response');
				button.removeClass('disabled');
			}
		});
	});

$(document)
	.off('click', contentCOSTNG + ' .costing-addrcvbtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .costing-addrcvbtn:not(".disabled")', function () {
		var btn = $(this);
		btn.addClass('disabled');
		var modal = '#' + $(this).closest('.modal').attr('id');

		var costingid = $(modal + ' .viewcostingrcvmodal-costingID').val();
		var rowid = btn.attr('rowid');
		var txnnumber = btn.attr('txnnumber');
		$('#loading-img').removeClass('hidden');
		$.post(server + 'costing.php', { addStockReceipt: 'FOskfOIheNLPFI#nlio5ja3op2a2lK@3#4hh$93s', rowid: rowid, txnnumber: txnnumber, costingid: costingid }, function (data) {
			if (isJson(data) == true) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'ADDED') {
					btn.removeClass('disabled');
				} else if (rsp['response'] == 'INVALIDCOSTING') {
					say('Invalid Costing ID: ' + costingid);
					btn.removeClass('disabled');
				} else if (rsp['response'] == 'INVALIDTXN') {
					say('Invalid BOL Number: ' + txnnumber + ' [' + rowid + ']');
					btn.removeClass('disabled');
				} else if (rsp['response'] == 'ALREADYADDED') {
					say('Already added');
					btn.removeClass('disabled');
				} else if (rsp['response'] == 'NOPERMISSION') {
					say('Unable to add transaction. No permission to add.');
					btn.removeClass('disabled');
				} else {
					say('Uncaught JSON Response. ' + rsp['response']);
					btn.removeClass('disabled');
				}

				$(contentCOSTNG + ' #viewcostingrcvmodal-existingrcvtbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.costing-view-rcvcosting-details.php?txnid=' + costingid,
						sortname: 'txn_waybill.waybill_number',
						sortorder: 'asc',
					})
					.flexReload();

				$(contentCOSTNG + ' #viewcostingrcvmodal-addrcvtbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.costing-add-rcvcosting-details.php?txnid=' + costingid,
						sortname: 'txn_waybill.waybill_number',
						sortorder: 'asc',
					})
					.flexReload();

				costingloadfilteredtxn();

				$('#loading-img').addClass('hidden');
			} else {
				console.log(data);
				btn.removeClass('disabled');
				$('#loading-img').addClass('hidden');
			}
		});
	});

$(document)
	.off('click', contentCOSTNG + ' .deletecostingrcvbtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .deletecostingrcvbtn:not(".disabled")', function () {
		var btn = $(this);
		btn.addClass('disabled');

		var targettbl = '#viewcostingrcvmodal-existingrcvtbl';
		var modal = '#' + $(this).closest('.modal').attr('id');
		var costingid = $(modal + ' .viewcostingrcvmodal-costingID').val();

		var selectedrowcount = $(contentCOSTNG + ' ' + targettbl + ' input[type="checkbox"]:checked').length;

		if (parseInt(selectedrowcount) > 0) {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Confirm Deletion',
				content: 'Deleting [<b>' + selectedrowcount + '</b>] rows. Do you want to continue?',
				confirmButton: 'Delete',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-maroon',
				cancelButtonClass: 'btn-maroon',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					var rows = [];
					$(contentCOSTNG + ' ' + targettbl + ' input[type="checkbox"]:checked').each(function () {
						rows.push($(this).attr('rowid'));
					});
					$.post(server + 'costing.php', { deleteSelectedRcvCostingDetails: 'skj$oihdtpoa$I#@4noi4AIFlskoRboIh4!Uboi@bp9Rbzhs', rows: rows, txnid:costingid }, function (data) {
						if (isJson(data) == true) {
							rsp = $.parseJSON(data);

							if (rsp['response'] == 'success') {
								btn.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (rsp['response'] == 'nouserpermission') {
								say('Unable to delete rows. No user permission.');
								$('#loading-img').addClass('hidden');
								btn.removeClass('disabled');
							} else {
								say('Uncaught JSON Response. Please contact system administrator.');
								btn.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}

							$(contentCOSTNG + ' #viewcostingrcvmodal-existingrcvtbl')
								.flexOptions({
									url: 'loadables/ajax/transactions.costing-view-rcvcosting-details.php?txnid=' + costingid,
									sortname: 'txn_waybill.waybill_number',
									sortorder: 'asc',
								})
								.flexReload();

							$(contentCOSTNG + ' #viewcostingrcvmodal-addrcvtbl')
								.flexOptions({
									url: 'loadables/ajax/transactions.costing-add-rcvcosting-details.php?txnid=' + costingid,
									sortname: 'txn_waybill.waybill_number',
									sortorder: 'asc',
								})
								.flexReload();

							costingloadfilteredtxn();
						} else {
							say('Invalid JSON Response. Please contact system administrator.');
							btn.removeClass('disabled');
							$('#loading-img').addClass('hidden');
							console.log(data);
						}
					});
				},
				cancel: function () {
					btn.removeClass('disabled');
				},
			});
		} else {
			say('Please select row(s) to be deleted');
			btn.removeClass('disabled');
		}
	});

$(document)
	.off('click', contentCOSTNG + ' .deletecostingbtn:not(".disabled")')
	.on('click', contentCOSTNG + ' .deletecostingbtn:not(".disabled")', function () {
		var btn = $(this);
		btn.addClass('disabled');

		var targettbl = '#costing-detailstbl';
		var modal = '#' + $(this).closest('.modal').attr('id');

		var selectedrowcount = $(contentCOSTNG + ' ' + targettbl + ' input[type="checkbox"]:checked').length;

		if (parseInt(selectedrowcount) > 0) {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Confirm Deletion',
				content: 'Deleting [<b>' + selectedrowcount + '</b>] rows. Do you want to continue?',
				confirmButton: 'Delete',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-maroon',
				cancelButtonClass: 'btn-maroon',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					var rows = [];
					$(contentCOSTNG + ' ' + targettbl + ' input[type="checkbox"]:checked').each(function () {
						rows.push($(this).attr('rowid'));
					});
					$.post(server + 'costing.php', { deleteSelectedCostingDetails: 'skj$oihdtpoa$I#JFHOFDnO#2hDOihENnlDUnKEUbn', rows: rows }, function (data) {
						if (isJson(data) == true) {
							rsp = $.parseJSON(data);

							if (rsp['response'] == 'success') {
								btn.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (rsp['response'] == 'nouserpermission') {
								say('Unable to delete rows. No user permission.');
								$('#loading-img').addClass('hidden');
								btn.removeClass('disabled');
							} else {
								say('Uncaught JSON Response. Please contact system administrator.');
								btn.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}

							costingloadfilteredtxn();
						} else {
							say('Invalid JSON Response. Please contact system administrator.');
							btn.removeClass('disabled');
							$('#loading-img').addClass('hidden');
							console.log(data);
						}
					});
				},
				cancel: function () {
					btn.removeClass('disabled');
				},
			});
		} else {
			say('Please select row(s) to be deleted');
			btn.removeClass('disabled');
		}
	});

$(document)
	.off('click', contentCOSTNG + ' .downloadcostinghistorybtn:not(".disabled")')
	.on('click', contentCOSTNG + " .downloadcostinghistorybtn:not('disabled')", function () {
		var btn = $(this);
		//btn.addClass('disabled');

		var expensetype = $(contentCOSTNG + ' .costing-typeofaccount').val(),
			chartofaccounts = $(contentCOSTNG + ' .costing-chartofaccounts').val(),
			payee = $(contentCOSTNG + ' .costing-payee').val(),
			reference = encodeURIComponent($(contentCOSTNG + ' .costing-reference').val()),
			prfnumber = encodeURIComponent($(contentCOSTNG + ' .costing-prfnumber').val()),
			bolnumber = encodeURIComponent($(contentCOSTNG + ' .costing-waybillnumber').val()),
			costingdatefrom = $(contentCOSTNG + ' .costing-datefrom').val(),
			costingdateto = $(contentCOSTNG + ' .costing-dateto').val(),
			createddatefrom = $(contentCOSTNG + ' .costing-createddatefrom').val(),
			createddateto = $(contentCOSTNG + ' .costing-createddateto').val();

		var downloadlog = window.open(
			'printouts/excel/transactions.costing-charges-summary.php?prfnumber=' +
				prfnumber +
				'&expensetype=' +
				expensetype +
				'&chartofaccounts=' +
				chartofaccounts +
				'&payee=' +
				payee +
				'&reference=' +
				reference +
				'&bolnumber=' +
				bolnumber +
				'&costingdatefrom=' +
				costingdatefrom +
				'&costingdateto=' +
				costingdateto +
				'&createddatefrom=' +
				createddatefrom +
				'&createddateto=' +
				createddateto
		);
	});

$(document)
	.off('change', contentCOSTNG + ' .addeditcostingmodal-typeofaccount:not(".disabled")')
	.on('change', contentCOSTNG + ' .addeditcostingmodal-typeofaccount:not(".disabled")', function () {
		var type = $(this).val();

		$(contentCOSTNG + ' .addeditcostingmodal-chartofaccounts').select2({
			ajax: {
				url: 'loadables/dropdown/chart-of-accounts.php?type=' + type,
				dataType: 'json',
				delay: 100,
				data: function (params) {
					return {
						q: params.term, // search term
					};
				},
				processResults: function (data) {
					return {
						results: data,
					};
				},
				cache: true,
			},
			minimumInputLength: 0,
			width: '100%',
		});
	});

$(document)
	.off('select2:select', contentCOSTNG + ' .addeditcostingmodal-chartofaccounts')
	.on('select2:select', contentCOSTNG + ' .addeditcostingmodal-chartofaccounts', function (e) {
		var vehicletype = $(this).val();
		var type = e.params.data['data-type'];

		var modal = '#' + $(this).closest('.modal').attr('id');
		$(modal + ' .addeditcostingmodal-accountproducttype').val(type);
	});

$(document)
	.off('select2:select', contentCOSTNG + ' .addeditcostingmodal-payee')
	.on('select2:select', contentCOSTNG + ' .addeditcostingmodal-payee', function () {
		let payee = $(this).val();
		let modal = '#' + $(this).closest('.modal').attr('id');

		$.post(server + 'costing.php', { getPayeeInfo: 'BCDjlkns2k!DEUgDLQIWN4mCLAhdOIEZ#', payee: payee }, function (data) {
			if (isJson(data) == true) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'success') {
					$(modal + ' .addeditcostingmodal-payeeaddress').val(rsp['address']);
					$(modal + ' .addeditcostingmodal-amount').focus();
				} else {
					say('Uncaught JSON Response: ' + rsp['response']);
					console.log(rsp['response']);
				}
			} else {
				say('Invalid JSON Response. Please contact system administrator.');
				console.log(data);
			}
		});
	});

$(document)
	.off('change', contentCOSTNG + ' .addeditcostingmodal-vatflag')
	.on('change', contentCOSTNG + ' .addeditcostingmodal-vatflag', function () {
		let modal = '#' + $(this).closest('.modal').attr('id');
		let vatableamount = $(modal + ' .addeditcostingmodal-vatableamount').val();
		let vat = '';

		if ($(this).val() == 1) {
			if (parseFloat(vatableamount) > 0) {
				vat = parseFloat(vatableamount) * 0.12;
			}
			$(modal + ' .addeditcostingmodal-vat')
				.val(vat)
				.removeClass('disabled')
				.removeAttr('disabled');
		} else {
			$(modal + ' .addeditcostingmodal-vat')
				.val('0')
				.addClass('disabled')
				.attr('disabled', true);
		}
	});

/************************* PRINTING *****************************************/
$(document)
	.off('click', contentCOSTNG + ' .printcostingbtn')
	.on('click', contentCOSTNG + ' .printcostingbtn', function () {
		var title = 'Print Preview [' + $(this).attr('prfnumber') + ']';
		var tabid = $(this).attr('prfnumber');
		var costingid = $(this).attr('rowid');

		if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
			$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
			$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
			$('#loading-img').removeClass('hidden');
			$('.content').animate({ scrollTop: 0 }, 300);

			$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
			$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
			$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
			$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
			$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
				'Printouts/print-preview.php?source=printouts/transactions/costing-per-prfnumber.php?txnnumber=' + costingid + '&reference=' + tabid
			);
			setTimeout(function () {
				$('#loading-img').addClass('hidden');
			}, 400);
		} else {
			$('#loading-img').removeClass('hidden');
			$('.content').animate({ scrollTop: 0 }, 300);

			$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
			$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
			$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
			$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
			$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
				'Printouts/print-preview.php?source=printouts/transactions/costing-per-prfnumber.php?txnnumber=' + costingid + '&reference=' + tabid
			);
			setTimeout(function () {
				$('#loading-img').addClass('hidden');
			}, 400);
		}
	});
/************************* PRINTING - END *****************************************/
