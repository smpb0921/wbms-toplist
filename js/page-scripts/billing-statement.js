var contentBLS = '#billingstatement-menutabpane';
var inputfieldsBLS = '.billingstatement-inputfields';
var processBLS = '';
var currentbillingstatementTxn = '';

var blsonchangeeventstatus = true;

function getBillingComputationBLS(txnnumber) {
	$(contentBLS + ' .billingstatement-totalbillingamount').val(0);
	var roundoffto = 2;
	$.post(server + 'billing-statement.php', { getBillingComputation: 'oiu2OI9kldp39u2o0lfknzzzo92po@k@', txnnumber: txnnumber }, function (data) {
		data = $.parseJSON(data);
		if (data['response'] == 'success') {
			$(contentBLS + ' .billingstatement-totalvatablecharges')
				.val(data['totalvatablecharges'])
				.number(true, roundoffto);
			$(contentBLS + ' .billingstatement-totalnonvatablecharges')
				.val(data['totalnonvatablecharges'])
				.number(true, roundoffto);
			$(contentBLS + ' .billingstatement-totalbillingamount')
				.val(data['totalamount'])
				.number(true, roundoffto);
			$(contentBLS + ' .billingstatement-vat')
				.val(data['vat'])
				.number(true, roundoffto);
			$(contentBLS + ' .billingstatement-cancelledamount')
				.val(data['cancelledamount'])
				.number(true, roundoffto);
			$(contentBLS + ' .billingstatement-revisedamount')
				.val(data['revisedamount'])
				.number(true, roundoffto);
		}
	});
}

function clearBillingStatementFields() {
	$(contentBLS + ' .transactionnumber').val('');
	$(contentBLS + ' #pgtxnbillingstatement-id')
		.val('')
		.attr('pgtxnbillingstatement-number', '');

	$(contentBLS + ' .billingstatement-shipperid').val('');
	$(contentBLS + ' .billingstatement-accountnumber').val('');
	$(contentBLS + ' .billingstatement-accountname').val('');
	$(contentBLS + ' .billingstatement-companyname').val('');

	$(contentBLS + ' .billingstatement-contact').val('');
	$(contentBLS + ' .billingstatement-phone').val('');
	$(contentBLS + ' .billingstatement-mobile').val('');
	$(contentBLS + ' .billingstatement-email').val('');

	$(contentBLS + ' .billingstatement-billingprovince').val('');
	$(contentBLS + ' .billingstatement-billingcity').val('');
	$(contentBLS + ' .billingstatement-billingdistrict').val('');
	$(contentBLS + ' .billingstatement-billingzipcode').val('');
	$(contentBLS + ' .billingstatement-billingstreet').val('');
	$(contentBLS + ' .billingstatement-billingcountry').val('');

	$(contentBLS + ' .billingstatement-documentdate').val('');
	$(contentBLS + ' .billingstatement-paymentduedate').val('');
	$(contentBLS + ' .billingstatement-remarks').val('');

	$(contentBLS + ' .billingstatement-createddate').val('');
	$(contentBLS + ' .billingstatement-createdby').val('');
	$(contentBLS + ' .billingstatement-updateddate').val('');
	$(contentBLS + ' .billingstatement-updatedby').val('');
	$(contentBLS + ' .billingstatement-posteddate').val('');
	$(contentBLS + ' .billingstatement-postedby').val('');
	$(contentBLS + ' .billingstatement-attention').val('');
	$(contentBLS + ' .billingstatement-invoice').val('');

	$(contentBLS + ' .billingstatement-totalbillingamount').val('');
}

function clearBLSSelectedShipperInfo(modal) {
	$(modal + ' .blscontact').val('');
	$(modal + ' .blsphone').val('');
	$(modal + ' .blsmobile').val('');
	//$(modal+' .blsfax').val('');
	$(modal + ' .blsemail').val('');

	$(modal + ' .blsprovince').val('');
	$(modal + ' .blsdistrict').val('');
	$(modal + ' .blscity').val('');
	$(modal + ' .blszipcode').val('');
	$(modal + ' .blsstreet').val('');
	$(modal + ' .blscountry').val('');
}

$(document)
	.off('change', contentBLS + ' .blsshipperselection:not(".disabled")')
	.on('change', contentBLS + ' .blsshipperselection:not(".disabled")', function () {
		if (blsonchangeeventstatus) {
			var modal = '#' + $(this).closest('.modal').attr('id');
			var shipperid = $(this).val();
			var field = $(this);
			field.addClass('disabled');

			clearBLSSelectedShipperInfo(modal);

			if (shipperid != '') {
				$.post(server + 'billing-statement.php', { getSelectedShipperInfoBLS: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', shipperid: shipperid }, function (data) {
					//alert(data);

					rsp = $.parseJSON(data);
					if (rsp['response'] == 'success') {
						$(modal + ' .blscontact').val(rsp['collectioncontact']);
						//$(modal+' .blsphone').val(rsp['phone']);
						//$(modal+' .blsemail').val(rsp['email']);
						//$(modal+' .blsmobile').val(rsp['mobile']);

						$(modal + ' .billingstatementmodal-remarks').focus();
						field.removeClass('disabled');
					} else if (rsp['response'] == 'invalidshipperid') {
						field.removeClass('disabled');
					} else {
						alert(data);
						field.removeClass('disabled');
					}
					field.removeClass('disabled');
				});
			} else {
				field.removeClass('disabled');
			}
		}
	});

$(document)
	.off('change', contentBLS + ' .blsaccountselection:not(".disabled")')
	.on('change', contentBLS + ' .blsaccountselection:not(".disabled")', function () {
		if (blsonchangeeventstatus) {
			var modal = '#' + $(this).closest('.modal').attr('id');
			var accountid = $(this).val();
			var accounttype = $(this).data('type');
			var btn = $(this);
			btn.addClass('disabled');

			if (accountid != '' && accountid != null && accountid != 'null' && accountid != 'NULL') {
				$.post(server + 'billing-statement.php', { getAccountContact: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', accountid: accountid, accounttype: accounttype }, function (data) {
					//alert(data);

					rsp = $.parseJSON(data);
					if (rsp['response'] == 'success') {
						$(modal + ' .blscontact').val(rsp['contact']);
						$(modal + ' .blsphone').val(rsp['phone']);
						$(modal + ' .blsmobile').val(rsp['mobile']);
						$(modal + ' .blsemail').val(rsp['email']);
					} else if (rsp['response'] == 'failed') {
						say(rsp['message']);
					} else {
						console.log(data);
						say('Please contact system administrator');
					}
					btn.removeClass('disabled');
				});
			} else {
				$(modal + ' .blscontact').val('');
				$(modal + ' .blsphone').val('');
				$(modal + ' .blsmobile').val('');
				$(modal + ' .blsemail').val('');
			}
		}
	});

$(document)
	.off('show.bs.modal', contentBLS + ' #blssearchwaybilltransactionmodal')
	.on('show.bs.modal', contentBLS + ' #blssearchwaybilltransactionmodal', function () {
		var shipperid = $(contentBLS + ' .billingstatement-shipperid').val();
		var agentid = $(contentBLS + ' .billingstatement-agentid').val();
		var consigneeid = $(contentBLS + ' .billingstatement-consigneeid').val();
		var shipmenttypeid = $(contentBLS + ' .billingstatement-shipmenttypeid').val();
		var billedto = $(contentBLS + ' .billingstatement-billedto').val();

		$(contentBLS + ' #blssearchwaybilltransactiontbl')
			.flexOptions({
				url: `loadables/ajax/transactions.billing-statement-waybill-lookup.php?shipperid=${shipperid}&agentid=${agentid}&consigneeid=${consigneeid}&shipmenttypeid=${shipmenttypeid}&billedto=${billedto}`,
				sortname: 'txn_waybill.waybill_number',
				sortorder: 'asc'
			})
			.flexReload();
	});

