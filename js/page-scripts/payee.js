$(document)
	.off('click', '.editpayeebtn')
	.on('click', '.editpayeebtn', function () {
		var modal = '#editpayeemodal';
		var rowid = $(this).attr('rowid');

		$(modal + ' .payeemodalid').val(rowid);

		$.post(server + 'payee.php', { payeeGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: rowid }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(modal + ' .payeename').val(rsp['payeename']);
				$(modal + ' .payeeaddress').val(rsp['payeeaddress']);
				$(modal + ' .payeetin').val(rsp['payeetin']);
			} else {
				$(modal).modal('hide');
				$(modal).on('hidden.bs.modal', function () {
					$(modal).off('hidden.bs.modal');
					say('Unable to load data. Invalid ID.');
				});
			}
		});
	});

/***************************** SAVE | EDIT **************************************************/
$(document)
	.off('click', '.payeemodal-savebtn:not(".disabled")')
	.on('click', '.payeemodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			payeename = $(modal + ' .payeename').val(),
			payeeaddress = $(modal + ' .payeeaddress').val(),
			payeetin = $(modal + ' .payeetin').val(),
			id = '',
			newsort = 'created_date',
			source = 'add',
			button = $(this);
		button.addClass('disabled').attr('disabled', 'disabled');

		if (modal == '#editpayeemodal') {
			id = $(modal + ' .payeemodalid').val();
			source = 'edit';
			newsort = 'updated_date';
		}

		if (payeename.trim() == '') {
			$(modal + ' .payeename').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide payee name</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (payeeaddress.trim() == '') {
			$(modal + ' .payeeaddress').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide payee address</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (payeetin.trim() == '') {
			$(modal + ' .payeetin').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide TIN number</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'payee.php',
				{ payeeSaveEdit: 'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@', source: source, id: id, payeename: payeename, payeeaddress: payeeaddress, payeetin: payeetin },
				function (data) {
					if (data.trim() == 'success') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$('#payeetable')
								.flexOptions({
									url: 'loadables/ajax/maintenance.payee.php',
									sortname: newsort,
									sortorder: 'desc',
								})
								.flexReload();
							$('#loading-img').addClass('hidden');
							$(modal).off('hidden.bs.modal');
							button.removeAttr('disabled').removeClass('disabled');
							$(modal + ' .payeename').val('');
							$(modal + ' .payeeaddress').val('');
							$(modal + ' .payeetin').val('');
						});
					} else if (data.trim() == 'codeexists') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .payeetin').focus();
						$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>TIN exists. Please provide another TIN.</div></div>");
						button.removeAttr('disabled').removeClass('disabled');
					} else {
						$('#loading-img').addClass('hidden');
						alert(data);
						button.removeAttr('disabled').removeClass('disabled');
					}
				}
			);
		}
	});

/******************************* DELETE *****************************************************/
