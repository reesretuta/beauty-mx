<div class="imagebrowser_search documentbrowser_search">
	<form method="post" action="<?=current_url()?>?<?=$_SERVER['QUERY_STRING']?>">Search for a document by title: <input class="search" name="search" type="text" size="40" value="<?=@$_POST['search']?>"/> <span class="button"><input type="submit" value="Filter Documents..."/></span></form>
	<br/><br/>
	Choose a document below or 
	<span style="display:none;">
		<input type="text" class="file_uploaded"/>
	</span>
	<img class="uploaded_image" style="margin-bottom:30px; display:none" src=""/>
	<div class="file_uploader" style="margin-left:195px; margin-top:-25px;"></div>
</div>

<div class="imagebrowser browserview">
	<? foreach ($images as $image):?>
		<? if($image->image_path):?>
		    <? $path = $image->image_path; $ext = strtolower(strrev(substr(strrev($path),0,strpos(strrev($path),'.'))));?>
		    <p>
		        <?if($ext=='pdf'):?>
		          <i class="fa fa-file-pdf-o"></i>
		        <?elseif($ext=='doc' || $ext=='docx'):?>
		          <i class="fa fa-file-word-o"></i>
		        <?elseif($ext=='png' || $ext=='jpg' || $ext=='gif'):?>
		          <i class="fa fa-file-image-o"></i>
		        <?else:?>
		          <i class="fa fa-file-o"></i>
		        <?endif;?>
		        <span class="imagepick caption" rel="<?=$image->image_path?>"><?=$image->caption?></span>
	        </p>
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
		$caption=prompt('Please enter title for this document:');

		//add this image to the media table
		$.ajax({
			url:'<?=current_url();?>',
			type:'POST',
			data:'search=none&file_type=document&regUploader=<?=$_GET['regUploader'];?>&image='+$url+'&caption='+$caption,
			success:function(){
			    window.close();
			    },
			error:function(e){console.log(e);console.log("There was an error while saving the document to the media portion of the CMS.");}
		});
		var ext = $url.split('.').pop();
		$pathUrl = $url;
		
		<? if(isset($regUpload)):?>
    		window.opener.$("input[name=<?=$regUpload?>]").val($pathUrl);
    		<?if($regUpload=='pdf_path'):?>
    		  window.opener.$("input[name=pdf_link]").val($pathUrl);
    		  window.opener.$("input[name=pdf_path]").val($pathUrl);
    		  window.opener.$(".pdf_uploaded").attr('href', $pathUrl);
    		<?elseif($regUpload=='doc_path'):?>
    		  window.opener.$("input[name=doc_link]").val($pathUrl);
    		  window.opener.$(".doc_uploaded").attr('href', $pathUrl);
    		<?endif;?>
    		window.opener.$(".has-doc").show();
    		window.opener.$("img#<?=$regUpload?>").after('<span class="has-doc">Document: <a href="'+$url+'">'+$caption+'</a>&nbsp;<button type="button" class="reset-form">Remove Document</button><br/></span>');
    		window.opener.$("img#<?=$regUpload?>").fadeIn();
		<? else:?>
    		window.opener.CKEDITOR.tools.callFunction('<?=$funcnum?>', $url);
		<? endif;?>
		
	});
	
	$(".imagepick").click(function(){

		$url=$(this).attr('rel');
		$caption = $(this).parent().find('.caption').text();
		$pdfUrl = $url;
		var ext = $url.split('.').pop();
		if(ext.toLowerCase() == 'pdf')
		{
			//$url = '/media/images/default_pdf.jpg';
		}
		else if(ext.toLowerCase() == 'doc' || ext.toLowerCase() == 'docx')
		{
			$url = '/media/images/default_doc.png';
		}
		<? if(isset($regUpload)):?>
		window.opener.$("input[name=<?=$regUpload?>]").val($pdfUrl);
		window.opener.$("img#<?=$regUpload?>").fadeIn();
		
		
            <?if($regUpload=='pdf_path'):?>
              window.opener.$("input[name=pdf_link]").val($pdfUrl);
              window.opener.$(".pdf_uploaded").attr('href', $pdfUrl);
            <?elseif($regUpload=='doc_path'):?>
              window.opener.$("input[name=doc_link]").val($pdfUrl);
              window.opener.$(".doc_uploaded").attr('href', $pdfUrl);
            <?endif;?>
            window.opener.$(".has-doc").remove();
            window.opener.$("img#<?=$regUpload?>").after('<span class="has-doc">Document: <a href="'+$pdfUrl+'">'+$caption+'</a>&nbsp;<button type="button" class="reset-form">Remove Document</button><br/></span>');
            window.opener.$("img#<?=$regUpload?>").fadeIn();		
		
		
		<? else:?>
		window.opener.CKEDITOR.tools.callFunction('<?=$funcnum?>', $url);
		<? endif;?>
		
		window.close();
	});
});
</script>

