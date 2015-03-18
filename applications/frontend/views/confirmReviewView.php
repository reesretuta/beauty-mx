<?php $this->load->view('includes/header');?>
<script src="<?php echo ROOTPATH; ?>/media/js/front/jquery.validate.js"></script>
<script type="text/javascript">
/*validation for checkout process final step*/
 jQuery(document).ready(function(){
jQuery("#checkOut").validate({
	rules: {
			termsCondition:"required",			
		 },
	messages: {
				termsCondition: "Please agree terms and conditions",
		}
	});
 })
</script> 


<div id="checkout-page" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <div id="secondry-header" class="fixednav clearfix">
            <div class="checkout-status clearfix">
                <a href="#" class="progress">CHECK OUT</a>
                <a href="#" class=" progress active">REVIEW ORDER</a>
                <a href="#" class=" progress">FINISH &amp; PAY</a>
            </div>
        </div> 
        <h1>Review & Confirm Order<div class="page-tagline">Clicking " Finish & Pay " will charge your payment option in the amount shown below.</div></h1>
    </div>
    <div class="secondary-content">
        <div class="row">
            <div class="col-sm-12">
                <form name="checkOut" id="checkOut" method="post" action="<?php echo site_url('checkout/confirm_order');?>">
                    <div><?php echo $this->message->display();?></div>
                    <?php  if($this->session->flashdata('error')!='') {?>
			<div class="message-container-div">	
                            <div id="divMessage" class="message">
                                <div class="item-image item-image-error"></div>
                                <div class="item-close"></div>
                                <p><?php echo $this->session->flashdata('error');  ?></p>
                            </div>	
			</div>				
			<?php }?>	
			<div id="checkout-separator" class="clear"></div>
			<input type="hidden" name="vspacer" id="vspacer" value="" />
                            <div class="addressForm">
                                <div class="review">	
                                    <div id="review-order">
                                        <div class="heading" id="orders-heading" style="float:left;font-size: 24px; padding-bottom: 0px; ">Order Details</div>	
                                        <div class="heading" id="billing" style="font-size: 24px; padding-bottom: 0px; ">Billing To:</div>
                                        <div class="clear"></div>
                                    </div>
                                    <div id="order-details">
                                        <div class="border-line"></div>	
                                        <?php $cartProduct = $this->go_cart->contents(); if(!empty($cartProduct)){?>								
                                        <?php foreach ($this->go_cart->contents() as $cartkey=>$product){ ?>
                                            <div>                                  
                                                <div class="cart-sku">
                                                    <?php echo '<div>Qty: '.$product['quantity']."</div>"; ?>
                                                </div>
                                                <div class="cart-prod-desc">
                                                    <div><?php echo $product['name']; ?></div>
                                                    <?php if(isset($product['attribute_name'])) { ?>
                                                        <div class="attribute">
                                                            <div class="attribute-name">
                                                                <?php //$attributeData = explode(',',$product['item_attribute_id']); 
//															$attributeTitle = getAttributeValue($attributeData);
//																foreach($attributeTitle as $attribute)
                                                                echo '<div>'.$product['attribute_name'].":</div>";
                                                                ?>
                                                            </div>
                                                            <div class="attribute-value">
                                                                <?php 
//															$attributeValue  = explode(',',$product['attribute_value']);
//															foreach($attributeValue as $attributeVal)
                                                                    echo '<div>'.$product['attribute_value']."</div>";
                                                                ?>	
                                                            </div>
                                                        </div>	
                                                        <?php } ?>
                                                </div>
                                                <div class="cart-prod-price"><?php echo format_currency($product['subtotal']);?></div>
                                                    <?php
                                                    if(!empty($product['gift_certificate_value'])){
                                                        echo '<div style="clear:both;">';
                                                     foreach($product['gift_certificate_value'] as $giftItems){	
                                                                          if(!empty($giftItems['to']))  echo 'To: '.$giftItems['to'];                                                                                                                           if(!empty($giftItems['from']))  echo ', From: '.$giftItems['from'];                                                                                                                   if(!empty($giftItems['message']))  echo ', Message: '.$giftItems['message'];
                                                            }
                                                        echo '</div>';
                                                    }
                                                    ?>
                                            </div>
                                            <div class="clear"></div>
                                        <?php }?>
                                        <?php }?>
                                        <div class="heading" id="orders-heading" style="font-size: 24px; padding-bottom: 0px; font-weight: normal;">Address</div>
                                        <div class="border-line"></div>
                                        <div class="customer-address" style="float: left; padding-right: 30px; padding-left: 0; font-weight: normal;">
                                            <div id="name">Shipping</div>
                                            <div><?php echo $ship_address['name']; ?></div>
                                            <div id="address-review"><?php echo $ship_address['address']; ?></div>
                                            <div id="state-country"><span><?php echo $ship_address['state']; ?></span><span><?php echo 'USA'; ?></span><span><?php echo $ship_address['zip']; ?></span></div>
                                        </div>
                                                                           
                                        <div class="customer-address" style="font-weight: normal;">
                                            <div id="name">Billing</div>
                                            <div><?php echo $bill_address['name']; ?></div>
                                            <div id="address-review"><?php echo $bill_address['address']; ?></div>
                                            <div id="state-country"><span><?php echo $bill_address['state']; ?></span><span><?php echo 'USA'; ?></span><span><?php echo $bill_address['zip']; ?></span></div>
                                        </div>
                                    </div>	
					
                                    <div class="confirm-address">
                                        <div id="edit-cart"><a href="<?php echo site_url('checkout/#payment-option');?>">edit card</a></div>
                                        <div class="customer-address">
                                            <div id="name"><?php echo $bill_address['name']; ?></div>
                                            <div id="address-review"><?php echo $cardNumber; ?></div>
                                            <div id="state-country"><span>Exp. <?php echo $expiration; ?></span></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>	
                                <div id="calculated-amount"> 
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
																		
                                    <div class="order-tax">
                                    <?php if($this->go_cart->order_tax() > 0) {?>
                                            <span>Tax:</span>
                                            <span><?php echo format_currency($this->go_cart->order_tax());?></span>
                                    <?php }?>	
                                    </div>
                                    <?php if($this->go_cart->shipping_cost() > 0){?>
                                    <div class="shipping-charge">
                                            <span>Shipping Charges:</span>
                                            <span id="shippQuote">
                                                    <?php 
                                                     $shippingInfo = $this->go_cart->shipping_method(); 
                                                    if(!empty($shippingInfo)) 
                                                            $ship = $shippingInfo['method'].'_'.$shippingInfo['price'];
                                                     $shipVal =0;
                                                     if($this->go_cart->shipping_cost()>0)
                                                            $shipVal = format_currency($this->go_cart->shipping_cost());
                                                     else
                                                            $shipVal = '0.00';

                                                     echo $shipVal;
                                                    ?>
                                                    <input type="hidden" name="shippingCost" id="shippingCost" value="<?php echo isset($shipVal)? $shipVal:'0';?>">
                                                    <input type="hidden" name="shipp" id="shipp" value="<?php echo $ship;?>">
                                            </span>
                                    </div>
                                    <?php }?>
                                    <div class="grand-total">
                                        <span>Grand Total:</span>
                                        <span id="gtotal"><?php echo format_currency($this->go_cart->total()); ?></span>
                                    </div>	
                                </div>	
                                <div class="clear"></div>
                                <div class="border-line"></div>	
                                <div id="terms-condition">
                                    <div id="terms">
<!--											<div id="text-terms"> I accept terms and conditions.</div>-->
                                        <div id="submit-button"><input type="submit" name="confirmOrder" class="confirm buttons" id="confirmOrder" value="Finish & Pay"></div>
                                        <div id="confirm-box" style="display:none;"><input checked="checked" type="checkbox" name="termsCondition" id="termsCondition" value="1"></div>
                                        <input type="hidden" name="payment_method" id="payment_method" value="authorize_net">
                                    </div>
                                </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer');?>  