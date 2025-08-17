
var tabshipper = '#shipper-menutabpane ';

function setActiveTab(tabid){
	var section = $(tabid).closest('.tabpanesection');
	$(tabid).addClass('active').siblings().removeClass('active');
	var pane = $(tabid).data('pane');
	$(pane).addClass('active').siblings().removeClass('active');
}



////// INSERT RATE ROW 
function shipperRateInsertRow(modal,table){
	$(modal+' .errordiv').empty();
	var originid = $(tabshipper+modal+' .originfld').val(),
	    origin = $(tabshipper+modal+' .originfld option:selected').text(),
	    destinationid = $(tabshipper+modal+' .destinationfld').val(),
	    destination = $(tabshipper+modal+' .destinationfld option:selected').text(),
	    modeoftransportid = $(tabshipper+modal+' .modeoftransportfld').val(),
	    modeoftransport = $(tabshipper+modal+' .modeoftransportfld option:selected').text(),
	    freightcomputation = $(tabshipper+modal+' .freightcomputationfld').val(),
	    fixedrate = $(tabshipper+modal+' .fixedrateflagfld').prop('checked'),
	    rush = $(tabshipper+modal+' .rushflagfld').prop('checked'),
	    pullout = $(tabshipper+modal+' .pulloutflagfld').prop('checked'),
	    valuation = $(tabshipper+modal+' .valuationfld').val(),
	    freightrate = $(tabshipper+modal+' .freightratefld').val(),
	    insurancerate = $(tabshipper+modal+' .insuranceratefld').val(),
	    fuelrate = $(tabshipper+modal+' .fuelratefld').val(),
	    bunkerrate = $(tabshipper+modal+' .bunkerratefld').val(),
	    minimumrate = $(tabshipper+modal+' .minimumratefld').val();
	   
	    fixedrateflag = "<select class='form-control shipperrate-fixedrateflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";
	    if(fixedrate==0){
	    	fixedrateflag = "<select class='form-control shipperrate-fixedrateflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }

	    rushflag = "<select class='form-control shipperrate-rushflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";
	    if(rush==0){
	    	rushflag = "<select class='form-control shipperrate-rushflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }

	    pulloutflag = "<select class='form-control shipperrate-pulloutflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";
	    if(pullout==0){
	    	pulloutflag = "<select class='form-control shipperrate-pulloutflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }

	    if((originid!=''&&originid!=null)&&
	       (destinationid!=''&&destinationid!=null)&&
	       (modeoftransportid!=''&&modeoftransportid!=null)&&
	       (freightcomputation!='')&&
	       valuation.trim()!=''&&freightrate.trim()!=''&&insurancerate.trim()!=''&&fuelrate.trim()!=''&&bunkerrate!=''&&minimumrate!='')
	    {

			table.DataTable().row.add([
						"<input type='checkbox' class='rowcheckbox'/>",
						fixedrateflag,
						rushflag,
						pulloutflag,
						"<span class='shipperrate-origin' originid='"+originid+"'>"+origin+"</span>",
						"<span class='shipperrate-destination' destinationid='"+destinationid+"'>"+destination+"</span>",
						"<span class='shipperrate-modeoftransport' modeoftransportid='"+modeoftransportid+"'>"+modeoftransport+"</span>",
						"<span class='shipperrate-freighcomputation'>"+freightcomputation+"</span>",
						"<span class='shipperrate-valuation'>"+valuation+"</span>",
						"<span class='shipperrate-freightrate'>"+freightrate+"</span>",
						"<span class='shipperrate-insurancerate'>"+insurancerate+"</span>",
						"<span class='shipperrate-fuelrate'>"+fuelrate+"</span>",
						"<span class='shipperrate-bunkerrate'>"+bunkerrate+"</span>",
						"<span class='shipperrate-minimumrate'>"+minimumrate+"</span>"

			]).draw();
	    	shipperRateClearRatefields(modal);
		}
		else{
			$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span><b>New Rate</b>: Please provide all the information</div></div>");
		}
}

$(document).off('click',tabshipper+' .shipper-insertratebtn').on('click',tabshipper+' .shipper-insertratebtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
	    shipperRateInsertRow(modal,$(tabshipper+modal+' .shipperratedetailstbl'));
});
////// INSERT RATE ROW END


////// REMOVE RATE ROW
function shipperRateRemoveRow(modal,table){
	$(tabshipper+modal+' '+table+' tbody tr .rowcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabshipper+modal+' '+table).DataTable().row(tr).remove().draw();
	});
}

$(document).off('click',tabshipper+' .shipper-removeratebtn').on('click',tabshipper+' .shipper-removeratebtn',function(){
		var tbl = '.shipperratedetailstbl';
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperContactRemoveRow(modal,tbl);
});
/////// REMOVE RATE ROW END


////// CLEAR RATE INPUT FIELDS
function shipperRateClearRatefields(modal){
		/*$(tabshipper+modal+' .originfld').empty().trigger('change');
	    $(tabshipper+modal+' .destinationfld').empty().trigger('change');*/
	    $(tabshipper+modal+' .modeoftransportfld').empty().trigger('change');
	    $(tabshipper+modal+' .freightcomputationfld').empty().trigger('change');
	    $(tabshipper+modal+' .valuationfld').val('');
	    $(tabshipper+modal+' .freightratefld').val('');
	    $(tabshipper+modal+' .insuranceratefld').val('');
	    $(tabshipper+modal+' .fuelratefld').val('');
	    $(tabshipper+modal+' .bunkerratefld').val('');
	    $(tabshipper+modal+' .minimumratefld').val('');
}

$(document).off('click',tabshipper+' .shipper-clearcontactfieldsbtn').on('click',tabshipper+' .shipper-clearcontactfieldsbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperContactClearContactfields(modal);
});
////// CLEAR RATE INPUT FIELDS END



////// INSERT PICKUP ADDRESS ROW
function shipperPickupAddressInsertRow(modal,table){

	var street = $(tabshipper+modal+' .pickupstreetfld').val(),
	    district = $(tabshipper+modal+' .pickupdistrictfld').val(),
	    city = $(tabshipper+modal+' .pickupcityfld').val(),
	    province = $(tabshipper+modal+' .pickupprovincefld').val(),
	    zipcode = $(tabshipper+modal+' .pickupzipcodefld').val(),
	    country = $(tabshipper+modal+' .pickupcountryfld').val(),
	    defaultflag = "<select class='form-control defaultpickupaddressflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";

	    if(parseInt(table.find("option[value='1']:selected").length)>0){
	    	defaultflag = "<select class='form-control defaultpickupaddressflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }
	    if(
	    		street!=''&&
	    		(district!='null'&&district!=''&&district!=null&&district!='NULL') &&
	            (zipcode!='null'&&zipcode!=''&&zipcode!=null&&zipcode!='NULL') &&
	            (city!='null'&&city!=''&&city!=null&&city!='NULL') &&
	            (province!='null'&&province!=''&&province!=null&&province!='NULL') &&
	            (country!='null'&&country!=''&&country!=null&&country!='NULL')
	       ){

			table.DataTable().row.add([
						"<input type='checkbox' class='rowcheckbox'/>",
						defaultflag,
						"<span class='shipperpickupaddress-street'>"+street+"</span>",
						"<span class='shipperpickupaddress-district'>"+district+"</span>",
						"<span class='shipperpickupaddress-city'>"+city+"</span>",
						"<span class='shipperpickupaddress-province'>"+province+"</span>",
						"<span class='shipperpickupaddress-zipcode'>"+zipcode+"</span>",
						"<span class='shipperpickupaddress-country'>"+country+"</span>"
			]).draw();
	    	shipperPickupAddressClearfields(modal);
		}
}

$(document).off('click',tabshipper+' .shipper-insertpickupdaddressbtn').on('click',tabshipper+' .shipper-insertpickupdaddressbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
	    shipperPickupAddressInsertRow(modal,$(tabshipper+modal+' .shipperpickupaddressdetailstbl'));
});
////// INSERT PICKUP ADDRESS ROW - END

////// REMOVE PICKUP ADDRESS ROW
function shipperPickupAddressRemoveRow(modal,table){
	$(tabshipper+modal+' '+table+' tbody tr .rowcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabshipper+modal+' '+table).DataTable().row(tr).remove().draw();
	});
}

$(document).off('click',tabshipper+' .shipper-removepickupdaddressbtn').on('click',tabshipper+' .shipper-removepickupdaddressbtn',function(){
		var tbl = '.shipperpickupaddressdetailstbl';
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperPickupAddressRemoveRow(modal,tbl);
});
/////// REMOVE PICKUP ADDRESS ROW END

////// CLEAR PICKUP ADDRESS  INPUT FIELDS
function shipperPickupAddressClearfields(modal){
		$(tabshipper+modal+' .pickupstreetfld').val('').focus();
	    $(tabshipper+modal+' .pickupdistrictfld').val('').trigger('change');
	    $(tabshipper+modal+' .pickupcityfld').val('').trigger('change');
	    $(tabshipper+modal+' .pickupprovincefld').val('').trigger('change');
	    $(tabshipper+modal+' .pickupzipcodefld').val('').trigger('change');
	    //$(tabshipper+modal+' .pickupcountryfld').val('').trigger('change');
}

$(document).off('click',tabshipper+' .shipper-clearpickupdaddressbtn').on('click',tabshipper+' .shipper-clearpickupdaddressbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperPickupAddressClearfields(modal);
});
////// CLEAR PICKUP ADDRESS  INPUT FIELDS END


////// DEFAULT FLAG YES LIMIT TO 1
$(document).off('change',tabshipper+' .defaultpickupaddressflag').on('change',tabshipper+' .defaultpickupaddressflag',function(){
	$(this).closest('tr').siblings().find('.defaultpickupaddressflag').val(0);
});
////// DEFAULT FLAG YES LIMIT TO 1 END






////// INSERT CONTACT ROW
function shipperContactInsertRow(modal,table){

	var contact = $(tabshipper+modal+' .contactfld').val(),
	    phone = $(tabshipper+modal+' .phonenumberfld').val(),
	    email = $(tabshipper+modal+' .emailfld').val(),
	    mobile = $(tabshipper+modal+' .mobilenumberfld').val(),
	    defaultflag = "<select class='form-control defaultcontactflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";

	    if(parseInt(table.find("option[value='1']:selected").length)>0){
	    	defaultflag = "<select class='form-control defaultcontactflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
	    }
	    if(contact.trim()!=''&&(phone.trim()!=''||email.trim()!=''||mobile.trim()!='')){

			table.DataTable().row.add([
						"<input type='checkbox' class='rowcheckbox'/>",
						defaultflag,
						"<select class='form-control sendsmsflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>",
						"<select class='form-control sendemailflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>",
						"<span class='shippercontact-contact'>"+contact+"</span>",
						"<span class='shippercontact-phone'>"+phone+"</span>",
						"<span class='shippercontact-email'>"+email+"</span>",
						"<span class='shippercontact-mobile'>"+mobile+"</span>"
			]).draw();
	    	shipperContactClearContactfields(modal);
		}
}

$(document).off('click',tabshipper+' .shipper-insertcontactbtn').on('click',tabshipper+' .shipper-insertcontactbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
	    shipperContactInsertRow(modal,$(tabshipper+modal+' .shippercontactdetailstbl'));
});
////// INSERT CONTACT ROW END


////// REMOVE CONTACT ROW
function shipperContactRemoveRow(modal,table){
	$(tabshipper+modal+' '+table+' tbody tr .rowcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabshipper+modal+' '+table).DataTable().row(tr).remove().draw();
	});
}

$(document).off('click',tabshipper+' .shipper-removecontactbtn').on('click',tabshipper+' .shipper-removecontactbtn',function(){
		var tbl = '.shippercontactdetailstbl';
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperContactRemoveRow(modal,tbl);
});
/////// REMOVE CONTACT ROW END



