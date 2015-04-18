<div class="imagebrowser_search">
	<form method="post" action="<?=current_url()?>?<?=$_SERVER['QUERY_STRING']?>">Search for an image by caption: <input class="search" name="search" type="text" size="40" value="<?=@$_POST['search']?>"/> <span class="button"><input type="submit" value="Filter Images..."/></span></form>
	<br/><br/>
	Choose image below or 
	<span style="display:none;">
		<input type="text" class="file_uploaded"/>
	</span>
	<img class="uploaded_image" style="margin-bottom:30px; display:none" src=""/>
	<div class="file_uploader" style="margin-left:155px; margin-top:-25px;"></div>
</div>

<div class="imagebrowser">
	<? foreach ($images as $image):?>
		<? if($image->image_path):?>
			<div>
            	<?php 
					$path = $image->image_path;
					$ext = strrev(substr(strrev($path),0,strpos(strrev($path),'.')));
					if(strtolower($ext) == 'pdf')
					{	?>
						<img class="imagepick" rel="<?=$image->image_path?>" src="/media/imagecache.php?width=150&image=/media/images/default_pdf.jpg"/>
                        <?php 
					}
					else if(strtolower($ext) == 'doc' || strtolower($ext) == 'docx')
					{	?>
						<img class="imagepick" rel="<?=$image->image_path?>" src="/media/imagecache.php?width=150&image=/media/images/default_doc.png"/>
                        <?php 
					}
					else
					{	?>
						<img class="imagepick" rel="<?=$image->image_path?>" style="max-width: 150px; max-height: 150px; width: auto; height: auto; display: block" src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?=$image->image_path?>"/>
                	<?php
                	}
					?>
				<span class="caption"><?=$image->caption?></span>
			</div>
		<? endif;?>
	<? endforeach;?>
</div>

<script type="text/javascript">
//send the file back to the browser, then close this window, yo
$(function (){

<? if($searchterm):?>
	$(".imagebrowser").highlight('<?=$searchterm?>');
	$(".highlight").css({ backgroundColor: "#FFFF88", 'display':'inline' });
	<? endif;?>
	$(".imagepick").hover(
		function(){
			$(this).css({'opacity':0.5, 'cursor':'pointer'});
		},
		function(){
			$(this).css({'opacity':1, 'cursor':'auto'});
		}
	);
	
	$(".uploaded_image").load(function (){
		$url=$(".file_uploaded").val();
		$caption=prompt('Please enter caption for this image:');

		//add this image to the media table
		$.ajax({
			url:'<?=current_url();?>',
			type:'POST',
			data:'search=none&image='+$url+'&caption='+$caption,
			success:function() {
                console.log("successfully uploaded");
                var ext = $url.split('.').pop();
                $pathUrl = $url;
                if(ext.toLowerCase() == 'pdf')
                {
                    $url = '/media/files/default_pdf.jpg';
                }
                else if(ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx')
                {
                    $url = '/media/files/default_doc.png';
                }
                <? if(isset($regUpload)):?>
                console.log("pathUrl", $pathUrl, "url", $url);
                window.opener.$("input[name=<?=$regUpload?>]").val($pathUrl);
                window.opener.$("img#<?=$regUpload?>").attr('style', 'max-width: 200px; max-height: 200px; width: auto; height: auto; display: block');
                window.opener.$("img#<?=$regUpload?>").attr('src', 'http://jafra-mx.s3-website-us-west-1.amazonaws.com'+$url);
                window.opener.$("img#<?=$regUpload?>").fadeIn();
                <? else:?>
                window.opener.CKEDITOR.tools.callFunction('<?=$funcnum?>', $url);
                <? endif;?>

                window.close();
            },
			error:function(e){
                console.log("error", e);
                console.log("There was an error while saving the image to the media portion of the CMS.");
            }
		});
	});
	
	$(".imagepick").click(function(){

		$url=$(this).attr('rel');
		$pdfUrl = $url;
		var ext = $url.split('.').pop();
		if(ext.toLowerCase() == 'pdf')
		{
			$url = '/media/images/default_pdf.jpg';
		}
		else if(ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx')
		{
			$url = '/media/images/default_doc.png';
		}
		<? if(isset($regUpload)):?>
		window.opener.$("input[name=<?=$regUpload?>]").val($pdfUrl);
        window.opener.$("img#<?=$regUpload?>").attr('style', 'max-width: 200px; max-height: 200px; width: auto; height: auto; display: block');
        window.opener.$("img#<?=$regUpload?>").attr('src', 'http://jafra-mx.s3-website-us-west-1.amazonaws.com'+$url);
		window.opener.$("img#<?=$regUpload?>").fadeIn();
		<? else:?>
		window.opener.CKEDITOR.tools.callFunction('<?=$funcnum?>', $url);
		<? endif;?>
		
		window.close();
	});
});
</script>

