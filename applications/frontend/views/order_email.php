<?php 
/**************************************
 @Page Name       : order_email.php
 @Author          : Edwin 
 @Date            : June 4,2013
 @Purpose         : email template for order details (send as email)
 ***************************************/
 #Chronological development
 #***********************************************************************************
 #| Ref No  | Name    | Date        | Purpose
 #***********************************************************************************	
?>
<table cellpadding="0" cellspacing="0" width="100%">
 <tr>
   <td>
		<div style="max-width: 1024px;position: relative;border: medium none !important;">
			<div class="section group">	
				<div style="padding-left:10px; clear: both;  float: left;  padding-left: 10px;">		
						<h1 style="border-bottom: solid 4px #412d1a; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:24px;color:#412d1a;">ORDER DETAILS</h1>
						<div>			
							<div style="clear:both; color: #412D1A !important; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size: 20px; padding: 15px 0 15px 0; text-align:left;">ORDER CONFIRMATION NUMBER: <span style="color:#008530;"><?php echo $order_id;?></span></div>	
							<div class="row">
							<?php
								$ship = $customer['ship_address'];
								$bill = $customer['bill_address'];
								?>								
								
								<div style="font-family: 'Georgia', serif; font-size:14px; font-weight:bold; text-align:left;">
									<div style="clear:both; color: #412D1A !important; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size: 18px; padding: 15px 0 15px 0; text-align:left;"><?php echo ($ship != $bill)? 'Shipping Information' : 'Shipping And Billing';?></div>
									<?php echo format_address($ship, TRUE);?><br/>
									<?php echo $ship['email'];?><br/>
									<?php echo $ship['phone'];?><br>
								</div>
								<?php if($ship != $bill):?>
								<div style="font-family: 'Georgia', serif; font-size:14px; font-weight:bold; text-align:left;">
									<div style="clear:both; color: #412D1A !important; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size: 18px; padding: 15px 0 15px 0; text-align:left;">Billing Information</div>
									<?php echo $bill['name'];?><br/>
									<?php echo $bill['email'];?> <br/>
									<?php echo $bill['phone'];?><br>
									<?php echo $bill['address'];?><br>
									<?php  echo $bill['city'].', '.$bill['state'].' '.$bill['zip'];?>
								</div>
								<?php endif;?>
							</div>
						<?php if(isset($shipping['method']) && $shipping['method']!=''){ ?>
							<div style="clear:both">	
								<div style="font-family: 'Georgia', serif; font-size:14px; font-weight:bold; text-align:left;">
									<div style="clear:both; color: #412D1A !important; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size: 18px; padding: 5px 0 5px 0; text-align:left;"> Shipping Method</div>
									<?php echo $shipping['method']; ?>
								</div>
							<?php } ?>	
								<div style="font-family: 'Georgia', serif; font-size:14px; font-weight:bold; text-align:left;">
									<div style="clear:both; color: #412D1A !important; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size: 18px; padding: 15px 0 15px 0; text-align:left;">Payment Information</div>
									<?php echo $payment['description']; ?>
								</div>
								
							</div>

							<table class="table table-bordered table-striped order-details" style="margin-top:20px;">
								<thead>
									<tr><td colspan="6" style="border-bottom:solid 4px #412d1a;margin: 10px 0;">&nbsp;</td></tr>
									<tr style="border-bottom:solid 4px #412d1a;margin: 10px 0;">
										<th style="width:10%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">UPC</th>
										<th style="width:30%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">NAME</th>
										<th style="width:10%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">PRICE</th>								
											<th style="width:30%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">DESCRIPTION</th>
											<th style="width:10%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">QUANTITY</th>
										<th style="width:8%; font-family: 'BebasNeueRegular',Arial,sans-serif; font-size:16px;font-weight:bold;color: #412D1A;text-align:left;">TOTAL</th>
									</tr>
									<tr><td colspan="6" style="border-bottom:solid 4px #412d1a;margin: 10px 0;">&nbsp;</td></tr>
								</thead>
								<tbody>
								<?php
								$subtotal = 0;
								$subtotal = 0;
								foreach ($this->go_cart->contents() as $cartkey=>$product):?>
									<tr>
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;"><?php echo $product['upc'];?></td>
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;"><?php echo $product['name']; ?></td>
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;"><?php echo format_currency($product['price']);   ?></td>
										
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;">								<?php if(isset($product['attribute_name'])){?>
											<div class="product-descriptions">								
												<div style="padding-top:0px!important;">
													<div style="float:left; text-align:left; min-width: 235px; font-size:14px; padding-left:20px;">
														<?php 
