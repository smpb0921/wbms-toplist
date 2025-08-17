var contentMFT = '#manifest-menutabpane';
var inputfieldsMFT = '.manifest-inputfields';
var processMFT = '';
var currentmanifestTxn = '';

function clearMFTNewFields() {
	$(contentMFT + ' .newmanifestmodal-documentdate').val('');
	$(contentMFT + ' .newmanifestmodal-loadplannumber')
		.empty()
		.trigger('change')
		.removeClass('disabled');
	$(contentMFT + ' .newmanifestmodal-remarks').val('');
	$(contentMFT + ' .newmanifestmodal-truckername')
		.empty()
		.trigger('change');
	$(contentMFT + ' .newmanifestmodal-trucktype')
		.empty()
		.trigger('change');
	$(contentMFT + ' .newmanifestmodal-drivername').val('');
	$(contentMFT + ' .newmanifestmodal-platenumber').val('');
	$(contentMFT + ' .newmanifestmodal-contactnumber').val('');

	$(contentMFT + ' .newmanifestmodal-agent')
		.empty()
		.trigger('change');
	$(contentMFT + ' .newmanifestmodal-modeoftransport')
		.empty()
		.trigger('change');
	$(contentMFT + ' .newmanifestmodal-mawbbl').val('');
	$(contentMFT + ' .newmanifestmodal-etd').val('');
	$(contentMFT + ' .newmanifestmodal-eta').val('');
}

function clearMFTEditFields() {
	$(contentMFT + ' .editmanifestmodal-documentdate').val('');
	$(contentMFT + ' .editmanifestmodal-loadplannumber')
		.empty()
		.trigger('change');
	$(contentMFT + ' .editmanifestmodal-remarks').val('');
	$(contentMFT + ' .editmanifestmodal-truckername')
		.empty()
		.trigger('change');
	$(contentMFT + ' .editmanifestmodal-trucktype')
		.empty()
		.trigger('change');
	$(contentMFT + ' .editmanifestmodal-drivername').val('');
	$(contentMFT + ' .editmanifestmodal-platenumber').val('');
	$(contentMFT + ' .editmanifestmodal-contactnumber').val('');
}

function clearAllMFT() {
	//alert('sdfsd');
}

$(document)
	.off('change', contentMFT + ' .editmanifestmodal-loadplanflag:not(".disabled")')
	.on('change', contentMFT + ' .editmanifestmodal-loadplanflag:not(".disabled")', function () {
		var flag = $(this).val();
		if (flag == 1) {
			$(contentMFT + ' .mftloadplanfieldwrapper').removeClass('hidden');
			$(contentMFT + ' .mftnoloadplanfieldwrapper').addClass('hidden');
		} else {
			$(contentMFT + ' .mftloadplanfieldwrapper').addClass('hidden');
			$(contentMFT + ' .mftnoloadplanfieldwrapper').removeClass('hidden');
		}
	});

$(document)
	.off('change', contentMFT + ' .newmanifestmodal-loadplanflag:not(".disabled")')
	.on('change', contentMFT + ' .newmanifestmodal-loadplanflag:not(".disabled")', function () {
		var flag = $(this).val();
		if (flag == 1) {
			$(contentMFT + ' .mftloadplanfieldwrapper').removeClass('hidden');
			$(contentMFT + ' .mftnoloadplanfieldwrapper').addClass('hidden');
		} else {
			$(contentMFT + ' .mftloadplanfieldwrapper').addClass('hidden');
			$(contentMFT + ' .mftnoloadplanfieldwrapper').removeClass('hidden');
		}
	});

$(document)
	.off('change', contentMFT + ' .newmanifestmodal-loadplannumber:not(".disabled")')
	.on('change', contentMFT + ' .newmanifestmodal-loadplannumber:not(".disabled")', function () {
		var element = $(this);
		var ldpnumber = $(this).val();

		//$(this).addClass('disabled');

		$.post(server + 'manifest.php', { getLoadPlanDetails: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', ldpnumber: ldpnumber }, function (data) {
			data = $.parseJSON(data);

			if (data['carrier'] != null) {
				$(contentMFT + ' .newmanifestmodal-truckername')
					.empty()
					.append('<option selected value="' + data['carrierid'] + '">' + data['carrier'] + '</option>')
					.trigger('change');
			} else {
				$(contentMFT + ' .newmanifestmodal-truckername')
					.empty()
					.trigger('change');
			}

			if (data['vehicletype'] != null) {
				$(contentMFT + ' .newmanifestmodal-trucktype')
					.empty()
					.append('<option selected value="' + data['vehicletypeid'] + '">' + data['vehicletype'] + '</option>')
					.trigger('change');
			} else {
				$(contentMFT + ' .newmanifestmodal-trucktype')
					.empty()
					.trigger('change');
			}

			$(contentMFT + ' .newmanifestmodal-documentdate').val(data['datenow']);
			$(contentMFT + ' .newmanifestmodal-platenumber').focus();

			element.removeClass('disabled');
		});
	});

