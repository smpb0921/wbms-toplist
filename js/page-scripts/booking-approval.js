var contentBKA = '#bookingapproval-menutabpane';

$(document)
	.off('click', contentBKA + ' .viewbookinghistorybtn')
	.on('click', contentBKA + ' .viewbookinghistorybtn', function () {
		var bookingnumber = $(this).attr('bookingnumber');
		var id = $(this).attr('rowid');
		var modal = '#viewbookinghistorymodal';

		$(modal).modal('show');

		$(contentBKA + ' ' + modal + ' .viewbookinghistorymodal-bookingnumber').text('# ' + bookingnumber);
		$.post(server + 'booking-approval.php', { getBookingHistoryDetails: 'kjho#hikh@Oidpo%$n85hoddpm!lqplkohi', id: id }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(modal + ' .viewbookinghistorymodal-bookingtype').html(rsp['bookingtype']);
				$(modal + ' .viewbookinghistorymodal-location').html(rsp['location']);
				$(modal + ' .viewbookinghistorymodal-origin').html(rsp['origin']);
				$(modal + ' .viewbookinghistorymodal-destination').html(rsp['destination']);
				$(modal + ' .viewbookinghistorymodal-pickupdate').html(rsp['pickupdate']);
				$(modal + ' .viewbookinghistorymodal-postedby').html(rsp['postedby']);
				$(modal + ' .viewbookinghistorymodal-posteddate').html(rsp['posteddate']);
				$(modal + ' .viewbookinghistorymodal-timeready').html(rsp['timeready']);
				$(modal + ' .viewbookinghistorymodal-remarks').html(rsp['remarks']);

				$(modal + ' .viewbookinghistorymodal-shipper-accountnumber').html(rsp['shipperaccountnumber']);
				$(modal + ' .viewbookinghistorymodal-shipper-accountname').html(rsp['shipperaccountname']);
				$(modal + ' .viewbookinghistorymodal-shipper-tel').html(rsp['shippertel']);
				$(modal + ' .viewbookinghistorymodal-shipper-companyname').html(rsp['shippercompanyname']);
				$(modal + ' .viewbookinghistorymodal-shipper-companyaddress').html(rsp['shipperaddress']);
				$(modal + ' .viewbookinghistorymodal-shipper-pickupaddress').html(rsp['pickupaddress']);

				$(modal + ' .viewbookinghistorymodal-consignee-accountnumber').html(rsp['consigneeaccountnumber']);
				$(modal + ' .viewbookinghistorymodal-consignee-accountname').html(rsp['consigneeaccountname']);
				$(modal + ' .viewbookinghistorymodal-consignee-tel').html(rsp['consigneetel']);
				$(modal + ' .viewbookinghistorymodal-consignee-companyname').html(rsp['consigneecompanyname']);
				$(modal + ' .viewbookinghistorymodal-consignee-companyaddress').html(rsp['consigneeaddress']);

				$(modal + ' .viewbookinghistorymodal-numberofpackage').html(rsp['numberofpackage']);
				$(modal + ' .viewbookinghistorymodal-uom').html(rsp['uom']);
				$(modal + ' .viewbookinghistorymodal-declaredvalue').html(rsp['declaredvalue']);
				$(modal + ' .viewbookinghistorymodal-actualweight').html(rsp['actualweight']);
				$(modal + ' .viewbookinghistorymodal-vwcbm').html(rsp['vwcbm']);
				//$(modal+' .viewbookinghistorymodal-vw').html(rsp['vw']);
				$(modal + ' .viewbookinghistorymodal-amount').html(rsp['amount']);
				$(modal + ' .viewbookinghistorymodal-service').html(rsp['service']);
				$(modal + ' .viewbookinghistorymodal-document').html(rsp['document']);
				$(modal + ' .viewbookinghistorymodal-handlinginstruction').html(rsp['handlinginstruction']);
				$(modal + ' .viewbookinghistorymodal-modeoftransport').html(rsp['modeoftransport']);
				$(modal + ' .viewbookinghistorymodal-paymode').html(rsp['paymode']);

				$(modal + ' .viewbookinghistorymodal-shipmentdescription').html(rsp['shipmentdescription']);

				$(modal + ' .viewbookinghistorymodal-vehicletype').html(rsp['vehicletype']);
				$(modal + ' .viewbookinghistorymodal-platenumber').html(rsp['platenumber']);
				$(modal + ' .viewbookinghistorymodal-driverfor').html(rsp['driverfor']);
				$(modal + ' .viewbookinghistorymodal-driver').html(rsp['driver']);
				$(modal + ' .viewbookinghistorymodal-helper').html(rsp['helper']);
				$(modal + ' .viewbookinghistorymodal-drivercontactnumber').html(rsp['drivercontactnumber']);
				$(modal + ' .viewbookinghistorymodal-billto').html(rsp['billto']);
				$(modal + ' .viewbookinghistorymodal-truckingdetails').html(rsp['truckingdetails']);
			}
		});
	});

