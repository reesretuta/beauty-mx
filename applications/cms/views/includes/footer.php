<p style="display: none;">rendered in {elapsed_time}s</p>
<?php  if(($e=$this->session->userdata('user')) &&(!isset($user_bar))):?>
	
	<div id="stickyFeet">
		<div class="dashboard_container">
			<div class="powered_by" style="position: absolute; width:200px; right:0; margin-top:-34px; margin-right:25px;">
				<a target="_blank" href="http://www.lavisual.com">CMS powered by La Visual <img src="/media/images/icons/LaVisual_bubble.png" alt="lavisual exclamation"/></a>
			</div>
			<div class="dashboard_tab" tip="click to show/hide dashboard"><span class="inner_dash">Dashboard</span></div>
			<div class="dashboard">
				<span style="display: block; background: #FFF; height:3px; font-size:1px;">&nbsp;</span>
				<div class="dashboard_contents" style="padding:10px;">
					<a class="img dashboard_logo" href="<?=base_url()?>"><img src="/media/images/logo.png"/></a>
			
	<div class="user_bar">
		You are logged in as: <?=$e['email'];?>
		<? //echo '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.base_url().'logout">Logout</a>';?>
	</div>
					<div class="dashboard_icons">
						<a class="home" href="<?=base_url()?>" rel="DASHBOARD">&nbsp;</a>
						<a class="go_to_site" href="/" rel="GO TO SITE">&nbsp;</a>
						<a class="help" rel="HELP">&nbsp;</a>
						<a class="logout" href="<?=base_url().'logout'?>" rel="LOGOUT">&nbsp;</a>
					</div>
					<br clear="all"/>
				</div>
			</div>
		</div>
	</div>
<?php endif;?>
	
	<div class="_image_preloader">
		<img src="<?php echo ROOTPATH;?>media/images/admin/loading_small.gif"/>
		<img src="<?php echo ROOTPATH;?>media/images/admin/overlaybg_white.png"/>
		<img src="<?php echo ROOTPATH;?>media/images/image_upload.gif"/>
		<img src="<?php echo ROOTPATH;?>media/images/loading_file.gif"/>
		<img src="<?php echo ROOTPATH;?>media/images/loading_small.gif"/>
		<img src="<?php echo ROOTPATH;?>media/images/loading.gif"/>
	</div>
	
<!-- wysiwyg -->

<script type="text/javascript">
$(function (){

	$("#stickyFeet").stickyFeet();
	$('input').customInput();


	$("div.dashboard_tab").qtip({
		content:{attr:"tip"},
		position:{my:'bottom center', at:'right right'},
		hide:'click'
		});
	
	$("div.dashboard_tab").click(function(){
		if(!$(".dashboard").is(":visible")){
			$(this).css({'height':29});
			$(".dashboard").slideDown();
			$('html, body').animate({ scrollTop: 60000 }, 'slow');
		}
		else{
			$(".dashboard").slideUp(function(){
				$("div.dashboard_tab").css({'position':'absolute', 'margin-top':-32, 'height':33});
			});
		}

	});

	$(".file_uploader_redux").click(function(){
		window.open("/cms/tools/browser?regUploader="+$(this).attr('ffield'), '', 'width=850, height=800');
	});
        

	$(".file_cropper").live('click',function(){
		window.open("/cms/tools/cropper?regUploader="+$(".file_uploader_redux").attr('ffield')+"&image_src="+$(".file_uploaded").val(), '', 'width=800, height=650');
	});
	
	CKEDITOR.replace('content', {
                extraPlugins:'maximize',
	toolbar:
		[
			['Bold', 'Italic', 'Underline', 'Strike', 
			 '-', 'NumberedList', 'BulletedList','Outdent','Indent','Blockquote', 'JustifyLeft', 'JustifyCenter','JustifyRight',
			 '-', 'Link','Unlink','Anchor',
			 '-','Cut','Copy','Paste','PasteText','PasteFromWord', 
			 '-', 'Image', 'Iframe', 'Table', 'HorizontalRule',
			 '-', 'Maximize', '-','Source', 'Styles', 'Format', 'FontSize', 'TextColor', '-', 'Save']
		],
	filebrowserImageBrowseUrl:'<?=base_url()?>browser'
	});
});
</script>	
	
	
</body>

</html>