////// CLEAR CONTACT INPUT FIELDS
function shipperContactClearContactfields(modal){
		$(tabshipper+modal+' .contactfld').val('');
	    $(tabshipper+modal+' .phonenumberfld').val('');
	    $(tabshipper+modal+' .emailfld').val('');
	    $(tabshipper+modal+' .mobilenumberfld').val('');
	    $(tabshipper+modal+' .contactfld').focus();
}

$(document).off('click',tabshipper+' .shipper-clearcontactfieldsbtn').on('click',tabshipper+' .shipper-clearcontactfieldsbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		shipperContactClearContactfields(modal);
});
////// CLEAR CONTACT INPUT FIELDS END


////// DEFAULT FLAG YES LIMIT TO 1
$(document).off('change',tabshipper+' .defaultcontactflag').on('change',tabshipper+' .defaultcontactflag',function(){
	$(this).closest('tr').siblings().find('.defaultcontactflag').val(0);
});
////// DEFAULT FLAG YES LIMIT TO 1 END



////// CLEAR MODAL FIELDS
function shipperClearInputFields(modal,type){
	$(modal+' .inputtxtfld').val(''),
	$(modal+' .inputslctfld').val('').trigger('change');
	if(type=='edit'){
		$(modal+' .mdlIDfld').val('');
	}
}
////// CLEAR MODAL FIELDS END




////// CLEAR MODAL FIELDS
function shipperClearInputFields(modal,type){
	$(modal+' .inputtxtfld').val(''),
	$(modal+' .inputslctfld').val('').trigger('change');
	if(type=='edit'){
		$(modal+' .mdlIDfld').val('');
	}
}
////// CLEAR MODAL FIELDS END