//															$attributeData = explode(',',$product['item_attribute_id']); 
//															$attributeTitle = getAttributeValue($attributeData);
//																foreach($attributeTitle as $attribute)
//																echo '<div>'.$attribute->attribute_name.":</div>";
                                                                                                                echo '<div>'.$product['attribute_name'].":</div>";
														?>
													</div>
													<div style="padding-bottom:0px!important;">
														 <?php 
//															$attributeValue  = explode(',',$product['attribute_value']);
//															foreach($attributeValue as $attributeVal)
//															echo '<div>'.$attributeVal."</div>";
                                                                                                                 echo '<div>'.$product['attribute_value']."</div>";
														?>	
													</div>
												</div>							
										</div>
									 <?php } ?>
									</td>	
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;"><?php echo $product['quantity'];?></td>
										<td valign="top" style="font-family: 'Georgia', serif; font-size:14px;"><?php echo format_currency($product['price']*$product['quantity']); ?></td>
									</tr>
                                                                        <?php
                                                                    if(!empty($product['gift_certificate_value'])){
                                                                        echo '<tr style="border-bottom:solid 4px #412d1a;margin: 10px 0;"><td colspan="6"><div style="clear:both;">';
                                                                     foreach($product['gift_certificate_value'] as $giftItems){	
                                                                                            if(!empty($giftItems['to']))  echo 'To: '.$giftItems['to'];                                                                                                                           if(!empty($giftItems['from']))  echo ', From: '.$giftItems['from'];                                                                                                                   if(!empty($giftItems['message']))  echo ', Message: '.$giftItems['message'];
                                                                            }
                                                                        echo '</div></td></tr>';
                                                                    }
                                                                    ?>
								<?php endforeach; ?>
								 <tr style="border-bottom:solid 4px #412d1a;margin: 10px 0;"><td colspan="6">&nbsp;</td></tr>
								</tbody>
								<tfoot>	
									<tr><td colspan="6" style="border-bottom:solid 4px #412d1a;margin: 10px 0;">&nbsp;</td></tr>
									<tr>
										<td colspan="5" style="font-family: 'Georgia', serif; font-size:16px; text-align:right;padding-right:20px;"><strong>Subtotal</strong></td>
										<td style="font-family: 'Georgia', serif; font-size:16px;text-align:right;"><?php echo format_currency($this->go_cart->subtotal()); ?></td>
									</tr>
									
									<?php if($this->go_cart->coupon_discount() > 0)  : ?> 
									<tr>
										<td colspan="5" style="font-family: 'Georgia', serif; font-size:16px; text-align:right;padding-right:20px;"><strong>Coupon Discount</strong></td>
										<td style="font-family: 'Georgia', serif; font-size:16px;text-align:right;"><?php echo format_currency($this->go_cart->coupon_discount()); ?></td>
									</tr>
									<?php endif; 
									if($this->go_cart->order_tax() > 0) :  ?>
									<tr>
										<td colspan="5" style="font-family: 'Georgia', serif; font-size:16px; text-align:right;padding-right:20px;"><strong>Tax</strong></td>
										<td style="font-family: 'Georgia', serif; font-size:16px;text-align:right;"><?php echo format_currency($this->go_cart->order_tax());?></td>
									</tr>
									<?php endif; 
									 // Show shipping cost if added after taxes
									if($this->go_cart->shipping_cost()>0) : ?>
									<tr>
										<td colspan="5" style="font-family: 'Georgia', serif; font-size:16px; text-align:right;padding-right:20px;"><strong>Shipping</strong></td>
										<td style="font-family: 'Georgia', serif; font-size:16px;text-align:right;"><?php echo format_currency($this->go_cart->shipping_cost()); ?></td>
									</tr>
									<?php endif;?>
									<tr> 
										<td colspan="5" style="font-family: 'Georgia', serif; font-size:16px; text-align:right;padding-right:20px;"><strong>Grand Total</strong></td>
										<td style="font-family: 'Georgia', serif; font-size:16px;text-align:right"><?php echo format_currency($this->go_cart->total()); ?></td>
									</tr>
								</tfoot>
							</table>			
						</div>
					</div>	
			  </div>
			</div>
		</div>	
     </td>
   </tr>
</table>  