var contentBK = '#booking-menutabpane';
var inputfieldsBK = '.booking-inputfields';
var processBK = '';
var currentBookingTxn = '';

function enableFieldsBK() {
	$(contentBK + ' .errordiv').empty();
	$(inputfieldsBK + ' input:not(".alwaysdisabled"),' + inputfieldsBK + ' textarea:not(".alwaysdisabled"),' + inputfieldsBK + ' select:not(".alwaysdisabled")').removeAttr('disabled');
	$(contentBK + ' .firstprevnextlastbtn').addClass('hidden');
	$(contentBK + ' .transactionnumber').attr('disabled', true);
	$(contentBK + ' .searchbtn')
		.addClass('disabled')
		.removeClass('active')
		.addClass('hidden');

	$(contentBK + ' .topbuttonsdiv')
		.empty()
		.html(
			"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='Save' id='booking-trans-savebtn'><img src='../resources/img/save.png'></div><div class='button-group-btn active' title='Cancel' id='booking-trans-cancelbtn'><img src='../resources/img/cancel.png'></div></div></div>"
		);
	$(contentBK + ' .savecancelbuttonwrapper').html(
		"<div class='padded-with-border-engraved button-bottom'><div class='button-group'><div class='button-group-btn active' id='booking-trans-savebtn'><img src='../resources/img/save.png'>&nbsp;&nbsp;Save</div><div class='button-group-btn active' id='booking-trans-cancelbtn'><img src='../resources/img/cancel.png'>&nbsp;&nbsp;Cancel</div></div></div>"
	);
	$(contentBK + ' .inputgroupbtnicon').removeClass('hidden');

	if ($(contentBK + ' .booking-samedaypickupflag').prop('checked') == true) {
		$(contentBK + ' .booking-pickupdate').attr('disabled', true);
	} else {
		$(contentBK + ' .booking-pickupdate').removeAttr('disabled');
	}
}

function disableFieldsBK() {
	$(contentBK + ' .errordiv').empty();
	$(inputfieldsBK + ' input:not(".alwaysdisabled"),' + inputfieldsBK + ' textarea:not(".alwaysdisabled"),' + inputfieldsBK + ' select').attr('disabled', true);
	$(contentBK + ' .savecancelbuttonwrapper').empty();
	$(contentBK + ' .firstprevnextlastbtn').removeClass('hidden');
	$(contentBK + ' .transactionnumber').removeAttr('disabled');
	$(contentBK + ' .searchbtn')
		.removeClass('disabled')
		.addClass('active')
		.removeClass('hidden');
	$(contentBK + ' .inputgroupbtnicon').addClass('hidden');
}

function clearAllBK() {
	$(inputfieldsBK + ' input:not(".transactionnumber")').val('');
	$(inputfieldsBK + ' textarea')
		.text('')
		.val('');
	$(inputfieldsBK + ' select')
		.val('')
		.trigger('change');
	$(inputfieldsBK + ' .header-errordiv').empty();
	$(inputfieldsBK + ' .detail-errordiv').empty();
	$(contentBK + ' .statusdiv').html('<br>');
}

function clearNewPickupAddressFieldsBK() {
	var modal = contentBK + ' #booking-shipperpickupaddresslookup';
	$(modal + ' .inputtxtfld').val('');
	$(modal + ' .inputslctfld')
		.empty()
		.trigger('change');
}

