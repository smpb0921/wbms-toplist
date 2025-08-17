var tabDCZR = '#districtcityzip-menutabpane';

$(document).off('change',tabDCZR+' .odaflag').on('change',tabDCZR+' .odaflag',function(){
		var modal = '#'+$(this).closest('.modal').attr('id');
		var flag = $(this).val();


		if(flag==1){
				$(modal+' .odaratewrapper').removeClass('hidden');
		}				
		else{
				$(modal+' .odaratewrapper').addClass('hidden');
		}

});


$(document).off('click','.editdistrictcityzipbtn').on('click','.editdistrictcityzipbtn',function(){
	var modal = "#editdistrictcityzipmodal";
	$(modal+' .district').val($(this).attr('district'));
	$(modal+' .city').val($(this).attr('city'));
	$(modal+' .zip').val($(this).attr('zip'));
	$(modal+' .cityleadtime').val($(this).attr('leadtime'));
	$(modal+' .districtcityzipmodalid').val($(this).attr('rowid'));
	$(modal+' .odaflag').val($(this).attr('odaflag')).trigger('change');
	$(modal+' .odarate').val($(this).attr('odarate'));

	if($(this).attr('region')!=null){
		$(modal+" .region").empty().append('<option selected value="'+$(this).attr('regionid')+'">'+$(this).attr('region')+'</option>').trigger('change');
	}
	else{
	    $(modal+" .region").empty().trigger('change');
	}
});

/***************************** SAVE | EDIT **************************************************/
$(document).off('click','.districtcityzipmodal-savebtn:not(".disabled")').on('click','.districtcityzipmodal-savebtn:not(".disabled")',function(){
	var modal = '#'+$(this).closest('.modal').attr('id'),
		district = $(modal+' .district').val(),
		city = $(modal+' .city').val(),
		zip = $(modal+' .zip').val(),
		region = $(modal+' .region').val(),
		leadtime = $(modal+' .cityleadtime').val(),
		odaflag = $(modal+' .odaflag').val(),
		odarate = 0,
		id='',
		newsort = 'created_date',
		source='add',
		button=$(this);
		button.addClass('disabled').attr('disabled','disabled');

	if(odaflag==1){
		odarate = $(modal+' .odarate').val();
	}

	if(modal=='#editdistrictcityzipmodal'){
		id = $(modal+' .districtcityzipmodalid').val();
		source = 'edit';
		newsort = 'updated_date';
	}

	if(district==''){
		$(modal+' .district').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide district.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(city==''){
		$(modal+' .city').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide city.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(zip==''){
		$(modal+' .zip').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide zip code.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(region==''||region=='null'||region=='NULL'||region==null){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select region/province.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	/*else if(leadtime.trim()==''||parseFloat(leadtime)<1){
		$(modal+' .cityleadtime').focus();
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide lead time (in days).</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}*/
	else if(odaflag==''||odaflag=='null'||odaflag=='NULL'||odaflag==null||odaflag==undefined){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please select ODA flag.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else if(odaflag==1&&(odarate.trim()==''||odarate<0)){
		$(modal+' .errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide ODA rate.</div></div>");
		button.removeAttr('disabled').removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		$.post(server+'district-city-zip.php',{districtcityzipSaveEdit:'j$isnDPo#P3sll3p23a3!@3kzlsO!mslo#k@',leadtime:leadtime,source:source,id:id,district:district,city:city,zip:zip,region:region,odaflag:odaflag,odarate:odarate},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						
						$('#districtcityziptable').flexOptions({
											url:'loadables/ajax/maintenance.district-city-zip.php',
											sortname: newsort,
											sortorder: "desc"
						}).flexReload(); 
						$('#loading-img').addClass('hidden');
						$(modal).off('hidden.bs.modal');
						button.removeAttr('disabled').removeClass('disabled');
						$(modal+' .district').val('');
						$(modal+' .city').val('');
						$(modal+' .zip').val('');
						$(modal+' .region').empty().trigger('change');



					});
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					button.removeAttr('disabled').removeClass('disabled');
				}
		});	
	}
});

/******************************* DELETE *****************************************************/





/***************************** UPLOAD ********************************************************/


$(document).off('click',tabDCZR+' #uploaddistrictcityzipmodal-uploadbtn:not(".disabled")').on('click',tabDCZR+' #uploaddistrictcityzipmodal-uploadbtn:not(".disabled")',function(){

	var modal = '#uploaddistrictcityzipmodal';
	var modal2 = '#districtcityzip-uploadtransactionlogmodal';
	var form = '#uploaddistrictcityzipmodal-form';
	var logframe = '#districtcityzipuploadtransactionlogframe';
	var button = $(this);
	button.addClass('disabled');

	if($(tabDCZR+' '+modal+' .uploaddistrictcityzipmodal-file').val().trim()==''){
		say('Please select a file to upload');
		button.removeClass('disabled');
	}
	else{
		$('#loading-img').removeClass('hidden');
		
		$(modal).modal('hide');
		$(document).off('hidden.bs.modal',tabDCZR+' '+modal).on('hidden.bs.modal',tabDCZR+' '+modal,function(){
			
			$(document).off('hidden.bs.modal',tabDCZR+' '+modal);
			$(tabDCZR+' '+modal2).modal('show');
			$(form).submit();

			$(logframe).load(function () {

				button.removeClass('disabled');

				$('#loading-img').addClass('hidden');

				$('#districtcityziptable').flexOptions({
						url:'loadables/ajax/maintenance.district-city-zip.php',
						sortname: "created_date",
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