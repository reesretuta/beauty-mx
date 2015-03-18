<?php $this->load->view('includes/header');?>
<script type="text/javascript">
/*ajax call for shipping quote based on zip code*/
 jQuery(document).ready(function($){ 
	jQuery('#calculateShipping').click(function(){
	value=jQuery('#shippingZipCode').val(); 
	if(value=='Zip/Postal Code' || value==''){
		jQuery('#shippingZipCode').addClass('validation-error');
		if (jQuery(".calculate-shipping .error").length){ 
				jQuery(".error").remove();
			}
		jQuery('.calculate-shipping').append('<div class="error">Please enter zip or postal code.</div>');
		return false;
	}else{
		jQuery.ajax({
		  url: "<?php echo base_url();?>cart/getShippingQuote/?zipCode="+value,
		  type:'GET',
		  cache:false,
		  success: function(data) { alert(data);
			var content = "<table cellpadding='2' cellspacing='2'>";
			jQuery.each(jQuery.parseJSON(data), function(k1, v1) {
				var va = k1+'_'+v1;				
				content +='<tr><td><input type="radio" class="shippingValue" value="'+va+'" name="shipping_method"/></td><td class="shipping-type">'+k1+'</td><td><b>$'+v1.toFixed(2)+'</b></td></tr>';
			});
			content += "</table>";
			$('#shippingQuoteResult').html(content);
		  }
		});
	 }	
   });
	
});
</script>

<div id="cart-page" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <h1>Shopping Cart</h1>
    </div>
    <div class="secondary-content">
        <?php $cartProduct = $this->go_cart->contents(); if (!empty($cartProduct)):?>
            <form name="cartForm" id="cartForm" method="post" action="<?php echo site_url('cart/updateCart');?>">
            <?php foreach ($this->go_cart->contents() as $cartkey=>$product): ?>
                    <div class="row">
                        <div class="col-sm-3 col-xs-12 cart-image">
                            <?php 
                            if(isset($product['path'])){
                                $productImage = $product['path'];
                            ?>
                            <img src="<?php echo ROOTPATH.IMAGE_CACHE.ROOTPATH.$productImage; ?>&width=340&cropratio=1:1" class="cart-image-view"  />
                            <?php }?>                            
                        </div>
                        
                        <div class="visible-xs clearfix">&nbsp;</div>
                        
                        <div class="col-xs-12 col-sm-6 cart-description">
                            <h3><?php echo $product['name']; ?></h3>
                            <div class="attribute">
                                                                
                                <div class="attribute-name hidden-xs">
                                    Price: 
                                </div>
                                <div class="attribute-value hidden-xs" style="padding-bottom: 0;">
                                     $<?php echo $product['price'];?>                                                                     
                                </div>
                                <div class="clear"></div>
                                </div>
                                                                
                                <?php if(isset($product['attribute_value_id'])):?>
                                <div class="attribute">
                                    <div class="attribute-name">
                                        <?php echo '<div>'.$product['attribute_name'].":</div>";?>
                                    </div>
                                    <div class="attribute-value">
                                         <?php echo '<div>'.$product['attribute_value']."</div>";?>  
                                    </div>
                                </div>  
                             <?php endif; ?>
                        </div>
                        
                        <div class="col-sm-3 cart-options">
                            <div class="price visible-xs">
                                <div class="text">Price:</div>
                                <div class="price-value">$<?php echo $product['price'];?> </div>
                            </div>
                            <div class="price">
                                <div class="text">Total:</div>
                                <div class="price-value"> $<?php echo $product['subtotal'];?></div>
                            </div>
                            <div class="qty clearfix">
                                <div class="float-left text">Quantity:</div>
                                <div class="float-left">
                                    <input type="text" name="cartkey[<?php echo $cartkey;?>]" id="quantity" size="3" value="<?php echo $product['quantity'];?>">
                                </div>
                            </div>
                            <div class="remove clearfix">
                                <a href="#" onclick="if(confirm('Are you sure to remove product from cart')){window.location='<?php echo site_url('cart/removeItem/'.$cartkey);?>';}">remove</a>
                            </div>                            
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="row">
                    <div class="cart-option">
                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="promoCode-div">
                                            <a href="#" id="promoCode">Enter Promo Code </a>
                                        </div>                                        
                                    </div>
                                    <div class="col-sm-6">
                                        <div id="promo-code-field" style="display: none;">
                                            <input type="text" name="couponCode" id="couponCode" value="">
                                            <input type="submit" name="addCouponCode" id="addCouponCode" value="Apply Coupon Code">
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-5 col-sm-offset-2 text-right float-right">
                                <div class="cart-option-details">
                                    <div class="calculate-shipping">Calculate Shipping :
                                        <input type="text" name="shippingZipCode" id="shippingZipCode" value="Zip/Postal Code" onfocus="if (this.value=='Zip/Postal Code') this.value='';" onblur="if (this.value=='') this.value='Zip/Postal Code';" style="color:#939598">
                                    </div>
                                    <div class="calculate">
                                        <a href="#" id="calculateShipping">Calculate</a>
                                        <div id="shippingQuoteResult"></div>
                                    </div>
                                    <div class="shipping-options"></div>                                
                                    <div class="update-cart-total">
                                        <a href="#" onclick="jQuery.noConflict(); jQuery('#cartForm').trigger('submit');">Update Cart Total</a>
                                    </div>
                                    <div class="shipping-charge">
                                        <span>Total Charges:</span>
                                        <span><?php echo format_currency($this->go_cart->subtotal());?></span>
                                    </div>
                                    <?php if($this->go_cart->coupon_discount() > 0) {?>
                                    <div class="coupon-discount">
                                        <span>Coupon Discount:</span>
                                        <span><?php echo format_currency($this->go_cart->coupon_discount());?></span>
                                    </div>
                                    <?php }?>
                                    <?php if($this->go_cart->shipping_cost() > 0){?>
                                    <div class="shipping-charge">
                                        <span>Shipping Charges:</span>
                                        <span>
                                            <?php 
                                             $shipVal =0;
                                             $shipVal = format_currency($this->go_cart->shipping_cost());
                                             echo $shipVal;
                                            ?>
                                            <input type="hidden" name="shippingCost" id="shippingCost" value="<?php echo isset($shipVal)? $shipVal:'0';?>">
                                        </span>
                                    </div>
                                    <?php }?>
                                    <div class="grand-total">
                                        <span>Grand Total:</span>
                                        <span><?php echo format_currency($this->go_cart->total()); ?></span>
                                    </div>
                                    <div class="check-out">
                                        <input id="redirect_path" type="hidden" name="redirect" value=""/>
                                        <div class="float-left continue-shipping">
                                            <a href="<?php echo site_url('store');?>">Continue Shopping</a>
                                        </div>
                                        <div class="float-right">
                                            <input type="submit" name="checkOut" class="buttons" value="Check Out" id="checkOut" onclick="jQuery('#redirect_path').val('checkout');">
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                    </div>                    
                </div>                
            </form>
        <?else:?>
            <div class="row">
                <div class="col-sm-12">
                    <div id="empty-cart">Your cart is empty. Go to our <a href="<?php echo site_url('store'); ?>" class="link">products</a> page to begin shopping.</div>
                </div>
            </div>
        <?endif;?>
    </div>
</div>

<script type="text/javascript">
jQuery("#promoCode").click(function () {
jQuery("#promo-code-field").toggle();
});
</script>
<?php $this->load->view('includes/footer');?>