/************* NEW BTN *********************/
$(document)
	.off('click', contentBK + ' #booking-trans-newbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-newbtn:not(".disabled")', function () {
		processBK = 'add';
		enableFieldsBK();
		clearAllBK();

		var date = getDate();
		$('#loading-img').removeClass('hidden');
		$(contentBK + ' #pgtxnbooking-id')
			.val('')
			.removeAttr('pgtxnbooking-number');
		$(contentBK + ' .statusdiv').html('LOGGED');
		$(contentBK + ' .booking-createddate').val(date);

		$(contentBK + ' .booking-samedaypickupflag').prop('checked', false);
		$(contentBK + ' .booking-pickupdate').removeAttr('disabled');

		$(contentBK + ' .booking-shipper-pickupcountry')
			.empty()
			.append('<option selected value="Philippines">Philippines</option>')
			.trigger('change');

		$.post('../config/post-functions.php', { getLoggedUserAndNextRef: 'Fns!oi3ah434ad#2l211#$*3%', transactionid: '1' }, function (data) {
			data = data.split('@#$%');
			$(contentBK + ' .booking-createdby').val(data[0]);
			$(contentBK + ' .transactionnumber').val(data[1]);
			$('#loading-img').addClass('hidden');

			$.post('../config/post-functions.php', { defaulDoortoDoor: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk' }, function (data) {
				data = $.parseJSON(data);

				//alert(data["service"]);
				if (data['response'] == 'success') {
					if (data['service'].trim() != null) {
						$(inputfieldsBK + ' .booking-services')
							.empty()
							.append('<option selected value="' + data['serviceid'] + '">' + data['service'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-services')
							.empty()
							.trigger('change');
					}
				}

				$.post(server + 'booking.php', { checkVehicleInformationAccess: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7' }, function (data) {
					if (data.trim() == 1) {
						$(contentBK + ' .vehicleinformationsection').removeClass('hidden');
					} else {
						$(contentBK + ' .vehicleinformationsection').addClass('hidden');
					}
				});
			});
		});
	});
/************* NEW BTN - END *****************/

/************* EDIT BTN *********************/
$(document)
	.off('click', contentBK + ' #booking-trans-editbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-editbtn:not(".disabled")', function () {
		processBK = 'edit';
		enableFieldsBK();
		pickupAddressFilterReload();

		$.post(server + 'booking.php', { checkVehicleInformationAccess: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7' }, function (data) {
			if (data.trim() == 1) {
				$(contentBK + ' .vehicleinformationsection').removeClass('hidden');
			} else {
				$(contentBK + ' .vehicleinformationsection').addClass('hidden');
			}
		});
	});
/************* EDIT BTN - END *****************/

/************* POST BTN *********************/
$(document)
	.off('click', contentBK + ' #booking-trans-postbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-postbtn:not(".disabled")', function () {
		var id = $(contentBK + ' #pgtxnbooking-id').val(),
			txnnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number'),
			vehicletype = $(inputfieldsBK + ' .booking-vehicletype').val(),
			platenumber = $(inputfieldsBK + ' .booking-platenumber').val(),
			driver = $(inputfieldsBK + ' .booking-driver').val(),
			helper = $(inputfieldsBK + ' .booking-helper').val(),
			drivercontact = $(inputfieldsBK + ' .booking-drivercontactnumber').val(),
			timeready = $(inputfieldsBK + ' .booking-timeready').val(),
			button = $(this);
		button.addClass('disabled');

		if (vehicletype == '' || vehicletype == null || vehicletype == 'null' || vehicletype == 'NULL') {
			say('Unable to post booking transaction. No vehicle type selected.');
			button.removeClass('disabled');
		} else if (timeready.trim() == '') {
			/*else if(platenumber==''||platenumber==null||platenumber=='null'||platenumber=='NULL'){
		say("Unable to post booking transaction. No plate number selected.");
		button.removeClass('disabled');
	}
	else if(driver==''||driver==null||driver=='null'||driver=='NULL'){
		say("Unable to post booking transaction. No driver selected.");
		button.removeClass('disabled');
	}
	else if(helper==''||helper==null||helper=='null'||helper=='NULL'){
		say("Unable to post booking transaction. No helper selected.");
		button.removeClass('disabled');
	}*/
			say('Unable to post booking transaction. Please provide time ready.');
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Post Transaction',
				content: 'Posting Booking Transaction[' + txnnumber + ']. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');

					$.post(server + 'booking.php', { postBookingTransaction: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk', id: id, txnnumber: txnnumber }, function (data) {
						rp = $.parseJSON(data);
						if (rp['response'] == 'success') {
							$('#loading-img').addClass('hidden');
							loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='booking-menutabpane']", 'transactions/booking.php?reference=' + txnnumber);
							button.removeClass('disabled');
						} else if (rp['response'] == 'noaccess') {
							say('No permission to post booking transaction [' + $txnnumber + '].');
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else if (rp['response'] == 'alreadyposted') {
							say('Booking transaction [' + txnnumber + '] is already posted.');
							getBookingInformation(txnnumber);
							$('#loading-img').addClass('hidden');
							button.removeClass('disabled');
						} else if (rp['response'] == 'invalidtransaction') {
							say('Unable to post transaction. Invalid booking number [' + $txnnumber + '].');
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
		}
	});
/************* POST BTN - END *****************/

/************** CANCEL BTN *******************/
$(document)
	.off('click', contentBK + ' #booking-trans-cancelbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-cancelbtn:not(".disabled")', function () {
		processBK = '';

		$('#loading-img').removeClass('hidden');
		clearAllBK();
		disableFieldsBK();

		if (currentBookingTxn != '') {
			getBookingInformation(currentBookingTxn);
		} else {
			$(contentBK + ' .transactionnumber').val('');
			$(contentBK + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='booking-trans-newbtn'><img src='../resources/img/add.png'></div></div></div>"
			);
		}
		$(contentBK + ' .vehicleinformationsection').removeClass('hidden');
		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 500);
		$('.content').animate({ scrollTop: 0 }, 300);
	});
/************ CANCEL BTN - END *****************/

/************** SAVE BTN *******************/
$(document)
	.off('click', contentBK + ' #booking-trans-savebtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-savebtn:not(".disabled")', function () {
		var button = $(this),
			shipmenttype = $(inputfieldsBK + ' .booking-shipmenttype').val(),
			shipmentmode = $(inputfieldsBK + ' .booking-shipmentmode').val(),
			bookingtype = $(inputfieldsBK + ' .booking-bookingtype').val(),
			origin = $(inputfieldsBK + ' .booking-origin').val(),
			destination = $(inputfieldsBK + ' .booking-destination').val(),
			remarks = $(inputfieldsBK + ' .booking-remarks').val(),
			pickupdate = $(inputfieldsBK + ' .booking-pickupdate').val(),
			shipperid = $(inputfieldsBK + ' .booking-shipper-systemid').val(),
			shipperaccountnumber = $(inputfieldsBK + ' .booking-shipper-accountnumber').val(),
			shipperaccountname = $(inputfieldsBK + ' .booking-shipper-accountname').val(),
			shippertel = $(inputfieldsBK + ' .booking-shipper-telephone').val(),
			shippermobile = $(inputfieldsBK + ' .booking-shipper-mobile').val(),
			shippercontact = $(inputfieldsBK + ' .booking-shipper-contact').val(),
			shippercompanyname = $(inputfieldsBK + ' .booking-shipper-companyname').val(),
			shipperstreet = $(inputfieldsBK + ' .booking-shipper-street').val(),
			shipperdistrict = $(inputfieldsBK + ' .booking-shipper-district').val(),
			shippercity = $(inputfieldsBK + ' .booking-shipper-city').val(),
			shipperprovince = $(inputfieldsBK + ' .booking-shipper-province').val(),
			shipperzipcode = $(inputfieldsBK + ' .booking-shipper-zipcode').val(),
			shippercountry = $(inputfieldsBK + ' .booking-shipper-country').val(),
			pickupstreet = $(inputfieldsBK + ' .booking-shipper-pickupstreet').val(),
			pickupdistrict = $(inputfieldsBK + ' .booking-shipper-pickupdistrict').val(),
			pickupcity = $(inputfieldsBK + ' .booking-shipper-pickupcity').val(),
			pickupprovince = $(inputfieldsBK + ' .booking-shipper-pickupprovince').val(),
			pickupzipcode = $(inputfieldsBK + ' .booking-shipper-pickupzipcode').val(),
			pickupcountry = $(inputfieldsBK + ' .booking-shipper-pickupcountry').val(),
			consigneeid = $(inputfieldsBK + ' .booking-consignee-systemid').val(),
			consigneeaccountnumber = $(inputfieldsBK + ' .booking-consignee-accountnumber').val(),
			consigneeaccountname = $(inputfieldsBK + ' .booking-consignee-accountname').val(),
			consigneetel = $(inputfieldsBK + ' .booking-consignee-telephone').val(),
			consigneecompanyname = $(inputfieldsBK + ' .booking-consignee-companyname').val(),
			consigneestreet = $(inputfieldsBK + ' .booking-consignee-street').val(),
			consigneedistrict = $(inputfieldsBK + ' .booking-consignee-district').val(),
			consigneecity = $(inputfieldsBK + ' .booking-consignee-city').val(),
			consigneeprovince = $(inputfieldsBK + ' .booking-consignee-province').val(),
			consigneezipcode = $(inputfieldsBK + ' .booking-consignee-zipcode').val(),
			consigneecountry = $(inputfieldsBK + ' .booking-consignee-country').val(),
			numberofpackage = parseInt($(inputfieldsBK + ' .booking-numberofpackages').val()),
			uom = $(inputfieldsBK + ' .booking-uom').val(),
			declaredvalue = $(inputfieldsBK + ' .booking-declaredvalue').val(),
			actualweight = $(inputfieldsBK + ' .booking-actualweight').val(),
			vwcbm = $(inputfieldsBK + ' .booking-vwcbm').val(),
			vw = 0,
			amount = $(inputfieldsBK + ' .booking-amount').val(),
			vehicletype = $(inputfieldsBK + ' .booking-vehicletype').val(),
			vehicletypetype = $(inputfieldsBK + ' .booking-vehicletypetype').val(),
			platenumber = $(inputfieldsBK + ' .booking-platenumber').val(),
			truckingdetails = $(inputfieldsBK + ' .booking-truckingdetails').val(),
			driver = $(inputfieldsBK + ' .booking-driver').val(),
			helper = $(inputfieldsBK + ' .booking-helper').val(),
			drivercontact = $(inputfieldsBK + ' .booking-drivercontactnumber').val(),
			timeready = $(inputfieldsBK + ' .booking-timeready').val(),
			vehicletype = $(inputfieldsBK + ' .booking-vehicletype').val(),
			services = $(inputfieldsBK + ' .booking-services').val(),
			modeoftransport = $(inputfieldsBK + ' .booking-modeoftransport').val(),
			handlinginstruction = $(inputfieldsBK + ' .booking-handlinginstruction').val(),
			paymode = $(inputfieldsBK + ' .booking-paymode').val(),
			shipmentdescription = $(inputfieldsBK + ' .booking-shipmentdescription').val(),
			bkdocument = $(inputfieldsBK + ' .booking-document').val(),
			mode = $(inputfieldsBK + ' .booking-modeoftransport')
				.find('option:selected')
				.text(),
			source = processBK,
			samedaypickupflag = $(inputfieldsBK + ' .booking-samedaypickupflag').prop('checked'),
			billto = $(inputfieldsBK + ' .booking-billto').val(),
			id = '';

		if (declaredvalue > 0) {
		} else {
			declaredvalue = 0;
		}

		button.addClass('disabled');
		$(inputfieldsBK + ' .header-errordiv, ' + inputfieldsBK + ' .detail-errordiv').empty();

		if (processBK == 'edit') {
			id = $(contentBK + ' #pgtxnbooking-id').val();
		}

		if (bookingtype == '' || bookingtype == null || bookingtype == 'null' || bookingtype == 'NULL') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select booking type.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			//$(modal+' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (shipmenttype == '' || shipmenttype == null || shipmenttype == 'null' || shipmenttype == 'NULL') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an shipment type.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			//$(modal+' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (shipmentmode == '' || shipmentmode == null || shipmentmode == 'null' || shipmentmode == 'NULL') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an shipment mode.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			//$(modal+' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (origin == '' || origin == null || origin == 'null' || origin == 'NULL') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select an origin.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			//$(modal+' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (destination == '' || destination == null || destination == 'null' || destination == 'NULL') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a destination.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			button.removeClass('disabled');
		} else if (pickupdate == '') {
			$(inputfieldsBK + ' .header-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a pickup date.</div></div>");
			$('.content').animate({ scrollTop: 0 }, 500);
			$(inputfieldsBK + ' .booking-pickupdate').focus();
			button.removeClass('disabled');
		} else if (shipperid == '') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a shipper.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			button.removeClass('disabled');
		} else if (pickupprovince == '' || pickupprovince == 'null' || pickupprovince == null || pickupprovince == 'NULL' || pickupprovince == undefined) {
			/*else if(shippertel==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper contact information.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-shipper-telephone').focus();
		button.removeClass('disabled');
	}
	else if(shipperstreet==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper street address.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-shipper-street').focus();
		button.removeClass('disabled');
	}
	else if(shippercity==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper city.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-shipper-city').focus();
		button.removeClass('disabled');
	}
	else if(shipperprovince==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipper province.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-shipper-province').focus();
		button.removeClass('disabled');
	}*/
			$(inputfieldsBK + ' .detail-errordiv').html(
				"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup region/province.</div></div>"
			);
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-shipper-pickupprovince').focus();
			button.removeClass('disabled');
		} else if (pickupcity == '' || pickupcity == 'null' || pickupcity == null || pickupcity == 'NULL' || pickupcity == undefined) {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup city.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-shipper-pickupcity').focus();
			button.removeClass('disabled');
		} else if (pickupdistrict == '' || pickupdistrict == 'null' || pickupdistrict == null || pickupdistrict == 'NULL' || pickupdistrict == undefined) {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup district.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-shipper-pickupdistrict').focus();
			button.removeClass('disabled');
		}
		// else if(pickupzipcode==''||pickupzipcode=='null'||pickupzipcode==null||pickupzipcode=='NULL'||pickupzipcode==undefined){
		// 	$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup zip code.</div></div>");
		// 	$('.content').animate({scrollTop:500},500);
		// 	$(inputfieldsBK+' .booking-shipper-pickupzipcode').focus();
		// 	button.removeClass('disabled');
		// }
		else if (pickupstreet == '' || pickupstreet == null || pickupstreet == undefined || pickupstreet == 'null' || pickupstreet == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup street address.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-shipper-pickupstreet').focus();
			button.removeClass('disabled');
		} else if (pickupcountry == '' || pickupcountry == 'null' || pickupcountry == null || pickupcountry == 'NULL' || pickupcountry == undefined) {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide pickup country.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-shipper-pickupcountry').focus();
			button.removeClass('disabled');
		} else if (uom == '' || uom == null || uom == 'null' || uom == 'NULL') {
			/*else if(consigneeid==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a consignee.</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}
	else if(consigneestreet==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide consignee street address.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-consignee-street').focus();
		button.removeClass('disabled');
	}
	else if(consigneecity==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide consignee city.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-consignee-city').focus();
		button.removeClass('disabled');
	}
	else if(consigneeprovince==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide consignee province.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-consignee-province').focus();
		button.removeClass('disabled');
	}*/
			/*else if(numberofpackage==''&&(mode.indexOf("FTL")<0&&mode.indexOf("FCL")<0)){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide number of packages.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-numberofpackages').focus();
		button.removeClass('disabled');
	}
	else if(actualweight==''&&(mode.indexOf("FTL")<0&&mode.indexOf("FCL")<0)){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide actual weight.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-actualweight').focus();
		button.removeClass('disabled');
	}
	else if((vwcbm=='')&&(mode.indexOf("FTL")<0&&mode.indexOf("FCL")<0)){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide cbm.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-vwcbm').focus();
		button.removeClass('disabled');
	}*/
			/*else if(amount==''||!amount>0){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an amount.</div></div>");
		$('.content').animate({scrollTop:500},500);
		$(inputfieldsBK+' .booking-amount').focus();
		button.removeClass('disabled');
	}*/
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select unit of measure.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-uom').focus();
			button.removeClass('disabled');
		} else if (paymode == '' || paymode == null || paymode == 'null' || paymode == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pay mode.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-paymode').focus();
			button.removeClass('disabled');
		} else if (services == '' || services == null || services == 'null' || services == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select service.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-services').focus();
			button.removeClass('disabled');
		} else if (modeoftransport == '' || modeoftransport == null || modeoftransport == 'null' || modeoftransport == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-modeoftransport').focus();
			button.removeClass('disabled');
		} else if (bkdocument == '' || bkdocument == null || bkdocument == 'null' || bkdocument == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select accompanying document.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-document').focus();
			button.removeClass('disabled');
		} else if (handlinginstruction == '' || handlinginstruction == null || handlinginstruction == 'null' || handlinginstruction == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select handling instruction.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-handlinginstruction').focus();
			button.removeClass('disabled');
		} else if (vehicletype == '' || vehicletype == null || vehicletype == 'null' || vehicletype == 'NULL') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select vehicle type.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			button.removeClass('disabled');
		} else if (timeready.trim() == '') {
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide time ready.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			$(inputfieldsBK + ' .booking-timeready').focus();
			button.removeClass('disabled');
		} else if (shipmentdescription.trim() == '') {
			/*else if(driver==''||driver==null||driver=='null'||driver=='NULL'){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select driver.</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}
	else if(helper==''||helper==null||helper=='null'||helper=='NULL'){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select helper.</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}
	else if(drivercontact==''){
		$(inputfieldsBK+' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide driver contact number.</div></div>");
		$('.content').animate({scrollTop:500},500);
		button.removeClass('disabled');
	}
	*/
			$(inputfieldsBK + ' .detail-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide shipment description.</div></div>");
			$('.content').animate({ scrollTop: 500 }, 500);
			button.removeClass('disabled');
		} else {
			$.ajax({
				type: 'POST',
				url: server + 'booking.php',
				data: { checkduplicateshipper: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@', shipperid: shipperid, pickupdate: pickupdate },
				success: function (response) {
					try {
						response = JSON.parse(response);
						if (response.DuplicateShipper) {
							$.confirm({
								animation: 'bottom',
								closeAnimation: 'top',
								animationSpeed: 400,
								animationBounce: 1,
								title: 'Confirm Save',
								content: `Shipper <b>${response.Shipper}</b> is already booked with transaction number <b>${response.DuplicateBookingNumber}</b> with the same pickup date <b>(${pickupdate})</b>. Do you still want to continue?`,
								confirmButton: 'Confirm',
								cancelButton: 'Cancel',
								confirmButtonClass: 'btn-oceanblue',
								cancelButtonClass: 'btn-royalblue',
								theme: 'white',

								confirm: function () {
									$('#loading-img').removeClass('hidden');
									$(inputfieldsBK + ' .header-errordiv, ' + inputfieldsBK + ' .detail-errordiv').empty();
									$.post(
										server + 'booking.php',
										{
											SaveBookingTransaction: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
											timeready: timeready,
											platenumber: platenumber,
											id: id,
											origin: origin,
											destination: destination,
											pickupdate: pickupdate,
											remarks: remarks,
											shipperid: shipperid,
											shipperaccountnumber: shipperaccountnumber,
											shipperaccountname: shipperaccountname,
											shippertel: shippertel,
											shippercompanyname: shippercompanyname,
											shipperstreet: shipperstreet,
											shipperdistrict: shipperdistrict,
											shippercity: shippercity,
											shipperprovince: shipperprovince,
											shipperzipcode: shipperzipcode,
											shippercountry: shippercountry,
											pickupstreet: pickupstreet,
											pickupdistrict: pickupdistrict,
											pickupcity: pickupcity,
											pickupprovince: pickupprovince,
											pickupzipcode: pickupzipcode,
											pickupcountry: pickupcountry,
											consigneeid: consigneeid,
											consigneeaccountnumber: consigneeaccountnumber,
											consigneeaccountname: consigneeaccountname,
											consigneetel: consigneetel,
											consigneecompanyname: consigneecompanyname,
											consigneestreet: consigneestreet,
											consigneedistrict: consigneedistrict,
											consigneecity: consigneecity,
											consigneeprovince: consigneeprovince,
											consigneezipcode: consigneezipcode,
											consigneecountry: consigneecountry,
											numberofpackage: numberofpackage,
											declaredvalue: declaredvalue,
											actualweight: actualweight,
											vwcbm: vwcbm,
											amount: amount,
											services: services,
											modeoftransport: modeoftransport,
											handlinginstruction: handlinginstruction,
											paymode: paymode,
											shipmentdescription: shipmentdescription,
											source: source,
											vw: vw,
											uom: uom,
											shippermobile: shippermobile,
											shippercontact: shippercontact,
											bkdocument: bkdocument,
											samedaypickupflag: samedaypickupflag,
											drivercontact: drivercontact,
											billto: billto,
											driver: driver,
											helper: helper,
											vehicletype: vehicletype,
											vehicletypetype: vehicletypetype,
											bookingtype: bookingtype,
											truckingdetails: truckingdetails,
											shipmenttype: shipmenttype,
											shipmentmode: shipmentmode
										},
										function (data) {
											data = $.parseJSON(data);

											if (data['response'] == 'success') {
												processBK = '';
												loadpage(
													".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='booking-menutabpane']",
													'transactions/booking.php?reference=' + data['txnnumber']
												);
												button.removeClass('disabled');
												setTimeout(function () {
													$('#loading-img').addClass('hidden');
												}, 500);
												$('.content').animate({ scrollTop: 0 }, 300);
											} else if (data['response'] == 'invalidpickupdate') {
												$(inputfieldsBK + ' .header-errordiv').html(
													"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid pickup date.</div></div>"
												);
												$('.content').animate({ scrollTop: 0 }, 500);
												$(inputfieldsBK + ' .booking-pickupdate').focus();
												$('#loading-img').addClass('hidden');
												button.removeClass('disabled');
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
						} else {
							$('#loading-img').removeClass('hidden');
							$(inputfieldsBK + ' .header-errordiv, ' + inputfieldsBK + ' .detail-errordiv').empty();
							$.post(
								server + 'booking.php',
								{
									SaveBookingTransaction: 'oi$ha@3h0$0jRoihQnsRP9$nzpo92po@k@',
									timeready: timeready,
									platenumber: platenumber,
									id: id,
									origin: origin,
									destination: destination,
									pickupdate: pickupdate,
									remarks: remarks,
									shipperid: shipperid,
									shipperaccountnumber: shipperaccountnumber,
									shipperaccountname: shipperaccountname,
									shippertel: shippertel,
									shippercompanyname: shippercompanyname,
									shipperstreet: shipperstreet,
									shipperdistrict: shipperdistrict,
									shippercity: shippercity,
									shipperprovince: shipperprovince,
									shipperzipcode: shipperzipcode,
									shippercountry: shippercountry,
									pickupstreet: pickupstreet,
									pickupdistrict: pickupdistrict,
									pickupcity: pickupcity,
									pickupprovince: pickupprovince,
									pickupzipcode: pickupzipcode,
									pickupcountry: pickupcountry,
									consigneeid: consigneeid,
									consigneeaccountnumber: consigneeaccountnumber,
									consigneeaccountname: consigneeaccountname,
									consigneetel: consigneetel,
									consigneecompanyname: consigneecompanyname,
									consigneestreet: consigneestreet,
									consigneedistrict: consigneedistrict,
									consigneecity: consigneecity,
									consigneeprovince: consigneeprovince,
									consigneezipcode: consigneezipcode,
									consigneecountry: consigneecountry,
									numberofpackage: numberofpackage,
									declaredvalue: declaredvalue,
									actualweight: actualweight,
									vwcbm: vwcbm,
									amount: amount,
									services: services,
									modeoftransport: modeoftransport,
									handlinginstruction: handlinginstruction,
									paymode: paymode,
									shipmentdescription: shipmentdescription,
									source: source,
									vw: vw,
									uom: uom,
									shippermobile: shippermobile,
									shippercontact: shippercontact,
									bkdocument: bkdocument,
									samedaypickupflag: samedaypickupflag,
									drivercontact: drivercontact,
									billto: billto,
									driver: driver,
									helper: helper,
									vehicletype: vehicletype,
									vehicletypetype: vehicletypetype,
									bookingtype: bookingtype,
									truckingdetails: truckingdetails,
									shipmenttype: shipmenttype,
									shipmentmode: shipmentmode
								},
								function (data) {
									data = $.parseJSON(data);

									if (data['response'] == 'success') {
										processBK = '';
										loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='booking-menutabpane']", 'transactions/booking.php?reference=' + data['txnnumber']);
										button.removeClass('disabled');
										setTimeout(function () {
											$('#loading-img').addClass('hidden');
										}, 500);
										$('.content').animate({ scrollTop: 0 }, 300);
									} else if (data['response'] == 'invalidpickupdate') {
										$(inputfieldsBK + ' .header-errordiv').html(
											"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid pickup date.</div></div>"
										);
										$('.content').animate({ scrollTop: 0 }, 500);
										$(inputfieldsBK + ' .booking-pickupdate').focus();
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled');
									} else {
										alert(data);
										$('#loading-img').addClass('hidden');
										button.removeClass('disabled');
									}
								}
							);
						}
					} catch (error) {
						console.log(error.toString());
					}
				}
			});
		}
	});
/************ SAVE BTN - END *****************/

/************** STAT TO PICKED UP BTN *******************/
$(document)
	.off('click', contentBK + ' #booking-trans-updatestatpickedbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-updatestatpickedbtn:not(".disabled")', function () {
		var bookingid = $(contentBK + ' #pgtxnbooking-id').val();
		var bookingnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');

		$(contentBK + ' #booking-updatestattopickedmodal').modal('show');

		$(contentBK + ' #booking-updatestattopickedmodal-bookingid').val(bookingid);
		$(contentBK + ' .booking-updatestattopickedmodal-bookingnumber').val(bookingnumber);
	});
/************ STAT TO PICKED UP - END *****************/

/************************* VOID BTN ***********************/
$(document)
	.off('click', contentBK + ' #booking-trans-voidbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-voidbtn:not(".disabled")', function () {
		var modal = '#voidbookingtransactionmodal';
		var bookingid = $(contentBK + ' #pgtxnbooking-id').val();
		var bookingnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');

		$(contentBK + ' #voidbookingtransactionmodal-bookingid').val(bookingid);
		$(contentBK + ' .voidbookingtransactionmodal-bookingnumber').val(bookingnumber);

		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(contentBK + ' .voidbookingtransactionmodal-remarks').focus();
			});
	});
/********************** VOID BTN - END ********************/

/*************** INPUT GROUP BUTTONS ****************************/
$(document)
	.off('click', contentBK + ' .inputgroupicon:not(".disabled")')
	.on('click', contentBK + ' .inputgroupicon:not(".disabled")', function () {
		if (processBK == 'add' || processBK == 'edit') {
			var modal = $(this).data('modal');
			$(modal).modal('show');
		}
	});

$(document)
	.off('click', contentBK + ' #booking-pickupaddresslookupbtn:not(".disabled")')
	.on('click', contentBK + ' #booking-pickupaddresslookupbtn:not(".disabled")', function () {
		var modal = $(this).data('modal');
		var selectedshipperID = $(modal + ' .booking-shipperpickupaddresslookup-shipperid').val();
		var button = $(this);
		button.addClass('disabled');

		if (selectedshipperID.trim() != '' && selectedshipperID != null && selectedshipperID != undefined) {
			$(modal).modal('show');
			button.removeClass('disabled');
		} else {
			say('Please select a shipper');
			button.removeClass('disabled');
		}
	});

/*************** INPUT GROUP BUTTONS - END ****************************/

/********************* TABLEROW EVENTS ********************************/
$(document)
	.off('dblclick', contentBK + ' .shipperlookuprow:not(".disabled")')
	.on('dblclick', contentBK + ' .shipperlookuprow:not(".disabled")', function () {
		var id = $(this).attr('rowid');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var row = $(this);
		row.addClass('disabled');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentBK + ' ' + modal, function () {
			$(document).off('hidden.bs.modal', contentBK + ' ' + modal);

			$.post(server + 'shipper.php', { ShipperGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: id }, function (data) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'success') {
					$(inputfieldsBK + ' .booking-shipper-systemid').val(id);
					$(inputfieldsBK + ' .booking-shipper-accountnumber').val(rsp['accountnumber']);
					$(inputfieldsBK + ' .booking-shipper-accountname').val(rsp['accountname']);
					$(inputfieldsBK + ' .booking-shipper-companyname').val(rsp['companyname']);
					$(inputfieldsBK + ' .booking-shipper-street').val(rsp['companystreet']);
					$(inputfieldsBK + ' .booking-shipper-district').val(rsp['companydistrict']);
					$(inputfieldsBK + ' .booking-shipper-city').val(rsp['companycity']);
					$(inputfieldsBK + ' .booking-shipper-province').val(rsp['companyprovince']);
					$(inputfieldsBK + ' .booking-shipper-zipcode').val(rsp['companyzipcode']);

					if (rsp['companydistrict'] != null) {
						$(inputfieldsBK + ' .booking-shipper-district')
							.empty()
							.append('<option selected value="' + rsp['companydistrict'] + '">' + rsp['companydistrict'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-shipper-district')
							.empty()
							.trigger('change');
					}
					if (rsp['companycity'] != null) {
						$(inputfieldsBK + ' .booking-shipper-city')
							.empty()
							.append('<option selected value="' + rsp['companycity'] + '">' + rsp['companycity'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-shipper-city')
							.empty()
							.trigger('change');
					}
					if (rsp['companyprovince'] != null) {
						$(inputfieldsBK + ' .booking-shipper-province')
							.empty()
							.append('<option selected value="' + rsp['companyprovince'] + '">' + rsp['companyprovince'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-shipper-province')
							.empty()
							.trigger('change');
					}
					if (rsp['companyzipcode'] != null) {
						$(inputfieldsBK + ' .booking-shipper-zipcode')
							.empty()
							.append('<option selected value="' + rsp['companyzipcode'] + '">' + rsp['companyzipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-shipper-zipcode')
							.empty()
							.trigger('change');
					}

					if (rsp['companycountry'] != null) {
						$(inputfieldsBK + ' .booking-shipper-country')
							.empty()
							.append('<option selected value="' + rsp['companycountry'] + '">' + rsp['companycountry'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-shipper-country')
							.empty()
							.trigger('change');
					}

					if (rsp['paymode'] != null) {
						$(inputfieldsBK + ' .booking-paymode')
							.empty()
							.append('<option selected value="' + rsp['paymode'] + '">' + rsp['paymode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-paymode')
							.empty()
							.trigger('change');
					}

					$(contentBK + ' .booking-shipperpickupaddresslookup-shipperid').val(id);
					$(contentBK + ' .booking-shipperpickupaddresslookup-accountnumber').val(rsp['accountnumber']);
					$(contentBK + ' .booking-shipperpickupaddresslookup-accountname').val(rsp['accountname']);

					$.post(server + 'booking.php', { ShipperDefaultContactGetInfo: 'ojoiAndElspriaoi#@po92po@k@', id: id }, function (data1) {
						rsp1 = $.parseJSON(data1);
						if (rsp1['response'] == 'success') {
							$(inputfieldsBK + ' .booking-shipper-telephone').val(rsp1['phone']);
							$(inputfieldsBK + ' .booking-shipper-mobile').val(rsp1['mobile']);
							$(inputfieldsBK + ' .booking-shipper-contact').val(rsp1['contact']);
							row.removeClass('disabled');
						} else if (rsp1['response'] == 'nocontactinfo') {
							row.removeClass('disabled');
						} else {
							alert(data1);
							say('Unable to retrive default contact information of selected shipper.');
							row.removeClass('disabled');
						}

						$.post(server + 'booking.php', { ShipperDefaultPickupAddressGetInfo: 'ooi3h$9apsojespriaoi#@po92po@k@', id: id }, function (data2) {
							rsp2 = $.parseJSON(data2);
							if (rsp2['response'] == 'success') {
								$(inputfieldsBK + ' .booking-shipper-pickupstreet').val(rsp2['street']);
								$(inputfieldsBK + ' .booking-shipper-pickupdistrict').val(rsp2['district']);
								$(inputfieldsBK + ' .booking-shipper-pickupcity').val(rsp2['city']);
								$(inputfieldsBK + ' .booking-shipper-pickupprovince').val(rsp2['province']);
								$(inputfieldsBK + ' .booking-shipper-pickupzipcode').val(rsp2['zipcode']);

								if (rsp2['district'] != null) {
									$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
										.empty()
										.append('<option selected value="' + rsp2['district'] + '">' + rsp2['district'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
										.empty()
										.trigger('change');
								}
								if (rsp2['city'] != null) {
									$(inputfieldsBK + ' .booking-shipper-pickupcity')
										.empty()
										.append('<option selected value="' + rsp2['city'] + '">' + rsp2['city'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsBK + ' .booking-shipper-pickupcity')
										.empty()
										.trigger('change');
								}
								if (rsp2['province'] != null) {
									$(inputfieldsBK + ' .booking-shipper-pickupprovince')
										.empty()
										.append('<option selected value="' + rsp2['province'] + '">' + rsp2['province'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsBK + ' .booking-shipper-pickupprovince')
										.empty()
										.trigger('change');
								}
								if (rsp2['zipcode'] != null) {
									$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
										.empty()
										.append('<option selected value="' + rsp2['zipcode'] + '">' + rsp2['zipcode'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
										.empty()
										.trigger('change');
								}

								if (rsp2['country'] != null) {
									$(inputfieldsBK + ' .booking-shipper-pickupcountry')
										.empty()
										.append('<option selected value="' + rsp2['country'] + '">' + rsp2['country'] + '</option>')
										.trigger('change');
								} else {
									$(inputfieldsBK + ' .booking-shipper-pickupcountry')
										.empty()
										.trigger('change');
								}
								row.removeClass('disabled');
							} else if (rsp2['response'] == 'nopickupaddressinfo') {
								row.removeClass('disabled');
							} else {
								alert(data2);
								say('Unable to retrive default pickup address information of selected shipper.');
								row.removeClass('disabled');
							}
						});
					});
				} else {
					alert(data);
					say('Selected shipper has invalid system ID. Please select another.');
					row.removeClass('disabled');
				}
			});
		});
	});

$(document)
	.off('dblclick', contentBK + ' .shipperpickupaddresslookuprow:not(".disabled")')
	.on('dblclick', contentBK + ' .shipperpickupaddresslookuprow:not(".disabled")', function () {
		var row = $(this);
		var modal = '#' + $(this).closest('.modal').attr('id');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentBK + ' ' + modal, function () {
			$(inputfieldsBK + ' .booking-shipper-pickupstreet').val(row.find("td[abbr='pickup_street_address'] div").text());
			$(inputfieldsBK + ' .booking-shipper-pickupdistrict').val(row.find("td[abbr='pickup_district'] div").text());
			$(inputfieldsBK + ' .booking-shipper-pickupcity').val(row.find("td[abbr='pickup_city'] div").text());
			$(inputfieldsBK + ' .booking-shipper-pickupprovince').val(row.find("td[abbr='pickup_state_province'] div").text());
			$(inputfieldsBK + ' .booking-shipper-pickupzipcode').val(row.find("td[abbr='pickup_zip_code'] div").text());

			if (row.find("td[abbr='pickup_district'] div").text().trim() != null && row.find("td[abbr='pickup_district'] div").text().trim() != '') {
				$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_district'] div").text() + '">' + row.find("td[abbr='pickup_district'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_city'] div").text().trim() != null && row.find("td[abbr='pickup_city'] div").text().trim() != '') {
				$(inputfieldsBK + ' .booking-shipper-pickupcity')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_city'] div").text() + '">' + row.find("td[abbr='pickup_city'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupcity')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_state_province'] div").text().trim() != null && row.find("td[abbr='pickup_state_province'] div").text().trim() != '') {
				$(inputfieldsBK + ' .booking-shipper-pickupprovince')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_state_province'] div").text() + '">' + row.find("td[abbr='pickup_state_province'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupprovince')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_zip_code'] div").text().trim() != null && row.find("td[abbr='pickup_zip_code'] div").text().trim() != '') {
				$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_zip_code'] div").text() + '">' + row.find("td[abbr='pickup_zip_code'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
					.empty()
					.trigger('change');
			}
			if (row.find("td[abbr='pickup_country'] div").text().trim() != null && row.find("td[abbr='pickup_country'] div").text().trim() != '') {
				$(inputfieldsBK + ' .booking-shipper-pickupcountry')
					.empty()
					.append('<option selected value="' + row.find("td[abbr='pickup_country'] div").text() + '">' + row.find("td[abbr='pickup_country'] div").text() + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupcountry')
					.empty()
					.trigger('change');
			}
			$(document).off('hidden.bs.modal', contentBK + ' ' + modal);
		});
	});

$(document)
	.off('dblclick', contentBK + ' .consigneelookuprow:not(".disabled")')
	.on('dblclick', contentBK + ' .consigneelookuprow:not(".disabled")', function () {
		var id = $(this).attr('rowid');
		var modal = '#' + $(this).closest('.modal').attr('id');
		var row = $(this);
		row.addClass('disabled');

		$(modal).modal('hide');
		$(document).on('hidden.bs.modal', contentBK + ' ' + modal, function () {
			$(document).off('hidden.bs.modal', contentBK + ' ' + modal);

			$.post(server + 'consignee.php', { ConsigneeGetInfo: 'kjoI$H2oiaah3h0$09jDppo92po@k@', id: id }, function (data) {
				rsp = $.parseJSON(data);
				if (rsp['response'] == 'success') {
					$(inputfieldsBK + ' .booking-consignee-systemid').val(id);
					$(inputfieldsBK + ' .booking-consignee-accountnumber').val(rsp['accountnumber']);
					$(inputfieldsBK + ' .booking-consignee-accountname').val(rsp['accountname']);
					$(inputfieldsBK + ' .booking-consignee-companyname').val(rsp['companyname']);
					$(inputfieldsBK + ' .booking-consignee-street').val(rsp['street']);
					$(inputfieldsBK + ' .booking-consignee-district').val(rsp['district']);
					$(inputfieldsBK + ' .booking-consignee-city').val(rsp['city']);
					$(inputfieldsBK + ' .booking-consignee-province').val(rsp['province']);
					$(inputfieldsBK + ' .booking-consignee-zipcode').val(rsp['zipcode']);

					if (rsp['district'] != null) {
						$(inputfieldsBK + ' .booking-consignee-district')
							.empty()
							.append('<option selected value="' + rsp['district'] + '">' + rsp['district'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-consignee-district')
							.empty()
							.trigger('change');
					}
					if (rsp['city'] != null) {
						$(inputfieldsBK + ' .booking-consignee-city')
							.empty()
							.append('<option selected value="' + rsp['city'] + '">' + rsp['city'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-consignee-city')
							.empty()
							.trigger('change');
					}
					if (rsp['zipcode'] != null) {
						$(inputfieldsBK + ' .booking-consignee-zipcode')
							.empty()
							.append('<option selected value="' + rsp['zipcode'] + '">' + rsp['zipcode'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-consignee-zipcode')
							.empty()
							.trigger('change');
					}
					if (rsp['province'] != null) {
						$(inputfieldsBK + ' .booking-consignee-province')
							.empty()
							.append('<option selected value="' + rsp['province'] + '">' + rsp['province'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-consignee-province')
							.empty()
							.trigger('change');
					}
					if (rsp['country'] != null) {
						$(inputfieldsBK + ' .booking-consignee-country')
							.empty()
							.append('<option selected value="' + rsp['country'] + '">' + rsp['country'] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-consignee-country')
							.empty()
							.trigger('change');
					}

					$.post(server + 'booking.php', { ConsigneeDefaultContactGetInfo: 'oj94oifof#o@odlspriaoi#@po92po@k@', id: id }, function (data1) {
						rsp1 = $.parseJSON(data1);
						if (rsp1['response'] == 'success') {
							$(inputfieldsBK + ' .booking-consignee-telephone').val(rsp1['phone']);
							//$(inputfieldsBK+' .booking-numberofpackages').focus();
							row.removeClass('disabled');
						} else if (rsp1['response'] == 'nocontactinfo') {
							row.removeClass('disabled');
						} else {
							alert(data1);
							say('Unable to retrive default contact information of selected consignee.');
							row.removeClass('disabled');
						}
					});
				} else {
					alert(data);
					say('Selected consignee has invalid system ID. Please select another.');
					row.removeClass('disabled');
				}
			});
		});
	});

/********************* TABLEROW EVENTS - END ********************************/

/************************* MODAL EVENTS ***************************************/

$(document)
	.off('show.bs.modal', contentBK + ' #booking-shipperpickupaddresslookup')
	.on('show.bs.modal', contentBK + ' #booking-shipperpickupaddresslookup', function () {
		var modal = '#' + $(this).attr('id');
		var selectedshipperID = $(modal + ' .booking-shipperpickupaddresslookup-shipperid').val();
		$(contentBK + ' #booking-shipperpickupaddresslookuptbl')
			.flexOptions({
				url: 'loadables/ajax/transactions.booking.shipper-pickup-address-lookup.php?id=' + selectedshipperID,
				sortname: 'pickup_street_address',
				sortorder: 'asc'
			})
			.flexReload();

		$.post(server + 'booking.php', { checkIfUserCanAddPickupAddress: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!' }, function (data) {
			if (data.trim() == '1') {
				$(contentBK + ' .booking-shipperpickupaddresslookup-country')
					.empty()
					.append('<option selected value="Philippines">Philippines</option>')
					.trigger('change');
				$(contentBK + ' .booking-addpickupaddresswrapper').removeClass('hidden');
			} else if (data.trim() == '0') {
				$(contentBK + ' .booking-addpickupaddresswrapper').addClass('hidden');
			} else {
				alert(data);
				$(contentBK + ' .booking-addpickupaddresswrapper').addClass('hidden');
			}
		});
	});

/************************* MODAL EVENTS - END ************************************/

/*********************** MODAL BUTTONS *************************************/
$(document)
	.off('click', contentBK + ' #booking-shipperpickupaddress-savebtn:not(".disabled")')
	.on('click', contentBK + ' #booking-shipperpickupaddress-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id'),
			shipperid = $(modal + ' .booking-shipperpickupaddresslookup-shipperid').val(),
			street = $(modal + ' .booking-shipperpickupaddresslookup-street').val(),
			district = $(modal + ' .booking-shipperpickupaddresslookup-district').val(),
			city = $(modal + ' .booking-shipperpickupaddresslookup-city').val(),
			province = $(modal + ' .booking-shipperpickupaddresslookup-province').val(),
			zipcode = $(modal + ' .booking-shipperpickupaddresslookup-zipcode').val(),
			country = $(modal + ' .booking-shipperpickupaddresslookup-country').val(),
			button = $(this);
		button.addClass('disabled');

		if (province == '' || province == null || province == 'null' || province == 'NULL' || province == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a region/province.</div></div>");
			$(modal + ' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (city == '' || city == null || city == 'null' || city == 'NULL' || city == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a city.</div></div>");
			$(modal + ' .booking-shipperpickupaddresslookup-city').focus();
			button.removeClass('disabled');
		} else if (district == '' || district == null || district == 'null' || district == 'NULL' || district == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a district.</div></div>");
			$(modal + ' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else if (street == '' || street == null || street == 'null' || street == 'NULL' || street == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide street address.</div></div>");
			$(modal + ' .booking-shipperpickupaddresslookup-street').focus();
			button.removeClass('disabled');
		} else if (country == '' || country == null || country == 'null' || country == 'NULL' || country == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide country.</div></div>");
			$(modal + ' .booking-shipperpickupaddresslookup-province').focus();
			button.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');

			$.post(
				server + 'booking.php',
				{
					AddNewPickupAddress: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
					shipperid: shipperid,
					street: street,
					district: district,
					city: city,
					province: province,
					zipcode: zipcode,
					country: country
				},
				function (data) {
					if (data.trim() == 'success') {
						$(contentBK + ' #booking-shipperpickupaddresslookuptbl')
							.flexOptions({
								url: 'loadables/ajax/transactions.booking.shipper-pickup-address-lookup.php?id=' + shipperid,
								sortname: 'created_date',
								sortorder: 'desc'
							})
							.flexReload();
						clearNewPickupAddressFieldsBK();
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data.trim() == 'noaccess') {
						say('Unable to add pickup address. No user permission.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else if (data.trim() == 'invalidshipperid') {
						say('Invalid Shipper ID. Please re-select a shipper.');
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					} else {
						alert(data);
						$('#loading-img').addClass('hidden');
						button.removeClass('disabled');
					}
				}
			);
		}
	});

$(document)
	.off('click', contentBK + ' #booking-updatestattopickedmodal-savebtn:not(".disabled")')
	.on('click', contentBK + ' #booking-updatestattopickedmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bookingid = $(modal + ' #booking-updatestattopickedmodal-bookingid').val();
		var bookingnumber = $(modal + ' .booking-updatestattopickedmodal-bookingnumber').val();
		var actualpickupdate = $(modal + ' .booking-updatestattopickedmodal-actualpickupdate').val();
		var pickedupby = $(modal + ' .booking-updatestattopickedmodal-pickedupby').val();
		var remarks = $(modal + ' .booking-updatestattopickedmodal-remarks').val();
		var status = $(modal + ' .booking-updatestattopickedmodal-status').val();
		var button = $(this);
		button.addClass('disabled');
		$(modal + ' .modal-errordiv').empty();

		if (status == '' || status == null || status == 'NULL' || status == undefined || status == 'null') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-status').focus();
			button.removeClass('disabled');
		} else if (actualpickupdate.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide actual pickup date.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-actualpickupdate').focus();
			button.removeClass('disabled');
		} else if (pickedupby.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide picked-up by.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-pickedupby').focus();
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
						server + 'booking.php',
						{
							updateStatusPickedUp: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
							bookingid: bookingid,
							actualpickupdate: actualpickupdate,
							pickedupby: pickedupby,
							remarks: remarks,
							status: status
						},
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getBookingInformation(currentBookingTxn);
										$(modal + ' #booking-updatestattopickedmodal-bookingid').val('');
										$(modal + ' .booking-updatestattopickedmodal-bookingnumber').val('');
										$(modal + ' .booking-updatestattopickedmodal-actualpickupdate').val('');
										$(modal + ' .booking-updatestattopickedmodal-pickedupby').val('');
										$(modal + ' .booking-updatestattopickedmodal-remarks').val('');

										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidbooking') {
								say('Unable to update status. ID: ' + bookingid + ' - Booking No.: ' + bookingnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'invaliddate') {
								$(modal + ' .modal-errordiv').html(
									"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid actual pickup date.</div></div>"
								);
								$(modal + ' .booking-updatestattopickedmodal-actualpickupdate').focus();
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

$(document)
	.off('click', contentBK + ' #voidbookingtransactionmodal-savebtn:not(".disabled")')
	.on('click', contentBK + ' #voidbookingtransactionmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bookingid = $(modal + ' #voidbookingtransactionmodal-bookingid').val();
		var bookingnumber = $(modal + ' .voidbookingtransactionmodal-bookingnumber').val();
		var remarks = $(modal + ' .voidbookingtransactionmodal-remarks').val();
		var button = $(this);
		button.addClass('disabled');

		$(modal + ' .modal-errordiv').empty();

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide reason for cancellation.</div></div>");
			$(modal + ' .voidbookingtransactionmodal-remarks').focus();
			button.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Void Booking [' + bookingnumber + ']',
				content: 'Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(
						server + 'booking.php',
						{ voidBookingTransaction: 'dROi$nsFpo94dnels$4sRoi809srbmouS@1!', bookingid: bookingid, bookingnumber: bookingnumber, remarks: remarks },
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getBookingInformation(currentBookingTxn);
										$(modal + ' #voidbookingtransactionmodal-bookingid').val('');
										$(modal + ' .voidbookingtransactionmodal-bookingnumber').val('');
										$(modal + ' .voidbookingtransactionmodal-remarks').val('');

										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidbooking') {
								say('Unable to void booking transaction. Invalid Booking ID/No. ID: ' + bookingid + ' - Booking No.: ' + bookingnumber);
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

/*********************** MODAL BUTTONS - END *************************************/

/********************* VIEWING *********************************/
$(document)
	.off('click', contentBK + " .firstprevnextlastbtn button:not('.disabled')")
	.on('click', contentBK + " .firstprevnextlastbtn button:not('.disabled')", function () {
		$('#loading-img').removeClass('hidden');
		var source = $(this).data('info'),
			id = $('#pgtxnbooking-id').val(),
			button = $(this);
		button.addClass('disabled');

		$.post(server + 'booking.php', { getReference: 'FOio5ja3op2a2lK@3#4hh$93s', source: source, id: id }, function (data) {
			if (data.trim() == 'N/A') {
				$('#loading-img').addClass('hidden');
				getBookingInformation('');
			} else {
				getBookingInformation(data.trim());
			}
			setTimeout(function () {
				button.removeClass('disabled');
			}, 200);
		});
	});

$(document)
	.off('keyup', contentBK + ' .transactionnumber')
	.on('keyup', contentBK + ' .transactionnumber', function (e) {
		e.preventDefault();
		$('#loading-img').removeClass('hidden');
		var key = e.which || e.keycode,
			bookingnumber = $(this).val();
		if (key == '13') {
			getBookingInformation(bookingnumber);
		} else {
			$('#loading-img').addClass('hidden');
		}
	});

function getBookingInformation(txnnumber) {
	$(contentBK + ' .vehicleinformationsection').removeClass('hidden');
	$.post(server + 'booking.php', { getBookingData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: txnnumber }, function (response) {
		//alert(response);
		if (response.trim() == 'INVALID') {
			clearAllBK();
			$(contentBK + ' .statusdiv').html('<br>');
			$(contentBK + ' #pgtxnbooking-id')
				.val('')
				.removeAttr('pgtxnbooking-number', '');
			$(contentBK + ' .topbuttonsdiv').html(
				"<div class='padded-with-border-engraved topbuttonswrapper'><div class='button-group'><div class='button-group-btn active' title='New' id='booking-trans-newbtn'><img src='../resources/img/add.png'></div></div></div>"
			);
			currentBookingTxn = '';
			processBK = '';
			userAccess();
		} else {
			currentBookingTxn = txnnumber;
			data = $.parseJSON(response);

			$(contentBK + ' .booking-shipperpickupaddresslookup-shipperid').val(data['shipperid']);
			$(contentBK + ' .booking-shipperpickupaddresslookup-accountnumber').val(data['shipperaccountnumber']);
			$(contentBK + ' .booking-shipperpickupaddresslookup-accountname').val(data['shipperaccountname']);

			$(contentBK + ' .transactionnumber').val(txnnumber);
			$(contentBK + ' #pgtxnbooking-id')
				.val(data['id'])
				.attr('pgtxnbooking-number', txnnumber);
			$(contentBK + ' .statusdiv').text(data['status']);

			$(contentBK + ' .booking-remarks').val(data['remarks']);
			$(contentBK + ' .booking-pickupdate').val(data['pickupdate']);

			$(contentBK + ' .booking-supervisornotified').val(data['supervisornotified']);
			$(contentBK + ' .booking-drivernotified').val(data['drivernotified']);

			$(contentBK + ' .booking-shipper-systemid').val(data['shipperid']);
			$(contentBK + ' .booking-shipper-accountnumber').val(data['shipperaccountnumber']);
			$(contentBK + ' .booking-shipper-accountname').val(data['shipperaccountname']);
			$(contentBK + ' .booking-shipper-telephone').val(data['shippertel']);
			$(contentBK + ' .booking-shipper-mobile').val(data['shippermobile']);
			$(contentBK + ' .booking-shipper-contact').val(data['shippercontact']);
			$(contentBK + ' .booking-shipper-companyname').val(data['shippercompanyname']);
			$(contentBK + ' .booking-shipper-street').val(data['shipperstreet']);
			$(contentBK + ' .booking-shipper-district').val(data['shipperdistrict']);
			$(contentBK + ' .booking-shipper-city').val(data['shippercity']);
			$(contentBK + ' .booking-shipper-province').val(data['shipperprovince']);
			$(contentBK + ' .booking-shipper-zipcode').val(data['shipperzipcode']);

			$(contentBK + ' .booking-shipper-pickupstreet').val(data['pickupstreet']);
			$(contentBK + ' .booking-shipper-pickupdistrict').val(data['pickupdistrict']);
			$(contentBK + ' .booking-shipper-pickupcity').val(data['pickupcity']);
			$(contentBK + ' .booking-shipper-pickupprovince').val(data['pickupprovince']);
			$(contentBK + ' .booking-shipper-pickupzipcode').val(data['pickupzipcode']);

			$(contentBK + ' .booking-consignee-systemid').val(data['consigneeid']);
			$(contentBK + ' .booking-consignee-accountnumber').val(data['consigneeaccountnumber']);
			$(contentBK + ' .booking-consignee-accountname').val(data['consigneeaccountname']);
			$(contentBK + ' .booking-consignee-telephone').val(data['consigneetel']);
			$(contentBK + ' .booking-consignee-companyname').val(data['consigneecompanyname']);
			$(contentBK + ' .booking-consignee-street').val(data['consigneestreet']);
			$(contentBK + ' .booking-consignee-district').val(data['consigneedistrict']);
			$(contentBK + ' .booking-consignee-city').val(data['consigneecity']);
			$(contentBK + ' .booking-consignee-province').val(data['consigneeprovince']);
			$(contentBK + ' .booking-consignee-zipcode').val(data['consigneezipcode']);

			$(contentBK + ' .booking-numberofpackages').val(data['numberofpackage']);
			$(contentBK + ' .booking-declaredvalue').val(data['declaredvalue']);
			$(contentBK + ' .booking-actualweight').val(data['actualweight']);
			$(contentBK + ' .booking-vwcbm').val(data['vwcbm']);
			$(contentBK + ' .booking-vw').val(data['vw']);
			$(contentBK + ' .booking-amount').val(data['amount']);
			$(contentBK + ' .booking-paymode').val(data['paymode']);
			$(contentBK + ' .booking-shipmentdescription').val(data['shipmentdescription']);

			$(contentBK + ' .booking-actualpickupdate').val(data['actualpickupdate']);
			$(contentBK + ' .booking-pickupby').val(data['pickupby']);
			$(contentBK + ' .booking-createddate').val(data['createddate']);
			$(contentBK + ' .booking-createdby').val(data['createdby']);
			$(contentBK + ' .booking-posteddate').val(data['posteddate']);
			$(contentBK + ' .booking-postedby').val(data['postedby']);
			$(contentBK + ' .booking-approveddate').val(data['approveddate']);
			$(contentBK + ' .booking-approvedby').val(data['approvedby']);
			$(contentBK + ' .booking-rejecteddate').val(data['rejecteddate']);
			$(contentBK + ' .booking-rejectedby').val(data['rejectedby']);
			$(contentBK + ' .booking-reason').val(data['reason']);
			$(contentBK + ' .booking-truckingdetails').val(data['truckingdetails']);
			$(contentBK + ' .booking-vehicletypetype').val(data['vehicletypetype']);

			$(contentBK + ' .booking-timeready').val(data['timeready']);

			if (data['samedaypickupflag'] == true) {
				$(contentBK + ' .booking-samedaypickupflag').prop('checked', true);
			} else {
				$(contentBK + ' .booking-samedaypickupflag').prop('checked', false);
			}

			$(contentBK + ' .booking-drivercontactnumber').val(data['drivercontact']);
			$(contentBK + ' .booking-billto').val(data['billto']);

			if (data['platenumber'] != null) {
				$(inputfieldsBK + ' .booking-platenumber')
					.empty()
					.append('<option selected value="' + data['platenumber'] + '">' + data['platenumber'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-platenumber')
					.empty()
					.trigger('change');
			}

			if (data['driver'] != null) {
				$(inputfieldsBK + ' .booking-driver')
					.empty()
					.append('<option selected value="' + data['driver'] + '">' + data['driver'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-driver')
					.empty()
					.trigger('change');
			}

			if (data['helper'] != null) {
				$(inputfieldsBK + ' .booking-helper')
					.empty()
					.append('<option selected value="' + data['helper'] + '">' + data['helper'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-helper')
					.empty()
					.trigger('change');
			}

			if (data['vehicletypeid'] != null) {
				$(inputfieldsBK + ' .booking-vehicletype')
					.empty()
					.append('<option selected value="' + data['vehicletypeid'] + '">' + data['vehicletype'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-vehicletype')
					.empty()
					.trigger('change');
			}

			if (data['uom'] != null) {
				$(inputfieldsBK + ' .booking-uom')
					.empty()
					.append('<option selected value="' + data['uom'] + '">' + data['uom'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-uom')
					.empty()
					.trigger('change');
			}

			if (data['bookingtype'] != null) {
				$(inputfieldsBK + ' .booking-bookingtype')
					.empty()
					.append('<option selected value="' + data['bookingtypeid'] + '">' + data['bookingtype'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-bookingtype')
					.empty()
					.trigger('change');
			}

			if (data['shipmenttype'] != null) {
				$(inputfieldsBK + ' .booking-shipmenttype')
					.empty()
					.append('<option selected value="' + data['shipmenttypeid'] + '">' + data['shipmenttype'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipmenttype')
					.empty()
					.trigger('change');
			}

			if (data['shipmentmode'] != null) {
				$(inputfieldsBK + ' .booking-shipmentmode')
					.empty()
					.append('<option selected value="' + data['shipmentmodeid'] + '">' + data['shipmentmode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipmentmode')
					.empty()
					.trigger('change');
			}

			if (data['origin'] != null) {
				$(inputfieldsBK + ' .booking-origin')
					.empty()
					.append('<option selected value="' + data['originid'] + '">' + data['origin'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-origin')
					.empty()
					.trigger('change');
			}

			if (data['destination'] != null) {
				$(inputfieldsBK + ' .booking-destination')
					.empty()
					.append('<option selected value="' + data['destinationid'] + '">' + data['destination'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-destination')
					.empty()
					.trigger('change');
			}

			if (data['shipperdistrict'] != null) {
				$(inputfieldsBK + ' .booking-shipper-district')
					.empty()
					.append('<option selected value="' + data['shipperdistrict'] + '">' + data['shipperdistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-district')
					.empty()
					.trigger('change');
			}
			if (data['shippercity'] != null) {
				$(inputfieldsBK + ' .booking-shipper-city')
					.empty()
					.append('<option selected value="' + data['shippercity'] + '">' + data['shippercity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-city')
					.empty()
					.trigger('change');
			}
			if (data['shipperzipcode'] != null) {
				$(inputfieldsBK + ' .booking-shipper-zipcode')
					.empty()
					.append('<option selected value="' + data['shipperzipcode'] + '">' + data['shipperzipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-zipcode')
					.empty()
					.trigger('change');
			}
			if (data['shipperprovince'] != null) {
				$(inputfieldsBK + ' .booking-shipper-province')
					.empty()
					.append('<option selected value="' + data['shipperprovince'] + '">' + data['shipperprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-province')
					.empty()
					.trigger('change');
			}

			if (data['shippercountry'] != null) {
				$(inputfieldsBK + ' .booking-shipper-country')
					.empty()
					.append('<option selected value="' + data['shippercountry'] + '">' + data['shippercountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-country')
					.empty()
					.trigger('change');
			}

			if (data['pickupdistrict'] != null) {
				$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
					.empty()
					.append('<option selected value="' + data['pickupdistrict'] + '">' + data['pickupdistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupdistrict')
					.empty()
					.trigger('change');
			}
			if (data['pickupcity'] != null) {
				$(inputfieldsBK + ' .booking-shipper-pickupcity')
					.empty()
					.append('<option selected value="' + data['pickupcity'] + '">' + data['pickupcity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupcity')
					.empty()
					.trigger('change');
			}
			if (data['pickupzipcode'] != null) {
				$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
					.empty()
					.append('<option selected value="' + data['pickupzipcode'] + '">' + data['pickupzipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupzipcode')
					.empty()
					.trigger('change');
			}
			if (data['pickupprovince'] != null) {
				$(inputfieldsBK + ' .booking-shipper-pickupprovince')
					.empty()
					.append('<option selected value="' + data['pickupprovince'] + '">' + data['pickupprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupprovince')
					.empty()
					.trigger('change');
			}

			if (data['pickupcountry'] != null) {
				$(inputfieldsBK + ' .booking-shipper-pickupcountry')
					.empty()
					.append('<option selected value="' + data['pickupcountry'] + '">' + data['pickupcountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-shipper-pickupcountry')
					.empty()
					.trigger('change');
			}

			if (data['consigneedistrict'] != null) {
				$(inputfieldsBK + ' .booking-consignee-district')
					.empty()
					.append('<option selected value="' + data['consigneedistrict'] + '">' + data['consigneedistrict'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-consignee-district')
					.empty()
					.trigger('change');
			}
			if (data['consigneecity'] != null) {
				$(inputfieldsBK + ' .booking-consignee-city')
					.empty()
					.append('<option selected value="' + data['consigneecity'] + '">' + data['consigneecity'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-consignee-city')
					.empty()
					.trigger('change');
			}
			if (data['consigneezipcode'] != null) {
				$(inputfieldsBK + ' .booking-consignee-zipcode')
					.empty()
					.append('<option selected value="' + data['consigneezipcode'] + '">' + data['consigneezipcode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-consignee-zipcode')
					.empty()
					.trigger('change');
			}
			if (data['consigneeprovince'] != null) {
				$(inputfieldsBK + ' .booking-consignee-province')
					.empty()
					.append('<option selected value="' + data['consigneeprovince'] + '">' + data['consigneeprovince'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-consignee-province')
					.empty()
					.trigger('change');
			}
			if (data['consigneecountry'] != null) {
				$(inputfieldsBK + ' .booking-consignee-country')
					.empty()
					.append('<option selected value="' + data['consigneecountry'] + '">' + data['consigneecountry'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-consignee-country')
					.empty()
					.trigger('change');
			}

			if (data['service'] != null) {
				$(inputfieldsBK + ' .booking-services')
					.empty()
					.append('<option selected value="' + data['serviceid'] + '">' + data['service'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-services')
					.empty()
					.trigger('change');
			}
			if (data['modeoftransport'] != null) {
				$(inputfieldsBK + ' .booking-modeoftransport')
					.empty()
					.append('<option selected value="' + data['modeoftransportid'] + '">' + data['modeoftransport'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-modeoftransport')
					.empty()
					.trigger('change');
			}

			/*if(data["handlinginstruction"]!=null){
				$(inputfieldsBK+" .booking-handlinginstruction").empty().append('<option selected value="'+data["handlinginstructionid"]+'">'+data["handlinginstruction"]+'</option>').trigger('change');
			}
			else{
				$(inputfieldsBK+" .booking-handlinginstruction").empty().trigger('change');
			}*/

			if (data['paymode'] != null) {
				$(inputfieldsBK + ' .booking-paymode')
					.empty()
					.append('<option selected value="' + data['paymode'] + '">' + data['paymode'] + '</option>')
					.trigger('change');
			} else {
				$(inputfieldsBK + ' .booking-paymode')
					.empty()
					.trigger('change');
			}

			/*if(data["document"]!=null){
				$(inputfieldsBK+" .booking-document").empty().append('<option selected value="'+data["documentid"]+'">'+data["document"]+'</option>').trigger('change');
			}
			else{
				$(inputfieldsBK+" .booking-document").empty().trigger('change');
			}*/

			$(contentBK + ' .driverdropdownselect').select2({
				ajax: {
					url: 'loadables/dropdown/personnel.php?position=DRIVER&flag=1&type=' + data['vehicletypetype'],
					dataType: 'json',
					delay: 100,
					data: function (params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function (data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 0,
				width: '100%'
			});

			$(contentBK + ' .helperdropdownselect').select2({
				ajax: {
					url: 'loadables/dropdown/personnel.php?position=HELPER&flag=1&type=' + data['vehicletypetype'],
					dataType: 'json',
					delay: 100,
					data: function (params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function (data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 0,
				width: '100%'
			});

			$(contentBK + ' .vehicledropdownselect').select2({
				ajax: {
					url: 'loadables/dropdown/vehicle.php?flag=1&type=' + data['vehicletypeid'],
					dataType: 'json',
					delay: 100,
					data: function (params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function (data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 0,
				width: '100%'
			});

			var allowothertrans = '';

			if (data['addaccess'] == 'true') {
				allowothertrans += "<div class='button-group-btn active' title='New' id='booking-trans-newbtn'><img src='../resources/img/add.png'></div>";
			}

			if (data['viewstatushistoryaccess'] == 'true') {
				allowothertrans += "<div class='button-group-btn active' title='View Status History' id='booking-trans-viewstatushistorybtn'><img src='../resources/img/history.png'></div>";
			}

			if (data['status'] == 'LOGGED') {
				if (data['editaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Edit' id='booking-trans-editbtn'><img src='../resources/img/edit.png'></div>";
				}

				if (data['voidaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Void' id='booking-trans-voidbtn'><img src='../resources/img/block.png'></div>";
				}

				if (data['postaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Post' id='booking-trans-postbtn'><img src='../resources/img/post.png'></div>";
				}

				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'POSTED') {
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'REJECTED') {
				if (data['editaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Edit' id='booking-trans-editbtn'><img src='../resources/img/edit.png'></div>";
				}

				if (data['voidaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Void' id='booking-trans-voidbtn'><img src='../resources/img/block.png'></div>";
				}
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'APPROVED') {
				if (data['assigndriverdetails'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Assign Driver/Helper' id='booking-trans-assigndriverbtn'><img src='../resources/img/assign-driver.png'></div>";
				}
				if (data['voidaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Void' id='booking-trans-voidbtn'><img src='../resources/img/block.png'></div>";
				}
				if (data['updatestatusaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Update Status' id='booking-trans-updatestatusbtn'><img src='../resources/img/update-status.png'></div>";
				}
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'WAITING FOR RESPONSE') {
				if (data['resetdriver'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Reset Driver/Helper' id='booking-trans-resetvehicleinformationbtn'><img src='../resources/img/refresh.png'></div>";
				}
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'ACKNOWLEDGED') {
				if (data['updatestatusaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Update Status' id='booking-trans-updatestatpickedbtn'><img src='../resources/img/update-status.png'></div>";
				}
				if (data['resetdriver'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Reset Driver/Helper' id='booking-trans-resetvehicleinformationbtn'><img src='../resources/img/refresh.png'></div>";
				}
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'MISSED PICKUP') {
				if (data['assigndriverdetails'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Assign Driver/Helper' id='booking-trans-assigndriverbtn'><img src='../resources/img/assign-driver.png'></div>";
				}
				if (data['voidaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Void' id='booking-trans-voidbtn'><img src='../resources/img/block.png'></div>";
				}
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'CANCELLED') {
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			} else if (data['status'] == 'PICKED UP' || data['status'] == 'VOID') {
				if (data['printaccess'] == 'true') {
					allowothertrans += "<div class='button-group-btn active' title='Print' id='booking-trans-printbtn'><img src='../resources/img/print.png'></div>";
				}
			}

			allowothertrans +=
				"<div class='button-group-btn active' title='View Attachements' id='booking-trans-viewattachmentsbtn' data-toggle='modal' href='#viewbookingattachments' bookingNumber='" +
				txnnumber +
				"'><img src='../resources/img/search-folder.png'></div>";

			$(contentBK + ' .topbuttonswrapper .button-group').html(allowothertrans);
			userAccess();

			/*else if(data["status"]=="CLOSED"){
				$(contentPRO+" .topbuttonswrapper .button-group").html("<div class='button-group-btn active' title='New' id='purchase-return-trans-newbtn'><i class='fa fa-file-o fa-lg fa-space'></i></div><div class='button-group-btn active' title='Print' id='purchase-return-trans-printbtn'><i class='fa fa-print fa-lg fa-space'></i></div><div class='button-group-btn active' title='Unclose' id='purchase-return-trans-unclosebtn'><i class='fa fa-unlock fa-lg fa-space'></i></div>");
				userAccess();
			}*/

			$.post(server + 'booking.php', { getHandlingInstructions: 'sdfed#n2L1hfi$n#opi3opod30napri', txnnumber: txnnumber }, function (data) {
				//alert(data);
				data = $.parseJSON(data);
				var instructions = data['instructions'].split('#@$');

				$(inputfieldsBK + ' .booking-handlinginstruction').empty();
				for (var i = 0; i < instructions.length; i++) {
					var strdesc = instructions[i];
					strdesc = strdesc.split('%$&');
					if (strdesc[1] != null) {
						$(inputfieldsBK + ' .booking-handlinginstruction')
							.append('<option selected value="' + strdesc[0] + '">' + strdesc[1] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-handlinginstruction').trigger('change');
					}
				}
			});

			$.post(server + 'booking.php', { getAccompanyingDocuments: 'sdfed#n2L1hfi$n#opi3opod30napri', txnnumber: txnnumber }, function (data) {
				//alert(data);
				data = $.parseJSON(data);
				var descriptions = data['descriptions'].split('#@$');

				$(inputfieldsBK + ' .booking-document').empty();
				for (var i = 0; i < descriptions.length; i++) {
					var strdesc = descriptions[i];
					strdesc = strdesc.split('%$&');
					if (strdesc[1] != null) {
						$(inputfieldsBK + ' .booking-document')
							.append('<option selected value="' + strdesc[0] + '">' + strdesc[1] + '</option>')
							.trigger('change');
					} else {
						$(inputfieldsBK + ' .booking-document').trigger('change');
					}
				}
			});

			//$('.content').animate({scrollTop:0},1000);
		}
		$('#loading-img').addClass('hidden');
	});
}

$(document)
	.off('click', contentBK + ' #booking-trans-viewattachmentsbtn')
	.on('click', contentBK + ' #booking-trans-viewattachmentsbtn', function () {
		let bookingNumber = $(this).attr('bookingNumber');
		$(contentBK + ' #viewbookingattachments-bookingNumber').val(bookingNumber);
		$(contentBK + ' #viewbookingattachments-table')
			.flexOptions({
				url: 'loadables/ajax/transactions.booking-attachments.php?bookingnumber=' + bookingNumber,
				sortname: 'booking_number',
				sortorder: 'asc'
			})
			.flexReload();
	});
/********************* VIEWING - END *******************************/

/************************* SEARCHING ***********************************/

$(document)
	.off('dblclick', contentBK + ' .bookingsearchrow')
	.on('dblclick', contentBK + ' .bookingsearchrow', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bookingnumber = $(this).attr('bookingnumber');
		$(modal).modal('hide');
		$(document)
			.off('hidden.bs.modal', modal)
			.on('hidden.bs.modal', modal, function () {
				$(document).off('hidden.bs.modal', modal);
				getBookingInformation(bookingnumber);
			});
	});

function searchBookingLookup(modal) {
	var status = $(modal + ' .bookingsearch-status')
			.val()
			.replace(/\s/g, '%20'),
		bookingtype = $(modal + ' .bookingsearch-bookingtype').val(),
		origin = $(modal + ' .bookingsearch-origin').val(),
		destination = $(modal + ' .bookingsearch-destination').val(),
		shipper = $(modal + ' .bookingsearch-shipper').val(),
		consignee = $(modal + ' .bookingsearch-consignee').val(),
		pickupdatefrom = $(modal + ' .bookingsearch-pickupdatefrom').val(),
		pickupdateto = $(modal + ' .bookingsearch-pickupdateto').val();
	pickupcity = $(modal + ' .bookingsearch-city').val();
	pickupregion = $(modal + ' .bookingsearch-region').val();

	$(contentBK + ' #bookingsearch-table')
		.flexOptions({
			url:
				'loadables/ajax/transactions.booking-search.php?status=' +
				status +
				'&origin=' +
				origin +
				'&destination=' +
				destination +
				'&shipper=' +
				shipper +
				'&consignee=' +
				consignee +
				'&pickupdatefrom=' +
				pickupdatefrom +
				'&pickupdateto=' +
				pickupdateto +
				'&pickupcity=' +
				pickupcity +
				'&pickupregion=' +
				pickupregion +
				'&bookingtype=' +
				bookingtype,
			sortname: 'booking_number',
			sortorder: 'asc'
		})
		.flexReload();
}

$(document).on('keyup', contentBK + ' #booking-searchmodallookup .bookingsearch-pickupdatefrom,' + contentBK + ' #booking-searchmodallookup .bookingsearch-pickupdateto', function (e) {
	var key = e.which || e.keycode;
	if (key == '13') {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchBookingLookup(modal);
	}
});

$(document)
	.off('click', contentBK + ' #bookingsearch-searchbtn:not(".disabled")')
	.on('click', contentBK + ' #bookingsearch-searchbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		searchBookingLookup(modal);
	});

/************************** SEARCHING - END ********************************/

$(document)
	.off('change', contentBK + ' .booking-shipper-pickupprovince')
	.on('change', contentBK + ' .booking-shipper-pickupprovince', function () {
		if (processBK == 'edit' || processBK == 'add') {
			var wrapper = $(this).closest('.addressgroupwrapper');
			var region = $(this).val();

			$(wrapper)
				.find('.booking-shipper-pickupcity')
				.select2({
					ajax: {
						url: 'loadables/dropdown/address-city.php?region=' + region,
						dataType: 'json',
						delay: 100,
						data: function (params) {
							return {
								q: params.term // search term
							};
						},
						processResults: function (data) {
							// parse the results into the format expected by Select2.
							// since we are using custom formatting functions we do not need to
							// alter the remote JSON data
							return {
								results: data
							};
						},
						cache: true
					},
					minimumInputLength: 0,
					width: '100%'
				});
		}
	});

$(document)
	.off('change', contentBK + ' .booking-shipper-pickupcity')
	.on('change', contentBK + ' .booking-shipper-pickupcity', function () {
		if (processBK == 'edit' || processBK == 'add') {
			var wrapper = $(this).closest('.addressgroupwrapper');
			var city = $(this).val();

			$(wrapper)
				.find('.booking-shipper-pickupdistrict')
				.select2({
					ajax: {
						url: 'loadables/dropdown/address-district.php?city=' + city,
						dataType: 'json',
						delay: 100,
						data: function (params) {
							return {
								q: params.term // search term
							};
						},
						processResults: function (data) {
							// parse the results into the format expected by Select2.
							// since we are using custom formatting functions we do not need to
							// alter the remote JSON data
							return {
								results: data
							};
						},
						cache: true
					},
					minimumInputLength: 0,
					width: '100%'
				});
		}
	});

$(document)
	.off('change', contentBK + ' .booking-shipper-pickupdistrict')
	.on('change', contentBK + ' .booking-shipper-pickupdistrict', function () {
		if (processBK == 'edit' || processBK == 'add') {
			var wrapper = $(this).closest('.addressgroupwrapper');
			var district = $(this).val();
			var city = $(this).closest('.addressgroupwrapper').find('.booking-shipper-pickupcity').val();

			$(wrapper)
				.find('.booking-shipper-pickupzipcode')
				.select2({
					ajax: {
						url: 'loadables/dropdown/address-zip.php?district=' + district + '&city=' + city,
						dataType: 'json',
						delay: 100,
						data: function (params) {
							return {
								q: params.term // search term
							};
						},
						processResults: function (data) {
							// parse the results into the format expected by Select2.
							// since we are using custom formatting functions we do not need to
							// alter the remote JSON data
							return {
								results: data
							};
						},
						cache: true
					},
					minimumInputLength: 0,
					width: '100%'
				});

			$.post(configfldr + 'post-functions.php', { getZipCode: 'Fns!oi3ah434ad#2l211#$*3%', district: district, city: city }, function (data) {
				if (data.trim() != '') {
					$(contentBK + ' .booking-shipper-pickupzipcode')
						.empty()
						.append('<option selected value="' + data + '">' + data + '</option>')
						.trigger('change');
				}
			});
		}
	});

function pickupAddressFilterReload() {
	var region = $(contentBK + ' .booking-shipper-pickupprovince').val();
	var city = $(contentBK + ' .booking-shipper-pickupcity').val();
	var district = $(contentBK + ' .booking-shipper-pickupdistrict').val();

	$(contentBK + ' .booking-shipper-pickupcity').select2({
		ajax: {
			url: 'loadables/dropdown/address-city.php?region=' + region,
			dataType: 'json',
			delay: 100,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$(contentBK + ' .booking-shipper-pickupdistrict').select2({
		ajax: {
			url: 'loadables/dropdown/address-district.php?city=' + city,
			dataType: 'json',
			delay: 100,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$(contentBK + ' .booking-shipper-pickupzipcode').select2({
		ajax: {
			url: 'loadables/dropdown/address-zip.php?district=' + district + '&city=' + city,
			dataType: 'json',
			delay: 100,
			data: function (params) {
				return {
					q: params.term // search term
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache: true
		},
		minimumInputLength: 0,
		width: '100%'
	});

	$.post(configfldr + 'post-functions.php', { getZipCode: 'Fns!oi3ah434ad#2l211#$*3%', district: district, city: city }, function (data) {
		if (data.trim() != '') {
			$(contentBK + ' .booking-shipper-pickupzipcode')
				.empty()
				.append('<option selected value="' + data + '">' + data + '</option>')
				.trigger('change');
		}
	});
}

$(document)
	.off('change', contentBK + ' .booking-modeoftransport')
	.on('change', contentBK + ' .booking-modeoftransport', function () {
		if (processBK == 'add' || processBK == 'edit') {
			var mode = $(this).find('option:selected').text();
			if (mode.indexOf('FTL') >= 0 || mode.indexOf('FCL') >= 0) {
				$(contentBK + ' .booking-actualweight, ' + contentBK + ' .booking-numberofpackages, ' + contentBK + ' .booking-vwcbm')
					.addClass('alwaysdisabled')
					.attr('disabled', '')
					.val(0);
			} else {
				$(contentBK + ' .booking-actualweight, ' + contentBK + ' .booking-numberofpackages, ' + contentBK + ' .booking-vwcbm')
					.removeClass('alwaysdisabled')
					.removeAttr('disabled');
			}
		}
	});

$(document)
	.off('change', contentBK + ' .booking-samedaypickupflag')
	.on('change', contentBK + ' .booking-samedaypickupflag', function () {
		if (processBK == 'add' || processBK == 'edit') {
			var flag = $(this).prop('checked');
			if (flag == true) {
				$(contentBK + ' .booking-pickupdate')
					.val('')
					.attr('disabled', true);

				$(contentBK + ' .booking-pickupdate').datepicker('setDate', new Date());
			} else {
				$(contentBK + ' .booking-pickupdate')
					.val('')
					.removeAttr('disabled');
			}
		}
	});

/************************* PRINTING *****************************************/
$(document)
	.off('click', contentBK + ' #booking-trans-printbtn')
	.on('click', contentBK + ' #booking-trans-printbtn', function () {
		var modal = '#bookingprintingmodal';
		$(modal).modal('show');
		
		// Initialize the select2 dropdown when modal is shown
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(modal + ' .bookingprintingmodal-formtype').select2();
			});
	});

// Add the print button handler for the modal
$(document)
	.off('click', contentBK + ' #bookingprintingmodal-printbtn:not(".disabled")')
	.on('click', contentBK + ' #bookingprintingmodal-printbtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var formtype = $(modal + ' .bookingprintingmodal-formtype').val();
		var txnnumber = $('#pgtxnbooking-id').attr('pgtxnbooking-number');
		var title = 'Print Preview [' + txnnumber + ']';
		var tabid = txnnumber;
		var printSource = '';

		// Determine which print source to use based on form type
		if (formtype === 'BOOKINGCONFIRMATION') {
			printSource = 'printouts/transactions/booking-confirmation.php';
		} else if (formtype === 'BOOKINGRECEIPTNOTICE') {
			printSource = 'printouts/transactions/booking-receipt-notice.php';
		} else {
			say('Please select a form type');
			return;
		}

		// Close the modal
		$(modal).modal('hide');

		// Remove existing tab if it exists
		if ($('.content>.content-tab-pane .content-tabs').find("li[data-pane='#" + tabid + "tabpane']").length >= 1) {
			$(".content>.content-tab-pane .content-tabs>li[data-pane='#" + tabid + "tabpane']").remove();
			$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='" + tabid + "tabpane']").remove();
		}

		// Load the print preview
		$('#loading-img').removeClass('hidden');
		$('.content').animate({ scrollTop: 0 }, 300);

		$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
		$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#" + tabid + "tabpane' class='active'>" + title + "<i class='fa fa-remove'></i></li>");
		$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='" + tabid + "tabpane'></div>");
		$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load(
			'Printouts/print-preview.php?source=' + printSource + '?txnnumber=' + tabid + '&reference=' + tabid
		);
		
		setTimeout(function () {
			$('#loading-img').addClass('hidden');
		}, 400);
	});
/************************* PRINTING - END *****************************************/


$(document)
	.off('click', contentBK + ' #booking-shipperinfobtn:not(".disabled")')
	.on('click', contentBK + ' #booking-shipperinfobtn:not(".disabled")', function () {
		var button = $(this);
		button.addClass('disabled');

		var shipperid = $(contentBK + ' .booking-shipper-systemid').val();
		var modal = '#bookingshipperinfomodal';

		$.post(server + 'booking.php', { getShipperCreditInfo: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7', shipperid: shipperid }, function (data) {
			if (IsJsonString(data) == true) {
				data = $.parseJSON(data);
			} else {
				data = 'NULL';
			}

			if (data != 'NULL') {
				if (data['response'] == 'success') {
					$(contentBK + ' ' + modal).modal('show');
					$(modal).on('shown.bs.modal', function () {
						$(modal).off('shown.bs.modal');

						$(modal + ' .bookingshipperinfomodal-accountnumber').val(data['accountnumber']);
						$(modal + ' .bookingshipperinfomodal-accountname').val(data['accountname']);
						$(modal + ' .bookingshipperinfomodal-status').val(data['status']);
						$(modal + ' .bookingshipperinfomodal-creditlimit').val(data['creditlimit']);
						$(modal + ' .bookingshipperinfomodal-balance').val(data['outstandingbalance']);
						$(modal + ' .bookingshipperinfomodal-billedamount').val(data['billedamount']);
						$(modal + ' .bookingshipperinfomodal-unbilledamount').val(data['unbilledamount']);
						$(modal + ' .bookingshipperinfomodal-creditbalance').val(data['creditbalance']);
						button.removeClass('disabled');
					});
				} else if (data['response'] == 'invalidshipperid') {
					say('No shipper loaded.');
					//say("Unable to load shipper information. Invalid Shipper ID: "+shipperid);
					button.removeClass('disabled');
				}
			} else {
				say('No shipper loaded.');
				button.removeClass('disabled');
			}
		});

		function IsJsonString(str) {
			try {
				JSON.parse(str);
			} catch (e) {
				return false;
			}
			return true;
		}
	});

$(document)
	.off('select2:select', contentBK + ' .booking-vehicletype')
	.on('select2:select', contentBK + ' .booking-vehicletype', function (e) {
		var vehicletype = $(this).val();
		var type = e.params.data['data-type'];
		$(contentBK + ' .booking-vehicletypetype').val(type);

		$(contentBK + ' .driverdropdownselect')
			.empty()
			.trigger('change')
			.select2({
				ajax: {
					url: 'loadables/dropdown/personnel.php?position=DRIVER&flag=1&type=' + type,
					dataType: 'json',
					delay: 100,
					data: function (params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function (data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 0,
				width: '100%'
			});

		$(contentBK + ' .helperdropdownselect')
			.empty()
			.trigger('change')
			.select2({
				ajax: {
					url: 'loadables/dropdown/personnel.php?position=HELPER&flag=1&type=' + type,
					dataType: 'json',
					delay: 100,
					data: function (params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function (data) {
						// parse the results into the format expected by Select2.
						// since we are using custom formatting functions we do not need to
						// alter the remote JSON data
						return {
							results: data
						};
					},
					cache: true
				},
				minimumInputLength: 0,
				width: '100%'
			});

		$(contentBK + ' .vehicledropdownselect').select2({
			ajax: {
				url: 'loadables/dropdown/vehicle.php?flag=1&type=' + vehicletype,
				dataType: 'json',
				delay: 100,
				data: function (params) {
					return {
						q: params.term // search term
					};
				},
				processResults: function (data) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					return {
						results: data
					};
				},
				cache: true
			},
			minimumInputLength: 0,
			width: '100%'
		});

		if ((processBK == 'add' || processBK == 'edit') && (vehicletype == '' || vehicletype == 'null' || vehicletype == null || vehicletype == 'NULL' || vehicletype == undefined)) {
			$(contentBK + ' .driverdropdownselect')
				.empty()
				.trigger('change');
			$(contentBK + ' .helperdropdownselect')
				.empty()
				.trigger('change');
			$(contentBK + ' .vehicledropdownselect')
				.empty()
				.trigger('change');
			$(contentBK + ' .booking-drivercontactnumber').val('');
		}
	});

$(document)
	.off('select2:select', contentBK + ' .booking-driver')
	.on('select2:select', contentBK + ' .booking-driver', function (e) {
		var contact = e.params.data['contactnumber'];
		$(contentBK + ' .booking-drivercontactnumber').val(contact);
	});

$(document)
	.off('select2:select', contentBK + ' .booking-assigndriverdetailsmodal-driver')
	.on('select2:select', contentBK + ' .booking-assigndriverdetailsmodal-driver', function (e) {
		var contact = e.params.data['contactnumber'];
		$(contentBK + ' .booking-assigndriverdetailsmodal-drivercontactnumber').val(contact);
	});

$(document)
	.off('click', contentBK + " #booking-trans-assigndriverbtn:not('.disabled')")
	.on('click', contentBK + " #booking-trans-assigndriverbtn:not('.disabled')", function () {
		var btn = $(this);
		btn.addClass('disabled');
		var modal = '#booking-assigndriverdetailsmodal';
		$(modal + ' .modal-errordiv').empty();

		var bookingnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');
		var bookingid = $(contentBK + ' #pgtxnbooking-id').val();

		$(modal + ' #booking-assigndriverdetailsmodal-bookingid').val(bookingid);
		$(modal + ' .booking-assigndriverdetailsmodal-bookingnumber').val(bookingnumber);

		$.post(server + 'booking.php', { getBookingData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: bookingnumber }, function (response) {
			//alert(response);
			if (response.trim() == 'INVALID') {
				getBookingInformation(bookingnumber);
				say('Unable to assign driver/helper details. Invalid Booking Number');
				btn.removeClass('disabled');
			} else {
				$(modal).modal('show');
				$(document)
					.off('shown.bs.modal', modal)
					.on('shown.bs.modal', modal, function () {
						$(document).off('shown.bs.modal', modal);
						$(contentBK + ' .booking-assigndriverdetailsmodal-vehicletype').val(data['vehicletype']);
						$(contentBK + ' .booking-assigndriverdetailsmodal-driverfor').val(data['vehicletypetype']);
						$(contentBK + ' .booking-assigndriverdetailsmodal-platenumber').val(data['platenumber']);
						$(contentBK + ' .booking-assigndriverdetailsmodal-drivercontactnumber').val(data['drivercontact']);
						$(contentBK + ' .booking-assigndriverdetailsmodal-timeready').val(data['timeready']);

						if (data['platenumber'] != null) {
							$(contentBK + ' .booking-assigndriverdetailsmodal-platenumber')
								.empty()
								.append('<option selected value="' + data['platenumber'] + '">' + data['platenumber'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-assigndriverdetailsmodal-platenumber')
								.empty()
								.trigger('change');
						}

						if (data['driver'] != null) {
							$(contentBK + ' .booking-assigndriverdetailsmodal-driver')
								.empty()
								.append('<option selected value="' + data['driver'] + '">' + data['driver'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-assigndriverdetailsmodal-driver')
								.empty()
								.trigger('change');
						}

						if (data['helper'] != null) {
							$(contentBK + ' .booking-assigndriverdetailsmodal-helper')
								.empty()
								.append('<option selected value="' + data['helper'] + '">' + data['helper'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-assigndriverdetailsmodal-helper')
								.empty()
								.trigger('change');
						}

						$(contentBK + ' .booking-assigndriverdetailsmodal-driver').select2({
							ajax: {
								url: 'loadables/dropdown/personnel.php?position=DRIVER&flag=1&type=' + data['vehicletypetype'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						$(contentBK + ' .booking-assigndriverdetailsmodal-helper').select2({
							ajax: {
								url: 'loadables/dropdown/personnel.php?position=HELPER&flag=1&type=' + data['vehicletypetype'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						$(contentBK + ' .booking-assigndriverdetailsmodal-platenumber').select2({
							ajax: {
								url: 'loadables/dropdown/vehicle.php?flag=1&type=' + data['vehicletypeid'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						btn.removeClass('disabled');
					});
			}
		});
	});

$(document)
	.off('click', contentBK + " #booking-assigndriverdetailsmodal-savebtn:not('.disabled')")
	.on('click', contentBK + " #booking-assigndriverdetailsmodal-savebtn:not('.disabled')", function () {
		var btn = $(this);
		btn.addClass('disabled');
		var modal = '#' + $(this).closest('.modal').attr('id');

		var bookingid = $(modal + ' #booking-assigndriverdetailsmodal-bookingid').val();
		var bookingnumber = $(modal + ' .booking-assigndriverdetailsmodal-bookingnumber').val();
		var platenumber = $(modal + ' .booking-assigndriverdetailsmodal-platenumber').val();
		var driver = $(modal + ' .booking-assigndriverdetailsmodal-driver').val();
		var helper = $(modal + ' .booking-assigndriverdetailsmodal-helper').val();
		var drivercontactnumber = $(modal + ' .booking-assigndriverdetailsmodal-drivercontactnumber').val();
		var timeready = $(modal + ' .booking-assigndriverdetailsmodal-timeready').val();

		if (platenumber == '' || platenumber == null || platenumber == 'null' || platenumber == 'NULL' || platenumber == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a plate number.</div></div>");
			$(modal + ' .booking-assigndriverdetailsmodal-platenumber').select2('open');
			btn.removeClass('disabled');
		} else if (driver == '' || driver == null || driver == 'null' || driver == 'NULL' || driver == undefined) {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a driver.</div></div>");
			$(modal + ' .booking-assigndriverdetailsmodal-driver').select2('open');
			btn.removeClass('disabled');
		} else if (drivercontactnumber == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide driver contact number.</div></div>");
			$(modal + ' .booking-assigndriverdetailsmodal-drivercontactnumber').focus();
			btn.removeClass('disabled');
		} else if (timeready == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide time ready.</div></div>");
			$(modal + ' .booking-assigndriverdetailsmodal-timeready').focus();
			btn.removeClass('disabled');
		} else {
			$('#loading-img').removeClass('hidden');
			$.post(
				server + 'booking.php',
				{
					confirmDriverDetails: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7',
					bookingid: bookingid,
					platenumber: platenumber,
					driver: driver,
					helper: helper,
					drivercontactnumber: drivercontactnumber,
					timeready: timeready
				},
				function (response) {
					if (IsJsonString(response) == true) {
						data = $.parseJSON(response);
					} else {
						data = 'NULL';
					}

					if (data == 'NULL') {
						alert(response);
						$('#loading-img').addClass('hidden');
						btn.removeClass('disabled');
					} else {
						if (data['response'] == 'success') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);
									getBookingInformation(bookingnumber);
									say("Booking Driver/Helper Details Updated. Waiting for Driver's Response.");
									btn.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data['response'] == 'invalidtimeready') {
							$(modal + ' .modal-errordiv').html(
								"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Time Ready. Please provide valid date and time.</div></div>"
							);
							$(modal + ' .booking-assigndriverdetailsmodal-timeready').focus();
							btn.removeClass('disabled');
							$('#loading-img').addClass('hidden');
						} else if (data['response'] == 'invalidstatus') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);
									getBookingInformation(bookingnumber);
									say('Unable to update. Driver/Helper has already been assigned by another user.');
									btn.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data['response'] == 'invalidbooking') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);
									getBookingInformation(bookingnumber);
									say('Invalid Booking Number. Please refresh page.');
									btn.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						} else if (data['response'] == 'invalidaccess') {
							$(modal).modal('hide');
							$(document)
								.off('hidden.bs.modal', modal)
								.on('hidden.bs.modal', modal, function () {
									$(document).off('hidden.bs.modal', modal);
									getBookingInformation(bookingnumber);
									say('Unable to assign driver/helper. No user permission.');
									btn.removeClass('disabled');
									$('#loading-img').addClass('hidden');
								});
						}
					}
				}
			);

			function IsJsonString(str) {
				try {
					JSON.parse(str);
				} catch (e) {
					return false;
				}
				return true;
			}
		}
	});

$(document)
	.off('click', contentBK + " #booking-trans-updatestatusbtn:not('.disabled')")
	.on('click', contentBK + " #booking-trans-updatestatusbtn:not('.disabled')", function () {
		var modal = '#booking-updatebookingstatusmodal';
		var bookingnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');
		$(modal + ' .booking-updatebookingstatusmodal-bookingnumber').val(bookingnumber);
		$(modal + ' .modal-errordiv').empty();

		$.post(server + 'booking.php', { getBookingData: 'F#@!3R3ksk#Op1NEi34smo1sonk&$', txnnumber: bookingnumber }, function (response) {
			//alert(response);
			if (response.trim() == 'INVALID') {
				getBookingInformation(bookingnumber);
				say('Unable to assign driver/helper details. Invalid Booking Number');
				btn.removeClass('disabled');
			} else {
				$(modal).modal('show');
				$(document)
					.off('shown.bs.modal', modal)
					.on('shown.bs.modal', modal, function () {
						$(document).off('shown.bs.modal', modal);
						$(contentBK + ' .booking-updatebookingstatusmodal-bookingnumber').val(bookingnumber);

						if (data['driver'] != null) {
							$(contentBK + ' .booking-updatebookingstatusmodal-driver')
								.empty()
								.append('<option selected value="' + data['driver'] + '">' + data['driver'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-updatebookingstatusmodal-driver')
								.empty()
								.trigger('change');
						}

						if (data['helper'] != null) {
							$(contentBK + ' .booking-updatebookingstatusmodal-helper')
								.empty()
								.append('<option selected value="' + data['helper'] + '">' + data['helper'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-updatebookingstatusmodal-helper')
								.empty()
								.trigger('change');
						}

						if (data['platenumber'] != null) {
							$(contentBK + ' .booking-updatebookingstatusmodal-platenumber')
								.empty()
								.append('<option selected value="' + data['platenumber'] + '">' + data['platenumber'] + '</option>')
								.trigger('change');
						} else {
							$(contentBK + ' .booking-updatebookingstatusmodal-platenumber')
								.empty()
								.trigger('change');
						}

						$(contentBK + ' .booking-updatebookingstatusmodal-driver').select2({
							ajax: {
								url: 'loadables/dropdown/personnel.php?position=DRIVER&flag=1&type=' + data['vehicletypetype'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						$(contentBK + ' .booking-updatebookingstatusmodal-helper').select2({
							ajax: {
								url: 'loadables/dropdown/personnel.php?position=HELPER&flag=1&type=' + data['vehicletypetype'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						$(contentBK + ' .booking-updatebookingstatusmodal-platenumber').select2({
							ajax: {
								url: 'loadables/dropdown/vehicle.php?flag=1&type=' + data['vehicletypeid'],
								dataType: 'json',
								delay: 100,
								data: function (params) {
									return {
										q: params.term // search term
									};
								},
								processResults: function (data) {
									// parse the results into the format expected by Select2.
									// since we are using custom formatting functions we do not need to
									// alter the remote JSON data
									return {
										results: data
									};
								},
								cache: true
							},
							minimumInputLength: 0,
							width: '100%'
						});

						btn.removeClass('disabled');
					});
			}
		});
	});

$(document)
	.off('click', contentBK + " #booking-trans-resetvehicleinformationbtn:not('.disabled')")
	.on('click', contentBK + " #booking-trans-resetvehicleinformationbtn:not('.disabled')", function () {
		var modal = '#booking-resetvehicleinformationmodal';
		$(modal + ' .booking-resetvehicleinformationmodal-bookingnumber').val($(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number'));
		$(modal + ' .modal-errordiv').empty();
		$(modal).modal('show');
		$(document)
			.off('shown.bs.modal', modal)
			.on('shown.bs.modal', modal, function () {
				$(document).off('shown.bs.modal', modal);
				$(modal + ' .booking-resetvehicleinformationmodal-remarks').focus();
			});
	});

$(document)
	.off('click', contentBK + " #booking-resetvehicleinformationmodal-savebtn:not('.disabled')")
	.on('click', contentBK + " #booking-resetvehicleinformationmodal-savebtn:not('.disabled')", function () {
		var btn = $(this);
		var modal = '#' + $(this).closest('.modal').attr('id');
		btn.addClass('disabled');

		var bookingnumber = $(modal + ' .booking-resetvehicleinformationmodal-bookingnumber').val();
		var remarks = $(modal + ' .booking-resetvehicleinformationmodal-remarks').val();

		if (remarks.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a reason.</div></div>");
			$(modal + ' .booking-resetvehicleinformationmodal-remarks').focus();
			btn.removeClass('disabled');
		} else {
			$.confirm({
				animation: 'bottom',
				closeAnimation: 'top',
				animationSpeed: 1000,
				animationBounce: 1,
				title: 'Reset Details',
				content: 'Reset Driver/Helper Details for Booking Transaction[' + bookingnumber + ']. Do you want to continue?',
				confirmButton: 'Confirm',
				cancelButton: 'Cancel',
				confirmButtonClass: 'btn-oceanblue',
				cancelButtonClass: 'btn-royalblue',
				theme: 'white',

				confirm: function () {
					$('#loading-img').removeClass('hidden');
					$.post(server + 'booking.php', { resetDriverDetails: 'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7', bookingnumber: bookingnumber, remarks: remarks }, function (response) {
						if (IsJsonString(response) == true) {
							data = $.parseJSON(response);
						} else {
							data = 'NULL';
						}

						if (data == 'NULL') {
							alert(response);
							$('#loading-img').addClass('hidden');
							btn.removeClass('disabled');
						} else {
							if (data['response'] == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);
										getBookingInformation(bookingnumber);
										btn.removeClass('disabled');
										$('#loading-img').addClass('hidden');
										$(modal + ' .modal-errordiv').empty();
										$(modal + ' .booking-resetvehicleinformationmodal-remarks').val('');
									});
							} else if (data['response'] == 'invalidstatus') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);
										getBookingInformation(bookingnumber);
										say('Unable to update. Transaction has already been reset.');
										btn.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data['response'] == 'invalidbooking') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);
										getBookingInformation(bookingnumber);
										say('Invalid Booking Number. Please refresh page.');
										btn.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data['response'] == 'invalidaccess') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);
										getBookingInformation(bookingnumber);
										say('Unable to update. No user permission.');
										btn.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							}
						}
					});
				},
				cancel: function () {
					btn.removeClass('disabled');
				}
			});

			function IsJsonString(str) {
				try {
					JSON.parse(str);
				} catch (e) {
					return false;
				}
				return true;
			}
		}
	});

$(document)
	.off('click', contentBK + ' #booking-trans-viewstatushistorybtn:not(".disabled")')
	.on('click', contentBK + ' #booking-trans-viewstatushistorybtn:not(".disabled")', function () {
		$(contentBK + ' #booking-statushistorymodal').modal('show');

		var bookingnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');

		$(document)
			.off('shown.bs.modal', contentBK + ' #booking-statushistorymodal')
			.on('shown.bs.modal', contentBK + ' #booking-statushistorymodal', function () {
				$(document).off('shown.bs.modal', contentBK + ' #booking-statushistorymodal');
				$(contentBK + ' #booking-statushistorytbl')
					.flexOptions({
						url: 'loadables/ajax/transactions.booking.status-history.php?bookingnumber=' + bookingnumber,
						sortname: 'created_date',
						sortorder: 'desc'
					})
					.flexReload();
			});
	});

$(document)
	.off('click', contentBK + ' #booking-updatebookingstatusmodal-savebtn:not(".disabled")')
	.on('click', contentBK + ' #booking-updatebookingstatusmodal-savebtn:not(".disabled")', function () {
		var modal = '#' + $(this).closest('.modal').attr('id');
		var bookingid = $(contentBK + ' #pgtxnbooking-id').val();
		var bookingnumber = $(modal + ' .booking-updatebookingstatusmodal-bookingnumber').val();
		var actualpickupdate = $(modal + ' .booking-updatebookingstatusmodal-actualpickupdate').val();
		var pickedupby = $(modal + ' .booking-updatebookingstatusmodal-pickedupby').val();
		var driver = $(modal + ' .booking-updatebookingstatusmodal-driver').val();
		var helper = $(modal + ' .booking-updatebookingstatusmodal-helper').val();
		var platenumber = $(modal + ' .booking-updatebookingstatusmodal-platenumber').val();
		var remarks = $(modal + ' .booking-updatebookingstatusmodal-remarks').val();
		var status = $(modal + ' .booking-updatebookingstatusmodal-status').val();
		var button = $(this);
		button.addClass('disabled');
		$(modal + ' .modal-errordiv').empty();

		if (status == '' || status == null || status == 'NULL' || status == undefined || status == 'null') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select status.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-status').focus();
			button.removeClass('disabled');
		} else if (actualpickupdate.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide actual pickup date.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-actualpickupdate').focus();
			button.removeClass('disabled');
		} else if (pickedupby.trim() == '') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide picked-up by.</div></div>");
			$(modal + ' .booking-updatestattopickedmodal-pickedupby').focus();
			button.removeClass('disabled');
		} else if (driver == '' || driver == null || driver == 'NULL' || driver == undefined || driver == 'null') {
			$(modal + ' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select driver.</div></div>");
			$(modal + ' .booking-updatebookingstatusmodal-driver').select2('open');
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
						server + 'booking.php',
						{
							updateBookingStatus: 'kkjO#@siaah3h0$09odfj$owenxezpo92po@k@',
							bookingid: bookingid,
							actualpickupdate: actualpickupdate,
							pickedupby: pickedupby,
							remarks: remarks,
							status: status,
							driver: driver,
							helper: helper,
							platenumber: platenumber
						},
						function (data) {
							if (data.trim() == 'success') {
								$(modal).modal('hide');
								$(document)
									.off('hidden.bs.modal', modal)
									.on('hidden.bs.modal', modal, function () {
										$(document).off('hidden.bs.modal', modal);

										getBookingInformation(currentBookingTxn);
										$(modal + ' #booking-updatebookingstatusmodal-bookingid').val('');
										$(modal + ' .booking-updatebookingstatusmodal-bookingnumber').val('');
										$(modal + ' .booking-updatebookingstatusmodal-actualpickupdate').val('');
										$(modal + ' .booking-updatebookingstatusmodal-pickedupby').val('');
										$(modal + ' .booking-updatebookingstatusmodal-remarks').val('');
										$(modal + ' .booking-updatebookingstatusmodal-driver')
											.empty()
											.trigger('change');
										$(modal + ' .booking-updatebookingstatusmodal-helper')
											.empty()
											.trigger('change');
										$(modal + ' .booking-updatebookingstatusmodal-platenumber')
											.empty()
											.trigger('change');

										button.removeClass('disabled');
										$('#loading-img').addClass('hidden');
									});
							} else if (data.trim() == 'invalidbooking') {
								say('Unable to update status. ID: ' + bookingid + ' - Booking No.: ' + bookingnumber);
								button.removeClass('disabled');
								$('#loading-img').addClass('hidden');
							} else if (data.trim() == 'invaliddate') {
								$(modal + ' .modal-errordiv').html(
									"<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid actual pickup date.</div></div>"
								);
								$(modal + ' .booking-updatebookingstatusmodal-actualpickupdate').focus();
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

$(document)
	.off('click', contentBK + ' .bookingattachmentsremovebtn:not(".disabled")')
	.on('click', contentBK + ' .bookingattachmentsremovebtn:not(".disabled")', function () {
		let filename = $(this).attr('filename');
		let attachmentid = $(this).attr('attachmentid');
		let btn = $(this);
		let txnnumber = $(contentBK + ' #pgtxnbooking-id').attr('pgtxnbooking-number');
		btn.addClass('disabled');

		$.confirm({
			animation: 'bottom',
			closeAnimation: 'top',
			animationSpeed: 1000,
			animationBounce: 1,
			title: 'Delete Attachment',
			content: 'Deleting attachment: ' + filename + '. Do you want to continue?',
			confirmButton: 'Confirm',
			cancelButton: 'Cancel',
			confirmButtonClass: 'btn-oceanblue',
			cancelButtonClass: 'btn-royalblue',
			theme: 'white',

			confirm: function () {
				$('#loading-img').removeClass('hidden');

				$.post(server + 'booking.php', { deleteAttachment: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk', txnnumber: txnnumber, filename: filename, attachmentid: attachmentid }, function (data) {
					if (isJSON(data)) {
						rp = $.parseJSON(data);
						if (rp['status'] != 'ok') {
							say('Error: ' + rp['message']);
							console.log(data);
						}
						$(contentBK + ' #viewbookingattachments-table')
							.flexOptions({
								url: 'loadables/ajax/transactions.booking-attachments.php?bookingnumber=' + txnnumber,
								sortname: 'booking_number',
								sortorder: 'asc'
							})
							.flexReload();
					} else {
						say('Invalid JSON Data. Please contact system administrator');
						console.log(data);
					}
					btn.removeClass('disabled');
					$('#loading-img').addClass('hidden');
				});
			},
			cancel: function () {
				btn.removeClass('disabled');
			}
		});
	});

/************* SHIPMENT TYPE CHANGE - AUTO SET BOOKING TYPE ***************/
$(document)
    .off('change', contentBK + ' .booking-shipmenttype')
    .on('change', contentBK + ' .booking-shipmenttype', function () {
        if (processBK == 'add' || processBK == 'edit') {
            var selectedText = $(this).find('option:selected').text().trim().toUpperCase();
            var bookingTypeName = null;

            if (selectedText === 'DOMESTIC') {
                bookingTypeName = 'PICKUP';
            } else if (selectedText === 'INTERNATIONAL - EXPORT' || selectedText === 'INTERNATIONAL - IMPORT') {
                bookingTypeName = 'FOB';
            }

            if (bookingTypeName) {
                $.post(
                    server + 'booking.php',
                    { getDefaultBookingType: 'oiskus49Fnla3#Oih4noiI$IO@Y#*h@o3sk', name: bookingTypeName },
                    function (data) {
                        try {
                            data = JSON.parse(data);
                            if (data['response'] == 'success') {
                                $(inputfieldsBK + ' .booking-bookingtype')
                                    .empty()
                                    .append(
                                        '<option selected value="' + data['id'] + '">' + data['name'] + '</option>'
                                    )
                                    .trigger('change');
                            }
                        } catch (e) {
                            console.log('getDefaultBookingType error: ' + e);
                        }
                    }
                );
            } else {
                // Clear the booking type for any other shipment type
                $(inputfieldsBK + ' .booking-bookingtype')
                    .empty()
                    .trigger('change');
            }
        }
    });
/************* SHIPMENT TYPE CHANGE - END *********************************/