$(document)
	.off('click', contentMFT + ' #newmanifestmodal-savebtn:not(".disabled")')
	.on('click', contentMFT + ' #newmanifestmodal-savebtn:not(".disabled")', function () {
		var remarks = $(contentMFT + ' .newmanifestmodal-remarks').val();
		var documentdate = $(contentMFT + ' .newmanifestmodal-documentdate').val();
		var loadplannumber = $(contentMFT + ' .newmanifestmodal-loadplannumber').val();
		var truckername = $(contentMFT + ' .newmanifestmodal-truckername').val();
		var trucktype = $(contentMFT + ' .newmanifestmodal-trucktype').val();
		var platenumber = $(contentMFT + ' .newmanifestmodal-platenumber').val();
		var drivername = $(contentMFT + ' .newmanifestmodal-driver').val();
		var contactnumber = $(contentMFT + ' .newmanifestmodal-contactnumber').val();
		var loadplanflag = $(contentMFT + ' .newmanifestmodal-loadplanflag').val();
		var location = $(contentMFT + ' .newmanifestmodal-location').val();
		var origin = $(contentMFT + ' .newmanifestmodal-origin').val();
		var modeoftransport = $(contentMFT + ' .newmanifestmodal-modeoftransport').val();
		var agent = $(contentMFT + ' .newmanifestmodal-agent').val();
		var etd = $(contentMFT + ' .newmanifestmodal-etd').val();
		var eta = $(contentMFT + ' .newmanifestmodal-eta').val();
		var mawbl = $(contentMFT + ' .newmanifestmodal-mawbbl').val();

		var modal = '#' + $(this).closest('.modal').attr('id');
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (loadplanflag == 1) {
			location = 'NULL';
			origin = 'NULL';
			modeoftransport = 'NULL';
			agent = 'NULL';
			etd = 'NULL';
			eta = 'NULL';
			mawbl = 'NULL';
		} else {
			loadplannumber = 'NULL';
		}

		if (loadplanflag == 1 && (loadplannumber == '' || loadplannumber == 'NULL' || loadplannumber == 'null' || loadplannumber == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select load plan number.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (location == '' || location == 'NULL' || location == 'null' || location == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select location.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (origin == '' || origin == 'NULL' || origin == 'null' || origin == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (modeoftransport == '' || modeoftransport == 'NULL' || modeoftransport == 'null' || modeoftransport == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (agent == '' || agent == 'NULL' || agent == 'null' || agent == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select agent.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && mawbl.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide mawbl.</div></div>");
			button.removeClass('disabled');
		} else if (documentdate.trim() == '' || checkDateFormat(documentdate) != 1) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valid document date.</div></div>");
			button.removeClass('disabled');
		} else if (truckername == '' || truckername == 'NULL' || truckername == 'null' || truckername == null) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide trucker name.</div></div>");
			button.removeClass('disabled');
		} else if (trucktype == '' || trucktype == 'NULL' || trucktype == 'null' || trucktype == null) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide truck type.</div></div>");
			button.removeClass('disabled');
		} else if (platenumber.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide plate number.</div></div>");
			button.removeClass('disabled');
		} else if (drivername == '' || drivername == 'NULL' || drivername == 'null' || drivername == null) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select driver.</div></div>");
			button.removeClass('disabled');
		} else if (contactnumber.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide contact number.</div></div>");
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'manifest.php',
				{
					saveNewManifestTransaction: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
					remarks: remarks,
					documentdate: documentdate,
					loadplannumber: loadplannumber,
					truckername: truckername,
					trucktype: trucktype,
					platenumber: platenumber,
					drivername: drivername,
					contactnumber: contactnumber,
					loadplanflag: loadplanflag,
					location: location,
					origin: origin,
					modeoftransport: modeoftransport,
					agent: agent,
					eta: eta,
					etd: etd,
					mawbl: mawbl
				},
				function (data) {
					/*alert(data);
			$('#loading-img').addClass('hidden');
				button.removeClass('disabled');*/

					data = $.parseJSON(data);
					if (data['response'] == 'success') {
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
						$(modal).modal('hide');
						clearMFTNewFields();
						$(document)
							.off('hidden.bs.modal', modal)
							.on('hidden.bs.modal', modal, function () {
								getManifestInformation(data['txnnumber']);
								$(document).off('hidden.bs.modal', modal);
							});
					} else if (data['response'] == 'invaliddocdate') {
						say('Invalid document date');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data['response'] == 'invalidetd') {
						say('Invalid estimated time of departure');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data['response'] == 'invalideta') {
						say('Invalid estimated time of arrival');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data['response'] == 'loadplanhasmanifest') {
						say('Selected load plan number has corresponding manifest transaction [' + data['manifestnumber'] + ']');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else if (data['response'] == 'invalidloadplanstatus') {
						say('Unable to create manifest transaction for [' + loadplannumber + ']. Load Plan transaction not POSTED.');
						button.removeClass('disabled');
						$('#loading-img').addClass('hidden');
					} else {
						alert(data);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					}
				}
			);
		}
	});

function getWaybillCountInLDP(manifest) {
	//var manifest = $(contentMFT+' #pgtxnmanifest-id').attr('pgtxnmanifest-number');
	//var loadplan = $(contentMFT+' .manifest-loadplannumber').val();

	$.post(server + 'manifest.php', { getWaybillCount: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', manifest: manifest }, function (data) {
		//alert(data);
		data = $.parseJSON(data);
		$(contentMFT + ' .manifest-waybillcountinloadplan').val(data['waybillcount']);
	});
}

/**************************** VIEWING ******************************/
function getManifestInformation(txnnumber) {
	processMFT = '';

	$.post(server + 'manifest.php', { getManifestData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber }, function (response) {
		//alert(response);
		if (response.trim() == 'INVALID') {
			clearAllMFT();
			$(contentMFT + ' .statusdiv').html('<br>');
			$(contentMFT + ' #pgtxnmanifest-id')
				.val('')
				.removeAttr('pgtxnmanifest-number', '');
			$(contentMFT + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='manifest-trans-newbtn' data-toggle='modal' href='#newmanifestmodal'><img src='../resources/img/add.png'></div></div></div>"
			);
			currentmanifestTxn = '';

			$(contentMFT + ' #manifest-waybilltbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.manifest-waybill.php?reference=',
					sortname: 'waybill_number',
					sortorder: 'asc'
				})
				.flexReload();

			$(contentMFT + ' #manifest-packagecodetbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=',
					sortname: 'waybill_number, package_code',
					sortorder: 'asc'
				})
				.flexReload();

			userAccess();
		} else {
			currentmanifestTxn = txnnumber;
			data = $.parseJSON(response);

			getWaybillCountInLDP(txnnumber);

			$(contentMFT + ' .transactionnumber').val(txnnumber);
			$(contentMFT + ' #pgtxnmanifest-id')
				.val(data['id'])
				.attr('pgtxnmanifest-number', txnnumber);
			$(contentMFT + ' .statusdiv').text(data['status']);

			$(contentMFT + ' .manifest-location').val(data['location']);
			//$(contentMFT+" .manifest-carrier").val(data["carrier"]);
			$(contentMFT + ' .manifest-origin').val(data['origin']);
			$(contentMFT + ' .manifest-destination').val(data['destination']);
			$(contentMFT + ' .manifest-modeoftransport').val(data['modeoftransport']);
			$(contentMFT + ' .manifest-agent').val(data['agent']);
			$(contentMFT + ' .manifest-agentaddress').val(data['agentaddress']);
			$(contentMFT + ' .manifest-agentcontact').val(data['agentcontact']);
			$(contentMFT + ' .manifest-remarks').val(data['remarks']);

			$(contentMFT + ' .manifest-loadplannumber').val(data['loadplannumber']);
			$(contentMFT + ' .manifest-mawbbl').val(data['mawbbl']);

			$(contentMFT + ' .manifest-truckername').val(data['truckername']);
			$(contentMFT + ' .manifest-trucktype').val(data['trucktype']);
			$(contentMFT + ' .manifest-platenumber').val(data['platenumber']);
			$(contentMFT + ' .manifest-drivername').val(data['drivername']);
			$(contentMFT + ' .manifest-contactnumber').val(data['contactnumber']);

			$(contentMFT + ' .manifest-documentdate').val(data['documentdate']);
			$(contentMFT + ' .manifest-etd').val(data['etd']);
			$(contentMFT + ' .manifest-eta').val(data['eta']);
			$(contentMFT + ' .manifest-statusupdateremarks').val(data['statusupdateremarks']);

			$(contentMFT + ' .manifest-createddate').val(data['createddate']);
			$(contentMFT + ' .manifest-createdby').val(data['createdby']);
			$(contentMFT + ' .manifest-updateddate').val(data['updateddate']);
			$(contentMFT + ' .manifest-updatedby').val(data['updatedby']);

			$(contentMFT + ' .manifest-statusupdateremarks').val(data['statusupdateremarks']);

			var allowothertrans = '';
			if (data['status'] == 'LOGGED') {
				if (data['hasaccess'] == 'true') {
					allowothertrans =
						"<div class='button-group-btn active' title='Edit' id='manifest-trans-editbtn'><img src='../resources/img/edit.png'></div><div class='button-group-btn active' title='Void' id='manifest-trans-voidbtn'><img src='../resources/img/block.png'></div><div class='button-group-btn active' title='Post' id='manifest-trans-postbtn'><img src='../resources/img/post.png'></div>";
				}
				$(contentMFT + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='manifest-trans-newbtn' data-toggle='modal' href='#newmanifestmodal'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						"<div class='button-group-btn active' title='Print' id='manifest-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentMFT + ' .mftaddwaybillbtn').removeClass('hidden');
				$(contentMFT + ' .mftdeletewaybillbtn').removeClass('hidden');
				$(contentMFT + ' .mftaddpackagebtn').removeClass('hidden');
				$(contentMFT + ' .mftdeletepackagebtn').removeClass('hidden');
			} else if (data['status'] != 'LOGGED' && data['status'] != 'VOID') {
				$(contentMFT + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='manifest-trans-newbtn' data-toggle='modal' href='#newmanifestmodal'><img src='../resources/img/add.png'></div><div class='button-group-btn active' title='Update Status' id='manifest-trans-updatestatusbtn'><img src='../resources/img/update-status.png'></div><div class='button-group-btn active' title='Print' id='manifest-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentMFT + ' .mftaddwaybillbtn').addClass('hidden');
				$(contentMFT + ' .mftdeletewaybillbtn').addClass('hidden');
				$(contentMFT + ' .mftaddpackagebtn').addClass('hidden');
				$(contentMFT + ' .mftdeletepackagebtn').addClass('hidden');
			} else {
				$(contentMFT + ' .topbuttonswrapper .button-group').html(
					"<div class='button-group-btn active' title='New' id='manifest-trans-newbtn' data-toggle='modal' href='#newmanifestmodal'><img src='../resources/img/add.png'></div>" +
						allowothertrans +
						"<div class='button-group-btn active' title='Print' id='manifest-trans-printbtn'><img src='../resources/img/print.png'></div>"
				);
				userAccess();

				$(contentMFT + ' .mftaddwaybillbtn').addClass('hidden');
				$(contentMFT + ' .mftdeletewaybillbtn').addClass('hidden');
				$(contentMFT + ' .mftaddpackagebtn').addClass('hidden');
				$(contentMFT + ' .mftdeletepackagebtn').addClass('hidden');
			}

			$(contentMFT + ' #manifest-waybilltbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' + txnnumber,
					sortname: 'waybill_number',
					sortorder: 'asc'
				})
				.flexReload();

			$(contentMFT + ' #manifest-packagecodetbl')
				.flexOptions({
					url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' + txnnumber,
					sortname: 'waybill_number, package_code',
					sortorder: 'asc'
				})
				.flexReload();
		}
		$('#loading-img').addClass('hidden');
	});
}

