<?php 
/**************************************
 @Page Name       : productView
 @Author          : Edwin 
 @Date            : June 4,2013
 @Purpose         : to show product detail page
 ***************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************	
?>
<?php $this->load->view('includes/header');?>
<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.validate.js"></script>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-cefb96eb-300e-dc39-3e2e-eb96d4fe6390", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
 

<script type="text/javascript">
/*ajax call for product attribute value and quantity */
 jQuery(document).ready(function($){ 
	jQuery('#attributeOption').change(function(){
		value	=	jQuery(this).val(); 	
		if(value==''){
			jQuery('#attributeOption').addClass('validation-error');
			jQuery('#quantity').prop('disabled', 'disabled');				
			jQuery("input[type=submit]").attr("disabled", "disabled");
		}else{
			jQuery('#attributeOption').removeClass('validation-error');
			jQuery.ajax({
			  url: "<?php echo base_url();?>cart/validateQtyPrice/?attibute="+value,
			  type:'GET',
			  cache:false,
			  dataType:"JSON",
			  success: function(data) {
				if(data.qty> 0){ 
					jQuery('#quantity').prop('disabled', false);
					jQuery('.outOfStock').css('display','none');							
					jQuery("input[type=submit]").attr("disabled", false);
				}else{ 
					jQuery('#quantity').prop('disabled', 'disabled');				
					jQuery('.addtocart').css('display', 'none');
					jQuery('.outOfStock').css('display','block');
				}
				if(data.price >0){
					jQuery('#additonalPriceValue').html(data.price);
					jQuery('#attribAdditionalPrice').val(data.price);
				}else{
					jQuery('#additionalPrice').css('display','none');
				}
			  }
			});
		}	
	});	
/*validation for empty attribute value*/	
	jQuery("#productPage").submit(function(e){ 
	 if (jQuery("#attributeOptionId").val()!=0 && jQuery("#attributeOptionId").val()!= undefined){ 
		 var attributeOption = jQuery("#attributeOption").val();
		 var quantity = jQuery("#quantity").val();
		  if(attributeOption=='' || attributeOption == undefined ){
		  if (jQuery(".error").length){ 
				jQuery('.error').remove();
			}
			jQuery('#attributeOption').addClass('validation-error');
			jQuery('.attribute-option').append('<div class="error">Please select attribute value</div>');
			return false;			
		   }else{
		   jQuery('.attribute-option .error').remove();
		   }
		   if(quantity==0){
				jQuery('#quantity').addClass('validation-error');
				jQuery('#qtyDiv').append('<div class="error">Please add quantity</div>');
			return false;
		   }else{ 
			jQuery('.attribute-option .error').remove();
		   }
		 }else if(jQuery('#hidden-gift-certificate').val()!=undefined){	
			/* validation for multiple gift certificate value*/
			var j=1;  var quantity = jQuery("#quantity").val();
			 for(j=1; j <= quantity; j++){ 
				if(document.getElementById('toPerson-'+j).value==''){
					jQuery('#toPerson-'+j).addClass('validation-error');
					jQuery('#error-toPerson-'+j).text('Please enter value');
					document.getElementById('toPerson-'+j).focus();
					return false;
				}else if(document.getElementById('fromPerson-'+j).value==''){
					jQuery('#fromPerson-'+j).addClass('validation-error');
					jQuery('#error-fromPerson-'+j).text('Please enter value');
					document.getElementById('fromPerson-'+j).focus();
					return false;
				}/*else if(document.getElementById('textMessage-'+j).value==''){
					jQuery('#textMessage-'+j).addClass('validation-error');
					jQuery('#error-textMessage-'+j).text('Please enter value');
					document.getElementById('textMessage-'+j).focus();
					return false;
				}*/	
			 }		
				
		 }
				 
	  });
	  
	/* function for generate Dynamic form  */
		var template =  jQuery.validator.format(jQuery.trim(jQuery("#template").html()));
			function addRow() { 
				jQuery(template(i++)).appendTo("#gift-certificate-container");
			}
			var i = 1;
		// start with one row
			addRow();
		// add more rows on click
//		jQuery('#quantity').change(function(){	
//			var j = 1
//			value	=	jQuery(this).val();
//			 for(var j; j< value; j++ ){
//				addRow();
//			 }	
//		});


            var $cover = $('#detailGalleryCover');
//            var $zoom  = $('#detailGalleryZoom');
            var $zoom  = $('#galleryZoom');



            $('#detailGalleryThumbs a:first').addClass('active');

            $('#detailGalleryThumbs a').on('click', function(e) {
                var targetImageSrc = $(this).attr('data-zoom');
                var thumbImageSrc  = $(this).attr('data-cover');


                if(targetImageSrc.indexOf("iframe") > 0) {
                    targetImageSrc = urldecode(targetImageSrc);
                } else { 
                    targetImageSrc = '<img id="detailGalleryZoom" class="frame img-responsive" src="'+targetImageSrc+'">';
                }

                $cover.attr('src',thumbImageSrc);
//                                    $zoom.attr('src',targetImageSrc);
                $zoom.html(targetImageSrc);

                $('#detailGalleryThumbs div').find('a.active').removeClass('active');
                $(this).addClass('active');

                e.preventDefault();
            });
	 
  })