$(document)
	.off('click', contentBLS + ' #addbillingstatementmodal-savebtn:not(".disabled")')
	.on('click', contentBLS + ' #addbillingstatementmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var btn = $(this);
		btn.addClass('disabled');

		var docdate = $(modal + ' .billingstatementmodal-documentdate').val();
		var paymentduedate = $(modal + ' .billingstatementmodal-paymentduedate').val();

		var shipmenttype = $(modal + ' .billingstatementmodal-shipmenttype').val();
		var billingtype = $(modal + ' .billingstatementmodal-billingtype').val();
		var accountexecutive = $(modal + ' .billingstatementmodal-accountexecutive').val();

		var billedto = $(modal + ' .billingstatementmodal-billedto').val();
		var remarks = $(modal + ' .billingstatementmodal-remarks').val();
		var contact = $(modal + ' .blscontact').val();
		var phone = $(modal + ' .blsphone').val();
		var email = $(modal + ' .blsemail').val();
		var mobile = $(modal + ' .blsmobile').val();
		var attention = $(modal + ' .blsattention').val();
		var invoice = $(modal + ' .blsinvoice').val();
		var vatflag = $(modal + ' .blsvatflag').val();

		var agent = 'NULL';
		var shipperid = 'NULL';
		var consignee = 'NULL';
		if (billedto == 'SHIPPER') {
			shipperid = $(modal + ' .billingstatementmodal-shipper').val();
		} else if (billedto == 'AGENT') {
			agent = $(modal + ' .billingstatementmodal-agent').val();
		} else if (billedto == 'CONSIGNEE') {
			consignee = $(modal + ' .billingstatementmodal-consignee').val();
		}

		console.log(billedto + ' s ' + shipperid);
		console.log(billedto + ' a ' + agent);
		console.log(billedto + ' c ' + consignee);

		if (docdate == '') {
			$(modal + ' .billingstatementmodal-documentdate').focus();
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide statement date.</div></div>");
			btn.removeClass('disabled');
		} else if (billingtype == '' || billingtype == null || billingtype == 'NULL' || billingtype == 'null') {
			/*else if(paymentduedate==''){
    	$(modal+' .billingstatementmodal-paymentduedate').focus();
    	$(contentBLS+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide payment due date.</div></div>");
    	btn.removeClass('disabled');
    }*/
			$(modal + ' .billingstatementmodal-billingtype').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing type.</div></div>");
			btn.removeClass('disabled');
		} else if (shipmenttype == '' || shipmenttype == null || shipmenttype == 'NULL' || shipmenttype == 'null') {
			$(modal + ' .billingstatementmodal-shipmenttype').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipment type.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == '' || billedto == null || billedto == 'NULL' || billedto == 'null') {
			$(modal + ' .billingstatementmodal-billedto').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billed to.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'SHIPPER' && (shipperid == '' || shipperid == null || shipperid == 'NULL' || shipperid == 'null')) {
			$(modal + ' .billingstatementmodal-shipper').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a shipper.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'AGENT' && (agent == '' || agent == null || agent == 'NULL' || agent == 'null')) {
			$(modal + ' .billingstatementmodal-agent').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select agent.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'CONSIGNEE' && (consignee == '' || consignee == null || consignee == 'NULL' || consignee == 'null')) {
			$(modal + ' .billingstatementmodal-consignee').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select consignee.</div></div>");
			btn.removeClass('disabled');
		} else if (accountexecutive == '' || accountexecutive == null || accountexecutive == 'NULL' || accountexecutive == 'null') {
			$(modal + ' .billingstatementmodal-accountexecutive').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select account executive.</div></div>");
			btn.removeClass('disabled');
		} else {
			$.post(
				server + 'billing-statement.php',
				{
					NewBillingStatement: 'f#oifpNLEpR#nsRP9$nzpo92po@k@',
					shipmenttype: shipmenttype,
					billingtype: billingtype,
					accountexecutive: accountexecutive,
					shipperid: shipperid,
					agent: agent,
					consignee: consignee,
					billedto: billedto,
					docdate: docdate,
					paymentduedate: paymentduedate,
					remarks: remarks,
					contact: contact,
					phone: phone,
					mobile: mobile,
					email: email,
					attention: attention,
					invoice: invoice,
					vatflag: vatflag
				},
				function (data) {
					rsp = $.parseJSON(data);

					if (rsp['response'] == 'success') {
						$(modal).modal('hide');
						$(document).on('hidden.bs.modal', contentBLS + ' ' + modal, function () {
							$(document).off('hidden.bs.modal', contentBLS + ' ' + modal);
							getBillingStatementInformation(rsp['txnnumber']);

							$(modal + ' .billingstatementmodal-documentdate').val('');
							$(modal + ' .billingstatementmodal-paymentduedate').val('');
							$(modal + ' .billingstatementmodal-shipper')
								.empty()
								.trigger('change');
							$(modal + ' .billingstatementmodal-remarks').val('');
							$(modal + ' .blscontact').val('');
							$(modal + ' .blsphone').val('');
							$(modal + ' .blsemail').val('');
							$(modal + ' .blsmobile').val('');
							$(modal + ' .blsattention').val('');
							$(modal + ' .blsinvoice').val('');
							$(modal + ' .modal-errordiv').empty();
						});
					} else if (rsp['response'] == 'false') {
						$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>" + rsp['message'] + '</div></div>');
					} else {
						alert(data);
					}
					btn.removeClass('disabled');
				}
			);
		}
	});

$(document)
	.off('shown.bs.modal', contentBLS + ' #blsscanwaybilltransactionmodal')
	.on('shown.bs.modal', contentBLS + ' #blsscanwaybilltransactionmodal', function () {
		$('#blsscanwaybilltransactionmodal .modal-errordiv').empty();
		$('#blsscanwaybilltransactionmodal .blsscanwaybilltransactionmodal-waybillnumber').val('').focus();
	});

$(document)
	.off('click', contentBLS + ' #blsscanwaybilltransactionmodal-addbtn:not(".disabled")')
	.on('click', contentBLS + ' #blsscanwaybilltransactionmodal-addbtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var shipperid = $(contentBLS + ' .billingstatement-shipperid').val();
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

		var wbnumbers = [];
		var modal = '#' + $(this).closest('.modal').attr('id');
		var waybill = $(modal + ' .blsscanwaybilltransactionmodal-waybillnumber').val();

		if (waybill == '' || waybill == null || waybill == undefined || waybill == 'null' || waybill == 'NULL') {
			$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber').focus();
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide waybill number.</div></div>");
			button.removeClass('disabled');
		} else {
			wbnumbers.push(waybill);

			$.post(server + 'billing-statement.php', { insertMultipleWaybillNumber: 'oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber, wbnumber: wbnumbers }, function (data) {
				//alert(data);
				data = $.parseJSON(data);
				if (data['response'] == 'success') {
					$(modal + ' .modal-errordiv').empty();
					$(contentBLS + ' #billingstatement-detailstbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
							sortname: 'txn_billing_waybill.created_date',
							sortorder: 'desc'
						})
						.flexReload();
					getBillingComputationBLS(txnnumber);
					$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
						.val('')
						.focus();
					button.removeClass('disabled');
				} else if (data['response'] == 'invalidtxnnumber') {
					$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
						.focus()
						.select();
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid billing statement number</div></div>");
					button.removeClass('disabled');
				} else if (data['response'] == 'invalidwaybill') {
					$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
						.focus()
						.select();
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Waybill Number</div></div>");
					$(contentBLS + ' #billingstatement-detailstbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
							sortname: 'txn_billing_waybill.created_date',
							sortorder: 'desc'
						})
						.flexReload();
					button.removeClass('disabled');
				} else {
					$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
						.focus()
						.select();
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill not added</div></div>");
					button.removeClass('disabled');
				}
			});
		}
	});