$(document)
	.off('click', contentBKA + ' .reviewbookingbtn')
	.on('click', contentBKA + ' .reviewbookingbtn', function () {
		var bookingnumber = $(this).attr('bookingnumber');
		var id = $(this).attr('rowid');
		var modal = '#reviewbookingmodal';

		$('#bookingapproval-confirmationmodal #bookingapproval-confirmationmodal-bookingnumber').val(bookingnumber);
		$('#reviewbookingmodal .reviewbookingmodal-bookingnumber').text('# ' + bookingnumber);

		$.post(server + 'booking-approval.php', { getBookingDetails: 'kjho#hikh@Oidpo%$n85hoddpm!lqplkohi', id: id }, function (data) {
			rsp = $.parseJSON(data);
			if (rsp['response'] == 'success') {
				$(modal + ' .reviewbookingmodal-location').html(rsp['location']);
				$(modal + ' .reviewbookingmodal-bookingtype').html(rsp['bookingtype']);
				$(modal + ' .reviewbookingmodal-origin').html(rsp['origin']);
				$(modal + ' .reviewbookingmodal-destination').html(rsp['destination']);
				$(modal + ' .reviewbookingmodal-pickupdate').html(rsp['pickupdate']);
				$(modal + ' .reviewbookingmodal-postedby').html(rsp['postedby']);
				$(modal + ' .reviewbookingmodal-posteddate').html(rsp['posteddate']);
				$(modal + ' .reviewbookingmodal-remarks').html(rsp['remarks']);

				$(modal + ' .reviewbookingmodal-shipper-accountnumber').html(rsp['shipperaccountnumber']);
				$(modal + ' .reviewbookingmodal-shipper-accountname').html(rsp['shipperaccountname']);
				$(modal + ' .reviewbookingmodal-shipper-tel').html(rsp['shippertel']);
				$(modal + ' .reviewbookingmodal-shipper-companyname').html(rsp['shippercompanyname']);
				$(modal + ' .reviewbookingmodal-shipper-companyaddress').html(rsp['shipperaddress']);
				$(modal + ' .reviewbookingmodal-shipper-pickupaddress').html(rsp['pickupaddress']);

				$(modal + ' .reviewbookingmodal-consignee-accountnumber').html(rsp['consigneeaccountnumber']);
				$(modal + ' .reviewbookingmodal-consignee-accountname').html(rsp['consigneeaccountname']);
				$(modal + ' .reviewbookingmodal-consignee-tel').html(rsp['consigneetel']);
				$(modal + ' .reviewbookingmodal-consignee-companyname').html(rsp['consigneecompanyname']);
				$(modal + ' .reviewbookingmodal-consignee-companyaddress').html(rsp['consigneeaddress']);

				$(modal + ' .reviewbookingmodal-numberofpackage').html(rsp['numberofpackage']);
				$(modal + ' .reviewbookingmodal-uom').html(rsp['uom']);
				$(modal + ' .reviewbookingmodal-declaredvalue').html(rsp['declaredvalue']);
				$(modal + ' .reviewbookingmodal-actualweight').html(rsp['actualweight']);
				$(modal + ' .reviewbookingmodal-vwcbm').html(rsp['vwcbm']);
				//$(modal+' .reviewbookingmodal-vw').html(rsp['vw']);
				$(modal + ' .reviewbookingmodal-amount').html(rsp['amount']);
				$(modal + ' .reviewbookingmodal-service').html(rsp['service']);
				$(modal + ' .reviewbookingmodal-document').html(rsp['document']);
				$(modal + ' .reviewbookingmodal-handlinginstruction').html(rsp['handlinginstruction']);
				$(modal + ' .reviewbookingmodal-modeoftransport').html(rsp['modeoftransport']);
				$(modal + ' .reviewbookingmodal-paymode').html(rsp['paymode']);

				$(modal + ' .reviewbookingmodal-shipmentdescription').html(rsp['shipmentdescription']);

				$(modal + ' .reviewbookingmodal-vehicletype').html(rsp['vehicletype']);
				$(modal + ' .reviewbookingmodal-platenumber').html(rsp['platenumber']);
				$(modal + ' .reviewbookingmodal-driverfor').html(rsp['driverfor']);
				$(modal + ' .reviewbookingmodal-driver').html(rsp['driver']);
				$(modal + ' .reviewbookingmodal-helper').html(rsp['helper']);
				$(modal + ' .reviewbookingmodal-drivercontactnumber').html(rsp['drivercontactnumber']);
				$(modal + ' .reviewbookingmodal-timeready').html(rsp['timeready']);
				$(modal + ' .reviewbookingmodal-billto').html(rsp['billto']);
				$(modal + ' .reviewbookingmodal-truckingdetails').html(rsp['truckingdetails']);
			}
		});
	});

