<?php $this->load->view('includes/header');?>
<div id="checkout-page" class="container-960 secondary-page-wrapper">
    <div class="secondary-page-header">
        <h1>Thank You!<div class="page-tagline">We have received your order and will begin processing it shortly.</div></h1>
    </div>
    <div class="secondary-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="heading">ORDER CONFIRMATION NUMBER: <span id="order-number"><?php echo $order_id;?></span></div>
                
                <?php
                $ship = $customer['ship_address'];
                $bill = $customer['bill_address'];
                ?>

                <div class="order-address">
                        <div class="order-headings"><?php echo ($ship != $bill)? 'Shipping Information' : 'Shipping And Billing';?></div>
                        <?php echo format_address($ship, TRUE);?><br/>
                        <?php echo $bill['email'];?><br/>
                        <?php echo $bill['phone'];?>
                </div>
                <?php if($ship != $bill):?>
                <div class="order-address">
                        <div class="order-headings">Billing Information</div>
                        <?php echo format_address($bill, TRUE);?><br/>
                        <?php echo $bill['email'];?><br/>
                        <?php echo $bill['phone'];?>
                </div>
                <?php endif;?>
            </div>
        </div>
        
        
        
        <div class="row">
            <div class="col-sm-12">
                <?php if($shipping['method']!=''){?>
                <div class="order-address">
                        <div class="order-headings"> Shipping Method</div>
                        <?php echo $shipping['method']; ?>
                </div>
                <?php } ?>
                <div class="order-address">
                        <div class="order-headings">Payment Information</div>
                        <?php echo $payment['description']; ?>
                </div>
                
                
                
                
                <table class="table table-bordered table-striped order-details">
                                            <thead>
                                                <tr class="border-line"><td colspan="6">&nbsp;</td></tr>
                                                <tr class="border-line">
                                                    <th style="width:10%;">UPC</th>
                                                    <th style="width:30%;">NAME</th>
                                                    <th style="width:10%;">PRICE</th>								
                                                    <th style="width:30%;">DESCRIPTION</th>
                                                    <th style="width:10%;">QUANTITY</th>
                                                    <th style="width:8%;">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $subtotal = 0;
                                            foreach ($go_cart['contents'] as $cartkey=>$product):?>
                                                <tr class="order-details-summary">
                                                    <td valign="top"><?php echo $product['upc'];?></td>
                                                    <td valign="top"><?php echo $product['name']; ?></td>
                                                    <td valign="top"><?php echo format_currency($product['price']);   ?></td>
								
                                                        <td valign="top">
                                                            <?php if(isset($product['attribute_name'])){?>
                                                                <div class="product-descriptions">	
                                                                    <?php //echo substr($product['description'],0,150); ?>
                                                                    <div class="attribute">
                                                                        <div class="attribute-name">
                                                                            <?php 
//                                                                                                $attributeData = explode(',',$product['item_attribute_id']); 
//													$attributeTitle = getAttributeValue($attributeData);
//														foreach($attributeTitle as $attribute)
                                                                                echo '<div>'.$product['attribute_name'].":</div>";
                                                                            ?>
                                                                        </div>
                                                                        <div class="attribute-value">
                                                                            <?php 
//													$attributeValue  = explode(',',$product['attribute_value']);
//													foreach($attributeValue as $attributeVal)
                                                                                echo '<div>'.$product['attribute_value']."</div>";
                                                                            ?>	
                                                                        </div>
                                                                    </div>							
                                                                </div>
                                                            <?php } ?>
							</td>	
                                                        <td valign="top"><?php echo $product['quantity'];?></td>
                                                        <td valign="top"><?php echo format_currency($product['price']*$product['quantity']); ?></td>
                                                    </tr>
						<?php endforeach; ?>
                                                <tr class="border-line"><td colspan="6">&nbsp;</td></tr>
                                            </tbody>
						
                                            <tfoot>	
                                                <tr>
                                                    <td colspan="5" class="order-summary"><strong>Subtotal</strong></td>
                                                    <td class="order-summary"><?php echo format_currency($go_cart['subtotal']); ?></td>
                                                </tr>
							
                                                <?php if($go_cart['coupon_discount'] > 0)  : ?> 
                                                    <tr>
                                                            <td colspan="5" class="order-summary"><strong>Coupon Discount</strong></td>
                                                            <td class="order-summary"><?php echo format_currency($go_cart['coupon_discount']); ?></td>
                                                    </tr>
                                                <?php endif; 
                                                if($go_cart['order_tax'] > 0) :  ?>
                                                    <tr>
                                                        <td colspan="5" class="order-summary"><strong>Tax</strong></td>
                                                        <td class="order-summary"><?php echo format_currency($go_cart['order_tax']);?></td>
                                                    </tr>
                                                <?php endif; 
                                                // Show shipping cost if added after taxes
                                                if($go_cart['shipping_cost']>0) : ?>
                                                    <tr>
                                                        <td colspan="5" class="order-summary"><strong>Shipping</strong></td>
                                                        <td class="order-summary"><?php echo format_currency($go_cart['shipping_cost']); ?></td>
                                                    </tr>
                                                <?php endif;?>
                                                <tr> 
                                                    <td colspan="5" class="order-summary"><strong>Grand Total</strong></td>
                                                    <td class="order-summary"><?php echo format_currency($go_cart['total']); ?></td>
                                                </tr>
                                            </tfoot>
					</table>
                
                
                
                
            </div>
        </div>
        
    </div>
				
					

					
</div>	
<!-- content area ends-->
<?php $this->load->view('includes/footer');?>