</script>

<div id="product-landing" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <h1>Online Store</h1>
        <div class="secondary-page-nav">
            <ul class="nav">
                <?php foreach($storeCategory as $category ){    ?>
                    <li><a href="<?php echo site_url('store/#'.$category->id); ?>" <?php if($product['productData']->category_id == $category->id) echo "class=active";?>><?php echo $category->category;?></a></li>
                <?php }?>
            </ul>
        </div>
    </div>
    <div class="secondary-content">
        <form name="productPage" id="productPage" method="post" action="<?php echo site_url('cart/addToCart');?>">
            <div class="row">
                <div class="col-sm-5">
                    
                    <!-- Start images gallery -->
                    <div class="detail-gallery">
                        <?php 
                        $image = '';
                        if(!empty($product['productData']->path))
                            $image = $product['productData']->path;
                        else
                            $image ='/media/images/front/featured-image-not-found.png';
                        ?>
                    
                    
                        <div class="detail-gallery-cover">
                            <a href="#" data-toggle="modal" data-target="#imageModal_<?php echo $product['productData']->id;?>">
                                <i class="fa fa-search detail-gallery-zoom-icon"></i>
                                <img id="detailGalleryCover" class="frame img-responsive" src="/media/imagecache.php?width=325&cropratio=4:3&image=<?php echo $image;?>">
                            </a>
                        </div>

                        <div class="modal fade" id="imageModal_<?php echo $product['productData']->id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <a href="#" class="modal-closer" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
                                        <div id="galleryZoom"><img id="detailGalleryZoom" class="frame img-responsive" src="/media/imagecache.php?width=1280&image=<?php echo $product['productData']->path;?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    
                        <div id="detailGalleryThumbs" class="detail-gallery-others row">
                            <?php 
                            // create image gallery
                            if(!empty($productGallery)) 
                            {
                                $index = 0;
                                foreach($productGallery as $galleryImage)
                                { 
                                    $gripImage 		= '';
                                    $gripYouTube 	= '';
                                    $gripThumbImage     = '';

                                    // check merchant have image or you tube video
                                    if( $galleryImage->video_embed_code  == '')
                                    {
                                        # RF2
                                        $gripImage 		= ROOTPATH.'/media/imagecache.php?width=720&height=540&cropratio=4:3&image='.$galleryImage->path;
                                        $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                        $gripThumbImage = '<img class="frame img-responsive" width="45" height="37" src="/media/imagecache.php?height=126&width=152&cropratio=45:37&image='.$galleryImage->path.'" alt=""/>';
                                    }
                                    else
                                    {
                                        $temp 			= stripslashes($galleryImage->youtube_embed_code);
                                        $tempArray  	= explode("embed/",$temp);
                                        $tempArray1 	= explode('"',$tempArray[1]);
                                        $gripYouTube 	= $galleryImage->youtube_embed_code;
                                        $gripCover		= ROOTPATH.'/media/imagecache.php?width=325&cropratio=4:3&image='.$galleryImage->path;
                                        // blank placeholder...
                                        // $gripImage 		= 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';
                                        $gripThumbImage = '<img src="/media/images/youtube-play.png" class="player-icon" /><img class="video-thumb frame img-responsive" src="http://img.youtube.com/vi/'.$tempArray1[0].'/0.jpg" height="37" width="45" class="player-image"  >';
                                    }
                                    ?>
                                    <div class="col-xs-3">
                                        <a href="#" class="detail-gallery-thumb" id="imageIndex_<?php echo $index ?>" data-cover="<?php echo $gripCover;?>" data-zoom="<?php echo $gripImage;?>">
                                                <?php echo $gripThumbImage;?>
                                        </a>
                                    </div><!-- /span -->
                                <?php 
                                        $index++;
                                    } 
                            } ?>
                        </div>
                    </div>                      
                                                            
                                                            
                                                     
                            <div id="sharethis" style="margin-left:6px;">
                                    <span class='st_facebook' displayText=' '></span>
                                    <span class='st_twitter' displayText=' '></span>
                                    <span class='st_linkedin' displayText=' '></span>
                                    <span class='st_pinterest' displayText=' '></span>
                                    <span class='st_email' displayText=' '></span>
                                </div>  
                        
                </div>
                <div class="col-sm-7">
                    <div class="product-description">
                            <h3><?php echo $product['productData']->name; ?></h3>
                            <div class="description">
                                <?php echo nl2br($product['productData']->description);?>
                            </div>
                            <?php if($product['productData']->category_id == '13'){?>
                                <input type="hidden" name="hidden-gift-certificate" id="hidden-gift-certificate" value="<?php echo $product['productData']->category_id;?>">
                                <div id="gift-certificate-container" style="clear:both;"></div>
                                <!--Template-->
                                    <div id="template" style="display:none;">
                                    <div>
                                        <label for="toPerson">To*:</label>
                                        <input type="text" name="toPerson-[0]" id="toPerson-[0]" value="" />
                                        <div id="error-toPerson-[0]" class="error"></div>       
                                    </div>
                                    <div>
                                        <label for="fromPerson">From*:</label>
                                        <input type="text" name="fromPerson-[0]" id="fromPerson-[0]" value="" />
                                        <div id="error-fromPerson-[0]" class="error"></div>     
                                    </div>
                                    <div>
                                        <label for="message">Message:</label>
                                        <textarea name="textMessage-[0]" class="textMessage" id="textMessage-[0]" maxlength="35"></textarea>
                                        <div id="error-textMessage-[0]" class="error"></div>        
                                    </div>
                                </div>
                                <!---->
                            <?php } ?>
                            <?php if(isset($productAttribute[0]->name)){ ?>
                                <div class="attribute-option" style="clear:both;">
                                <div class="attribute-name">                                                
                                        <?php 
                                             if(!empty($productAttribute)){
                                                foreach($productAttribute as $attributes){
                                                    $attributeArray[] =  $attributes->name;
                                                }
                                             
                                                $result =  '';
                                                $flag='';   
                                                $attributeVal = array_merge($attributeArray);   
                                                $attributeVal =     array_unique($attributeVal);                            
//                                              $attributeRes =  getAttributeValue($attributeVal);
//                                           foreach($attributeRes as $res){
//                                              $result = $result.$flag.$res->attribute_name;
//                                              $flag= '/';
//                                            }
                                                                                          // need to update for multiple attribute types BH 8-14-13
                                              echo $attributeVal[0];
                                            }
                                            ?>
                                    :</div> 
                                    <?php }else{?>
                                    <div class="attribute-option-text">     
                                    <?php }?>
                                        <div class="attribute-value-option">
                                            <?php if(isset($productAttribute[0]->name)){?>
                                              <select name="attributeOption" id="attributeOption" class="styled">
                                                <option value="">Please Select</option>
                                                     <?php                                                       
                                                      if(!empty($productAttribute)){
                                                       $attributePrice= '';
                                                        foreach($productAttribute as $attribute){ 
//                                                      $attributePrice = isset($attribute->extra_price) ? ' (+$'.$attribute->extra_price.')' :''; 
                                                        ?>
                                                        <option value="<?php echo $attribute->upc ?>"><?php echo $attribute->value.$attributePrice;?></option>
                                                        <?php }
                                                        } ?>
                                                </select>
                                                 <input type="hidden" name="attributeOptionId" id="attributeOptionId" value="<?php echo isset($productAttribute[0]->id) ? $productAttribute[0]->id  : 0;?>">
                                                <?php }else {?>
                                                 <input type="hidden" name="attributeOptionId" id="attributeOptionId" value="0">
                                                     <?php if($productAttribute[0]):?>
                                                     <input type="hidden" name="attributeOption" id="attributeOption" value="<?php echo $productAttribute[0]->upc; ?>" />
                                                     <?php endif;?>
                                                <?php  } ?>
                                            </div>  
                                            <div class="clearfix"></div>
                                </div>
                                <div class="attribute-container">
                                        <div id="price">
                                            <div class="attribute-name">Price:</div>
                                            <div class="attribute-value">
                                                                                            
                                                                                            <?php if($product['productData']->category_id == '-1') { ?>
                                                                                            
                                                                                                <select name="additonalPriceValue" id="additonalPriceValue" class="styled">
                                                                                                    <?php for($i=1; $i<41; $i++) {
                                                                                                        $amount = $i * 5;
                                                                                                        echo '<option>$'.$amount.'</option>';
                                                                                                    } ?>
                                                                                                </select>
                                                                                            
                                                                                            <?php } else { ?>
                                                                                            
                                                                                            
                                                <span id="additonalPriceValue">
                                                <?php //echo $product['productData']->price; ?>
                                                    <?php 
                                                    if($product['productData']->sale_price != 0) 
                                                    {
                                                        echo '<span class="strike-through">$'.number_format($product['productData']->price,2).'</span> ';
                                                        echo '<span class="sale-price">$'.number_format($product['productData']->sale_price,2).'</span>';
                                                    }
                                                    else 
                                                    {
                                                        echo '$'.number_format($product['productData']->price,2);
                                                    }
                                                    ?>
                                                    
                                                    
                                                </span>
                                                
                                            <?php } ?>
                                            </div>                                          
                                        </div>
                                        <div class="clearfix"></div>
                                       <div id="qtyDiv">
                                            <div class="attribute-name">Quantity:</div>
                                            <div class="attribute-value">
                                            <?php if($product['productData']->category_id != '-1'){?>
                                              <input type="text" name="quantity" id="quantity" size="6" value="1" class="quantity-textbox">
                                             <?php }else{ ?> 
                                                <select name="quantity" class=" styled" id="quantity">
                                                    <?php for($i=1;$i<=10;$i++){?>
                                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                                    <?php }?>
                                                </select>
                                             <?php }?>
                                            </div>      
                                            <div class="clearfix"></div>
                                      </div>
                                 </div>
                              </div>    
                            <div class="clearfix"></div> 
                            <?php if ($productAttribute[0] && $productAttribute[0]->quantity):?>                      
                                <div class="addtocart">
                                    <input type="hidden" name="attribAdditionalPrice" id="attribAdditionalPrice" value="0">
                                    <input type="hidden" name="productCategoryId" id="productCatergoryId" value="<?php echo $product['productData']->category_id; ?>">
                                    <input type="submit" name="addToCart" id="addToCart" value="ADD TO CART" class="buttons">
                                </div>
                            <?php else:?>                  
                                <div class="outOfStock">Out of Stock</div>
                            <?php endif;?>
                            
                          </div>
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $this->uri->segment(3);?>" />
        </form>
    </div>
</div>

<?php $this->load->view('includes/footer');?>