$(document)
	.off('keyup', contentBLS + ' .blsscanwaybilltransactionmodal-waybillnumber:not(".disabled")')
	.on('keyup', contentBLS + ' .blsscanwaybilltransactionmodal-waybillnumber:not(".disabled")', function (e) {
		var keycode = e.keycode || e.which;
		if (keycode == 13) {
			var button = $(this);
			button.addClass('disabled');
			var shipperid = $(contentBLS + ' .billingstatement-shipperid').val();
			var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
			var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

			var wbnumbers = [];
			var modal = '#' + $(this).closest('.modal').attr('id');
			var waybill = $(modal + ' .blsscanwaybilltransactionmodal-waybillnumber').val();

			if (waybill == '' || waybill == null || waybill == undefined || waybill == 'null' || waybill == 'NULL') {
				$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber').focus();
				$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide waybill number.</div></div>");
				button.removeClass('disabled');
			} else {
				wbnumbers.push(waybill);

				$.post(server + 'billing-statement.php', { insertMultipleWaybillNumber: 'oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber, wbnumber: wbnumbers }, function (data) {
					//alert(data);
					data = $.parseJSON(data);
					if (data['response'] == 'success') {
						$(modal + ' .modal-errordiv').empty();
						$(contentBLS + ' #billingstatement-detailstbl')
							.flexOptions({
								url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
								sortname: 'txn_billing_waybill.created_date',
								sortorder: 'desc'
							})
							.flexReload();
						getBillingComputationBLS(txnnumber);
						$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
							.val('')
							.focus();
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidtxnnumber') {
						$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
							.focus()
							.select();
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid billing statement number</div></div>");
						button.removeClass('disabled');
					} else if (data['response'] == 'invalidwaybill') {
						$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
							.focus()
							.select();
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Waybill Number</div></div>");
						$(contentBLS + ' #billingstatement-detailstbl')
							.flexOptions({
								url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
								sortname: 'txn_billing_waybill.created_date',
								sortorder: 'desc'
							})
							.flexReload();
						button.removeClass('disabled');
					} else {
						$(modal + ' .blsscanwaybilltransactionmodal-waybillnumber')
							.focus()
							.select();
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill not added</div></div>");
						button.removeClass('disabled');
					}
				});
			}
		}
	});

$(document)
	.off('click', contentBLS + ' .blswaybilllookup-addbtn:not(".disabled")')
	.on('click', contentBLS + ' .blswaybilllookup-addbtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var shipperid = $(contentBLS + ' .billingstatement-shipperid').val();
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

		var wbnumbers = [];
		var modal = '#' + $(this).closest('.modal').attr('id');
		var selectedwaybills = $(modal + ' .blswaybilllookup-checkbox:checked').length;

		if (parseInt(selectedwaybills) > 0) {
			$(modal + ' .blswaybilllookup-checkbox:checked').each(function () {
				wbnumbers.push($(this).attr('waybillnumber'));
			});

			$.post(server + 'billing-statement.php', { insertMultipleWaybillNumber: 'oihh#p@0fldpe3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber, wbnumber: wbnumbers }, function (data) {
				//alert(data);
				data = $.parseJSON(data);
				if (data['response'] == 'success') {
					$(modal).modal('hide');
					$(modal)
						.off('hidden.bs.modal')
						.on('hidden.bs.modal', function () {
							$(modal).off('hidden.bs.modal');
							button.removeClass('disabled');
							$(contentBLS + ' #billingstatement-detailstbl')
								.flexOptions({
									url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
									sortname: 'txn_billing_waybill.created_date',
									sortorder: 'desc'
								})
								.flexReload();
							$(contentBLS + ' #blssearchwaybilltransactiontbl')
								.flexOptions({
									url: 'loadables/ajax/transactions.billing-statement-waybill-lookup.php?shipperid=' + shipperid,
									sortname: 'txn_waybill.waybill_number',
									sortorder: 'asc'
								})
								.flexReload();
							//$(contentBLS+' .billingstatement-totalbillingamount').val(data['billingtotal']).number(true,4);
							getBillingComputationBLS(txnnumber);
						});
				} else if (data['response'] == 'invalidtxnnumber') {
					say('Invalid Billing Number. <br> Billing Number: ' + txnnumber);
					button.removeClass('disabled');
				} else if (data['response'] == 'invalidwaybill') {
					say(
						'Unable to add the following Waybill(s): ' +
							data['details'] +
							'<br><br>Reasons:<br>- Invalid waybill number<br>- In another billing transaction<br>- Already billed<br>- Waybill does not belong to selected shipper<br>- POD Required for selected shipper'
					);
					$(contentBLS + ' #billingstatement-detailstbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
							sortname: 'txn_billing_waybill.created_date',
							sortorder: 'desc'
						})
						.flexReload();
					$(contentBLS + ' #blssearchwaybilltransactiontbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.billing-statement-waybill-lookup.php?shipperid=' + shipperid,
							sortname: 'txn_waybill.waybill_number',
							sortorder: 'asc'
						})
						.flexReload();
					button.removeClass('disabled');
				} else {
					button.removeClass('disabled');
				}
			});
		} else {
			say('Please select waybills to be added.');
			button.removeClass('disabled');
		}
	});

