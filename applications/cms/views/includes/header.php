<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>CMS <?php echo (isset($page_title)&&$page_title)?(' :: '.$page_title):''?></title>
	<link href="/media/css/styles.css" type="text/css" rel="stylesheet"/>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/smoothness/jquery-ui.css" type="text/css" />
	<link href="<?php echo ROOTPATH;?>media/css/default_cms.css" type="text/css" rel="stylesheet"/>
	<link href="<?php echo ROOTPATH;?>media/css/fileuploader.css" type="text/css" rel="stylesheet"/>	
	<link href="<?php echo ROOTPATH;?>media/css/jquery.qtip.min.css" type="text/css" rel="stylesheet"/>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo ROOTPATH;?>media/css/jquery.uix.multiselect.css" type="text/css" rel="stylesheet"/>
	<link href="<?php echo ROOTPATH;?>media/css/jquery.fancybox.css" type="text/css" rel="stylesheet"/>
	
        <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
        
        <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
	<!--<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery-1.6.2.min.js"></script>-->
	<!--<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery-ui-1.8.10.min.js"></script>-->
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/common.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/fileuploader.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.stickyFeet.js"></script>
	<script src="<?php echo ROOTPATH;?>media/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.qtip.pack.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/customInput.jquery.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.hoverIntent.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.reOrder.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.uix.multiselect.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.fancybox.js"></script>
	<script type="text/javascript">        
	function createUploader(){ 
		$(".file_uploader").each(function (e){
			var uploader = new qq.FileUploader({
	    		element: $('.file_uploader')[e],
	            action: '/media/uploader.php',
	            debug: true,
	           	allowedExtensions: ['png', 'jpg', 'gif', 'jpeg'], 
	           	sizeLimit: 10 * 1024 * 1024,
	            minSizeLimit: 10,
	            multiple:false,
	            showMessage:function(message){ $(".uploadstatus").html(message); },
	            onComplete:function(id, fileName, response){
		            $(".file_uploaded").eq(e).parents().find('form').prepend('<input type="hidden" name="_unlink[]" value="'+$(".file_uploaded").eq(e).val()+'"/>');
	            	$(".file_uploaded").eq(e).val('/media/'+response.filename);
	            	
	            	$(".file_uploader").eq(e).find(".qq-upload-success").not(":last").remove();
	            	$(".uploaded_image").eq(e).attr("src", '<?php echo ROOTPATH?>media/imagecache.php?width=200&height=200&image=/media/'+response.filename);
	            	$(".uploaded_image").eq(e).fadeIn(1000);
	            	$(".file_uploader").eq(e).find("a.qq-upload-cancel").addClass('removeUploadedImage');
	            	$(".file_uploader").eq(e).find('.removeUploadedImage').click(function (){
	            		$(this).removeClass('.qq-upload-cancel');
						$(".file_uploader").eq(e).find(".qq-upload-success").fadeOut();
						$(".uploaded_image").eq(e).attr("src", "");
						$(".uploaded_image").eq(e).slideUp();
	            	});
	            }
	       });
		});
	}
	window.onload = createUploader;
	</script> 
</head>

<body>

