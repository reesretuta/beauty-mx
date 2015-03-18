<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link href="/media/css/styles.css" type="text/css" rel="stylesheet"/>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/smoothness/jquery-ui.css" type="text/css" />
	<link href="<?php echo ROOTPATH;?>media/css/default_cms.css" type="text/css" rel="stylesheet"/>
	<link href="<?php echo ROOTPATH;?>media/css/fileuploader.css" type="text/css" rel="stylesheet"/>	
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery-1.5.1.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery-ui-1.8.10.min.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/common.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/fileuploader.js"></script>
	<script src="<?php echo ROOTPATH;?>media/js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.highlight-3.js"></script>
	<script type="text/javascript" src="<?php echo ROOTPATH;?>media/js/jquery.qtip.pack.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        
        
        <!--   jcrop files     -->
        <link href="/media/css/jquery.Jcrop.css" type="text/css" rel="stylesheet"/>
	<script type="text/javascript" src="/media/js/jquery.Jcrop.min.js"></script>
	
	<script type="text/javascript">  
            
            $(function (){
                if($( "#image_crop_target" ).hasClass( "image_to_crop" ))
                    $('#image_crop_target img').Jcrop({
                            boxWidth: 800, 
                            boxHeight: 600,
                            onChange: showPreview,
                            onSelect: showPreview
                    });
            });
            
            function showPreview(c)
            {
                    var w = Math.round(c.w);
                    var h = Math.round(c.h);Math.cei
                    var ra = Math.round(w/h * 100) / 100;
                    $('#w').val(w);
                    $('#h').val(h);
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    
                    function gcd (a, b) {
                        return (b == 0) ? a : gcd (b, a%b);
                    }
                    
                    var r = gcd (w, h);
                    
                    $('#r').val(ra);
            }
        
            function checkCoords()
            {
                    if (parseInt(jQuery('#w').val())>0) return true;
                    alert('Please select a crop region then press submit.');
                    return false;
            };
            
            
                  
	function createUploader(){ 
		$(".file_uploader").each(function (e){
			var uploader = new qq.FileUploader({
	    		element: $('.file_uploader')[e],
	            action: '/media/uploader.php',
	            debug: true,
	           	allowedExtensions: ['png', 'jpg', 'gif', 'jpeg', 'pdf', 'doc', 'docx'], 
	           	sizeLimit: 10 * 1024 * 1024,
	            minSizeLimit: 10,
	            multiple:true,
	            showMessage:function(message){ $(".uploadstatus").html(message); },
	            onComplete:function(id, fileName, response){
		            $(".file_uploaded").eq(e).parents().find('form').prepend('<input type="hidden" name="_unlink[]" value="'+$(".file_uploaded").eq(e).val()+'"/>');
	            	$(".file_uploaded").eq(e).val('/media/'+response.filename);
	            	
	            	$(".file_uploader").eq(e).find(".qq-upload-success").not(":last").remove();
	            	$f = response.filename;
					
					var ext = $f.split('.').pop();

					if(ext.toLowerCase() == 'pdf')
					{
	            		$(".uploaded_image").eq(e).attr("src", '/media/imagecache.php?width=150&height=150&image=/media/images/default_pdf.jpg');
					}
					else if(ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx')
					{
	            		$(".uploaded_image").eq(e).attr("src", '/media/imagecache.php?width=150&height=150&image=/media/images/default_doc.png');
					}
					else
					{
	            		$(".uploaded_image").eq(e).attr("src", '/media/imagecache.php?width=200&height=200&image=/media/'+response.filename);
					}
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

