
				$('#sidebar-nav>.withsubmenu').click(function(){
					$(this).next('.submenu').slideToggle(600).siblings('.submenu').slideUp(100);

				});

				$('.submenu>div').click(function(){
					$(this).addClass('active').siblings().removeClass('active');//.parent().siblings().removeClass('active').children().removeClass('active');
					$(this).parent().siblings().removeClass('active');
					$(this).parent().siblings().children().removeClass('active');
				});

				$('.nosubmenu').click(function(){
					$(this).addClass('active').siblings().removeClass('active').children().removeClass('active');
				});
 		


				$(document).off('click','.submenu>div, .nosubmenu, #usersettings').on('click','.submenu>div, .nosubmenu, #usersettings',function(){
					var title = $(this).data('title');
					var tabid = $(this).attr('id');
					var menu = $(this);
					

					if($(".content>.content-tab-pane .content-tabs").find("li[data-pane='#"+tabid+"tabpane']").length>=1){
						$(".content>.content-tab-pane .content-tabs>li[data-pane='#"+tabid+"tabpane']").addClass('active').siblings().removeClass('active');
						$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").addClass('active').siblings().removeClass('active');
						if(tabid=='systemlog-menu'){
							$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='"+tabid+"tabpane']").load(menu.data('file'));
						}

					}
					else{
						$('#loading-img').removeClass('hidden');
						var js = pagescripts+$(this).data('js');
						$.getScript(js);
						$('.content').animate({scrollTop:0},300);

						$('.content>.content-tab-pane .content-tabs>li').removeClass('active');
						$('.content>.content-tab-pane .content-pane-wrapper>.content-pane').removeClass('active');
						$('.content>.content-tab-pane .content-tabs').append("<li data-pane='#"+tabid+"tabpane' class='active'>"+title+"<i class='fa fa-remove'></i></li>");
						$('.content>.content-tab-pane .content-pane-wrapper').append("<div class='content-pane active' id='"+tabid+"tabpane'></div>");
						$('.content>.content-tab-pane .content-pane-wrapper>.content-pane:last-child').load($(this).data('file'),function(){
								$('#loading-img').addClass('hidden');
						});
						
					}


				});
				




				/********************* CONTENT TAB AND PANES *************************/

				/** BUTTON CLICK SOUND ***/
				var obj = document.createElement("audio"); 
				obj.src="../resources/click.mp3";
				obj.volume=1;
				obj.autoPlay=false;
				obj.preLoad=true;
			        
				$(document).off('click','.content-tabs>li').on('click','.content-tabs>li',function(){
					$(this).addClass('active').siblings().removeClass('active');

					var wrapper = $(this).closest('.content-tab-pane'),
						pane = $(this).data('pane');

					$(wrapper).find(pane).addClass('active').siblings().removeClass('active');
					obj.play();
					/////////////////////////
					if(pane=='#systemlog-menutabpane'){
						$(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='systemlog-menutabpane']").load("System/system-log.php");
					}
					////////////////////
				});
				$(document).off('click','.content-tabs>li>.fa').on('click','.content-tabs>li>.fa',function(){
					var wrapper = $(this).closest('.content-tab-pane');
					var pane = $(this).closest('li').data('pane');

					$(this).closest('.content-tab-pane').find(pane).remove();
					$(this).closest('li').remove();

					wrapper.find('.content-pane-wrapper>div:first-child').addClass('active').siblings().removeClass('active');
					wrapper.find('.content-tabs>li:first-child').addClass('active').siblings().removeClass('active');

				});

				/********************* TAB AND PANES *************************/
				


				$(document).on('focusout','.inputpricefield',function(){
					var val = $(this).val();
					$(this).number( true, 2 );
				});
				$(document).on('focusout','.inputqtyfield',function(){
					var val = $(this).val();
					$(this).number( true, 0);
				});



				$(document).off('change','.addrdropregion').on('change','.addrdropregion',function(){
					
					var wrapper = $(this).closest('.addressgroupwrapper');
					var region = $(this).val();
						

					    $(wrapper).find(".addrdropcity").empty().trigger('change').select2({
					            ajax: {
					                    url: "loadables/dropdown/address-city.php?region="+region,
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
					                width:'100%'
					    });

					    $(wrapper).find(".addrdropzip").empty().trigger('change');

					    $(wrapper).find(".addrdropdistrict").empty().trigger('change');
				});

				$(document).off('change','.addrdropcity').on('change','.addrdropcity',function(){
					
					var wrapper = $(this).closest('.addressgroupwrapper');
					var city = $(this).val();

					 $(wrapper).find(".addrdropdistrict").empty().trigger('change').select2({
					            ajax: {
					                    url: "loadables/dropdown/address-district.php?city="+city,
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
					                width:'100%'
					 });

					 $(wrapper).find(".addrdropzip").empty().trigger('change');

					 
				});

				$(document).off('change','.addrdropdistrict').on('change','.addrdropdistrict',function(){
					var wrapper = $(this).closest('.addressgroupwrapper');
					var district = $(this).val();
					var city = $(this).closest('.addressgroupwrapper').find('.addrdropcity').val();
					var tmp = $(this);
					 $(wrapper).find(".addrdropzip").empty().trigger('change').select2({
					            ajax: {
					                    url: "loadables/dropdown/address-zip.php?district="+district+'&city='+city,
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
					                width:'100%'
					 });

					 $.post(configfldr+'post-functions.php',{getZipCode:'Fns!oi3ah434ad#2l211#$*3%',district:district,city:city},function(data){
					 	 if(data.trim()!=''){
								tmp.closest('.addressgroupwrapper').find(".addrdropzip").empty().append('<option selected value="'+data+'">'+data+'</option>').trigger('change');
							
					 	 }

					 });

				});


				$(document).off('change','.editdropregion').on('change','.editdropregion',function(){
					
					var wrapper = $(this).closest('.addressgroupwrapper');
					var region = $(this).val();
						

					    $(wrapper).find(".editdropcity").select2({
					            ajax: {
					                    url: "loadables/dropdown/address-city.php?region="+region,
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
					                width:'100%'
					    });
				});

				$(document).off('change','.editdropcity').on('change','.editdropcity',function(){
					
					var wrapper = $(this).closest('.addressgroupwrapper');
					var city = $(this).val();

					 $(wrapper).find(".editdropdistrict").select2({
					            ajax: {
					                    url: "loadables/dropdown/address-district.php?city="+city,
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
					                width:'100%'
					 });

					 
				});

				$(document).off('change','.editdropdistrict').on('change','.editdropdistrict',function(){
					var wrapper = $(this).closest('.addressgroupwrapper');
					var district = $(this).val();
					var city = $(this).closest('.addressgroupwrapper').find('.editdropcity').val();
					var tmp = $(this);

					 $(wrapper).find(".editdropzip").select2({
					            ajax: {
					                    url: "loadables/dropdown/address-zip.php?district="+district+"&city="+city,
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
					                width:'100%'
					 });

					$.post(configfldr+'post-functions.php',{getZipCode:'Fns!oi3ah434ad#2l211#$*3%',district:district,city:city},function(data){
					 	 if(data.trim()!=''){
								tmp.closest('.addressgroupwrapper').find(".editdropzip").empty().append('<option selected value="'+data+'">'+data+'</option>').trigger('change');
							
					 	 }

					 });

				});


				/********************* FIELD EDIT *************************/

				$(document).off('click','.controllabeliconedit:not(".disabled")').on('click','.controllabeliconedit:not(".disabled")',function(){
					var button = $(this);
					button.addClass("disabled");

					var modal = '#fieldeditmodal';
					var code = $(this).data("code");
					var table = $(this).data("table");
					var column = $(this).data("column");
					var txncolumn = $(this).data("txncolumn");
					var txnnumber = $(this).data("txnnumber");
					var type = $(this).data("type");
					var id = $(this).attr('id');

					$(modal+" .fieldeditmodal-code").val(code);
					$(modal+" .fieldeditmodal-table").val(table);
					$(modal+" .fieldeditmodal-column").val(column);
					$(modal+" .fieldeditmodal-txncolumn").val(txncolumn);
					$(modal+" .fieldeditmodal-txnnumber").val(txnnumber);
					$(modal+" .fieldeditmodal-type").val(type);
					$(modal+" .fieldeditmodal-id").val(id);
					$(modal+' .modal-errordiv').empty();

				

					$(modal).find(".fieldeditmodal-title").html("Edit "+$(this).data("label"));


					$.post(server+'main.php',{fieldeditprocessing:'sdfkjh$FI#@#NDOPs09#j!@IO#09aujj$Oi03n',code:code,table:table,column:column,txncolumn:txncolumn,txnnumber:txnnumber,type:type,id:id},function(data){
							data = $.parseJSON(data);

							if(data['response']=='success'){
								$(modal).find(".fieldeditmodal-oldvalue").val(data['oldvalue']);
								$(modal).modal("show");
								button.removeClass("disabled");
							}
							else if(data['response']=='nopermission'){
								say("Unable to edit. No user permission.");
								button.removeClass("disabled");
							}
							else if(data['response']=='tablenotexist'){
								say("Invalid Table["+table+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='columnnotexist'){
								say("Invalid Column["+column+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='txncolumnnotexist'){
								say("Invalid Transaction Column["+txncolumn+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='invalidtxnnumber'){
								say("Invalid Transaction Number["+txnnumber+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else{
								alert(data);
								button.removeClass("disabled");
							}
					});
					

				});


				$(document).off('click','#fieldeditmodal-savebtn:not(".disabled")').on('click','#fieldeditmodal-savebtn:not(".disabled")',function(){
					var button = $(this);
					button.addClass("disabled");

					var modal = '#'+$(this).closest('.modal').attr('id');
					var code = $(modal+' .fieldeditmodal-code').val();
					var table = $(modal+' .fieldeditmodal-table').val();
					var column = $(modal+' .fieldeditmodal-column').val();
					var txncolumn = $(modal+' .fieldeditmodal-txncolumn').val();
					var txnnumber = $(modal+' .fieldeditmodal-txnnumber').val();
					var type = $(modal+' .fieldeditmodal-type').val();
					var id = $(modal+' .fieldeditmodal-id').val();
					var newvalue = $(modal+' .fieldeditmodal-newvalue').val();

					$.post(server+'main.php',{saveFieldEditValue:'sdfkjh$FI#@#NDOPs09#j!@IO#09aujj$Oi03n',code:code,table:table,column:column,txncolumn:txncolumn,txnnumber:txnnumber,type:type,id:id,newvalue:newvalue},function(data){
							
							data = $.parseJSON(data);

							if(data['response']=='success'){
								$(modal).modal("hide");
								$(document).off("hidden.bs.modal",modal).on("hidden.bs.modal",modal,function(){
										$(modal+' input').val('');
										if(code=='WBCBM'){
												getWaybillInformation(txnnumber);
										}
										$(document).off("hidden.bs.modal",modal);
										button.removeClass("disabled");
								});

								
							}
							else if(data['response']=='nopermission'){
								say("Unable to edit. No user permission.");
								button.removeClass("disabled");
							}
							else if(data['response']=='tablenotexist'){
								say("Invalid Table["+table+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='columnnotexist'){
								say("Invalid Column["+column+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='txncolumnnotexist'){
								say("Invalid Transaction Column["+txncolumn+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='invalidtxnnumber'){
								say("Invalid Transaction Number["+txnnumber+"]. Please contact system administrator.");
								button.removeClass("disabled");
							}
							else if(data['response']=='notnumeric'){
								$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>New Value must be greater than or equal to zero.</div></div>");
								button.removeClass("disabled");
							}
							else if(data['response']=='newvaluenotprovided'){
								$(modal+' .modal-errordiv').html("<div class='message'><div class='message-content'><span class='closemessage'>&times;</span>Please provide new value.</div></div>");
								button.removeClass("disabled");
							}
							else{
								alert(data);
								button.removeClass("disabled");
							}
					});

					


				});



				

				
				









