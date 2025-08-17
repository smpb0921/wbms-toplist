var tabconsignee = '#consignee-menutabpane ';


////// INSERT CONTACT ROW
function consigneeContactInsertRow(modal,table){

	var contact = $(tabconsignee+modal+' .contactfld').val(),
	    phone = $(tabconsignee+modal+' .phonenumberfld').val(),
	    email = $(tabconsignee+modal+' .emailfld').val(),
	    mobile = $(tabconsignee+modal+' .mobilenumberfld').val(),
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
						"<span class='consigneecontact-contact'>"+contact+"</span>",
						"<span class='consigneecontact-phone'>"+phone+"</span>",
						"<span class='consigneecontact-email'>"+email+"</span>",
						"<span class='consigneecontact-mobile'>"+mobile+"</span>"
			]).draw();
	    	consigneeContactClearContactfields(modal);
		}
}

$(document).off('click',tabconsignee+' .consignee-insertcontactbtn').on('click',tabconsignee+' .consignee-insertcontactbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
	    consigneeContactInsertRow(modal,$(tabconsignee+modal+' .consigneecontactdetailstbl'));
});
////// INSERT CONTACT ROW END



////// REMOVE CONTACT ROW
function consigneeContactRemoveRow(modal,table){
	$(tabconsignee+modal+' '+table+' tbody tr .rowcheckbox:checked').each(function(){
		var tr = $(this).closest('tr');
		$(tabconsignee+modal+' '+table).DataTable().row(tr).remove().draw();
	});
}

$(document).off('click',tabconsignee+' .consignee-removecontactbtn').on('click',tabconsignee+' .consignee-removecontactbtn',function(){
		var tbl = '.consigneecontactdetailstbl';
		var modal = '#'+$(this).closest('.modal').attr('id');
		consigneeContactRemoveRow(modal,tbl);
});
/////// REMOVE CONTACT ROW END



////// CLEAR CONTACT INPUT FIELDS
function consigneeContactClearContactfields(modal){
		$(tabconsignee+modal+' .contactfld').val('');
	    $(tabconsignee+modal+' .phonenumberfld').val('');
	    $(tabconsignee+modal+' .emailfld').val('');
	    $(tabconsignee+modal+' .mobilenumberfld').val('');
	    $(tabconsignee+modal+' .contactfld').focus();
}

$(document).off('click',tabconsignee+' .consignee-clearcontactfieldsbtn').on('click',tabconsignee+' .consignee-clearcontactfieldsbtn',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		consigneeContactClearContactfields(modal);
});
////// CLEAR CONTACT INPUT FIELDS END



////// DEFAULT FLAG YES LIMIT TO 1
$(document).off('change',tabconsignee+' .defaultcontactflag').on('change',tabconsignee+' .defaultcontactflag',function(){
	$(this).closest('tr').siblings().find('.defaultcontactflag').val(0);
});
////// DEFAULT FLAG YES LIMIT TO 1 END





////// CLEAR MODAL FIELDS
function consigneeClearInputFields(modal,type){
	$(modal+' .inputtxtfld').val(''),
	$(modal+' .inputslctfld').val('').trigger('change');
	if(type=='edit'){
		$(modal+' .mdlIDfld').val('');
	}
}
////// CLEAR MODAL FIELDS END