$(document)
	.off('click', contentMFT + " .firstprevnextlastbtn button:not('.disabled')")
	.on('click', contentMFT + " .firstprevnextlastbtn button:not('.disabled')", function () {
		$('#loading-img').removeClass('hidden');
		var source = $(this).data('info'),
			id = $('#pgtxnmanifest-id').val(),
			button = $(this);
		button.addClass('disabled');

		$.post(server + 'manifest.php', { getReference: 'FOio5ja3op2a2lK@3#4hh$93s', source: source, id: id }, function (data) {
			if (data.trim() == 'N/A') {
				$('#loading-img').addClass('hidden');
				getManifestInformation('');
			} else {
				getManifestInformation(data.trim());
			}
			setTimeout(function () {
				button.removeClass('disabled');
			}, 200);
		});
	});

$(document)
	.off('keyup', contentMFT + ' .transactionnumber')
	.on('keyup', contentMFT + ' .transactionnumber', function (e) {
		e.preventDefault();
		$('#loading-img').removeClass('hidden');
		var key = e.which || e.keycode,
			wbmnumber = $(this).val();
		if (key == '13') {
			getManifestInformation(wbmnumber);
		} else {
			$('#loading-img').addClass('hidden');
		}
	});

/**************************** VIEWING ******************************/

/*************************** WAYBILL ******************************/
$(document)
	.off('shown.bs.modal', contentMFT + ' #mftaddwaybillnumbermodal')
	.on('shown.bs.modal', contentMFT + ' #mftaddwaybillnumbermodal', function () {
		$(this).find(' .mftaddwaybillnumbermodal-waybillnumber').val('').focus();
		$(this).find(' .modal-errordiv').empty();
	});