$(document)
	.off('click', contentBKA + ' .reviewbookingmodal-approverejectbtn')
	.on('click', contentBKA + ' .reviewbookingmodal-approverejectbtn', function () {
		var action = $(this).attr('title');
		var bookingnumber = $(contentBKA + ' #bookingapproval-confirmationmodal #bookingapproval-confirmationmodal-bookingnumber').val();

		if (action == 'Approve') {
			$(contentBKA + ' #bookingapproval-confirmationmodal-action').text('Approve Booking [' + bookingnumber + ']');
			$(contentBKA + ' #bookingapproval-confirmationmodal-sourceaction').val('approve');
		} else if (action == 'Reject') {
			$(contentBKA + ' #bookingapproval-confirmationmodal-action').text('Reject Booking [' + bookingnumber + ']');
			$(contentBKA + ' #bookingapproval-confirmationmodal-sourceaction').val('reject');
		}
		$(contentBKA + ' #bookingapproval-confirmationmodal').modal('show');
	});

$(document)
	.off('shown.bs.modal', contentBKA + ' #bookingapproval-confirmationmodal')
	.on('shown.bs.modal', contentBKA + ' #bookingapproval-confirmationmodal', function () {
		var modal = '#' + $(this).attr('id');
		$(modal + ' .bookingapproval-confirmationmodal-remarks').focus();
	});

$(document)
	.off('click', contentBKA + ' #bookingapproval-confirmationmodal-confirmbtn:not(".disabled")')
	.on('click', contentBKA + ' #bookingapproval-confirmationmodal-confirmbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var modal2 = '#reviewbookingmodal';
		var action = $(modal + ' #bookingapproval-confirmationmodal-sourceaction').val();
		var bookingnumber = $(modal + ' #bookingapproval-confirmationmodal-bookingnumber').val();
		var remarks = $(modal + ' .bookingapproval-confirmationmodal-remarks').val();

		if (action == 'reject' && remarks.trim() == '') {
			say('Please provide reason for rejection');
		} else {
			$('#loading-img').removeClass('hidden');

			$.post(server + 'booking-approval.php', { approveRejectBooking: 'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@', action: action, bookingnumber: bookingnumber, remarks: remarks }, function (data) {
				if (data.trim() == 'success') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(modal2).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal2)
								.on('hidden.bs.modal', modal2, function () {
									$(document).off('hidden.bs.modal', modal);
									$(document).off('hidden.bs.modal', modal2);

									$('#pendingapprovalstable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.pending-approvals.php',
											sortname: 'posted_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#approvalhistorytable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.approval-history.php',
											sortname: 'created_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#loading-img').addClass('hidden');

									$(modal + ' .bookingapproval-confirmationmodal-remarks').val('');
									if (action == 'reject') {
										say('Booking [' + bookingnumber + '] successfully rejected');
									} else if (action == 'approve') {
										say('Booking [' + bookingnumber + '] successfully approved');
									}
								});
						});
				} else if (data.trim() == 'invalidbookingnumber') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(modal2).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal2)
								.on('hidden.bs.modal', modal2, function () {
									$(document).off('hidden.bs.modal', modal);
									$(document).off('hidden.bs.modal', modal2);

									$('#pendingapprovalstable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.pending-approvals.php',
											sortname: 'posted_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#approvalhistorytable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.approval-history.php',
											sortname: 'created_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#loading-img').addClass('hidden');

									say('Invalid Booking Number [' + bookingnumber + ']');
								});
						});
				} else if (data.trim() == 'bookingnotposted') {
					$(modal).modal('hide');
					$(document)
						.off('hidden.bs.modal', modal)
						.on('hidden.bs.modal', modal, function () {
							$(modal2).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal2)
								.on('hidden.bs.modal', modal2, function () {
									$(document).off('hidden.bs.modal', modal);
									$(document).off('hidden.bs.modal', modal2);

									$('#pendingapprovalstable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.pending-approvals.php',
											sortname: 'posted_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#approvalhistorytable')
										.flexOptions({
											url: 'loadables/ajax/transactions.booking-approval.approval-history.php',
											sortname: 'created_date',
											sortorder: 'desc',
										})
										.flexReload();
									$('#loading-img').addClass('hidden');

									say('Unable to approve/reject booking number [' + bookingnumber + ']. Booking has already been approved/rejected by another approver.');
								});
						});
				} else {
					alert(data);
					$('#loading-img').addClass('hidden');
				}
			});
		}
	});
