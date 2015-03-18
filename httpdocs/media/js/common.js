/** MISC FUNCTIONS **/
$(function (){
	var base_url="http://"+(window.location.host);
	$(document).keyup(function (e){
		if(e.keyCode==27){ removeBlockade();}
	});
	
	$(".uploaded_image").not('.documentbrowser_search .uploaded_image').load(function(){
		$width=$(this).width();
		$height=$(this).height();
		//$(this).css({'width':$width, 'height':$height});
		$(this).css({'height':$height});
		$(this).parent().find(".hold_img_space").remove();
		$(this).parent().find(".remove_image").remove();
		$(this).parent().find(".crop_image").remove();
		var position=$(this).position();
//		$(this).parent().append('<img src="/media/images/black_cancel_btn.png" class="remove_image" style="cursor:pointer; position:absolute; top:'+(position.top-18)+'px; left:'+(position.left-18+$width)+'px; z-index:2;"/>');
        $(this).parent().append('<img src="/media/images/crop.png" class="crop_image file_cropper" style="cursor:pointer; position:absolute; top:'+(position.top-18)+'px; left:'+(position.left-18+$width)+'px; z-index:2;"/>');
	});
	
	$("table.fullheight").css({
		'height':$(document).height()
	});
	
	$(".tr_with_actions").hover(
		function(){
			$(this).find("div").show();
		},
		function(){
			$("div.toggle_actions").hide();
		}
	);
	
	$('.tooltip').qtip({
			content:{attr:'tip'},
			position:{my:'center left', at:'center right'},
			show:'focus',
			hide:'blur'
	});
	
	$("#help_button").click(function (e){
		$(".inneroverlay").live('click', function(e){e.stopPropagation();});
		blockade();
		$('.inneroverlay').html('<img class="loaderimage" src="/media/images/loading.gif"><br/>please wait...');
		$('.inneroverlay').removeClass('centeredloader');
		
		$.ajax({
			type:'GET',
			url:'/cms/admin/help',
			success:function(data)
			{
				$('.inneroverlay').html(data);
			},
			error:function()
			{	
				$('.inneroverlay').animate({'width':500});
				$('.inneroverlay').html('Please contact us directly: <br/><a href="mailto:tech@lavisual.com" style="font-weight:normal;">tech@lavisual.com</a>');
				addCloseButton2();
			}
		});
	});	
	
	$("input[type='submit'], input[type='button']").each(function(){
		$(this).css({
			'font-size':'14px',
			'color':'#000',
			'background':"url('/media/images/button_background.png')", 
			'border':'1px solid #999',
			'border-bottom':'2px solid #BBB',
			'cursor':'pointer',
			'padding':'5px 20px',
			'text-shadow':'1px 1px 0px #CCC',
			'border-radius':5,
			'-moz-border-radius':5,
			'-webkit-border-radius':5
		});
		
		$(this).hover(
			function(){
				$(this).css({'background':'#CCC none'});
			},
			function(){
				$(this).css({'background':"url('/media/images/button_background.png')"});
			}
		);
	});
	
	$(".checkmark :input:checkbox").click(function(){
		if($(this).parent().parent().hasClass('check1'))
			{$(this).parent().parent().removeClass('check1');}
		else
			{$(this).parent().parent().addClass('check1');}
	});
	
	$(".highlight_parent_image").each(function(){
		if(this.checked)
		{
			$(this).css('z-index', '100');
			$(this).parent('td').find('img').css({'opacity':'0.3'});
			$(this).parent('td').find('img').addClass('chosen');
		}
	});
	
	$(".highlight_parent_image").click(function(e){
		if($(this).parent('td').find('img').hasClass('chosen'))
		{
			$(this).parent('td').find('img').css({'opacity':'1'});
			$(this).parent('td').find('img').removeClass('chosen');		
		}
		else
		{
			$(this).css('z-index', '100');
			$(this).parent('td').find('img').css({'opacity':'0.5'});
			$(this).parent('td').find('img').addClass('chosen');
		}
	});
	
	$(".hoverswap").hover(
		function () {
			$(this).attr("src", $(this).attr("src").replace(/.png/, "_over.png"));
		},
		function () {
			$(this).attr("src", $(this).attr("src").replace(/_over.png/, ".png"));
		}
	);
	
	$(".checkmark").hover(
	function(){$(this).addClass('check2');},
	function(){$(this).removeClass('check2');}
	);	
	
	//checks to make sure the amount entered is not greater than the max amount allowed
	$(".max_num").change(function (){
		$val=parseFloat($(this).val());
		$max=parseFloat($(this).attr('max'));
		if($val>$max)
		{
			$(this).val($max);
			alert("The maximum amount is "+$max);
		}
		else
		{
			$(this).val($val.toFixed(2));
		}
	});	
	
	//disable multiclick
	$(".no_double_click").click(function (){
		$(this).fadeOut();
		$(".cancel_double_click").fadeOut();
	});
	
	$(".sortable_tbody").sortable({ 
		cursor: 'move',
		update:function (event, ui){
			$data=($(".sortable_form").serialize());
			$url=($(".sorters_url").html());
			$.ajax({
				type:'POST',
				url:$url,
				data:$data,
				success:function(e){$(".flash_bar").show().effect("highlight", {color:"yellow"}, 900).fadeOut(500);}
			});
		}
	});
	$(".sortable_tbody").disableSelection();
	
	$(".timestamp_datetime").datetimepicker({
		ampm: true,
		timeFormat: 'h:mm tt',
		separator:' at ',
		dateFormat:'M d yy',
		changeMonth:true,
		showOtherMonths: true,
		selectOtherMonths: true
	});
	
	$(".timestamp_datetime").change(function(){
		$data=$(this).datetimepicker("getDate");
		$info=($data.getFullYear()+'-'+($data.getMonth()+1)+'-'+$data.getDate()+' '+$data.getHours()+':'+$data.getMinutes()+':00');
		$("."+$(this).attr('alternate')).val($info);
	});	
	
	
	$(".date1").datepicker();
	$(".date2").datepicker({
		minDate: new Date()
	});
	$(".date3").datepicker({maxDate:new Date()});
	
	
	$(".toggle_publish").click(function(){
		$.get($(this).attr('href'));
		$html=$(this).attr('rel');
		if($html=='0')
		{
			$(this).html('<img src="/media/images/icons/on.png"/> Published');
			$(this).attr('rel', 1);
		}
		else
		{
			$(this).html('<img src="/media/images/icons/off.png"/> Draft');
			$(this).attr('rel', 0);
		}
		return false;
	});
	
	$(".swapper").hover(
			function (){
				$(this).attr("src", $(this).attr("hover"));
				$(this).css('z-index', 100);
				$(this).css('position', 'relative');
				
			},
			function (){
				$(this).attr("src", $(this).attr("static"));
				$(this).css('z-index', 0);
				$(this).css('position', 'static');
			}
	);
	
	$(".swapper").mousedown(function (){
			$(this).attr("src", $(this).attr("active"));
	});
	
	$(".swapper").mouseup(function (){
		$(this).attr("src", $(this).attr("static"));
	});
	
	$("a.dashboard").hover(
			function (){
				$(".dashboard_text").html($(this).attr("rel"));
			},
			function (){
				$(".dashboard_text").html('&nbsp;');
			}
	);

	$(".previewLinkm2m, .quick_delete_view, .quick_preview, .quick_add").live('click',function (){
		blockade();
		$('.inneroverlay').html('<iframe id="quick_iframe" style="border:0; width: 625px; height: 500px;"  src="'+$(this).attr("href")+'"></iframe>').addClass('iframe');
		$(".inneroverlay").find('.user_bar').remove();
		return false;		
	});
	
	$(".quick_add").live('click',function (){
		blockade();
		$('.inneroverlay').html('<iframe id="quick_iframe" style="border:0; width: 625px; height: 500px;"  src="'+$(this).attr("href")+'"></iframe>').addClass('iframe');
		$(".inneroverlay").find('.user_bar').remove();	
	});	
	
	
	
	$("._search").focus(function (){
		if($(this).val()=='search...') $(this).val('');
	});
	
	$("._search").blur(function (){
		if($(this).val()=='') $(this).val('search...');
	});
	
	$("._searchform").submit(function (){
		if($("._search").val()=='' || $("._search").val()=='search...')
		{
			$("._search").effect("highlight", {color:"#00CCFF"}, 500);
			return false;
		}
	});
	
	$(".delete_by_line").click(function(){
		$url=$(this).attr('href');
		$.get($url);
		blockade();
	});
	
	
	$(".restore_by_line").click(function(){
		$url=$(this).attr('href');
		$.get($url);
		blockade();
	});	

	$("a.restore_items").click(function(){
		$(".restoration").slideToggle();
		return false;
	});
	
	$("a.restore_items").toggle(
		function(){ $(this).html('close');	},
		function (){ $(this).html('restore');}
	);
	
	$(".trigger_blockade").click(function(){
		blockade();
	});
		
		
	$(".quick_view_edit").click(function(){
		blockade();
		addCloseButton();
		$('.inneroverlay').html('<iframe style="border:0; width: 625px; height: 500px;"  src="'+$(this).attr("href")+'"></iframe>').addClass('iframe');
		$(".inneroverlay").find('.user_bar').remove();
		$(".closebutton").click(function(e){
			e.stopPropagation();
			blockade();
			location.reload(true);
			});
		return false;
	});
	
	$("a.popup").each(function (e){
		$(this).css('cursor', 'pointer');
		$(this).click(function(){
			$divcontent=$("div.popup").eq(e).html();
			blockade();
			$('.inneroverlay').html($divcontent);
			$('.inneroverlay').addClass('embeddedcenter');
			addCloseButton();
		});
	});
	
	$("img.popup").click(function(){
		$src=$(this).attr('rel');
		blockade();
		$('.inneroverlay').html('<img src="/media/imagecache.php?width=500&height=500&image='+$src+'"/>');
		$('.inneroverlay').addClass('embeddedcenter imgborder');
		addCloseButton();
	});
	
	$(".grandoverlay").live('click', function(){
		removeBlockade();
	});
	
	$(".inneroverlay img").live('click', function(e){
		e.stopPropagation();
	});
	
	$(".closebutton").live('click', function(e){
		e.stopPropagation();
		removeBlockade();
	});

	$(".delete_confirm").click(function(){
		
		var e= confirm($(this).attr('m'));
		if(e)
		{
			blockade();
		}
		return e;
	});
	
	$("#mastercheck").click(function(){
		$name=$(this).attr('class');
		var checked_status=this.checked;
		$(":input[@name="+$name+"]").each(function(){
			this.checked=checked_status;
		});
	});
	
	$("#mastercheck2").click(function(){
		$name=$(this).attr('class');
		var checked_status=this.checked;
		$("form.mastercheck2 :input[@name="+$name+"]").each(function(){
			this.checked=checked_status;
		});
	});
	
	$(".mainform_disabled").submit(function (){
		blockade();
	});
	
	$(".mainform").submit(function(){
		$(".extra_parameters").fadeOut(function(){
			$(".extra_parameters").remove();
			$data=($(".mainform").serialize());
			$url=$(".mainform").attr('action')+'/ajax';
			blockade();
			$.ajax({
				type:	'POST',
				url:	$url,
				data:	$data,
				dataType:'json',
				error:function(a,b,c){
					alert("There was an error with your post. Please try again. \n"+a+"\n"+b+"\n"+c);
					removeBlockade();
				},				
				success:function(rdata){
					window.location.href=rdata.url;
				}
			});
		});
		return false;
	});
	
	$(".closeoverlay").live('click', function (){
		$('.grandoverlay').html('');
		$(".grandoverlay").remove();
		return false;
	});	
	
	$(".removerelationship").live('click', function(){
		$selectclass=$(this).attr("selectparent");
		$this=$(this);
		$(this).parent('span').remove();
		$("select."+$selectclass).remove();
		$.ajax({
			type:'POST',
			url:$(".mainform").attr("action")+"/ajax",
			data:'ajax=quick&action=delete&table='+$this.attr('table')+'&where_col='+$this.attr('where_col')+'&where_data='+$this.attr('where_data')+'&rel_table='+$this.attr('rel')+'&rel_data='+$this.attr('rel_data')
		});
		return false;
	});
	
	$(".addnew").click(function(){
		$table=$(this).attr('rel');
		$url=$(this).attr('href');
		openAddDialog($url);
		return false;
	});
	
	$(".addanother").live('change', function (){
		$unique_name=($(this).attr('name'));
		$related_table_id=$(this).attr('rel');
		$related_table_value=$(this).attr('rel_value');
		$reference_id=$(this).attr("reference_id");
		$table=$(this).attr('table');
		$action=$(this).attr('action');
		$col=$(this).attr('col');
		$val=$(this).val();
		$unique_class=$table+$val;
		$(this).addClass($unique_class);

		if($(this).val()>0 && !$(this).hasClass('used_'+$unique_name))
		{
			if($(this).children().length-1 >1)
			{
				$(this).after('<select col="'+$col+'" name="'+$(this).attr('name')+'"  action="'+$action+'" table="'+$table+'" rel_value="'+$related_table_value+'" rel="'+$related_table_id+'" reference_id="'+$reference_id+'" class="apphold_'+$unique_name+' addanother '+$unique_name+'" >'+$(this).html()+'</select>');
				
			}
			$(this).after('<span class="successbar heldsuccess"><img src="/media/images/loading_small.gif" alt=""/><br/></span>');
			
			$.ajax({
				type:'POST',
				url:$(".mainform").attr('action')+'/ajax',
				data:"ajax=quick&action="+$action+"&set_col="+$related_table_id+"&set_data="+$related_table_value+"&where_col="+$unique_name+"&where_data="+$val+"&table="+$table,
				dataType:'json',
				success:function(datar){
					if(datar.status==1)
					{
						$(".heldsuccess").html('<a href="" selectparent="'+$unique_class+'" where_col="'+$related_table_id+'" where_data="'+$related_table_value+'" table="'+$table+'" rel_data="'+$val+'" rel="'+$reference_id+'" class="removerelationship">remove</a><br/>');
						$(".heldsuccess").removeClass("heldsuccess");
					}
					else
					{
						$(".heldsuccess").html("&nbsp;Error: Save this page and try again.<br/>");
						$(".heldsuccess").removeClass("heldsuccess");
					}
				}
			});
			
			$("select.apphold_"+$unique_name+" option[value='"+$(this).val()+"']").remove();
			$("select.apphold_"+$unique_name).removeClass('apphold_'+$unique_name);
			$("select.used_"+$unique_name+" option[value='"+$(this).val()+"']").remove();
			$(this).addClass('used_'+$unique_name);
			$(this).attr('disabled', 'disabled');
		}
	});
});