function MFTinsertnewwaybill(modal, scantype, pouchsize) {
	var mftnumber = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');
	var wbnumber = $(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber').val();
	$(modal + ' .modal-errordiv').empty();

	if (pouchsize == '' || pouchsize == 'null' || pouchsize == undefined || pouchsize == 'NULL' || pouchsize == null) {
		$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size.</div></div>");
		$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-pouchsize').select2('open');
	} else if (wbnumber.trim() == '') {
		$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter waybill number.</div></div>");
		$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber').focus();
	} else {
		$.post(
			server + 'manifest.php',
			{ insertNewWaybillNumber: 'dskljouioU#ouh$3ksk#Op1NEi34smo1sonk&$', mftnumber: mftnumber, wbnumber: wbnumber, scantype: scantype, pouchsize: pouchsize },
			function (data) {
				if (data.trim() == 'success') {
					$(contentMFT + ' #manifest-waybilltbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' + mftnumber,
							sortname: 'created_date',
							sortorder: 'desc'
						})
						.flexReload();

					$(contentMFT + ' #manifest-packagecodetbl')
						.flexOptions({
							url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' + mftnumber,
							sortname: 'waybill_number, package_code',
							sortorder: 'asc'
						})
						.flexReload();

					getWaybillCountInLDP(mftnumber);

					$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
						.val('')
						.focus();
				} else if (data.trim() == 'invalidmanifestnumber') {
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid manifest number.</div></div>");
				} else if (data.trim() == 'invalidwaybill') {
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid waybill number.</div></div>");
					$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
						.val('')
						.focus();
				} else if (data.trim() == 'invalidpouchsize') {
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid pouch size.</div></div>");
					$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-pouchsize').select2('open');
				} else if (data.trim() == 'alreadyadded') {
					$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Waybill number already added.</div></div>");
					$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
						.val('')
						.focus();
				} else if (data.trim() == 'pouchsizenotmatched') {
					$(modal + ' .modal-errordiv').html(
						"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Pouch size in waybill transaction is different from selected pouch size.</div></div>"
					);
					$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
						.val('')
						.focus();
				} else {
					var rp = data.split('@#&');
					resp = rp[0];
					if (resp.trim() == 'hasactivemanifest') {
						var pendingmft = rp[1];
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Already added in another manifest transaction. [" + pendingmft + ']</div></div>'
						);
						$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
							.val('')
							.focus();
					} else if (resp.trim() == 'hasfinalstatus') {
						var finalwbstatus = rp[1];
						$(modal + ' .modal-errordiv').html(
							"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Unable to add transaction. Waybill already updated to final status. [" +
								wbnumber +
								'=>' +
								finalwbstatus +
								']</div></div>'
						);
						$(contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
							.val('')
							.focus();
					} else {
						alert(data);
					}
				}
			}
		);
	}
}

$(document)
	.off('click', contentMFT + ' #mftaddwaybillnumbermodal-addbtn')
	.on('click', contentMFT + ' #mftaddwaybillnumbermodal-addbtn', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var scantype = $(modal + ' .mftaddwaybillnumbermodal-scantype').val();
		var pouchsize = $(modal + ' .mftaddwaybillnumbermodal-pouchsize').val();
		MFTinsertnewwaybill(modal, scantype, pouchsize);
	});

