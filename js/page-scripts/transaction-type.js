$(document).off('click','#transactiontypeedit-savebtn').on('click','#transactiontypeedit-savebtn',function(){
		$(this).attr('disabled',true);
		var id, code, desc, prefix, numseries, modal;
		modal = '#'+$(this).closest('.modal').attr('id');
		id=$(modal+' .transactiontypeid').val();
		code=$(modal+' .code').val();
		desc=$(modal+' .desc').val();
		prefix=$(modal+' .prefix').val();
		numseries=$(modal+' .numseries').val();
		button = $(this);
		$('#loading-img').removeClass('hidden');
		$.post(server+'transaction-type.php',{DmalePNElaporjd:'edit',id:id,code:code,desc:desc,prefix:prefix,numseries:numseries},function(data){
				if(data.trim()=='success'){
					$(modal).modal('hide');
					$(modal).on('hidden.bs.modal',function(){
						loadpage(".content>.content-tab-pane .content-pane-wrapper>.content-pane[id='transactiontype-menutabpane']",'System/transaction-type.php');
					});
					$('#loading-img').addClass('hidden');
				}
				else if(data.trim()=='codeexist'){
					$('#loading-img').addClass('hidden');
					say("<b>Code Exists</b>. Please enter another code.");
					button.removeAttr('disabled');
					$(modal+' .code').focus();
				}
				else{
					$('#loading-img').addClass('hidden');
					alert(data);
					button.removeAttr('disabled');
				}
		});	
});
$(document).off('click','.edittranstypebtn').on('click','.edittranstypebtn',function(){
	$('#edittransactiontypemodal .transactiontypeid').val($(this).attr('rowid'));
	$('#edittransactiontypemodal .code').val($(this).attr('code'));
	$('#edittransactiontypemodal .desc').val($(this).attr('desc'));
	$('#edittransactiontypemodal .prefix').val($(this).attr('prefix'));
	$('#edittransactiontypemodal .numseries').val($(this).attr('numseries'));
});