////////////////// MAIN BUTTONS - ADD & EDIT
$(document).off('click','.consigneemodal-savebtn:not(".disabled")').on('click','.consigneemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		accountnumber = $(modal+' .accountnumber').val(),
		accountname = $(modal+' .accountname').val(),
		companyname = $(modal+' .companyname').val(),
		idnumber = $(modal+' .idnumber').val(),
		street = $(modal+' .street').val(),
		district = $(modal+' .district').val(),
		city = $(modal+' .city').val(),
		province = $(modal+' .province').val(),
		zipcode = $(modal+' .zipcode').val(),
		country = $(modal+' .country').val(),
		contactcount = $(modal+' .consigneecontactdetailstbl tbody tr.mydatatablerow').length,
		contact = [],
		email = [],
		phonenumber = [],
		mobilenumber = [],
		defaultflag = [],
		sendsmsflag = [],
		sendemailflag = [],
		id='',
		inactiveflag = 0,
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(modal=='#editconsigneemodal'){
		id = $(modal+' .consigneemodalid').val();
		inactiveflag = $(modal+' .inactiveflag').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(accountnumber==''&&modal=='#editconsigneemodal'){
		$(modal+' .accountnumber').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please Account Number.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(accountname==''){
		$(modal+' .accountname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide Account Name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(companyname==''){
		$(modal+' .companyname').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide company name.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(street==''){
		$(modal+' .street').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide street address.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(city==''){
		$(modal+' .city').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a city.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(province==''){
		$(modal+' .province').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a region/province.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(parseInt(contactcount)==0){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide atleast 1 contact information</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else{
		$(modal+' .errordiv').empty();
		$('#loading-img').removeClass('hidden');
		$(tabconsignee+modal+' .consigneecontactdetailstbl tbody tr.mydatatablerow').each(function(){
				contact.push($(this).find('.consigneecontact-contact').text());
				email.push($(this).find('.consigneecontact-email').text());
				phonenumber.push($(this).find('.consigneecontact-phone').text());
				mobilenumber.push($(this).find('.consigneecontact-mobile').text());
				defaultflag.push($(this).find('.defaultcontactflag').val());
				sendsmsflag.push($(this).find('.sendsmsflag').val());
				sendemailflag.push($(this).find('.sendemailflag').val());
		});

		
		
		$.post(server+'consignee.php',{consigneeSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',idnumber:idnumber,source:source,id:id,accountnumber:accountnumber,accountname:accountname,companyname:companyname,street:street,district:district,city:city,province:province,zipcode:zipcode,country:country,inactiveflag:inactiveflag,contact:contact,email:email,phonenumber:phonenumber,mobilenumber:mobilenumber,defaultflag:defaultflag,sendsmsflag:sendsmsflag,sendemailflag:sendemailflag},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#consigneetable').flexOptions({
											url:'loadables/ajax/maintenance.consignee.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');


						consigneeClearInputFields(modal,source);
						$(tabconsignee+modal+' .consigneecontactdetailstbl').DataTable().clear().draw();
						$(modal+' .errordiv').empty();

					});
				}
				else if(data.trim()=='codeexist'){
					$('#loading-img').addClass('hidden');
					$(modal+' .accountnumber').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Account Number exists. Please provide a different account number.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else if(data.trim()=='idnumberexists'){
					$('#loading-img').addClass('hidden');
					$(modal+' .idnumber').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>ID Number exists. Please provide a different ID number.</div></div>");
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




$(document).off('click','.editconsigneebtn').on('click','.editconsigneebtn',function(){
	var modal = "#editconsigneemodal";
	var rowid = $(this).attr('rowid');

	consigneeClearInputFields(modal,'edit');
	$(tabconsignee+modal+' .consigneecontactdetailstbl').DataTable().search('').clear().draw();

	$(modal+' .consigneemodalid').val(rowid);

	$.post(server+'consignee.php',{ConsigneeGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		
		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .accountnumber').val(rsp['accountnumber']);
			$(modal+' .accountname').val(rsp['accountname']);
			$(modal+' .companyname').val(rsp['companyname']);
			$(modal+' .street').val(rsp['street']);
			$(modal+' .district').val(rsp['district']);
			$(modal+' .city').val(rsp['city']);
			$(modal+' .province').val(rsp['province']);
			$(modal+' .zipcode').val(rsp['zipcode']);
			$(modal+' .inactiveflag').val(rsp['inactiveflag']).trigger('change');

			if(rsp["district"]!=null){
		        $(modal+" .district").empty().append('<option selected value="'+rsp["district"]+'">'+rsp["district"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .district").empty().trigger('change');
		    }
		    if(rsp["city"]!=null){
		        $(modal+" .city").empty().append('<option selected value="'+rsp["city"]+'">'+rsp["city"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .city").empty().trigger('change');
		    }
		    if(rsp["province"]!=null){
		        $(modal+" .province").empty().append('<option selected value="'+rsp["province"]+'">'+rsp["province"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .province").empty().trigger('change');
		    }
		    if(rsp["zipcode"]!=null){
		        $(modal+" .zipcode").empty().append('<option selected value="'+rsp["zipcode"]+'">'+rsp["zipcode"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .zipcode").empty().trigger('change');
		    }

		    if(rsp["country"]!=null){
		        $(modal+" .country").empty().append('<option selected value="'+rsp["country"]+'">'+rsp["country"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .country").empty().trigger('change');
		    }

		    $.post(server+'consignee.php',{ConsigneeContactGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data2){
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

		    		$(tabconsignee+modal+' .consigneecontactdetailstbl').DataTable().row.add([
							"<input type='checkbox' class='rowcheckbox'/>",
							defaultflag,
							sendsmsflag,
							sendemailflag,
							"<span class='consigneecontact-contact'>"+rsp1[i]['contact']+"</span>",
							"<span class='consigneecontact-phone'>"+rsp1[i]['phone']+"</span>",
							"<span class='consigneecontact-email'>"+rsp1[i]['email']+"</span>",
							"<span class='consigneecontact-mobile'>"+rsp1[i]['mobile']+"</span>"
					]).draw();
		    	}
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


$(document).off('show.bs.modal',tabconsignee+' #addconsigneemodal').on('show.bs.modal',tabconsignee+' #addconsigneemodal',function(){
    $("#addconsigneemodal .countriesdropdownselect").empty().append('<option selected value="Philippines">Philippines</option>').trigger('change');
    
});



/***************************** UPLOAD ********************************************************/


$(document).off('click',tabconsignee+' #uploadconsigneemodal-uploadbtn:not(".disabled")').on('click',tabconsignee+' #uploadconsigneemodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadconsigneemodal';
	var modal2 = '#consignee-uploadtransactionlogmodal';
	var form = '#uploadconsigneemodal-form';
	var logframe = '#consigneeuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabconsignee+' '+modal+' .uploadconsigneemodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabconsignee+' '+modal).on('hidden.bs.modal',tabconsignee+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabconsignee+' '+modal);
			$(tabconsignee+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#consigneetable').flexOptions({
											url:'loadables/ajax/maintenance.consignee.php',
											sortname: 'created_date',
											sortorder: "desc"
				}).flexReload(); 
				
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