$(document)
	.off('keyup', contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber')
	.on('keyup', contentMFT + ' #mftaddwaybillnumbermodal .mftaddwaybillnumbermodal-waybillnumber', function (e) {
		var key = e.keycode || e.which;
		if (key == 13) {
			var modal = '#' + $(this).closest('.modal').attr('id');
			var scantype = $(modal + ' .mftaddwaybillnumbermodal-scantype').val();
			var pouchsize = $(modal + ' .mftaddwaybillnumbermodal-pouchsize').val();
			MFTinsertnewwaybill(modal, scantype, pouchsize);
		}
	});
/************************* WAYBILL - END ****************************/

/************* POST BTN *********************/
$(document)
	.off('click', contentMFT + ' #manifest-trans-postbtn:not(".disabled")')
	.on('click', contentMFT + ' #manifest-trans-postbtn:not(".disabled")', function () {
		var id = $(contentMFT + ' #pgtxnmanifest-id').val(),
			txnnumber = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number'),
			button = $(this);
		button.addClass('disabled');

		var warningmessage = '';

		$.post(server + 'manifest.php', { getWaybillCount: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', manifest: txnnumber }, function (response) {
			data = $.parseJSON(response);

			if ((data['allowpostingincompletewaybill'] == 1 && data['allowpostingincompletepackage'] == 1) || (data['completewaybill'] == true && data['completepackage'] == true)) {
				if (data['completewaybill'] == true && data['completepackage'] == true) {
				} else if (data['completewaybill'] == false) {
					warningmessage = "<br><br><b><i style='color:#cc4242'>Warning: There are missing waybill transaction(s)</i></b><br><br>";
				} else if (data['completepackage'] == false) {
					warningmessage = "<br><br><b><i style='color:#cc4242'>Warning: There are missing waybill package(s)</i></b><br><br>";
				} else {
					say(response);
					button.removeClass('disabled');
				}

				$.confirm({
					animation: 'bottom',
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce: 1,
					title: 'Post Transaction',
					content: 'Posting Manifest Transaction[' + txnnumber + '].' + warningmessage + ' Do you want to continue?',
					confirmButton: 'Confirm',
					cancelButton: 'Cancel',
					confirmButtonClass: 'btn-oceanblue',
					cancelButtonClass: 'btn-royalblue',
					theme: 'white',

					confirm: function () {
						$('#loading-img').removeClass('hidden');

						$.post(server + 'manifest.php', { postTransaction: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk', id: id, txnnumber: txnnumber }, function (data) {
							rp = $.parseJSON(data);
							if (rp['response'] == 'success') {
								$('#loading-img').addClass('hidden');
								loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='manifest-menutabpane']", 'transactions/manifest.php?reference=' + txnnumber);
								button.removeClass('disabled');
							} else if (rp['response'] == 'noaccess') {
								say('No permission to post manifest transaction [' + $txnnumber + '].');
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else if (rp['response'] == 'nowaybilladded') {
								say('Please add waybill transaction(s).');
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else if (rp['response'] == 'alreadyposted') {
								say('Waybill transaction [' + txnnumber + '] is already posted.');
								getWaybillInformation(txnnumber);
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else if (rp['response'] == 'invalidtransaction') {
								say('Unable to post transaction. Invalid manifest number [' + $txnnumber + '].');
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else if (rp['response'] == 'nodriver') {
								say('Unable to post transaction. No driver provided.');
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else if (rp['response'] == 'incompletepackages') {
								say('<i>Unable to post transaction.</i><br>Incomplete package codes for the waybill transaction(s):<br>' + rp['details']);
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							} else {
								alert(data);
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
							}
						});
					},
					cancel: function () {
						button.removeClass('disabled').addClass('active');
					}
				});
			} else if (data['allowpostingincompletepackage'] != 1) {
				say('<i>Unable to post manifest transaction.</i><br><br>Incomplete package code scanned. Please add missing waybill package(s).');
				button.removeClass('disabled');
			} else if (data['allowpostingincompletewaybill'] != 1) {
				say('<i>Unable to post manifest transaction.</i><br><br>Incomplete waybill scanned. Please add missing waybill transaction(s).');
				button.removeClass('disabled');
			}
		});
	});
/************* POST BTN - END *****************/

/************************* VOID BTN ***********************/
$(document)
	.off('click', contentMFT + ' #manifest-trans-voidbtn:not(".disabled")')
	.on('click', contentMFT + ' #manifest-trans-voidbtn:not(".disabled")', function () {
		var modal = '#voidmanifesttransactionmodal';
		var txnid = $(contentMFT + ' #pgtxnmanifest-id').val();
		var txnnumber = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');

		$(contentMFT + ' #voidmanifesttransactionmodal-id').val(txnid);
		$(contentMFT + ' .voidmanifesttransactionmodal-txnnumber').val(txnnumber);

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentMFT + ' .voidmanifesttransactionmodal-remarks').focus();
			});
	});

$(document)
	.off('click', contentMFT + ' #voidmanifesttransactionmodal-savebtn:not(".disabled")')
	.on('click', contentMFT + ' #voidmanifesttransactionmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var txnid = $(contentMFT + ' #pgtxnmanifest-id').val();
		var txnnumber = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');
		var remarks = $(modal + ' .voidmanifesttransactionmodal-remarks').val();
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for cancellation.</div></div>");
			$(modal + ' .voidmanifesttransactionmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Void Manifest [' + txnnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(server + 'manifest.php', { voidTransaction: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', txnid: txnid, txnnumber: txnnumber, remarks: remarks }, function (data) {
						if (data.trim() == 'success') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);

									$(modal + ' #voidmanifesttransactionmodal-id').val('');
									$(modal + ' .voidmanifesttransactionmodal-txnnumber').val('');
									$(modal + ' .voidmanifesttransactionmodal-remarks').val('');

									getManifestInformation(txnnumber);
									button.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data.trim() == 'invalidtransaction') {
							say('Unable to void manifest transaction. Invalid Manifest ID/No. ID: ' + txnid + ' - Manifest No.: ' + txnnumber);
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

$(document)
	.off('click', contentMFT + ' #manifest-trans-editbtn')
	.on('click', contentMFT + ' #manifest-trans-editbtn', function () {
		var modal = '#editmanifestmodal';
		var rowid = $(contentMFT + ' #pgtxnmanifest-id').val();

		$.post(server + 'manifest.php', { ManifestGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: rowid }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(modal + ' .editmanifestmodal-documentdate').val(rsp['documentdate']);
				$(modal + ' .editmanifestmodal-remarks').val(rsp['remarks']);
				//$(modal+' .editmanifestmodal-truckername').val(rsp['truckername']);
				//$(modal+' .editmanifestmodal-trucktype').val(rsp['trucktype']);
				$(modal + ' .editmanifestmodal-platenumber').val(rsp['platenumber']);
				//$(modal + ' .editmanifestmodal-drivername').val(rsp['drivername']);
				$(modal + ' .editmanifestmodal-contactnumber').val(rsp['contactnumber']);
				$(modal + ' .editmanifestmodal-eta').val(rsp['eta']);
				$(modal + ' .editmanifestmodal-etd').val(rsp['etd']);
				$(modal + ' .editmanifestmodal-mawbbl').val(rsp['mawbl']);
				$(modal + ' .editmanifestmodal-loadplanflag')
					.val(rsp['loadplanflag'])
					.trigger('change');

				if (rsp['loadplannumber'] != null) {
					$(modal + ' .editmanifestmodal-loadplannumber')
						.empty()
						.append('<option selected value="' + rsp['loadplannumber'] + '">' + rsp['loadplannumber'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-loadplannumber')
						.empty()
						.trigger('change');
				}

				if (rsp['carrier'] != null) {
					$(modal + ' .editmanifestmodal-truckername')
						.empty()
						.append('<option selected value="' + rsp['truckername'] + '">' + rsp['carrier'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-truckername')
						.empty()
						.trigger('change');
				}

				if (rsp['vehicletype'] != null) {
					$(modal + ' .editmanifestmodal-trucktype')
						.empty()
						.append('<option selected value="' + rsp['trucktype'] + '">' + rsp['vehicletype'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-trucktype')
						.empty()
						.trigger('change');
				}

				if (rsp['drivername'] != null) {
					$(modal + ' .editmanifestmodal-driver')
						.empty()
						.append('<option selected value="' + rsp['drivername'] + '">' + rsp['drivername'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-driver')
						.empty()
						.trigger('change');
				}

				if (rsp['location'] != null) {
					$(modal + ' .editmanifestmodal-location')
						.empty()
						.append('<option selected value="' + rsp['locationid'] + '">' + rsp['location'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-location')
						.empty()
						.trigger('change');
				}

				if (rsp['origin'] != null) {
					$(modal + ' .editmanifestmodal-origin')
						.empty()
						.append('<option selected value="' + rsp['originid'] + '">' + rsp['origin'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-origin')
						.empty()
						.trigger('change');
				}

				if (rsp['modeoftransport'] != null) {
					$(modal + ' .editmanifestmodal-modeoftransport')
						.empty()
						.append('<option selected value="' + rsp['modeoftransportid'] + '">' + rsp['modeoftransport'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-modeoftransport')
						.empty()
						.trigger('change');
				}

				if (rsp['agent'] != null) {
					$(modal + ' .editmanifestmodal-agent')
						.empty()
						.append('<option selected value="' + rsp['agentid'] + '">' + rsp['agent'] + '</option>')
						.trigger('change');
				} else {
					$(modal + ' .editmanifestmodal-agent')
						.empty()
						.trigger('change');
				}

				$(modal).modal('show');
			} else {
				$(modal).modal('hide');
				$(modal).on('hidden.bs.modal', function () {
					$(modal).off('hidden.bs.modal');
					say('Unable to load data. Invalid ID.');
				});
			}
		});
	});

$(document)
	.off('click', contentMFT + ' #editmanifestmodal-savebtn:not(".disabled")')
	.on('click', contentMFT + ' #editmanifestmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var remarks = $(modal + ' .editmanifestmodal-remarks').val();
		var documentdate = $(modal + ' .editmanifestmodal-documentdate').val();
		var loadplannumber = $(modal + ' .editmanifestmodal-loadplannumber').val();
		var truckername = $(contentMFT + ' .editmanifestmodal-truckername').val();
		var trucktype = $(contentMFT + ' .editmanifestmodal-trucktype').val();
		var platenumber = $(contentMFT + ' .editmanifestmodal-platenumber').val();
		var drivername = $(contentMFT + ' .editmanifestmodal-driver').val();
		var contactnumber = $(contentMFT + ' .editmanifestmodal-contactnumber').val();
		var loadplanflag = $(contentMFT + ' .editmanifestmodal-loadplanflag').val();
		var location = $(contentMFT + ' .editmanifestmodal-location').val();
		var origin = $(contentMFT + ' .editmanifestmodal-origin').val();
		var modeoftransport = $(contentMFT + ' .editmanifestmodal-modeoftransport').val();
		var agent = $(contentMFT + ' .editmanifestmodal-agent').val();
		var etd = $(contentMFT + ' .editmanifestmodal-etd').val();
		var eta = $(contentMFT + ' .editmanifestmodal-eta').val();
		var mawbl = $(contentMFT + ' .editmanifestmodal-mawbbl').val();
		var id = $(contentMFT + ' #pgtxnmanifest-id').val();
		var button = $(this);
		button.addClass('disabled');

		if (loadplanflag == 1) {
			location = 'NULL';
			origin = 'NULL';
			modeoftransport = 'NULL';
			agent = 'NULL';
			etd = 'NULL';
			eta = 'NULL';
			mawbl = 'NULL';
		} else {
			loadplannumber = 'NULL';
		}

		$(modal + ' .modal-errordiv').empty();

		if (loadplanflag == 1 && (loadplannumber == '' || loadplannumber == 'NULL' || loadplannumber == 'null' || loadplannumber == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select load plan number.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (location == '' || location == 'NULL' || location == 'null' || location == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select location.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (origin == '' || origin == 'NULL' || origin == 'null' || origin == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (modeoftransport == '' || modeoftransport == 'NULL' || modeoftransport == 'null' || modeoftransport == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && (agent == '' || agent == 'NULL' || agent == 'null' || agent == null)) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select agent.</div></div>");
			button.removeClass('disabled');
		} else if (loadplanflag == 0 && mawbl.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide mawbl.</div></div>");
			button.removeClass('disabled');
		} else if (documentdate.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide document date.</div></div>");
			button.removeClass('disabled');
		} else if (truckername.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide trucker name.</div></div>");
			button.removeClass('disabled');
		} else if (trucktype.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide truck type.</div></div>");
			button.removeClass('disabled');
		} else if (platenumber.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide plate number.</div></div>");
			button.removeClass('disabled');
		} else if (drivername == '' || drivername == null || drivername == 'null' || drivername == 'NULL') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide driver name.</div></div>");
			button.removeClass('disabled');
		} else if (contactnumber.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide contact number.</div></div>");
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Edit Manifest Header',
				content: 'Editing manifest header would delete all previously added waybills/bols and their corresponding package codes. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'manifest.php',
						{
							saveEditManifestTransaction: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
							id: id,
							remarks: remarks,
							documentdate: documentdate,
							loadplannumber: loadplannumber,
							truckername: truckername,
							trucktype: trucktype,
							platenumber: platenumber,
							drivername: drivername,
							contactnumber: contactnumber,
							loadplanflag: loadplanflag,
							location: location,
							origin: origin,
							modeoftransport: modeoftransport,
							agent: agent,
							eta: eta,
							etd: etd,
							mawbl: mawbl
						},
						function (data) {
							/*alert(data);
						$('#loading-img').addClass('hidden');
							button.removeClass('disabled');*/

							data = $.parseJSON(data);
							if (data['response'] == 'success') {
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
								$(modal).modal('hide');
								clearMFTEditFields();
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										getManifestInformation(data['txnnumber']);
										$(document).off('hidden.bs.modal', modal);
									});
							} else if (data['response'] == 'invaliddocdate') {
								say('Invalid document date');
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data['response'] == 'loadplanhasmanifest') {
								say('Selected load plan number has corresponding manifest transaction [' + data['manifestnumber'] + ']');
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else {
								alert(data);
								$('#loading-img').addClass('hidden');
								button.removeClass('disabled');
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

/********************* PACKAGE CODE ********************************/
$(document)
	.off('shown.bs.modal', contentMFT + ' #mftaddpackagecodemodal')
	.on('shown.bs.modal', contentMFT + ' #mftaddpackagecodemodal', function () {
		$(this).find(' .mftaddpackagecodemodal-code').val('').focus();
		$(this).find(' .modal-errordiv').empty();
	});

$(document)
	.off('click', contentMFT + ' #mftaddpackagecodemodal-addbtn')
	.on('click', contentMFT + ' #mftaddpackagecodemodal-addbtn', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		mftinsertnewpackagecode(modal);
	});

$(document)
	.off('keyup', contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
	.on('keyup', contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code', function (e) {
		var key = e.keycode || e.which;
		if (key == 13) {
			var modal = '#' + $(this).closest('.modal').attr('id');
			mftinsertnewpackagecode(modal);
		}
	});

function mftinsertnewpackagecode(modal) {
	var mftnumber = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');
	var packagecode = $(contentMFT + ' ' + modal + ' .mftaddpackagecodemodal-code').val();
	$(modal + ' .modal-errordiv').empty();

	if (packagecode.trim() == '') {
		$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please enter package code.</div></div>");
		$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code').focus();
	} else {
		$.post(server + 'manifest.php', { insertNewPackageCode: 'ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$', mftnumber: mftnumber, packagecode: packagecode }, function (data) {
			if (data.trim() == 'success') {
				$(contentMFT + ' #manifest-waybilltbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.manifest-waybill.php?reference=' + mftnumber,
						sortname: 'created_date',
						sortorder: 'desc'
					})
					.flexReload();

				$(contentMFT + ' #manifest-packagecodetbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.manifest-package-code.php?txnnumber=' + mftnumber,
						sortname: 'waybill_number, package_code',
						sortorder: 'asc'
					})
					.flexReload();

				$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
					.val('')
					.focus();
			} else if (data.trim() == 'invalidmanifest') {
				$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid manifest number.</div></div>");
				$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
					.val('')
					.focus();
			} else if (data.trim() == 'invalidpackagecode') {
				$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid package code.</div></div>");
				$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
					.val('')
					.focus();
			} else if (data.trim() == 'invalidwaybill') {
				$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Source waybill is invalid</div></div>");
				$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
					.val('')
					.focus();
			} else if (data.trim() == 'alreadyadded') {
				$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Package code already added.</div></div>");
				$(contentMFT + ' #mftaddpackagecodemodal .mftaddpackagecodemodal-code')
					.val('')
					.focus();
			} else {
				alert(data);
			}
		});
	}
}

/****************** PACKAGE CODE - END ******************************/
$(document)
	.off('click', contentMFT + ' #manifest-trans-updatestatusbtn')
	.on('click', contentMFT + ' #manifest-trans-updatestatusbtn', function () {
		$(contentMFT + ' #updatemanifeststatusmodal').modal('show');
		$(contentMFT + ' #updatemanifeststatusmodal-manifestid').val($(contentMFT + ' #pgtxnmanifest-id').val());
		$(contentMFT + ' .updatemanifeststatusmodal-manifestnumber').val($(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number'));
	});

$(document)
	.off('change', contentMFT + ' .updatemanifeststatusmodal-status')
	.on('change', contentMFT + ' .updatemanifeststatusmodal-status', function () {
		if ($(this).val() == 'TRANSFERRED' || $(this).val() == 'LOADED' || $(this).val() == 'RETRIEVED') {
			$(contentMFT + ' .mftmawblwrapper').removeClass('hidden');
			$(contentMFT + ' .mftetdwrapper').removeClass('hidden');
			$(contentMFT + ' .mftetawrapper').removeClass('hidden');
		} else {
			$(contentMFT + ' .mftmawblwrapper').addClass('hidden');
			$(contentMFT + ' .mftetdwrapper').addClass('hidden');
			$(contentMFT + ' .mftetawrapper').addClass('hidden');
		}
	});

$(document)
	.off('click', contentMFT + ' #updatemanifeststatusmodal-savebtn:not(".disabled")')
	.on('click', contentMFT + ' #updatemanifeststatusmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var status = $(modal + ' .updatemanifeststatusmodal-status').val();
		var manifestid = $(modal + ' #updatemanifeststatusmodal-manifestid').val();
		var manifestnumber = $(modal + ' .updatemanifeststatusmodal-manifestnumber').val();
		var mawbbl = '';
		var etd = '';
		var eta = '';
		var remarks = $(modal + ' .updatemanifeststatusmodal-remarks').val();
		var button = $(this);
		button.addClass('disabled');
		$(modal + ' .modal-errordiv').empty();

		if (status == 'TRANSFERRED' || status == 'LOADED' || status == 'RETRIEVED') {
			mawbbl = $(modal + ' .updatemanifeststatusmodal-mawbbl').val();
			etd = $(modal + ' .updatemanifeststatusmodal-etd').val();
			eta = $(modal + ' .updatemanifeststatusmodal-eta').val();
		}

		if (manifestnumber == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>No manifest number provided.</div></div>");
			button.removeClass('disabled');
		} else if (status == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
			$(modal + ' .updatemanifeststatusmodal-status').focus();
			button.removeClass('disabled');
		} else if ((status == 'TRANSFERRED' || status == 'LOADED' || status == 'RETRIEVED') && mawbbl == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide master waybill number.</div></div>");
			$(modal + ' .updatemanifeststatusmodal-mawbbl').focus();
			button.removeClass('disabled');
		} else if ((status == 'TRANSFERRED' || status == 'LOADED' || status == 'RETRIEVED') && etd == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide etd.</div></div>");
			$(modal + ' .updatemanifeststatusmodal-etd').focus();
			button.removeClass('disabled');
		} else if ((status == 'TRANSFERRED' || status == 'LOADED' || status == 'RETRIEVED') && eta == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide eta.</div></div>");
			$(modal + ' .updatemanifeststatusmodal-eta').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Update Status',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'manifest.php',
						{
							updateManifestStatus: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
							manifestid: manifestid,
							status: status,
							manifestnumber: manifestnumber,
							remarks: remarks,
							mawbbl: mawbbl,
							etd: etd,
							eta: eta
						},
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getManifestInformation(manifestnumber);
										$(modal + ' #updatemanifeststatusmodal-manifestid').val('');
										$(modal + ' .updatemanifeststatusmodal-manifestnumber').val('');
										$(modal + ' .updatemanifeststatusmodal-status')
											.val('')
											.trigger('change');
										$(modal + ' .updatemanifeststatusmodal-mawbbl').val('');
										$(modal + ' .updatemanifeststatusmodal-etd').val('');
										$(modal + ' .updatemanifeststatusmodal-eta').val('');
										$(modal + ' .updatemanifeststatusmodal-remarks').val('');

										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidmanifest') {
								say('Unable to update status. ID: ' + manifestid + ' - Manifest No.: ' + manifestnumber);
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

/************************* PRINTING *****************************************/
$(document)
	.off('click', contentMFT + ' #manifestprintingmodal-printbtn')
	.on('click', contentMFT + ' #manifestprintingmodal-printbtn', function () {
		var tabid = $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var type = $(modal + ' .manifestprintingmodal-formtype').val();

		var filepath = '';

		if (type == 'MFTTRANSMITTAL') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/manifest-dispatch-transmittal.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'T';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-T]';
		} else if (type == 'MFTTRANSMITTAL2') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/manifest-dispatch-transmittal2.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'T2';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-T2]';
		} else if (type == 'AIRCARGOMANIFEST') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/air-cargo-manifest.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'AIRT';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-AIRT]';
		} else if (type == 'SEALANDCARGOMANIFEST') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/sea-land-cargo-manifest.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'SEAT';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-SEAT]';
		} else if (type == 'MFTSYSTEMGENERATED') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/manifest.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'S';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-S]';
		} else if (type == 'MFTCOURIERDELTRANS') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/manifest-courier-delivery-transmittal.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'CDT';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-CDT]';
		} else if (type == 'RTS') {
			filepath = 'printouts/print-preview.php?txnnumber=' + tabid + '&source=printouts/transactions/rts-transmittal.php?txnnumber=' + tabid + '&reference=' + tabid;
			var typesuffix = tabid + 'RTS';
			var title = 'Print Preview [' + $(contentMFT + ' #pgtxnmanifest-id').attr('pgtxnmanifest-number') + '-RTS]';
		}

		//alert(type+' '+filepath);

		$(modal).modal('hide');
		$(document)
			.off('hidden.bs.modal', modal)
			.on('hidden.bs.modal', modal, function () {
				$(document).off('hidden.bs.modal', modal);

				if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + typesuffix + "tabpane']").length >= 1) {
					$('#loading-img').removeClass('hidden');
					$('.content').animate({ scrollTop: 0 }, 300);

					$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
					$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');

					$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + typesuffix + "tabpane']").addClass('active');
					$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + typesuffix + "tabpane']").addClass('active');

					$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + typesuffix + "tabpane']").load(filepath);
					setTimeout(function () {
						$('#loading-img').addClass('hidden');
					}, 400);
				} else {
					$('#loading-img').removeClass('hidden');
					$('.content').animate({ scrollTop: 0 }, 300);

					$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
					$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
					$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + typesuffix + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
					$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + typesuffix + "tabpane'></div>");
					$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(filepath);
					setTimeout(function () {
						$('#loading-img').addClass('hidden');
					}, 400);
				}
			});
	});
/************************* PRINTING - END *****************************************/

/************************* SEARCHING ***********************************/

$(document)
	.off('dblclick', contentMFT + ' .manifestsearchrow')
	.on('dblclick', contentMFT + ' .manifestsearchrow', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var manifestnumber = $(this).attr('manifestnumber');
		$(modal).modal('hide');
		$(document)
			.off('hidden.bs.modal', modal)
			.on('hidden.bs.modal', modal, function () {
				$(document).off('hidden.bs.modal', modal);
				getManifestInformation(manifestnumber);
			});
	});

function searchManifestLookup(modal) {
	var status = $(modal + ' .manifestsearch-status').val(),
		loadplannumber = $(modal + ' .manifestsearch-loadplan').val(),
		waybillnumber = $(modal + ' .manifestsearch-waybillnumber')
			.val()
			.replace(/\s/g, '%20'),
		mawbl = $(modal + ' .manifestsearch-mawbl')
			.val()
			.replace(/\s/g, '%20'),
		packagecode = $(modal + ' .manifestsearch-packagecode')
			.val()
			.replace(/\s/g, '%20'),
		origin = $(modal + ' .manifestsearch-origin').val(),
		destination = $(modal + ' .manifestsearch-destination').val(),
		agent = $(modal + ' .manifestsearch-agent').val(),
		mode = $(modal + ' .manifestsearch-mode').val(),
		carrier = $(modal + ' .manifestsearch-carrier').val(),
		vehicletype = $(modal + ' .manifestsearch-vehicletype').val(),
		docdatefrom = $(modal + ' .manifestsearch-docdatefrom').val(),
		docdateto = $(modal + ' .manifestsearch-docdateto').val();

	$(contentMFT + ' #manifestsearch-table')
		.flexOptions({
			url:
				'loadables/ajax/transactions.manifest-search.php?status=' +
				status +
				'&loadplannumber=' +
				loadplannumber +
				'&destination=' +
				destination +
				'&origin=' +
				origin +
				'&mode=' +
				mode +
				'&agent=' +
				agent +
				'&carrier=' +
				carrier +
				'&vehicletype=' +
				vehicletype +
				'&waybillnumber=' +
				waybillnumber +
				'&packagecode=' +
				packagecode +
				'&docdatefrom=' +
				docdatefrom +
				'&docdateto=' +
				docdateto +
				'&mawbl=' +
				mawbl,
			sortname: 'manifest_number',
			sortorder: 'asc'
		})
		.flexReload();
}

$(document).on(
	'keyup',
	contentMFT +
		' #manifest-searchmodallookup .manifestsearch-docdatefrom,' +
		contentMFT +
		' #manifest-searchmodallookup .manifestsearch-docdateto, ' +
		contentMFT +
		' #manifest-searchmodallookup .manifestsearch-waybillnumber, ' +
		contentMFT +
		' #manifest-searchmodallookup .manifestsearch-mawbl, ' +
		contentMFT +
		' #manifest-searchmodallookup .manifestsearch-packagecode',
	function (e) {
		var key = e.which || e.keycode;
		if (key == '13') {
			var modal = '#' + $(this).closest('.modal').attr('id');
			searchManifestLookup(modal);
		}
	}
);

$(document)
	.off('click', contentMFT + ' #manifestsearch-searchbtn:not(".disabled")')
	.on('click', contentMFT + ' #manifestsearch-searchbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchManifestLookup(modal);
	});

/************************** SEARCHING - END ********************************/

$(document)
	.off('click', contentMFT + ' #manifest-trans-printbtn:not(".disabled")')
	.on('click', contentMFT + ' #manifest-trans-printbtn:not(".disabled")', function () {
		$(contentMFT + ' #manifestprintingmodal').modal('show');
	});

$(document)
	.off('focusout', contentMFT + ' .mftwaybillremarks')
	.on('focusout', contentMFT + ' .mftwaybillremarks', function () {
		let remarks = $(this).val();
		let rowid = $(this).attr('rowid');

		$.post(server + 'manifest.php', { updateManifestWaybillRemarks: 'ojoi#johlp#ouh$3ksk#Op1NEi34smo1sonk&$', rowid: rowid, remarks: remarks }, function (data) {
			data = $.parseJSON(data);

			if (data['response'] == 'success') {
			} else {
				console.log(data);
			}
		});
	});
