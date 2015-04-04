
                        
<div id="image_crop_target" class="image_to_crop"><img src="http://jafra-mx.s3-website-us-west-1.amazonaws.com<?php echo $image_src; ?>" /></div>

<div id="coordinates">
        <input type="hidden" id="x" name="x" />
	<input type="hidden" id="y" name="y" />
        <label>W <input type="text" size="4" id="w" name="w" /><?php if(isset($_POST['w'])) echo $_POST['w']; ?></label>
	<label>H <input type="text" size="4" id="h" name="h" /><?php if(isset($_POST['h'])) echo $_POST['h']; ?></label>
	<label>Ratio <input type="text" size="4" id="r" name="r" /></label>
        <input class="mainsubmit" type="submit" value="Crop" name="_continue" id="crop" style="font-size: 14px; color: rgb(0, 0, 0); background: url("/media/images/button_background.png") repeat scroll 0% 0% transparent; border-width: 1px 1px 2px; border-style: solid; border-color: rgb(153, 153, 153) rgb(153, 153, 153) rgb(187, 187, 187); -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; cursor: pointer; padding: 5px 20px; text-shadow: 1px 1px 0px rgb(204, 204, 204); border-radius: 5px;">
</div>

<div style="display: none;" id="new_image"><?php echo $image_db_name; ?></div>

<script type="text/javascript">
//send the file back to the browser, then close this window
$(function (){
    
    $('#crop').click(function() { 
        
        $coords = checkCoords();
        if(!$coords) return false;
        
        // create new cropped image and save to db
	$url='<?php echo $image_src; ?>';
        $caption=prompt('Please enter caption for this image:');
        $w=$('#w').val();
        $h=$('#h').val();
        $x=$('#x').val();
        $y=$('#y').val();
        $.ajax({
                url:'<?=current_url();?>',
                type:'GET',
                dataType:'html',
                data:'image='+$url+'&caption='+$caption+'&x='+$x+'&y='+$y+'&w='+$w+'&h='+$h+'&<?=$_SERVER['QUERY_STRING']?>',
               success:function(data){
                   $image_db_name = $(data).filter('#new_image').text();
                   window.opener.$("input[name=<?=$regUpload?>]").val($image_db_name);
                   window.opener.$("img#<?=$regUpload?>").attr('width', '200');
                   window.opener.$("img#<?=$regUpload?>").attr('height', '200');
                   window.opener.$("img#<?=$regUpload?>").attr('style', 'max-width: 100%; height: auto');
                   window.opener.$("img#<?=$regUpload?>").attr('src', 'http://jafra-mx.s3-website-us-west-1.amazonaws.com'+$image_db_name);
                   window.opener.$("img#<?=$regUpload?>").fadeIn();
                   window.close();
               },
// success:function(msg){alert(msg)},
                error:function(e){console.log(e);console.log("There was an error while saving the image to the media portion of the CMS.");}
        });
        
       
        
    });



});
</script>

