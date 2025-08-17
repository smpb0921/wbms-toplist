$(document)
	.off('click', '.editchartofaccountsbtn')
	.on('click', '.editchartofaccountsbtn', function () {
		var modal = '#editchartofaccountsmodal';
		$(modal + ' .code').val($(this).attr('code'));
		$(modal + ' .description').val($(this).attr('desc'));
		$(modal + ' .chartofaccountsmodalid').val($(this).attr('rowid'));
		$(modal + ' .producttype')
			.val($(this).attr('producttype'))
			.trigger('change');

		if ($(this).attr('expensetype') != null && $(this).attr('expensetypeid') > 0) {
			$(modal + ' .typeofaccount')
				.empty()
				.append('<option selected value="' + $(this).attr('expensetypeid') + '">' + $(this).attr('expensetype') + '</option>')
				.trigger('change');
		} else {
			$(modal + ' .typeofaccount')
				.empty()
				.trigger('change');
		}
	});

/***************************** SAVE | EDIT **************************************************/
$(document)
	.off('click', '.chartofaccountsmodal-savebtn:not(".disabled")')
	.on('click', '.chartofaccountsmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			code = $(modal + ' .code').val(),
			desc = $(modal + ' .description').val(),
			type = $(modal + ' .typeofaccount').val(),
			producttype = $(modal + ' .producttype').val(),
			id = '',
			newsort = 'created_date',
			source = 'add',
			button = $(this);
		button.addClass('disabled').attr('disabled', 'disabled');

		if (modal == '#editchartofaccountsmodal') {
			id = $(modal + ' .chartofaccountsmodalid').val();
			source = 'edit';
			newsort = 'updated_date';
		}

		if (code == '') {
			$(modal + ' .code').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a code.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (desc == '') {
			$(modal + ' .description').focus();
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a description.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (type == '' || type == 'null' || type == 'NULL' || type == null) {
			$(modal + ' .typeofaccount').select2('open');
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select type of account.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else if (producttype == '' || producttype == 'null' || producttype == 'NULL' || producttype == null) {
			$(modal + ' .producttype').select2('open');
			$(modal + ' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select type.</div></div>");
			button.removeAttr('disabled').removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'chart-of-accounts.php',
				{ chartofaccountsSaveEdit: 'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@', producttype: producttype, source: source, id: id, code: code, desc: desc, type: type },
				function (data) {
					if (data.trim() == 'success') {
						$(modal).modal('hide');
						$(modal).on('hidden.bs.modal', function () {
							$('#chartofaccountstable')
								.flexOptions({
									url: 'loadables/ajax/maintenance.chart-of-accounts.php',
									sortname: newsort,
									sortorder: 'desc',
								})
								.flexReload();
							$('#loading-img').addClass('hidden');
							$(modal).off('hidden.bs.modal');
							button.removeAttr('disabled').removeClass('disabled');
							$(modal + ' .code').val('');
							$(modal + ' .description').val('');
						});
					} else if (data.trim() == 'codeexists') {
						$('#loading-img').addClass('hidden');
						$(modal + ' .code').focus();
						$(modal + ' .errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Code exists. Please provide another code.</div></div>"
						);
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
