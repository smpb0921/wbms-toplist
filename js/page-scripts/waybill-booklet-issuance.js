
var tabWYBKLTISS = '#waybillbookletissuance-menutabpane';
$(document).off('click','.editwaybillbookletissuancebtn').on('click','.editwaybillbookletissuancebtn',function(){
	var modal = "#editwaybillbookletissuancemodal";
	var rowid = $(this).attr('rowid')
	$(modal+' .waybillbookletissuancemodalid').val(rowid);

	$.post(server+'waybill-booklet-issuance.php',{WaybillBookletIssuanceGetInfo:'kjoI$H2oiaah3h0$09jDppo92po@k@',id:rowid},function(data){
		//alert(data);
		rsp = $.parseJSON(data);
		if(rsp['response']=='success'){
			$(modal+' .issuancedate').val(rsp['issuancedate']);
			$(modal+' .validitydate').val(rsp['validitydate']);
			$(modal+' .issuedto').val(rsp['issuedto']);
			$(modal+' .startseries').val(rsp['startseries']);
			$(modal+' .endseries').val(rsp['endseries']);
			$(modal+' .courierflag').val(rsp['courierflag']).trigger('change');
			$(modal+' .remarks').val(rsp['remarks']);
			if(rsp["location"]!=null){
		        $(modal+" .location").empty().append('<option selected value="'+rsp["locationid"]+'">'+rsp["location"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .location").empty().trigger('change');
		    }

		    if(rsp["courier"]!=null){
		        $(modal+" .courier").empty().append('<option selected value="'+rsp["courier"]+'">'+rsp["courier"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .courier").empty().trigger('change');
		    }

		    if(rsp["shipper"]!=null){
		        $(modal+" .shipper").empty().append('<option selected value="'+rsp["shipperid"]+'">'+rsp["shipper"]+'</option>').trigger('change');
		    }
		    else{
		        $(modal+" .shipper").empty().trigger('change');
		    }
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

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.waybillbookletissuancemodal-savebtn:not(".disabled")').on('click','.waybillbookletissuancemodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		issuancedate = $(modal+' .issuancedate').val(),
		validitydate = $(modal+' .validitydate').val(),
		issuedto = $(modal+' .issuedto').val(),
		location = $(modal+' .location').val(),
		startseries = $(modal+' .startseries').val(),
		endseries = $(modal+' .endseries').val(),
		remarks = $(modal+' .remarks').val(),
		courierflag = $(modal+' .courierflag').val(),
		courier = 'NULL',
		shipper = 'NULL',
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');


	if(courierflag=='1'){
		courier = $(modal+' .courier').val();
	}
	else if(courierflag=='0'){
		shipper = $(modal+' .shipper').val();
	}

	if(modal=='#editwaybillbookletissuancemodal'){
		id = $(modal+' .waybillbookletissuancemodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(issuancedate==''){
		$(modal+' .issuancedate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide an issuance date.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(validitydate==''){
		$(modal+' .validitydate').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a validity date.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(issuedto==''){
		$(modal+' .issuedto').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a value for issued to field</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(location==''||location=='null'||location=='NULL'||location==null){
		$(modal+' .location').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select a location.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else if((shipper==''||shipper=='null'||shipper=='NULL'||shipper==null)&&courierflag=='0'){
		$(modal+' .shipper').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select shipper.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if((courier==''||courier=='null'||courier=='NULL'||courier==null)&&courierflag=='1'){
		$(modal+' .courier').select2('open');
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select courier.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(startseries==''){
		$(modal+' .startseries').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a valid booklet start series.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(endseries==''){
		$(modal+' .endseries').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide a valid booklet end series.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(parseInt(endseries)<=parseInt(startseries)){
		$(modal+' .endseries').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid booklet end series.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'waybill-booklet-issuance.php',{WaybillBookletIssuanceSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',source:source,id:id,issuancedate:issuancedate,validitydate:validitydate,issuedto:issuedto,location:location,startseries:startseries,endseries:endseries,remarks:remarks,courierflag:courierflag,courier:courier,shipper:shipper},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#waybillbookletissuancetable').flexOptions({
											url:'loadables/ajax/maintenance.waybill-booklet-issuance.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' input, '+modal+' textarea').val('');
						$(modal+' .location').empty().trigger('change');
						$(modal+' .shipper').empty().trigger('change');
						$(modal+' .courier').empty().trigger('change');
						$(modal+' .errordiv').empty();

					});
				}
				else if(data.trim()=='invalidissuancedate'){
					$('#loading-img').addClass('hidden');
					$(modal+' .issuancedate').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid issuance date.</div></div>");
					button.removeAttr('disabled').removeClass('disabled');
				}
				else if(data.trim()=='invalidvaliditydate'){
					$('#loading-img').addClass('hidden');
					$(modal+' .validitydate').focus();
					$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Invalid validity date.</div></div>");
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

/******************************* DELETE *****************************************************/


$(document).off('change',tabWYBKLTISS+' .courierflag').on('change',tabWYBKLTISS+' .courierflag',function(){
	var modal = "#"+$(this).closest('.modal').attr('id');
	var flag = $(this).val();


	if(flag=='1'){
		$(modal+' .courier').empty().trigger('change');
		$(modal+' .shipper').empty().trigger('change');

		$(modal+' .shippergroupwrapper').addClass('hidden');
		$(modal+' .couriergroupwrapper').removeClass('hidden');

	}
	else if(flag=='0'){
		$(modal+' .shipper').empty().trigger('change');
		$(modal+' .courier').empty().trigger('change');

		$(modal+' .shippergroupwrapper').removeClass('hidden');
		$(modal+' .couriergroupwrapper').addClass('hidden');
	}
	


});

/************************************* UPLOAD *************************************************/

$(document).off('click',tabWYBKLTISS+' #uploadwaybillbookletissuancemodal-uploadbtn:not(".disabled")').on('click',tabWYBKLTISS+' #uploadwaybillbookletissuancemodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploadwaybillbookletissuancemodal';
	var modal2 = '#waybillbookletissuance-uploadtransactionlogmodal';
	var form = '#uploadwaybillbookletissuancemodal-form';
	var logframe = '#waybillbookletissuanceuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabWYBKLTISS+' '+modal+' .uploadwaybillbookletissuancemodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabWYBKLTISS+' '+modal).on('hidden.bs.modal',tabWYBKLTISS+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabWYBKLTISS+' '+modal);
			$(tabWYBKLTISS+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#waybillbookletissuancetable').flexOptions({
						url:'loadables/ajax/maintenance.waybill-booklet-issuance.php',
						sortname: "created_date",
						sortorder: "desc"
				}).flexReload();

			});

		});
	
	}

});
/************************************* UPLOAD END *************************************************/