/******************* VIEWING *****************************/
function getBillingStatementInformation(txnnumber) {
	processBLS = '';
	//$(contentBLS+' .topbuttonswrapper .button-group').addClass('hidden');

	$.post(server + 'billing-statement.php', { getBillingStatementData: 'foiRFN#@!pspR#1NEi34smo1sonk&$', txnnumber: txnnumber }, function (response) {
		//alert(response);
		if (response.trim() == 'INVALID') {
			clearBillingStatementFields();
			$(contentBLS + ' .statusdiv').html('<br>');
			$(contentBLS + ' #pgtxnbillingstatement-id')
				.val('')
				.removeAttr('pgtxnbillingstatement-number');
			$(contentBLS + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='billingstatement-trans-newbtn' data-toggle='modal' href='#addbillingstatementmodal'><img src='../resources/img/add.png'></div></div></div>"
			);
			currentloadplanTxn = '';

			$(contentBLS + ' #billingstatement-detailstbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=',
					sortname: 'txn_billing_waybill.waybill_number',
					sortorder: 'asc'
				})
				.flexReload();

			$(contentBLS + ' .blsaddwaybillbtn').addClass('hidden');
			$(contentBLS + ' .blsdeletewaybillbtn').addClass('hidden');
			$(contentBLS + ' .blswaybilllookupbtn').addClass('hidden');
			$(contentBLS + ' .blsscanwaybillbtn').addClass('hidden');
			$(contentBLS + ' .blsuploadwaybillbtn').addClass('hidden');

			userAccess();
		} else {
			currentbillingstatementTxn = txnnumber;
			data = $.parseJSON(response);

			$(contentBLS + ' .transactionnumber').val(txnnumber);
			$(contentBLS + ' #pgtxnbillingstatement-id')
				.val(data['id'])
				.attr('pgtxnbillingstatement-number', txnnumber);
			$(contentBLS + ' .statusdiv').text(data['status']);

			$(contentBLS + ' .billingstatement-agentid').val(data['agentid']);
			$(contentBLS + ' .billingstatement-consigneeid').val(data['consigneeid']);
			$(contentBLS + ' .billingstatement-shipmenttypeid').val(data['shipmenttypeid']);

			$(contentBLS + ' .billingstatement-shipperid').val(data['shipperid']);
			$(contentBLS + ' .billingstatement-accountnumber').val(data['accountnumber']);
			$(contentBLS + ' .billingstatement-accountname').val(data['accountname']);
			$(contentBLS + ' .billingstatement-companyname').val(data['companyname']);

			$(contentBLS + ' .billingstatement-billedto').val(data['billedto']);

			$(contentBLS + ' .billingstatement-contact').val(data['contact']);
			$(contentBLS + ' .billingstatement-phone').val(data['phone']);
			$(contentBLS + ' .billingstatement-mobile').val(data['mobile']);
			$(contentBLS + ' .billingstatement-email').val(data['email']);

			$(contentBLS + ' .billingstatement-billingprovince').val(data['billprovince']);
			$(contentBLS + ' .billingstatement-billingcity').val(data['billcity']);
			$(contentBLS + ' .billingstatement-billingdistrict').val(data['billdistrict']);
			$(contentBLS + ' .billingstatement-billingzipcode').val(data['billzipcode']);
			$(contentBLS + ' .billingstatement-billingstreet').val(data['billstreet']);
			$(contentBLS + ' .billingstatement-billingcountry').val(data['billcountry']);

			$(contentBLS + ' .billingstatement-documentdate').val(data['documentdate']);
			$(contentBLS + ' .billingstatement-paymentduedate').val(data['paymentduedate']);
			$(contentBLS + ' .billingstatement-remarks').val(data['remarks']);

			$(contentBLS + ' .billingstatement-createddate').val(data['createddate']);
			$(contentBLS + ' .billingstatement-createdby').val(data['createdby']);
			$(contentBLS + ' .billingstatement-receiveddate').val(data['receiveddate']);
			$(contentBLS + ' .billingstatement-receivedby').val(data['receivedby']);
			$(contentBLS + ' .billingstatement-updateddate').val(data['updateddate']);
			$(contentBLS + ' .billingstatement-updatedby').val(data['updatedby']);
			$(contentBLS + ' .billingstatement-posteddate').val(data['posteddate']);
			$(contentBLS + ' .billingstatement-postedby').val(data['postedby']);
			$(contentBLS + ' .billingstatement-reason').val(data['reason']);
			$(contentBLS + ' .billingstatement-attention').val(data['attention']);
			$(contentBLS + ' .billingstatement-invoice').val(data['invoice']);
			$(contentBLS + ' .billingstatement-vatflag').val(data['vatflag']);

			$(contentBLS + ' .billingstatement-shipmenttype').val(data['shipmenttype']);
			$(contentBLS + ' .billingstatement-billingtype').val(data['billingtype']);
			$(contentBLS + ' .billingstatement-accountexecutive').val(data['accountexecutive']);

			//$(contentBLS+" .billingstatement-paidflag").val(data["paidflagstr"]).attr("paidflagbool",data["paidflag"]);

			if (data['paidflagstr'] == 'YES') {
				$(contentBLS + ' .paidflagdiv')
					.html('PAID')
					.addClass('greenfld')
					.removeClass('redfld');
			} else {
				$(contentBLS + ' .paidflagdiv')
					.html('UNPAID')
					.addClass('redfld')
					.removeClass('greenfld');
			}

			//$(contentBLS+' .billingstatement-totalbillingamount').val(data["totalamount"]).number(true,4);
			getBillingComputationBLS(txnnumber);

			var allowothertrans = '';
			var paidflagbtn = '';
			var voidbtn = '';
			if (data['voidtxnflag'] == 'true') {
				voidbtn = "<div class='button-group-btn active' title='Void' id='billingstatement-trans-voidbtn'><img src='../resources/img/block.png'></div>";
			}
			if (data['status'] == 'LOGGED') {
				if (data['hasaccess'] == 'true') {
					allowothertrans =
						"<div class='button-group-btn active' title='Edit' id='billingstatement-trans-editbtn'><img src='../resources/img/edit.png'></div><div class='button-group-btn active' title='Post' id='billingstatement-trans-postbtn'><img src='../resources/img/post.png'></div>";
				}
				$(contentBLS + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='billingstatement-trans-newbtn' data-toggle='modal' href='#addbillingstatementmodal'><img src='../resources/img/add.png'></div>" +
						voidbtn +
						allowothertrans +
						"<div class='button-group-btn active' title='Print' id='billingstatement-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentBLS + ' .blsaddwaybillbtn').removeClass('hidden');
				$(contentBLS + ' .blsdeletewaybillbtn').removeClass('hidden');
				$(contentBLS + ' .blswaybilllookupbtn').removeClass('hidden');
				$(contentBLS + ' .blsscanwaybillbtn').removeClass('hidden');
				$(contentBLS + ' .blsuploadwaybillbtn').removeClass('hidden');
			} else if (data['status'] == 'POSTED') {
				if (data['paidflagbtn'] == 'true') {
					paidflagbtn = "<div class='button-group-btn active' title='Payment Tagging' id='billingstatement-trans-paymenttaggingbtn'><img src='../resources/img/payment.png'></div>";
				}

				if (data['hasaccess'] == 'true') {
					allowothertrans = "<div class='button-group-btn active' title='Edit' id='billingstatement-trans-editbtn'><img src='../resources/img/edit.png'></div>";
				}

				if (data['unpostaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Unpost' id='billingstatement-trans-unpostbtn'><img src='../resources/img/unpost.png'></div>";
				}

				$(contentBLS + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='billingstatement-trans-newbtn' data-toggle='modal' href='#addbillingstatementmodal'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						voidbtn +
						paidflagbtn +
						"<div class='button-group-btn active' title='Print' id='billingstatement-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentBLS + ' .blsaddwaybillbtn').addClass('hidden');
				$(contentBLS + ' .blsdeletewaybillbtn').addClass('hidden');
				$(contentBLS + ' .blswaybilllookupbtn').addClass('hidden');
				$(contentBLS + ' .blsscanwaybillbtn').addClass('hidden');
				$(contentBLS + ' .blsuploadwaybillbtn').addClass('hidden');
			} else {
				$(contentBLS + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='billingstatement-trans-newbtn' data-toggle='modal' href='#addbillingstatementmodal'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						"<div class='button-group-btn active' title='Print' id='billingstatement-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentBLS + ' .blsaddwaybillbtn').addClass('hidden');
				$(contentBLS + ' .blsdeletewaybillbtn').addClass('hidden');
				$(contentBLS + ' .blswaybilllookupbtn').addClass('hidden');
				$(contentBLS + ' .blsscanwaybillbtn').addClass('hidden');
				$(contentBLS + ' .blsuploadwaybillbtn').addClass('hidden');
			}

			$(contentBLS + ' #billingstatement-detailstbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.billing-statement-waybill.php?reference=' + txnnumber,
					sortname: 'txn_billing_waybill.waybill_number',
					sortorder: 'asc'
				})
				.flexReload();

			/*$(contentBLS+' #blssearchwaybilltransactiontbl').flexOptions({
											url:'loadables/ajax/transactions.billing-statement-waybill-lookup.php?shipperid='+data["shipperid"],
											sortname: 'txn_waybill.waybill_number',
											sortorder: "asc"
			}).flexReload();*/
		}
		$('#loading-img').addClass('hidden');
	});
}

$(document)
	.off('click', contentBLS + " .firstprevnextlastbtn button:not('.disabled')")
	.on('click', contentBLS + " .firstprevnextlastbtn button:not('.disabled')", function () {
		$('#loading-img').removeClass('hidden');
		var source = $(this).data('info'),
			id = $('#pgtxnbillingstatement-id').val(),
			button = $(this);
		button.addClass('disabled');

		$.post(server + 'billing-statement.php', { getReference: 'FOio5ja3op2a2lK@3#4hh$93s', source: source, id: id }, function (data) {
			if (data.trim() == 'N/A') {
				$('#loading-img').addClass('hidden');
				getBillingStatementInformation('');
			} else {
				getBillingStatementInformation(data.trim());
			}
			setTimeout(function () {
				button.removeClass('disabled');
			}, 200);
		});
	});

$(document)
	.off('keyup', contentBLS + ' .transactionnumber')
	.on('keyup', contentBLS + ' .transactionnumber', function (e) {
		e.preventDefault();
		$('#loading-img').removeClass('hidden');
		var key = e.which || e.keycode,
			txnnumber = $(this).val();
		if (key == '13') {
			getBillingStatementInformation(txnnumber);
		} else {
			$('#loading-img').addClass('hidden');
		}
	});

$(document)
	.off('click', contentBLS + ' #billingstatement-trans-postbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatement-trans-postbtn:not(".disabled")', function () {
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val(),
			txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number'),
			button = $(this);
		button.addClass('disabled');

		$.confirm({
			animation: 'bottom',
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce: 1,
			title: 'Post Billing Transaction [' + txnnumber + ']',
			content: 'Do you want to continue?',
			confirmButton: 'Confirm',
			cancelButton: 'Cancel',
			confirmButtonClass: 'btn-oceanblue',
			cancelButtonClass: 'btn-royalblue',
			theme: 'white',

			confirm: function () {
				$('#loading-img').removeClass('hidden');
				$.post(server + 'billing-statement.php', { postTransaction: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', txnid: txnid, txnnumber: txnnumber }, function (data) {
					if (data.trim() == 'success') {
						getBillingStatementInformation(txnnumber);
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data.trim() == 'invalidtransaction') {
						say('Unable to post Billing transaction. Invalid Billing ID/No. ID: ' + txnid + ' - Billing No.: ' + txnnumber);
						getBillingStatementInformation(txnnumber);
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data.trim() == 'nowaybillsadded') {
						say('Unable to post Billing transaction. No waybill transactions added.');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else {
						alert(data);
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					}
				});
			},
			cancel: function () {
				button.removeClass('disabled');
			}
		});
	});
/******************* VIEWING *****************************/

$(document)
	.off('click', contentBLS + ' #billingstatement-trans-unpostbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatement-trans-unpostbtn:not(".disabled")', function () {
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val(),
			txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number'),
			button = $(this);
		button.addClass('disabled');

		$.confirm({
			animation: 'bottom',
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce: 1,
			title: 'Unpost Billing Transaction [' + txnnumber + ']',
			content: '<span style="color:red">Reason*</span><br><textarea class="form-control" id="blsunpostreasonfld" rows="8"></textarea>',
			confirmButton: 'Confirm',
			cancelButton: 'Cancel',
			confirmButtonClass: 'btn-oceanblue',
			cancelButtonClass: 'btn-royalblue',
			theme: 'white',

			confirm: function () {
				var reason = $('#blsunpostreasonfld').val();

				if (reason.trim() == '') {
					say('Please provde reason for unposting');
					button.removeClass('disabled');
				} else {
					$('#loading-img').removeClass('hidden');
					$.post(server + 'billing-statement.php', { unpostTransaction: 'dferDi$nsFpo94dnels$4sRoi809srbmouS@1!', txnid: txnid, txnnumber: txnnumber, reason: reason }, function (data) {
						if (data.trim() == 'success') {
							getBillingStatementInformation(txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else if (data.trim() == 'invalidtransaction') {
							say('Unable to post Billing transaction. Invalid Billing ID/No. ID: ' + txnid + ' - Billing No.: ' + txnnumber);
							getBillingStatementInformation(txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else if (data.trim() == 'notposted') {
							say('Unable to unpost transaction with status that is not POSTED.');
							getBillingStatementInformation(txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else if (data.trim() == 'nopermission') {
							say('Unable to unpost transaction. No user permission.');
							getBillingStatementInformation(txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else {
							alert(data);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
					});
				}
			},
			cancel: function () {
				button.removeClass('disabled');
			}
		});
	});

/************************* VOID BTN ***********************/
$(document)
	.off('click', contentBLS + ' #billingstatement-trans-voidbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatement-trans-voidbtn:not(".disabled")', function () {
		var modal = '#voidbillingstatementtransactionmodal';
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

		$(contentBLS + ' #voidbillingstatementtransactionmodal-id').val(txnid);
		$(contentBLS + ' .voidbillingstatementtransactionmodal-txnnumber').val(txnnumber);

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentBLS + ' .voidbillingstatementtransactionmodal-remarks').focus();
			});
	});

$(document)
	.off('click', contentBLS + ' #voidbillingstatementtransactionmodal-savebtn:not(".disabled")')
	.on('click', contentBLS + ' #voidbillingstatementtransactionmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');
		var remarks = $(modal + ' .voidbillingstatementtransactionmodal-remarks').val();
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for cancellation.</div></div>");
			$(modal + ' .voidbillingstatementtransactionmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Void Billing Transaction [' + txnnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(server + 'billing-statement.php', { voidTransaction: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', txnid: txnid, txnnumber: txnnumber, remarks: remarks }, function (data) {
						if (data.trim() == 'success') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #voidbillingstatementtransactionmodal-id').val('');
									$(modal + ' .voidbillingstatementtransactionmodal-txnnumber').val('');
									$(modal + ' .voidbillingstatementtransactionmodal-remarks').val('');

									getBillingStatementInformation(txnnumber);
									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data.trim() == 'invalidtransaction') {
							say('Unable to void Billing transaction. Invalid Billing ID/No. ID: ' + txnid + ' - Billing No.: ' + txnnumber);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else {
							alert(data);
							button.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						}
					});
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

/********************** VOID BTN - END ********************/

/************************* PRINTING *****************************************/
$(document)
	.off('click', contentBLS + ' #billingstatement-trans-printbtn')
	.on('click', contentBLS + ' #billingstatement-trans-printbtn', function () {
		var modal = '#billingstatementprintingmodal';
		$(modal).modal('show');

		// Initialize the select2 dropdown when modal is shown
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(modal + ' .billingstatementprintingmodal-formtype').select2();
			});
	});

$(document)
	.off('click', contentBLS + ' #billingstatementprintingmodal-printbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatementprintingmodal-printbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var formtype = $(modal + ' .billingstatementprintingmodal-formtype').val();
		var txnnumber = $('#pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');
		var title = 'Print Preview [' + txnnumber + ']';
		var tabid = txnnumber;
		var printSource = '';

		if (formtype === 'SERVICEINVOICE') {
			printSource = 'printouts/transactions/service-invoice.php';
		} else if (formtype === 'AWB') {
			printSource = 'printouts/transactions/billing-statement-awb.php';
		} else {
			say('Please select a form type');
			return;
		}

		$(modal).modal('hide');

		if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
			$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
			$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
		}

		$('#loading-img').removeClass('hidden');
		$('.content').animate({ scrollTop: 0 }, 300);

		$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
		$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
		$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load('Printouts/print-preview.php?source=' + printSource + '?txnnumber=' + tabid + '&reference=' + tabid);

		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 400);
	});
/************************* PRINTING - END *****************************************/

$(document)
	.off('shown.bs.modal', contentBLS + ' #addbillingstatementmodal')
	.on('shown.bs.modal', contentBLS + ' #addbillingstatementmodal', function () {
		var modal = '#addbillingstatementmodal';

		var date = $(modal + ' .billingstatementmodal-documentdate').val();

		if (date == '') {
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!
			var yyyy = today.getFullYear();

			if (dd < 10) {
				dd = '0' + dd;
			}

			if (mm < 10) {
				mm = '0' + mm;
			}
			today = mm + '/' + dd + '/' + yyyy;

			$(modal + ' .billingstatementmodal-documentdate').val(today);
		}
	});

$(document)
	.off('click', contentBLS + ' #billingstatement-trans-editbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatement-trans-editbtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var modal = '#editbillingstatementmodal';
		var billingid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		blsonchangeeventstatus = false;

		$.post(server + 'billing-statement.php', { editBillingGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: billingid }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(modal).modal('show');
				$(modal).on('shown.bs.modal', function () {
					if (rsp['shipmenttype'] != null) {
						$(modal + ' .billingstatementmodal-shipmenttype')
							.empty()
							.append('<option selected value="' + rsp['shipmenttypeid'] + '">' + rsp['shipmenttype'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-shipmenttype')
							.empty()
							.trigger('change');
					}

					if (rsp['shipper'] != null) {
						$(modal + ' .billingstatementmodal-shipper')
							.empty()
							.append('<option selected value="' + rsp['shipperid'] + '">' + rsp['shipper'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-shippper')
							.empty()
							.trigger('change');
					}

					if (rsp['agent'] != null) {
						$(modal + ' .billingstatementmodal-agent')
							.empty()
							.append('<option selected value="' + rsp['agentid'] + '">' + rsp['agent'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-agent')
							.empty()
							.trigger('change');
					}

					if (rsp['consignee'] != null) {
						$(modal + ' .billingstatementmodal-consignee')
							.empty()
							.append('<option selected value="' + rsp['consigneeid'] + '">' + rsp['consignee'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-consignee')
							.empty()
							.trigger('change');
					}
					$(modal + ' .billingstatementmodal-documentdate').val(rsp['documentdate']);
					$(modal + ' .billingstatementmodal-paymentduedate').val(rsp['paymentduedate']);
					$(modal + ' .billingstatementmodal-remarks').val(rsp['remarks']);
					$(modal + ' .billingstatementmodal-contact').val(rsp['contact']);
					$(modal + ' .billingstatementmodal-phone').val(rsp['phone']);
					$(modal + ' .billingstatementmodal-mobile').val(rsp['mobile']);
					$(modal + ' .billingstatementmodal-email').val(rsp['email']);
					$(modal + ' .billingstatementmodal-street').val(rsp['street']);
					$(modal + ' .billingstatementmodal-attention').val(rsp['attention']);
					$(modal + ' .billingstatementmodal-invoice').val(rsp['invoice']);
					$(modal + ' .billingstatementmodal-vatflag').val(rsp['vatflag']);
					//$(modal+' .billingstatementmodal-shipperstr').val(rsp['shipper']).attr('EkPCXShipEOID',rsp['shipperid']);

					if (rsp['billingtype'] != null) {
						$(modal + ' .billingstatementmodal-billingtype')
							.empty()
							.append('<option selected value="' + rsp['billingtypeid'] + '">' + rsp['billingtype'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-billingtype')
							.empty()
							.trigger('change');
					}

					if (rsp['accountexecutive'] != null) {
						$(modal + ' .billingstatementmodal-accountexecutive')
							.empty()
							.append('<option selected value="' + rsp['accountexecutiveid'] + '">' + rsp['accountexecutive'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-accountexecutive')
							.empty()
							.trigger('change');
					}

					if (rsp['province'] != null) {
						$(modal + ' .billingstatementmodal-province')
							.empty()
							.append('<option selected value="' + rsp['province'] + '">' + rsp['province'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-province')
							.empty()
							.trigger('change');
					}

					if (rsp['city'] != null) {
						$(modal + ' .billingstatementmodal-city')
							.empty()
							.append('<option selected value="' + rsp['city'] + '">' + rsp['city'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-city')
							.empty()
							.trigger('change');
					}

					if (rsp['district'] != null) {
						$(modal + ' .billingstatementmodal-district')
							.empty()
							.append('<option selected value="' + rsp['district'] + '">' + rsp['district'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-district')
							.empty()
							.trigger('change');
					}

					if (rsp['zipcode'] != null) {
						$(modal + ' .billingstatementmodal-zipcode')
							.empty()
							.append('<option selected value="' + rsp['zipcode'] + '">' + rsp['zipcode'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-zipcode')
							.empty()
							.trigger('change');
					}

					if (rsp['country'] != null) {
						$(modal + ' .billingstatementmodal-country')
							.empty()
							.append('<option selected value="' + rsp['country'] + '">' + rsp['country'] + '</option>')
							.trigger('change');
					} else {
						$(modal + ' .billingstatementmodal-country')
							.empty()
							.trigger('change');
					}

					blsOnBilledToUpdate(modal, rsp['billedto']);
					blsOnShipmentTypeUpdate(modal, rsp['shipmenttype']);

					$(modal + ' .billingstatementmodal-billedto')
						.val(rsp['billedto'])
						.trigger('change');

					$(modal).off('shown.bs.modal');
					button.removeClass('disabled');
					blsonchangeeventstatus = true;
				});
			} else {
				/*$(modal).modal('hide');
			$(modal).on('hidden.bs.modal',function(){
				$(modal).off('hidden.bs.modal');
				say('Unable to load data. Invalid ID.');
			});*/
				say('Unable to load data. Invalid ID.');
				button.removeClass('disabled');
			}
		});
	});

function blsOnShipmentTypeUpdate(modal, shipmenttype) {
	$(modal + ' .shipperwrapper').addClass('hidden');
	$(modal + ' .agentwrapper').addClass('hidden');
	$(modal + ' .consigneewrapper').addClass('hidden');
	$(modal + ' .billingstatementmodal-billedto')
		.empty()
		.trigger('change');

	// if (blsonchangeeventstatus) {
	if (shipmenttype == 'DOMESTIC') {
		$(modal + ' .shipperwrapper').removeClass('hidden');

		$(modal + ' .billingstatementmodal-billedto')
			.append("<option value='SHIPPER'>SHIPPER</option>")
			.val('SHIPPER')
			.trigger('change');
		$(modal + ' .billingstatementmodal-billedto').attr('disabled', true);
	} else if (shipmenttype == 'INTERNATIONAL - EXPORT') {
		$(modal + ' .shipperwrapper').removeClass('hidden');

		$(modal + ' .billingstatementmodal-billedto')
			.append("<option value='AGENT'>AGENT</option><option value='SHIPPER'>SHIPPER</option>")
			.trigger('change');
		$(modal + ' .billingstatementmodal-billedto').removeAttr('disabled');
	} else {
		$(modal + ' .billingstatementmodal-billedto')
			.append("<option value='AGENT'>AGENT</option><option value='CONSIGNEE'>CONSIGNEE</option>")
			.trigger('change');
		$(modal + ' .billingstatementmodal-billedto').removeAttr('disabled');
	}
	// }
}

$(document)
	.off('change', contentBLS + ' .blsshipmenttypeselection:not(".disabled")')
	.on('change', contentBLS + ' .blsshipmenttypeselection:not(".disabled")', function () {
		const modal = '#' + $(this).closest('.modal').attr('id');
		const shipmenttype = $(this).find('option:selected').text();

		blsOnShipmentTypeUpdate(modal, shipmenttype);
	});

function blsOnBilledToUpdate(modal, billedto) {
	$(modal + ' .shipperwrapper').addClass('hidden');
	$(modal + ' .agentwrapper').addClass('hidden');
	$(modal + ' .consigneewrapper').addClass('hidden');

	// if (blsonchangeeventstatus) {
	if (billedto == 'SHIPPER') {
		$(modal + ' .shipperwrapper').removeClass('hidden');
	} else if (billedto == 'AGENT') {
		$(modal + ' .agentwrapper').removeClass('hidden');
	} else if (billedto == 'CONSIGNEE') {
		$(modal + ' .consigneewrapper').removeClass('hidden');
	}
	// }
}

$(document)
	.off('change', contentBLS + ' .blsbilledtoselection:not(".disabled")')
	.on('change', contentBLS + ' .blsbilledtoselection:not(".disabled")', function () {
		const modal = '#' + $(this).closest('.modal').attr('id');
		const billedto = $(this).val();
		blsOnBilledToUpdate(modal, billedto);
	});

$(document)
	.off('click', contentBLS + ' #editbillingstatementmodal-savebtn:not(".disabled")')
	.on('click', contentBLS + ' #editbillingstatementmodal-savebtn:not(".disabled")', function () {
		$(modal + ' .modal-errordiv').empty();

		var modal = '#' + $(this).closest('.modal').attr('id');
		var btn = $(this);
		btn.addClass('disabled');

		var billingid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var billingnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');
		var docdate = $(modal + ' .billingstatementmodal-documentdate').val();
		var paymentduedate = $(modal + ' .billingstatementmodal-paymentduedate').val();
		var remarks = $(modal + ' .billingstatementmodal-remarks').val();
		var contact = $(modal + ' .billingstatementmodal-contact').val();
		var phone = $(modal + ' .billingstatementmodal-phone').val();
		var mobile = $(modal + ' .billingstatementmodal-mobile').val();
		var email = $(modal + ' .billingstatementmodal-email').val();
		var attention = $(modal + ' .billingstatementmodal-attention').val();
		var invoice = $(modal + ' .billingstatementmodal-invoice').val();
		var vatflag = $(modal + ' .billingstatementmodal-vatflag').val();

		var shipperid = $(modal + ' .billingstatementmodal-shipper').val();
		var shipmenttype = $(modal + ' .billingstatementmodal-shipmenttype').val();
		var billingtype = $(modal + ' .billingstatementmodal-billingtype').val();
		var accountexecutive = $(modal + ' .billingstatementmodal-accountexecutive').val();

		var billedto = $(modal + ' .billingstatementmodal-billedto').val();
		var agent = $(modal + ' .billingstatementmodal-agent').val();
		var consignee = $(modal + ' .billingstatementmodal-consignee').val();

		var province = $(modal + ' .billingstatementmodal-province').val();
		var city = $(modal + ' .billingstatementmodal-city').val();
		var district = $(modal + ' .billingstatementmodal-district').val();
		var zipcode = $(modal + ' .billingstatementmodal-zipcode').val();
		var street = $(modal + ' .billingstatementmodal-street').val();
		var country = $(modal + ' .billingstatementmodal-country').val();

		var country = $(modal + ' .billingstatementmodal-country').val();
		var country = $(modal + ' .billingstatementmodal-country').val();
		var country = $(modal + ' .billingstatementmodal-country').val();

		if (docdate == '') {
			$(modal + ' .billingstatementmodal-documentdate').focus();
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide statement date.</div></div>");
			btn.removeClass('disabled');
		} else if (shipmenttype == '' || shipmenttype == null || shipmenttype == 'NULL' || shipmenttype == 'null') {
			$(modal + ' .billingstatementmodal-shipmenttype').select2('open');
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipment type.</div></div>");
			btn.removeClass('disabled');
		} else if (billingtype == '' || billingtype == null || billingtype == 'NULL' || billingtype == 'null') {
			$(modal + ' .billingstatementmodal-billingtype').select2('open');
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing type.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'SHIPPER' && (shipperid == '' || shipperid == null || shipperid == 'NULL' || shipperid == 'null')) {
			$(modal + ' .billingstatementmodal-shipper').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a shipper.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'AGENT' && (agent == '' || agent == null || agent == 'NULL' || agent == 'null')) {
			$(modal + ' .billingstatementmodal-agent').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select agent.</div></div>");
			btn.removeClass('disabled');
		} else if (billedto == 'CONSIGNEE' && (consignee == '' || consignee == null || consignee == 'NULL' || consignee == 'null')) {
			$(modal + ' .billingstatementmodal-consignee').select2('open');
			$(contentBLS + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select consignee.</div></div>");
			btn.removeClass('disabled');
		} else if (accountexecutive == '' || accountexecutive == null || accountexecutive == 'NULL' || accountexecutive == 'null') {
			$(modal + ' .billingstatementmodal-accountexecutive').select2('open');
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select account executive.</div></div>");
			btn.removeClass('disabled');
		} else {
			/*else if(paymentduedate==''){
    	$(modal+' .billingstatementmodal-paymentduedate').focus();
    	$(contentBLS+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide payment due date.</div></div>");
    	btn.removeClass('disabled');
    }*/
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'billing-statement.php',
				{
					updateBillingStatement: 'kjoI$H2oiaah3h0$09jDppo92po@k@',
					id: billingid,
					shipperid: shipperid,
					shipmenttype: shipmenttype,
					billingtype: billingtype,
					accountexecutive: accountexecutive,
					billingnumber: billingnumber,
					docdate: docdate,
					paymentduedate: paymentduedate,
					remarks: remarks,
					contact: contact,
					phone: phone,
					mobile: mobile,
					email: email,
					province: province,
					city: city,
					district: district,
					zipcode: zipcode,
					street: street,
					country: country,
					attention: attention,
					invoice: invoice,
					vatflag: vatflag,
					consignee: consignee,
					agent: agent,
					billedto: billedto
				},
				function (data) {
					//alert(data);
					rsp = $.parseJSON(data);
					if (rsp['response'] == 'success') {
						$(modal).modal('hide');
						$(document).on('hidden.bs.modal', contentBLS + ' ' + modal, function () {
							$(document).off('hidden.bs.modal', contentBLS + ' ' + modal);
							btn.removeClass('disabled');
							getBillingStatementInformation(billingnumber);
							$(modal + ' .modal-errordiv').empty();
							$('#loading-img').addClass('hidden');
						});
					} else if (rsp['response'] == 'invaliddocdate') {
						$(modal + ' .billingstatementmodal-documentdate').focus();
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid document date.</div></div>");
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'invalidpaymentduedate') {
						$(modal + ' .billingstatementmodal-paymentduedate').focus();
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid payment due date.</div></div>");
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'invalidbilling') {
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Billing Statement. Please refresh page.</div></div>"
						);
						btn.removeClass('disabled');
					} else if (rsp['response'] == 'noprovinceprovided') {
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing state/province.</div></div>"
						);
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'nocityprovided') {
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing city.</div></div>");
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'nodistrictprovided') {
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing barangay/district.</div></div>"
						);
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'nozipcodeprovided') {
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing zip code.</div></div>");
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'nostreetprovided') {
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide billing street address.</div></div>"
						);
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (rsp['response'] == 'nocountryprovided') {
						$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing country.</div></div>");
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else {
						alert(data);
						btn.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					}
				}
			);
		}
	});

/************************* SEARCHING ***********************************/

$(document)
	.off('dblclick', contentBLS + ' .billingstatementsearchrow')
	.on('dblclick', contentBLS + ' .billingstatementsearchrow', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var txnnumber = $(this).attr('billingnumber');
		$(modal).modal('hide');
		$(document)
			.off('hidden.bs.modal', modal)
			.on('hidden.bs.modal', modal, function () {
				$(document).off('hidden.bs.modal', modal);
				getBillingStatementInformation(txnnumber);
			});
	});

function searchBillingStatementLookup(modal) {
	var status = $(modal + ' .billingstatementsearch-status').val(),
		paidflag = $(modal + ' .billingstatementsearch-paidflag').val(),
		shipper = $(modal + ' .billingstatementsearch-shipper').val(),
		waybillnumber = $(modal + ' .billingstatementsearch-waybillnumber')
			.val()
			.replace(/\s/g, '%20'),
		bsnumber = $(modal + ' .billingstatementsearch-bsnumber')
			.val()
			.replace(/\s/g, '%20'),
		docdatefrom = $(modal + ' .billingstatementsearch-docdatefrom').val(),
		docdateto = $(modal + ' .billingstatementsearch-docdateto').val();

	$(contentBLS + ' #billingstatementsearch-table')
		.flexOptions({
			url:
				'loadables/ajax/transactions.billing-statement-search.php?status=' +
				status +
				'&shipper=' +
				shipper +
				'&waybillnumber=' +
				waybillnumber +
				'&bsnumber=' +
				bsnumber +
				'&docdatefrom=' +
				docdatefrom +
				'&docdateto=' +
				docdateto +
				'&paidflag=' +
				paidflag,
			sortname: 'billing_number',
			sortorder: 'asc'
		})
		.flexReload();
}

$(document).on('keyup', contentBLS + ' #billingstatement-searchmodallookup .billingstatementsearch-waybillnumber', function (e) {
	var key = e.which || e.keycode;
	if (key == '13') {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchBillingStatementLookup(modal);
	}
});

$(document)
	.off('click', contentBLS + ' #billingstatementsearch-searchbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatementsearch-searchbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchBillingStatementLookup(modal);
	});

/************************** SEARCHING - END ********************************/

/************************* TOGGLE PAID FLAG BTN ***********************/

$(document)
	.off('click', contentBLS + ' #billingstatement-trans-paymenttaggingbtn:not(".disabled")')
	.on('click', contentBLS + ' #billingstatement-trans-paymenttaggingbtn:not(".disabled")', function () {
		var modal = '#togglepaidflagmodal';
		var paidflag = $(contentBLS + ' .billingstatement-paidflag').attr('paidflagbool');
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

		$(contentBLS + ' #togglepaidflagmodal-id').val(txnid);
		$(contentBLS + ' .togglepaidflagmodal-txnnumber').val(txnnumber);
		$(contentBLS + ' .togglepaidflagmodal-paidflag')
			.val(paidflag)
			.trigger('change');

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentBLS + ' .togglepaidflagmodal-remarks').focus();
			});
	});

$(document)
	.off('click', contentBLS + ' #togglepaidflagmodal-savebtn:not(".disabled")')
	.on('click', contentBLS + ' #togglepaidflagmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var txnid = $(contentBLS + ' #pgtxnbillingstatement-id').val();
		var txnnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');
		var remarks = $(modal + ' .togglepaidflagmodal-remarks').val();
		var paidflag = $(modal + ' .togglepaidflagmodal-paidflag').val();

		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (paidflag == 0 && remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide remarks.</div></div>");
			$(modal + ' .togglepaidflagmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Paid Flag for [' + txnnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'billing-statement.php',
						{ changePaymentFlagging: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', txnid: txnid, txnnumber: txnnumber, remarks: remarks, paidflag: paidflag },
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										$(modal + ' #togglepaidflagmodal-id').val('');
										$(modal + ' .togglepaidflagmodal-txnnumber').val('');
										$(modal + ' .togglepaidflagmodal-paidflag')
											.val(0)
											.trigger('change');
										$(modal + ' .togglepaidflagmodal-remarks').val('');

										getBillingStatementInformation(txnnumber);
										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidtransaction') {
								say('Unable to change Paid Flag. Invalid Transaction ID/No.: ' + txnid + ' - Billing No.: ' + txnnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else {
								alert(data);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							}
						}
					);
				},
				cancel: function () {
					button.removeClass('disabled');
				}
			});
		}
	});

/********************** TOGGLE PAID FLAG BTN - END ********************/

$(document)
	.off('click', contentBLS + ' .blsuploadwaybillbtn')
	.on('click', contentBLS + ' .blsuploadwaybillbtn', function () {
		var modal = '#blsuploadwaybillmodal';
		var billingnumber = $(contentBLS + ' #pgtxnbillingstatement-id').attr('pgtxnbillingstatement-number');

		$(modal + ' .blsuploadwaybillmodal-billingnumber').val(billingnumber);
		$(modal + ' .blswaybilluploadbillingnumber').val(billingnumber);

		$(modal).modal('show');
	});

$(document)
	.off('click', contentBLS + ' #blsuploadwaybillmodal-uploadbtn:not(".disabled")')
	.on('click', contentBLS + ' #blsuploadwaybillmodal-uploadbtn:not(".disabled")', function () {
		var modal = '#blsuploadwaybillmodal';
		var modal2 = '#blsuploadwaybilllogmodal';
		var form = '#blsuploadwaybillmodal-form';
		var button = $(this);
		button.addClass('disabled');

		var billingnumber = $(modal + ' .blswaybilluploadbillingnumber').val();

		if (
			$(contentBLS + ' ' + modal + ' .blsuploadwaybillmodal-file')
				.val()
				.trim() == ''
		) {
			say('Please select a file to upload');
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$(modal).modal('hide');
			$(document)
				.off('hidden.bs.modal', contentBLS + ' ' + modal)
				.on('hidden.bs.modal', contentBLS + ' ' + modal, function () {
					$(document).off('hidden.bs.modal', contentBLS + ' ' + modal);
					$(contentBLS + ' ' + modal2).modal('show');
					$(form).submit();

					$('#billingstatementuploadtransactionlogframe').load(function () {
						button.removeClass('disabled');

						$('#loading-img').addClass('hidden');
						getBillingStatementInformation(billingnumber);

						$(this)
							.contents()
							.find('#touploadsuccessbtn')
							.off('click')
							.on('click', function () {
								$(this).contents().find('#touploadsuccessbtn').off('click');

								/*var to = $(this).attr('tonumber');
					$('#transfer-order-touploadlog-modal').modal('hide');
					$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal').on('hidden.bs.modal','#transfer-order-touploadlog-modal',function(){
						$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal');
						getToDetails(to);
					});*/
							});
					});
				});
		}
	});