////////////////// MAIN BUTTONS - ADD & EDIT
$(document).off('click','.shippermodal-savebtn:not(".disabled")').on('click','.shippermodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		accountnumber = $(modal+' .accountnumber').val(),
		accountname = $(modal+' .accountname').val(),
		companyname = $(modal+' .companyname').val(),
		billingincharge = $(modal+' .billingincharge').val(),
		accountexecutive = $(modal+' .accountexecutive').val(),
		nonpodflag = $(modal+' .nonpodflag').val(),
		vatflag = $(modal+' .vatflag').val(),
		shipperstatus = $(modal+' .shipperstatus').val(),
		paymode = $(modal+' .paymode').val(),
		businessstyle = $(modal+' .businessstyle').val(),
		creditlimit = $(modal+' .creditlimit').val(),
		creditterm = $(modal+' .creditterm').val(),
		tin = $(modal+' .tin').val(),
		lineofbusiness = $(modal+' .lineofbusiness').val(),
		podinstruction = $(modal+' .podinstruction').val(),
		collectioncontactperson = $(modal+' .collectioncontactperson').val(),
		collectionday = $(modal+' .collectionday').val(),
		collectionlocation = $(modal+' .collectionlocation').val(),
		billingcutoff = $(modal+' .billingcutoff').val(),
		companystreet = $(modal+' .companystreet').val(),
		companydistrict = $(modal+' .companydistrict').val(),
		companycity = $(modal+' .companycity').val(),
		companyprovince = $(modal+' .companyprovince').val(),
		companyzipcode = $(modal+' .companyzipcode').val(),
		companycountry = $(modal+' .companycountry').val(),
		billingstreet = $(modal+' .billingstreet').val(),
		billingdistrict = $(modal+' .billingdistrict').val(),
		billingcity = $(modal+' .billingcity').val(),
		billingprovince = $(modal+' .billingprovince').val(),
		billingzipcode = $(modal+' .billingzipcode').val(),
		billingcountry = $(modal+' .billingcountry').val(),
		/*pickupstreet = $(modal+' .pickupstreet').val(),
		pickupdistrict = $(modal+' .pickupdistrict').val(),
		pickupcity = $(modal+' .pickupcity').val(),
		pickupprovince = $(modal+' .pickupprovince').val(),
		pickupzipcode = $(modal+' .pickupzipcode').val(),
		pickupcountry = $(modal+' .pickupcountry').val(),*/
		contactcount = $(modal+' .shippercontactdetailstbl tbody tr.mydatatablerow').length,
		ratecount = $(modal+' .shipperratedetailstbl tbody tr.mydatatablerow').length,
		pickupaddrcount = $(modal+' .shipperpickupaddressdetailstbl tbody tr.mydatatablerow').length,
		/*returndocumentfee = $(modal+' .returndocumentfee').val(),
		waybillfee = $(modal+' .waybillfee').val(),
		securityfee = $(modal+' .securityfee').val(),
		docstampfee = $(modal+' .docstampfee').val(),*/
		returndocumentfee = 0,
		waybillfee = 0,
		securityfee = 0,
		docstampfee = 0,
		contact = [],
		email = [],
		phonenumber = [],
		mobilenumber = [],
		defaultflag = [],
		sendsmsflag = [],
		sendemailflag = [],
		fixedrate = [],
		origin = [],
		destination = [],
		modeoftransport = [],
		freightcomputation = [],
		valuation = [],
		fuelrate = [],
		insurancerate = [],
		freightrate = [],
		bunkerrate = [],
		minimumrate = [],
		pickupstreet = [],
		pickupdistrict = [],
		pickupcity = [],
		pickupprovince = [],
		pickupzipcode = [],
		pickupcountry = [],
		defaultpickupaddressflag = [],
		id='',
		inactiveflag = 0,
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editshippermodal'){
		id = $(modal+' .shippermodalid').val();
		inactiveflag = $(modal+' .inactiveflag').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	
	if(accountnumber==''&&modal=='#editshippermodal'){
		setActiveTab('#shipperaddgeninfo-tab');
		$(modal+' .accountnumber').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide Account Number.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(accountname==''){
		setActiveTab('#shipperaddgeninfo-tab');
		$(modal+' .accountname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide Account Name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companyname==''){
		setActiveTab('#shipperaddgeninfo-tab');
		$(modal+' .companyname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide company name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(paymode==''||paymode=='NULL'||paymode=='null'||paymode==null){
		setActiveTab('#shipperaddgeninfo-tab');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pay mode.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(parseInt(contactcount)==0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide at least 1 contact information</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companyprovince==''||companyprovince=='NULL'||companyprovince=='null'||companyprovince==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companyprovince').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select company region/province.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companycity==''||companycity=='NULL'||companycity=='null'||companycity==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companycity').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select company city.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companydistrict==''||companydistrict=='NULL'||companydistrict=='null'||companydistrict==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companydistrict').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select company district.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companyzipcode==''||companyzipcode=='NULL'||companyzipcode=='null'||companyzipcode==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companyzipcode').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select company zip code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else if(companystreet==''){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companystreet').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide company street address.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(companycountry==''||companycountry=='NULL'||companycountry=='null'||companycountry==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .companycountry').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select company country.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(billingprovince==''||billingprovince=='NULL'||billingprovince=='null'||billingprovince==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingprovince').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing region/province.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(billingcity==''||billingcity=='NULL'||billingcity=='null'||billingcity==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingcity').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing city.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(billingdistrict==''||billingdistrict=='NULL'||billingdistrict=='null'||billingdistrict==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingdistrict').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing district.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(billingzipcode==''||billingzipcode=='NULL'||billingzipcode=='null'||billingzipcode==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingzipcode').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing zip code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else if(billingstreet==''){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingstreet').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide billing street address.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(billingcountry==''||billingcountry=='NULL'||billingcountry=='null'||billingcountry==null){
		setActiveTab('#shipperaddaddress-tab');
		$(modal+' .billingcountry').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select billing country.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	
	else if(parseInt(pickupaddrcount)==0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide at least 1 Pickup Address</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$(modal+' .errordiv').empty();
		$('#loading-img').removeClass('hidden');
		$(tabshipper+modal+' .shippercontactdetailstbl tbody tr.mydatatablerow').each(function(){
				contact.push($(this).find('.shippercontact-contact').text());
				email.push($(this).find('.shippercontact-email').text());
				phonenumber.push($(this).find('.shippercontact-phone').text());
				mobilenumber.push($(this).find('.shippercontact-mobile').text());
				defaultflag.push($(this).find('.defaultcontactflag').val());
				sendsmsflag.push($(this).find('.sendsmsflag').val());
				sendemailflag.push($(this).find('.sendemailflag').val());
		});
		$(tabshipper+modal+' .shipperratedetailstbl tbody tr.mydatatablerow').each(function(){
				fixedrate.push($(this).find('.shipperrate-fixedrateflag').val());
				origin.push($(this).find('.shipperrate-origin').attr('originid'));
				destination.push($(this).find('.shipperrate-destination').attr('destinationid'));
				modeoftransport.push($(this).find('.shipperrate-modeoftransport').attr('modeoftransportid'));
				freightcomputation.push($(this).find('.shipperrate-freighcomputation').text());
				valuation.push($(this).find('.shipperrate-valuation').text());
				freightrate.push($(this).find('.shipperrate-freightrate').text());
				insurancerate.push($(this).find('.shipperrate-insurancerate').text());
				fuelrate.push($(this).find('.shipperrate-fuelrate').text());
				bunkerrate.push($(this).find('.shipperrate-bunkerrate').text());
				minimumrate.push($(this).find('.shipperrate-minimumrate').text());
		});

		$(tabshipper+modal+' .shipperpickupaddressdetailstbl tbody tr.mydatatablerow').each(function(){
				pickupstreet.push($(this).find('.shipperpickupaddress-street').text());
				pickupdistrict.push($(this).find('.shipperpickupaddress-district').text());
				pickupcity.push($(this).find('.shipperpickupaddress-city').text());
				pickupprovince.push($(this).find('.shipperpickupaddress-province').text());
				pickupzipcode.push($(this).find('.shipperpickupaddress-zipcode').text());
				pickupcountry.push($(this).find('.shipperpickupaddress-country').text());
				defaultpickupaddressflag.push($(this).find('.defaultpickupaddressflag').val());

				
		});

		
		
		$.post(server+'shipper.php',{shipperSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,accountnumber:accountnumber,accountname:accountname,companyname:companyname,billingincharge:billingincharge,accountexecutive:accountexecutive,nonpodflag:nonpodflag,vatflag:vatflag,shipperstatus:shipperstatus,companystreet:companystreet,companydistrict:companydistrict,companycity:companycity,companyprovince:companyprovince,companyzipcode:companyzipcode,companycountry:companycountry,billingstreet:billingstreet,billingdistrict:billingdistrict,billingcity:billingcity,billingprovince:billingprovince,billingzipcode:billingzipcode,billingcountry:billingcountry,pickupstreet:pickupstreet,pickupdistrict:pickupdistrict,pickupcity:pickupcity,pickupprovince:pickupprovince,pickupzipcode:pickupzipcode,pickupcountry:pickupcountry,defaultpickupaddressflag:defaultpickupaddressflag,inactiveflag:inactiveflag,contact:contact,email:email,phonenumber:phonenumber,mobilenumber:mobilenumber,defaultflag:defaultflag,sendsmsflag:sendsmsflag,sendemailflag:sendemailflag,fixedrate:fixedrate,origin:origin,destination:destination,modeoftransport:modeoftransport,freightcomputation:freightcomputation,valuation:valuation,freightrate:freightrate,insurancerate:insurancerate,fuelrate:fuelrate,bunkerrate:bunkerrate,minimumrate:minimumrate,paymode:paymode,businessstyle:businessstyle,creditterm:creditterm,creditlimit:creditlimit,lineofbusiness:lineofbusiness,tin:tin,billingcutoff:billingcutoff,collectionlocation:collectionlocation,collectionday:collectionday,collectioncontactperson:collectioncontactperson,podinstruction:podinstruction,returndocumentfee:returndocumentfee,waybillfee:waybillfee,securityfee:securityfee,docstampfee:docstampfee},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#shippertable').flexOptions({
											url:'loadables/ajax/maintenance.shipper.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');


						shipperClearInputFields(modal,source);
						$(tabshipper+modal+' .shippercontactdetailstbl').DataTable().clear().draw();
						$(tabshipper+modal+' .shipperratedetailstbl').DataTable().clear().draw();
						$(tabshipper+modal+' .shipperpickupaddressdetailstbl').DataTable().clear().draw();
						$(modal+' .errordiv').empty();

					});
				}
				else if(data.trim()=='codeexist'){
					$('#loading-img').addClass('hidden');
					$(modal+' .accountnumber').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Account Number exists. Please provide a different account number.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else if(data.trim()=='invalidcreditlimit'){
					$('#loading-img').addClass('hidden');
					$(modal+' .creditlimit').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid Credit Limit. Provided value cannot be less than shipper outstanding balance.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}
});




$(document).off('click','.editshipperbtn').on('click','.editshipperbtn',function(){
	var modal = "#editshippermodal";
	var rowid = $(this).attr('rowid');

	shipperClearInputFields(modal,'edit');
	$(tabshipper+modal+' .shippercontactdetailstbl').DataTable().search('').clear().draw();
	$(tabshipper+modal+' .shipperratedetailstbl').DataTable().search('').clear().draw();
	$(tabshipper+modal+' .shipperpickupaddressdetailstbl').DataTable().search('').clear().draw();

	$(modal+' .shippermodalid').val(rowid);

	$.post(server+'shipper.php',{ShipperGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		
		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .accountnumber').val(rsp['accountnumber']);
			$(modal+' .accountname').val(rsp['accountname']);
			$(modal+' .companyname').val(rsp['companyname']);
			$(modal+' .nonpodflag').val(rsp['nonpodflag']).trigger('change');
			$(modal+' .vatflag').val(rsp['vatflag']).trigger('change');
			$(modal+' .shipperstatus').val(rsp['shipperstatus']).trigger('change');
			$(modal+' .companystreet').val(rsp['companystreet']);
			$(modal+' .companydistrict').val(rsp['companydistrict']);
			$(modal+' .companycity').val(rsp['companycity']);
			$(modal+' .companyprovince').val(rsp['companyprovince']);
			$(modal+' .companyzipcode').val(rsp['companyzipcode']);
			$(modal+' .billingstreet').val(rsp['billingstreet']);
			$(modal+' .billingdistrict').val(rsp['billingdistrict']);
			$(modal+' .billingcity').val(rsp['billingcity']);
			$(modal+' .billingprovince').val(rsp['billingprovince']);
			$(modal+' .billingzipcode').val(rsp['billingzipcode']);
			$(modal+' .inactiveflag').val(rsp['inactiveflag']).trigger('change');
			$(modal+' .businessstyle').val(rsp['businessstyle']);
			$(modal+' .creditlimit').val(rsp['creditlimit']);
			$(modal+' .creditterm').val(rsp['creditterm']);

			$(modal+' .podinstruction').val(rsp['podinstruction']);
			$(modal+' .tin').val(rsp['tin']);
			$(modal+' .lineofbusiness').val(rsp['lineofbusiness']);
			$(modal+' .collectioncontactperson').val(rsp['collectioncontactperson']);
			$(modal+' .billingcutoff').val(rsp['billingcutoff']);
			$(modal+' .collectionday').val(rsp['collectionday']);
			$(modal+' .collectionlocation').val(rsp['collectionlocation']);

			if(rsp['creditlimitaccess']==1){
				$(modal+' .creditlimit').removeAttr('disabled');
			}
			else{
				$(modal+' .creditlimit').attr('disabled',true);
			}
			

			if(rsp['viewcreditinfoaccess']==1){
				$(tabshipper+' .creditlimitwrapper').removeClass('hidden');
			}
			else{
				$(tabshipper+' .creditlimitwrapper').addClass('hidden');
			}
			

			//$(modal+' .returndocumentfee').val(rsp['returndocumentfee']);
			//$(modal+' .waybillfee').val(rsp['waybillfee']);
			//$(modal+' .securityfee').val(rsp['securityfee']);
			//$(modal+' .docstampfee').val(rsp['docstampfee']);

			if(rsp["companydistrict"]!=null){
		        $(modal+" .companydistrict").empty().append('<option selected value="'+rsp["companydistrict"]+'">'+rsp["companydistrict"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .companydistrict").empty().trigger('change');
		    }
		    if(rsp["companycity"]!=null){
		        $(modal+" .companycity").empty().append('<option selected value="'+rsp["companycity"]+'">'+rsp["companycity"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .companycity").empty().trigger('change');
		    }
		    if(rsp["companyprovince"]!=null){
		        $(modal+" .companyprovince").empty().append('<option selected value="'+rsp["companyprovince"]+'">'+rsp["companyprovince"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .companyprovince").empty().trigger('change');
		    }
		    if(rsp["companyzipcode"]!=null){
		        $(modal+" .companyzipcode").empty().append('<option selected value="'+rsp["companyzipcode"]+'">'+rsp["companyzipcode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .companyzipcode").empty().trigger('change');
		    }

		    if(rsp["companycountry"]!=null){
		        $(modal+" .companycountry").empty().append('<option selected value="'+rsp["companycountry"]+'">'+rsp["companycountry"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .companycountry").empty().trigger('change');
		    }
		    


		    if(rsp["billingdistrict"]!=null){
		        $(modal+" .billingdistrict").empty().append('<option selected value="'+rsp["billingdistrict"]+'">'+rsp["billingdistrict"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingdistrict").empty().trigger('change');
		    }
		    if(rsp["billingcity"]!=null){
		        $(modal+" .billingcity").empty().append('<option selected value="'+rsp["billingcity"]+'">'+rsp["billingcity"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingcity").empty().trigger('change');
		    }
		    if(rsp["billingprovince"]!=null){
		        $(modal+" .billingprovince").empty().append('<option selected value="'+rsp["billingprovince"]+'">'+rsp["billingprovince"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingprovince").empty().trigger('change');
		    }
		    if(rsp["billingzipcode"]!=null){
		        $(modal+" .billingzipcode").empty().append('<option selected value="'+rsp["billingzipcode"]+'">'+rsp["billingzipcode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingzipcode").empty().trigger('change');
		    }
		    if(rsp["billingcountry"]!=null){
		        $(modal+" .billingcountry").empty().append('<option selected value="'+rsp["billingcountry"]+'">'+rsp["billingcountry"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingcountry").empty().trigger('change');
		    }
		    if(rsp["pickupcountry"]!=null){
		        $(modal+" .pickupcountry").empty().append('<option selected value="'+rsp["pickupcountry"]+'">'+rsp["pickupcountry"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .pickupcountry").empty().trigger('change');
		    }
		    if(rsp["billingincharge"]!=null){
		        $(modal+" .billingincharge").empty().append('<option selected value="'+rsp["billinginchargeid"]+'">'+rsp["billingincharge"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .billingincharge").empty().trigger('change');
		    }
		    if(rsp["accountexecutive"]!=null){
		        $(modal+" .accountexecutive").empty().append('<option selected value="'+rsp["accountexecutiveid"]+'">'+rsp["accountexecutive"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .accountexecutive").empty().trigger('change');
		    }

		    if(rsp["paymode"]!=null){
		        $(modal+" .paymode").empty().append('<option selected value="'+rsp["paymodeid"]+'">'+rsp["paymode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .paymode").empty().trigger('change');
		    }

		    $("#editshippermodal .pickupcountryfld").empty().append('<option selected value="Philippines">Philippines</option>').trigger('change');
			

		    $.post(server+'shipper.php',{ShipperContactGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data2){
		    	//alert(data2);
			    	rsp1 = $.parseJSON(data2);
			    	for(var i=0;i<rsp1.length;i++){
					    if(rsp1[i]['defaultflag']==1){
			    			defaultflag = "<select class='form-control defaultcontactflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";	
					    }
					    else{
					    	defaultflag = "<select class='form-control defaultcontactflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
					    }

					    if(rsp1[i]['sendsmsflag']==1){
			    			sendsmsflag = "<select class='form-control sendsmsflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";	
					    }
					    else{
					    	sendsmsflag = "<select class='form-control sendsmsflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
					    }

					    if(rsp1[i]['sendemailflag']==1){
			    			sendemailflag = "<select class='form-control sendemailflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";	
					    }
					    else{
					    	sendemailflag = "<select class='form-control sendemailflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
					    }

			    		$(tabshipper+modal+' .shippercontactdetailstbl').DataTable().row.add([
								"<input type='checkbox' class='rowcheckbox'/>",
								defaultflag,
								sendsmsflag,
								sendemailflag,
								"<span class='shippercontact-contact'>"+rsp1[i]['contact']+"</span>",
								"<span class='shippercontact-phone'>"+rsp1[i]['phone']+"</span>",
								"<span class='shippercontact-email'>"+rsp1[i]['email']+"</span>",
								"<span class='shippercontact-mobile'>"+rsp1[i]['mobile']+"</span>"
						]).draw();
			    	}


		    	


			    	$.post(server+'shipper.php',{ShipperPickupAddressGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data4){
			    	
				    	rsp3 = $.parseJSON(data4);
				    	for(var i=0;i<rsp3.length;i++){


							var defaultpckflag = "<select class='form-control defaultpickupaddressflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
						    if(rsp3[i]['defaultpickupaddressflag']==1){
						    	defaultpckflag = "<select class='form-control defaultpickupaddressflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";
						    }
						    
						    $(tabshipper+modal+' .shipperpickupaddressdetailstbl').DataTable().row.add([
											"<input type='checkbox' class='rowcheckbox'/>",
											defaultpckflag,
											"<span class='shipperpickupaddress-street'>"+rsp3[i]['pickupstreet']+"</span>",
											"<span class='shipperpickupaddress-district'>"+rsp3[i]['pickupdistrict']+"</span>",
											"<span class='shipperpickupaddress-city'>"+rsp3[i]['pickupcity']+"</span>",
											"<span class='shipperpickupaddress-province'>"+rsp3[i]['pickupprovince']+"</span>",
											"<span class='shipperpickupaddress-zipcode'>"+rsp3[i]['pickupzipcode']+"</span>",
											"<span class='shipperpickupaddress-country'>"+rsp3[i]['pickupcountry']+"</span>"
							]).draw();
				    	}

				    	/*$.post(server+'shipper.php',{ShipperRateGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data3){
			    	
					    	rsp2 = $.parseJSON(data3);
					    	for(var i=0;i<rsp2.length;i++){



							    fixedrateflag = "<select class='form-control shipperrate-fixedrateflag'><option value='1'>Yes</option><option value='0' selected>No</option></select>";
							    if(rsp2[i]['fixedrateflag']==1){
							    	fixedrateflag = "<select class='form-control shipperrate-fixedrateflag'><option value='1' selected>Yes</option><option value='0'>No</option></select>";
							    }


					    		$(tabshipper+modal+' .shipperratedetailstbl').DataTable().row.add([
										"<input type='checkbox' class='rowcheckbox'/>",
										fixedrateflag,
										"<span class='shipperrate-origin' originid='"+rsp2[i]['originid']+"'>"+rsp2[i]['origin']+"</span>",
										"<span class='shipperrate-destination' destinationid='"+rsp2[i]['destinationid']+"'>"+rsp2[i]['destination']+"</span>",
										"<span class='shipperrate-modeoftransport' modeoftransportid='"+rsp2[i]['modeoftransportid']+"'>"+rsp2[i]['modeoftransport']+"</span>",
										"<span class='shipperrate-freighcomputation'>"+rsp2[i]['freightcomputation']+"</span>",
										"<span class='shipperrate-valuation'>"+rsp2[i]['valuation']+"</span>",
										"<span class='shipperrate-freightrate'>"+rsp2[i]['freightrate']+"</span>",
										"<span class='shipperrate-insurancerate'>"+rsp2[i]['insurancerate']+"</span>",
										"<span class='shipperrate-fuelrate'>"+rsp2[i]['fuelrate']+"</span>",
										"<span class='shipperrate-bunkerrate'>"+rsp2[i]['bunkerrate']+"</span>",
										"<span class='shipperrate-minimumrate'>"+rsp2[i]['minimumrate']+"</span>"
								]).draw();
					    	}

						    });

					    });*/


				    });

		    });
		    

		}
		else{
			
			$(modal).modal('hide');
			$(modal).on('hidden.bs.modal',function(){
				$(modal).off('hidden.bs.modal');
				say('Unable to load data. Invalid ID.');
			});
		}
		
	});

	
});
////////////////// MAIN BUTTONS - ADD & EDIT END




/************** ATTACHMENTS ***********************************/
$(document).off('click',tabshipper+' .viewshipperattachmentbtn:not(".disabled")').on('click',tabshipper+' .viewshipperattachmentbtn:not(".disabled")',function(){
	var id = $(this).attr('rowid');
	$(tabshipper+' #viewshipperattachmentmodal-shipperid').val(id);
	$(tabshipper+' #addshipperattachmentmodal-shipperid').val(id);
	$(tabshipper+" #viewshipperattachmentmodal-tbl").flexOptions({
		url:'loadables/ajax/maintenance.shipper-attachments.php?shipper='+id,
		sortname: "created_date",
		sortorder: "desc"
	}).flexReload();
});

$(document).off('click',tabshipper+' .addshipperattachmentmodal-moreattachmentsbtn:not(".disabled")').on('click',tabshipper+' .addshipperattachmentmodal-moreattachmentsbtn:not(".disabled")',function(){

	var table = '#addshipperattachmentmodal-tbl';
	$(tabshipper+' '+table).DataTable().row.add([
						"<input type='checkbox' class='itemcheckbox'>",
						"<input type='file' class='fileattachment' name='file[]'>",
						" <input type='text' class='form-control fileattachmentdescription' name='filedescription[]' style='width:100%'>"

	]).draw();

	//$(contentADJ+' #stockadjustment-attachmentsmodaltbl tbody').append("<tr><td><input type='checkbox' class='itemcheckbox'></td><td><input type='file' class='fileattachment' name='file[]' id='abc'></td><td class='text-center'><input type='text' class='form-control fileattachmentdescription' name='filedescription[]'></td></tr>");

});


$(document).off('click',tabshipper+' .addshipperattachmentmodal-removeattachmentbtn:not(".disabled")').on('click',tabshipper+' .addshipperattachmentmodal-removeattachmentbtn:not(".disabled")',function(){

	var table = '#addshipperattachmentmodal-tbl';
	$(tabshipper+' '+table+' tbody tr .itemcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabshipper+' '+table).DataTable().row(tr).remove().draw();
	});

	//$(contentADJ+' #stockadjustment-attachmentsmodaltbl tbody .itemcheckbox:checked').closest('tr').remove();

	
});



$(document).off('click',tabshipper+' .addshipperattachmentmodal-savebtn:not(".disabled")').on('click',tabshipper+' .addshipperattachmentmodal-savebtn:not(".disabled")',function(){

	var button = $(this),
	    modal = "#"+$(this).closest('.modal').attr('id'),
	    attachmentcount = 0,
	    shipperid = $(tabshipper+' #viewshipperattachmentmodal-shipperid').val();
	button.addClass('disabled');

	var table = '#addshipperattachmentmodal-tbl';

	$(tabshipper+' '+table+' tbody tr').each(function(){
		if($(this).find(".fileattachment").val().trim()!=''){
			attachmentcount++;
		}
	});

	if(attachmentcount>0){
		    
			$(table+' tbody tr').each(function(){
				if($(this).find(".fileattachment").val().trim()==''){
					var tr = $(this).closest('tr');
			   		$(table).DataTable().row(tr).remove().draw();
			   	}
		    });
			$('#loading-img').removeClass('hidden');

			$.post(server+'shipper.php',{submitShipperAttachments:'f$bpom@soalns3o#2$I!Hk3so3!njsk',shipperid:shipperid},function(data){

				if(data.trim()=='success'){
					
				 	
						$(tabshipper+' #addshipperattachmentmodal-form').submit();
				 	
				 		$(tabshipper+' #uploadtgt').load(function(){
					 		$(tabshipper+' '+modal).modal('hide');
					 		button.removeClass('disabled');
					 		
					 		$(document).off('hidden.bs.modal',tabshipper+' '+modal).on('hidden.bs.modal',tabshipper+' '+modal,function(){
					 				$(tabshipper+' '+table).DataTable().clear().draw();
					 			    $(document).off('hidden.bs.modal',tabshipper+' '+modal);
					 			
					 				$(tabshipper+" #viewshipperattachmentmodal-tbl").flexOptions({
											url:'loadables/ajax/maintenance.shipper-attachments.php?shipper='+shipperid,
											sortname: "created_date",
											sortorder: "desc"
									}).flexReload(); 

									$('#loading-img').addClass('hidden');
					 		});
				 		});

				 		
				 	
				

				}
				else if(data.trim()=='invalidaccess'){
					say("Unable to add attachments. No permission to add attachments for this transaction.");
					button.removeClass('disabled');
					$('#loading-img').addClass('hidden');
				}
				else{
					alert(data);
					button.removeClass('disabled');
					$('#loading-img').addClass('hidden');
				}

			});
	}
	else{
		say("No attachment(s) added");
		button.removeClass('disabled');
	}


	
});


$(document).off('show.bs.modal',tabshipper+' #addshippermodal').on('show.bs.modal',tabshipper+' #addshippermodal',function(){
    $("#addshippermodal .countriesdropdownselect").empty().append('<option selected value="Philippines">Philippines</option>').trigger('change');
    
});




////// NEW SHIPPER RATE MODULE AS OF 03/09/2018
$(document).off('click',tabshipper+' .viewshipperratemodal-insertratebtn:not(".disabled")').on('click',tabshipper+' .viewshipperratemodal-insertratebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
	    type = $(modal+' .viewshipperratemodal-type').val(),
	    pouchsize = '',
	    expresstransactiontype = '',
		origin = $(modal+' .viewshipperratemodal-origin').val(),
		destination = $(modal+' .viewshipperratemodal-destination').val(),
		modeoftransport = $(modal+' .viewshipperratemodal-modeoftransport').val(),
		services = $(modal+' .viewshipperratemodal-services').val(),
		parceltype = 'NULL';//$(modal+' .viewshipperratemodal-parceltype').val(),
		freightcomputation = '',
		fixedrateflag = $(modal+' .viewshipperratemodal-fixedrateflag').prop('checked'),
		rushflag = $(modal+' .viewshipperratemodal-rushflag').prop('checked'),
		pulloutflag = $(modal+' .viewshipperratemodal-pulloutflag').prop('checked'),
		advaloremflag = $(modal+' .viewshipperratemodal-advaloremflag').prop('checked'),
		valuation = $(modal+' .viewshipperratemodal-valuation').val(),
		freightrate = $(modal+' .viewshipperratemodal-freightrate').val(),
		insurancerate = $(modal+' .viewshipperratemodal-insurancerate').val(),
		fuelrate = $(modal+' .viewshipperratemodal-fuelrate').val(),
		bunkerrate = $(modal+' .viewshipperratemodal-bunkerrate').val(),
		minimumrate = $(modal+' .viewshipperratemodal-minimumrate').val(),
		odarate = $(modal+' .viewshipperratemodal-odarate').val(),
		fixedrateamount = $(modal+' .viewshipperratemodal-fixedrateamount').val(),
		pulloutfee = $(modal+' .viewshipperratemodal-pulloutfee').val(),
		shipperid = $(modal+' #viewshipperratemodal-shipperid').val(),
		freightchargecomputation = $(modal+' .viewshipperratemodal-freightchargecomputation').val(),
		cbmcomputation = 'NULL',
		returndocumentfee = $(modal+' .viewshipperratemodal-returndocumentfee').val(),
		waybillfee = $(modal+' .viewshipperratemodal-waybillfee').val(),
		securityfee = $(modal+' .viewshipperratemodal-securityfee').val(),
		docstampfee = $(modal+' .viewshipperratemodal-docstampfee').val(),
		collectionpercentage = $(modal+' .viewshipperratemodal-collectionpercentage').val(),
		insuranceratecomputation = $(modal+' .viewshipperratemodal-insuranceratecomputation').val(),
		excessamount = $(modal+' .viewshipperratemodal-excessamount').val(),
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(type=='PARCEL'){
		freightcomputation = $(modal+' .viewshipperratemodal-freightcomputation').val();



		if(freightcomputation=='CBM'&&fixedrateflag!=true){
			cbmcomputation = $(modal+' .viewshipperratemodal-cbmcomputation').val();
		}
		else{
			cbmcomputation = 'NULL';
		}
	}
	else if(type=='DOCUMENT'){
		pouchsize = $(modal+' .viewshipperratemodal-pouchsize').val();
		expresstransactiontype = $(modal+' .viewshipperratemodal-expresstransactiontype').val();
		insurancerate = 0;
		insuranceratecomputation = 'NULL';
		parceltype = 'NULL';
		excessamount = 0;
		cbmcomputation = 'NULL';
	}

	if(freightcomputation=='Collection Fee'){
		freightchargecomputation = '';
	}
	else{
		collectionpercentage = 0;
	}

	if(fixedrateflag==true){
		fixedrateamount = $(modal+' .viewshipperratemodal-fixedrateamount').val();
		valuation = 0;
		freightrate = 0;
		insurancerate = 0;
		fuelrate = 0;
		bunkerrate = 0;
		minimumrate = 0;
		pulloutfee = 0;
		odarate = 0;

		returndocumentfee = 0;
		waybillfee = 0;
		securityfee = 0;
		docstampfee = 0;
		freightcomputation='';
		freightchargecomputation = '';
		collectionpercentage = 0;

		insuranceratecomputation = 'NULL';
		excessamount = 0;
	}


	if((type=='PARCEL'&&(freightcomputation=='Ad Valorem'||freightcomputation=='No. of Package'||freightcomputation=='Collection Fee'))){
		freightchargecomputation = 'NULL';

	}

	
	/*else if(pulloutflag==true){
		pulloutfee = $(modal+' .viewshipperratemodal-pulloutfee').val();
		valuation = 0;
		freightrate = 0;
		insurancerate = 0;
		fuelrate = 0;
		bunkerrate = 0;
		minimumrate = 0;
		fixedrateamount = 0;
		odarate = 0;
	}*/
	/*if(type=='PARCEL'&&(parceltype==''||parceltype==null||parceltype=='NULL'||parceltype=='null')){
		$(modal+' .viewshipperratemodal-parceltype').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select parcel type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else*/ if(origin==''||origin==null||origin=='NULL'||origin=='null'){
		$(modal+' .viewshipperratemodal-origin').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(destination==''||destination==null||destination=='NULL'||destination=='null'){
		$(modal+' .viewshipperratemodal-destination').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&(modeoftransport==''||modeoftransport==null||modeoftransport=='NULL'||modeoftransport=='null')){
		$(modal+' .viewshipperratemodal-modeoftransport').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&(services==''||services==null||services=='NULL'||services=='null')){
		$(modal+' .viewshipperratemodal-services').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select services.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(freightcomputation==''||freightcomputation==null||freightcomputation=='NULL'||freightcomputation=='null')){
		$(modal+' .viewshipperratemodal-freightcomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select freight computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation=='CBM'&&(cbmcomputation==''||cbmcomputation==null||cbmcomputation=='NULL'||cbmcomputation=='null'||cbmcomputation==undefined)){
		$(modal+' .viewshipperratemodal-cbmcomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select cbm computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation=='Collection Fee'&&(collectionpercentage==''||collectionpercentage<=0)){
		$(modal+' .viewshipperratemodal-collectionpercentage').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide collection fee percentage.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation!='Collection Fee'&&freightcomputation!='Ad Valorem'&&freightcomputation!='No. of Package'&&(freightchargecomputation==''||freightchargecomputation==null||freightchargecomputation=='NULL'||freightchargecomputation=='null')){
		$(modal+' .viewshipperratemodal-freightchargecomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select freight charge computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(insuranceratecomputation==''||insuranceratecomputation==null||insuranceratecomputation=='NULL'||insuranceratecomputation=='null')){
		$(modal+' .viewshipperratemodal-insuranceratecomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select insurance rate computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&insuranceratecomputation==2&&(excessamount==''||excessamount<0||excessamount==undefined)){
		$(modal+' .viewshipperratemodal-excessamount').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess amount.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='DOCUMENT'&&(pouchsize==''||pouchsize==null||pouchsize=='NULL'||pouchsize=='null')){
		$(modal+' .viewshipperratemodal-pouchsize').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='DOCUMENT'&&(expresstransactiontype==''||expresstransactiontype==null||expresstransactiontype=='NULL'||expresstransactiontype=='null'||expresstransactiontype==undefined)){
		$(modal+' .viewshipperratemodal-expresstransactiontype').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select express transaction type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(valuation==''||parseFloat(valuation)<0)){
		$(modal+' .viewshipperratemodal-valuation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valuation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(freightrate==''||parseFloat(freightrate)<0)){
		$(modal+' .viewshipperratemodal-freightrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(insurancerate==''||parseFloat(insurancerate)<0)){
		$(modal+' .viewshipperratemodal-insurancerate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide insurance rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(fuelrate==''||parseFloat(fuelrate)<0)){
		$(modal+' .viewshipperratemodal-fuelrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fuel rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(bunkerrate==''||parseFloat(bunkerrate)<0)){
		$(modal+' .viewshipperratemodal-bunkerrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide bunker rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(minimumrate==''||parseFloat(minimumrate)<0)){
		$(modal+' .viewshipperratemodal-minimumrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide minimum rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(returndocumentfee==''||parseFloat(returndocumentfee)<0)){
		$(modal+' .viewshipperratemodal-returndocumentfee').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide return document fee.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(waybillfee==''||parseFloat(waybillfee)<0)){
		$(modal+' .viewshipperratemodal-waybillfee').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide waybill fee.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(securityfee==''||parseFloat(securityfee)<0)){
		$(modal+' .viewshipperratemodal-securityfee').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide security fee.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(docstampfee==''||parseFloat(docstampfee)<0)){
		$(modal+' .viewshipperratemodal-docstampfee').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide doc stamp fee.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==true&&(fixedrateamount==''||parseFloat(fixedrateamount)<=0)){
		$(modal+' .viewshipperratemodal-fixedrateamount').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fixed rate amount.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper.php',{AddEditShipperRate:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,origin:origin,destination:destination,modeoftransport:modeoftransport,freightcomputation:freightcomputation,fixedrateflag:fixedrateflag,valuation:valuation,freightrate:freightrate,insurancerate:insurancerate,fuelrate:fuelrate,bunkerrate:bunkerrate,minimumrate:minimumrate,rushflag:rushflag,pulloutflag:pulloutflag,shipperid:shipperid,type:type,pouchsize:pouchsize,fixedrateamount:fixedrateamount,pulloutfee:pulloutfee,odarate:odarate,services:services,freightchargecomputation:freightchargecomputation,returndocumentfee:returndocumentfee,waybillfee:waybillfee,securityfee:securityfee,docstampfee:docstampfee,collectionpercentage:collectionpercentage,expresstransactiontype:expresstransactiontype,advaloremflag:advaloremflag,insuranceratecomputation:insuranceratecomputation,excessamount:excessamount,parceltype:parceltype,cbmcomputation:cbmcomputation},function(data){
				if(data.trim()=='success'){
					//$(modal).modal('hide');
					//$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipper+" #viewshipperratemodal-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rates.php?shipper='+shipperid,
							sortname: "created_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
						$(modal+' .inputslctfld:not(".noresetfld")').empty().trigger('change');
						$(modal+' .errordiv').empty();
						//$(modal).off('hidden.bs.modal');

					//});
				}
				else if(data.trim()=='rateexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Rate exists. Please select another origin, destination, mode of transport, rush flag, or pull out flag.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}
});

$(document).off('click','.viewshipperratebtn').on('click','.viewshipperratebtn',function(){
	var modal = '#viewshipperratemodal';
	var shipperid = $(this).attr('rowid');
	var accountname = $(this).attr('accountname');
	$(modal+' #viewshipperratemodal-shipperid').val(shipperid);
	$(modal+' #viewshipperratemodal-accountname').text(accountname);

	$(tabshipper+" #viewshipperratemodal-tbl").flexOptions({
		url:'loadables/ajax/maintenance.shipper-rates.php?shipper='+shipperid,
		sortname: "origin",
		sortorder: "asc"
	}).flexReload();

});

function viewShipperRateClearFields(modal){
		/*$(tabshipper+modal+' .originfld').empty().trigger('change');
	    $(tabshipper+modal+' .destinationfld').empty().trigger('change');*/
	    //$(tabshipper+modal+' .viewshipperratemodal-modeoftransport').empty().trigger('change');
	    $(tabshipper+modal+' .viewshipperratemodal-freightcomputation').empty().trigger('change');
	    //$(tabshipper+modal+' .viewshipperratemodal-pouchsize').empty().trigger('change');
	    $(tabshipper+modal+' .viewshipperratemodal-valuation').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-freightrate').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-insurancerate').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-fuelrate').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-bunkerrate').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-minimumrate').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-fixedrateamount').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-pulloutfee').val('');
	    $(tabshipper+modal+' .viewshipperratemodal-odarate').val('');
}

$(document).off('click',tabshipper+' .viewshipperratemodal-clearratefieldsbtn').on('click',tabshipper+' .viewshipperratemodal-clearratefieldsbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		viewShipperRateClearFields(modal);
});
////// NEW SHIPPER RATE MODULE AS OF 03/09/2018



$(document).off('click',tabshipper+' .editshipperratebtn:not(".disabled")').on('click',tabshipper+' .editshipperratebtn:not(".disabled")',function(){

	var modal = '#editshipperratemodal';
	var button = $(this);
	button.addClass('disabled');

	var rowid = $(this).attr('rowid');
	var shipperid = $(this).attr('shipperid');
	//alert(rowid);

	

	$.post(server+'shipper.php',{getShipperRateDetails:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		//alert(data);

		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			button.removeClass('disabled');


			$(modal+' .editshipperratemodal-shipperrateid').val(rowid);
			$(modal+' .editshipperratemodal-shipperid').val(shipperid);
			$(modal+' .valuation').val(rsp['valuation']);
			$(modal+' .freightrate').val(rsp['freightrate']);
			$(modal+' .insurancerate').val(rsp['insurancerate']);
			$(modal+' .fuelrate').val(rsp['fuelrate']);
			$(modal+' .bunkerrate').val(rsp['bunkerrate']);
			$(modal+' .minimumrate').val(rsp['minimumrate']);
			$(modal+' .type').val(rsp['type']).trigger('change');
			$(modal+' .fixedrateamount').val(rsp['fixedrateamount']);
			$(modal+' .pulloutfee').val(rsp['pulloutfee']);
			$(modal+' .odarate').val(rsp['odarate']);

			$(modal+' .returndocumentfee').val(rsp['returndocumentfee']);
			$(modal+' .waybillfee').val(rsp['waybillfee']);
			$(modal+' .securityfee').val(rsp['securityfee']);
			$(modal+' .docstampfee').val(rsp['docstampfee']);
			$(modal+' .collectionpercentage').val(rsp['collectionpercentage']);
			$(modal+' .excessamount').val(rsp['excessamount']);
			

			if(rsp['fixedrateflag']==1){

				$(modal+' .fixedrateflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .fixedrateflag').bootstrapToggle('off');
			}

			if(rsp['advaloremflag']==1){

				$(modal+' .advaloremflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .advaloremflag').bootstrapToggle('off');
			}

			if(rsp['rushflag']==1){

				$(modal+' .rushflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .rushflag').bootstrapToggle('off');
			}

			if(rsp['pulloutflag']==1){

				$(modal+' .pulloutflag').bootstrapToggle('on')
			}
			else{
				$(modal+' .pulloutflag').bootstrapToggle('off');
			}

			/*if(rsp["parceltype"]!=null){
		        $(modal+" .parceltype").empty().append('<option selected value="'+rsp["parceltypeid"]+'">'+rsp["parceltype"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .parceltype").empty().trigger('change');
		    }*/
			
			if(rsp["origin"]!=null){
		        $(modal+" .origin").empty().append('<option selected value="'+rsp["originid"]+'">'+rsp["origin"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .origin").empty().trigger('change');
		    }

		    if(rsp["destination"]!=null){
		        $(modal+" .destination").empty().append('<option selected value="'+rsp["destinationid"]+'">'+rsp["destination"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .destination").empty().trigger('change');
		    }

		    if(rsp["modeoftransport"]!=null){
		        $(modal+" .modeoftransport").empty().append('<option selected value="'+rsp["modeoftransportid"]+'">'+rsp["modeoftransport"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .modeoftransport").empty().trigger('change');
		    }

		    if(rsp["services"]!=null){
		        $(modal+" .services").empty().append('<option selected value="'+rsp["servicesid"]+'">'+rsp["services"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .services").empty().trigger('change');
		    }

		    if(rsp["freightcomputation"]!=null){
		        $(modal+" .freightcomputation").empty().append('<option selected value="'+rsp["freightcomputation"]+'">'+rsp["freightcomputation"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .freightcomputation").empty().trigger('change');
		    }

		    if(rsp["pouchsize"]!=null){
		        $(modal+" .pouchsize").empty().append('<option selected value="'+rsp["pouchsizeid"]+'">'+rsp["pouchsize"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .pouchsize").empty().trigger('change');
		    }

		    if(rsp["freightchargecomputation"]!=null){
		        $(modal+" .freightchargecomputation").empty().append('<option selected value="'+rsp["freightchargecomputationid"]+'">'+rsp["freightchargecomputation"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .freightchargecomputation").empty().trigger('change');
		    }

		    if(rsp["cbmcomputation"]!=null){
		        $(modal+" .cbmcomputation").empty().append('<option selected value="'+rsp["cbmcomputationid"]+'">'+rsp["cbmcomputation"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .cbmcomputation").empty().trigger('change');
		    }

		    if(rsp["insuranceratecomputation"]!=null){
		        $(modal+" .insuranceratecomputation").empty().append('<option selected value="'+rsp["insuranceratecomputationid"]+'">'+rsp["insuranceratecomputation"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .insuranceratecomputation").empty().trigger('change');
		    }

		    if(rsp["expresstransactiontype"]!=null){
		        $(modal+" .expresstransactiontype").empty().append('<option selected value="'+rsp["expresstransactiontype"]+'">'+rsp["expresstransactiontype"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .expresstransactiontype").empty().trigger('change');
		    }


			$(modal).modal('show');
		}
		else if(rsp['response']=='invalidshipperrateid'){
			say("Invalid Shipper Rate ID. Please refresh page.");
			button.removeClass('disabled');


		}
		else{
			alert(data);
			button.removeClass('disabled');
		}
		

	});



	

});

$(document).off('click',tabshipper+' .recomputewaybillbtn:not(".disabled")').on('click',tabshipper+' .recomputewaybillbtn:not(".disabled")',function(){

		var modal = '#viewunbilledwaybillforrecomputation';

		$(modal).modal('show');
});


$(document).off('click',tabshipper+' .editshipperratemodal-savebtn:not(".disabled")').on('click',tabshipper+' .editshipperratemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	    type = $(modal+' .type').val(),
		origin = $(modal+' .origin').val(),
		destination = $(modal+' .destination').val(),
		modeoftransport = $(modal+' .modeoftransport').val(),
		services = $(modal+' .services').val(),
		freightcomputation = '',
		pouchsize = '',
		expresstransactiontype = '',
		fixedrateflag = $(modal+' .fixedrateflag').prop('checked'),
		rushflag = $(modal+' .rushflag').prop('checked'),
		pulloutflag = $(modal+' .pulloutflag').prop('checked'),
		advaloremflag = $(modal+' .advaloremflag').prop('checked'),
		parceltype = 'NULL';//$(modal+' .parceltype').val(),
		valuation = $(modal+' .valuation').val(),
		freightrate = $(modal+' .freightrate').val(),
		insurancerate = $(modal+' .insurancerate').val(),
		fuelrate = $(modal+' .fuelrate').val(),
		bunkerrate = $(modal+' .bunkerrate').val(),
		minimumrate = $(modal+' .minimumrate').val(),
		fixedrateamount = $(modal+' .fixedrateamount').val(),
		pulloutfee = $(modal+' .pulloutfee').val(),
		odarate = $(modal+' .odarate').val(),
		shipperrateid = $(modal+' .editshipperratemodal-shipperrateid').val(),
		shipperid = $(modal+' .editshipperratemodal-shipperid').val(),
		freightchargecomputation = $(modal+' .freightchargecomputation').val(),
		returndocumentfee = $(modal+' .returndocumentfee').val(),
		waybillfee = $(modal+' .waybillfee').val(),
		securityfee = $(modal+' .securityfee').val(),
		docstampfee = $(modal+' .docstampfee').val(),
		collectionpercentage = $(modal+' .collectionpercentage').val(),
		id=$(modal+' .editshipperratemodal-shipperrateid').val(),
		insuranceratecomputation = $(modal+' .insuranceratecomputation').val(),
		excessamount = $(modal+' .excessamount').val(),
		cbmcomputation = 'NULL',
		newsort = 'updated_date',
		source='edit',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');



	if(type=='PARCEL'){
		freightcomputation = $(modal+' .freightcomputation').val();

		if(freightcomputation=='CBM'&&fixedrateflag!=true){
			cbmcomputation = $(modal+' .cbmcomputation').val();
		}
		else{
			cbmcomputation = 'NULL';
		}
	}
	else if(type=='DOCUMENT'){
		pouchsize = $(modal+' .pouchsize').val();
		expresstransactiontype = $(modal+' .expresstransactiontype').val();
		insurancerate = 0;

		cbmcomputation = 'NULL';
		insuranceratecomputation = 'NULL';
		parceltype = 'NULL';
		excessamount = 0;
	}

	if(freightcomputation=='Collection Fee'){
		freightchargecomputation = 'NULL';
	}
	else{
		collectionpercentage = 0;
	}

	if(fixedrateflag==true){
		fixedrateamount = $(modal+' .fixedrateamount').val();
		valuation = 0;
		freightrate = 0;
		insurancerate = 0;
		fuelrate = 0;
		bunkerrate = 0;
		minimumrate = 0;
		pulloutfee = 0;
		odarate = 0;


		returndocumentfee = 0;
		waybillfee = 0;
		securityfee = 0;
		docstampfee = 0;
		freightcomputation='';
		freightchargecomputation = 'NULL';
		collectionpercentage = 0;

		insuranceratecomputation = 'NULL';

		excessamount = 0;
	}


	if((type=='PARCEL'&&(freightcomputation=='Ad Valorem'||freightcomputation=='No. of Package'||freightcomputation=='Collection Fee'))){
		freightchargecomputation = 'NULL';
		
	}

	/*if(type=='PARCEL'&&(parceltype==''||parceltype==null||parceltype=='NULL'||parceltype=='null')){
		$(modal+' .parceltype').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select parcel type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else*/ if(origin==''||origin==null||origin=='NULL'||origin=='null'){
		$(modal+' .origin').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select origin.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(destination==''||destination==null||destination=='NULL'||destination=='null'){
		$(modal+' .destination').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select destination.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&(modeoftransport==''||modeoftransport==null||modeoftransport=='NULL'||modeoftransport=='null')){
		$(modal+' .modeoftransport').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select mode of transport.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&(services==''||services==null||services=='NULL'||services=='null')){
		$(modal+' .services').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select services.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(freightcomputation==''||freightcomputation==null||freightcomputation=='NULL'||freightcomputation=='null')){
		$(modal+' .freightcomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select freight computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation=='CBM'&&(cbmcomputation==''||cbmcomputation==null||cbmcomputation=='NULL'||cbmcomputation=='null'||cbmcomputation==undefined)){
		$(modal+' .cbmcomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select cbm computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation=='Collection Fee'&&(collectionpercentage==''||collectionpercentage<=0)){
		$(modal+' .collectionpercentage').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide collection fee percentage.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&freightcomputation!='Collection Fee'&&freightcomputation!='Ad Valorem'&&freightcomputation!='No. of Package'&&(freightchargecomputation==''||freightchargecomputation==null||freightchargecomputation=='NULL'||freightchargecomputation=='null')){
		$(modal+' .freightchargecomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select freight charge computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(insuranceratecomputation==''||insuranceratecomputation==null||insuranceratecomputation=='NULL'||insuranceratecomputation=='null')){
		$(modal+' .insuranceratecomputation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select insurance rate computation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&insuranceratecomputation==2&&(excessamount==''||excessamount<0||excessamount==undefined)){
		$(modal+' .excessamount').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess amount.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='DOCUMENT'&&(pouchsize==''||pouchsize==null||pouchsize=='NULL'||pouchsize=='null')){
		$(modal+' .pouchsize').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select pouch size.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='DOCUMENT'&&(expresstransactiontype==''||expresstransactiontype==null||expresstransactiontype=='NULL'||expresstransactiontype=='null'||expresstransactiontype==undefined)){
		$(modal+' .expresstransactiontype').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select express transaction type.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(valuation==''||parseFloat(valuation)<0)){
		$(modal+' .valuation').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide valuation.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(fixedrateflag==false&&(freightrate==''||parseFloat(freightrate)<0)){
		$(modal+' .freightrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(insurancerate==''||parseFloat(insurancerate)<0)){
		alert(insurancerate);
		$(modal+' .insurancerate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide insurance rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(fuelrate==''||parseFloat(fuelrate)<0)){
		$(modal+' .fuelrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fuel rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(bunkerrate==''||parseFloat(bunkerrate)<0)){
		$(modal+' .bunkerrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide bunker rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==false&&(minimumrate==''||parseFloat(minimumrate)<0)){
		$(modal+' .minimumrate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide minimum rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(type=='PARCEL'&&fixedrateflag==true&&(fixedrateamount==''||parseFloat(fixedrateamount)<=0)){
		$(modal+' .fixedrateamount').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fixed rate amount.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{

		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper.php',{AddEditShipperRate:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,origin:origin,destination:destination,modeoftransport:modeoftransport,freightcomputation:freightcomputation,fixedrateflag:fixedrateflag,valuation:valuation,freightrate:freightrate,insurancerate:insurancerate,fuelrate:fuelrate,bunkerrate:bunkerrate,minimumrate:minimumrate,rushflag:rushflag,pulloutflag:pulloutflag,shipperrateid:shipperrateid,shipperid:shipperid,type:type,pouchsize:pouchsize,fixedrateamount:fixedrateamount,pulloutfee:pulloutfee,odarate:odarate,services:services,freightchargecomputation:freightchargecomputation,returndocumentfee:returndocumentfee,waybillfee:waybillfee,securityfee:securityfee,docstampfee:docstampfee,collectionpercentage:collectionpercentage,expresstransactiontype:expresstransactiontype,advaloremflag:advaloremflag,insuranceratecomputation:insuranceratecomputation,excessamount:excessamount,parceltype:parceltype,cbmcomputation:cbmcomputation},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipper+" #viewshipperratemodal-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rates.php?shipper='+shipperid,
							sortname: "updated_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
						$(modal+' .inputslctfld:not(".noresetfld")').empty().trigger('change');
						$(modal+' .errordiv').empty();
						$(modal).off('hidden.bs.modal');

					});
				}
				else if(data.trim()=='rateexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Rate exists. Please select another origin, destination, mode of transport, rush flag, or pull out flag.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});
	}
});









function showHideShipperRateFields(modal,type,fixedrateflag,freightcomputation,insuranceratecomputation){
	if(type=='PARCEL'){
			$(modal+' .pouchsizewrapper').addClass('hidden');
			$(modal+' .expresstransactiontypewrapper').addClass('hidden');
			$(modal+' .advaloremflagwrapper').addClass('hidden');

			$(modal+' .freightcomputationwrapper').removeClass('hidden');

			$(modal+' .parceltypewrapper').removeClass('hidden');
			$(modal+' .modeoftransportwrapper').removeClass('hidden');
			$(modal+' .serviceswrapper').removeClass('hidden');
			$(modal+' .pulloutflagwrapper').removeClass('hidden');
			$(modal+' .fixedrateflagwrapper').removeClass('hidden');

			$(modal+' .fuelratewrapper').removeClass('hidden');
			$(modal+' .bunkerratewrapper').removeClass('hidden');
			$(modal+' .minimumratewrapper').removeClass('hidden');



			if(fixedrateflag==true){
				$(modal+' .fixedrateamountwrapper').removeClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .freightcomputationwrapper').addClass('hidden');
				$(modal+' .cbmcomputationwrapper').addClass('hidden');
			}	
			else{
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').removeClass('hidden');
				$(modal+' .freightcomputationwrapper').removeClass('hidden');	
			}

			if(freightcomputation=='Collection Fee'&&fixedrateflag==false){
				$(modal+' .collectionpercentagewrapper').removeClass('hidden');
				$(modal+' .freightchargecomputationwrapper').addClass('hidden');
			}
			else{
				$(modal+' .collectionpercentagewrapper').addClass('hidden');
			}



			if(freightcomputation=='Ad Valorem'||freightcomputation=='No. of Package'){
				$(modal+' .freightchargecomputationwrapper').addClass('hidden');
			}

		    if(freightcomputation=='CBM'&&fixedrateflag!=true){
				$(modal+' .cbmcomputationwrapper').removeClass('hidden');
			}
			else{
				$(modal+' .cbmcomputationwrapper').addClass('hidden');
			}


			if(insuranceratecomputation==2&&fixedrateflag==false){
				$(modal+' .excessamountwrapper').removeClass('hidden');
			}
			else{
				$(modal+' .excessamountwrapper').addClass('hidden');
			}
			
	}
	else{
			$(modal+' .pouchsizewrapper').removeClass('hidden');
			$(modal+' .valuationwrapper').removeClass('hidden');
			$(modal+' .freightratewrapper').removeClass('hidden');
			$(modal+' .expresstransactiontypewrapper').removeClass('hidden');
			$(modal+' .advaloremflagwrapper').removeClass('hidden');

			$(modal+' .odaratewrapper').removeClass('hidden');
			$(modal+' .returndocumentfeewrapper').removeClass('hidden');
			$(modal+' .waybillfeewrapper').removeClass('hidden');
			$(modal+' .securityfeewrapper').removeClass('hidden');
			$(modal+' .docstampfeewrapper').removeClass('hidden');
			
			$(modal+' .parceltypewrapper').addClass('hidden');
			$(modal+' .collectionpercentagewrapper').addClass('hidden');
			$(modal+' .freightcomputationwrapper').addClass('hidden');
			$(modal+' .freightchargecomputationwrapper').addClass('hidden');
			$(modal+' .insuranceratecomputationwrapper').addClass('hidden');
			$(modal+' .cbmcomputationwrapper').addClass('hidden');
			$(modal+' .excessamountwrapper').addClass('hidden');
			$(modal+' .modeoftransportwrapper').addClass('hidden');
			$(modal+' .serviceswrapper').addClass('hidden');
			$(modal+' .pulloutflagwrapper').addClass('hidden');
			$(modal+' .fixedrateflagwrapper').addClass('hidden');
			$(modal+' .insuranceratewrapper').addClass('hidden');

			$(modal+' .fuelratewrapper').addClass('hidden');
			$(modal+' .bunkerratewrapper').addClass('hidden');
			$(modal+' .minimumratewrapper').addClass('hidden');
			$(modal+' .fixedrateamountwrapper').addClass('hidden');
			$(modal+' .pulloutfeewrapper').addClass('hidden');
	}
}

$(document).off('change',tabshipper+' .shipperrate-insuranceratecomputation').on('change',tabshipper+' .shipperrate-insuranceratecomputation',function(){

	var modal = '#'+$(this).closest('.modal').attr('id');
	var type = $(modal+' .shipperrate-waybilltype').val();
	var fixedrateflag = $(modal+' .shipperrate-fixedrateflag').prop('checked');
	var freightcomp = $(modal+' .shipperrate-freightcomputation').val();
	var insuranceratecomp = $(modal+' .shipperrate-insuranceratecomputation').val();
	var cbmcomp = $(modal+' .shipperrate-cbmcomputation').val();

	showHideShipperRateFields(modal,type,fixedrateflag,freightcomp,insuranceratecomp);

});

$(document).off('change',tabshipper+' .shipperrate-freightcomputation').on('change',tabshipper+' .shipperrate-freightcomputation',function(){
	var modal = '#'+$(this).closest('.modal').attr('id');
	var type = $(modal+' .shipperrate-waybilltype').val();
	var fixedrateflag = $(modal+' .shipperrate-fixedrateflag').prop('checked');
	var freightcomp = $(modal+' .shipperrate-freightcomputation').val();
	var insuranceratecomp = $(modal+' .shipperrate-insuranceratecomputation').val();
	var cbmcomp = $(modal+' .shipperrate-cbmcomputation').val();

	showHideShipperRateFields(modal,type,fixedrateflag,freightcomp,insuranceratecomp);

});

$(document).off('change',tabshipper+' .shipperrate-waybilltype').on('change',tabshipper+' .shipperrate-waybilltype',function(){

	var type = $(this).val();
	var modal = '#'+$(this).closest('.modal').attr('id');
	var fixedrateflag = $(modal+' .shipperrate-fixedrateflag').prop('checked');
	var freightcomp = $(modal+' .shipperrate-freightcomputation').val();
	var insuranceratecomp = $(modal+' .shipperrate-insuranceratecomputation').val();
	var cbmcomp = $(modal+' .shipperrate-cbmcomputation').val();

	showHideShipperRateFields(modal,type,fixedrateflag,freightcomp,insuranceratecomp);

});

$(document).off('change',tabshipper+' .shipperrate-fixedrateflag').on('change',tabshipper+' .shipperrate-fixedrateflag',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		var fixedrateflag = $(this).prop('checked');
		var type = $(modal+' .shipperrate-waybilltype').val();
		var freightcomp = $(modal+' .shipperrate-freightcomputation').val();
		var insuranceratecomp = $(modal+' .shipperrate-insuranceratecomputation').val();
		var cbmcomp = $(modal+' .shipperrate-cbmcomputation').val();


		showHideShipperRateFields(modal,type,fixedrateflag,freightcomp,insuranceratecomp);
		/*var pulloutflag = $(modal+' .shipperrate-pulloutflag').prop('checked');
		//alert(flag);


		if(flag==true){
				$(modal+' .fixedrateamountwrapper').removeClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
				$(modal+' .pulloutfeewrapper').addClass('hidden');
		}	
		else if(pulloutflag==true){
			    $(modal+' .pulloutfeewrapper').removeClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').addClass('hidden');
		}			
		else{
				$(modal+' .pulloutfeewrapper').addClass('hidden');
				$(modal+' .fixedrateamountwrapper').addClass('hidden');
				$(modal+' .normalratewrapper').removeClass('hidden');	
		}*/






});










/*********************** HANDLING INSTRUCTION *********************/

$(document).off('click',tabshipper+' .viewshipperhandlinginstructionbtn').on('click',tabshipper+' .viewshipperhandlinginstructionbtn',function(){
	var button = $(this);
	//button.addClass('disabled');

	var modal = '#viewshipperratehandlinginstruction';
	var shipperrateid = button.attr('shipperrateid');

	$(modal+' #viewshipperratehandlinginstruction-shipperrateid').val(shipperrateid);


	
	$("#viewshipperratehandlinginstruction-tbl").flexOptions({
		url: 'loadables/ajax/maintenance.shipper-rate-handling-instruction.php?shipperrateid='+shipperrateid,
		sortname: "handlinginstruction",
		sortorder: "asc"
	}).flexReload();

	$(modal).modal('show');




	
});






$(document).off('change',tabshipper+' .viewshipperratehandlinginstruction-type').on('change',tabshipper+' .viewshipperratehandlinginstruction-type',function(){

	var val = $(this).val();
	var modal = '#'+$(this).closest('.modal').attr('id');

	if(val=='1'){
		
		$(modal+' .fixedchargewrapper').addClass('hidden');
		$(modal+' .percentagewrapper').removeClass('hidden');
		$(modal+' .percentagewrapper').focus();
	}
	else{
		$(modal+' .percentagewrapper').addClass('hidden');
		$(modal+' .fixedchargewrapper').removeClass('hidden');
		$(modal+' .fixedchargewrapper').focus();
	}



});


$(document).off('click',tabshipper+' .viewshipperratehandlinginstruction-insertratebtn:not(".disabled")').on('click',tabshipper+' .viewshipperratehandlinginstruction-insertratebtn:not(".disabled")',function(){


	var button = $(this);
	    button.addClass('disabled');

	var modal = '#'+$(this).closest('.modal').attr('id');

	var handlinginstruction = $(modal+' .viewshipperratehandlinginstruction-handlinginstruction').val();
	var type = $(modal+' .viewshipperratehandlinginstruction-type').val();
	var shipperrateid = $(modal+' #viewshipperratehandlinginstruction-shipperrateid').val();
	var percentage = '';
	var fixedcharge = '';

	if(type=='1'){
		percentage = $(modal+' .viewshipperratehandlinginstruction-percentage').val();
	}
	else{
		fixedcharge = $(modal+' .viewshipperratehandlinginstruction-fixedcharge').val();
	}


	if(handlinginstruction==''||handlinginstruction==null||handlinginstruction=='NULL'||handlinginstruction=='null'){
		$(modal+' .viewshipperratehandlinginstruction-handlinginstruction').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select handling instruction.</div></div>");
		button.removeClass('disabled');
	}
	else if(type=='1'&&(percentage==''||percentage<=0)){
		$(modal+' .viewshipperratehandlinginstruction-handlinginstruction').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight charge percentage.</div></div>");
		button.removeClass('disabled');
	}
	else if(type!='1'&&(fixedcharge==''||fixedcharge<=0)){
		$(modal+' .viewshipperratehandlinginstruction-fixedcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide fixed charge.</div></div>");
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper.php',{AddEditShipperRateHandlingInstruction:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipperrateid:shipperrateid,handlinginstruction:handlinginstruction,type:type,percentage:percentage,fixedcharge:fixedcharge},function(data){
				if(data.trim()=='success'){
					//$(modal).modal('hide');
					//$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipper+" #viewshipperratehandlinginstruction-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rate-handling-instruction.php?shipperrateid='+shipperrateid,
							sortname: "created_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
						$(modal+' .inputslctfld:not(".noresetfld")').empty().trigger('change');
						$(modal+' .errordiv').empty();
						//$(modal).off('hidden.bs.modal');

					//});
				}
				/*else if(data.trim()=='rateexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Rate exists. Please select another origin, destination, mode of transport, rush flag, or pull out flag.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}*/
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}



});




/************************ FREIGHT CHARGE *********************************/
$(document).off('click',tabshipper+' .viewshipperfreightchargebtn').on('click',tabshipper+' .viewshipperfreightchargebtn',function(){
	var button = $(this);
	//button.addClass('disabled');

	var modal = '#viewshipperratefreightcharge';
	var shipperrateid = button.attr('shipperrateid');

	$(modal+' #viewshipperratefreightcharge-shipperrateid').val(shipperrateid);


	
	$("#viewshipperratefreightcharge-tbl").flexOptions({
		url: 'loadables/ajax/maintenance.shipper-rate-freight-charge.php?shipperrateid='+shipperrateid,
		sortname: "created_date",
		sortorder: "asc"
	}).flexReload();

	$(modal).modal('show');




	
});


$(document).off('click',tabshipper+' .viewshipperratefreightcharge-insertratebtn:not(".disabled")').on('click',tabshipper+' .viewshipperratefreightcharge-insertratebtn:not(".disabled")',function(){


	var button = $(this);
	    button.addClass('disabled');

	var modal = '#'+$(this).closest('.modal').attr('id');

	var fromkg = $(modal+' .viewshipperratefreightcharge-fromkg').val();
	var tokg = $(modal+' .viewshipperratefreightcharge-tokg').val();
	var freightcharge = $(modal+' .viewshipperratefreightcharge-freightcharge').val();
	var excessweightcharge = $(modal+' .viewshipperratefreightcharge-excessweightcharge').val();

	var shipperrateid = $(modal+' #viewshipperratefreightcharge-shipperrateid').val();




	if(fromkg==undefined||fromkg==null||fromkg==''||fromkg<0){
		$(modal+' .viewshipperratefreightcharge-fromkg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide from (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(tokg==undefined||tokg==null||tokg==''||tokg<=0){
		$(modal+' .viewshipperratefreightcharge-tokg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide to (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(freightcharge==undefined||freightcharge==null||freightcharge==''||freightcharge<=0){
		$(modal+' .viewshipperratefreightcharge-freightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight charge.</div></div>");
		button.removeClass('disabled');
	}
	else if(excessweightcharge==undefined||excessweightcharge==null||excessweightcharge==''||excessweightcharge<0){
		$(modal+' .viewshipperratefreightcharge-excessweightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess weight charge.</div></div>");
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper.php',{AddEditShipperRateFreightCharge:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipperrateid:shipperrateid,fromkg:fromkg,tokg:tokg,freightcharge:freightcharge,excessweightcharge:excessweightcharge,source:'add'},function(data){
				if(data.trim()=='success'){
					//$(modal).modal('hide');
					//$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipper+" #viewshipperratefreightcharge-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rate-freight-charge.php?shipperrateid='+shipperrateid,
							sortname: "created_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input:not(".noresetfld"), '+modal+' textarea').val('');
						$(modal+' .inputslctfld:not(".noresetfld")').empty().trigger('change');
						$(modal+' .errordiv').empty();
						//$(modal).off('hidden.bs.modal');

					//});
				}
				/*else if(data.trim()=='rateexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Rate exists. Please select another origin, destination, mode of transport, rush flag, or pull out flag.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}*/
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}



});




$(document).off('click',tabshipper+' .editshipperratefreightchargebtn').on('click',tabshipper+' .editshipperratefreightchargebtn',function(){

	var modal = '#editshipperratefreightchargemodal';
	var freightchargeID = $(this).attr('rowid');
	var fromkg = $(this).attr('fromkg');
	var tokg = $(this).attr('tokg');
	var freightcharge = $(this).attr('freightcharge');
	var excessweightcharge = $(this).attr('excessweightcharge');

	$(modal+' #shipperratefreightchargeID').val(freightchargeID);
	$(modal+' .editshipperratefreightchargemodal-fromkg').val(fromkg);
	$(modal+' .editshipperratefreightchargemodal-tokg').val(tokg);
	$(modal+' .editshipperratefreightchargemodal-freightcharge').val(freightcharge);
	$(modal+' .editshipperratefreightchargemodal-excessweightcharge').val(excessweightcharge);
	$(tabshipper+' '+modal).modal('show');


});



$(document).off('click',tabshipper+' .editshipperratefreightchargemodal-savebtn:not("disabled")').on('click',tabshipper+' .editshipperratefreightchargemodal-savebtn:not("disabled")',function(){
	var button = $(this);
		button.addClass("disabled");

	var shipperrateid = $('#viewshipperratefreightcharge #viewshipperratefreightcharge-shipperrateid').val();
	
	var modal = '#'+$(this).closest(".modal").attr('id');
	var freightchargeID = $(modal+' #shipperratefreightchargeID').val();
	var fromkg = $(modal+' .editshipperratefreightchargemodal-fromkg').val();
	var tokg = $(modal+' .editshipperratefreightchargemodal-tokg').val();
	var freightcharge = $(modal+' .editshipperratefreightchargemodal-freightcharge').val();
	var excessweightcharge = $(modal+' .editshipperratefreightchargemodal-excessweightcharge').val();


	if(fromkg==undefined||fromkg==null||fromkg==''||fromkg<0){
		$(modal+' .editshipperratefreightchargemodal-fromkg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide from (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(tokg==undefined||tokg==null||tokg==''||tokg<=0){
		$(modal+' .editshipperratefreightchargemodal-tokg').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide to (kg).</div></div>");
		button.removeClass('disabled');
	}
	else if(freightcharge==undefined||freightcharge==null||freightcharge==''||freightcharge<=0){
		$(modal+' .editshipperratefreightchargemodal-freightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide freight charge.</div></div>");
		button.removeClass('disabled');
	}
	else if(excessweightcharge==undefined||excessweightcharge==null||excessweightcharge==''||excessweightcharge<0){
		$(modal+' .editshipperratefreightchargemodal-excessweightcharge').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide excess weight charge.</div></div>");
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'shipper.php',{AddEditShipperRateFreightCharge:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',shipperrateid:shipperrateid,freightchargeID:freightchargeID,fromkg:fromkg,tokg:tokg,freightcharge:freightcharge,excessweightcharge:excessweightcharge,source:'edit'},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$(tabshipper+" #viewshipperratefreightcharge-tbl").flexOptions({
							url:'loadables/ajax/maintenance.shipper-rate-freight-charge.php?shipperrateid='+shipperrateid,
							sortname: "updated_date",
							sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						
						button.removeClass('disabled');
						$(modal+' .errordiv').empty();
						$(modal).off('hidden.bs.modal');

					});
				}
				else{
					$('#loading-img').addClass('hidden');
					$(modal+' .errordiv').empty();
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}


});




/***************************** UPLOAD ********************************************************/


$(document).off('click',tabshipper+' #uploadshipperratesmodal-uploadbtn:not(".disabled")').on('click',tabshipper+' #uploadshipperratesmodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadshipperratesmodal';
	var modal2 = '#uploadshipperrateslogmodal';
	var form = '#uploadshipperratesmodal-form';
	var logframe = '#shipperratesuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabshipper+' '+modal+' .uploadshipperratesmodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabshipper+' '+modal).on('hidden.bs.modal',tabshipper+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabshipper+' '+modal);
			$(tabshipper+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				/*$('#districtcityziptable').flexOptions({
						url:'loadables/ajax/maintenance.district-city-zip.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();*/

				/*$(this).contents().find('#touploadsuccessbtn').off('click').on('click',function(){
					$(this).contents().find('#touploadsuccessbtn').off('click');

					*var to = $(this).attr('tonumber');
					$('#transfer-order-touploadlog-modal').modal('hide');
					$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal').on('hidden.bs.modal','#transfer-order-touploadlog-modal',function(){
						$(document).off('hidden.bs.modal','#transfer-order-touploadlog-modal');
						getToDetails(to);
					});

				});*/

			});

		});
	
	}

});











/************************************* UPLOAD END *************************************************/




$(document).off('click',tabshipper+' .viewshipperinvoicebtn:not(".disabled")').on('click',tabshipper+' .viewshipperinvoicebtn:not(".disabled")',function(){

	var shipperid = $(this).attr('shipperid');
	var accountname = $(this).attr('accountname');
	var modal = '#viewshipperinvoicemodal';
	var btn = $(this);
	btn.addClass('disabled');



	$(tabshipper+" #viewshipperinvoicemodal-tbl").flexOptions({
		url:'loadables/ajax/maintenance.shipper-invoice.php?shipperid='+shipperid,
		sortname: "billing_number",
		sortorder: "asc"
	}).flexReload(); 



	$(modal+' #viewshipperinvoicemodal-shippername').text(accountname);

	$(modal).modal('show');

	$(modal).on('shown.bs.modal',function(){
		$(modal).off('shown.bs.modal');
		btn.removeClass('disabled');
	});



});



$(document).off('click',tabshipper+' #shipper-creditinfolookupbtn:not(".disabled")').on('click',tabshipper+' #shipper-creditinfolookupbtn:not(".disabled")',function(){
	var button = $(this);
	button.addClass('disabled');

	var currentmodal = '#'+$(this).closest('.modal').attr('id');
	var shipperid = $(currentmodal+' .shippermodalid').val();

	var modal = '#shipper-creditinfomodal';


	$.post(server+'shipper.php',{getShipperCreditInfo:'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7',shipperid:shipperid},function(response){
		
		if(IsJsonString(response)==true){
			data = $.parseJSON(response);
		}
		else{
			data = 'NULL';
		}



		if(data!='NULL'){
			if(data['response']=='success'){
				$(tabshipper+' '+modal).modal('show');
				$(modal).on("shown.bs.modal",function(){
					$(modal).off("shown.bs.modal");

					$(modal+' .shipper-creditinfomodal-accountnumber').val(data['accountnumber']);
					$(modal+' .shipper-creditinfomodal-accountname').val(data['accountname']);
					$(modal+' .shipper-creditinfomodal-status').val(data['status']);
					$(modal+' .shipper-creditinfomodal-creditlimit').val(data['creditlimit']);
					$(modal+' .shipper-creditinfomodal-balance').val(data['outstandingbalance']);
					$(modal+' .shipper-creditinfomodal-billedamount').val(data['billedamount']);
					$(modal+' .shipper-creditinfomodal-unbilledamount').val(data['unbilledamount']);
					$(modal+' .shipper-creditinfomodal-creditbalance').val(data['creditbalance']);
					button.removeClass('disabled');
				});
			}
			else if(data['response']=='invalidshipperid'){
				say("Invalid Shipper ID. Please refresh page.");
				button.removeClass('disabled');
			}
		}
		else {
			alert(response);
			button.removeClass('disabled');
		}

	});





	





	




});



function IsJsonString(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
}




//ADD SHIPPER CS EMAIL ADDRESSES ////////////////////////////////////////////////////////////////
function addshippercsemailaddressesfunc(button){
	button.addClass('disabled');
	var modal = '#viewshippercsemailaddressesmodal';
	var email =  $(modal+' .viewshippercsemailaddressesmodal-email').val();
	var shipperid = $(modal+' #viewshippercsemailaddressesmodal-shipperid').val();


	if(email.trim()==''){
		$(modal+' .viewshippercsemailaddressesmodal-email').focus();
		$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an email address.</div></div>");
		button.removeClass('disabled');
	}
	else{


		$.post(server+'shipper.php',{addShipperCSEmailAddresses:'sdf#io2s9$dlIP$psLn!#oid($)soep$8%syo7',shipperid:shipperid,email:email},function(response){
			
			if(IsJsonString(response)==true){
				data = $.parseJSON(response);
			}
			else{
				data = 'NULL';
			}



			if(data!='NULL'){
				if(data['response']=='success'){
					$('#viewshippercsemailaddressesmodal-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-cs-email-addresses.php?shipperid='+shipperid,
											sortname: 'created_date',
											sortorder: "desc"
					}).flexReload();
					$(modal+' .viewshippercsemailaddressesmodal-email').val('').focus();
					$(modal+' .modal-errordiv').empty();
					button.removeClass('disabled');
					
				}
				else if(data['response']=='invalidshipperid'){
					say("Invalid Shipper ID. Please refresh page.");
					$(modal).modal('hide');
					button.removeClass('disabled');
				}
				else if(data['response']=='emailexists'){
					$(modal+' .viewshippercsemailaddressesmodal-email').focus();
					$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Email exists.</div></div>");
					button.removeClass('disabled');
				}
				else if(data['response']=='noemailprovided'){
					$(modal+' .viewshippercsemailaddressesmodal-email').focus();
					$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an email address.</div></div>");
					button.removeClass('disabled');
				}
				else{
					alert(data['response']);
					button.removeClass('disabled');
				}
			}
			else {
				alert(response);
				button.removeClass('disabled');
			}

		});

	}
}

$(document).off('click',tabshipper+' .viewshippercsemailaddressesmodal-insertratebtn:not(".disabled")').on('click',tabshipper+' .viewshippercsemailaddressesmodal-insertratebtn:not(".disabled")',function(){

	var button = $(this);
	addshippercsemailaddressesfunc(button);

});

$(document).off('keyup',tabshipper+' .viewshippercsemailaddressesmodal-email:not(".disabled")').on('keyup',tabshipper+' .viewshippercsemailaddressesmodal-email:not(".disabled")',function(e){
	
	var key = e.keycode||e.which;
	var button = $(this);
	if(key==13){
		addshippercsemailaddressesfunc(button);
	}
	
	

});



$(document).off('click',tabshipper+' .viewshippercsemailbtn:not(".disabled")').on('click',tabshipper+' .viewshippercsemailbtn:not(".disabled")',function(){

	var button = $(this);
	button.addClass('disabled');

	var modal = '#viewshippercsemailaddressesmodal';
	var shipperid = $(this).attr('shipperid');
	$(modal+' #viewshippercsemailaddressesmodal-shipperid').val(shipperid);
	$('#viewshippercsemailaddressesmodal-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-cs-email-addresses.php?shipperid='+shipperid,
											sortname: 'created_date',
											sortorder: "desc"
	}).flexReload();
	$(modal).modal('show');
	$(modal).off('shown.bs.modal').on('shown.bs.modal',function(){	
		
		$(modal).off('shown.bs.modal');
		button.removeClass('disabled');
	});

});


$(document).off('click',tabshipper+' .deleteshippercsemailaddressesbtn:not(".disabled")').on('click',tabshipper+' .deleteshippercsemailaddressesbtn:not(".disabled")',function(){
	var button = $(this);
		button.addClass('disabled');

	var modal = '#'+$(this).closest('.modal').attr('id');
	var shipperid = $(modal+' #viewshippercsemailaddressesmodal-shipperid').val();
	var tbl = '#viewshippercsemailaddressesmodal-tbl';

	if(parseInt($(tbl+' .viewshippercsemailaddressesmodal-checkbox:checked').length)>0){
				$.confirm({
					animation: 'bottom', 
					closeAnimation: 'top',
					animationSpeed: 1000,
					animationBounce:1,
					title: 'Confirmation',
					content: 'Delete selected row(s)?',
					confirmButton: 'Delete',
					cancelButton: 'Cancel',	
					confirmButtonClass: 'btn-maroon', 
					cancelButtonClass: 'btn-maroon', 
					theme: 'white', 

					confirm: function(){
							var data = [];
							$(tbl+' .viewshippercsemailaddressesmodal-checkbox:checked').each(function(){
								data.push($(this).attr('rowid'));
							});
							$.post('../scripts/shipper.php',{deleteSelectedCSEmailAddrRows:'skj$oihdtpoa$I#@4noi4AIFNlskoRboIh4!j3sio$*yhs',data:data},function(response){

								if(response.trim()=='success'){
									$('#viewshippercsemailaddressesmodal-tbl').flexOptions({
											url:'loadables/ajax/maintenance.shipper-cs-email-addresses.php?shipperid='+shipperid,
											sortname: 'email',
											sortorder: "asc"
									}).flexReload();
									button.removeClass('disabled');
								}
								else{
									alert(response);
									button.removeClass('disabled');
								}

							});
					},
					cancel:function(){
						button.removeClass('disabled');
					}
				});
	}
	else{
				say("Please select row(s) to be deleted");
	}

});

//ADD SHIPPER CS EMAIL ADDRESSES  - END  ////////////////////////////////////////////////////////////////