function openAddDialog(purl){
	removeBlockade();
	$("body").prepend('<div class="grandoverlay"></div>');
	$('.grandoverlay').append('<div class="inneroverlay"></div>');
	$('.inneroverlay').html('<img class="loaderimage" src="/media/images/loading.gif"/>');
	
	$.ajax({
		type	:	'POST',
		url		:	purl,
		success	:	function (data){
						$(".loaderimage").fadeOut(function (){
							$(".inneroverlay").append(data);
						});
					},
		error	:	function(){
						alert('An error has occurred. Please add this item manually, then return to edit. Sorry for the inconvenience');
						$(".closeoverlay").click();
				}
	});
	//close btn
	$(".inneroverlay").append('<div style="text-align:right">[ <a href="" class="closeoverlay">close</a> ]</div>');
}

function submit_from_frame(url, data)
{
	blockade();	
	$.ajax({
		type	:	'POST',
		url		:	url,
		data	:	data,
		success	:	function(){window.location.reload();},
		error	:	function(){window.location.reload();}
	});	
}

//puts a large overlay on the ENTIRE page
function removeBlockade(){
	$("body").removeClass("blockade");
	$('.grandoverlay').html('');
	$('.grandoverlay').remove();
}
function blockade(){
	removeBlockade();
	var base_url="http://"+(window.location.host);
	$("body").addClass("blockade");
	$("body").prepend('<div class="grandoverlay"></div>');
	
	$('.grandoverlay').append('<div class="inneroverlay noborder centeredloader"></div>');
	$('.inneroverlay').html('<img class="loaderimage" src="'+base_url+'/media/images/loading.gif"/><br/><br/>Updating... Please Wait');
}

function addCloseButton(){
	$(".inneroverlay").after('<div class="closebutton">Close Preview</div>');
}
